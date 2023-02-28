<?php

namespace xoapp\advanced\commands;

use Cassandra\Time;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use xoapp\advanced\restrictions\MuteManager;
use xoapp\advanced\restrictions\TimeManager;
use xoapp\advanced\utils\SystemUtils;

class MuteCommand extends Command {

    public function __construct()
    {
        parent::__construct("mute", "");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("advanced.mute")) {
            $player->sendMessage(SystemUtils::PREFIX . "You Don't Have Permissions to use this");
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /mute (player) (time) (reason)");
            return;
        }

        if (!isset($args[1])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /mute (player) (time) (reason)");
            return;
        }

        if (!isset($args[2])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /mute (player) (time) (reason)");
            return;
        }

        if (!in_array(TimeManager::intToString($args[1]), TimeManager::VALID_FORMATS)) {
            $player->sendMessage(SystemUtils::PREFIX . "Please Put a valid time format");
            return;
        }

        if (!is_numeric($args[1][0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Please Put a valid time format");
        }

        if (MuteManager::getInstance()->isRegistered($player)) {
            $player->sendMessage(SystemUtils::PREFIX . "Apparently this player already has this sanction!");
            return;
        }

        $time = TimeManager::getFormatTime(TimeManager::stringToInt($args[1]), $args[1]);
        MuteManager::getInstance()->register($args[0], $player->getName(), $args[2], $time);

        SystemUtils::broadcastMessage(SystemUtils::PREFIX . "Player §e" . $args[0] . " §7was muted for §e" . $args[2]);

        if (($victim = Server::getInstance()->getPlayerExact($args[0])) instanceof Player) {
            $victim->sendMessage(SystemUtils::PREFIX . "You have been Muted For §e" . $args[2] . " §7expiry in §e" . TimeManager::getTimeLeft($time));
        }
    }
}