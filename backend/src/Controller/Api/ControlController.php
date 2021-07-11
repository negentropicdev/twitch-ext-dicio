<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

use App\Service\TwitchService;
use App\Service\UserService;
use App\Service\ConsoleService;

class ControlController extends AbstractController
{
  private TwitchService $twitch;
  private UserService $us;

  public function __construct(TwitchService $twitch, ConsoleService $cs, UserService $us)
  {
    $this->twitch = $twitch;
    $this->us = $us;
  }

  /**
   * @Route("/api/control/values", methods={"GET"}, name="control_values_get")
   */
  public function values_get(Request $request): Response {
    return new Response();
  }

  /**
   * @Route("/api/control/values", methods={"POST"}, name="control_values_set")
   */
  public function values_post(Request $request): Response {
    $values = json_decode($request->getContent(), true);

    $len = count($values['vals']);

    for ($i = 0; $i < $len; ++$i) {

    }
    //and always return the current values as would be seen by a client app
    return $this->values_get($request);
  }

  /**
   * @Route("/api/control/config", name="control_config")
   */
  public function config(Request $request): Response {
    return new Response();
  }
}
