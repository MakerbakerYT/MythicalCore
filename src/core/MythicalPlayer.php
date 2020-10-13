<?php

namespace core;

use core\data\Naddy;
use pocketmine\Player;

class MythicalPlayer {

    /** @var Player */
    private $player;
    /** @var Naddy */
    private $mythicalData;

    public function __construct(Player $player, Naddy $data)
    {
        $this->player = $player;
        $this->mythicalData = $data;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @return Naddy
     */
    public function getMythicalData(): Naddy
    {
        return $this->mythicalData;
    }

}
