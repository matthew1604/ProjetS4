<?php


require_once("../Anemone.php");
require_once("../AnemoneForTest.php");

class AnemoneTest extends PHPUnit\Framework\TestCase
{
    public function testNbJuvenile () {
        //given
        $data1 = array(
            1=>1,
            2=>7,
            3=>5
        );
        $anemone1 = new Anemone(1,$data1);
        $expected1 = 3;

        $data2 = array(
            1=>1,
            2=>3,
            3=>1
        );
        $anemone2 = new Anemone(2,$data2);
        $expected2 = 0;



        //when
        $actual1 = $anemone1->nbJuvenile();
        $actual2 = $anemone2->nbJuvenile();

        //then
        $this->assertEquals($expected1,$actual1);
        $this->assertEquals($expected2,$actual2);
    }

    public function testRecruit() {
        //given
        $expected1 = 4;
        $expected2 = 3;
        $expected3 = 4;
        $expected4 = 2;


        //when
        $actual1 = AnemoneForTest::recruit(4,4,false);
        $actual2 = AnemoneForTest::recruit(2,4,false);
        $actual3 = AnemoneForTest::recruit(4,4,true);
        $actual4 = AnemoneForTest::recruit(2,4,true);


        //then
        $this->assertEquals($expected1,$actual1);
        $this->assertEquals($expected2,$actual2);
        $this->assertEquals($expected3,$actual3);
        $this->assertEquals($expected4,$actual4);
    }

    public function testKill() {
        //given
        $expected1 = 3;
        $expected2 = 3;
        $expected3 = 3;

        $expected4 = 4;

        $expected5 = 1;
        $expected6 = 1;

        //when
        $actual1 = AnemoneForTest::kill(0,4,false);
        $actual2 = AnemoneForTest::kill(1,4,false);
        $actual3 = AnemoneForTest::kill(2,4,false);

        $actual4 = AnemoneForTest::kill(0,4,true);

        $actual5 = AnemoneForTest::kill(1,1,false);
        $actual6 = AnemoneForTest::kill(2,1,false);



        //then
        $this->assertEquals($expected1,$actual1);
        $this->assertEquals($expected2,$actual2);
        $this->assertEquals($expected3,$actual3);
        $this->assertEquals($expected4,$actual4);
        $this->assertEquals($expected5,$actual5);
        $this->assertEquals($expected6,$actual6);

    }
}
