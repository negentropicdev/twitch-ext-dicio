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

class ExtController extends AbstractController
{
  private TwitchService $twitch;

  public function __construct(TwitchService $twitch)
  {
    $this->twitch = $twitch;
  }
  /**
   * @Route("/api/ext/auth", name="api_extauth")
   * 
   * Expects a Twitch JWT to match up to a Roverlay user.
   * Returns a JWT that contains Roverlay user info.
   */
  public function extauth(Request $request, UserService $us): Response
  {
    $ret = $this->twitch->verifyExtAuth($request);
    $response = new JsonResponse();

    $json = [
      'msg' => $ret['msg'],
      'user' => null,
      'status' => $ret['status']
    ];

    if ($ret['status'] != 'OK') {
      throw new HttpException(401, $ret['msg']);
    } else {
      //Look for existing user or create new user.
      //We'll return an error if user hasn't shared their ID with the extension
      $user = $us->findOrCreateUser($ret['jwt']);

      if (!$user->getUserId()) {
        throw new HttpException(403, 'Unlinked ID');
      }

      if (!$user->getLogin()) {
        throw new HttpException(502, 'Unable to identify user');
      }
      
      $json['user'] = $user;
    }

    $response->setData($json);
    return $response;
  }
}
