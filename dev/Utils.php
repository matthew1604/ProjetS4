<?php
define("oneYearInS", 31556952);

// 86400*365.2425
class Utils
{
    private static $data_keyName = array();

    public static function tireExp ($l)
    {
        $random = Utils::random_float(0, 1);
        $randomLog = log10($random);
        $tempsAleat = - $randomLog / $l;
        return $tempsAleat;
    }

    public static function yearsToSeconds ($y)
    {
        $s = oneYearInS * $y;
        
        return $s;
    }

    public static function random_float ($min, $max)
    {
        return ($min + lcg_value() * (abs($max - $min)));
    }

    public static function getArrayFromIniAnemone ($fileName)
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

    public static function getArrayFromIniLagon ($fileName)
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
    public static function explodeLine ($l)
    {
        return preg_split('/ +/', $l);
    }

    public static function writeInFile ($fileName, $data)
    {
        $dataString = "";
        
        $nbData = count($data);
        
        
        for ($i = 0; $i < $nbData; $i ++) {
            $keyName = self::$data_keyName[$i];
            $dataString = $dataString . " " . $data[$keyName] .
                     "\r\n";
        }
        $dataString = $dataString . PHP_EOL;
        if (! is_writable($fileName)) {
            return "Impossible d'ecrire dans le fichier $fileName: ";
        }
        file_put_contents($fileName, $dataString);
        
        return $dataString;
    }
    
    public static function writeInFileSorted ($fileName, $data)
    {
        $dataString = "";
    
        $nbData = count($data);
    
        
        for ($i = 0; $i < $nbData; $i ++) {
            
            $iData = "";
            foreach ($data[$i] as $ar){
                $iData = $iData . $ar . " ";
            }
            
            //$keyName = self::$data_keyName[$i];
            $dataString = $dataString . " " . ($iData) .
            "\r\n";
        }
        $dataString = $dataString . PHP_EOL;
        if (! is_writable($fileName)) {
            return "Impossible d'ecrire dans le fichier $fileName: ";
        }
        file_put_contents($fileName, $dataString);
    
        return $dataString;
    }
    
    public static function appendInFile ($fileName, $data)
    {
        $nbData = count($data);
        $dataString = "\r\n" . $data[$nbData] . " ";
        
        self::$data_keyName = array_keys($data);
        
        for ($i = 0; $i < $nbData - 1; $i ++) {
            $keyName = self::$data_keyName[$i];
            $dataString = $dataString . $data[$keyName] . " ";
        }
        if (! is_writable($fileName)) {
            return WRITING_PROBLEMS;
        }
        file_put_contents($fileName, $dataString, FILE_APPEND);
        
        return $dataString;
    }

    public static function fileFinding ($needle, $arrayOfLines)
    {
        /*
         * $arrayOfLines contient une array avec chaque ligne du fichier
         * Les index pairs sont le temps et les impairs sont la population de chaque lagon.
         * exemple : {0, 1 120 2 456 3 230 4 19 5 45 6 98 7 180
         *           ,0.002, 1 119 2 456 3 230 4 19 5 45 6 98 7 180
         *           ,0.015, 1 119 2 457 3 230 4 19 5 45 6 98 7 180
         *           ,0.045, 1 120 2 457 3 230 4 19 5 45 6 98 7 180
         *
         */      
        $i = 0;
        if($needle != 0){

            while ($i <= sizeof($arrayOfLines)-1) {
                if (!empty($arrayOfLines)) { //correction: if pour passer les tests
                    $min = ((double)($arrayOfLines[$i])) / ($needle);
                    $max = ((double)$arrayOfLines[$i + 2]) / ($needle);
                    if ($min <= 1 && $max >= 1) {
                        if (1 - $min < ($max - 1)) {
                            return $arrayOfLines[$i + 1];
                        } else {
                            return $arrayOfLines[$i + 3];
                        }
                    } else {
                        $i = $i + 2;
                    }
                } else {
                    break;
                }
            }
        }
        return false;
    }

    //Si tmax = 0
    public static function sortingResults ($tmax, $fileName)
    {
        $nbSemaines = 10;
        $nbAffichage = $nbSemaines * $tmax;
        $diviseur = $tmax / $nbAffichage;
        
        $arrayOfLines = file($fileName);

        
        $needle = 0;
        
        $populations_array = array();
        while ($needle < $tmax) {
            
            $population = Utils::fileFinding($needle, $arrayOfLines);
            if ($population != false) {
                array_push($populations_array, array(
                        $needle, $population
                ));
            } 
            $needle = $needle + $diviseur;
        }
        //print_r($populations_array);
        return $populations_array;
    }
    
    public static function lagonPopSorted_array($fileName){
        $file = file($fileName);
        
        $results = array();
        
        for ($i = 0; $i < count($file); $i+=2){
            //values contient le temps et les 7 population 
            $line = $file[$i];
            $values = explode(" ", $line);
            unset($values[0]);
            //unset($values[9]); avant correction
            unset($values[10]); //après correction
            array_push($results, $values);               
        }
        return $results;
    }
}

?>