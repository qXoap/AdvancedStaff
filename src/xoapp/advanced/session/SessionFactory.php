<?php

namespace xoapp\advanced\session;

use pocketmine\player\GameMode;
use xoapp\advanced\item\Freeze;
use xoapp\advanced\item\PlayerInfo;
use xoapp\advanced\item\Teleport;
use xoapp\advanced\item\Vanish;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class SessionFactory {
    use SingletonTrait;

    private array $session = [];

    private array $freeze = [];

    private array $items = [];
    private array $armor = [];
    private array $off_hand = [];

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
        $this->sendKit($player);
        $player->setGamemode(GameMode::SURVIVAL());
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
        $player->setGamemode(GameMode::SURVIVAL());
    }

    public function sendKit(Player $player): void
    {
        $inventory = $player->getInventory();
        $inventory->setContents([
            0 => new Teleport(),
            1 => new PlayerInfo(),
            2 => new Freeze(),
            8 => new Vanish()
        ]);
    }

    public function isRegistered(Player $player): bool
    {
        return isset($this->session[$player->getName()]);
    }

    public function setFreeze(Player $player): void
    {
        $this->freeze[$player->getName()] = new \xoapp\advanced\session\types\Freeze($player);
    }

    public function unsetFreeze(Player $player): void
    {
        unset($this->freeze[$player->getName()]);
    }

    public function isFreezed(Player $player): bool
    {
        return isset($this->freeze[$player->getName()]);
    }
}