<?php

namespace xoapp\advanced\utils;

class SystemUtils {

    const PREFIX = "§8(§cAdvancedStaff§8) §7";

    public static function equals($object1, $object2)
    {
        if ($object1 === $object2) {
            return true;
        }
        return false;
    }
}