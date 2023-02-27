<?php

namespace xoapp\advanced\async;

use pocketmine\scheduler\AsyncTask;

class PlayerCountryAsync extends AsyncTask {

    private $address;
    public $country;
    private $callable;

    public function __construct(string $address, Callable $callable)
    {
        $this->address = $address;
        $this->callable = $callable;
    }

    public function onRun(): void
    {
        $http = file_get_contents('http://www.geoplugin.net/php.gp?ip=' . $this->address);
        $handle = unserialize($http);
        $this->country = is_null($handle["geoplugin_countryName"]) ? "Unknown" : $handle["geoplugin_countryName"];
    }

    public function onCompletion(): void
    {
        call_user_func($this->callable, $this->country);
    }
}