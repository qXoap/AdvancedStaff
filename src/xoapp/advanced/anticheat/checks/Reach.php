<?php

namespace xoapp\advanced\anticheat\checks;

use pocketmine\event\entity\EntityDamageByChildEntityEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use xoapp\advanced\player\Player;
use xoapp\advanced\anticheat\Prototype;
use xoapp\advanced\anticheat\session\ProtoypeSession;
use xoapp\advanced\utils\SystemUtils;

class Reach implements Listener {

    public function onPlayerDamage(EntityDamageByEntityEvent $event)
    {
        $entity = $event->getEntity();
        $player = $event->getDamager();

        if ($entity instanceof EntityDamageByChildEntityEvent) return;
        if (!$player instanceof Player) return;
        if (!$entity instanceof Player) return;

        $diff = number_format($player->distance($entity->getPosition()), 4);

        if (SystemUtils::isMore($diff, 5.6)) {
            ProtoypeSession::getInstance()->addValue($player);
            Prototype::debug($player, "Reach", $diff);
        }
    }
}