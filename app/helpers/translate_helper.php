<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    /**
     * Translate 
     *
     * $s - default source language ( English )
     * $d - Destination language ( French , Spanish ... )
     * Converted the string using google translate API.
     * @return	string
     */
    if (!function_exists('translate'))
    {

        function translate($text, $d = '')
        {
            $ci = & get_instance();
            $query = $ci->db->get_where('settings', array('code' => 'FRONTEND_LANGUAGE'));

            $d = $ci->session->userdata('locale');
            $s = 'en';


            if ($query->row()->int_value == 2)
            {
                if ($d == '')
                {
                    $d = $query->row()->string_value;
                }

                $ci->lang->load('common', $d);
                $tranlate = $ci->lang->line($text);

                if ($tranlate == '')
                    return $text;
                else
                    return $tranlate;
            }
            else
            {
                if ($d == '')
                {
                    $d = $query->row()->string_value;
                }

                $ci->lang->load('common', $d);
                $tranlate = $ci->lang->line($text);

                if ($tranlate == '')
                    return $text;
                else
                    return $tranlate;
            }
        }

    }



    if (!function_exists('translate_admin'))
    {

        function translate_admin($text, $d = '')
        {
            $ci = & get_instance();
            $query = $ci->db->get_where('settings', array('code' => 'BACKEND_LANGUAGE'));
            if ($query->row()->int_value == 2)
            {
                $ci->lang->load('common_admin', $query->row()->string_value);
                $tranlate = $ci->lang->line($text);

                if ($tranlate == '')
                    return $text;
                else
                    return $tranlate;
            }
            else
            {
                $ci->lang->load('common_admin', $query->row()->string_value);
                $tranlate = $ci->lang->line($text);

                if ($tranlate == '')
                    return $text;
                else
                    return $tranlate;
            }
        }

    }

    function get_currency_value($amount)
    {
        $rate = 0;

        //echo $amount;

        $ci = & get_instance();

        $ci->load->helper("cookie");

        //var_dump($ci->session->userdata("default_currency_cookie"));

        $current = $ci->session->userdata("locale_currency");
        if ($current == '')
        {
            //$current = 'USD';
            $current = $ci->Common_model->getTableData('currency', array('default' => 1))->row()->currency_code;
        }

        $params = array('amount' => $amount, 'currFrom' => 'USD', 'currInto' => $current);

        //$ci->load->library('GoogleCurrencyConvertor',$params);

        $rate = round(google_convert($params));

        //var_dump($rate);

        if ($rate != 0)
            return $rate;
        else
            return $amount;

        //$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
    }

    function get_currency_value1($id, $amount)
    {
        $rate = 0;

        //echo $amount;

        $ci = & get_instance();

        $ci->load->helper("cookie");

        //var_dump($ci->session->userdata("default_currency_cookie"));

        $current = $ci->session->userdata("locale_currency");

        $list_currency = $ci->db->where('id', $id)->get('list')->row()->currency;

        if ($current == '')
        {
            $list_currency1 = $ci->db->where('default', 1)->get('currency')->row()->currency_code;

            $params = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $list_currency1);

            //$ci->load->library('GoogleCurrencyConvertor',$params);

            $rate = round(google_convert($params));

            //$current = 'USD';
            if ($rate != 0)
                return $rate;
            else
                return 0;
        }

        $params = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $current);

        //$ci->load->library('GoogleCurrencyConvertor',$params);

        $rate = round(google_convert($params));

        //var_dump($rate);

        if ($rate != 0)
            return $rate;
        else
            return 0;

        //$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
    }

    function get_currency_value_lys($from, $to, $amount)
    {
        $params = array('amount' => $amount, 'currFrom' => $from, 'currInto' => $to);

        //$ci->load->library('GoogleCurrencyConvertor',$params);

        $rate = round(google_convert($params));

        //var_dump($rate);

        if ($rate != 0)
            return $rate;
        else
            return $amount;
    }

    function get_currency_value2($fromcurrency, $tocurrency, $amount)
    {
        $rate = 0;

        //echo $amount;

        $ci = & get_instance();

        $ci->load->helper("cookie");

        //var_dump($ci->session->userdata("default_currency_cookie"));
        //$current = $ci->session->userdata("locale_currency");

        $list_currency = $currency;

        /* if($current == '')
          {
          $list_currency1 = $ci->db->where('default',1)->get('currency')->row()->currency_code;

          $params  = array('amount' => $amount, 'currFrom' => $list_currency, 'currInto' => $list_currency1);

          //$ci->load->library('GoogleCurrencyConvertor',$params);

          $rate=round(google_convert($params));

          //$current = 'USD';
          if($rate!=0)
          return $rate;
          else
          return $amount;

          } */

        $params = array('amount' => $amount, 'currFrom' => $fromcurrency, 'currInto' => $tocurrency);

        //$ci->load->library('GoogleCurrencyConvertor',$params);

        $rate = round(google_convert($params));

        //var_dump($rate);

        if ($rate != 0)
            return $rate;
        else
            return $amount;


        //$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
    }

    function get_currency_symbol1()
    {
        $ci = & get_instance();

        $currency_code = $ci->session->userdata('locale_currency');
        $new_currency = $ci->Common_model->getTableData('currency', array('default' => 1))->row();
        if ($currency_code == '')
        {
            $currency_symbol = $new_currency->currency_symbol;
            return $currency_symbol;
        }
        else
        {
            $currency_symbol = $ci->Common_model->getTableData('currency', array('currency_code' => $currency_code))->row()->currency_symbol;
            return $currency_symbol;
        }
    }

    function get_currency_symbol($id)
    {

        $ci = & get_instance();

        $currency_code = $ci->session->userdata('locale_currency');

        $list_currency = $ci->db->where("id", $id)->get("list")->row()->currency;
        //	$list_currency = $ci->db->query("SELECT currency FROM list WHERE id = $id");
        //	print_r($list_currency);exit;
        $new_currency = $ci->Common_model->getTableData('currency', array('currency_code' => $list_currency))->row();
        if ($currency_code == '')
        {
            $list_currency = $ci->db->where('default', 1)->get('currency')->row()->currency_code;
            $currency_symbol = $ci->Common_model->getTableData('currency', array('currency_code' => $list_currency))->row()->currency_symbol;
            //$currency_symbol = $new_currency->currency_symbol;
            return $currency_symbol;
        }
        else
        {
            $currency_symbol = $ci->Common_model->getTableData('currency', array('currency_code' => $currency_code))->row()->currency_symbol;
            return $currency_symbol;
        }
    }

    function get_currency_code()
    {
        $ci = & get_instance();

        $currency_code = $ci->session->userdata('locale_currency');
        $new_currency = $ci->Common_model->getTableData('currency', array('default' => 1))->row();
        if ($currency_code == '')
        {
            $currency_code = $new_currency->currency_code;
            return $currency_code;
        }
        else
        {
            return $currency_code;
        }
    }

    function get_currency_value_coupon($amount, $currency)
    {
        $rate = 0;
        $currency_from = $currency;

        //echo $amount;

        $ci = & get_instance();

        $ci->load->helper("cookie");

        //var_dump($ci->session->userdata("default_currency_cookie"));

        $currency_to = $ci->session->userdata("locale_currency");
        if ($currency_to == '')
        {
            $ci->load->model('Common_model');
            $currency_to = $ci->Common_model->getTableData('currency', array('default' => '1'))->row()->currency_code;
        }

        $params = array('amount' => $amount, 'currFrom' => $currency_from, 'currInto' => $currency_to);

        //$ci->load->library('GoogleCurrencyConvertor',$params);

        $rate = round(google_convert($params));


        //var_dump($rate);

        if ($rate != 0)
            return $rate;
        else
            return $amount;

        //$googleCurrencyConvertor = new GoogleCurrencyConvertor("1","INR","USD");
    }

    function google_convert($params)
    {
        $amount = $params["amount"];

        $currFrom = $params["currFrom"];

        $currInto = $params["currInto"];

        if (trim($amount) == "" || !is_numeric($amount))
        {
            trigger_error("Please enter a valid amount", E_USER_ERROR);
        }
        $return = array();


        /* 	$gurl="http://www.google.com/finance/converter?a=$amount&from=$currFrom&to=$currInto";

          $html=getHtmlCodeViaFopen($gurl);

          //var_dump($gurl);

          $pattern='/<span class=b(.*)\<\/span\>/Uis';

          preg_match_all($pattern,$html,$return,PREG_PATTERN_ORDER);

          //var_dump($return[0][0]);

          if (isset($return[0][0])) {
          $rate=$return[0][0];

          //var_dump($rate);

          $tmp=str_replace(" ".$currInto,"",$rate);

          $tmp=str_replace("<span class=bld>","",$tmp);

          $rate=$tmp;

          //var_dump(round($rate));

          //Patch Ends

          //$reverseRate=1/$rate;
          }
          else {
          $rate="Google could not convert your request. Please try again.";
          }
         */
        //echo $rate;

        $ci = & get_instance();

        if ($currFrom == 'USD')
        {
            $currInto_result = $ci->db->where('currency_code', $currInto)->get('currency_converter')->row();
            $rate = $amount * $currInto_result->currency_value;
        }
        else
        {

            $currFrom_result = $ci->db->where('currency_code', $currFrom)->get('currency_converter')->row();

            $from_usd = 1 / $currFrom_result->currency_value;

            $from_usd_amt = $amount * $from_usd;

            $currInto_result = $ci->db->where('currency_code', $currInto)->get('currency_converter')->row();

            $rate = $currInto_result->currency_value * $from_usd_amt;
        }

        return $rate;
    }

    function getHtmlCodeViaFopen($url)
    {
        $ci = & get_instance();
        $returnStr = "";

        $default_currency_code = $ci->config->item('default_currency_code');

        if ($default_currency_code != "USD")
        {
            $fp = fopen($url, "r") or die("ERROR: Failed to open $url for reading via this script.");
            while (!feof($fp))
            {
                $returnStr.=fgetc($fp);
            }
            fclose($fp);
            return $returnStr;
        }
    }

// ------------------------------------------------------------------------
/* End of file translate_helper.php */
/* Location: ./system/helpers/translate_helper.php */
