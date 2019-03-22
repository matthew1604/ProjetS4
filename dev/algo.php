<?php
require_once 'Lagon.php';
require_once 'Anemone.php';
require_once 'Horloge.php';
require_once 'Utils.php';
require_once 'Event.php';

ini_set('max_execution_time', 300);

class algo
{

    private $horloge;

    private $lagons;

    private $anemones;

    public function __construct ()
    {
        $l_file = "../doc/lagon.ini";
        $a_file = "../doc/anemone.ini";

        $this->initAnemones($a_file);
        $this->initLagon($l_file);
        $this->initHorloge();
    }

    public function startSimulation ($tmax, $echoooo)
    {

        // Definition de la date actuelle
        $startDate = date_create(date("Y/m/d h:i:sa"));
        // variable pour stocker le temps qui s'ecoulera
        $futureDate = new DateTime($startDate->format("Y/m/d h:i:sa"));

        $t = 0;
        $e = null;
        $lambda = null;
        $i = 0;

        // Reset des fichier de population
        Utils::writeInFile('../output/lagonsPopulationT.ini', array());

        while ($t < $tmax) {

            if ($echoooo) {
                echo ("_________________________________________________________________<br><br>");
                if ($t == 0) {
                    echo ("Lagon id : population");

                    $lp = $this->getAll_population_lagon();
                    foreach ($this->lagons as $l) {
                        echo ("<br>" . $l->getId() . " : " . $lp[$l->getId()]);
                    }
                    echo ("<br>____________<br><br><p>--    DATE DEBUT (format Y/mo/d h:min:sec) : " .
                        date_format($futureDate, "Y/m/d h:i:sa") .
                        "    --</p>");
                } else {
                    echo ("<p>--    DATE : " .
                        date_format($futureDate, "Y/m/d h:i:sa") .
                        "    --</p><p>T : $t</p> ");
                }
                echo ("-Iteration n $i-<br>");
            }

            $lambda = $this->tauxGlobal();

            $r = Utils::tireExp($lambda);
            $t = $t + $r;

            $interval = new DateInterval(
                'PT' . (int) Utils::yearsToSeconds($r) . 'S');

            // Création d'un interavalle de $r secondes

            $futureDate = date_add($futureDate, $interval); // Ajout de
            // $interval a la
            // date temporaire

            $e = $this->tireEvent();

            $this->appliqueEvent($e[0][0], $e[1], $echoooo);
            $this->updateHorloge($e[0][0]);

            $lp = $this->getAll_population_lagon();
            $lp[count($lp) + 1] = $t;
            Utils::appendInFile('../output/lagonsPopulationT.ini', $lp);

            $i ++;
        }

        if ($echoooo) {
            echo ("_________________________________________________________________<br><br>");
            echo ("FIN DE LA SIMULATION<br>");
            $interval = date_diff($startDate, $futureDate);
            echo ("<p>-   Temps ecoule pendant l'algo : " .
                $interval->format('%R%a days'));
            echo ("</p><p>-   Nombre d'evenement pendant ce temps : " . $i);

            $nbDead = 0;
            foreach ($this->anemones as $a) {
                if ($a->getActualCapacity() <= 0) {
                    $nbDead ++;
                }
            }
            echo ("<br><br>--- Nombre de mortes : $nbDead<br>");

            echo ("</p>Lagon id : population");

            foreach ($this->lagons as $l) {
                echo ("<br>" . $l->getId() . " : " . $lp[$l->getId()]);
            }
        }

        $p = Utils::sortingResults($tmax, '../output/lagonsPopulationT.ini');

        Utils::writeInFileSorted('../output/lagonPopulationSortedOnceAWeek.ini', $p);
    }

    /*
     * Initilisation des anemones avec le nom du fichier .ini en parametres
     *
     * Retourne une liste contenant toutes les anemones
     */
    private function initAnemones ($fileName)
    {
        $array = Utils::getArrayFromIniAnemone($fileName);
        $indice = 1;
        foreach ($array as $key => $value) {
            $this->anemones[$indice] = new Anemone($key, $value);
            $indice ++;
        }
    }

    /*
     * Initilisation des lagons avec le nom du fichier .ini en parametres
     *
     * Retourne une liste contenant tout les lagons
     */
    private function initLagon ($fileName)
    {
        $array = Utils::getArrayFromIniLagon($fileName);

        $indice = 1;
        foreach ($array as $key => $value) {
            $this->lagons[$indice] = new Lagon($key, $value);
            $indice ++;
        }
    }

    // --------------A OPTIMISER EN METTANT A JOUR SEULEMENT LES TAUX
    // CONCERNER------------------------//
    // --------METTRE A JOUR LES TAUX DES AUTRES ANEMONES QUI SONT IMPACTES PAR
    // CELLE CI---------------//

