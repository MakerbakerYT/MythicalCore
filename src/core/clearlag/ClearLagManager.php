<?php

declare(strict_types = 1);

namespace core\clearlag;

use core\clearlag\task\ClearLagTask;
use core\Mythical;

class ClearLagManager{

    public function __construct(){
        Mythical::getInstance()->getScheduler()->scheduleRepeatingTask(new ClearLagTask(), 20);
    }
}
