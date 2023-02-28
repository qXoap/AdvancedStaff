<?php

namespace xoapp\advanced\item;

use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemIds;

class PlayerInfo extends Item {

    public function __construct()
    {
        parent::__construct(new ItemIdentifier(ItemIds::STICK, 0));
        $this->setCustomName("§6Player Information");
    }
}