<?php

namespace xoapp\advanced\anticheat\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use xoapp\advanced\anticheat\session\ProtoypeSession;
use xoapp\advanced\utils\SystemUtils;

class PrototypeCommand extends Command {

    public function __construct()
    {
        parent::__construct("prototype", "", null, [""]);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if (!$player instanceof Player) return;

        if (!$player->hasPermission("prototype.command")) {
            return;
        }

        if (!ProtoypeSession::getInstance()->isRegister($player)) {
            $player->sendMessage(SystemUtils::LOG . "You have entered prototype mode");
            ProtoypeSession::getInstance()->register($player);
            return;
        }

        if (ProtoypeSession::getInstance()->isRegister($player)) {
            $player->sendMessage(SystemUtils::LOG . "You have exited prototype mode");
            ProtoypeSession::getInstance()->unregister($player);
            return;
        }
    }
}