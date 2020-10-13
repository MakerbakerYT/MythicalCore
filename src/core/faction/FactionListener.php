<?php

declare(strict_types = 1);

namespace core\faction;

use core\Mythical;
use core\MythicalPlayer;
use core\translation\Translation;
use core\translation\TranslationException;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;

class FactionListener implements Listener {

    /** @var Mythical */
    private $core;

    /**
     * MythicalListener constructor.
     *
     * @param Cryptic $core
     */
    public function __construct(Mythical $core) {
        $this->core = $core;
    }

    /**
     * @priority HIGHEST
     * @param PlayerJoinEvent $event
     */
    public function onPlayerJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        $faction = $player->getFaction();
        if($faction === null) {
            return;
        }
        foreach($faction->getOnlineMembers() as $member) {
            $member->sendMessage(Translation::GREEN . "{$player->getName()} is now online!");
        }
    }

    /**
     * @priority HIGHEST
     * @param PlayerQuitEvent $event
     */
    public function onPlayerQuit(PlayerQuitEvent $event): void {
        $player = $event->getPlayer();
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        $faction = $player->getFaction();
        if($faction === null) {
            return;
        }
        foreach($faction->getOnlineMembers() as $member) {
            $member->sendMessage(Translation::RED . "{$player->getName()} is now offline!");
        }
    }

    /**
     * @priority HIGH
     * @param PlayerDeathEvent $event
     */
    public function onPlayerDeath(PlayerDeathEvent $event) {
        $player = $event->getPlayer();
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        $cause = $player->getLastDamageCause();
        if($cause instanceof EntityDamageByEntityEvent) {
            $damager = $cause->getDamager();
            if($damager instanceof MythicalPlayer) {
                $faction = $player->getFaction();
                if($faction !== null) {
                    $faction->subtractStrength(Faction::POWER_PER_KILL);
                }
                $damagerFaction = $damager->getFaction();
                if($damagerFaction !== null) {
                    $damagerFaction->addStrength(Faction::POWER_PER_KILL);
                }
                return;
            }
        }
    }

    /**
     * @priority LOWEST
     * @param EntityDamageEvent $event
     *
     * @throws TranslationException
     */
    public function onEntityDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();
        if($entity instanceof MythicalPlayer) {
            $faction = $entity->getFaction();
            if($faction === null) {
                return;
            }
            if($event instanceof EntityDamageByEntityEvent) {
                $damager = $event->getDamager();
                if(!$damager instanceof CrypticPlayer) {
                    return;
                }
                $damagerFaction = $damager->getFaction();
                if($damagerFaction === null) {
                    return;
                }
                if($faction->isInFaction($damager) or $faction->isAlly($damagerFaction)) {
                    $damager->sendMessage(Translation::getMessage("attackFactionAssociate"));
                    $event->setCancelled();
                    return;
                }
            }
        }
    }

    /**
     * @priority LOWEST
     *
     * @param BlockPlaceEvent $event
     *
     * @throws TranslationException
     */
    public function onBlockPlace(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if(!$player instanceof MythicalPlayer) {
            return;
        }
        $claim = $this->core->getFactionManager()->getClaimInPosition($block->asPosition());
        if($claim === null) {
            return;
        }
        $faction = $player->getFaction();
        if($faction === null or $claim->getFaction()->getName() !== $faction->getName() or $player->getFactionRole() === Faction::RECRUIT) {
            $player->sendMessage(Translation::getMessage("editClaimNotAllowed"));
            $event->setCancelled();
            return;
        }
    }

    /**
     * @priority LOWEST
     * @param BlockBreakEvent $event
     *
     * @throws TranslationException
     */
    public function onBlockBreak(BlockBreakEvent $event): void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if(!$player instanceof CrypticPlayer) {
            return;
        }
        $claim = $this->core->getFactionManager()->getClaimInPosition($block->asPosition());
        if($claim === null) {
            return;
        }
        $faction = $player->getFaction();
        if($faction === null or $claim->getFaction()->getName() !== $faction->getName() or $player->getFactionRole() === Faction::RECRUIT) {
            $player->sendMessage(Translation::getMessage("editClaimNotAllowed"));
            $event->setCancelled();
            return;
        }
    }
}
