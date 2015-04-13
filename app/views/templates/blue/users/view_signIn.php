
<link rel="stylesheet" type="text/css" href="<?php echo css_url() . '/jquery.fancybox-1.3.4.css' ?>" media="screen" />

<div class="container_bg1 signup_head">
    <div id="section_signin" style="" class="signup_h1">
        <h1>
            <?php echo translate("Sign in to your " . $this->dx_auth->get_site_title() . " Account"); ?>
        </h1>
        <div class="clsSign_Top">
            <div class="sign-fb-my-account">
               <!-- <p><?php echo translate("Sign in using Facebook:"); ?></p> -->
                <?php if (!$this->facebook_lib->logged_in()): ?>
                        <a href="javascript:void(0)" onclick="login();" class="Sign_Fb_Bg"><?php echo translate("Facebook"); ?></a>
                        <fb:facepile></fb:facepile>
                    <?php else: ?>
                        <?php redirect('facebook/login'); ?>
                <?php endif; ?>

                <!-- Twitter sign in -->
                <p class="Sign_Or_Row"><span><?php echo translate("Or"); ?></span></p>
                <a href="<?php echo base_url() . 'users/redirect'; ?>" class="sign_tw_bg"></a>
                <!-- Twitter sign in -->
                <p class="Sign_Or_Row"><span><?php echo translate("Or"); ?></span></p>
                <?php
                    require_once APPPATH . 'libraries/openid.php';
                    $openid = new LightOpenID(base_url());

                    $openid->identity = 'https://www.google.com/accounts/o8/id';
                    $openid->required = array(
                        'namePerson/first',
                        'namePerson/last',
                        'contact/email',
                    );
                    $openid->returnUrl = base_url() . 'users/google_signin';
                ?>
                <a href="<?php echo $openid->authUrl(); ?>" class="sign_google_bg"></a>

                <p class="Sign_Or_Row"><span><?php echo translate("Or"); ?></span></p>  

                <p class="create_acc"><span><?php echo translate("Create Your Account"); ?></span> &nbsp;
                    <a href="javascript:void(0);" onclick="$('#section_signin').hide();
                            $('#section_signup').show();
                            return false;"><?php echo translate("Sign up"); ?>
                    </a></p> 
                <p class="Sign_Or_Row"><span><?php echo translate("Or"); ?></span></p>
            </div>
            <div class="clsSign_Email">

                <?php echo form_open("users/signin", array('name' => 'signin', 'id' => 'signin')); ?>
                <div id="Input_Mail" class="Txt_input">
                    <label style="padding-left:25px;" for="username" id="lusername" class="labelBlur"><?php echo translate("Enter your username or email"); ?></label>
                    <input style="width:425px;margin-left:25px;" type="text" name="username" id="username" value="<?php echo set_value('username'); ?>" />
                </div>
                <?php echo form_error('username'); ?>
                <div id="Input_Password" class="Txt_input">
                    <label style="padding-left:25px;" for="password" id="lpassword" class="labelBlur"><?php echo translate("Password"); ?></label>
                    <input style="width:425px;margin-left:25px;" id="password" name="password" type="password" value="" />
                </div>
                <?php echo form_error('password'); ?>
                <p><?php
                        //echo anchor('users/forgot_password','Forgot password?', array('id' => 'forgot_password'))
                        echo anchor('users/forgot_password', translate('Forgot password'), array('id' => 'forgot_password'))
                    ?></p>
                <p>
                    <button name="SignIn" class="btn blue gotomsg" type="submit"><span><span><?php echo translate("Sign in"); ?></span></span></button>

                    <label class="Sign_Reminder_Me"><input type="checkbox" style="vertical-align: middle;box-shadow: 0 0 0 #F8F8F8;" />&nbsp;<span style="vertical-align: middle;color: #393C3D;"><?php echo translate("Remember me next time"); ?></span></label>

                </p>
