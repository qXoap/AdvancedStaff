<?php

namespace xoapp\advanced\anticheat\checks;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\Server;
use ReflectionException;
use xoapp\advanced\Loader;
use xoapp\advanced\utils\SystemUtils;

class Toolbox implements Listener {

    public function onPlayerMove(PlayerMoveEvent $event)
    {
        try {
            $player = $event->getPlayer();
            $extraData = $player->getPlayerInfo()->getExtraData();
            if ($extraData["DeviceOS"] === DeviceOS::ANDROID) {
                $model = explode(" ", $extraData["DeviceModel"], 2)[0];

                if ($model !== strtoupper($model) && $model !== "") {
                    foreach (SystemUtils::getEveryone() as $players) {
                        if ($players->hasPermission("prototype.command")) {
                            $players->sendMessage(SystemUtils::LOG . "The Player " . $player->getName() . " possibly entered with Toolbox");
                        }
                    }
                    Server::getInstance()->broadcastMessage(SystemUtils::LOG . "Player §6" . $player->getName() . " §7was kicked for §6Unfair Advantage §e(§fToolbox§e)");
                    $player->kick(SystemUtils::LOG . "Please try to enter again but without Toolbox");
                }
            }
        } catch (ReflectionException $e) {
            Loader::getInstance()->getLogger()->error(SystemUtils::LOG . "Failed: §a" . $e->getMessage() . " (§f" . $e->getFile() . "§a) Line: §a" . $e->getLine());
        }
    }
}