<?php

require_once("Anemone.php");

require_once("Utils.php");


//WARNING : require_once rajouté sinon tests impossibles
require_once("Event.php");

class Lagon{
	
	private $id;
	//----TAUX----//
	//Taux mort femelle
	private $eF;
	//Taux mort juvenile
	private $eJ;
	//Taux mort male
	private $eM;
	//Taux immigration
	private $eI;
	//Taux recrutement par lagon (si id lagon == $this->idLagon c'est de l'auto-recrutement
	private $rL1;
	private $rL2;
	private $rL3;
	private $rL4;
	private $rL5;
	private $rL6;
	private $rL7;
	
	private $allEvent;
	
	public function __construct($id, $data){
		$this->id = $id;
		
		$this->eF = new Event('eF', $data[0]);
		$this->eM = new Event('eM', $data[1]);
		$this->eJ = new Event('eJ', $data[2]);
		$this->rL1 = new Event('rL1', $data[3]);
		$this->rL2 = new Event('rL2', $data[4]);
		$this->rL3 = new Event('rL3', $data[5]);
		$this->rL4 = new Event('rL4', $data[6]);
		$this->rL5 = new Event('rL5', $data[7]);
		$this->rL6 = new Event('rL6', $data[8]);
		$this->rL7 = new Event('rL7', $data[9]);
		$this->eI = new Event('eI', $data[10]);
		
		$this->allEvent = array($this->eF, $this->eM, $this->eJ,
				                $this->rL1 , $this->rL2,$this->rL3 ,
				                $this->rL4,$this->rL5 ,$this->rL6  ,
                                $this->rL7, $this->eI);
	}

	/* Met a jour le taux de chaque event dans $this
	 * 
	 */
	public function updateAllRate(){
	    //TODO
	}
	
	public function set($property, $other_p){
		$this->$property = $other_p;
	}
	
	
	/*
	 * Calcul le taux global de $this
	 */
	public function getRate(){
		$lambda = 0;
		$lambda+=$this->eF->get('rate');
		$lambda+=$this->eI->get('rate');
		$lambda+=$this->eM->get('rate');
		$lambda+=$this->eJ->get('rate');
		$lambda+=$this->rL1->get('rate');
		$lambda+=$this->rL2->get('rate');
		$lambda+=$this->rL3->get('rate');
		$lambda+=$this->rL4->get('rate');
		$lambda+=$this->rL5->get('rate');
		$lambda+=$this->rL6->get('rate');
		$lambda+=$this->rL7->get('rate');
		return $lambda;
	}
	
	public function getId(){
	    return $this->id;
	}
	public function get($p){
		return $this->$p;
	}
	
	
	public function afficher(){
	    echo("<p>----------------</p>");
		echo("<p>Le lagon $this->id a les evenements suivants : </p>");
		foreach ($this->allEvent as $e){
			$e->afficher();
		}
	}
	
	
		
}

	
?>
	