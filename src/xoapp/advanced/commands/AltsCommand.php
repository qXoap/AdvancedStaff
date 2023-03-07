<?php

namespace xoapp\advanced\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advanced\restrictions\PlayerManager;
use xoapp\advanced\utils\SystemUtils;

class AltsCommand extends Command {

    public function __construct()
    {
        parent::__construct("alts", "", null, ["dupeip"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("advanced.alts")) {
            return;
        }

        if (!isset($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "Use /alts (player)");
            return;
        }

        if (!PlayerManager::isRegistered($args[0])) {
            $player->sendMessage(SystemUtils::PREFIX . "This Player is not Registered");
            return;
        }

        $possible_alts = PlayerManager::getPlayers();

        $player->sendMessage(SystemUtils::PREFIX . "The possible accounts of this player could be");
        foreach ($possible_alts as $possible_alt) {
            if (!SystemUtils::equals(PlayerManager::getAddress($possible_alt), PlayerManager::getAddress($args[0]))) {
                return;
            }
            $player->sendMessage("§7- §a" . $possible_alt);
        }
    }
}