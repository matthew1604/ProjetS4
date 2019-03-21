<?php
/**
 * Created by PhpStorm.
 * User: znath
 * Date: 19/03/2019
 * Time: 21:05
 */
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
        // TEST USELESS CAR
        // log10(0) = INF et log10(1) = 0

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
}
