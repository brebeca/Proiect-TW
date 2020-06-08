<?php
class ProduseController extends Controller {

    /**
     * intoarce pagina de produse
     */
    public function produse(){
       $this->view('produse\produse');
       $this->view->render();
     }

    /**
     * se apleaza cand utilizatorului alege un produs de la eBay
     * informatiile sunt preluate din uri si se apeleaza functia de trimtie produs din model pentru transmiterea informatiei la serverul 2
     */
    public function alegereEbay(){
            $params= explode('?',$_SERVER['REQUEST_URI'])[1];
            $id=$_GET['id'];
            $params=explode("&id=", $params)[0];
            $this->model('produseModel');
            $this->model->trimiteProdus($id,$params);
    }

    /**
     * intoarce pagina de comparare
     */
    public function compara(){
        $this->view('produse\compara');
        $this->view->render();

    }

    /**
     * se apleaza din pagina de comparare pentru a incerca produelse utilizatorului
     * se apleaeaza functia din model care face cererea la serverul 2
     * se transmite rezultul prin echo
     */
    public function incarcaProduse(){
        $this->model('ProduseModel');
        $produse=$this->model->toateProdusele($_GET['id']);
        echo $produse;
    }

    /**
     * se verifica daca sunt setati parametrii necereai in url si se apeleaza funtia de sterge din model
     */
    public function stergeProdus(){
         $this->model('ProduseModel');
        if(isset($_GET['session'])&&isset($_GET['product_id']))
            $this->model->sterge($_GET['product_id'],$_GET['session']);
        else {
            http_response_code(400);
            echo json_encode(array("Success" => "false","Reason" => "Need more data"));
        }
    }

    /**
     * se verifica sursa
     * se apeleaza cu parametrii primiti functioa din model pentru returnarea produsului
     * daca esueaza se trimite mesaj de fail
     * se seteaza ratingul (in unele caruri nu exista rating)
     *  se apeleaza funtia din model care trimte produsul la al doilea server
     */
    public function alegere(){

        $id=$_GET['id'];
        $this->model('ProduseModel');
        $source='';
        if(strpos($_GET['source'], 'emag') !==false)
            $source='emag';
        else  if(strpos($_GET['source'], 'altex') !==false)
            $source='altex';

        $produs=$this->model->getProdusDb($_GET['index'], $_GET['category'],$source);
        if($produs==false)
        {
            http_response_code(500);
            echo json_encode(array("Success" => "false","Reason" => "Internal error"));
        }
        else {
            if(isset($produs['rating']))
                $rating=$produs['rating'];
            else
                $rating=0;
            $de_trimis=array("category"=>$_GET['category'],"title"=>$produs['nume'],"link"=>$produs['link'],"img_link"=>$produs['imagine']
            ,"price"=>$produs['pret'],"rating"=>$rating, "source"=>$source);
            $this->model->trimiteProdus2($de_trimis,$id);
            //$this->model->updateProdus($_GET['source'],$_GET['category'],$_GET['index'],$produs['link']);
        }
    }

    /**
     * se parcuge inputul din body-ul cererii si se genreaza feee-ul si se trimie la client
     */
    public function RSS(){
        $rssfeed = '<?xml version="1.0" encoding="ISO-8859-1"?>';
        $rssfeed .= '<rss version="2.0">';
        $rssfeed .= '<channel>';
        $rssfeed .= '<title> RSS feed - CompIT</title>';
        $rssfeed .= '<link>http://localhost:800/</link>';
        $rssfeed .= '<description>Site de comparare</description>';
        $rssfeed .= '<language>ro</language>';;
        $data = json_decode(file_get_contents('php://input'), true);
        foreach ($data as $index=>$item){
            $rssfeed .= '<item>';
            $rssfeed .= '<title>' . $item['title'] . '</title>';
            $description='';
          /*  if(is_array($item['description'])){
                foreach ($item['description'] as $key=>$value){
                    if(!is_array($value))
                        $description.='<'.$key.'>'.$value.'</'.$key.'>';
                }
                $rssfeed .= '<description>' .$description. '</description>';
            }
            else*/
                $rssfeed .= '<description> Categoria : '  . $item['category'] . '</description>';
            $rssfeed .= '<link>"' . explode('#',$item['link'] )[0]. '"</link>';
            $rssfeed .= '</item>';
        }

        $rssfeed .= '</channel>';
        $rssfeed .= '</rss>';
        echo $rssfeed;
    }

    public function renderRSS(){
        header("Content-Type: text/xml");
        echo $_GET['rss'];
    }
}