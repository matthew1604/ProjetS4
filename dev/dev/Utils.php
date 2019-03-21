<?php
define("oneYearInS",  31556952); // 86400*365.2425

class Utils
{
    public static function tireExp($l)
    {
        $random = self::random_float(0, 1);
        $randomLog = log10($random);
        $tempsAleat = - $randomLog / $l;
        return $tempsAleat;
    }
    
    public static function yearsToSeconds($y){
        
        $s = oneYearInS * $y;
        
        return $s;
    }

    public static function random_float($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }

    public static function getArrayFromIniAnemone($fileName)
    {
        $array = array();
        $line = null;
        $file = new SplFileObject($fileName);

        // Loop until we reach the end of the file.
        while (! $file->eof()) {
            $line = $file->fgets();

            $dataFromline = Utils::explodeLine($line);
            $id = $dataFromline[0];
            unset($dataFromline[0]);
            $array[$id] = $dataFromline;
        }
        // Unset the file to call __destruct(), closing the file handle.
        $file = null;

        return $array;
    }

    public static function getArrayFromIniLagon($fileName)
    {
        $array = array();
        $line = null;
        $id = 1;
        $file = new SplFileObject($fileName);

        // Loop until we reach the end of the file.
        while (! $file->eof()) {
            $line = $file->fgets();

            $dataFromline = Utils::explodeLine($line);
            $array[$id] = $dataFromline;
            $id ++;
        }

        // Unset the file to call __destruct(), closing the file handle.
        $file = null;

        return $array;
    }

    /*
     * split d'un string avec deux espace comme param�tre de split
     * retourne une array contenant chaque valeur split�
     */
    public static function explodeLine($l)
    {
        return preg_split('/ +/', $l);
    }
}

?>