<?php
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>


<div id="Add_Email_Template">
    <?php
        //Content of a group
        if (isset($categories) and $categories->num_rows() > 0)
        {
            $category = $categories->row();
            ?>
            <div class="clsTitle">
                <h3><?php echo translate_admin("Add Neighbourhood Category"); ?></h3>
            </div> 
            <div class="MainTop_Links clearfix">
                <div class="clsNav">
                    <ul>
                        <li><a href="<?php echo admin_url('neighbourhoods/viewcategory'); ?>"><?php echo translate_admin('View Category'); ?></a></li>
                    </ul>
                </div>
                <div class="clsTitle">
                    <?php /* ?><h3><?php echo translate_admin("Manage Neighborhood Places"); ?></h3><?php */ ?>
                </div>
            </div>
            <div>
                <form method="post" action="<?php echo admin_url('neighbourhoods/editcategory') ?>/<?php echo $category->id; ?>">					
                    <table width="700" class="table">

                        <tr>
                            <td><label><?php echo translate_admin('Category'); ?><span class="clsRed">*</span></label></td>
                            <td>
                                <input class="clsTextBox" size="30" type="text" name="category" id="category" value="<?php echo $category->category; ?>"/>
                                <?php echo form_error('category'); ?>
                            </td>
                        </tr>
                        <tr>

                            <td><input type="hidden" name="id"  value="<?php echo $category->id; ?>"/></td>
                            <td><input  name="submit" type="submit" value="<?php echo translate_admin('Submit'); ?>">
                            </td>
                        </tr>

                    </table>
                </form>	
                <?php
            }
        ?>
    </div>
