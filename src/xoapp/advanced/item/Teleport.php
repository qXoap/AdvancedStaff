<?php

namespace xoapp\advanced\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;

class Teleport extends Item {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(ItemIds::COMPASS, 0));
        $this->setCustomName("§bTeleport");
    }
}