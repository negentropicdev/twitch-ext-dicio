<?php

namespace App\Service;

class ConsoleService {
  public function addText(string $text, bool $endOfLine = true, string $color = null, int $delayBefore = 0) {
    $entry = [
      'type' => 'text',
      'text' => $text,
      'eol' => $endOfLine,
      'delay' => $delayBefore
    ];

    if ($color != null) {
      $entry['color'] = $color;
    }

    return $entry;
  }
}