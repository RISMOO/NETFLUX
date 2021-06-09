<?php
//DECLARATION CONSTANTES
define('SITE_NETFLIX','6LciQiAbAAAAAJ838WQxv-1g-v_u_8qNRd0nSBgd');//noM, valeur

define('SECRETE_NETFLIX','6LciQiAbAAAAACN3-vvqgo3qzDE2Gv84CrOsAmEW');


function check_token ($token,$secret_key){

$url_verif ="https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$token";//on rajoute  alurl secret key et token

    //ON FAIT DES REQUETES AVEC CURL

    $curl= curl_init($url_verif);
    //OPTIONS
    curl_setopt($curl,CURLOPT_HEADER, false); //PAS DOPTIONS
    curl_setopt($curl,CURLOPT_RETURNTRANSFER, true); //RETOURNE TEXT JSON
    $verif_response= curl_exec($curl);//JENVOI
   

    if(empty($verif_response)) return false;
    else{

        $json = json_decode($verif_response);//JE DECODE
        return $json->success;
    }
}
?>