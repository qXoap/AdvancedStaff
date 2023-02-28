<?php

namespace xoapp\advanced\forms;

use Forms\FormAPI\SimpleForm;
use pocketmine\Server;
use xoapp\advanced\player\Player;
use xoapp\advanced\utils\SystemUtils;

class TeleportForm extends SimpleForm {

    public function __construct()
    {
        parent::__construct(function (Player $player, $data = null) {
            if (is_null($data)) {
                return;
            }

            if (SystemUtils::equals($data, "close")) {
                return;
            }

            $result = Server::getInstance()->getPlayerExact($data);

            if (!$result instanceof Player) {
                $player->sendMessage(SystemUtils::PREFIX . "This Player Is Not Online");
                return;
            }

            if (SystemUtils::equals($result->getName(), $player->getName())) {
                return;
            }

            $player->teleport($result->getPosition());
            $player->sendMessage(SystemUtils::PREFIX . "You have teleported to §e" . $result->getName());
        });
        $this->setTitle("Player List");
        $this->addButton("Close", 0, "textures/ui/redX1", "close");
        foreach (SystemUtils::getEveryone() as $player) {
            $this->addButton($player->getName() . "\nTap To Teleport", 0, "textures/ui/icon_steve", $player->getName());
        }
    }
}