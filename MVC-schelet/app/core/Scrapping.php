<?php
class Select 
{
  public static function scrapping($categorie,$db){
   $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = $db;
    $page = 1;
    $nr_produse_pagina = 30;
    $clauza_where = "";
    $clauza_order = "";
    $con = new mysqli($servername, $username, $password, $dbname);

    if ($con->connect_error) {
      die("Nu s-a reusit conectarea la baza de date: " . $con->connect_error);
    }
    if (isset($_GET['rating_minim'])) {
      $clauza_where =" rating >= " . $_GET['rating_minim'];
    }
    else if (isset($_GET['pret1'])&&isset($_GET['pret2'])) {
        $clauza_where =" pret BETWEEN " . $_GET['pret1']." and ".$_GET['pret2'];
    }
    else if(isset($_GET['page'])){
            $page=$_GET['page'];
        }
    if ($clauza_where != "")
      $clauza_where = " WHERE " . $clauza_where;

    $inceput=($page - 1) * $nr_produse_pagina; 
    $stmt = $con->prepare("SELECT * FROM " . $categorie . $clauza_where . $clauza_order . " LIMIT ?,?"); //luam din tabela cu numele transmis prin get
    $stmt->bind_param("ii", $inceput, $nr_produse_pagina);
    $stmt->execute();
    $rezultat = $stmt->get_result();
    $output = $rezultat->fetch_all(MYSQLI_ASSOC);
    return $output;
  }
    public static function get_products_by_name($nume,$db_name){
        $categorii=array('telefoane','calculatoare','electrocasnice');
        $servername = "localhost";
        $username = "root";
        $password = "";
        $con = new mysqli($servername, $username, $password, $db_name);

        if ($con->connect_error) {
            die("Nu s-a reusit conectarea la baza de date: " . $con->connect_error);
        }
        $output=array();
        foreach ($categorii as $categorie) {
            $stmt =$con->prepare("SELECT * FROM ".$categorie." WHERE nume like  '%".$nume."%' ");
            $stmt->execute();
            $rezultat = $stmt->get_result();
            $output[$categorie]=$rezultat->fetch_all(MYSQLI_ASSOC);
        }
        return $output;
    }
}
