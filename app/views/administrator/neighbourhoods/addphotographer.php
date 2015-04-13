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

    <div class="clsTitle">
        <h3><?php echo translate_admin("Add Photographer"); ?></h3>
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
        <form method="post" action="<?php echo admin_url('neighbourhoods/addphotographer') ?>" enctype="multipart/form-data">					
            <table width="700" class="table">
                <tr>
                    <td><label><?php echo translate_admin('City'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <select name='city' style="width:292px" onChange='get_cities(this.value)'>
                            <option value='none' selected="selected"><?php echo translate_admin('Select City'); ?></option>
                            <?php
                                foreach ($cities->result() as $row)
                                {
                                    echo '<option value="' . $row->city_name . '">' . $row->city_name . '</option>';
                                }
                            ?>
                        </select>
                    </td>
                </tr>		
                <tr>
                    <td><label><?php echo translate_admin('Place'); ?><span class="clsRed">*</span></label></td>
                    <td id="place">
                        <select name='place' style="width:292px">
                            <option value='none' selected="selected"><?php echo translate_admin('No Place'); ?></option>	
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo translate_admin('Photo Grapher Name'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="30" type="text" name="photo_grapher_name" id="photo_grapher_name" value=""/>
                        <?php echo form_error('photo_grapher_name'); ?>
                    </td>
                </tr>  
                <tr>
                    <td><label><?php echo translate_admin('Photo Grapher Website URL'); ?><span class="clsRed"></span></label></td>
                    <td>
                        <input class="clsTextBox" size="30" type="text" name="photo_grapher_web" id="photo_grapher_web" value=""/>
                        <?php echo form_error('photo_grapher_web'); ?>
                    </td>
                </tr>  
                <tr>
                    <td><label><?php echo translate_admin('Description Of Photo Grapher'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <textarea class="clsTextBox" name="photo_grapher_desc" id="photo_grapher_desc" value="" style="height: 162px; width: 282px;" ></textarea>
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
                    <td class="clsName"><?php echo translate_admin('Is Featured') . '?'; ?></td>
                    <td>
                        <select name="is_home" id="is_home" >
                            <option value="0"> <?php echo translate_admin('No'); ?> </option>
                            <option value="1"> <?php echo translate_admin('Yes'); ?> </option>
                        </select> 
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input  name="submit" type="submit" value="<?php echo translate_admin('Submit'); ?>">
                    </td>
                </tr>
            </table>
        </form>
    </div>