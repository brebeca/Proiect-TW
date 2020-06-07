<?php
class ProduseController extends Controller {
    public function produse(){

       $this->view('produse\produse');
       $this->view->render();
     }

    public function alegereEbay($data=''){
            $params= explode('?',$_SERVER['REQUEST_URI'])[1];
            $id=$_GET['id'];
            $params=explode("&id=", $params)[0];
            $this->model('produseModel');
            $this->model->trimiteProdus($id,$params);
    }

    public function compara(){
        $this->view('produse\compara');
        $this->view->render();

    }

    public function incarcaProduse(){
        $this->model('ProduseModel');
        $produse=$this->model->toateProdusele(md5($_GET['id']));
        echo $produse;
    }

    public function stergeProdus(){
         $this->model('ProduseModel');
        if(isset($_GET['session'])&&isset($_GET['product_id']))
            $this->model->sterge($_GET['product_id'],$_GET['session']);
    }

    public function alegere(){

        $id=$_GET['id'];
        $this->model('ProduseModel');
        $source='';
        if(strpos($_GET['source'], 'emag') !==false)
            $source='emag';
        else  if(strpos($_GET['source'], 'altex') !==false)
            $source='altex';

        $produs=$this->model->getProdusDb($_GET['index'], $_GET['category'],$source);
        if(isset($produs['rating']))
            $rating=$produs['rating'];
        else $rating=0;
        $de_trimis=array("category"=>$_GET['category'],"title"=>$produs['nume'],"link"=>$produs['link'],"img_link"=>$produs['imagine']
        ,"price"=>$produs['pret'],"rating"=>$rating,
            "source"=>$source);
       // print_r($de_trimis);
        $this->model->trimiteProdus2($de_trimis,$id);
       // $this->model->updateProdus($_GET['source'],$_GET['category'],$_GET['index'],$produs['link']);

    }

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