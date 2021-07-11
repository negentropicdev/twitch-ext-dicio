<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\TwitchService;
use App\Entity\RoverlayApp;

class TwitchController extends AbstractController
{
  var $twitch;

  public function __construct(TwitchService $twitch)
  {
    $this->twitch = $twitch;
  }
  /**
   * @Route("/oauth", name="twitch_oauth")
   */
  public function twitchOAuth(Request $request): Response
  {
    $app = $this->getDoctrine()->getRepository(RoverlayApp::class)->find(1);

    $code = $request->get('code');
    $scope = $request->get('scope');
    $state = $request->get('state');

    if (is_null($code)) {
      //Not a response to a auth request, so let's start the auth request
      $query = http_build_query([
        'client_id' => $app->getApiClientId(),
        'redirect_uri' => 'http://localhost:8082/oauth',
        'response_type' => 'code',
        'scope' => 'user:read:follows user:read:subscriptions'
      ]);

      $url = 'https://id.twitch.tv/oauth2/authorize?' . $query;
      return $this->redirect($url);
    } else {
      //got a code, finish the auth flow
      $opts = [
        'http' => [
          'method' => 'POST'
        ]
      ];

      $query = http_build_query([
        'client_id' => $app->getApiClientId(),
        'client_secret' => $app->getApiClientSecret(),
        'code' => $code,
        'grant_type' => 'authorization_code',
        'redirect_uri' => 'http://localhost:8082/oauth'
      ]);

      $url = 'https://id.twitch.tv/oauth2/token?' . $query;

      $ret = json_decode(file_get_contents($url, false, stream_context_create($opts)), true);

      return new JsonResponse($ret);
    }
  }
}
