<?php

    if ($_SERVER["HTTP_HOST"] == "www.mrbananaapp.in" || $_SERVER["HTTP_HOST"] == "mrbananaapp.in")
    {
        // When running LIVE
        $host = 'mrbananaindia.cfqcd4jw1baj.eu-west-1.rds.amazonaws.com';
        $username = 'mrbananaindia';
        $database = 'mrbananaindia';
        $password = 'dontgobananas123';
    }
    elseif ($_SERVER["REMOTE_ADDR"] == "127.0.0.1")
    {
        // When running locally
        $host = 'localhost';
        $username = 'root';
        $database = 'banana_india';
        $password = '';
    }

    define('DB_HOST', $host);
    define('DB_USER', $username);
    define('DB_PASS', $password);
    define('DB_NAME', $database);
    