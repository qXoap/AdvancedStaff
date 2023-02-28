<?php

namespace xoapp\advanced\session\types;

use pocketmine\player\Player;

class Vanish {

    public Player $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }
}