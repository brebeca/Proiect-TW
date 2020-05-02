<?php
    class Procesor{
        var $taguri_deschise;
        var $taguri_inchise;
        var $xml;
        var $interpretor_xml;
        var $html_generat = "";
    
        function __construct($deschise, $inchise, $xml){
            $this->taguri_deschise = $deschise;
            $this->taguri_inchise = $inchise;
            $this->xml = $xml;

            $this->interpretor_xml = xml_parser_create(); 
            xml_parser_set_option($this->interpretor_xml, XML_OPTION_CASE_FOLDING, false);
            xml_set_object($this->interpretor_xml, $this);
            xml_set_element_handler($this->interpretor_xml, "trateaza_tag_inceput", "trateaza_tag_sfarsit");
            xml_set_character_data_handler($this->interpretor_xml, "trateaza_date_caracter");
        }  
        function __destruct(){
            xml_parser_free($this->interpretor_xml);
        }  

        function trateaza_tag_inceput($interpretor_xml, $tag, $atribute){
            if (array_key_exists($tag, $this->taguri_deschise))
                $this->html_generat .= $this->taguri_deschise[$tag];
        }
        function trateaza_tag_sfarsit($interpretor_xml, $tag){
            if (array_key_exists($tag, $this->taguri_inchise))
                $this->html_generat .= $this->taguri_inchise[$tag];
        }
        function trateaza_date_caracter($interpretor_xml, $text){
            $this->html_generat .= $text;
        }

        function interpreteaza(){
            if ($fisier = fopen($this->xml, "r"))
            {
                while($date = fread($fisier, 1024))
                    if (!xml_parse($this->interpretor_xml, $date, feof($fisier)))
                    {
                        die(sprintf("XML error: %s at line %d",
                        xml_error_string(xml_get_error_code($this->interpretor_xml)),
                        xml_get_current_line_number($this->interpretor_xml)));
                    }
                
            }
        }

        function obtine_html(){
            return $this->html_generat;
        }
    }

?>