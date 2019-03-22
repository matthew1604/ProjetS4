<?php

require_once("Anemone.php");
require_once("Lagon.php");
class Initializer
{
    public static function initAnemones($fileName)
    {
        $array = Utils::getArrayFromIniAnemone($fileName);
        $indice = 1;
        $anemones = array();
        foreach ($array as $key => $value) {
            $anemones[$indice] = new Anemone($key, $value);
            $indice ++;
        }
        return $anemones;
    }

    public static function initLagons ($fileName) {
        $array = Utils::getArrayFromIniLagon($fileName);
        $indice = 1;
        $lagons = array();

        foreach ($array as $key => $value) {
            $lagons[$indice] = new Lagon($key, $value);
            $indice ++;
        }
        return $lagons;
    }

    public static function initHorloge($anemones, $lagons) {
        $horloge = new Horloge();

        foreach ($anemones as $a) {
            $a_id = $a->get('id');

            $l_a = algo::lagon($a);
            $horloge->set($a_id, 'MortFemelle', $l_a->get('eF')
                ->get('rate'));
            $horloge->set($a_id, 'MortMale', $l_a->get('eM')
                ->get('rate'));
            $horloge->set($a_id, 'MortJuvenile', ($l_a->get('eJ')
                    ->get('rate') * $a->nbJuvenile()));

            foreach ($lagons as $l) {
                $l_id = $l->get('id');
                $nbFemelle_l = nbFemelle($l);
                $nbFemelle_l_a = nbFemelle($l_a);
                $horloge->set($a_id, "recrutementLagon$l_id", (algo::tauxRecrutement($l, $l_a) * $nbFemelle_l) / $nbFemelle_l_a);
            }

            $horloge->set($a_id, 'Immigration', $l_a->get('eI')
                    ->get('rate') / $nbFemelle_l_a);
        }
        return $horloge;

    }

}