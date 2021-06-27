<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\TwitchService;

class HomeController extends AbstractController
{
  var $twitch;

  public function __construct(TwitchService $twitch)
  {
    $this->twitch = $twitch;
  }
  /**
   * @Route("/", name="home")
   */
  public function index(): Response
  {
    return $this->render('home/index.html.twig', [
      'ext_client' => $this->twitch->ext_client,
      'api_client' => $this->twitch->api_client
    ]);
  }
}
