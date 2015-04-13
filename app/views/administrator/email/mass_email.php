<!--<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo js_url(); ?>/jquery.validate.min.js"></script>
<script type="text/javascript">
    function startCallback() {
        var flag = 0;
        if ($('#is_this').val() == 0)
        {
            if ($('#subject').val() != "" && $('#comment').val() != "")
            {
                flag = 1;
            }
        }
        else
        {
            if ($('#email_to').val() != "" && $('#subject').val() != "" && $('#comment').val() != "")
            {
                flag = 1;
            }
        }

        if (flag == 1)
        {
            $("#message").html('<img src="<?php echo base_url() . 'images/loading.gif' ?>">');
            // make something useful before submit (onStart)
            return true;
        }
        else
        {
            alert("please fill the all fields.");
            return false;
        }
    }

    function completeCallback(response)
    {
        if (response.length > 50)
        {
            window.location.href = "<?php echo base_url() . 'administrator'; ?>";
        }
        else
        {
            $('#message').show();
            $("#message").html(response);
            $("#message").delay(1800).fadeOut('slow');
        }

    }
    $(document).ready(function ()
    {
        function validateEmail(field) {
            var regex = /\b[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b/i;
            return (regex.test(field)) ? true : false;
        }

        $.validator.addMethod("multiemail", function (value, element)
        {
            var result = value.split(",");
            for (var i = 0; i < result.length; i++)
                if (!validateEmail(result[i]) || result.length > 5)
                    return false;
            return true;

        }, 'One or more email addresses are invalid');

        $("#form").validate({
            rules: {
                emails: {required: true, multiemail: true},
                subject: {required: true},
                comment: {required: true}
            },
            messages: {
                emails: {
                    required: "Please enter the required field.",
                    multiemail: "Please enter the valid email id's."
                },
                subject: {required: "Please enter the subject."},
                comment: {required: "Please enter the comment."}
            }

        });
    });


</script>

<div id="Mass_Email">
    <div class="clsTitle">
        <h3><?php echo translate_admin('Mass E-Mail Campaigns'); ?></h3>
    </div>

    <form action="<?php echo admin_url('email/mass_email'); ?>" method="post" id="form" enctype="multipart/form-data" onsubmit="return AIM.submit(this, {'onStart': startCallback, 'onComplete': completeCallback})">	

        <table class="table" cellpadding="2" cellspacing="0">

            <tr valign="top">
                <td class="clsName"><?php echo translate_admin('Email To'); ?><sup>*</sup></td>
                <td> 
                    <input type="radio" checked="checked" name="is_private" onclick="javacript:showhide(this.value);" value="0"> <?php echo translate_admin('All Users'); ?> &nbsp;
                    <input type="radio" name="is_private" onclick="javacript:showhide(this.value);" value="1"> <?php echo translate_admin('Particular Users'); ?>

                    <div id="emails_private" style="display:none;">
                        <br />
                        <p><?php echo translate_admin('Enter the email address separated by commas'); ?></p>
                        <textarea name="emails" id="emails" style="width:300px; height:100px" rows="10" cols="60" class="text_area"> </textarea>
                    </div>
                </td>
            </tr>

            <tr>
                <td class="clsName"><?php echo translate_admin('Subject'); ?><sup>*</sup></td>
                <td> <input type="text" size="55" name="subject" id="subject" value=""> </td>
            </tr>		

            <tr>
                <td class="clsName"><?php echo translate_admin('Message'); ?><sup>*</sup></td>
                <td>
                    <textarea name="message" id="comment" style="width:400px; height:100px" rows="10" cols="60" class="text_area required"></textarea>
                </td>
            </tr>

            <tr>
                <td></td>
                <td>
                    <div class="clearfix">
                        <span style="float:left; margin:0 10px 0 0;">
                            <input class="clsSubmitBt1" type="submit" name="submit" value="<?php echo translate_admin('Submit'); ?>" style="width:90px;" />
                            <input type="hidden" name="is_this" id="is_this" value="0" />
                        </span>
                        <span style="float:left;"><div id="message"></div></span>
                    </div>
                </td>
            </tr>

        </table>

    </form>
</div>

<script language="Javascript">

    function showhide(id)
    {
        if (id == 0)
        {
            $('#emails_private').hide();
            $('#is_this').val(0);
        }
        else
        {
            $('#emails_private').show();
            $('#is_this').val(1);
        }

    }
</script>
