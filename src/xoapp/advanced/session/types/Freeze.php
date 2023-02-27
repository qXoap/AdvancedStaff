<?php

namespace xoapp\advanced\session\types;

use pocketmine\player\Player;

class Freeze {

    public Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }
}