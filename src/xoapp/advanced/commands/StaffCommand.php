<?php

namespace xoapp\advanced\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use xoapp\advanced\player\Player;

class StaffCommand extends Command {

    public function __construct()
    {
        parent::__construct("staff", "", null, ["mod"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) {
            $player->sendMessage("Execute with Game");
            return;
        }

        if (!$player->hasPermission("advanced.staff")) {
            return;
        }

        if (!$player->isRegistered()) {
            $player->register();
            return;
        }

        if ($player->isRegistered()) {
            $player->unregister();
            return;
        }
    }
}