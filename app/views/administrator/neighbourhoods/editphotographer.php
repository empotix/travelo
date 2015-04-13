<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>
<script type="text/javascript">
    function get_cities(city) {
        $.ajax({
            type: 'GET',
            data: "city=" + city,
            url: "<?php echo base_url() . 'administrator/neighbourhoods/place_drop' ?>",
            success: function ($data) {

                $('#place').html($data);

            },
            error: function ($responseObj) {
                alert("Something went wrong while processing your request.\n\nError => "
                        + $responseObj.responseText);
            }
        });
    }
</script>

<div id="Add_Email_Template">
    <?php
        //Content of a group
        if (isset($photographers) and $photographers->num_rows() > 0)
        {
            $photographer = $photographers->row();
            ?>
            <div class="clsTitle">
                <h3><?php echo translate_admin("Edit Photographer"); ?></h3>
            </div> 
            <div class="MainTop_Links clearfix">
                <div class="clsNav">
                    <ul>
                        <li><a href="<?php echo admin_url('neighbourhoods/viewphotographer'); ?>"><?php echo translate_admin('Manage Photographers'); ?></a></li>
                    </ul>
                </div>
                <div class="clsTitle">
                    <?php /* ?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */ ?>
                </div>
            </div>
            <div>
                <form method="post" action="<?php echo admin_url('neighbourhoods/editphotographer') ?>/<?php echo $photographer->id; ?>" enctype="multipart/form-data">					
                    <table width="700" class="table">
                        <tr>
                            <td><label><?php echo translate_admin('City'); ?><span class="clsRed">*</span></label></td>
                            <td>
                                <select name='city' style="width:292px" onChange='get_cities(this.value)'>
                                    <?php
                                    foreach ($cities->result() as $row)
                                    {
                                        if ($row->city_name == $photographer->city)
                                        {
                                            $s = "selected='selected'";
                                        }
                                        else
                                        {
                                            $s = "";
                                        }
                                        echo '<option value="' . $row->city_name . '"' . $s . '>' . $row->city_name . '</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>		
                        <tr>
                            <td><label><?php echo translate_admin('Place'); ?><span class="clsRed">*</span></label></td>
                            <td id="place">
                                <select name='place' style="width:292px">
                                    <?php
                                    foreach ($cities->result() as $row)
                                    {
                                        if ($row->city_name == $photographer->city)
                                        {
                                            $city = $row->city_name;
                                        }
                                    }
                                    $result = $this->db->select('place_name')->where('city_name', $city)->get('neigh_city_place');
                                    foreach ($result->result() as $row)
                                    {
                                        echo "<option value='" . $row->place_name . "'>" . $row->place_name . "</option>";
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label><?php echo translate_admin('Photo Grapher Name'); ?><span class="clsRed">*</span></label></td>
                            <td>
                                <input class="clsTextBox" size="30" type="text" name="photo_grapher_name" id="photo_grapher_name" value="<?php echo $photographer->photographer_name; ?>"/>
                                <?php echo form_error('photo_grapher_name'); ?>
                            </td>
                        </tr>  
                        <tr>
                            <td><label><?php echo translate_admin('Photo Grapher Website URL'); ?><span class="clsRed"></span></label></td>
                            <td>
                                <input class="clsTextBox" size="30" type="text" name="photo_grapher_web" id="photo_grapher_web" value="<?php echo $photographer->photographer_web; ?>"/>
                                <?php echo form_error('photo_grapher_web'); ?>
                            </td>
                        </tr>  
                        <tr>
                            <td><label><?php echo translate_admin('Description Of Photo Grapher'); ?><span class="clsRed">*</span></label></td>
                            <td>
                                <textarea class="clsTextBox" name="photo_grapher_desc" id="photo_grapher_desc" value="" style="height: 162px; width: 282px;" >
                                    <?php echo $photographer->photographer_desc; ?>
                                </textarea>
                                <?php echo form_error('photo_grapher_desc'); ?>
                            </td>
                        </tr>  
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Photo Grapher Image'); ?><span class="clsRed">*</span></td>
                            <td>
                                <input id="photo_grapher_image" name="photo_grapher_image"  size="24" type="file" />
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <img src="<?php echo base_url() . '/images/neighbourhoods/photographer/' . $photographer->photographer_name . '/' . $photographer->photographer_image; ?>" height=183 width=300/>
                            </td>
                        </tr>
                        <tr>
                            <td class="clsName"><?php echo translate_admin('Is Featured') . '?'; ?></td>
                            <td>
                                <select name="is_home" id="is_home" >
                                    <option value="0"<?php
                                    if ($photographer->is_featured == "0")
                                    {
                                        echo "selected";
                                    }
                                    ?>> <?php echo translate_admin('No'); ?> </option>
                                    <option value="1"<?php
                                            if ($photographer->is_featured == "1")
                                            {
                                                echo "selected";
                                            }
                                            ?>> <?php echo translate_admin('Yes'); ?> </option>
                                </select> 
                            </td>
                        </tr>
                        <tr>
                            <td><input type="hidden" name="id"  value="<?php echo $photographer->id; ?>"/></td>
                            <td>	
                                <input  name="submit" type="submit" value="<?php echo translate_admin('Submit'); ?>">
                            </td>
                        </tr>
                    </table>
                </form>
        <?php
    }
?>
    </div>