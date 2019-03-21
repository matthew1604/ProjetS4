<?php
require_once 'Lagon.php';
require_once 'Anemone.php';
require_once 'Horloge.php';
require_once 'Utils.php';
require_once 'Event.php';
require_once 'Initializer.php';

class algo
{

    private $horloge;

    private $lagons;

    private $anemones;

    public function __construct()
    {
        $l_file = "../doc/lagon.ini";
        $a_file = "../doc/anemone.ini";

        $this->initAnemones($a_file);
        $this->initLagon($l_file);
        $this->initHorloge();
    }

    public function startSimulation($tmax, $echoooo)
    {

        // Definition de la date actuelle
        $startDate = date_create(date("Y/m/d h:i:sa"));
        // variable pour stocker le temps qui s'ecoulera
        $futureDate = new DateTime($startDate->format("Y/m/d h:i:sa"));

        $t = 0;
        $e = null;
        $lambda = null;
        $i = 0;
                
        while ($t < $tmax) {
            if ($echoooo) {
                echo ("_________________________________________________________________<br><br>");
                if ($t == 0){
                    echo ("<p>--    DATE DEBUT (format Y/mo/d h:min:sec) : " . date_format($futureDate, "Y/m/d h:i:sa") . "    --</p>");
                }
                else{
                    echo ("<p>--    DATE : " . date_format($futureDate, "Y/m/d h:i:sa") . "    --</p>");
                }
                echo ("-Iteration n $i-<br>");
            }

            $lambda = $this->tauxGlobal();

            $r = Utils::tireExp($lambda);
            $t = $t + $r;

            $interval = new DateInterval('PT' . (int) Utils::yearsToSeconds($r) . 'S'); // Cr�ation d'un interavalle de $r secondes
            $futureDate = date_add($futureDate, $interval); // Ajout de $interval a la date temporaire

            $e = $this->tireEvent();

            $this->appliqueEvent($e[0][0], $e[1], $echoooo);
            $this->updateHorloge($e[0][0]);
            $i ++;
        }

        if ($echoooo) {
            echo ("_________________________________________________________________<br><br>");

            echo ("FIN DE LA SIMULATION<br>");
            $interval = date_diff($startDate, $futureDate);
            echo ("<p>-   Temps ecoule pendant l'algo : " . $interval->format('%R%a days'));
            echo ("<p>-   Nombre d'evenement pendant ce temps : " . $i);
        }
    }

    /*
     * Initilisation des anemones avec le nom du fichier .ini en parametres
     *
     * Retourne une liste contenant toutes les anemones
     */
    public function initAnemones($fileName)
    {
        /*$array = Utils::getArrayFromIniAnemone($fileName);
        $indice = 1;
        foreach ($array as $key => $value) {
            $this->anemones[$indice] = new Anemone($key, $value);
            $indice ++;
        }*/
        $this->anemones = Initializer::initAnemones($fileName);
    }

    /*
     * Initilisation des lagons avec le nom du fichier .ini en parametres
     *
     * Retourne une liste contenant tout les lagons
     */
    public function initLagons($fileName)
    {
        /*$array = Utils::getArrayFromIniLagon($fileName);

        $indice = 1;
        foreach ($array as $key => $value) {
            $this->lagons[$indice] = new Lagon($key, $value);
            $indice ++;
        }*/

        $this->lagons = Initializer::initLagons($fileName);
        print_r("WARNING " . $this->lagons);
    }

    // --------A OPTIMISER EN METTANT A JOUR SEULEMENT LES TAUX CONCERNER---------------//
    /*
     * Mise a jour de l'horloge au niveau de l'anemone @a
     * Methode : On recalcule le taux de tous les evenements de @a
     */
    public function updateHorloge($a_id)
    {
        $a = $this->anemones[$a_id];
        $l_id = $a->get('idLagon');
        $l_a = $this->lagons[$l_id];

        $this->horloge->set($a_id, 'MortFemelle', $l_a->get('eF')
            ->get('rate'));
        $this->horloge->set($a_id, 'MortMale', $l_a->get('eM')
            ->get('rate'));
        $this->horloge->set($a_id, 'MortJuvenile', ($l_a->get('eJ')
            ->get('rate') * $a->nbJuvenile()));

        foreach ($this->lagons as $l) {
            $nbFemelle_l = $this->nbFemelle($l);
            $nb_femelle_la = $this->nbFemelle($l_a);
            if($nb_femelle_la == 0){
                $this->horloge->set($a_id, "recrutementLagon$l_id", 0);              
            }else $this->horloge->set($a_id, "recrutementLagon$l_id", ($this->tauxRecrutement($l, $l_a) * $nbFemelle_l) / $nb_femelle_la);
        }
        if($nb_femelle_la != 0){            
            $this->horloge->set($a_id, 'Immigration', $l_a->get('eI')->get('rate') / $nb_femelle_la);
        }else  $this->horloge->set($a_id, 'Immigration', 0);
        
    }

