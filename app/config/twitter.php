<?php

    /**
     * twconnect library configuration
     */
    $active_group = "default";
    $active_record = TRUE;
    $CI = & get_instance();

    $db['default']['hostname'] = $CI->config->item('hostname');
    $host = $db['default']['hostname'];
    $db['default']['username'] = $CI->config->item('db_username');
    $user = $db['default']['username'];
    $db['default']['password'] = $CI->config->item('db_password');
    $pass = $db['default']['password'];
    $db['default']['database'] = $CI->config->item('db');
    $dbb = $db['default']['database'];

    /* Access tokens */

    $query = $CI->db->query("SELECT code,string_value FROM settings WHERE code ='SITE_TWITTER_API_ID'");

    foreach ($query->result() as $row)
    {
        $consumer_key = $row->string_value;
    }

    $query1 = $CI->db->query("SELECT code,string_value FROM settings WHERE code ='SITE_TWITTER_API_SECRET'");

    foreach ($query1->result() as $row1)
    {
        $consumer_secret = $row1->string_value;
    }

    $config = array(
        'consumer_key' => $consumer_key,
        'consumer_secret' => $consumer_secret,
        'oauth_callback' => 'oob' // Default callback application path
    );
?>