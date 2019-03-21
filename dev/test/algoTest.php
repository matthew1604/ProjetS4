<?php
/**
 * Created by PhpStorm.
 * User: znath
 * Date: 19/03/2019
 * Time: 22:56
 */

require_once("../Initializer.php");


class algoTest extends PHPUnit\Framework\TestCase
{
    public function testInitAnemones() {
        //given
        $fileName = "../../doc/anemone_test.ini";

        $data1 = array("1", "1", "5", "5\n");
        $data2 = array("2", "1", "6", "6\n");
        $data3 = array("3", "1", "4", "4\n");
        $data4 = array("4", "1", "5", "5\n");
        $data5 = array("5", "1", "4", "4");

        $anemone1 = new Anemone(1,$data1);
        $anemone2 = new Anemone(2,$data2);
        $anemone3 = new Anemone(3,$data3);
        $anemone4 = new Anemone(4,$data4);
        $anemone5 = new Anemone(5,$data5);

        $expected = array(
            1=> $anemone1,
            2=> $anemone2,
            3=> $anemone3,
            4=> $anemone4,
            5=> $anemone5
        );

        //when
        $actual = Initializer::initAnemones($fileName);

        //then
        $this->assertEquals($expected[1],$actual[1]);
    }

    public function testInitLagons() {
        //given
        $fileName = "../../doc/lagon_test.ini";

        $data1 = array(0.184,  0.164,  0.187, 0.451,  0.236,  0.09,   0.081,  0.163,  0.135,  0.065, 33.207);
        $data2 = array(0.22,   0.095,  0.159, 0.138,  0.108,  0.087,  0.026,  0.054,  0.13,   0.126, 28.577);
        $data3 = array(0.168,  0.203,  0.184, 0.076,  0.055,  0.173,  0.044,  0.008,  0.,     0.094, 11.097);

        $lagon1 = new Lagon(1,$data1);
        $lagon2 = new Lagon(2,$data2);
        $lagon3 = new Lagon(3,$data3);

        $expected = array(
            1=>$lagon1,
            2=>$lagon2,
            3=>$lagon3,
        );

        //when
        $actual = Initializer::initLagons($fileName);

        //then
        $this->assertEquals($expected,$actual);

    }

}
