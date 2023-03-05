<?php

namespace xoapp\advanced\utils;

use pocketmine\Server;

class SystemUtils {

    const PREFIX = "§8(§cAdvancedStaff§8) §7";

    const LOG = "§8(§cSystemLogs§8) §7";

    public static function equals($object1, $object2): bool
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

    public static function isLess(int $number1 = 0, int $number2 = 0)
    {
        if ($number1 < $number2) {
            return true;
        }
        return false;
    }

    public static function isMore(int $number1 = 0, int $number2 = 0): bool
    {
        if ($number1 >= $number2) {
            return true;
        }
        return false;
    }

    public static function broadcastMessage(string $message): string
    {
        return Server::getInstance()->broadcastMessage($message);
    }

    public static function unregisterCommand(array $commandName) : void
    {
        $commandMap = Server::getInstance()->getCommandMap();
        foreach ($commandName as $cmd) {
            if (($cmd = $commandMap->getCommand($cmd)) !== null) {
                $commandMap->unregister($cmd);
            }
        }
    }
}