<?php

namespace xoapp\advanced\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use xoapp\advanced\restrictions\BanManager;
use xoapp\advanced\restrictions\PlayerManager;
use xoapp\advanced\restrictions\TimeManager;

class LoginListener implements Listener {

    public function onPlayerLogin(PlayerLoginEvent $event)
    {

        $player = $event->getPlayer();
        $name = $player->getName();
        $manager = BanManager::getInstance();
        $address = $player->getNetworkSession()->getIp();

        if ($manager->isRegistered($name, true)) {
            $reason = $manager->getData($name, "banReason", true);
            $sender = $manager->getData($name, "senderName", true);
            $date = $manager->getData($name, "date", true);

            $player->kick("§7You have been Permanently Banned\n§7Reason: (§e" . $reason . " §7: §e" . $sender . "§7 : §e" . $date . "§7)\n§7Expiry in §eUndefined");
            return;
        }

        if ($manager->isRegistered($name)) {
            $time = TimeManager::getTimeLeft($manager->getData($name, "banTime"));

            if ($time <= 0) {
                $manager->unregister($name);
                return;
            }

            $reason = $manager->getData($name, "banReason");
            $sender = $manager->getData($name, "senderName");
            $date = $manager->getData($name, "date");

            $player->kick("§7You have been Temporarily Banned\n§7Reason: (§e" . $reason . " §7: §e" . $sender . "§7 : §e" . $date . "§7)\n§7Expiry in §e" . $time);
            return;
        }

        if ($manager->isAddressRegistered($address)) {
            $time = TimeManager::getTimeLeft($manager->getAddressData($address, "banTime"));

            if ($time <= 0) {
                $manager->unregister($name);
                return;
            }

            $reason = $manager->getAddressData($name, "banReason");
            $sender = $manager->getAddressData($name, "senderName");
            $date = $manager->getAddressData($name, "date");

            $player->kick("§7You have been Temporarily IP Banned\n§7Reason: (§e" . $reason . " §7: §e" . $sender . "§7 : §e" . $date . "§7)\n§7Expiry in §e" . $time);
            return;
        }
    }

    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        if (!PlayerManager::isRegistered($event->getPlayer())) {
            PlayerManager::register($event->getPlayer());
        }
    }
}