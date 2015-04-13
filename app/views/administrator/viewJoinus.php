<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/jquery.validate.js"></script>
<div id="View_Pages">
    <?php if ($message != "")
        {
            ?>
            <div class="message"><div class="success"><?php echo $message; ?></div></div>
    <?php } ?>	
    <div class="MainTop_Links clearfix">
        <div class="clsTitle">
            <h3><?php echo translate_admin("Manage Join us on"); ?></h3>
        </div>
    </div>
    <form action="<?php echo admin_url('joinus/updateJoinus') ?>" name="managepage" method="post" id="data_form">
        <table class="table" cellpadding="2" cellspacing="0">
            <th></th>
            <th><?php echo translate_admin('S.No'); ?></th>
            <th><?php echo translate_admin('Site Name'); ?></th>
            <th><?php echo translate_admin('Link'); ?> <?php echo translate_admin("Examplelink"); ?></th>

            <tr><td></td><td>1</td><td>
<?php echo translate_admin("Twitter"); ?></td>
                <td>
                    <input class="joinus_box" type="text" id="twitter" name="twitter" value="<?php echo $twitter ?>">
                </td>
            </tr>
            <tr><td></td><td>2</td>
                <td><?php echo translate_admin("Facebook"); ?></td>
                <td>
                    <input class="joinus_box" type="text" id="facebook" name="facebook" value="<?php echo $facebook ?>">
                </td>
            </tr>
            <tr><td></td><td>3</td>
                <td><?php echo translate_admin("Google"); ?></td>
                <td>
                    <input class="joinus_box" type="text" id="google" name="google" value="<?php echo $google ?>">
                </td>
            </tr>
            <tr>
                <td></td><td>4</td>
                <td><?php echo translate_admin("YouTube"); ?></td>
                <td><input class="joinus_box" type="text" id="youtube" name="youtube" value="<?php echo $youtube ?>">
                </td>
            </tr>
        </table>
        <br><center><input type="submit" name="submit" value="<?php echo translate_admin("Submit"); ?>"></center>
    </form>	
</div>

<script type="text/javascript">

    jQuery(document).ready(function () {

        jQuery.validator.addMethod("codecheck", function (value, element) {
            return this.optional(element) || /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(value);
        });
        jQuery("#data_form").validate({
            rules: {
                twitter: {required: true, url: true},
                facebook: {required: true, url: true},
                google: {required: true, url: true},
                youtube: {required: true, url: true}
            },
            messages: {twitter: {required: "Twitter is required", },
                facebook: {required: "Facebook is required", },
                google: {required: "Google is required", },
                youtube: {required: "YouTube is required", }
            }

        });

    });
</script>