<?php

declare(strict_types=1);

namespace core\rank;

use core\Mythical;
use core\MythicalPlayer;
use core\discord\DiscordManager;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;

class RankListener implements Listener{

	/** @var Mythical */
	private $core;

	/**
	 * GroupListener constructor.
	 *
	 * @param Mythical $core
	 */
	public function __construct(Mythical $core){
		$this->core = $core;
	}

	/**
	 * @priority HIGHEST
	 * @param PlayerChatEvent $event
	 */
	public function onPlayerChat(PlayerChatEvent $event) : void{
		if($event->isCancelled()){
			return;
		}
		$player = $event->getPlayer();
		if(!$player instanceof MythicalPlayer){
			return;
		}
		$mode = $player->getChatMode();

		$webhook = null;
		if($mode === Mythical::PUBLIC){
			$webhook = "762430854554714122/xlShL59iHAV3LiERtsnan_gCnlfEgiyeMRO9pGK5bbV7Jx0VLOOY8dT6s2_fp9Lp4QZx";
		}elseif($mode === MythicalPlayer::STAFF){
			$webhook = "762430854554714122/xlShL59iHAV3LiERtsnan_gCnlfEgiyeMRO9pGK5bbV7Jx0VLOOY8dT6s2_fp9Lp4QZx";
		}
		if($webhook !== null)
			DiscordManager::postWebhook($webhook, $event->getMessage(), $player->getName() . " (" . $player->getRank()->getName() . ")");

		$faction = $player->getFaction();
		if($faction === null and ($mode === CrypticPlayer::FACTION or $mode === MythicalPlayer::ALLY)){
			$mode = MythicalPlayer::PUBLIC;
			$player->setChatMode($mode);
		}
		if($mode === MythicalPlayer::PUBLIC){
			$event->setFormat($player->getRank()->getChatFormatFor($player, $event->getMessage(), [
				"faction_rank" => $player->getFactionRoleToString(),
				"faction" => ($faction = $player->getFaction()) !== null ? $faction->getName() : "",
				"kills" => $player->getKills()
			]));
			return;
		}
		$event->setCancelled();
		if($mode === MythicalPlayer::STAFF){
			/** @var MythicalPlayer $staff */
			foreach($this->core->getServer()->getOnlinePlayers() as $staff){
				$rank = $staff->getRank();
				if($rank->getIdentifier() >= Rank::TRAINEE and $rank->getIdentifier() <= Rank::OWNER){
					$staff->sendMessage(TextFormat::DARK_GRAY . "[" . $player->getRank()->getColoredName() . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
				}
				if($rank->getIdentifier() === Rank::DEVELOPER){
					$staff->sendMessage(TextFormat::DARK_GRAY . "[" . $player->getRank()->getColoredName() . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
				}
				if($rank->getIdentifier() === Rank::BUILDER){
				$staff->sendMessage(TextFormat::DARK_GRAY . "[" . $player->getRank()->getColoredName() . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
				}
			}
			return;
		}
		if($player->getChatMode() === MythicalPlayer::FACTION){
			$onlinePlayers = $faction->getOnlineMembers();
			foreach($onlinePlayers as $onlinePlayer){
				$onlinePlayer->sendMessage(TextFormat::DARK_GRAY . "[" . TextFormat::BOLD . TextFormat::RED . "FC" . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
			}
		}else{
			$allies = $faction->getAllies();
			$onlinePlayers = $faction->getOnlineMembers();
			foreach($allies as $ally){
				if(($ally === $this->core->getFactionManager()->getFaction($ally)) === null){
					continue;
				}
				$onlinePlayers = array_merge($ally->getOnlineMembers(), $onlinePlayers);
			}
			foreach($onlinePlayers as $onlinePlayer){
				$onlinePlayer->sendMessage(TextFormat::DARK_GRAY . "[" . TextFormat::BOLD . TextFormat::GOLD . "AC" . TextFormat::RESET . TextFormat::DARK_GRAY . "] " . TextFormat::WHITE . $player->getName() . TextFormat::GRAY . ": " . $event->getMessage());
			}
		}
	}

	/**
	 * @priority NORMAL
	 * @param EntityRegainHealthEvent $event
	 */
	public function onEntityRegainHealth(EntityRegainHealthEvent $event) : void{
		if($event->isCancelled()){
			return;
		}
		$player = $event->getEntity();
		if(!$player instanceof MythicalPlayer){
			return;
		}
		$player->setScoreTag(TextFormat::WHITE . round($player->getHealth(), 1) . TextFormat::RED . TextFormat::BOLD . " HP");
	}

	/**
	 * @priority NORMAL
	 * @param EntityDamageEvent $event
	 */
	public function onEntityDamage(EntityDamageEvent $event) : void{
		if($event->isCancelled()){
			return;
		}
		$player = $event->getEntity();
		if(!$player instanceof MythicalPlayer){
			return;
		}
		$player->setScoreTag(TextFormat::WHITE . round($player->getHealth(), 1) . TextFormat::RED . TextFormat::BOLD . " HP");
	}
}
