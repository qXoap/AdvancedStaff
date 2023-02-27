<?php

namespace xoapp\advanced;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use xoapp\advanced\commands\StaffCommand;
use xoapp\advanced\listeners\ItemListener;

class Loader extends PluginBase {
    use SingletonTrait;

    protected function onEnable(): void
    {
        self::setInstance($this);

        $this->registerEvents();
        $this->registerCommands();
    }

    public function registerEvents(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new ItemListener(), $this);
    }

    public function registerCommands(): void
    {
        $this->getServer()->getCommandMap()->registerAll("AdvancedStaff", [
            new StaffCommand()
        ]);
    }
}