<?php
class Horloge{
    
    //Array with all the anemones
    private $anemonesAndRate;
    
    
    public function __construct(){
    }
    
    /*Met a jour l'horloge de maniere sale
    *Recalcule tout les taux de tout les evenements
    */
    public function updateGlobal(){
       
    }
    
    
    
    //TODO
    public  function appliqueEvent($e){
        //TODO
    }
        
    
    /*
     * Methode : Trouve l'anemone $a dans la liste de toutes les anemones
     * update son taux de type $type en lui assignant $newRate
     */
    public function set($a_id, $type, $newRate){
        switch($type){
            case 'MortFemelle':
                $this->anemonesAndRate[$a_id][0] = $newRate;
                break;
            case 'MortMale':
                $this->anemonesAndRate[$a_id][1] = $newRate;
                break;
            case 'MortJuvenile':
                $this->anemonesAndRate[$a_id][2] = $newRate;
                break;
            case 'recrutementLagon1':
                $this->anemonesAndRate[$a_id][3] = $newRate;
                break;
            case 'recrutementLagon2':
                $this->anemonesAndRate[$a_id][4] = $newRate;
                break;
            case 'recrutementLagon3':
                $this->anemonesAndRate[$a_id][5] = $newRate;
                break;
            case 'recrutementLagon4':
                $this->anemonesAndRate[$a_id][6] = $newRate;
                break;
            case 'recrutementLagon5':
                $this->anemonesAndRate[$a_id][7] = $newRate;
                break;
            case 'recrutementLagon6':
                $this->anemonesAndRate[$a_id][8] = $newRate;
                break;
            case 'recrutementLagon7':
                $this->anemonesAndRate[$a_id][9] = $newRate;
                break;
            case 'Immigration':
                $this->anemonesAndRate[$a_id][10] = $newRate;
                break;
        }
    }
   
    
    public function get($a){
        
        return $this->anemonesAndRate[$a->get('id')];
    }
    public function getArray(){
        return $this->anemonesAndRate;
    }
    
    public function afficher(){
        
        echo("<p>----------Affichage de l'horloge----------</p>");
        
        $i = 1;
        while($i < 226){
            echo("<p> Anemone $i |");
            foreach ($this->anemonesAndRate[$i] as $value){
                echo(" $value ");
            }
            echo("</p>");
            $i++;
        }
    }
        
}

?>