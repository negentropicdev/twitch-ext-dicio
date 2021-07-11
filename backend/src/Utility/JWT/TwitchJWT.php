<?php

namespace App\Utility\JWT;

use App\Entity\User;

class TwitchJWT {
  var $tokenStr = null;
  var $decoded = null;

  var $identified = false;

  var $user = null;

  public function __construct($tokenStr, $decoded) {
    $this->tokenStr = $tokenStr;
    $this->decoded = $decoded;

    $this->identified = array_key_exists('user_id', $decoded) && $decoded['user_id'] != '';
  }

  public function getTokenStr(): string {
    return $this->tokenStr;
  }

  public function value(string $key): ?string {
    if (array_key_exists($key, $this->decoded)) {
      return $this->decoded[$key];
    }

    return null;
  }

  public function isIdentified(): bool {
    return $this->identified;
  }

  public function opaqueId(): ?string {
    return $this->value('opaque_user_id');
  }

  public function userId(): ?string {
    return $this->value('user_id');
  }

  public function role(): ?string {
    return $this->value('role');
  }

  public function channelId(): ?string {
    return $this->value('channel_id');
  }

  public function asUser(): User {
    if ($this->user == null) {
      $this->user = new User();
      $this->user->setOpaqueId($this->opaqueId());
      $this->user->setUserId($this->userId());
    }

    return $this->user;
  }
}