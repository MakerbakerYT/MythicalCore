<?php

declare(strict_types = 1);

namespace core\update;

use core\Mythical;
use core\MythicalPlayer;
use core\update\task\UpdateTask;
use libs\utils\UtilsException;
use pocketmine\item\Armor;
use pocketmine\utils\TextFormat;

class UpdateManager {

    /** @var Mythical */
    private $core;

    /**
     * UpdateManager constructor.
     *
     * @param Mythical $core
     */
    public function __construct(Mythical $core) {
        $this->core = $core;
        $core->getScheduler()->scheduleRepeatingTask(new UpdateTask($core), 1);
    }

    /**
     * @param MythicalPlayer $player
     *
     * @throws UtilsException
     */
    public function updateScoreboard(MythicalPlayer $player): void {
        $scoreboard = $player->getScoreboard();
        if($scoreboard === null) {
            return;
        }
        if($scoreboard->isSpawned() === false) {
            $scoreboard->spawn(Mythical::SERVER_NAME);
            return;
        }
        if($scoreboard->getLine(1) === null) {
            $scoreboard->setScoreLine(1, "");
        }
        $scoreboard->setScoreLine(2, $player->getRank()->getColoredName() . TextFormat::RESET . TextFormat::WHITE . " " . $player->getName());
        if($scoreboard->getLine(3) === null) {
            $scoreboard->setScoreLine(3, "");
        }
        if($player->isUsingPVPHUD() === false) {
            $scoreboard->setScoreLine(4, TextFormat::RESET . TextFormat::BLUE . "Kills: " . TextFormat::RESET . TextFormat::WHITE . $player->getKills());
            $scoreboard->setScoreLine(5, TextFormat::RESET . TextFormat::BLUE . "Balance: " . TextFormat::RESET . TextFormat::WHITE . "$" . number_format($player->getBalance()));
            $scoreboard->setScoreLine(6, TextFormat::RESET . TextFormat::BLUE . "Lucky Blocks: " . TextFormat::RESET . TextFormat::WHITE . $player->getLuckyBlocksMined());
            if($scoreboard->getLine(7) === null) {
                $scoreboard->setScoreLine(7, "");
            }
            if($scoreboard->getLine(8) === null) {
                $scoreboard->setScoreLine(8, TextFormat::RESET . TextFormat::AQUA . "Vote Link Coming Soon");
            }
            if($scoreboard->getLine(9) === null) {
                $scoreboard->setScoreLine(9, TextFormat::RESET . TextFormat::AQUA . "BuyCraft Coming Soon");
            }
            return;
        }
        $helmet = $player->getArmorInventory()->getHelmet();
        $durability = "Not detected.";
        if($helmet instanceof Armor) {
            $durability = $helmet->getMaxDurability() - $helmet->getDamage();
        }
        $scoreboard->setScoreLine(4, TextFormat::RESET . TextFormat::DARK_RED . "Helmet: " . TextFormat::RESET . TextFormat::WHITE . $durability);
        $chestplate = $player->getArmorInventory()->getChestplate();
        $durability = "Not detected.";
        if($chestplate instanceof Armor) {
            $durability = $chestplate->getMaxDurability() - $chestplate->getDamage();
        }
        $scoreboard->setScoreLine(5, TextFormat::RESET . TextFormat::DARK_RED . "Chestplate: " . TextFormat::RESET . TextFormat::WHITE . $durability);
        $leggings = $player->getArmorInventory()->getLeggings();
        $durability = "Not detected.";
        if($leggings instanceof Armor) {
            $durability = $leggings->getMaxDurability() - $leggings->getDamage();
        }
        $scoreboard->setScoreLine(6, TextFormat::RESET . TextFormat::DARK_RED . "Leggings: " . TextFormat::RESET . TextFormat::WHITE . $durability);
        $boots = $player->getArmorInventory()->getBoots();
        $durability = "Not detected.";
        if($boots instanceof Armor) {
            $durability = $boots->getMaxDurability() - $boots->getDamage();
        }
        $scoreboard->setScoreLine(7, TextFormat::RESET . TextFormat::DARK_RED . "Boots: " . TextFormat::RESET . TextFormat::WHITE . $durability);
        if($scoreboard->getLine(8) !== "") {
            $scoreboard->setScoreLine(8, "");
        }
        $scoreboard->setScoreLine(9, TextFormat::RESET . TextFormat::YELLOW . "GA CD: " . TextFormat::RESET . TextFormat::WHITE . $this->core->getCombatManager()->getGoldenAppleCooldown($player) . "s");
        $scoreboard->setScoreLine(10, TextFormat::RESET . TextFormat::LIGHT_PURPLE . "EGA CD: " . TextFormat::RESET . TextFormat::WHITE . $this->core->getCombatManager()->getGodAppleCooldown($player) . "s");
        $scoreboard->setScoreLine(11, TextFormat::RESET . TextFormat::DARK_PURPLE . "EP CD: " . TextFormat::RESET . TextFormat::WHITE . $this->core->getCombatManager()->getEnderPearlCooldown($player) . "s");
    }
}
