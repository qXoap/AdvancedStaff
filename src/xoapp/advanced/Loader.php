<?php

namespace xoapp\advanced;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\SingletonTrait;
use xoapp\advanced\anticheat\checks\Nuke;
use xoapp\advanced\anticheat\checks\Packets;
use xoapp\advanced\anticheat\checks\Toolbox;
use xoapp\advanced\anticheat\session\ProtoypeSession;
use xoapp\advanced\commands\BanIpCommand;
use xoapp\advanced\commands\BanListCommand;
use xoapp\advanced\commands\UnMuteCommand;
use xoapp\advanced\anticheat\checks\AutoClick;
use xoapp\advanced\anticheat\checks\Reach;
use xoapp\advanced\anticheat\command\PrototypeCommand;
use xoapp\advanced\anticheat\listeners\PrototypeListener;
use xoapp\advanced\commands\BanCommand;
use xoapp\advanced\commands\MuteCommand;
use xoapp\advanced\commands\StaffCommand;
use xoapp\advanced\commands\UnBanCommand;
use xoapp\advanced\listeners\ChatListener;
use xoapp\advanced\listeners\ItemListener;
use xoapp\advanced\listeners\LoginListener;
use xoapp\advanced\listeners\PlayerListener;
use xoapp\advanced\listeners\StaffListener;
use xoapp\advanced\session\SessionFactory;
use xoapp\advanced\utils\SystemUtils;

class Loader extends PluginBase {
    use SingletonTrait;

    protected function onLoad(): void
    {
        SystemUtils::unregisterCommand(["ban", "unban", "ban-ip", "banlist"]);

        if (!is_dir($this->getDataFolder() . "/restrictions/")) {
            @mkdir($this->getDataFolder() . "/restrictions/");
        }
    }

    protected function onEnable(): void
    {
        self::setInstance($this);

        $this->registerEvents();
        $this->registerCommands();

        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (): void {
            foreach ($this->getServer()->getOnlinePlayers() as $onlinePlayer) {

                if (SessionFactory::getInstance()->isRegistered($onlinePlayer)) {
                    $onlinePlayer->getEffects()->add(new EffectInstance(VanillaEffects::NIGHT_VISION(), null, 0, false));
                }
            }
        }), 20);

        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function (): void {
            foreach ($this->getServer()->getOnlinePlayers() as $onlinePlayer) {
                if (ProtoypeSession::getInstance()->isRegisterValue($onlinePlayer)) {
                    ProtoypeSession::getInstance()->resetValue($onlinePlayer);
                }
            }
        }), 800);
    }

    public function registerEvents(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new ItemListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PrototypeListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new LoginListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ChatListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Reach(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new StaffListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new AutoClick(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Toolbox(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Nuke(), $this);
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
            new MuteCommand(),
            new BanIpCommand()
        ]);
    }
}