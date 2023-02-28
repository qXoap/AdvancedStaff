<?php

namespace xoapp\advanced\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;

class Freeze extends Item {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(ItemIds::ICE, 0));
        $this->setCustomName("§eFreeze");
    }
}