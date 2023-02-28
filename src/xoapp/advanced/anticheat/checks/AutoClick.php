<?php

namespace xoapp\advanced\anticheat\checks;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\inventory\UseItemOnEntityTransactionData;
use pocketmine\network\mcpe\protocol\types\LevelSoundEvent;
use xoapp\advanced\anticheat\Prototype;
use xoapp\advanced\anticheat\session\ProtoypeSession;
use xoapp\advanced\player\Player;
use xoapp\advanced\utils\SystemUtils;

class AutoClick implements Listener {

    public function onDataPacketReceive(DataPacketReceiveEvent $event)
    {
        $pk = $event->getPacket();

        $player = $event->getOrigin()->getPlayer();

        if (!$player instanceof Player) return;

        if ($pk instanceof LevelSoundEventPacket) {
            if (SystemUtils::equals($pk->sound, LevelSoundEvent::ATTACK_NODAMAGE)) {
                $player->addClicks();
                $clicks = $player->getClicks();

                if (SystemUtils::isMore($clicks, 24)) {
                    ProtoypeSession::getInstance()->addValue($player);
                    Prototype::debug($player, "AutoClick", $clicks);
                }
            }
        }

        if ($pk instanceof InventoryTransactionPacket) {
            if ($pk->trData instanceof UseItemOnEntityTransactionData) {
                $player->addClicks();
                $clicks = $player->getClicks();

                if (SystemUtils::isMore($clicks, 24)) {
                    ProtoypeSession::getInstance()->addValue($player);
                    Prototype::debug($player, "AutoClick", $clicks);
                }
            }
        }
    }
}