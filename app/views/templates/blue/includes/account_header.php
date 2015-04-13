<ul class="subnav">

<li <?php if($this->uri->segment(1) == 'account' && $this->uri->segment(2) == '') echo 'class="active"'; ?>>
<a href="<?php echo base_url(); ?>account"><?php echo translate("Notification"); ?></a>
</li>

<li <?php if($this->uri->segment(2) == 'payout') echo 'class="active"'; ?>>
<a href="<?php echo base_url();?>account/payout"><?php echo translate("Payout Preferences"); ?></a>
</li>

<li <?php if($this->uri->segment(2) == 'transaction') echo 'class="active"'; ?>>
<a href="<?php echo base_url();?>account/transaction"><?php echo translate("Transaction History"); ?></a>
</li>

<li>
<a href="<?php echo base_url();?>referrals"><?php echo translate("Referrals"); ?></a>
</li>

<li <?php if($this->uri->segment(2) == 'mywishlist') echo 'class="active"'; ?>>
<a href="<?php echo base_url();?>account/mywishlist"><?php echo translate("My Wishlist"); ?></a>
</li>
</ul>
