	<ul class="subnav" id="submenu">
	
			<?php
			if($this->uri->segment(1) == 'hosting' && $this->uri->segment(2) == '')  echo '<li class="active">'; else echo '<li>'; 
			
				echo anchor('hosting', translate("Manage Listings")); 
				
				echo '</li>';
		 ?>

		<?php
			if($this->uri->segment(2) == 'my_reservation') echo '<li class="active">'; else echo '<li>';
			 
				echo anchor('hosting/my_reservation', translate("My Reservations")); 

    echo '</li>';
		 ?>

		<?php	if($this->uri->segment(2) == 'policies') echo '<li class="active clsBorder_No">'; else echo '<li class="clsBorder_No">'; 
		
			echo anchor('hosting/policies', translate("Policies")); 
			
			echo '</li>';
		?>
	</ul>