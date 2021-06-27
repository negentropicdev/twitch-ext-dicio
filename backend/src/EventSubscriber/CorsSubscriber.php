<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class CorsSubscriber implements EventSubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return [
      KernelEvents::REQUEST => [
        'onRequest', 9999
      ],
      KernelEvents::RESPONSE => [
        'onResponse', 9999
      ]
    ];
  }

  public function onRequest(RequestEvent $event) {
    //Only respond to external requests; ignore sub-requests
    if (!$event->isMasterRequest()) {
      return;
    }

    //For OPTION requests send an empty response
    //CORS headers will be added in onResponse() below when fired
    if ($event->getRequest()->getRealMethod() == 'OPTIONS') {
      $event->setResponse(new Response());
    }
  }

  public function onResponse(ResponseEvent $event) {
    //Ignore sub requests
    if (!$event->isMasterRequest()) {
      return;
    }

    $headers = $event->getResponse()->headers;
    $headers->set('Access-Control-Allow-Origin', '*');
    $headers->set('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    $headers->set('Access-Control-Allow-Header', 'authorization');
  }
}