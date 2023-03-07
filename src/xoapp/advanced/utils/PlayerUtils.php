<?php

namespace xoapp\advanced\utils;

use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\network\mcpe\protocol\types\InputMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use xoapp\advanced\async\PlayerCountryAsync;

class PlayerUtils {

    private static $cps;

    public static function getPlayerInput(Player $player): string
    {
        $data = $player->getPlayerInfo()->getExtraData();

        return match ($data["CurrentInputMode"]) {
            InputMode::TOUCHSCREEN => "Touch",
            InputMode::MOUSE_KEYBOARD => "Keyboard",
            InputMode::GAME_PAD => "Controller",
            InputMode::MOTION_CONTROLLER => "Motion Controller",
            default => "Unknown"
        };
    }

    public static function getDeviceModel(Player $player)
    {
        $data = $player->getPlayerInfo()->getExtraData();
        return $data["DeviceModel"];
    }

    public static function getPlayerPlatform(Player $player): string
    {
        $extraData = $player->getPlayerInfo()->getExtraData();

        if ($extraData["DeviceOS"] === DeviceOS::ANDROID && $extraData["DeviceModel"] === "") {
            return "Linux";
        }

        return match ($extraData["DeviceOS"]) {
            DeviceOS::ANDROID => "Android",
            DeviceOS::IOS => "iOS",
            DeviceOS::OSX => "MacOS",
            DeviceOS::AMAZON => "FireOS",
            DeviceOS::GEAR_VR => "Gear VR",
            DeviceOS::HOLOLENS => "Hololens",
            DeviceOS::WINDOWS_10 => "Windows",
            DeviceOS::WIN32 => "WinEdu",
            DeviceOS::DEDICATED => "Dedicated",
            DeviceOS::TVOS => "TV OS",
            DeviceOS::PLAYSTATION => "PlayStation",
            DeviceOS::NINTENDO => "Nintendo Switch",
            DeviceOS::XBOX => "Xbox",
            DeviceOS::WINDOWS_PHONE => "Windows Phone",
            default => "Unknown"
        };
    }

    public static function distance(Position $a, Position $b): float
    {
        return sqrt(pow($a->getX() - $b->getX(), 2) + pow($a->getY() - $b->getY(), 2) + pow($a->getZ() - $b->getZ(), 2));
    }

    public static function addClicks(Player $player): void
    {
        $time = microtime(true);
        self::$cps[$player->getName()][] = $time;
    }

    public static function getClicks(Player $player): int
    {
        $time = microtime(true);
        return count(array_filter(self::$cps[$player->getName()] ?? [], static function (float $t) use ($time): bool {
            return ($time - $t) <= 1;
        }));
    }

    public static function getCountry(Player $player, Callable $callable): void
    {
        Server::getInstance()->getAsyncPool()->submitTask(new PlayerCountryAsync($player->getNetworkSession()->getIp(), $callable));
    }
}