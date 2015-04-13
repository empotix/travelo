<ul class="subnav">

<li <?php if($this->uri->segment(1) == 'users' && $this->uri->segment(2) == 'edit') echo 'class="active"'; ?>>
<a href="<?php echo site_url('users/edit'); ?>"><?php echo translate("Edit Profile"); ?></a>
</li>
<?php
$via_login = $this->db->where('id',$this->dx_auth->get_user_id())->get('users')->row()->via_login;
 if ($via_login == ''): ?>
<li <?php if($this->uri->segment(2) == 'change_password') echo 'class="active"'; ?>>
<?php
	echo anchor('users/change_password/'.$this->dx_auth->get_user_id(),translate("Change Password"),array("class" => "")); 
	?>
</li>
<?php endif; ?>

<li <?php if($this->uri->segment(2) == 'recommendation') echo 'class="active"'; ?>>
<a href="<?php echo site_url('users/recommendation');?>"><?php echo translate("Get Recommendations"); ?></a>
</li>

<li <?php if($this->uri->segment(2) == 'reviews') echo 'class="active"'; ?>>
<a href="<?php echo site_url('users/reviews');?>"><?php echo translate("Reviews & Recommendations"); ?></a>
</li>

<li class="clsBorder_No"><a href="<?php echo site_url('users/profile/'.$this->dx_auth->get_user_id()); ?>"><?php echo translate("View Public Profile"); ?></a></li>

<li <?php if($this->uri->segment(2) == 'verify') echo 'class="active"'; ?>>
<a href="<?php echo site_url('users/verify');?>"><?php echo translate("Trust and Verification"); ?></a>
</li>

</ul>
