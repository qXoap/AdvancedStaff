<?php

namespace xoapp\advanced\session;

use xoapp\advanced\player\Player;
use pocketmine\utils\SingletonTrait;

class SessionFactory {
    use SingletonTrait;

    private $session;

    private $items;
    private $armor;
    private $off_hand;

    public function __construct()
    {
        self::setInstance($this);
    }

    public function register(Player $player): void
    {
        $this->session[$player->getName()] = new Session($player);
        $this->items[$player->getName()] = $player->getInventory()->getContents();
        $this->armor[$player->getName()] = $player->getArmorInventory()->getContents();
        $this->off_hand[$player->getName()] = $player->getOffHandInventory()->getContents();
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getOffHandInventory()->clearAll();
    }

    public function unregister(Player $player): void
    {
        unset($this->session[$player->getName()]);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $player->getOffHandInventory()->clearAll();
        $player->getInventory()->setContents($this->items[$player->getName()]);
        $player->getArmorInventory()->setContents($this->armor[$player->getName()]);
        $player->getOffHandInventory()->setContents($this->off_hand[$player->getName()]);
    }

    public function isRegistered(Player $player): bool
    {
        return isset($this->session[$player->getName()]);
    }
}