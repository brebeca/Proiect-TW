<?php


class AdminView
{

    public static function displayMessages($messages){
        echo '<h3>Mesajele utilizatorilor</h3>';
        if(sizeof( $messages)==0)
           echo '<p> Nu sunt mesaje noi </p>';
       foreach ($messages as $message)
        echo '<div class="message">
                <br>
                <h> Utilizator : '.$message['name'].' ('.$message['email'].' , '.$message['telephone'].')</h>
                <br>
                <p >Mesajul : ' . $message['text'] . ' </p>
                </div>';
    }
}