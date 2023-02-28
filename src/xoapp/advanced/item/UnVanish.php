<?php

namespace xoapp\advanced\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;

class UnVanish extends Item {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(351, 8));
        $this->setCustomName("§cUnVanish");
    }
}