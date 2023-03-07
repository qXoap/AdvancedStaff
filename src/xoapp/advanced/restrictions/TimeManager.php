<?php

namespace xoapp\advanced\restrictions;

class TimeManager {

    const VALID_FORMATS = ["minutes", "hours", "seconds", "days", "years", "months"];

    public static function intToString(string $timeFormat): string
    {
        $format = str_split($timeFormat);
        $time = null;
        for ($i = 0; $i < count($format); $i++) {
            switch ($format[$i]) {
                case "m":
                    $time = "minutes";
                    break;
                case "h":
                    $time = "hours";
                    break;
                case "d":
                    $time = "days";
                    break;
                case "s":
                    $time = "seconds";
                    break;
                case "y":
                    $time = "years";
                    break;
                case "n":
                    $time = "months";
                    break;
            }
        }
        return $time;
    }

    public static function stringToInt(string $timeFormat): int
    {
        $format = str_split($timeFormat);
        $characters = "";
        for ($i = 0; $i < count($format); $i++) {
            if (is_numeric($format[$i])) {
                $characters .= $format[$i];
                continue;
            }
        }
        return $characters;
    }

    public static function getFormatTime(int $time, string $timeFormat): int
    {
        $value = null;
        switch (self::intToString($timeFormat)) {
            case "minutes":
                $value = time() + ($time * 60);
                break;
            case "hours":
                $value = time() + ($time * 3600);
                break;
            case "days":
                $value = time() + ($time * 86400);
                break;
            case "seconds":
                $value = time() + ($time * 1);
                break;
            case "years":
                $value = time() + ($time * 3600 * 24 * 365);
                break;
            case "months":
                $value = time() + ($time * 3600 * 24 * 30);
                break;
        }
        return $value;
    }

    public static function getTimeElapsed($time): string
    {
        $seconds = $time % 60;
        $minutes = null;
        $hours = null;
        $days = null;

        if ($time >= 60) {
            $minutes = floor(($time % 3600) / 60);
            if ($time >= 3600) {
                $hours = floor(($time % (3600 * 24)) / 3600);
                if ($time >= 3600 * 24) {
                    $days = floor($time / (3600 * 24));
                }
            }
        }
        return ($minutes !== null ? ($hours !== null ? ($days !== null ? "$days days " : "") . "$hours hours " : "") . "$minutes minutes " : "") . "$seconds seconds";
    }

    public static function getTimeLeft($time): string
    {
        $remaning = $time - time();

        $s = $remaning % 60;
        $m = null;
        $h = null;
        $days = null;
        $months = null;
        $years = null;

        if ($remaning >= 60) {
            $m = floor(($remaning % 3600) / 60);
            if ($remaning >= 3600) {
                $h = floor(($remaning % 86400) / 3600);
                if ($remaning >= 3600 * 24) {
                    $days = floor($remaning / 86400);
                    if ($remaning >= 3600 * 24 * 30) {
                        $months = floor($remaning / (3600 * 24 * 30));
                        if ($remaning >= 3600 * 24 * 365) {
                            $years = floor($remaning / (3600 * 24 * 365));
                        }
                    }
                }
            }
        }
        return ($m !== null ? ($h !== null ? ($days !== null ? ($months !== null ? ($years !== null ? "$years years " : "") . "$months months " : "") . "$days days " : "") . "$h hours " : "") . "$m minutes " : "") . "$s seconds";
    }
}