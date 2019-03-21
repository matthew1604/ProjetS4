<?php
class Event{
    //Rate of the event
    private $rate;
    
    //Name|Type of the event (use to search throught lagon Event)
	private $type;
	
	public function __construct($type, $r){
	    $this->rate = $r; 
		$this->type = $type;
	}
	
	public function getRate(){
	    return $this->rate;
	}
	
	public function getType(){
	    return $this->type;
	}
	public function get($p){
		return $this->$p;
	}
	
	public function setLambda($l){
	    $this->rate = $l;
	}
	
	public function afficher(){
	    echo("<p>- Evenement $this->type avec la probabilite : $this->rate</p>");
	}
	
}