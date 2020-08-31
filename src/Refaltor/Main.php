<?php
	
	namespace Refaltor;
	
	use pocketmine\item\Item;
	use pocketmine\Server;
	use pocketmine\Player;
	use pocketmine\plugin\PluginBase;
	use pocketmine\event\Listener;
	use pocketmine\event\player\PlayerInteractEvent;
	use pocketmine\entity\Effect;
	use pocketmine\entity\EffectInstance;
	use pocketmine\plugin\Plugin;
	use pocketmine\utils\Config;
	
	class Main extends PluginBase implements Listener{
	 
	 public $config;
	 public $cooldownList = [];
	 
	 public function onEnable(){
	  
	  @mkdir($this->getDataFolder());
	  if(!file_exists($this->getDataFolder()."config.yml")){
	  
	  $this->saveResource('config.yml');
	 
	  }
	  
	  $this->config = new Config($this->getDataFolder().'config.yml', Config::YAML);
	  
	  $addvie = $this->getConfig()->get("Vie ajouté");
	  $MaxVie = $this->getConfig()->get("Vie maximum");
	  $id = $this->getConfig()->get("ID");
	  $remove = $this->getConfig()->get("Remove HealStick");
	  
	  $this->getLogger()->notice("HealStick setup sur l'ID $id , il ajoutera $addvie de points de vies si le joueur est en dessous de $MaxVie de points de vie");
	  
	  if($this->getConfig()->get("Remove HealStick") === true){
	   $this->getLogger()->notice("Remove HealStick activé");
	  }else{
	      $this->getLogger()->notice("Remove HealStick désactivé");
	  }
	  if($this->getConfig()->get("Cooldown")=== true){
	   $temps = $this->getConfig()->get("temps");
	   $this->getLogger()->notice("Cooldown sur " . $temps . " secondes");
	  }else{
	   $this->getLogger()->notice("Cooldown désactivé");
	  }
	  
	  $this->getLogger()->info("HealStick enable");
	  $this->getServer()->getPluginManager()->RegisterEvents($this, $this);
	 }
	 
	 public function onDisable(){
	  
	 }
	 
	 public function HealStick(PlayerInteractEvent $e){
	  
	  $item = $e->getItem();
	  $player = $e->getPlayer();
	  $player->getInventory()->getItemInHand();
	  $id = $this->getConfig()->get("ID");
	  $vie = $this->getConfig()->get("Vie maximum");
	  $addvie = $this->getConfig()->get("Vie ajouté");
	  if($this->getConfig()->get("Cooldown") === true){
	  if($item->getId() === $id){
	   
	   
	   if(!isset($this->cooldownList[$player->getName()])){
	    $time = $this->getConfig()->get("temps");
	    $this->cooldownList[$player->getName()] = time() + $time;
	   
	   if($player->getHealth() <= $vie){
	    if($this->getConfig()->get("Remove HealStick") === true){
	    $player->setHealth($player->getHealth() + $addvie);
	    $player->getInventory()->removeItem(Item::get($id, 0, 1));
	    $player->sendPopup("§c[HealStick] §7$addvie de points de vie ajouté !");
	    }else{
	    $player->setHealth($player->getHealth() + $addvie);
	    $player->sendPopup("§c[HealStick] §7$addvie de points de vie ajouté !");
	    }
	   }else{
	    $player->sendPopup("§c[HealStick] §7Trop de vie !");
	   }
	   }else{
	    if(time() < $this->cooldownList[$player->getName()]){
	     $temps = $this->cooldownList[$player->getName()] - time();
	    $player->sendPopup("§c[HealStick] §7Il reste " . $temps . " secondes");
	   }else{
	    unset($this->cooldownList[$player->getName()]);
	   }
	   }
	  }
	  }else{
	   
	   
	   
	   
	   if($item->getId() === $id){
	   if($player->getHealth() <= $vie){
	    if($this->getConfig()->get("Remove HealStick") === true){
	    $player->setHealth($player->getHealth() + $addvie);
	    $player->getInventory()->removeItem(Item::get($id, 0, 1));
	    $player->sendPopup("§c[HealStick] §7$addvie de points de vie ajouté !");
	    }else{
	    $player->setHealth($player->getHealth() + $addvie);
	    $player->sendPopup("§c[HealStick] §7$addvie de points de vie ajouté !");
	    }
	   }else{
	    $player->sendPopup("§c[HealStick] §7Trop de vie !");
	   }
	  }
	   
	  }
	}
	}
	
