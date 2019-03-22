<?php


require_once ("Anemone.php");
class AnemoneForTest
{
    public static function recruit($actualCapacity, $capacityMax, $isDead)
    {
        if (!$isDead)
        {
            if ($actualCapacity < $capacityMax)
            {
                $actualCapacity ++;
            }
        }
        return $actualCapacity;
    }

    public static function kill($typeOfKill, $actualCapacity, $isDead)
    {
        if (! $isDead) {
            // Mort Juvenile
            if ($typeOfKill == 2 && $actualCapacity > 2) {
                $actualCapacity = $actualCapacity -1;
            } // Mort Male
            else if ($typeOfKill == 1 && $actualCapacity != 1) {
                $actualCapacity = $actualCapacity -1;
            } // Mort Femelle
            else if($typeOfKill == 0){

                $actualCapacity = $actualCapacity -1;
                if ($actualCapacity == 0) {
                    $isDead = true;
                }
            }
        }
        return $actualCapacity;
    }
}