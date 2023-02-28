<?php

namespace xoapp\advanced\anticheat;

use CortexPE\DiscordWebhookAPI\Embed;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use xoapp\advanced\anticheat\session\ProtoypeSession;
use xoapp\advanced\player\Player;
use xoapp\advanced\utils\SystemUtils;

class Prototype {

    public static function debug(Player $cheater, string $cheatType, string $diff): void
    {
        foreach (SystemUtils::getEveryone() as $player) {
            if (!$player instanceof Player) return;
            if (ProtoypeSession::getInstance()->isRegister($player)) {
                $vl = ProtoypeSession::getInstance()->getValue($cheater);
                $player->sendMessage(SystemUtils::LOG . "Debug §7(§e" . $cheater->getName() . " §7: §e" . $cheatType . "§7) VL: §e" . $vl . " §8(§7diff:§e" . $diff . " §7ping:§e" . $cheater->getConnection() . "§8)");
            }
        }
        if (SystemUtils::equals($cheatType, "AutoClick")) {
            return;
        }
        self::debugDiscord($cheater, $cheatType, $diff);
    }

    public static function debugDiscord(Player $cheater, string $cheatType, string $diff): void
    {
        $webhook = new Webhook("webhook aqui");
        $message = new Message();
        $embed = new Embed();
        $embed->setColor(14177041);
        $embed->setTitle("Prototype");
        $embed->addField("Cheater Name", $cheater->getName());
        $embed->addField("Cheater Connection", $cheater->getConnection());
        $embed->addField("Cheat Type", $cheatType);
        $embed->addField("VL", ProtoypeSession::getInstance()->getValue($cheater));
        $embed->addField("Diff", $diff);
        $message->addEmbed($embed);
        $webhook->send($message);
    }
}