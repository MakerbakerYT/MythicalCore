<?php

namespace core\gamble;

use core\Mythical;
use core\MythicalPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class GambleListener implements Listener {

    /** @var Cryptic */
    private $core;

    /**
     * GambleListener constructor.
     *
     * @param Mythical $core
     */
    public function __construct(Mythical $core) {
        $this->core = $core;
    }

    /**
     * @priority NORMAL
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        $this->core->getGambleManager()->createRecord($player);
    }

    /**
     * @priority NORMAL
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof CrypticPlayer) {
            return;
        }
        $this->core->getGambleManager()->removeCoinFlip($player);
    }
}
