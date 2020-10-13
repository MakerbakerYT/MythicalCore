<?php

declare(strict_types = 1);

namespace core\envoy\event;

use core\envoy\Reward;
use core\MythicalPlayer;
use pocketmine\event\player\PlayerEvent;

class EnvoyClaimEvent extends PlayerEvent {

    /** @var Reward[] */
    private $items = [];

    /**
     * ItemBuyEvent constructor.
     *
     * @param MythicalPlayer $player
     * @param Reward[]      $items
     */
    public function __construct(MythicalPlayer $player, array $items) {
        $this->player = $player;
        $this->items = $items;
    }

    /**
     * @return Reward[]
     */
    public function getItems(): array {
        return $this->items;
    }

    /**
     * @param Reward[] $items
     */
    public function setItems(array $items): void {
        $this->items = $items;
    }
}
