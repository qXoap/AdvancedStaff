<?php

namespace xoapp\advanced\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advanced\session\SessionFactory;

class StaffCommand extends Command {

    public function __construct()
    {
        parent::__construct("staff", "", null, ["mod"]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("advanced.staff")) {
            return;
        }

        if (!SessionFactory::getInstance()->isRegistered($player)) {
            SessionFactory::getInstance()->register($player);
            return;
        }

        if (SessionFactory::getInstance()->isRegistered($player)) {
            SessionFactory::getInstance()->unregister($player);
            $player->getEffects()->clear();
            return;
        }
    }
}