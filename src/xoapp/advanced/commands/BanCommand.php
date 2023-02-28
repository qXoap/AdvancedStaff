<?php

namespace xoapp\advanced\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use xoapp\advanced\restrictions\BanManager;
use xoapp\advanced\restrictions\TimeManager;
use xoapp\advanced\utils\SystemUtils;

class BanCommand extends Command {

    public function __construct()
    {
        parent::__construct("ban", "");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("moderation.ban")) {
            $player->sendMessage(SystemUtils::PREFIX . "You Don't Have Permissions to use this");
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /ban (player) (reason) (time = null)");
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /ban (player) (reason) (time = null)");
            return;
        }

        if (!isset($args[2])) {
            if (BanManager::getInstance()->isRegistered($args[0], true)) {
                $player->sendMessage(SystemUtils::PREFIX . "Apparently this player is already sanctioned");
                return;
            }

            BanManager::getInstance()->register($args[0], $player->getName(), $args[1], true, "Undefined");
            SystemUtils::broadcastMessage(SystemUtils::PREFIX . "Player §e" . $args[0] . " §7Was Permanently Banned for §e" . $args[1]);

            if (($victim = Server::getInstance()->getPlayerExact($args[0])) instanceof Player) {
                $victim->kick("§7You have been Permanently Banned\n§7Reason: (§e" . $args[1] . " §7: §e" . $player->getName() . "§7)\n§7Expiry in §eUndefined");
            }
            return;
        }

        if (BanManager::getInstance()->isRegistered($args[0], false)) {
            $player->sendMessage(SystemUtils::PREFIX . "Apparently this player is already sanctioned");
            return;
        }

        if (!in_array(TimeManager::intToString($args[2]), TimeManager::VALID_FORMATS)) {
            $player->sendMessage(SystemUtils::PREFIX . "Please Put a valid time format");
            return;
        }

        if (!is_numeric($args[2][0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Please Put a valid time format");
            return;
        }

        $time = TimeManager::getFormatTime(TimeManager::stringToInt($args[2]), $args[2]);

        BanManager::getInstance()->register($args[0], $player->getName(), $args[1], false, $time);
        SystemUtils::broadcastMessage(SystemUtils::PREFIX . "Player §e" . $args[0] . " §7Was Temporarily Banned for §e" . $args[1]);

        if (($victim = Server::getInstance()->getPlayerExact($args[0])) instanceof Player) {
            $victim->kick("§7You have been Temporarily Banned\n§7Reason: (§e" . $args[1] . " §7: §e" . $player->getName() . "§7)\n§7Expiry in: §e" . TimeManager::getTimeLeft($time));
        }
    }
}