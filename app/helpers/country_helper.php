<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    function findcountry()
    {
        //$lang = $_GET['lang'];
        //var_dump($lang);exit;
        $_GET['lang'] = "english";
        if ($_GET['lang'] == "english")
        {
            $language = "english";
        }

        $url = sprintf("http://freegeoip.net/json/%s", urlencode($_SERVER['REMOTE_ADDR']));
        $geo = json_decode(file_get_contents($url));
        $country_name = $geo->country_name;

        if ($country_name == "Portugal" || $country_name == "Brazil")
        {

            redirect("http://demo.cogzideltemplates.com/client/lemundi/pt/");
        }
        else if ($country_name == "Andorra" || $country_name == "Argentina" || $country_name == "Bolivia" || $country_name == "Chile" || $country_name == "Colombia" || $country_name == "Costa Rica" || $country_name == "Cuba" || $country_name == "El Salvador" || $country_name == "Ecuador" || $country_name == "Spain" || $country_name == "Guatemala" || $country_name == "Equatorial Guinea" || $country_name == "Honduras" || $country_name == "Mexico" || $country_name == "Nicaragua" || $country_name == "Dominican Republic" || $country_name == "Panama" || $country_name == "Paraguay" || $country_name == "Peru" || $country_name == "Puerto Rico" || $country_name == "Uruguay" || $country_name == "Venezuela")
        {
            redirect("http://demo.cogzideltemplates.com/client/lemundi/es/");
        }
        else if ($country_name == "Italy")
        {
            redirect("http://demo.cogzideltemplates.com/client/lemundi/it/");
        }
        else if ($country_name == "France")
        {
            redirect("http://demo.cogzideltemplates.com/client/lemundi/fr/");
        }
        else if ($language == "english")
        {
            
        }
        else
        {
            
        }

//return $geo;
    }

?>