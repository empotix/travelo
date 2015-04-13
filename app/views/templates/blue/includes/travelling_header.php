	<ul class="subnav" id="submenu">

		<!--	<?php
			if($this->uri->segment(1) == 'travelling' && $this->uri->segment(2) == 'current_trip')  echo '<li class="active">'; else echo '<li>'; 
			
				echo anchor('travelling/current_trip', translate("Current Trips")); 
				
				echo '</li>';
		 ?>-->

		<?php
			if($this->uri->segment(1) == 'travelling' && $this->uri->segment(2) == 'your_trips') echo '<li class="active">'; else echo '<li>';
			 
				echo anchor('travelling/your_trips', translate("Your Trips")); 

    echo '</li>';
		 ?>

		<?php	if($this->uri->segment(1) == 'travelling' && $this->uri->segment(2) == 'previous_trips') echo '<li class="active">'; else echo '<li>'; 
		
			echo anchor('travelling/previous_trips', translate("Previous Trips")); 
			
			echo '</li>';
		?>
		
		<!--<?php	if($this->uri->segment(1) == 'travelling' && $this->uri->segment(2) == 'starred_items') echo '<li class="active clsBorder_No">'; else echo '<li class="clsBorder_No">'; 
		
			echo anchor('travelling/starred_items', translate("Starred Items")); 
			
			echo '</li>';
		?>-->

	</ul>