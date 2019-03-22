<?php
/**
 * Created by PhpStorm.
 * User: znath
 * Date: 22/03/2019
 * Time: 09:13
 */

class PopulationCounter
{
    public static function getAll_population_lagon ($lagons, $anemones)
    {
        $lagons_population = array();

        foreach ($lagons as $lagon) {
            $l_id = $lagon->getId();
            $lagons_population[$l_id] = self::population_lagon($l_id, $anemones);
        }

        return $lagons_population;
    }

    private static function population_lagon ($l_id, $anemones)
    {
        $population = 0;
        foreach ($anemones as $a) {
            if (! $a->isDead) {

                $a_id = $a->getIdLagon();
                if ($a_id == $l_id) {
                    $population += $a->getActualCapacity();
                }
            }
        }
        return $population;
    }

}