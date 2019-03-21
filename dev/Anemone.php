<?php
require_once("AnemoneForTest.php");

class Anemone
{

    // ----DATA----//
    // Number of the anemone
    private $id;

    // Id of the lagon (in wich the anemone is)
    private $idLagon;

    // max capacity of the anemone
    private $capacityMax;

    // number of fish in the anemone in real time
    private $actualCapacity;

    public $isDead = false;

    public function __construct($i, $data)
    {
        $this->id = $i;
        $this->idLagon = $data[1];
        $this->capacityMax = $data[2];
        $this->actualCapacity = $data[3];
    }

    public function nbJuvenile()
    {
        if ($this->actualCapacity >= 2) {
            return $this->actualCapacity - 2;
        } else
            return 0;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getIdLagon()
    {
        return $this->idLagon;
    }

    public function getActualCapacity()
    {
        return $this->actualCapacity;
    }

    public function isDead()
    {
        return $this->isDead;
    }

    public function get($property)
    {
        return $this->$property;
    }

    public function set($p, $v)
    {
        $this->$p = $v;
    }

    /*
     * Mort dans l'anemone
     * Methode : Si l'anemone n'est pas morte on décremente sa population
     * Si sa population est égale a 1 (cad plus qu'une femelle)
     * Alors l'anemone ne peut plus survivre et meurt.
     */
    public function kill($typeOfKill)
    {
        $this->actualCapacity = AnemoneForTest::kill($typeOfKill,$this->actualCapacity,$this->isDead);
        /*if (! $this->isDead) {
            // Mort Juvenile
            if ($typeOfKill == 2 && $this->actualCapacity > 2) {
                $this->actualCapacity = $this->actualCapacity -1;
            } // Mort Male
            else if ($typeOfKill == 1 && $this->actualCapacity != 1) {
                $this->actualCapacity = $this->actualCapacity -1;
            } // Mort Femelle
            else if($typeOfKill == 0){
                
                $this->actualCapacity = $this->actualCapacity -1;
                if ($this->actualCapacity == 0) {
                    $this->isDead = true;
                }
            }
        }*/
    }

    /*
     * Recrutement dans l'anemone
     * Si l'anemone n'est pas morte et non pleine on incr�mente si population
     */
    public function recruit()
    {
        $this->actualCapacity = AnemoneForTest::recruit($this->actualCapacity,$this->capacityMax,$this->isDead);
        /*if (! $this->isDead) {
            if ($this->actualCapacity < $this->capacityMax) {
                $this->actualCapacity ++;
            }
        }*/
    }

    public function afficher()
    {
        echo ("<p>-----------</p>");
        echo ("<p>Anemone $this->id</p>");
        echo ("<p>-IdLagon : $this->idLagon</p>");
        echo ("<p>-Capacite d'accueil : $this->capacityMax</p>");
        echo ("<p>-population actuelle : $this->actualCapacity</p>");
    }
}
?>

