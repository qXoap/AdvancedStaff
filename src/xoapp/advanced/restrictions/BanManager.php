<?php

namespace xoapp\advanced\restrictions;

use pocketmine\item\Book;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use xoapp\advanced\Loader;
use function Sodium\add;

class BanManager {
    use SingletonTrait;

    private $temporarily;
    private $permanently;
    private $address;

    public function __construct()
    {
        self::setInstance($this);
        $this->temporarily = new Config(Loader::getInstance()->getDataFolder() . "/restrictions/temporarily_banned.json", Config::JSON);
        $this->permanently = new Config(Loader::getInstance()->getDataFolder() . "/restrictions/permanently_banned.json", Config::JSON);
        $this->address = new Config(Loader::getInstance()->getDataFolder() . "/restrictions/addresses_banned.json", Config::JSON);
    }

    public function register(string $name, string $senderName, string $reason, bool $isPermanently = false, $time = null): void
    {
        $date = date("d/m/y H:i:s");
        if ($isPermanently) {
            $this->permanently->set($name, [
                "senderName" => $senderName, "banReason" => $reason, "date" => $date, "isPermanently" => $isPermanently
            ]);
            $this->permanently->save();
        } else {
            $this->temporarily->set($name, [
                "senderName" => $senderName, "banReason" => $reason, "date" => $date, "isPermanently" => $isPermanently, "banTime" => $time
            ]);
            $this->temporarily->save();
        }
    }

    public function isRegistered(string $name, bool $isPermanently = false): bool
    {
        if ($isPermanently) {
            return $this->permanently->exists($name);
        } else {
            return $this->temporarily->exists($name);
        }
    }

    public function unregister(string $name, bool $isPermanently = false): void
    {
        if ($isPermanently) {
            $this->permanently->remove($name);
            $this->permanently->save();
        } else {
            $this->temporarily->remove($name);
            $this->temporarily->save();
        }
    }

    public function getData(string $name, string $data, bool $isPermanently = false)
    {
        if ($isPermanently) {
            return $this->permanently->get($name)[$data];
        } else {
            return $this->temporarily->get($name)[$data];
        }
    }

    public function getBanneds(bool $isPermanently = false): array
    {
        if ($isPermanently) {
            return $this->permanently->getAll(true);
        } else {
            return $this->temporarily->getAll(true);
        }
    }

    public function registerAddress(string $address, string $ownerName, string $senderName, string $reason, $time = null): void
    {
        $this->address->set($address, [
            "ownerName" => $ownerName, "senderName" => $senderName, "banReason" => $reason, "banTime" => $time
        ]);
        $this->address->save();
    }

    public function isAddressRegistered(string $address): bool
    {
        return $this->address->exists($address);
    }

    public function getAddressData(string $name, string $data): string
    {
        return $this->address->get($name)[$data];
    }

    public function unregisterAddress(string $address): void
    {
        $this->address->remove($address);
        $this->address->save();
    }
}