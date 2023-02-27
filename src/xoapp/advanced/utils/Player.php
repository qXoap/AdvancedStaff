<?php

namespace xoapp\advanced\player;

use pocketmine\network\mcpe\protocol\types\DeviceOS;
use pocketmine\network\mcpe\protocol\types\InputMode;
use pocketmine\player\Player as PMPLayer;
use pocketmine\Server;
use xoapp\advanced\async\PlayerCountryAsync;
use xoapp\advanced\session\SessionFactory;
use xoapp\advanced\utils\SystemUtils;

class Player extends PMPLayer {

    public function register(): void
    {
        SessionFactory::getInstance()->register($this);
        $this->sendMessage(SystemUtils::PREFIX . "You have entered in StaffMode");
    }

    public function unregister(): void
    {
        SessionFactory::getInstance()->unregister($this);
        $this->sendMessage(SystemUtils::PREFIX . "You have exited the StaffMode");
    }

    public function isRegistered(): bool
    {
        return SessionFactory::getInstance()->isRegistered($this);
    }

    public function getCurrentInput(): string
    {
        $data = $this->getPlayerInfo()->getExtraData();

        return match ($data["CurrentInputMode"]) {
            InputMode::TOUCHSCREEN => "Touch",
            InputMode::MOUSE_KEYBOARD => "Keyboard",
            InputMode::GAME_PAD => "Controller",
            InputMode::MOTION_CONTROLLER => "Motion Controller",
            default => "Unknown"
        };
    }

    public function getCurrentPlatform(): string
    {
        $extraData = $this->getPlayerInfo()->getExtraData();

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

    public function getCountry(Callable $callable): void
    {
        Server::getInstance()->getAsyncPool()->submitTask(new PlayerCountryAsync($this->getNetworkSession()->getIp(), $callable));
    }

    public function getDeviceModel(): string
    {
        return $this->getPlayerInfo()->getExtraData()["DeviceModel"];
    }
}