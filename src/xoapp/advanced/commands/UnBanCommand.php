<?php

namespace xoapp\advanced\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advanced\restrictions\BanManager;
use xoapp\advanced\utils\SystemUtils;

class UnBanCommand extends Command {

    public function __construct()
    {
        parent::__construct("unban", "");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("moderation.unban")) {
            $player->sendMessage(SystemUtils::PREFIX . "You Don't Have Permissions to use this");
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /unban (player) (temporarily : permanently)");
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /unban (player) (temporarily : permanently)");
            return;
        }

        if (SystemUtils::equals($args[1], "temporarily")) {
            if (!BanManager::getInstance()->isRegistered($args[0], false)) {
                $player->sendMessage(SystemUtils::PREFIX . "This Player is apparently not penalized");
                return;
            }

            BanManager::getInstance()->unregister($args[0], false);
            $player->sendMessage(SystemUtils::PREFIX . "You have removed the ban from §e" . $args[0]);
            return;
        }

        if (SystemUtils::equals($args[1], "permanently")) {
            if (!BanManager::getInstance()->isRegistered($args[0], true)) {
                $player->sendMessage(SystemUtils::PREFIX . "This Player is apparently not penalized");
                return;
            }

            BanManager::getInstance()->unregister($args[0], true);
            $player->sendMessage(SystemUtils::PREFIX . "You have removed the ban from §e" . $args[0]);
            return;
        }

        $player->sendMessage(SystemUtils::PREFIX . "Use /unban (player) (temporarily : permanently)");
    }
}