    /*
     * Mise a jour de l'horloge au niveau de l'anemone @a
     * Methode : On recalcule le taux de tous les evenements de @a
     *
     */
    private function updateHorloge ($a_id)
    {
        $a = $this->anemones[$a_id];

        $l_id = $a->getIdLagon();
        $l_a = $this->lagons[$l_id];
        // Si l'anemone est morte (ie 0 population)
        // Alors cela impacte aussi les taux des autres lagons.

        if ($a->isDead) {
            $isAlreadyDead = $this->horloge->setDead($a_id);

            // Si l'anemone n'avais pas dï¿½ja ses taux a 0 dans l'horloge
            if (! $isAlreadyDead) {
                // Mettre a jour les taux en rapport avec le lagon $l_a
                foreach ($this->anemones as $aT) {
                    $l = $this->lagons[$aT->getIdLagon()];
                    $nbFemelle_l = $this->nbFemelle($l);
                    $nb_femelle_la = $this->nbFemelle($l_a);
                    if ($nb_femelle_la == 0) {
                        $this->horloge->set($aT->getId(),
                            "recrutementLagon$l_id", 0);
                    } else {
                        $this->horloge->set($aT->getId(),
                            "recrutementLagon$l_id",
                            ($this->tauxRecrutement($l, $l_a) * $nbFemelle_l) /
                            $nb_femelle_la);
                    }
                }
            }
        } else {

            $this->horloge->set($a_id, 'MortJuvenile',
                ($l_a->get('eJ')
                        ->getRate() * $a->nbJuvenile()));

            /*
             * Si l'anemone n'est pas morte cela ne sert a rien de mettre a jour
             * ses taux ??//
             */
        }

    }

    /*
     * Initialise l'horloge
     * Methode : Crée une nouvelle horloge
     * Description de cette methode dans la doc.
     */
    private function initHorloge ()
    {
        $this->horloge = new Horloge();

        foreach ($this->anemones as $a) {
            $a_id = $a->getId();

            $l_a = $this->lagon($a);
            $this->horloge->set($a_id, 'MortFemelle',
                $l_a->get('eF')
                    ->getRate());
            $this->horloge->set($a_id, 'MortMale',
                $l_a->get('eM')
                    ->getRate());
            $this->horloge->set($a_id, 'MortJuvenile',
                ($l_a->get('eJ')
                        ->getRate() * $a->nbJuvenile()));

            foreach ($this->lagons as $l) {
                $l_id = $l->getId();
                $nbFemelle_l = $this->nbFemelle($l);
                $nbFemelle_l_a = $this->nbFemelle($l_a);
                $this->horloge->set($a_id, "recrutementLagon$l_id",
                    ($this->tauxRecrutement($l, $l_a) * $nbFemelle_l) /
                    $nbFemelle_l_a);
            }

            $this->horloge->set($a_id, 'Immigration',
                $l_a->get('eI')
                    ->getRate() / $nbFemelle_l_a);
        }

        //print_r($this->horloge);
    }

    /*
     * Retourne le lagon associe a l'anemone
     */
    private function lagon ($a)
    {
        $idL = $a->getIdLagon();
        foreach ($this->lagons as $l) {
            if ($l->getId() == $idL) {
                return $l;
            }
        }
        return null;
    }

    /*
     * Retourne le taux de recrutement du lagon $la dans $l;
     */
    private function tauxRecrutement ($l, $la)
    {
        $la_id = $la->getId();
        return $l->get("rL$la_id")->getRate();
    }

    public function getAll_population_lagon ()
    {
        $lagons_population = array();

        foreach ($this->lagons as $lagon) {
            $l_id = $lagon->getId();
            $lagons_population[$l_id] = $this->population_lagon($l_id);
        }

        return $lagons_population;
    }

    /*
     * Retourne la population totale d'un lagon
     */
    public function population_lagon ($l_id)
    {
        $population = 0;
        foreach ($this->anemones as $a) {
            if (! $a->isDead) {

                $a_id = $a->getIdLagon();
                if ($a_id == $l_id) {
                    $population += $a->getActualCapacity();
                }
            }
        }
        return $population;
    }

    /*
     * Retourne le nombre de femelle dans un lagon
     */
    private function nbFemelle ($l)
    {
        $nbFemelle = 0;

        $id_l = $l->getId();
        foreach ($this->anemones as $a) {
            if (! $a->isDead) {
                $a_id = $a->getIdLagon();
                if ($a_id == $id_l) {
                    if ($a->getActualCapacity() > 0) {
                        $nbFemelle += 1;
                    }
                }
            }
        }
        return $nbFemelle;
    }

    private function initParamSimu ()
    {
        // TODO
    }