<?php echo form_close(); ?>
            </div>
        </div>
        <div class="clsSign_Bottom">&nbsp;</div>

        <!-- End of form for the sign in feature -->

    </div>

    <div id="section_signup" style="display:none;" class="signup_h1">
        <h1>
<?php echo translate("Sign up for " . $this->dx_auth->get_site_title()); ?>
        </h1>
        <!-- Facebook Login is under here -->
        <div class="clsSign_Top">
            <div class="sign-fb-my-account">
              <!--  <p><?php echo translate("Sign up using Facebook:"); ?></p> -->

<?php if (!$this->facebook_lib->logged_in()): ?>
                        <a href="javascript:void(0)" onclick="login();" class="Sign_up_Fb_Bg"><?php echo translate("Fb sign up"); ?></a>
                        <fb:facepile></fb:facepile>
                    <?php else: ?>
                        <?php redirect('facebook/login'); ?>
<?php endif; ?>

                <!-- Twitter sign up -->
                <p class="Sign_Or_Row"><span><?php echo translate("Or"); ?></span></p>
                <a href="<?php echo base_url() . 'users/redirect'; ?>" class="sign_up_tw_bg"></a>
                <!-- Twitter sign up -->
                <p class="Sign_Or_Row"><span><?php echo translate("Or"); ?></span></p>
                <?php
                    require_once APPPATH . 'libraries/openid.php';
                    $openid = new LightOpenID(base_url());

                    $openid->identity = 'https://www.google.com/accounts/o8/id';
                    $openid->required = array(
                        'namePerson/first',
                        'namePerson/last',
                        'contact/email',
                    );
                    $openid->returnUrl = base_url() . 'users/google_signin';
                ?>
                <a href="<?php echo $openid->authUrl(); ?>" class="sign_up_google_bg"></a>
                <p class="Sign_Or_Row"><span><?php echo translate("Or"); ?></span></p>
                <p class="create_acc">
                    <?php echo translate("Already a member"); ?>?&nbsp;
                    <a href="javascript:void(0);" onclick="$('#section_signup').hide();
                            $('#section_signin').show();
                            return false;"><?php echo translate("Sign in"); ?></a>
                </p>
                <p class="Sign_Or_Row"><span><?php echo translate("Or"); ?></span></p>
            </div>
            <div class="clsSign_Email">
<?php echo form_open("users/signup", array('name' => 'signup', 'id' => 'signup')); ?>
                <span style="color:#FF0000">*</span>
                <div id="Input_First" class="Txt_input">
                    <label for="first_name" class="labelBlur"><?php echo translate("First name"); ?></label>
                    <input type="text" name="first_name" id="first_name" value="<?php echo set_value('first_name'); ?>" />
                </div>
<?php //echo form_error('first_name');   ?>
                <span style="color:#FF0000">*</span>
                <div id="Input_Last" class="Txt_input">
                    <label for="last_name" class="labelBlur"><?php echo translate("Last name"); ?></label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo set_value('last_name'); ?>" />
                </div>
<?php //echo form_error('last_name');   ?>
                <span style="color:#FF0000">*</span>
                <div id="Input_User" class="Txt_input">
                    <label for="username1" class="labelBlur"><?php echo translate("User name"); ?></label>
                    <input type="text" name="username" id="username1" value="<?php echo set_value('username'); ?>" />
<?php //echo form_error('username');   ?>
                </div>

                <span style="color:#FF0000">*</span>
                <div id="Input_Mail" class="Txt_input">
                    <label for="email" class="labelBlur"><?php echo translate("Email Address"); ?></label>
                    <input type="text" name="email" id="email" class="Sign_Inp_Bg" value="<?php echo set_value('email'); ?>" />
                </div>
