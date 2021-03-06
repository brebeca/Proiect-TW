<?php
require_once('core/Scrapping.php');
/**
 * in functie de request funtia  afiseaza produsele dorite
 * daca sunt setati parametrii pentru cautare epleaza functia de cautare in baza de date si pe cea care returneaza produsele intoarse de Ebay
 * daca  sunt setati parametrii pentru cautarea unui anumit tip de produs dintr-o anumita sursa se epeleaza fnuctia care intoarce produsele
 * apoi pe vectorii de produse intorsi se apeleaza functiile de afisare
 */
function makeItems()
{
    if (isset($_GET["nume-produs"]) && isset($_GET["nr-produse"])) {
        $produse = Select::getProductsByName($_GET["nume-produs"],"produse_emag");
        if ($produse == false)
            header("Location:/index.php?scrap_esuat=1");
        foreach ($produse as $i => $values) {
            foreach ($values as  $value)
                display_emag($value['id'], $value['nume'], $value['link'],
                    $value['imagine'], $value['rating'], $value['pret'],
                    $value['disponibilitate'],$i);

            }
        $produse = Select::getProductsByName($_GET["nume-produs"],"produse_altex");
        foreach ($produse as $i => $values) {
            foreach ($values as  $value)
                display_altex($value['id'], $value['nume'], $value['link'],
                    $value['imagine'],  $value['pret'],
                    $value['disponibilitate'],$i);
        }
        include VIEW . 'produse/search_ebay.phtml';
    }
    else if (isset($_GET["categorie"]) && isset($_GET["sursa"])) {
        $produse = Select::scrapping($_GET["categorie"], $_GET["sursa"]);
        if ($produse == false)
            header("Location:/index.php?scrap_esuat=1");
        else if ($_GET["sursa"] == "produse_emag") {
            foreach ($produse as $produs) {
                display_emag($produs['id'], $produs['nume'], $produs['link'],
                             $produs['imagine'], $produs['rating'], $produs['pret'],
                             $produs['disponibilitate'],$_GET["categorie"]);
            }
        } else if ($_GET["sursa"] == "produse_altex") {
            foreach ($produse as $produs) {
                display_altex($produs['id'], $produs['nume'], $produs['link'],
                              $produs['imagine'], $produs['pret'],
                              $produs['disponibilitate'],$_GET["categorie"]);
            }
        }
        else if ($_GET["sursa"] == "produse_cel") {
            foreach ($produse as $produs) { //putem folosi display_altex pentru ca avem aceleasi coloane in BD
                display_altex($produs['id'], $produs['nume'], $produs['link'],
                              $produs['imagine'], $produs['pret'],
                              $produs['disponibilitate'],$_GET["categorie"]);
            }
        }
    }
}

function display_emag($id, $nume, $link, $imagine, $rating, $pret, $disponibilitate,$categorie) //generam pentru fiecare
{
    $sursa="produse_emag";
    echo '<div class="grid-item">
                <img class="aimg" src=' . $imagine . '></img>
                <button class="compara" type="button" onclick=produs_ales(' . $id . ',\'produse_emag\','.'\''.$categorie.'\')  title="Compara">
                  <span class="tooltip">Compara</span>
                  <i class="fas fa-check hovtip"></i>
                  </button>
                <br>
                <a class="titlu" href=' . $link . '> ' . $nume . '</a>
                <br><br>
                <a class="review" href=' . $link . '/#reviews-section' . '> Catre review-uri </a>
                <div class="rating" style="--rating:' . $rating . ';"></div>
                <p class="pret">' . $pret . ' lei</p>';
    if (strpos($disponibilitate, 'ultimul') !== false || strpos($disponibilitate, 'ultimele') !== false) {
        echo '<p class="ultimul">' . $disponibilitate . '</p>';
    } else {
        echo '<p class=' . str_replace(" ", "-", $disponibilitate) . '>' . $disponibilitate . '</p>';
    }
    echo "</div>";
}

function display_altex($id, $nume, $link, $imagine, $pret, $disponibilitate,$categorie) //generam pentru fiecare
{
    $sursa="produse_altex";
    echo '<div class="grid-item">
                <img class="aimg "src=' . $imagine . '></img>
                <button class="compara" type="button" onclick="produs_ales(' . $id . ',\'produse_altex\','.'\''.$categorie.'\')" title="Compara">
                  <span class="tooltip">Compara</span>
                  <i class="fas fa-check hovtip"></i>
                  </button>
                <br>
                <a class="titlu" href=' . $link . '> ' . $nume . '</a>
                <br><br>
                <p class="pret">' . $pret . ' lei</p>';
    if (strpos($disponibilitate, 'ultimul') !== false || strpos($disponibilitate, 'ultimele') !== false) {
        echo '<p class="ultimul">' . $disponibilitate . '</p>';
    } else {
        echo '<p class=' . str_replace(" ", "-", $disponibilitate) . '>' . $disponibilitate . '</p>';
    }
    echo "</div>";
}
