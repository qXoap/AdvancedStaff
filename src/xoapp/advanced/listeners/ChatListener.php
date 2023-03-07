<?php

namespace xoapp\advanced\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use xoapp\advanced\restrictions\MuteManager;
use xoapp\advanced\restrictions\TimeManager;
use xoapp\advanced\utils\SystemUtils;

class ChatListener implements Listener {

    public function onPlayerChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();

        $manager = MuteManager::getInstance();

        if ($manager->isRegistered($name)) {
            $time = TimeManager::getTimeLeft($manager->getData($name, "muteTime"));

            if ($time <= 0) {
                $manager->unregister($name);
                return;
            }

            $reason = $manager->getData($name, "muteReason");
            $sender = $manager->getData($name, "senderName");
            $date = $manager->getData($name, "date");

            $player->sendMessage(SystemUtils::PREFIX . "You have been Muted For (§e" . $reason . " §7: " . $sender . " §7: §e" . $date . "§7) §7Expiry in §e" . $time);
            $event->cancel();
        }
    }

    public function onCommandEvent(CommandEvent $event)
    {
        $player = $event->getSender();

        if (!$player instanceof Player) return;

        $command = null;
        if (($cmd = Server::getInstance()->getCommandMap()->getCommand($event->getCommand())) !== null) {
            $command .= $cmd->getName();
        }
        if (MuteManager::getInstance()->isRegistered($player->getName())) {
            if (SystemUtils::equals($command, "tell")) {
                $event->cancel();
                return;
            }
            if (SystemUtils::equals($command, "msg")) {
                $event->cancel();
                return;
            }

            if (SystemUtils::equals($command, "me")) {
                $event->cancel();
                return;
            }
        }
    }
}