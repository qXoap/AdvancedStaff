<?php

namespace xoapp\advanced\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use xoapp\advanced\player\Player;

class PlayerListener implements Listener {

    public function onPlayerCreation(PlayerCreationEvent $event)
    {
        $event->setPlayerClass(Player::class);
    }
}