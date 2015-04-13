<script src="<?php echo js_url(); ?>/jquery.validate.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#tag_form").validate({
            debug: false,
            rules: {
                tag: {
                    required: true,
                    minlength: 4,
                    maxlength: 15
                },
                city:
                        {
                            customvalidation: true
                        }
            },
            messages: {
                tag:
                        {
                            required: "Tag Must Be Required",
                            minlength: "Minimum 4 Characters Required",
                            maxlength: "Maximum 15 Character Required"
                        }
            },
        });
        //alert($('#city').val());

        $.validator.addMethod("customvalidation",
                function (value, element) {
                    if (($('#city').val()) != 'none')
                        return 'false';
                },
                "City Must Be Required"
                );
    });
</script>
<style>
    label.error { width: 250px; display: inline; color: red;}
    #city_error { width: 250px; display: inline; color: red;}
</style>
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
        <h3><?php echo translate_admin("Add Neighbourhoods Tag"); ?></h3>
    </div> 
    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li><a href="<?php echo admin_url('neighbourhoods/viewtag'); ?>"><?php echo translate_admin('Manage Tags'); ?></a></li>
            </ul>
        </div>

    </div>
    <div>
        <form method="post" id="tag_form" action="<?php echo admin_url('neighbourhoods/addtag') ?>" enctype="multipart/form-data">					
            <table width="700" class="table">

                <tr>
                    <td><label><?php echo translate_admin('Tag'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="30" type="text" name="tag" id="tag" value=""/>

                    </td>
                </tr>
                <tr>
                    <td><label><?php echo translate_admin('City'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <select name='city' id="city" style="width:292px" onChange='get_cities(this.value)'>
                            <option value='none' selected="selected"><?php echo translate_admin('Select City'); ?></option>
                            <?php
                                foreach ($cities->result() as $row)
                                {
                                    echo '<option value="' . $row->city_name . '">' . $row->city_name . '</option>';
                                }
                            ?>
                        </select>
                        <span id="city_error" style="display: none"> Must Required</span>
                    </td>
                </tr>		
                <tr>
                    <td><label><?php echo translate_admin('Place'); ?><span class="clsRed"></span></label></td>
                    <td id="place">
                        <select name='place' style="width:292px">
                            <option value='none' selected="selected"><?php echo translate_admin('No Place'); ?></option>	
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="clsName"><?php echo translate_admin('Is Shown') . '?'; ?></td>
                    <td>
                        <select name="is_shown" id="is_shown">
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






