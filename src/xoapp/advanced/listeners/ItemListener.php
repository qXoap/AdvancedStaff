<?php

namespace xoapp\advanced\listeners;

use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\Server;
use xoapp\advanced\forms\TeleportForm;
use xoapp\advanced\item\Freeze;
use xoapp\advanced\item\PlayerInfo;
use xoapp\advanced\item\Teleport;
use xoapp\advanced\item\UnVanish;
use xoapp\advanced\item\Vanish;
use xoapp\advanced\player\Player;
use xoapp\advanced\utils\SystemUtils;

class ItemListener implements Listener {

    public function onItemUse(PlayerItemUseEvent $event)
    {
        $player = $event->getPlayer();
        $item = $event->getItem();

        if (!$player instanceof Player) return;

        if ($item instanceof Teleport) {
            if (!$player->isRegistered()) {
                return;
            }

            $player->sendForm(new TeleportForm());
            return;
        }

        if ($item instanceof Vanish) {
            if (!$player->isRegistered()) {
                return;
            }

            $player->getInventory()->setItem(8, new UnVanish());
            $player->sendMessage(SystemUtils::PREFIX . "Now you're in vanish");
            foreach (SystemUtils::getEveryone() as $players) {
                $players->hidePlayer($player);
            }
            return;
        }

        if ($item instanceof UnVanish) {
            if (!$player->isRegistered()) {
                return;
            }

            $player->getInventory()->setItem(8, new Vanish());
            $player->sendMessage(SystemUtils::PREFIX . "You came out of the vanish");
            foreach (SystemUtils::getEveryone() as $players) {
                $players->showPlayer($player);
            }
            return;
        }
    }

    public function onEntityDamage(EntityDamageByEntityEvent $event)
    {
        $player = $event->getDamager();
        $vic = $event->getEntity();

        if (!$player instanceof Player) return;
        if (!$vic instanceof Player) return;

        $item = $player->getInventory()->getItemInHand();

        if ($item instanceof Freeze) {
            if (!$player->isRegistered()) {
                $event->cancel();
                return;
            }

            if ($vic->isRegistered()) {
                $event->cancel();
                return;
            }

            if (!$vic->isFreezed()) {
                $vic->setFreeze();
                $vic->setImmobile();
                Server::getInstance()->broadcastMessage(SystemUtils::PREFIX . "Player §e" . $vic->getName() . " was frozen by §e" . $player->getName());
                $event->cancel();
                return;
            }

            if ($vic->isFreezed()) {
                $vic->unsetFreeze();
                $vic->setImmobile(false);
                Server::getInstance()->broadcastMessage(SystemUtils::PREFIX . "Player §e" . $vic->getName() . " was thawed by §e" . $player->getName());
                $event->cancel();
            }
            return;
        }

        if ($item instanceof PlayerInfo) {
            if (!$player->isRegistered()) {
                $event->cancel();
                return;
            }

            if ($vic->isRegistered()) {
                $event->cancel();
                return;
            }

            $vic->getCountry(function (string $country) use ($player, $vic) {
                $player->sendMessage(SystemUtils::PREFIX . "Player Information: §e" . $vic->getName());
                $player->sendMessage(" ");
                $player->sendMessage("§7 - §fPlayer Address" . $vic->getAddress());
                $player->sendMessage("§7 - §fPlayer Country" . $country);
                $player->sendMessage("§7 - §fPlayer Device" . $vic->getDeviceModel());
                $player->sendMessage("§7 - §fPlayer Platform" . $vic->getCurrentPlatform());
                $player->sendMessage("§7 - §fPlayer Input" . $vic->getCurrentInput());
                $player->sendMessage(" ");
            });

            $event->cancel();
            return;
        }

        if ($player->isRegistered()) {
            $event->cancel();
            return;
        }
    }
}