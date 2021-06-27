<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

use Firebase\JWT\JWT;

class TwitchService {
  public $api_client = '';
  var $api_secret = '';
  public $ext_client = '';
  var $ext_secret = '';

  public function __construct($api_client, $api_secret, $ext_client, $ext_secret) {
    //These are injected from the environment via services.yaml configuration
    $this->api_client = $api_client;
    $this->api_secret = $api_secret;
    $this->ext_client = $ext_client;
    $this->ext_secret = $ext_secret;

    JWT::$leeway = 300; // 5 minutes of leeway for jwt expiration
  }

  /**
   * Returns decoded authorization header if valid
   *
   * @param Request $request The page request that should include the Bearer Authorization header
   * @return object The decoded JWT if valid, null otherwise
   */
  public function verifyReqAuth(Request $request) {
    $bearer = $request->headers->get('Authorization');
    $matches = [];
    $jwt = null;

    $r = preg_match('/\s*Bearer\s*(.+)/', $bearer, $matches);

    if ($r) {
      $jwt = $matches[1];
    } else {
      return 'Authorization not found';
    }

    try {
      $decoded = JWT::decode($jwt, $this->ext_secret, array('HS256'));
      return $decoded;
    } catch (\Exception $e) {
      return $e->__toString();
    }

    return null;
  }
}