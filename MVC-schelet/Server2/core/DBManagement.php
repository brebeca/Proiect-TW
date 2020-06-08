<?php


use MongoDB\BSON\Regex;

require_once './../public/vendor/autoload.php';


class DBManagement
{
    private $connection;
    private $products_collection;
    private $users_collection;

    public function __construct()
    {
        $this->connection=DBConnection::obtineConexiune();
        $this->products_collection=$this->connection->selectCollection('Products');
        $this->users_collection=$this->connection->selectCollection('Users');
    }

    public  function insertUsers($doc){
        $collection = $this->connection->selectCollection('Users');
        $collection->insertOne($doc);
        $this->deleteTempUsers();
    }

    public function getNextId(){
        $collection = $this->connection->selectCollection('Products');
        $result= $collection->find(array(), array('id' => 1));
        $max=1000;
        foreach ($result as $item){
            if($item['id']>$max)
                $max=$item['id'];
        }
        return ($max+1);

    }
    public  function insertProducts($doc){
        $collection = $this->connection->selectCollection('Products');
        $doc['id'] = $this->getNextId();
        $collection->insertOne( $doc );
        return $doc['id'];
    }

    public  function verifySession($session){
        $collection = $this->connection->selectCollection('Users');
        $record = $collection->find( )->toArray();
        foreach ($record as $item){
            if (md5($item['session'])==$session or $item['session']==$session)
                return $item['session'];
        }
        return false;
    }
    public  function getAllSession(){
        $collection = $this->connection->selectCollection('Users');
        return $collection->find( )->toArray();
    }

    public function getProductsForOwner($owner){
        $collection = $this->connection->selectCollection('Products');
        $query = array('owner' => $owner);
        return $collection->find( $query)->toArray();
    }

    public function getProductsByName($word, $id='')
    {
        $collection = $this->connection->selectCollection('Products');
        if($id=='')
            $query = array('title' => new Regex($word,'i'));
        else
            $query = array('title' => new Regex($word,'i'),'owner'=>$id);
        return $collection->find( $query)->toArray();
    }

    public function doseProductExist($session, $id)
    {
        if($this->products_collection->countDocuments(array("owner"=>$session,"id"=>intval($id)))>0)
            return true;
        return false;
    }

    public function deleteProduct($id)
    {
       $this->products_collection->deleteOne(array("id"=>intval($id)));
    }

    public function updatePrice($id, $new_price)
    {
        $newdata = array('$set' => array("price" => intval($new_price)));
        $this->products_collection->updateOne(array("id"=>intval($id)),$newdata);
    }

    public function getProductsByBategory($category,$session)
    {
        //$products=$this->getProductsByName($category,$session);
        $products=[];
        $result=$this->products_collection->find(array('category'=>$category,'owner'=>$session))->toArray();
        foreach ($result as $item){
            array_push($products,$item);
        }
        usort($products,function($first,$second){
            return $first->price > $second->price;
        });
        return $products;
    }

    public function deleteTempUsers(){
        $to_delete=$this->users_collection->find(array("temp"=>true,"expire"=> array( '$lt' => time())))->toArray();
        $this->products_collection->deleteMany(array('owner' => array('$in' => $to_delete)));
        $this->users_collection->deleteMany(array("temp"=>true,"expire"=> array( '$lt' => time())));
    }
}