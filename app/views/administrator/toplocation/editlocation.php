<div id="Edit_Location">
    <div class="clsTitle">
        <h3><?php echo translate_admin('Edit Location'); ?></h3></div>
    <form method="post" action="<?php echo admin_url('toplocation/editlocation') ?>/<?php echo $this->uri->segment(4, 0); ?>">
        <table class="table" cellpadding="2" cellspacing="0">
            <tr>
                <td class="clsName"><?php echo translate_admin('City Name'); ?><span class="clsRed">*</span></td>
                <td>
                    <input type="text" id="city_name" name="city_name" value="" required="required">
                </td>
                <?php form_error('city_name'); ?>
            </tr>


            <tr>
                <td class="clsName"><?php echo translate_admin('Country Name'); ?><span class="clsRed">*</span></td>
                <td>

                    <select id="category" name="category">
                        <?php foreach ($categories->result() as $category)
                            {
                                ?>
                                <option value="<?php echo $category->id; ?>"> <?php echo $category->name; ?> </option>
    <?php } ?>
                    </select>		

                </td>
            </tr>


            <tr>
                <td></td>
                <td>
                    <input type="hidden" name="page_operation" value="edit" />
                    <input type="hidden" name="id"  value="<?php echo $result->id; ?>"/>
                    <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Update'); ?>"  name="Update"/></td>
            </tr>  

        </table>
    </form>

</div>

<!-- End of clsSettings -->

<script language="Javascript">
    jQuery("#city_name").val('<?php echo $result->name; ?>');
    jQuery("#category").val('<?php echo $result->categories_id; ?>');
</script>