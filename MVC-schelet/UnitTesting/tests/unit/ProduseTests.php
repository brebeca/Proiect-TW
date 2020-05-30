<?php


use PHPUnit\Framework\TestCase;
include 'config.php';
require MODEL.'produseModel.php';
class ProduseTests extends TestCase
{
    public function testCautaProdusFail_wrongParams(){
        $this->assertEquals(false,produseModel::cautaProdus('',''));
    }

    public function testCautaProdusSucces(){
        $this->assertIsArray(produseModel::cautaProdus('iphone',10));
        $this->assertContainsOnlyInstancesOf("Product",produseModel::cautaProdus('iphone',10));
    }

    public function testToate_produseleFail_wrongSession(){
        $res=produseModel::toate_produsele("id_inexistent");
        $this->assertJson($res);
        $res=json_decode($res,true);
        $this->assertArrayHasKey('Success',$res);
        $this->assertEquals('false',$res['Success']);
    }

    public function testToate_produseleSuccess(){
        $id_existent="5ec51799ca2c9";
        $res=produseModel::toate_produsele($id_existent);
        $this->assertJson($res);
        $res=json_decode($res,true);
        $this->assertArrayHasKey('Success',$res);
        $this->assertArrayHasKey('produse',$res);
        $this->assertIsArray($res['produse']);
        $this->assertEquals('true',$res['Success']);
    }

}