    /*
     * Mise a jour de l'horloge au niveau de l'anemone @a_id et de son taux n°@e_index
     * Methode : Detecte quel taux a été modifié et met a jour ce qu'il affecte.
     */
    public function updateHorlogeWithEvent($a_id, $e_index)
    {
        $a = $this->anemones[$a_id];
        $l_id = $a->get('idLagon');
        $l_a = $this->lagons[$l_id];
        // TODO
    }

    /*
     * Initialise l'horloge
     * Methode : Crée une nouvelle horloge
     * Description de cette methode dans la doc.
     */
    public function initHorloge()
    {
        $this->horloge = Initializer::initHorloge($this->anemones, $this->lagons);

        /*foreach ($this->anemones as $a) {
            $a_id = $a->get('id');

            $l_a = $this->lagon($a);
            $this->horloge->set($a_id, 'MortFemelle', $l_a->get('eF')
                ->get('rate'));
            $this->horloge->set($a_id, 'MortMale', $l_a->get('eM')
                ->get('rate'));
            $this->horloge->set($a_id, 'MortJuvenile', ($l_a->get('eJ')
                ->get('rate') * $a->nbJuvenile()));

            foreach ($this->lagons as $l) {
                $l_id = $l->get('id');
                $nbFemelle_l = $this->nbFemelle($l);
                $nbFemelle_l_a = $this->nbFemelle($l_a);
                $this->horloge->set($a_id, "recrutementLagon$l_id", ($this->tauxRecrutement($l, $l_a) * $nbFemelle_l) / $nbFemelle_l_a);
            }

            $this->horloge->set($a_id, 'Immigration', $l_a->get('eI')
                ->get('rate') / $nbFemelle_l_a);
        }*/
    }

    /*
     * Retourne le lagon associe a l'anemone
     */
    public function lagon($a)
    {
        foreach ($this->lagons as $l) {
            if ($l->get('id') == $a->get('idLagon')) {
                return $l;
            }
        }
        return null;
    }

    /*
     * Retourne le taux de recrutement du lagon $la dans $l;
     */
    public function tauxRecrutement($l, $la)
    {
        $la_id = $la->get('id');
        return $l->get("rL$la_id")->get('rate');
    }

    /*
     * Retourne le nombre de femelle dans un lagon
     */
    public function nbFemelle($l)
    {
        $nbFemelle = 0.0;
        foreach ($this->anemones as $a) {
            if ($a->get('idLagon') == $l->get('id')) {
                if ($a->get('actualCapacity') > 0) {
                    $nbFemelle += 1;
                }
            }
        }
        return $nbFemelle;
    }

    public function initParamSimu()
    {
        // TODO
    }

