<?php

namespace xoapp\advanced;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use xoapp\advanced\commands\StaffCommand;
use xoapp\advanced\listeners\ItemListener;
use xoapp\advanced\listeners\PlayerListener;

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
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
    }

    public function registerCommands(): void
    {
        $this->getServer()->getCommandMap()->registerAll("AdvancedStaff", [
            new StaffCommand()
        ]);
    }
}