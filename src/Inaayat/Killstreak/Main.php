<?php

/**
 *
 *  ██ ███    ██  █████   █████  ██    ██  █████  ████████
 *  ██ ████   ██ ██   ██ ██   ██  ██  ██  ██   ██    ██
 *  ██ ██ ██  ██ ███████ ███████   ████   ███████    ██
 *  ██ ██  ██ ██ ██   ██ ██   ██    ██    ██   ██    ██
 *  ██ ██   ████ ██   ██ ██   ██    ██    ██   ██    ██

 *
 * @author Inaayat
 * @link https://github.com/Inaay
 *
 */

declare(strict_types=1);

namespace Inaayat\Killstreak;

use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase {

	private $killstreaks = [];

    public function onEnable(): void {
        $this->saveDefaultConfig();
        $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
    }

	public function getPrefix(): string {
		return "§7[§cKillstreak§7]§r ";
	}

	public function increaseKillstreak(Player $player): void {
        $uuid = $player->getUniqueId()->toString();
        if (!isset($this->killstreaks[$uuid])) {
            $this->killstreaks[$uuid] = 1;
        } else {
            $this->killstreaks[$uuid]++;
        }
    }
    
    public function getKillStreak(Player $player): int {
        $uuid = $player->getUniqueId()->toString();
        return isset($this->killstreaks[$uuid]) ? $this->killstreaks[$uuid] : 0;
    }

	public function resetKillstreak(Player $player): void {
		$uuid = $player->getUniqueId()->toString();
		unset($this->killstreaks[$uuid]);
	}
}