<?php

namespace xoapp\advanced\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use xoapp\advanced\restrictions\BanManager;
use xoapp\advanced\restrictions\PlayerManager;
use xoapp\advanced\restrictions\TimeManager;
use xoapp\advanced\utils\SystemUtils;

class BanIpCommand extends Command {

    public function __construct()
    {
        parent::__construct("ban-ip", "");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("advanced.ban-ip")) {
            $player->sendMessage(SystemUtils::PREFIX . "You Don't Have Permissions to use this");
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /ban-ip (player) (reason) (time = null)");
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /ban-ip (player) (reason) (time = null)");
            return;
        }

        if (!isset($args[2])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /ban-ip (player) (reason) (time = null)");
            return;
        }

        if (BanManager::getInstance()->isAddressRegistered($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Apparently this player address is already sanctioned");
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

        if (($vic = Server::getInstance()->getPlayerExact($args[0])) instanceof Player) {
            if (!PlayerManager::isRegistered($vic)) {
                PlayerManager::register($vic);
            }
        }

        $time = TimeManager::getFormatTime(TimeManager::stringToInt($args[2]), $args[2]);

        BanManager::getInstance()->registerAddress(PlayerManager::getAddress($args[0]), $args[0], $player->getName(), $args[1], $time);
        SystemUtils::broadcastMessage(SystemUtils::PREFIX . "Player §e" . $args[0] . " §7Was Temporarily IP Banned for §e" . $args[1]);

        if (($victim = Server::getInstance()->getPlayerExact($args[0])) instanceof Player) {
            $victim->kick("§7You have been Temporarily IP Banned\n§7Reason: (§e" . $args[1] . " §7: §e" . $player->getName() . "§7)\n§7Expiry in: §e" . TimeManager::getTimeLeft($time));
        }
    }
}