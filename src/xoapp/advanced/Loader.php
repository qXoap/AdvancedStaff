<?php

namespace xoapp\advanced;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use system\moderation\restrictions\commands\BanListCommand;
use system\moderation\restrictions\commands\UnMuteCommand;
use xoapp\advanced\anticheat\checks\AutoClick;
use xoapp\advanced\anticheat\checks\Reach;
use xoapp\advanced\anticheat\command\PrototypeCommand;
use xoapp\advanced\anticheat\listeners\PrototypeListener;
use xoapp\advanced\commands\BanCommand;
use xoapp\advanced\commands\MuteCommand;
use xoapp\advanced\commands\StaffCommand;
use xoapp\advanced\commands\UnBanCommand;
use xoapp\advanced\listeners\ItemListener;
use xoapp\advanced\listeners\PlayerListener;
use xoapp\advanced\utils\SystemUtils;

class Loader extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void
    {
        SystemUtils::unregisterCommand(["ban", "unban", "ban-ip", "banlist"]);
    }

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
        $this->getServer()->getPluginManager()->registerEvents(new PrototypeListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Reach(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new AutoClick(), $this);
    }

    public function registerCommands(): void
    {
        $this->getServer()->getCommandMap()->registerAll("AdvancedStaff", [
            new StaffCommand(),
            new PrototypeCommand(),
            new BanCommand(),
            new UnBanCommand(),
            new UnMuteCommand(),
            new BanListCommand(),
            new MuteCommand()
        ]);
    }
}