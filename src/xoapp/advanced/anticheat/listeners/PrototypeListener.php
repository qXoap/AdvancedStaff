<?php

namespace xoapp\advanced\anticheat\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use xoapp\advanced\anticheat\session\ProtoypeSession;

class PrototypeListener implements Listener {

    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();

        if (!ProtoypeSession::getInstance()->isRegisterValue($player)) {
            ProtoypeSession::getInstance()->registerValue($player);
        }
    }
}