<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    function get_meta_details($meta_name = '', $type = '')
    {
        $CI = & get_instance();

        if ($type == 'title')
        {
            return $CI->Common_model->getTableData('metas', array('name' => $meta_name))->row()->title;
        }
        else if ($type == 'meta_keyword')
        {
            return $CI->Common_model->getTableData('metas', array('name' => $meta_name))->row()->meta_keyword;
        }
        else if ($type == 'meta_description')
        {
            return $CI->Common_model->getTableData('metas', array('name' => $meta_name))->row()->meta_description;
        }
    }

    function get_price1($val = '')
    {
        $percent = ($val) * (55 / 100);
        $finalvalue = ($percent * 10) + $val;
        return $finalvalue;
    }

    function get_price2($val = '')
    {
        $percent = (($val) * (58 / 100));
        $finalvalue = ($percent * 10) + $val;
        return $finalvalue;
    }

    function get_price3($val = '')
    {
        $percent = ($val) * (130 / 100);
        $finalvalue = ($percent * 10) + $val;
        return $finalvalue;
    }

    function get_price4($val = '')
    {
        $percent = ($val) * (170 / 100);
        $finalvalue = ($percent * 10) + $val;
        return $finalvalue;
    }

?>