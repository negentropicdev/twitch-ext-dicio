<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\TwitchService;
use App\Repository\RoverlayAppRepository;
use App\Entity\RoverlayApp;

class TestController extends AbstractController
{
  var $twitch;

  public function __construct(TwitchService $twitch)
  {
    $this->twitch = $twitch;
  }
}
