<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\TwitchService;

class TestController extends AbstractController
{
  var $twitch;

  public function __construct(TwitchService $twitch)
  {
    $this->twitch = $twitch;
  }
  /**
   * @Route("/api/testauth", name="api_testauth")
   */
  public function index(Request $request): Response
  {
    $ret = $this->twitch->verifyReqAuth($request);
    $response = new JsonResponse();

    if (is_null($ret)) {
      $response->setData(['err' => 'Unknown error']);
    } else if (is_string($ret)) {
      $response->setData(['err' => $ret]);
    } else {
      $response->setData((array)$ret);
    }

    return $response;
  }
}
