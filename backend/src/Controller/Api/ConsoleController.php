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

class ConsoleController extends AbstractController
{
  private TwitchService $twitch;
  private ConsoleService $console;
  private UserService $us;

  public function __construct(TwitchService $twitch, ConsoleService $cs, UserService $us)
  {
    $this->twitch = $twitch;
    $this->console = $cs;
    $this->us = $us;
  }
  /**
   * @Route("/api/console/command", name="console_command")
   * 
   * Expects a Twitch JWT to match up to a Roverlay user.
   * Returns a JWT that contains Roverlay user info.
   */
  public function command(Request $request, UserService $us): Response
  {
    $ret = $this->twitch->verifyExtAuth($request);

    if ($ret['jwt'] != null) {
      $user = $us->findOrCreateUser($ret['jwt'], false);
    }

    $json = [
      'msg' => $ret['msg'],
      'status' => $ret['status'],
      'console' => []
    ];

    if ($ret['status'] != 'OK') {
      throw new HttpException(401, $ret['msg']);
    } else {
      //Look for existing user or create new user.
      //We'll return an error if user hasn't shared their ID with the extension
      $user = $us->findOrCreateUser($ret['jwt'], false);

      if (!$user->getUserId()) {
        throw new HttpException(403, 'Unlinked ID');
      }

      if (!$user->getLogin()) {
        throw new HttpException(502, 'Unable to identify user');
      }

      $req = json_decode($request->getContent(), true);
      
      $console = $this->dispatchCommand($req['cmd']);
      if ($console != null) {
        $json['console'] = $console;
      } else {
        $json['msg'] = 'Invalid / Unknown command';
      }
    }

    return new JsonResponse($json);
  }

  function dispatchCommand(array $cmd): ?array {
    $output = [
      'parsed' => $cmd,
      'action' => []
    ];

    switch ($cmd[0]) {
      case 'help':
        $output['action'][] = $this->console->addText('help has not been configured for this extension', true, '#EA6');
        break;
    }

    return $output;
  }

  /**
   * Separates text entry into commands with argument string.
   *
   * @param string $cmd The full string as entered. Separate commands can be separated with ;
   * @return array Has keys 'cmd' which is the first word in the command and 'arg' which is the full argument string as entered, null if none.
   */
  function parseCommands(string $cmd): array {
    $cmds = [];
    $len = strlen($cmd);

    $inQuotes = false; //ignore '\"' when inside quoted string
    $lastChar = '';
    $parseCmd = true; //need to find first space or ; to separate command
    $curArg = '';
    $skipChar = false;
    $cur = [
      'cmd' => '',
      'args' => []
    ];

    for ($i = 0; $i < $len; ++$i) {
      $c = substr($cmd, $i, 1);
      $skipChar = false;

      if ($c == ' ') {
        if ($parseCmd) {
          //separator between cmd and args
          $parseCmd = false;
          $skipChar = true;
        } else if (!$inQuotes) {
          $cur['args'][] = $curArg;
          $curArg = '';
          $skipChar = true;
        }
      }

      if ($c == '"') {
        if ($inQuotes) {
          if ($lastChar != '\\') {
            $inQuotes = false;
          }
        } else {
          $inQuotes = true;
        }
      }

      if ($c == ';') {
        if ($curArg != '') {
          $cur['args'][] = $curArg;
        }

        $cmds[] = $cur;
        $cur = [
          'cmd' => '',
          'args' => []
        ];
        $parseCmd = true;
        $curArg = '';
        $skipChar = true;
      }

      if (!$skipChar) {
        if ($parseCmd) {
          $cur['cmd'] .= $c;
        } else {
          $curArg .= $c;
        }
      }

      $lastChar = $c;
    }

    return $cmds;
  }
}
