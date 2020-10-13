<?php

declare(strict_types = 1);

namespace core\update\task;

use core\Mythical;
use core\MythicalPlayer;
use libs\utils\UtilsException;
use pocketmine\scheduler\Task;

class UpdateTask extends Task {

    /** @var Mythical */
    private $core;

    /** @var MythicalPlayer[] */
    private $players = [];

    /**
     * UpdateTask constructor.
     *
     * @param Mythical $core
     */
    public function __construct(Mythical $core) {
        $this->core = $core;
        $this->players = $core->getServer()->getOnlinePlayers();
    }

    /**
     * @param int $tick
     *
     * @throws UtilsException
     */
    public function onRun(int $tick) {
        if(empty($this->players)) {
            $this->players = $this->core->getServer()->getOnlinePlayers();
        }
        $player = array_shift($this->players);
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        if($player->isOnline() === false) {
            return;
        }
        $this->core->getUpdateManager()->updateScoreboard($player);
    }
}
