<script type="text/javascript" src="<?php echo base_url() ?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
    tinyMCE.init({
        mode: "textareas",
        theme: "simple"
    });
</script>

<div id="Add_Faq">

    <div class="clsTitle">
        <h3><?php echo translate_admin('Add FAQ'); ?></h3>
    </div>

    <form method="post" action="<?php echo admin_url('faq/addFaq') ?>" id="faq_form" name="faq_form">	
        <table class="table" cellpadding="2" cellspacing="0" width="100%">
            <tr>
                <td class="clsName"><?php echo translate_admin('Question ?'); ?>&nbsp;<span class="clsRed">*</span></td>
                <td class="clsMailID">
                    <input class="forminput" id="question" type="text" name="question" value="<?php echo set_value('question'); ?>">
                    <?php echo form_error('question'); ?></td>
            </tr>

            <tr>
                <td class="clsName"> 
                    <?php echo translate_admin('Answer'); ?>&nbsp;<span class="clsRed">*</span>
                </td>
                <td>
                    <textarea id="faq_content" name="faq_content" rows="15" cols="150" style="width: 100%"></textarea>
                    <?php echo form_error('faq_content'); ?>
                </td>
            </tr>

            <tr>
                <td></td>    
                <td>
                    <input type="hidden" name="faq_operation" value="add"  />
                    <input class="clsSubmitBt1" value="<?php echo translate_admin('Submit'); ?>" name="addFaq" type="submit">
                </td>
            </tr>
        </table>
    </form>

</div>