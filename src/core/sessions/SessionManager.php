<?php

namespace core\sessions;

use core\MythicalPlayer;
use pocketmine\Player;

class SessionManager {

    private static $sessions = [];

    static function open(MythicalPlayer $player) {
        self::$sessions = array_merge(self::$sessions, [$player->getName() => $player]);
    }

    static function get(Player $player) {
        return self::$sessions[$player->getName()] ?? null;
    }

    static function close(Player $player) {
        unset(self::$sessions[$player->getName()]);
    }

}
