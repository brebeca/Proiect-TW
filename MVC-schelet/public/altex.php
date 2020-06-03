<?php
function altex($categorie)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $URL_categorie = "";
    $dbname = "produse_altex";
    require_once 'simple_html_dom.php'; //trebuie marita memoria la simple_html_dom; la linia 45 merge ok defined('MAX_FILE_SIZE') || define('MAX_FILE_SIZE', 6000000);
    //ne folosim de biblioteca
    $conn = mysqli_connect($servername, $username, $password, $dbname); //ne conectam la bd
    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    $sql = "DROP TABLE IF EXISTS $categorie";
    mysqli_query($conn, $sql);
    $sql = "CREATE TABLE $categorie (
    id INT(5) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nume VARCHAR(256) NOT NULL,
    imagine VARCHAR(128),
    link VARCHAR(256) NOT NULL,
    pret INT(5),
    disponibilitate VARCHAR(64)
    )";
    if ($conn->query($sql) === TRUE) {
        echo "\n\nAm creat tabela $categorie. \n";
    } else {
        echo "Eroare la crearea tabelei: " . $conn->error;
    }
    $page = 5;
    $URL_categorie=array();
    $answer = array();
    switch ($categorie) {
        case "calculatoare":
            array_push($URL_categorie,"laptopuri");
            break;
        case "telefoane":
            array_push($URL_categorie,"telefoane");
            break;
        case "electrocasnice": //la electrocasnice sunt multe subcategorii; o sa luam de test 2 pagini din fiecare
            array_push($URL_categorie, "masini-spalat-rufe-frontale");
            array_push($URL_categorie, "combine-frigorifice");
            $page = 2;
            break;
        case "casti":
            array_push($URL_categorie,"casti-telefon");
            break;
        default:
            echo "\nInterogare gresita/Incercare de atac";
            exit(-1);
    }
    $i = 0;
    foreach ($URL_categorie as $url) {
        for ($var = 1; $var <= $page; $var++) {
            $dom = file_get_html(sprintf('https://altex.ro/' . $url . '/cpl/filtru/p/%s/', $var)); //paginile; var este numarul paginii..
            if (!empty($dom)) {
                $produse = $dom->find('ul[class=Products Products--grid Products--4to2]', 0); //aici sunt toate produsele
                foreach ($produse->find('.Products-item') as $element) { //cartea unui produs
                    $answer['name'] = $element->find('a', 0)->getAttribute('title'); //atributul acesta contine descrierea produsului (titlul); situat in prima ancora
                    $answer['imagine'] = $element->find('img', 0)->src; //luam linkul imaginii produsului
                    $answer['link'] = $element->find('a', 0)->href; //prima ancora contine linkul catre produs
                    //nu exista review-uri/rating pe altex
                    $pret = $element->find('.Price-int', 0)->plaintext; //pret simplu string
                    $pret = str_replace(".", "", $pret); //eliminam punctul pentru a putea converti corect la int
                    $answer['pret'] = intval($pret); //plaintext ia ce e scris intre taguri
                    //$answer[$i]['pret complet'] = $element->find('.Price-current', 0)->plaintext; //pret cu tot cu , si valuta
                    $answer['disponibilitate'] = $element->find('.Product-list-right', 0)->find('div', 0)->last_child()->first_child()->plaintext;
                    //acces complicat la ultima: numele clasei se schimba in functie de daca e in stoc sau nu; iar calea pana acolo e formata din div-uri fara clasa
                    $sql = "INSERT INTO $categorie (nume,imagine,link,pret,disponibilitate) VALUES(?,?,?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param(
                        "sssis",
                        $answer['name'],
                        $answer['imagine'],
                        $answer['link'],
                        $answer['pret'],
                        $answer['disponibilitate']
                    );
                    $stmt->execute();
                    $i++;
                }
                $dom->clear(); //curatam dom pentru ca aparent PHP-ul are probleme la management-ul memoriei
                unset($dom);
            }
        }
    }
    echo"\n $i produse inserate cu succes";
    $conn->close();
}
//altex("calculatoare");
//altex("telefoane");
//altex("electrocasnice");
altex("casti");

/*
altex("imbracaminte");//nu exista
*/
