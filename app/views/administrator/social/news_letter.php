<script type="text/javascript" src="<?php echo base_url() ?>css/tiny_mce/tiny_mce.js" ></script>
<script type="text/javascript">
    tinyMCE.init({
        mode: "textareas",
        theme: "advanced",
        elements: "elm1",
        plugins: "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
        theme_advanced_toolbar_align: "left",
        theme_advanced_toolbar_location: "top",
        skin: "o2k7",
        skin_variant: "silver",
        theme_advanced_buttons1: "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
        theme_advanced_buttons2: "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
        theme_advanced_buttons3: "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
        theme_advanced_buttons4: "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
        theme_advanced_statusbar_location: "bottom",
        theme_advanced_resizing: true

    });
</script>
<style>
    #chck{
        margin:20px;}
    .table span{
        color:#FF0000;}
    </style>
    <div class="Edit_Page">
        <?php
            //Show Flash Message
            if ($msg = $this->session->flashdata('flash_message'))
            {
                echo $msg;
            }
        ?>


    <div class="clsTitle"><h3><?php echo translate_admin('News Letter'); ?></h3></div>
    <form method="post" action="<?php echo admin_url('social/news_letter') ?>">
        <table class="table" cellpadding="2" cellspacing="0">

            <tr>
                <td class="clsName"><?php echo translate_admin('Name'); ?><span class="clsRed">*</span></td>
                <td><input class="" type="text" name="name" id="name" value=""><span><?php echo form_error('name'); ?></span></td>

            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('From'); ?><span class="clsRed">*</span></td>
                <td><input class="" type="text" name="from" id="from" value=""><span><?php echo form_error('from'); ?></span></td>

            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Subject'); ?><span class="clsRed">*</span></td>
                <td><input class="" type="text" name="subject" id="subject" value=""><span><?php echo form_error('subject'); ?></span></td>

            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('To'); ?><span class="clsRed">*</span></td>
                <td>
                    <?php
                        $query = $this->db->query("SELECT email,newsletter_status FROM users WHERE newsletter_status = 1");

                        if ($query->num_rows() > 0)
                        {
                            foreach ($query->result() as $row)
                            {
                                echo '<input type="checkbox" class="to" id="to" name="to[]" value="' . $row->email . '" >' . $row->email . '';
                            }
                        }
                    ?> <span> <?php echo form_error('to'); ?></span></td>

                <td><input type="button" value="Check All" id="chck"><input type="button" value="Un Check All" id="unchck"></td>

            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Message'); ?><span class="clsRed">*</span></td>
                <td><textarea id="elm1" name="message" rows="15" cols="80" style="width: 80%"></textarea><span><?php echo form_error('message'); ?></span></td>

            </tr>

            <tr>
                <td>
                    <input type="hidden" name="id"  value=""/></td><td>
                    <input  name="submit" type="submit" value="Submit"></td>
            </tr>  

        </table>
    </form>

</div>

<script>
    /* To make all our checkboxes checked:*/

    $("#unchck").click(function () {
        $(".to").attr('checked', false);
    });

    /*To uncheck all the checkboxes:*/

    $("#chck").click(function () {
        $(".to").attr('checked', true);
    });
</script>
