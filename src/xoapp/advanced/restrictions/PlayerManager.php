<?php

namespace xoapp\advanced\restrictions;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use xoapp\advanced\Loader;

class PlayerManager {

    private static Config $config;

    public function __construct()
    {
        self::$config = new Config(Loader::getInstance()->getDataFolder() . "players.json", Config::JSON);
    }

    public static function getAddress(string $name): string
    {
        return self::$config->get($name)["address"];
    }

    public static function register(Player $player): void
    {
        self::$config->set($player->getName(), ["address" => $player->getNetworkSession()->getIp()]);
        self::$config->save();
    }

    public static function isRegistered(Player $player): bool
    {
        return self::$config->exists($player->getName());
    }
}