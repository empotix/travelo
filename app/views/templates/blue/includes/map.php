<!-- Change the string after key= in the script below to the key that you have obtained in the google maps -->
<?php
$gmap_api_key = $this->db->get_where('settings', array('code' => 'SITE_GMAP_API_KEY'))->row()->string_value;;
?>

