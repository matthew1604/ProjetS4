<?php

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

    private $isDead = false;

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

    public function get($property)
    {
        return $this->$property;
    }

    public function set($p, $v)
    {
        $this->$p = $v;
    }

    public function kill()
    {
        if (!$this->isDead) {
            
            $this->actualCapacity -= 1;
            if ($this->actualCapacity <= 1) {
                $this->isDead = true;
                $this->actualCapacity = 0;
            }
        }
    }

    public function recruit()
    {
        if (!$this->isDead) {
            if ($this->actualCapacity < $this->capacityMax) {
                $this->actualCapacity ++;
            }
        }
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