    /*
     * Applique l'event $e a l'anemone qui id == $a_id
     * Methode : Cherche dans toutes les anemones l'anemone avec l'id $a_id
     * Regarde le numÃ©ro de l'evenement et applique son effet a l'anemone
     */
    private function appliqueEvent ($a_id, $e, $echo)
    {
        $anemones = null;
        $event = $e;

        $anemones = $this->anemones[$a_id];

        switch ($event) {
            // Case MortFemelle
            case 0:
                if ($echo)
                    echo ("<p>- Evenement : Mort femelle in : $a_id</p>");
                $anemones->kill($event);
                break;
            // Case MortMale
            case 1:
                if ($echo)
                    echo ("<p>- Evenement : Mort Male in : $a_id</p>");
                $anemones->kill($event);
                break;
            // Case MortJuvenile
            case 2:
                if ($echo)
                    echo ("<p>- Evenement : Mort Juvenile in : $a_id</p>");
                $anemones->kill($event);
                break;
            // Case rl1
            case 3:
                if ($echo)
                    echo ("<p>- Evenement : Recrutement from lagon 1 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl2
            case 4:
                if ($echo)
                    echo ("<p>- Evenement : Recrutement from lagon 2 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl3
            case 5:
                if ($echo)
                    echo ("<p>- Evenement : Recrutement from lagon 3 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl4
            case 6:
                if ($echo)
                    echo ("<p>- Evenement : Recrutement from lagon 4 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl5
            case 7:
                if ($echo)
                    echo ("<p>- Evenement : Recrutement from lagon 5 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl6
            case 8:
                if ($echo)
                    echo ("<p>- Evenement : Recrutement from lagon 6 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case rl7
            case 9:
                if ($echo)
                    echo ("<p>- Evenement : Recrutement from lagon 7 in : $a_id</p>");
                $anemones->recruit();
                break;
            // Case Immigration
            case 10:
                if ($echo)
                    echo ("<p>- Evenement : Immigration in : $a_id</p>");
                $anemones->recruit();
                break;
            default:
                echo ("<br> ERRORS : Event n$event n'existe pas<br>");
                break;
        }
    }

    /*
     * Retourne le taux global d'une anemone
     */
    private function tauxAnemone ($h, $a_id)
    {
        $anemones = $h->getArray();
        $ane = $anemones[$a_id];

        $tauxA = 0;
        foreach ($ane as $val) {
            $tauxA += $val;
        }
        return $tauxA;
    }

    /*
     * Calcul le taux global de l'horloge
     * Methode : Additionne les taux globaux de chaque lagon
     * retourne : taux global de l'horloge
     */
    private function tauxGlobal ()
    {
        $lambda = 0;
        foreach ($this->anemones as $a) {
            $a_id = $a->getId();

            $t = $this->tauxAnemone($this->horloge, $a_id);
            $lambda += $t;
        }
        return $lambda;
    }

    private function tireEvent ()
    {
        $a = $this->tireAnemone($this->horloge);
        $e = $this->tireEventA($a[1], $a[0]);

        return array(
            $a,
            $e
        );
    }

    /*
     * Tire une anemone dans @h
     * Methode : ??
     *
     * Return : une array avec en premiere case l'id de l'anemone et ensuite ses
     * taux sous la meme forme qu'il sont dans l'horloge
     */
    private function tireAnemone ($h)
    {
        $tauxGlobal = $this->tauxGlobal();

        $anemoneChoisi = null;

        $rand = Utils::random_float(0, $tauxGlobal);

        $echelle = 0;
        foreach ($this->anemones as $a) {

            $a_id = $a->getId();

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
     * Methode : Tire alÃ©atoirement dans l'anemone un evenement en fonction des
     * taux
     * Trouve l'indice de cette evenement pour identifier son 'type'
     * Retourne l'indice de l'evenement tirÃ©
     */
    private function tireEventA ($a, $a_id)
    {
        $aneRate = $this->tauxAnemone($this->horloge, $a_id);
        $eventChoisi = null;

        $rand = Utils::random_float(0, $aneRate);

        $echelle = 0;
        $idEvent = 0;
        foreach ($a as $value) {

            if ($rand >= $echelle && $rand < $echelle + $value) {

                $eventChoisi = $idEvent;
                break;
            } else {
                $echelle = $echelle + $value;
            }
            $idEvent ++;
        }
        return $eventChoisi;
    }

    // GETTERS AND SETTERS
    public function get ($p)
    {
        return $this->$p;
    }

    public function set ($p, $v)
    {
        $this->$p = $v;
    }

    // TEST
    public static function testSimu ($tmax, $echooo)
    {
        $time_start = microtime(true);

        // Test
        $t = new algo();
        $t->startSimulation($tmax, $echooo);

        $time_end = microtime(true);
        $time = $time_end - $time_start;
        echo "<br><br>__________Process Time: {$time} seconds take to simulate $tmax year(s)________________";
    }

    public static function testHorloge ()
    {
        $t = new algo();
        $t->initLagon('../doc/lagon.ini');
        $t->initAnemones('../doc/anemone.ini');
        $t->initHorloge();
        $t->horloge->afficher();
    }
}

// algo::testHorloge();

if (! isset($testGraph)) {
    if (! isset($tmax)) {
        algo::testSimu(5, 1);
    } else
        algo::testSimu($tmax, 1);
} else
    if ($testGraph == false) {

        if (! isset($tmax)) {
            algo::testSimu(5, 1);
        } else
            algo::testSimu($tmax, 1);
    }
?>



