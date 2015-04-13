<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>
<script>
    $(document).ready(function ()
    {
        $('#submit').click(function ()
        {
            var len = $("input:checkbox:checked").length;

            if (len == 0)
            {
                alert('Select atleast one category');
                return false;
            }
        })
    })

</script>
<div id="Add_Email_Template">

    <div class="clsTitle">
        <h3><?php echo translate_admin("Add Neighbourhood City Place"); ?></h3>
    </div> 
    <div class="MainTop_Links clearfix">
        <div class="clsNav">
            <ul>
                <li><a href="<?php echo admin_url('neighbourhoods/viewcity_place'); ?>"><?php echo translate_admin('Manage City Places'); ?></a></li>
            </ul>
        </div>
        <div class="clsTitle">
            <?php /* ?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */ ?>
        </div>
    </div>
    <div>
        <form method="post" action="<?php echo admin_url('neighbourhoods/addcity_place') ?>" enctype="multipart/form-data">					
            <table width="700" class="table">
                <tr>
                    <td><label><?php echo translate_admin('City'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <select name='city' style="width:292px">
                            <?php
                                foreach ($cities->result() as $row)
                                {
                                    echo '<option value="' . $row->city_name . '">' . $row->city_name . '</option>';
                                }
                            ?>
                        </select>
                        <?php echo form_error('city'); ?>
                    </td>
                </tr>		
                <tr>
                    <td><label><?php echo translate_admin('Place'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="30" type="text" name="place" id="place" value=""/>
                        <?php echo form_error('place'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo translate_admin('Quote'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <input class="clsTextBox" size="30" type="text" name="quote" id="quote" value=""/>
                        <?php echo form_error('quote'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo translate_admin('Short Description'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <textarea class="clsTextBox" name="short_desc" id="short_desc" value="" style="height: 162px; width: 282px;" ></textarea>
                        <?php echo form_error('short_desc'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo translate_admin('Long Description'); ?><span class="clsRed">*</span></label></td>
                    <td>
                        <textarea class="clsTextBox" name="long_desc" id="long_desc" value="" style="height: 162px; width: 282px;" ></textarea>
                        <?php echo form_error('long_desc'); ?>
                    </td>
                </tr>
                <tr>
                    <td><label><?php echo translate_admin('Category'); ?><span class="clsRed">*</span></label></td>
                    <td class="admin_category">
                        <ul>
                            <?php
                                if ($categories->num_rows() != 0)
                                {
                                    foreach ($categories->result() as $row)
                                    {
                                        $data = array(
                                            'name' => 'category[]',
                                            'value' => $row->id
                                        );
                                        echo '<li>';
                                        echo form_checkbox($data);
                                        echo $row->category;
                                        echo '</li>';
                                    }
                                }
                                else
                                {
                                    echo translate_admin('Please Add Category');
                                }
                            ?>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="clsName"><?php echo translate_admin('Place Image'); ?><span class="clsRed">*</span></td>
                    <td>
                        <input id="place_image" name="place_image"  size="24" type="file"/>
                        <span style="color:#9c9c9c; text-style:italic; font-size:11px; padding:33px;"><?php echo translate_admin("Resolution"); ?>: 1425x151</span>
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
                        <input  name="submit" type="submit" id='submit' value="<?php echo translate_admin('Submit'); ?>">
                    </td>
                </tr>

            </table>
        </form>	
    </div>






