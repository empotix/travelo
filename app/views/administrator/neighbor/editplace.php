<div class="Edit_Page">
    <?php
        //Show Flash Message
        if ($msg = $this->session->flashdata('flash_message'))
        {
            echo $msg;
        }
    ?>
    <?php
        //Content of a group
        if (isset($places) and $places->num_rows() > 0)
        {
            $page = $places->row();
            ?>
            <div class="clsTitle"><h3><?php echo translate_admin('Edit_Place'); ?></h3></div>
            <form method="post" action="<?php echo admin_url('email/editplace') ?>/<?php echo $page->id; ?>">
                <table class="table" cellpadding="2" cellspacing="0">

                    <?php /* ?>		  <tr>
                      <td class="clsName"><?php echo translate_admin('Country name'); ?><span class="clsRed">*</span></td>
                      <td><input class="" type="text" name="country" id="country" value="<?php echo $page->Country; ?>"></td>
                      </tr>
                      <tr>
                      <td class="clsName"><?php echo translate_admin('State name'); ?><span class="clsRed">*</span></td>
                      <td><input class="" type="text" name="state" id="state" value="<?php echo $page->State; ?>"></td>
                      </tr>
                      <tr>
                      <td class="clsName"><?php echo translate_admin('City name'); ?><span class="clsRed">*</span></td>
                      <td><input class="" type="text" name="city" id="city" value="<?php echo $page->city; ?>"></td>
                      </tr>

                      <tr><?php */ ?>
                    <tr>
                        <td class="clsName"><?php echo translate_admin('Area_name'); ?><span class="clsRed">*</span></td>
                        <td><input class="" type="text" name="area" id="area" value="<?php echo $page->area; ?>"></td>
                    </tr>

                    <tr>
                        <td><input type="hidden" name="id"  value="<?php echo $page->id; ?>"/></td>
                        <td><input  name="submit" type="submit" value="Submit"></td>
                    </tr>  

                </table>
            </form>
            <?php
        }
    ?>
</div>
