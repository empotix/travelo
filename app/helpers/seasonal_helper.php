<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    function getDailyPrice($list_id, $date, $list_price)
    {
        $CI = & get_instance();
        $special = 0;
        $CI->load->model('Common_model');
        $conditions = array('list_id' => $list_id);
        $seasonal_query = $CI->Common_model->getTableData('seasonalprice', $conditions);
        if ($seasonal_query->num_rows() > 0)
        {
            $seasonal_result = $seasonal_query->result_array();
            foreach ($seasonal_result as $res)
            {
                if ($date == $res['start_date'] || $date == $res['end_date'] || ($date > $res['start_date'] && $date < $res['end_date']))
                {
                    $price = $res['price'];
                    $special = 1;
                }
            }
            if ($special == 0)
                $price = $list_price;
        }
        else
        {
            $price = $list_price;
        }
        return $price;
    }

?>