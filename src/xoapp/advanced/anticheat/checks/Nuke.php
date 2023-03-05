<?php

namespace xoapp\advanced\anticheat\checks;

use pocketmine\entity\effect\VanillaEffects;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\player\GameMode;
use xoapp\advanced\anticheat\Prototype;
use xoapp\advanced\anticheat\session\ProtoypeSession;
use xoapp\advanced\utils\SystemUtils;

class Nuke implements Listener {

    public array $breakTime = [];

    public function onPlayerInteract(PlayerInteractEvent $event) : void
    {
        if(SystemUtils::equals($event->getAction(), PlayerInteractEvent::LEFT_CLICK_BLOCK)){
            $this->breakTime[$event->getPlayer()->getName()] = floor(microtime(true) * 20);
        }
    }

    public function onBlockBreack(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();

        if ($event->getInstaBreak()) {
            return;
        }

        if (!isset($this->breakTime[$player->getName()])) {
            $event->cancel();
            return;
        }

        $normal_time = ceil($event->getBlock()->getBreakInfo()->getBreakTime($event->getItem()) * 20);

        if(($haste = $player->getEffects()->get(VanillaEffects::HASTE())) !== null){
            $normal_time *= 1 - (0.2 * $haste->getEffectLevel());
        }

        if(($miningFatigue = $player->getEffects()->get(VanillaEffects::MINING_FATIGUE())) !== null){
            $normal_time *= 1 + (0.3 * $miningFatigue->getEffectLevel());
        }

        $normal_time -= 1;

        $actual_time = ceil(microtime(true) * 20) - $this->breakTime[$player->getName()];

        if (SystemUtils::equals($player->getGamemode(), GameMode::CREATIVE())) {
            return;
        }

        if ($actual_time < $normal_time) {
            ProtoypeSession::getInstance()->addValue($player);
            Prototype::debug($player, "Nuke or InstaBreak", "true");
            $event->cancel();
        }

        unset($this->breakTime[$player->getName()]);
    }

    public function onQuit(PlayerQuitEvent $event)
    {
        unset($this->breakTime[$event->getPlayer()->getName()]);
    }
}