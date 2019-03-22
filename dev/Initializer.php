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
}