<?php
include 'config.php';
use PHPUnit\Framework\TestCase;
require MODEL.'ModelRegister.php';
require MODEL . 'LoginModel.php';
class RegistrationTests extends TestCase
{


    public function testSePoateInregistraToGetSession(){
        $model=new ModelRegister();
        $email="test1@yahoo.com";
       $this->assertEquals("5ec51799ca2c9",$model->sePoateIregistra($email));
    }
    public function testSePoateInregistraTrue(){
        $model=new ModelRegister();
        $email="test7@yahoo.com";
        $this->assertEquals(true,$model->sePoateIregistra($email));
    }

   /* public function testAddUserTrue(){
        $model=new ModelRegister();
        //$this->assert(true,$model->addUser(1,2,3));
    }*/

    public function testregisterUserSessionSuccess(){
        $model=new ModelRegister();
        $this->assertEquals("true",$model->registerUserSession("un_id_de_test"));
    }


    public function testPerformLoginSuccess(){
        $model=new loginModel();
        $result=$model->performLogin("rebeca2","test1@yahoo.com");
        $this->assertIsArray($result);
        $this->assertArrayHasKey('username',$result[0]);
    }

    public function testPerformLoginFail(){
        $model=new loginModel();
        $result=$model->performLogin("rebeca3","test@yahoo.com");
        $this->assertNull($result);
    }

}
