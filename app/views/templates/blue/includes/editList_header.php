<ul class="edit_room_nav">

<!--<li class="<?php if($this->uri->segment(2) == 'edit') echo "selected"; ?>">
<?php echo anchor('rooms/edit/'.$this->uri->segment(3),translate('Description'), array('class' => 'details')); ?>
</li>

<li class="<?php if($this->uri->segment(2) == 'edit_photo') echo "selected"; ?>">
<?php echo anchor('rooms/lys_next/edit_photo/'.$this->uri->segment(3),translate('Photos'), array('class' => 'photo')); ?>
</li>-->

<li class="<?php if($this->uri->segment(2) == 'single') echo "selected"; ?>">
<?php echo anchor('calendar/single/'.$this->uri->segment(3),translate('Calendar'), array('class' => 'calendar')); ?>
</li>

<!--<li class="clsBorder_No <?php if($this->uri->segment(2) == 'edit_price') echo "selected"; ?>">
<?php echo anchor('rooms/edit_price/'.$this->uri->segment(3),translate('Pricing and Terms'), array('class' => 'pricing')); ?>
</li>-->

</ul>
<ul class="edit_room_nav">
</ul>
