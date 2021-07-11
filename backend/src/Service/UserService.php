<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Carbon\Carbon;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\TwitchService;

use App\Utility\JWT\TwitchJWT;

class UserService {
  private ?UserRepository $ur = null;

  private ?TwitchService $twitch = null;

  private ?EntityManagerInterface $em = null;

  public function __construct(UserRepository $ur, TwitchService $twitch, EntityManagerInterface $em) {
    $this->ur = $ur;
    $this->twitch = $twitch;
    $this->em = $em;
  }

  public function findOrCreateUser(TwitchJWT $jwt, bool $canCreate = true): ?User {
    $user = null;
    $updated = false;

    //if have user_id, retrieve by that since opaque ID might change
    if ($jwt->isIdentified()) {
      $user = $this->ur->findOneBy(['user_id' => $jwt->userId()]);
    }

    //if not found by user_id, use opaque_id
    if (is_null($user)) {
      $user = $this->ur->findOneBy(['opaque_id' => $jwt->opaqueId()]);
    }

    if (is_null($user)) {
      if ($canCreate) {
        //doesn't exist yet, need to create new user entry
        $user = $jwt->asUser();
        $user->setCreatedAt(Carbon::now('UTC'));
        $updated = true;
      } else {
        return null;
      }
    }

    if (!$user->hasLogin() && $user->isIdentified()) {
      //Get TwitchAPI user info
      $updated = $updated || $this->twitch->populateTwitchUser($user);
    }

    if ($updated) {
      $this->em->persist($user);
      $this->em->flush();
    }

    return $user;
  }
}