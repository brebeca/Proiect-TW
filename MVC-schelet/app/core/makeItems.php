<?php
function makeItems(){
    if (isset($_GET["nume-produs"]) && isset($_GET["nr-produse"])) {
      //butonul de cautare
      include VIEW . 'produse/search_db.phtml';
      include VIEW . 'produse/search_ebay.phtml';
    } else if (isset($_GET["categorie"])) {
      //
      require_once('core/Scrapping.php');

      $produse = Select::scrapping($_GET["categorie"],$_GET["bd"]);
      //aici am pus if-ul in caz ca ceva esueaza la scraping si intorci false
      if ($produse == false)
        header("Location:/index.php?scrap_esuat=1");
      else if($_GET["bd"]=="emag"){
        foreach ($produse as $produs) {
          display_emag($produs['id'], $produs['nume'], $produs['link'], $produs['imagine'], $produs['rating'], $produs['pret'], $produs['disponibilitate']);
        }
      }
      else if($_GET["bd"]=="altex"){
          foreach ($produse as $produs) {
          display_altex($produs['id'], $produs['nume'], $produs['link'], $produs['imagine'], $produs['pret'], $produs['disponibilitate']);
        }
      }
    }
    /* //aici ar trebui sa fie un buton. dar nu face nimic la apasare desi linkul este corect. ???
    $URL_current="$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $URL_current=substr($URL_current, 0, -1); //stergem ultimul caracter, numarul paginii
    $URL_current.=++$_GET['page']; //si il incrementam;
    echo $URL_current;
    ?>
    <a href="<?php echo $URL_current; ?>">Link</a>;
    <?php
    */
}
    function display_emag($id, $nume, $link, $imagine, $rating, $pret, $disponibilitate) //generam pentru fiecare 
    {
      echo '<div class="grid-item">
                <img src=' . $imagine . '></img>
                <button class="compara" type="button"  onclick=produs_ales('.$id.') title="Compara">
                  <span class="tooltip">Compara</span>
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
    function display_altex($id, $nume, $link, $imagine, $pret, $disponibilitate) //generam pentru fiecare 
    {
      echo '<div class="grid-item">
                <img src=' . $imagine . '></img>
                <button class="compara" type="button" title="Compara">
                  <span class="tooltip">Compara</span>
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
