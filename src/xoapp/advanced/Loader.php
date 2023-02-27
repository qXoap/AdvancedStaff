<?php

namespace xoapp\advanced;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

class Loader extends PluginBase {
    use SingletonTrait;

    protected function onEnable(): void
    {
        self::setInstance($this);
    }
}