<?php
/**
 * @param $categorie
 * functia care face scrapind in siteul emag
 * creaza talea corespunzatoare corespunzatoare categoriei
 * alcatuieste in switch in functie de categorie linkul de unde va face scrap
 * inregistreaza in baza de date rezultalele extrase din pagina htlm incarcata
 */
function Emag($categorie)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $URL_categorie = "";
    $dbname = "produse_emag";
    require_once 'simple_html_dom.php';

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
    rating FLOAT(20),
    pret INT(5),
    disponibilitate VARCHAR(64)
    )";

    if ($conn->query($sql) === TRUE) {
        echo "\n\nAm creat tabela $categorie. \n";
    } else {
        echo "Eroare la crearea tabelei: " . $conn->error;
    }


    $answer = array();

    switch ($categorie) {
        case "calculatoare":
            $URL_categorie = "laptopuri";
            break;
        case "telefoane":
            $URL_categorie = "telefoane-mobile";
            break;
        case "electrocasnice":
            $URL_categorie = "search/electrocasnice";
            break;
        case "imbracaminte":
            $URL_categorie = "search/imbracaminte";
            break;
        case "casti":
            $URL_categorie = "casti-audio";
            break;
    }

    $i = 0;
    $dom = file_get_html('https://www.emag.ro/' . $URL_categorie . '/p1/c'); //accesam prima pagina ca sa vedem cate pagini sunt in total; nr de obiecte/60+1
    $page = intdiv(intval($dom->find('span[class=title-phrasing title-phrasing-sm]', 0)->plaintext), 60) + 1;
    echo "\nCategoria $URL_categorie are $page pagini.";
    if($page>5){
        $page=5;
        echo "\nDar o sa luam doar 5 pentru ca oricum o pagina ia 2.5s sa fie incarcata si procesata in DB";
    }
    echo "\nIncepem inserarea...";
    for ($var = 1; $var <= $page; $var++) {
        $dom = file_get_html(sprintf('https://www.emag.ro/' . $URL_categorie . '/p%s/c', $var)); //paginile; var este numarul paginii..
        if (!empty($dom)) {
            $produse = $dom->find('div[id=card_grid]', 0); //aici sunt toate produsele
            foreach ($produse->find('div[class=card-item js-product-data]') as $element) { //cartea unui produs
                if($element->find('.text-danger')) //momentan nu tratem pachetele; sunt dubioase
                    continue;
                $answer['name'] = $element->getAttribute('data-name'); //atributul acesta contine descrierea produsului (titlul)

                $top = $element->find('.card-section-top', 0); //in partea superioara a cartii 
                $answer['imagine'] = $top->find('img', 0)->getAttribute('data-src'); //luam linkul imaginii produsului
                $answer['link'] = $top->find('a', 0)->href; //prima ancora contine linkul catre produs
                if($rating = $top->find('.star-rating-inner', 0)){ //linia cu stele
                $stele = $rating->style; //stilul contine latimea, aratata ca numarul de stele si determinata de rating
                $stele = substr($stele, -5, 4); //luam doar cifrele de dupa width
                if ($stele == "h: 0") //fara review-uri 
                    $stele = 0;
                else
                    $stele = floatval($stele) / 20; //width a fost intre 0 si 100, unde o stea era 20 
                $answer['rating'] = floatval($stele); //pentru consistenta in bd
                //luam ratingul; in html este reprezentat dupa stilul adoptat de stelute, fiind o latime de la 0 (0 stele) la 100%(5 stele)
                }
                $pret = $element->find('.product-new-price', 0)->find('text', 0)->plaintext; //luam pretul nou
                $pret = substr($pret, 0, 1) . substr($pret, -3); //parsam textul ca sa eliminam &#46; <=> ". "
                $answer['pret'] = intval($pret); //convertim la int 
                $answer['disponibilitate'] = $element->find('.card-section-btm', 0)->first_child()->first_child()->plaintext;
                //asa se ajunge la tagul care spune daca este in stoc (limitat) sau nu

                //preparam si executam insertul pentru fiecare produs gasit 
                $sql = "INSERT INTO $categorie (nume,imagine,link,rating,pret,disponibilitate) VALUES(?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param(
                    "sssdis",
                    $answer['name'],
                    $answer['imagine'],
                    $answer['link'],
                    $answer['rating'],
                    $answer['pret'],
                    $answer['disponibilitate']
                );
                $stmt->execute();

                $i++;
            }
        }
        $dom->clear(); //curatam dom pentru ca aparent PHP-ul are probleme la management-ul memoriei
        unset($dom);
    }
    echo "$i obiecte inserate cu succes";
    $conn->close();
}
//emag("calculatoare");
//emag("telefoane");
//emag("electrocasnice");
//emag("imbracaminte");
emag("casti");