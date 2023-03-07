<?php

namespace xoapp\advanced\listeners;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityCombustEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\server\CommandEvent;
use pocketmine\Server;
use pocketmine\player\Player;
use xoapp\advanced\session\SessionFactory;

class StaffListener implements Listener {

    public function onQuit(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::getInstance()->isFreezed($player)) {
            SessionFactory::getInstance()->unsetFreeze($player);
        }

        if (SessionFactory::getInstance()->isRegistered($player)) {
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->getEffects()->clear();
            $player->setFlying(false);
            $player->setAllowFlight(false);
            $player->setSilent(false);
            $player->getEffects()->clear();
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                $players->showPlayer($player);
            }
        }
    }

    public function onExhaust(PlayerExhaustEvent $event)
    {
        $player = $event->getPlayer();

        if(!$player instanceof Player)return;

        if (SessionFactory::getInstance()->isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::getInstance()->isRegistered($player)) {
            $event->setDrops([]);
        }
    }

    public function onRespawn(PlayerRespawnEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::getInstance()->isRegistered($player)) {
            SessionFactory::getInstance()->sendKit($player);
        }
    }

    public function onKick(PlayerKickEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::getInstance()->isRegistered($player)) {
            $player->getInventory()->clearAll();
            $player->getArmorInventory()->clearAll();
            $player->getEffects()->clear();
            $player->setFlying(false);
            $player->setAllowFlight(false);
            $player->setSilent(false);
            $player->getEffects()->clear();
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                $players->showPlayer($player);
            }
        }
    }

    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::getInstance()->isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::getInstance()->isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::getInstance()->isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();

        if (SessionFactory::getInstance()->isRegistered($player)) {
            $event->cancel();
        }
    }

    public function onDamage(EntityDamageEvent $event)
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Player) return;

        if (SessionFactory::getInstance()->isRegistered($entity)) {
            $event->cancel();
            return;
        }

        if (SessionFactory::getInstance()->isFreezed($entity)) {
            $event->cancel();
            return;
        }
    }

    public function onDamageByEntity(EntityDamageByEntityEvent $event)
    {
        $entity = $event->getDamager();

        if(!$entity instanceof Player)return;

        if (SessionFactory::getInstance()->isRegistered($entity)) {
            $event->cancel();
        }
    }

    public function onPickup(EntityItemPickupEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            if (SessionFactory::getInstance()->isRegistered($entity)) {
                $event->cancel();
            }
        }
    }

    public function onCombust(EntityCombustEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {
            if (SessionFactory::getInstance()->isRegistered($entity)) {
                $event->cancel();
            }
        }
    }

    public function onCommand(CommandEvent $event)
    {
        $player = $event->getSender();

        if(!$player instanceof Player)return;

        if (SessionFactory::getInstance()->isFreezed($player)) {
            $event->cancel();
        }
    }
}