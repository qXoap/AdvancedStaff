<?php

namespace xoapp\advanced\restrictions;

use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use xoapp\advanced\Loader;

class MuteManager {
    use SingletonTrait;

    private $temporarily;

    public function __construct()
    {
        self::setInstance($this);
        $this->temporarily = new Config(Loader::getInstance()->getDataFolder() . "/restrictions/temporarily_muted.json", Config::JSON);
    }

    public function register(string $name, string $senderName, string $reason, $time = null): void
    {
        $date = date("d/m/y H:i:s");
        $this->temporarily->set($name, [
            "senderName" => $senderName, "muteReason" => $reason, "date" => $date, "muteTime" => $time
        ]);
        $this->temporarily->save();
    }

    public function isRegistered(string $name)
    {
        return $this->temporarily->exists($name);
    }

    public function getData(string $name, string $data)
    {
        return $this->temporarily->get($name)[$data];
    }

    public function unregister(string $name): void
    {
        $this->temporarily->remove($name);
        $this->temporarily->save();
    }
}