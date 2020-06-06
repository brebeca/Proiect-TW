<?php


use PHPUnit\Framework\TestCase;
include 'config.php';
require MODEL . 'ContactModel.php';
class ContactUSTests extends TestCase
{

    public function testAddAnonimusContact(){
        $model=new contactModel();
        $email="test1@yahoo.com";
        $nume="un_nume";
        $telephone_nr=123456;
        $message="mesaj din test";
        $this->assertTrue($model->addAnonimusContact($nume,$email,$telephone_nr,$message));
    }


}
