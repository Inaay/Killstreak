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

use Inaayat\Killstreak\Main;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\player\Player;

class EventListener implements Listener {

	public $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
	
	public function onDeath(PlayerDeathEvent $event) {
		$player = $event->getPlayer();
		$cause = $player->getLastDamageCause();
		if ($cause instanceof EntityDamageByEntityEvent) {
			$killer = $cause->getDamager();
			if ($killer instanceof Player && $player instanceof Player) {
				$killerName = $killer->getName();
				$this->plugin->resetKillstreak($player);
				$this->plugin->increaseKillstreak($killer);
				$killStreak = $this->plugin->getKillStreak($killer);
				$config = $this->plugin->getConfig()->get("killstreak");
				if (isset($config[$killStreak])) {
					$command = str_replace("{player}", $killerName, $config[$killStreak]["command"]);
					$command = str_replace("{milestone}", $config[$killStreak]["milestone"], $command);
					$this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender($this->plugin->getServer(), $this->plugin->getServer()->getLanguage()), $command);
					if ($this->plugin->getConfig()->get("Broadcast")) {
						$broadcastMessage = str_replace("{player}", $killerName, $this->plugin->getConfig()->get("BroadcastMessage"));
						$broadcastMessage = str_replace("{milestone}", $config[$killStreak]["milestone"], $broadcastMessage);
						$broadcastMessage = str_replace("&", "§", $broadcastMessage);
						$this->plugin->getServer()->broadcastMessage($this->plugin->getPrefix() . $broadcastMessage);
					}
				}
			}
		}
	}
}
