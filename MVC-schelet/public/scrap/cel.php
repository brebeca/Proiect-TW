<?php
function cel($categorie)
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $URL_categorie = "";
    $dbname = "produse_cel";
    require_once 'simple_html_dom.php';
    //trebuie marita memoria la simple_html_dom; la linia 45 merge ok defined('MAX_FILE_SIZE') || define('MAX_FILE_SIZE', 6000000);
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
    disponibilitate VARCHAR(64),
    updated TIMESTAMP DEFAULT NOW() ON UPDATE NOW()
    )";

    if ($conn->query($sql) === TRUE) {
        echo "\n\nAm creat tabela $categorie. \n";
    } else {
        echo "Eroare la crearea tabelei: " . $conn->error;
    }


    $answer = array();

    switch ($categorie) {
        case "calculatoare":
            $URL_categorie = "laptop-laptopuri";
            break;
        case "telefoane":
            $URL_categorie = "telefoane-mobile";
            break;
        case "electrocasnice":
            $URL_categorie = "frigidere-combine-frigorifice"; //cauta/electrocasnice e absolut oribila. returneaza numai accesorii(placuta electronica) pentru masini de spalat si altele
            break;
        case "casti":
            $URL_categorie = "casti";
            break;
    }
    $page = 5;
    $i = 0;
    echo "\nLuam 5 pagini";
    echo "\nIncepem inserarea...";
    //https://stackoverflow.com/questions/37425134/simple-html-dom-failed-to-open-stream-for-a-site pentru a rezolva forbidden(a face server-ul sa creada ca suntem om)
    $context = stream_context_create(array(
        'http' => array(
            'header' => array('User-Agent: Mozilla/5.0 (Windows; U; Windows NT 6.1; rv:2.2) Gecko/20110201'),
        ),
    ));
    for ($var = 1; $var <= $page; $var++) {
        usleep(1000000 * rand(0, 1)); //sleep intre 0 si 1 secunda. La rularea scriptului intreg, deja ma atentioneaza si imi cere CAPCHA... 
        $dom = file_get_html(sprintf('https://www.cel.ro/' . $URL_categorie . '/0a-%s', $var), false, $context); //paginile; var este numarul paginii..
        if (!empty($dom)) {
            $produse = $dom->find('.productlisting', 0); //aici sunt toate produsele
            foreach ($produse->find('div[class=product_data productListing-tot]') as $element) { //cartea unui produs

                $answer['name'] = $element->find('.productTitle', 0)->find('a', 0)->find('span', 0)->innertext; //atributul acesta contine descrierea produsului (titlul)
                $answer['link'] = 'https://www.cel.ro'.$element->find('.productTitle',0)->find('a', 0)->href; //prima ancora contine linkul catre produs
                //dintr-un motiv nu ia si domeniul, desi el apare in html
                $answer['imagine'] = $element->find('.productListing-poza', 0)->find('img',0)->content; //luam linkul imaginii produsului
                //fara rating
                $pret = $element->find('.pret_n', 0)->find('b', 0)->innertext; //luam pretul nou
                $answer['pret'] = intval($pret); //convertim la int
                
                //$answer['disponibilitate'] = $element->find('div[class=stoc_list infoStocElem]', 0)->find('strong',0)->innertext; 
                $answer['disponibilitate']="In stoc";
                //asa se ajunge la tagul care spune daca este in stoc (limitat) sau nu.. fara imbracaminte, la asta trbuie accesata pagina in sine 

                //preparam si executam insertul pentru fiecare produs gasit
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
        }
        $dom->clear(); //curatam dom pentru ca aparent PHP-ul are probleme la management-ul memoriei
        unset($dom);
    }
    echo "$i obiecte inserate cu succes";
    $conn->close();
}
cel("calculatoare");
cel("telefoane");
cel("electrocasnice");
cel("casti");
