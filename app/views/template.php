<?php

    $this->load->view(THEME_FOLDER . '/includes/header');
?>

<?php

    //Show Flash Message
    if ($msg = $this->session->flashdata('flash_message'))
    {
        echo $msg;
    }
?>
<?php

    echo '<div id ="main_content">';
    $this->load->view(THEME_FOLDER . '/' . $message_element);
    echo '</div>';
?>
<script>
    $(document).ready(function () {
        $('.clsShow_Notification').fadeOut(5000);
    });
</script>

<?php /*
      echo '<div id=hidee>';
      $this->load->library('twitter');
      $tId = $this->twitter->get_userId();
      if($this->twitter->is_logged_in()) {?>
      <div id="popup_box">    <!-- OUR PopupBox DIV-->
      <?php echo form_open("users/Twitter_MailId_Popup", array('name' => 'signup', 'id' => 'signup')); ?>
      Please re-enter your Email address:<input type="text" name="email" id="email" /><br />
      <input type="hidden" name="tId" value="<?php echo $tId; ?>" />
      <p id="hidd" style="display:none; color:#FF0000; float:right; margin-top:30px;">Enter the valid email</p>
      <button type="submit" value="Submit" id="popupBoxClose" class="clsLink1_Bg">Submit</button>
      <?php echo form_close(); ?>
      </div><?php }
      echo '</div>' */ ?>



<?php /* if($this->twitter->is_logged_in()) {?>
      <script type="text/javascript">

      $(document).ready( function() {

      // When site loaded, load the Popupbox First
      loadPopupBox();

      $('#popupBoxClose').click( function() {
      unloadPopupBox();
      });

      function unloadPopupBox() {    // TO Unload the Popupbox
      $('#popup_box').fadeOut("slow");
      $('#main_content').css({ // this is just for style
      "display":"block"
      });
      $('#hidee').css({ // this is just for style
      "display":"block"
      });
      }
      <?php if($this->twitter->get_MailId($tId)=="x@mail.com" || $this->twitter->get_MailId($tId)=="") {?>
      function loadPopupBox() {    // To Load the Popupbox
      $('#popup_box').fadeIn("slow");
      $('#main_content').css({ // this is just for style
      "display": "none"
      });
      }   <?php }?>

      });
      </script>
      <script>
      $('#signup').submit(function(){
      var email_val = $('#email').val();
      var emailRegex = new RegExp(/^([\w\.\-]+)@([\w\-]+)((\.(\w){2,3})+)$/i);
      var valid = emailRegex.test(email_val);
      if(!valid) {
      $('#popup_box').fadeIn("slow");
      $('#main_content').css({ // this is just for style
      "display": "none"
      });
      $('#hidd').css({ // this is just for style
      "display": "block"
      });
      return false;
      } else
      return true;
      });
      </script>
      <?php } */ ?>	


<?php

    if ($message_element != 'list_your_space/view_list_your_space_next')
    {
        $this->load->view(THEME_FOLDER . '/includes/footer.php');
    }
?>
