<?php

declare(strict_types=1);

namespace Zedstar16\NoGrief;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;

class NoGrief extends PluginBase implements Listener {

    public $nobreak = [];

	public function onEnable() : void{
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
	    $this->nobreak = [];
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
		switch($command->getName()){
			case "nogrief":
			    if($sender instanceof Player) {
                    $pn = $sender->getName();
                    if (isset($this->nobreak[$pn])) {
                        unset($this->nobreak[$pn]);
                        $sender->sendMessage(TextFormat::AQUA . "You have enabled breaking & placing of blocks");
                    } elseif (!isset($this->nobreak[$pn])) {
                        $this->nobreak[$pn] = true;
                        $sender->sendMessage(TextFormat::GREEN . "You have disabled breaking & placing of blocks");
                    }
                }else $sender->sendMessage(TextFormat::RED."Only players can use this command");
                return true;
			}
	return true;
	}

    /**
     * @param BlockBreakEvent $event
     * @ignoreCancelled True
     * Priority HIGH
     */
    public function onBreak(BlockBreakEvent $event){
	    $pn = $event->getPlayer()->getName();
	    if(isset($this->nobreak[$pn])){
	        $event->getPlayer()->sendPopup(TextFormat::RED."Block Breaking disabled");
	        $event->setCancelled();
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @ignoreCancelled True
     * Priority HIGH
     */
    public function onPlace(BlockPlaceEvent $event){
        $pn = $event->getPlayer()->getName();
        if(isset($this->nobreak[$pn])){
            $event->getPlayer()->sendPopup(TextFormat::RED."Block Placing disabled");
            $event->setCancelled();
        }
    }
}