<?php //echo form_error('email');   ?>
                <span style="color:#FF0000">*</span>
                <div id="Input_Password" class="Txt_input">
                    <label for="password1" class="labelBlur"><?php echo translate("Password"); ?></label>
                    <input id="password1" name="password" size="30" type="password" value="" />
                </div>
                <span id="pass"><?php //echo form_error('password');   ?></span>
                <span style="color:#FF0000">*</span>
                <div id="Input_Password" class="Txt_input hidden" >
                    <label for="re_password" class="labelBlur"><?php echo translate("Confirm Password"); ?></label>
                    <input id="re_password" name="confirmpassword" size="30" type="password" value="" />
                </div>
<?php //echo form_error('confirmpassword');   ?>
                <p>
                    <button name="SignUp" class="btn blue gotomsg" type="submit"><span><span><?php echo translate("Sign up"); ?></span></span></button>
                </p>
                <p>
                    <span style="color:#FF0000">*</span> <?php echo translate("Required fields"); ?>
                </p>

<?php echo form_close(); ?>
                <!--  End of form for sign up -->
            </div>
        </div>
        <div class="clsSign_Bottom">&nbsp;</div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        $("#signup #password1").focus(function () {
            $(".hidden").show();
        })

    })
</script>
<script src="<?php echo base_url() . 'js/facebook_invite.js'; ?>"></script>
<script src="<?php echo base_url() . 'js/jquery.fancybox-1.3.4.pack.js'; ?>"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $("#forgot_password").fancybox({});
    });
</script>
<script type="text/javascript">
    FB.init({
        appId: '<?php echo $fb_app_id; ?>',
        frictionlessRequests: true
    });
    /*   FB.getLoginStatus(function(response) {
     if (response.status === 'connected') {
     FB.login(function(response) {
     if (response.authResponse) {
     FB.api("/me", function(me){
     if (me.id) {
     
     var id = me.id; 
     var email = me.email;
     var first_name = me.first_name;
     var last_name = me.last_name;
     var live ='';
     if (me.hometown!= null)
     {
     var live = me.hometown.name;
     }
     
     //alert(email);return false;
     var picture = 'https://graph.facebook.com/'+id+'/picture?type=square';
     var username = me.username;
     
     //alert('https://graph.facebook.com/'+id+'/picture?type=square'); return false;	
     $.ajax({
     cache: false,
     type: "POST",
     dataType : 'text',
     url: '<?php //echo base_url()."facebook/success?";  ?>'+//new Date().getTime(),
     data: { id: id, email: email, Fname: first_name, Lname: last_name, live: live, src: picture, username: username },
     success: function(data)
     { 
     //alert(data);return false;
     if(data)
     {
     window.location.href = '<?php //echo base_url();  ?>'+//data;
     /*	}  
     },
     error: function (req, text, error) {
     alert(error);
     }
     });
     }
     });
     }
     }, {scope: 'email'});
     } else if (response.status === 'not_authorized') {
     //alert('author');
     } else {
     
     }
     });*/

    function login()
    {

        //  document.getElementById('light').style.display='block'; 
        FB.login(function (response) {
            if (response.authResponse) {
                FB.api("/me", function (me) {
                    if (me.id) {

                        var id = me.id;
                        var email = me.email;
                        var first_name = me.first_name;
                        var last_name = me.last_name;
                        var live = '';
                        if (me.hometown != null)
                        {
                            var live = me.hometown.name;
                        }

                        //alert(email);return false;
                        var picture = 'https://graph.facebook.com/' + id + '/picture?type=square';
                        var username = me.username;

                        //alert('https://graph.facebook.com/'+id+'/picture?type=square'); return false;	
                        $.ajax({
                            cache: false,
                            type: "POST",
                            dataType: 'text',
                            url: '<?php echo base_url() . "facebook/success?"; ?>' + new Date().getTime(),
                            data: {id: id, email: email, Fname: first_name, Lname: last_name, live: live, src: picture, username: username},
                            success: function (data)
                            {
                                //alert(data);return false;
                                if (data)
                                {
                                    window.location.href = '<?php echo base_url(); ?>' + data;
                                }
                            },
                            error: function (req, text, error) {
                                alert(error);
                            }
                        });
                    }
                });
            }
        }, {scope: 'email'});
    }
</script>