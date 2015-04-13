<div id="dashboard_page" class="container_bg">
<ul id="nav" class="clearfix">
	<li id="dashboard">
	<?php
	 if($this->uri->segment(2) == 'dashboard')
	 echo anchor('home/dashboard/',translate("Dashboard"),array("class" => "Search_Active")); 
		else
		echo anchor('home/dashboard/',translate("Dashboard"),array("class" => "")); 
		?>
	</li>
	
		<li id="rooms">
	<?php
	 if($this->uri->segment(1) == 'message'  && $this->uri->segment(2) == 'inbox')
	 echo anchor('message/inbox',translate("Inbox"),array("class" => "Search_Active")); 
		else
		echo anchor('message/inbox',translate("Inbox"),array("class" => "")); 
		?>
	</li>
	
	<li id="rooms">
	<?php
	 if($this->uri->segment(1) == 'hosting' || $this->uri->segment(2) == 'myReservation' || $this->uri->segment(2) == 'policies')
	 echo anchor('hosting',translate("Hosting"),array("class" => "Search_Active")); 
		else
		echo anchor('hosting',translate("Hosting"),array("class" => "")); 
		?>
	</li>
	
	<li id="rooms">
	<?php
	 if($this->uri->segment(1) == 'travelling')
	 echo anchor('travelling/your_trips',translate("Your Trips"),array("class" => "Search_Active"));
		else
		echo anchor('travelling/your_trips',translate("Your Trips"),array("class" => ""));
		 ?>
	</li>
	
 <li id="rooms">
	<?php
	 if($this->uri->segment(1) == 'account')
	 echo anchor('account',translate("Account"),array("class" => "Search_Active"));
		else
		echo anchor('account',translate("Account"),array("class" => ""));
		?>
	</li>
	
 <li id="rooms" class="clsBg_None">
	<?php
	 if($this->uri->segment(1) == 'users')
	 echo anchor('users/edit',translate("Profile"),array("class" => "Search_Active"));
		else
		echo anchor('users/edit',translate("Profile"),array("class" => ""));
		?>
	</li>
	
</ul>