    /*
     * Applique l'event $e a l'anemone qui id == $a_id
     * Methode : Cherche dans toutes les anemones l'anemone avec l'id $a_id
     * Regarde le numéro de l'evenement et applique son effet a l'anemone
     */
    public function appliqueEvent($a_id, $e, $echo)
    {
        $anemones = null;
        $event = $e;
        foreach ($this->anemones as $a) {
            if ($a->get('id') == $a_id) {
                $anemones = $a;
            }
        }

        switch ($event) {
            // Case MortFemelle
            case 0:
                if($echo) echo ("<p>- Evenement : Mort femelle in : $a_id</p>");
                $anemones->kill();
                break;
            // Case MortMale
            case 1:
                if($echo)  echo ("<p>- Evenement : Mort Male in : $a_id</p>");
                $anemones->kill();
                break;
            // Case MortJuvenile
            case 2:
                if($echo)  echo ("<p>- Evenement : Mort Juvenile in : $a_id</p>");
                $anemones->kill();
                break;
            // Case rl1
            case 3:
                if($echo) echo ("<p>- Evenement : Recrutement from lagon 1 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl2
            case 4:
                if($echo) echo ("<p>- Evenement : Recrutement from lagon 2 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl3
            case 5:
                if($echo) echo ("<p>- Evenement : Recrutement from lagon 3 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl4
            case 6:
                if($echo) echo ("<p>- Evenement : Recrutement from lagon 4 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl5
            case 7:
                if($echo) echo ("<p>- Evenement : Recrutement from lagon 5 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl6
            case 8:
                if($echo) echo ("<p>- Evenement : Recrutement from lagon 6 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl7
            case 9:
                if($echo) echo ("<p>- Evenement : Recrutement from lagon 7 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case Immigration
            case 10:
                if($echo)  echo ("<p>- Evenement : Immigration in : $a_id</p>");
                $anemones->recruit();
                break;
        }
    }

    /*
     * Retourne le taux global d'une anemone
     */
    public function tauxAnemone($h, $a)
    {
        $anemones = $h->getArray();
        $ane = $anemones[$a];

        $taux = 0;
        foreach ($ane as $val) {
            $taux += $val;
        }
        return $taux;
    }

    /*
     * Calcul le taux global de l'horloge
     * Methode : Additionne les taux globaux de chaque lagon
     * retourne : taux global de l'horloge
     */
    public function tauxGlobal()
    {
        $lambda = 0;
        foreach ($this->anemones as $a) {
            $a_id = $a->get('id');
            
            $t = $this->tauxAnemone($this->horloge, $a_id);
            $lambda += $t;
        }
        return $lambda;
    }

    public function tireEvent()
    {
        $a = $this->tireAnemone($this->horloge);
        $e = $this->tireEventA($a[1]);

        return array(
            $a,
            $e
        );
    }

    // -------------- A OPTIMISER--------------------//
    /*
     * Tire une anemone dans @h
     * Methode : ??
     *
     * Return : une array avec en premiere case l'id de l'anemone et ensuite ses taux sous la meme forme qu'il sont dans l'horloge
     */
    public function tireAnemone($h)
    {
        $tauxGlobal = $this->tauxGlobal();

        $anemoneChoisi = null;

        $rand = Utils::random_float(0, $tauxGlobal);

        $echelle = 0;
        foreach ($this->anemones as $a) {

            $a_id = $a->get('id');

            $tauxA = $this->tauxAnemone($h, $a_id);

            if ($rand >= $echelle && $rand < $echelle + $tauxA) {
                $anemoneChoisi = $this->horloge->getArray()[$a_id];
                break;
            } else {
                $echelle = $echelle + $tauxA;
            }
        }

        return array(
            $a_id,
            $anemoneChoisi
        );
    }

    // -------------- A OPTIMISER--------------------//
    /*
     * Tire un evenement aleatoire sur l'anemone $a
     * Methode : Tire aléatoirement dans l'anemone un evenement en fonction des taux
     * Trouve l'indice de cette evenement pour identifier son 'type'
     * Retourne l'indice de l'evenement tiré
     */
    public function tireEventA($a)
    {
        $a_id = array_search($a, $this->horloge->getArray());

        $aneRate = $this->tauxAnemone($this->horloge, $a_id);
        $eventChoisi = null;

        $rand = Utils::random_float(0, $aneRate);

        $echelle = 0;
        foreach ($a as $value) {

            if ($rand >= $echelle && $rand < $echelle + $value) {
                $eventChoisi = array_search($value, $a);
                break;
            } else {
                $echelle = $echelle + $value;
            }
        }
        return $eventChoisi;
    }

    // GETTERS AND SETTERS
    public function get($p)
    {
        return $this->$p;
    }

    public function set($p, $v)
    {
        $this->$p = $v;
    }

    // TEST
    public static function testSimu($tmax, $echooo)
    {
        // Test
        $t = new algo();
        $t->startSimulation($tmax, $echooo);
    }

    public static function testHorloge()
    {
        $t = new algo();
        $t->initLagon('../doc/lagon.ini');
        $t->initAnemones('../doc/anemone.ini');
        $t->initHorloge();
        $t->horloge->afficher();
    }
}

// algo::testHorloge();
if(!isset($testGraph)) algo::testSimu(1, 1);
?>



