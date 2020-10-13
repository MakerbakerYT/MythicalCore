<?php

declare(strict_types = 1);

namespace core\watchdog;

use core\Mythical;

class WatchdogManager {

    /** @var Cryptic */
    private $core;

    /**
     * WatchdogManager constructor.
     *
     * @param Mythical $core
     */
    public function __construct(Mythical $core) {
        $this->core = $core;
        $core->getServer()->getPluginManager()->registerEvents(new WatchdogListener($core), $core);
    }
}
