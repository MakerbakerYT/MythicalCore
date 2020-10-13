<?php

declare(strict_types = 1);

namespace core\entity\npc;

use core\Mythical;
use core\MythicalPlayer;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;

class NPCListener implements Listener {

    /** @var Mythical */
    private $core;

    /**
     * NPCListener constructor.
     *
     * @param Mythical $core
     */
    public function __construct(Mythical $core) {
        $this->core = $core;
    }

    /**
     * @priority NORMAL
     *
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        foreach($this->core->getEntityManager()->getNPCs() as $npc) {
            $npc->spawnTo($player);
        }
    }

    /**
     * @priority NORMAL
     *
     * @param PlayerMoveEvent $event
     */
    public function onPlayerMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        foreach($this->core->getEntityManager()->getNPCs() as $npc) {
            if($npc->getPosition()->getLevel()->getFolderName() === $event->getPlayer()->getLevel()->getFolderName()) {
                if($npc->getPosition()->distance($player) <= 20) {
                    $npc->move($player);
                    continue;
                }
            }
        }
    }

    /**
     * @priority NORMAL
     *
     * @param DataPacketReceiveEvent $event
     */
    public function onDataPacketReceive(DataPacketReceiveEvent $event): void {
        $pk = $event->getPacket();
        $player = $event->getPlayer();
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        if($pk instanceof InventoryTransactionPacket and $pk->transactionType === InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY) {
            $npc = $this->core->getEntityManager()->getNPC($pk->trData->entityRuntimeId);
            if($npc === null) {
                return;
            }
            $callable = $npc->getCallable();
            $callable($player);
        }
    }
}
