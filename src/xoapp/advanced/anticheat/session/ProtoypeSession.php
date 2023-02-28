<?php

namespace xoapp\advanced\anticheat\session;

use xoapp\advanced\player\Player;
use pocketmine\utils\SingletonTrait;

class ProtoypeSession {
    use SingletonTrait;

    private $session = [];

    private $vl = [];

    public function __construct()
    {
        self::setInstance($this);
    }


    public function register(Player $player): void
    {
        $this->session[$player->getName()] = new Session($player);
    }

    public function isRegister(Player $player)
    {
        return isset($this->session[$player->getName()]);
    }

    public function unregister(Player $player): void
    {
        unset($this->session[$player->getName()]);
    }

    public function registerValue(Player $player): void
    {
        $this->vl[$player->getName()] = 0;
    }

    public function addValue(Player $player): void
    {
        $this->vl[$player->getName()] = $this->vl[$player->getName()] + 1;
    }

    public function getValue(Player $player)
    {
        return $this->vl[$player->getName()];
    }

    public function isRegisterValue(Player $player)
    {
        return isset($this->vl[$player->getName()]);
    }

    public function resetValue(Player $player): void
    {
        $this->vl[$player->getName()] = 0;
    }
}