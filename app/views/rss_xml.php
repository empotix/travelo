<?php
    echo '<?xml version="1.0" encoding="utf-8"?>';

    $logo = $this->Common_model->getTableData('settings', array('code' => 'SITE_LOGO'))->row()->string_value;
    $meta_description = $this->Common_model->getTableData('settings', array('code' => 'META_DESCRIPTION'))->row()->string_value;
?>
<rss version="2.0" xmlns:media="http://search.yahoo.com/mrss/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:georss="http://www.georss.org/georss">
    <channel>
        <title><?php echo $this->dx_auth->get_site_title(); ?></title> 
        <link><?php echo base_url(); ?></link> 
        <description><?php echo $meta_description; ?></description>
        <image>
        <url><?php echo base_url() . "logo/" . $logo; ?></url>
        </image>
        <feedCount> <?php echo $query->num_rows(); ?> </feedCount>
    </channel>
    <?php
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $url = getImageRss($row->id);

                if ($row->amenities != "")
                    $amenities = $row->amenities;
                else
                    $amenities = "NULL";

                if ($row->phone != "")
                    $phone = $row->phone;
                else
                    $phone = 0;

                if ($row->manual != "")
                    $manual = $row->manual;
                else
                    $manual = "NULL";

                echo '<item>
				      <listid>' . $row->id . '</listid>
										<title><![CDATA[' . urlencode($row->title) . ']]></title>
										<image>' . $url . '</image>
										<link>' . site_url('rooms') . '/' . $row->id . '</link>
										<address><![CDATA[' . urlencode($row->address) . ']]></address>
										<desc><![CDATA[' . urlencode($row->desc) . ']]></desc>
										<lat>' . $row->lat . '</lat>
										<long>' . $row->long . '</long>
										<propertyid>' . $row->property_id . '</propertyid>
										<roomtype>' . $row->room_type . '</roomtype>
										<bedrooms>' . $row->bedrooms . '</bedrooms>
										<beds>' . $row->beds . '</beds>
										<bedtype>' . $row->bed_type . '</bedtype>
										<bathrooms>' . $row->bathrooms . '</bathrooms>
										<amenities>' . $amenities . '</amenities>
										<capacity>' . $row->capacity . '</capacity>
										<price>' . $row->price . '</price>
										<currency>' . $row->currency . '</currency>
										<phone>' . $phone . '</phone>
										<email>' . $row->email . '</email>
										<manual><![CDATA[' . $manual . ']]></manual>
									</item>';
            }
        }
    ?>
</rss>   