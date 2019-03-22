<?php

require_once("../Utils.php");

class UtilsTest extends PHPUnit\Framework\TestCase
{

    public function testExplodeLine()
    {
        //given
        $line = "0.184  0.164  0.187 0.451  0.236  0.09   0.081  0.163  0.135  0.065 33.207";
        $expected = array(
            0 => '0.184',
            1 => '0.164',
            2 => '0.187',
            3 => '0.451',
            4 => '0.236',
            5 => '0.09',
            6 => '0.081',
            7 => '0.163',
            8 => '0.135',
            9 => '0.065',
            10 => '33.207'
        );

        //when
        $actual = Utils::explodeLine($line);

        //then
        $this->assertEquals($expected, $actual);
    }

    public function testGetArrayFromIniAnemone() {
        //given
        $fileName = "../../doc/anemone_test.ini";
        $expected = array(
            1 => array(1 => "1", 2 => "5", 3=> "5\n"),
            2 => array(1 => "1", 2 => "6", 3=> "6\n"),
            3 => array(1 => "1", 2 => "4", 3=> "4\n"),
            4 => array(1 => "1", 2 => "5", 3=> "5\n"),
            5 => array(1 => "1", 2 => "4", 3=> "4"),
        );

        //when
        $actual = Utils::getArrayFromIniAnemone($fileName);

        //then
        $this->assertEquals($expected, $actual);
    }

    public function testGetArrayFromIniLagon() {
        //given
        $fileName = "../../doc/lagon_test.ini";
        $expected = array(
            1 => array(0 => "0.184",1 => "0.164",2=> "0.187",3=> "0.451",4=> "0.236", 5=>"0.09",6=> "0.081",7=> "0.163",8=> "0.135",9=> "0.065",10=>"33.207",11=>"\n"),
            2 => array(0 => "0.22",1 => "0.095",2=> "0.159",3=> "0.138",4=> "0.108", 5=>"0.087",6=> "0.026",7=> "0.054",8=> "0.13",9=> "0.126",10=>"28.577",11=>"\n"),
            3 => array(0 => "0.168",1 => "0.203",2=> "0.184",3=> "0.076",4=> "0.055", 5=>"0.173",6=> "0.044",7=> "0.008",8=> "0.",9=> "0.094",10=>"11.097")
        );

        //when
        $actual = Utils::getArrayFromIniLagon($fileName);

        //then
        $this->assertEquals($expected, $actual);
    }

    public function testRandom_float() {
        //given
        $minExpected = 2;
        $maxExpected = 4;



        //when
        $actual = Utils::random_float(2,4);

        //then
        $this->assertTrue($actual >= $minExpected);
        $this->assertTrue($actual <= $maxExpected);
    }

    public function testYearsToSeconds() {
        //given
        $years = 3;
        $expected = $years*86400*365.2425;

        //when
        $actual = Utils::yearsToSeconds(3);

        //then
        $this->assertEquals($expected, $actual);
    }

    public function testTireExp() {
        // log10(0) = INF donc inutile à tester

        //given
        $l = 640;
        $minExpected = - log10(1) / $l;
        $maxExpected = - log10(0) / $l;



        //when
        $actual = Utils::tireExp(640);


        //then
        $this->assertTrue($actual >= $minExpected);
        $this->assertTrue($actual <= $maxExpected);
    }

    public function testFileFinding() {
        //given
        $array1 = array(0, 0, 5, 1);
        $needle1 = 1;
        $expected1 = $array1[1];

        $array2 = array(0.5, 0, 1, 1);
        $needle2 = 1;
        $expected2 = $array2[3];

        $array3 = array(0, 0, 5, 1);
        $needle3 = 100;





        //when
        $actualFalse = Utils::fileFinding(0, array());
        $actualArrayEmpty = Utils::fileFinding(5, array());
        $actual1 = Utils::fileFinding($needle1, $array1);
        $actual2 = Utils::fileFinding($needle2, $array2);
        $actual3 = Utils::fileFinding($needle3, $array3);


        //then
        $this->assertFalse($actualFalse);
        $this->assertFalse($actualArrayEmpty);
        $this->assertEquals($expected1, $actual1);
        $this->assertEquals($expected2, $actual2);
        $this->assertFalse($actual3);
    }
}
