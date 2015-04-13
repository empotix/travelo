<div id="Edit_Managemetas">
    <div class="clsTitle">
        <h3><?php echo translate_admin('Edit Metas'); ?></h3>
    </div>
    <form method="post" action="<?php echo admin_url('managemetas/editmetas') ?>/<?php echo $this->uri->segment(4, 0); ?>">
        <table class="table" cellpadding="2" cellspacing="0">
            <tr>

                <td class="clsName"><?php echo translate_admin('Url'); ?><span class="clsRed">*</span></td>
                <td>
                    <input type="text" id="city_name" name="url" maxlength="100"  value="<?php echo $metas->url; ?>">
                    <span style="color: red;"><?php echo form_error('url'); ?></span>
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin(' Name'); ?><span class="clsRed">*</span></td>
                <td><input type="text" id="city_name" name="name" maxlength="50"  value="<?php echo $metas->name; ?>">
                    <span style="color: red;"><?php echo form_error('name'); ?></span>
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Title'); ?><span class="clsRed">*</span></td>
                <td><input type="text" id="city_name" name="title" maxlength="50"  value="<?php echo $metas->title; ?>">
                    <span style="color: red;"><?php echo form_error('title'); ?></span>
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Description'); ?><span class="clsRed">*</span></td>
                <?php echo form_error('title'); ?>
                <td><input type="text" id="city_name" name="description" maxlength="100"  value="<?php echo $metas->meta_description; ?>">
                    <span style="color: red;"><?php echo form_error('description'); ?></span>
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Keyword'); ?><span class="clsRed">*</span></td>
                <td><input type="text" id="city_name" name="keyword" maxlength="50" value="<?php echo $metas->meta_keyword; ?>">
                    <span style="color: red;"><?php echo form_error('keyword'); ?></span>
                </td>
            </tr>

            <tr>
                <td></td>
                <td><input type="hidden" name="page_operation" value="edit" />
                    <input type="submit" class="clsSubmitBt1" value="<?php echo translate_admin('Update'); ?>"  name="Update"/></td>
            </tr> 


        </table>
    </form>

</div>
