<?php

namespace system\moderation\restrictions\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advanced\restrictions\BanManager;
use xoapp\advanced\restrictions\TimeManager;
use xoapp\advanced\utils\SystemUtils;

class BanListCommand extends Command {

    public function __construct()
    {
        parent::__construct("banlist", "");
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("moderation.banlist")) {
            $player->sendMessage(SystemUtils::PREFIX . "You Don't Have Permissions to use this");
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /banlist (permanently : temporarily)");
            return;
        }

        $manager = BanManager::getInstance();

        if (SystemUtils::equals($args[0], "temporarily")) {
            $player->sendMessage(SystemUtils::PREFIX . "There are Total §a" . count($manager->getBanneds(false)) . " §7players temporarily banned");
            foreach ($manager->getBanneds(false) as $banned) {
                $reason = $manager->getData($banned, "banReason");
                $sender = $manager->getData($banned, "senderName");
                $date = $manager->getData($banned, "date");
                $left = TimeManager::getTimeLeft($manager->getData($banned, "banTime"));

                $player->sendMessage("§7Player §e" . $banned . " §7banned by §e" . $sender . " §7for §e" . $reason . " §7ban date §e" . $date . " §7expiration §e" . $left);
            }
            return;
        }

        if (SystemUtils::equals($args[0], "permanently")) {
            $player->sendMessage(SystemUtils::PREFIX . "There are Total §a" . count($manager->getBanneds(true)) . " §7players permanently banned");
            foreach ($manager->getBanneds(true) as $banned) {
                $reason = $manager->getData($banned, "banReason", true);
                $sender = $manager->getData($banned, "senderName", true);
                $date = $manager->getData($banned, "date", true);

                $player->sendMessage("§7Player §e" . $banned . " §7banned by §e" . $sender . " §7for §e" . $reason . " §7ban date §e" . $date . " §7expiration §eUndefined");
            }
            return;
        }

        $player->sendMessage(SystemUtils::PREFIX . "Use /banlist (permanently : temporarily)");
    }
}