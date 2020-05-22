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
        $this->connection=DBConnection::obtine_conexiune();
        $this->products_collection=$this->connection->selectCollection('Products');
        $this->users_collection=$this->connection->selectCollection('Users');

    }

    public  function insert_users($doc){
        $collection = $this->connection->selectCollection('Users');
        $collection->insertOne($doc);

    }

    public function get_next_id(){
        $collection = $this->connection->selectCollection('Products');
        //$query = array('owner' => '');
        $result= $collection->find(array(), array('id' => 1));
        $max=1000;
        foreach ($result as $item){
            if($item['id']>$max)
                $max=$item['id'];
        }
        return ($max+1);

    }
    public  function insert_products($doc){
        $collection = $this->connection->selectCollection('Products');
        $doc['id'] = $this->get_next_id();
        $collection->insertOne( $doc );
        return $doc['id'];
    }

    public  function verify_session($session){
        $collection = $this->connection->selectCollection('Users');
        $record = $collection->find( )->toArray();
        foreach ($record as $item){
            if (md5($item['session'])==$session or $item['session']==$session)
                return $item['session'];
        }
        return false;
    }

    public function get_products_for_owner($owner){
        $collection = $this->connection->selectCollection('Products');
        $query = array('owner' => $owner);
        return $collection->find( $query)->toArray();
    }

    public function get_products_by_name($word)
    {
        $collection = $this->connection->selectCollection('Products');
        $query = array('title' => new Regex($word,'i'));
        return $collection->find( $query)->toArray();
    }

    public function dose_product_exist($session, $id)
    {
        if($this->products_collection->countDocuments(array("owner"=>$session,"id"=>intval($id)))>0)
            return true;
        return false;
    }

    public function delete_product($id)
    {
        print_r($this->products_collection->deleteOne(array("id"=>intval($id))));
    }


}