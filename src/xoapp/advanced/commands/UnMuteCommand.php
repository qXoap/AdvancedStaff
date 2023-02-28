<?php

namespace xoapp\advanced\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advanced\restrictions\MuteManager;
use xoapp\advanced\utils\SystemUtils;

class UnMuteCommand extends Command {

    public function __construct()
    {
        parent::__construct("unmute", "");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("advanced.unmute")) {
            $player->sendMessage(SystemUtils::PREFIX . "You Don't Have Permissions to use this");
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /unmute (player)");
            return;
        }

        if (!MuteManager::getInstance()->isRegistered($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "This player is not sanctioned!");
            return;
        }

        MuteManager::getInstance()->unregister($args[0]);
        $player->sendMessage(SystemUtils::PREFIX . "You have Unmuted §e" . $args[0]);

    }
}