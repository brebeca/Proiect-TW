<?php
class Select 
{
  public static function scrapping_altex($categorie){
  //select from categorie ;
  }
  public static function scrapping_emag($categorie){
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "produse_emag";
    $page = 1;
    $nr_produse_pagina = 30;
    $clauza_where = ""; //pentru filtre
    $clauza_order = ""; //pentru crescator/descrescator
    $con = new mysqli($servername, $username, $password, $dbname); //cream conexiunea
  
    if ($con->connect_error) {
      die("Nu s-a reusit conectarea la baza de date: " . $con->connect_error);
    }
    if(isset($_GET['page'])){
      $page=$_GET['page'];
    }
  /*
    if (isset($_GET['nr_produse_pagina'])) {
      // daca exista, atunci e schimbat defaultul de 30
      $nr_produse_pagina = $_GET['nr_produse_pagina'];
    }
    if (isset($_GET['rating_minim'])) {
      $clauza_where = $clauza_where . "rating>" . $_GET['rating_minim'];
    }
    if ($clauza_where != "")
      $clauza_where = " WHERE " . $clauza_where;
  */
    $inceput=($page - 1) * $nr_produse_pagina; 
    $stmt = $con->prepare("SELECT * FROM " . $categorie . $clauza_where . $clauza_order . " LIMIT ?,?"); //luam din tabela cu numele transmis prin get
    $stmt->bind_param("ii", $inceput, $nr_produse_pagina);
    $stmt->execute();
    $rezultat = $stmt->get_result();
    $output = $rezultat->fetch_all(MYSQLI_ASSOC);
    return $output;
  }
}
