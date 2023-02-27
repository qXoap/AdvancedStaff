<?php

namespace xoapp\advanced\utils;

use pocketmine\Server;

class SystemUtils {

    const PREFIX = "§8(§cAdvancedStaff§8) §7";

    const LOG = "§8(§cSystemLogs§8) §7";

    public static function equals($object1, $object2)
    {
        if ($object1 === $object2) {
            return true;
        }
        return false;
    }

    public static function getEveryone()
    {
        return Server::getInstance()->getOnlinePlayers();
    }
}