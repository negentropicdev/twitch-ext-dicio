<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Carbon\Carbon;

use App\Utility\JWT\TwitchJWT;
use App\Entity\RoverlayApp;
use App\Entity\User;
use App\Repository\RoverlayAppRepository;

class TwitchService {
  public $api_client = '';
  var $api_secret = '';
  public $ext_client = '';
  var $ext_secret = '';

  public RoverlayApp $app;
  private EntityManagerInterface $em;

  private HttpClientInterface $http;

  var $apiBase = 'https://api.twitch.tv/helix/';

  public function __construct(RoverlayAppRepository $appRepo, EntityManagerInterface $em, HttpClientInterface $http) {
    $this->http = $http;
    $this->em = $em;
    $this->app = $appRepo->find(1);

    if ($this->app) {
      $this->api_client = $this->app->getApiClientId();
      $this->api_secret = $this->app->getApiClientSecret();

      $this->ext_client = $this->app->getExtClientId();
      $this->ext_secret = base64_decode($this->app->getExtClientSecret());
    } else {
      throw new HttpException(500, 'Missing app credentials.');
    }

    JWT::$leeway = 300; // 5 minutes of leeway for jwt expiration
  }

  public function populateTwitchUser(User &$user): bool {
    if ($user->getUserId()) {
      $response = '';

      if ($this->apiRequest($response, 'users', 'GET', ['id' => $user->getUserId()])) {
        $twitchUser = json_decode($response, true)['data'][0];

        $user->setLogin($twitchUser['login']);
        $user->setDisplayName($twitchUser['display_name']);
        $user->setBroadcasterType($twitchUser['broadcaster_type']);
        $user->setProfileImageUrl($twitchUser['profile_image_url']);

        return true;
      }
    }

    return false;
  }

  private function apiRequest(string &$response, string $endpoint, string $method = 'GET', array $query = null, string $content = null): bool {
    if (!$this->app->getApiAccessToken()) {
      if (!$this->doAppAuth()) {
        return false;
      }
    }

    $opts = [
      'headers' => [
        'Authorization' => 'Bearer ' . $this->app->getApiAccessToken(),
        'Client-Id' => $this->app->getApiClientId()
      ]
    ];

    if (!is_null($query)) {
      $opts['query'] = $query;
    }

    if (!is_null($content)) {
      $opts['body'] = $content;
    }

    //can retry if get unauth response and are able to reauth inline
    $retry = false;
    do {
      $r = $this->http->request(
        'GET', $this->apiBase . 'users',
        $opts
      );
      
      $code = $r->getStatusCode();

      if ($code == 200) {
        $response = (string)$r->getContent();
        return true;
      } else if ($retry) {
        $retry = false; //Already retried, fail now
      } else if ($code == 401) {
        $retry = $this->doAppAuth(); //Retry if able to reauth
      }
    } while ($retry);

    return false;
  }

  public function doAppAuth(): bool {
    $opts = [
      'query' => [
      'client_id' => $this->app->getApiClientId(),
      'client_secret' => $this->app->getApiClientSecret(),
      'grant_type' => 'client_credentials',
      'scope' => 'channel:manage:redemptions channel:read:redemptions channel:read:subscriptions moderation:read user:read:follows user:read:subscriptions'
    ]];

    $url = 'https://id.twitch.tv/oauth2/token';

    //Start expires timer before request to make sure we expire before twitch.
    $now = Carbon::now('UTC');
    $r = $this->http->request(
      'POST',
      $url,
      $opts
    );

    $code = $r->getStatusCode();

    if ($code != 200) {
      //Didn't get a valid response
      return false;
    }

    $auth = $r->toArray();

    if (!array_key_exists('access_token', $auth) || $auth['access_token'] == '') {
      return false;
    }

    $this->app->setApiLastAuth($now->clone());
    $this->app->setApiExpiresAt($now->addSeconds($auth['expires_in']));

    $this->app->setApiAccessToken($auth['access_token']);
    $this->app->setScopes($auth['scope']);

    //Update auth in database
    $this->em->persist($this->app);
    $this->em->flush();

    return true;
  }

  /**
   * Returns decoded extension viewer authorization header if valid
   *
   * @param Request $request The page request that should include the Bearer Authorization header
   * @return array The decoded JWT in 'jwt' if valid, null otherwise. 'status' is 'OK' or 'FAIL' with a reason in 'msg'
   */
  public function verifyExtAuth(Request $request) {
    $bearer = $request->headers->get('authorization');
    $matches = [];
    $jwt = null;

    $r = preg_match('/Bearer\s*(.+)/', $bearer, $matches);

    if ($r) {
      $jwt = $matches[1];
    } else {
      return [
        'status' => 'FAIL',
        'jwt' => null,
        'msg' => 'Missing authorization.'
      ];
    }

    try {
      $decoded = (array)JWT::decode($jwt, $this->ext_secret, array('HS256'));
      
      return [
        'status' => 'OK',
        'jwt' => new TwitchJWT($jwt, $decoded),
        'msg' => 'success'
      ];
    } catch (\Exception $e) {
      return [
        'status' => 'FAIL',
        'jwt' => null,
        'msg' => 'Invalid authorization.'
      ];
    }
  }
}