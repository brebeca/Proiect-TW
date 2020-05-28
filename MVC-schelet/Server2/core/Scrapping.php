<?php


class Scrapping
{
    public static function detalii_emag($link){
        $html = file_get_html($link);
        $product_det=array();
        $i=0;
        while($html->find('table.table.table-striped.product-page-specifications',$i)!=null){
            $table = $html->find('table.table.table-striped.product-page-specifications',$i);
            $table=$table->find('tbody',0);
            $rowData = array();

            foreach($table->find('tr') as $row) {
                $rowData[ str_replace(' ','_',str_replace('.','-',$row->find('td.col-xs-4.text-muted',0)->plaintext))] = $row->find('td.col-xs-8',0)->plaintext;
            }
            $product_det[ $html->find('div.pad-top-sm',$i)->find('p.text-uppercase',0)->find('strong',0)->innertext]=$rowData;
            $i++;
        }
        return $product_det;

    }

    public static function detalii_altex($link){
        $html = file_get_html($link);
        $product_det=array();
        $i=0;
        while($html->find('table.Specs-table',$i)!=null){
            $table = $html->find('table.Specs-table',$i);
            $table=$table->find('tbody',0);
            $rowData = array();

            foreach($table->find('tr') as $row) {
                $rowData[ str_replace(' ','_',str_replace('.','-',$row->find('td.Specs-cell',0)->plaintext))] = $row->find('td.Specs-cell',1)->plaintext;
            }
            $product_det[ $html->find('h3.Specs-title',$i)->innertext]=$rowData;
            $i++;
        }
        return $product_det;
    }


}