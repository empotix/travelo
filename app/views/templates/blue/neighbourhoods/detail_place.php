    <script>
$(document).ready(function() {
   $(window).scroll(function() {
       
       var headerH = $('#header').outerHeight(true);
  
       var scrollTopVal = $(this).scrollTop();
        if ( scrollTopVal > headerH ) {
            $('#sticky_nav').css({'position':'fixed','top' :'0px'});
        } else {
            $('#sticky_nav').css({'position':'static','top':'0px'});
        }
     
    });
 });
</script>
   <style>
	.saved_neighbor {
    background: -moz-linear-gradient(center top , #FF009C, #CC007D) repeat scroll 0 0 transparent;
    border: 2px solid #FFFFFF;
    border-radius: 20px 20px 20px 20px;
    color: #FFFFFF;
    font-size: 11px;
    padding: 0 5px 1px;
    position: relative;
	margin-left:10px;
}

	#saved {
	-webkit-touch-callout: none;
-webkit-user-select: none;
-khtml-user-select: none;
-moz-user-select: none;
-ms-user-select: none;
user-select: none;
}
	
</style>
<?php
 $city = $this->uri->segment(3);
 $city = $this->Common_model->city_name($city);	
 $place = $this->uri->segment(4);
 if(isset($place))
 {
 $place = $this->Common_model->place_name($place);
 }
 else {
     $place = '';
 }	
 if(!isset($places))
 {
  header('HTTP/1.0 404 Not Found');
    echo "<div class='404' style='margin:100px'><h1>".translate('404 Not Found')."</h1><br>";
    echo translate("The page that you have requested could not be found.")."</div>";
	$this->load->view(THEME_FOLDER.'/includes/footer.php');
    exit;
 }
 ?>
        <script type="text/javascript">
            var map;
            var geocoder;
            var centerChangedLast;
            var reverseGeocodedLast;
            var currentReverseGeocodeResponse;

            function initialize($lat,$lng,$place) {
            	
                var latlng = new google.maps.LatLng($lat,$lng);
                var myOptions = {
                    zoom: 10,
                    center: latlng,
                    scrollwheel: false,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
                map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
                geocoder = new google.maps.Geocoder();

                var marker = new google.maps.Marker({
                    position: latlng,
                    map: map,
                    title: $place
                });

            }
$(document).ready(function()
{
	$('#saved').click(function()
	{
		var place = '<?php echo $place; ?>';
		var city = '<?php echo $city; ?>';
		
			$.ajax({
				type: 'POST',
				//dataType: 'json',
		data: 'city='+city+'&place='+place,
		url: '<?php echo base_url().'neighbourhoods/saved'; ?>',
		success : function(data){
			//alert(data);
			//if(data == 'inserted')
			//{
				if(data == 'signin')
			 {
			 	window.location.replace("<?php echo base_url().'users/signin'; ?>");return false;
			 } 
				var lastChar = data[data.length -1];
				if(lastChar == ',')
				{
				$('#save_neigh').show();
				$('#save_neigh1').show();
				var count = data.slice(0,-1);
			    $(".saved_neighbor").html(count);
				}
				else
				{
				$('#save_neigh').hide();
				$('#save_neigh1').hide();
			    $(".saved_neighbor").html(data);
			 }
			 
		//}
		}
			})
		
		
	})
	
	$('#save_neigh').click(function()
	{
		var place = '<?php echo $place; ?>';
		var city = '<?php echo $city; ?>';
		
			$.ajax({
				type: 'POST',
		data: 'city='+city+'&place='+place,
		url: '<?php echo base_url().'neighbourhoods/saved'; ?>',
		success : function(data){
			//alert(data);
			//if(data == 'inserted')
			//{
				if(data == 'signin')
			 {
			 	window.location.replace("<?php echo base_url().'users/signin'; ?>");return false;
			 }
				
			 var lastChar = data[data.length -1];
				if(lastChar == ',')
				{
				$('#save_neigh').show();
				$('#save_neigh1').show();
				var count = data.slice(0,-1);
			    $(".saved_neighbor").html(count);
				}
				else
				{
				$('#save_neigh').hide();
				$('#save_neigh1').hide();
			    $(".saved_neighbor").html(data);
			 }
		//}
		}
			})
		
		
	})
	$('#save_neigh1').click(function()
	{
		var place = '<?php echo $place; ?>';
		var city = '<?php echo $city; ?>';
		
			$.ajax({
				type: 'POST',
		data: 'city='+city+'&place='+place,
		url: '<?php echo base_url().'neighbourhoods/saved'; ?>',
		success : function(data){
			//alert(data);
			//if(data == 'inserted')
		//	{
			if(data == 'signin')
			 {
			 	window.location.replace("<?php echo base_url().'users/signin'; ?>");return false;
			 }
				var lastChar = data[data.length -1];
				if(lastChar == ',')
				{
				$('#save_neigh').show();
				$('#save_neigh1').show();
				var count = data.slice(0,-1);
			    $(".saved_neighbor").html(count);
				}
				else
				{
				$('#save_neigh').hide();
				$('#save_neigh1').hide();
			    $(".saved_neighbor").html(data);
			 }
			 
		}
		//}
			})
		
		
	})
	
	$('#suggest').click(function()
	{
		$('#popup').show();
	})
	$('#close').click(function()
	{
		$('#popup').hide();
	})
	$('#close1').click(function()
	{
		$('#local_know').hide();
	})
	$('#knowledge').click(function()
	{
		$('#local_know').show();
	})
})
 function remove_fun($place)
    {
    	
    	var place = $place;
		var city = '<?php echo $city; ?>';
		
    	$.ajax({
				type: 'POST',
		data: 'city='+city+'&place='+place,
		url: '<?php echo base_url().'neighbourhoods/saved_delete'; ?>',
		success : function(data){
			//alert(data);
			$('.saved_neighbor').html(data);
			//if(data == 'deleted')
			//{
				//
				$.ajax({
				type: 'POST',
		data: 'city='+city+'&place='+place,
		url: '<?php echo base_url().'neighbourhoods/saved_delete_remove'; ?>',
		success : function(data){
			//alert(data);
			$('.overlay_flayout').html(data);
		}
		})
				
		//}
		}
			})
    }
        </script>
  <script type="text/javascript">
	$(document).ready(function(){
		$("#popup").validate({
			debug: false,
			rules: {
				tag: {
          required: true,
          minlength: 4,
          maxlength: 15
          }
			},
			messages: {
		        tag:
                    { 
                    	required: "Tag Must Be Required",
                    	minlength: "Minimum 4 Characters Required",
                    	maxlength: "Maximum 15 Characters Only Allowed"                    	
                  }
			},
		});
		$("#local_know").validate({
			debug: false,
			rules: {
				knowledge: {
          required: true,
          minlength: 6
          }
			},
			messages: {
		        knowledge:
                    { 
                    	required: "Local Knowledge Must Be Required",
                    	minlength: "Minimum 6 Characters Required"                 	
                  }
			},
		});
	});
	</script>
	<style>
	label.error { width: 250px; display: inline; color: red; margin: 10px 10px 10px 150px;}
	</style>
<div id="sticky_nav" class="fixed">
    
  <div class="blue breadcrumb">
    <div class="container">
 
      <ul id="neighborhood_nav" class="crumbs">
        <li><a STYLE="TEXT-DECORATION: NONE" href="<?php echo base_url().'home/neighborhoods'; ?>"><?php echo translate('Cities')?></a></li>
          <li><a class="" STYLE="TEXT-DECORATION: NONE" href="<?php echo base_url().'neighbourhoods/city/'.$cities->row()->id;?>"><?php echo $cities->row()->city_name; ?></a></li>
       <?php
       if(isset($place))
	   {
	   	?>
       <li><a class="" STYLE="TEXT-DECORATION: NONE" href="<?php echo base_url().'neighbourhoods/city_detail/'.$cities->row()->id.'/'.$this->uri->segment(4);?>"><?php echo $place; ?></a></li>
     <?php } ?>
      </ul>
      
<ul class="crumbs right">
		<li class="see-neighborhood-listings">
			<a target="_blank" href="<?php echo base_url().'search?location='.$place; ?>">
				<span><?php echo translate('See').' '.$list_count.' '.translate('Places to stay');?></span>
			</a>
		 </li>
		 <li class="ndrop_neighbor">
		 	<?php
		 	$saved_city = $this->db->where('city',$city)->where('user_id',$this->dx_auth->get_user_id())->get('saved_neigh'); 
		 	?>
		  <a style="text-decoration:none" id='saved'><span class="message"><?php echo translate('Saved neighbourhoods')?><span class='saved_neighbor'><?php echo $saved_city->num_rows();?></span></span></a>
    </div>
  </div>
</div>

<div class="city-page page" id="neighborhood_picker">

  <div class="city hero minimal">
  	<?php 
  	
  	$image_s = $this->db->where('place_name',$place)->get('neigh_city_place')->row();
  	
  	?>
  	<?php
  	if(isset($places))
	{
		?>
	
<img width="1425" height="462" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$image_s->image_name; ?>" />
	<?php } 
	else
	{
		
       if(isset($place))
	   {
	   	if($place!='')
		{
	   
		$place_image_name = $this->db->where('place_name',$place)->get('neigh_city_place')->row()->image_name;
	   
		?>
<img width="1425" height="462" src="<?php echo base_url().'/images/neighbourhoods/'.'/'.$image_s->city_id.'/'.$image_s->id.'/'.$place_image_name; ?>" />		
		<?php
	   }
		else
			{
				echo '<center><h1>'.translate('No Data').'</h1></center>';
			}
	   }
	}?>
	<div class="container map svg">
        <ul class="traits">
                
			<?php 
    	   $category_place = $this->db->where('city',$city)->where('place',$place)->get('neigh_place_category');
			
			foreach($category_place->result() as $row)
			{
				echo '<li><p class="large gray btn" style="cursor: default;"><span class="name">'.$this->db->where('id',$row->category_id)->get('neigh_category')->row()->category.'</span></p></li>';
		    }
			
			?>
              </ul>
		</div>
    <div class="neighborhoods_title">
      <div class="container">
        <h1 class="shiftbold" style="text-align:left; font-size:55px;"><?php echo $place; ?></h1>
        <?php
  	if(isset($places))
	{
		?>
        <h2><?php echo $places->row()->short_desc; ?></h2>
        <?php } ?>
      </div>
    </div>
  
</div>
</div>

    <div class="neighborhoods_section">
    	<div class="container">
        	<div class="main_neighborhood">
                <div class="neighborhoods_span8">
                	<?php
  	if(isset($places))
	{
		?>
                    <p class="lede"><?php echo $places->row()->long_desc; ?></p>
                    <?php } ?>
                    <a target="_blank" href="<?php echo base_url().'search?location='.$place; ?>" id="listings_button" class="btn seeplace"><?php echo translate('See').' '.$list_count.' '.translate('places to stay');?></a>
              
              <?php 
            $saved_city1 = $this->db->where('city',$city)->where('place',$place)->where('user_id',$this->dx_auth->get_user_id())->get('saved_neigh');  
              if($saved_city1->num_rows() == 0)
	           {
              ?>
               <script>
               $(document).ready(function()
               {
               	$('#save_neigh').show();
               		$('#save_neigh1').show();
               })
               </script>
               <?php
			   } 
			   ?>
			   <a id='save_neigh' class="cta save-neighborhood-cta" style="display: none" ><?php echo translate('Save this neighborhood');?></a>
              </div>
              <?php
              if(isset($place))
			  {
			  	if($place!='')
				{
              ?>
               <HEAD>
     <meta property="og:image" content="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$image_s->image_name; ?>" />
     <meta property="og:url" content="<?php echo base_url(); ?>" />
     <meta property="og:title" content="<?php echo translate('Love the').' '.$place.' '.translate('neighbourhood page on').' '.$this->dx_auth->get_site_title(); ?>" />
</HEAD>
      <div class="neighborhoods_span4 sidebar">
      
                        <!-- AddThis Button BEGIN -->
<div class="addthis_toolbox addthis_default_style ">
<a class="addthis_button_tweet" tw:count="none"></a>
<a class="addthis_button_pinterest_pinit" pi:pinit:count="none" pi:pinit:layout="horizontal" pi:pinit:media="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$image_s->image_name; ?>"></a>
<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
</div>
<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-525c2c194313da57"></script>
<!-- AddThis Button END -->
<?php }
}
 ?>
                 <h5 style="font-size:15px; margin-bottom:10px; margin-top: 10px; text-align:left;"><?php echo translate('The community says:');?></h5>
                    <ul class="tags">
                    	<?php
                    	if($tag->num_rows()!=0)
						{
							foreach($tag->result() as $row)
							{
								echo '<li style="opacity: 0.575">'.$row->tag.'</li>';
							}
						}
						else { ?>
							<h5 style="font-size:13px; margin-bottom:10px; text-align:left;"><?php echo translate('No tags');?></h5>
						<?php }
                    	?>
                        <a style="float:left; font-size:12px; margin-top:7px;text-transform: lowercase;" class="js-add-feedback" id='suggest'>+ <?php echo translate('Suggest a tag');?></a>
                   </ul>
                </div>
            </div>
        </div>
      
    </div>
    
    </div>
<?php 
if(isset($place_map->row()->lat) && isset($place_map->row()->lng) && isset($place_map->row()->place_name))
		{ ?>
	<div class="city_map" style="clear:both;">
      <div class="container" style="margin-top:15%; padding: 25px; margin-bottom:6%;">
      	
        <h2><?php echo translate('On the Map');?></h2>
        
            <body onLoad="initialize('<?php echo $place_map->row()->lat;?>','<?php echo $place_map->row()->lng; ?>','<?php echo $place_map->row()->place_name; ?>')">
        <div id="map" style="width:100%; height:350px" >
            <div id="map_canvas" style="width:100%; height:350px"></div>
            <div id="crosshair"></div>
        </div>
        </body>
      
      </div>
    </div>
     <?php }
else
	{ ?>
		 <div class="city_map" style="clear:both;">
      <div class="container" style="margin-top:15%;margin-bottom:6%;">
      	
        <h2><?php echo translate('No Map');?></h2>
       
      
      </div>
    </div>
		
<?php	}
 ?>
<?php
if(isset($detail_place))
{
if($detail_place->num_rows() != 0)
{
	$post_count = 1;
	
	foreach($detail_place->result() as $row)
	{
		
		?>
  <div class="section section-offset">
    <div class="container">
    	<section style="padding: 0;">
        <h2 class="neighborhoods_section" style="margin-top:5%;"><?php echo $row->image_title; ?></h2>
	        <div>
                <div class="row" style="margin-top:0;">
                    <div class="span12">
                          <img class="feature" alt="one R1" width="995" height="663" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$row->big_image1; ?>"  />
                    </div>
                </div>
                <div class="center caption">
                    <div class="primary" style="width: 75%;">
                      <p><?php echo $row->image_desc; ?></p>
                    </div>
                </div>
              </div>
         </section style="padding: 0;">
    	<section style="padding: 0;">
	        <div>
                <div class="row">
                    <div class="span12">
                          <img class="feature" width="485" height="304" alt="one R1" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$row->small_image1; ?>"  />
                          <img class="feature1" width="483" height="304" alt="one R1" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$row->small_image2; ?>"  />
                    </div>
                </div>
              </div>
         </section>
    	<section style="padding: 0;">
	        <div>
                <div class="row">
                    <div class="span12">
                          <img class="feature_city" alt="one R1" width="315" height="286" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$row->small_image3; ?>"  />
                          <img class="feature_city" alt="one R1" width="315" height="286" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$row->small_image4; ?>"  />
                          <img class="feature1" alt="one R1" width="315" height="286" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$row->small_image5; ?>"  />
                    </div>
                </div>
              </div>
         </section>
    	<section style="padding: 0;">
	        <div>
                <div class="row">
                    <div class="span12">
                          <img class="feature" alt="one R1" width="485" height="304" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$row->big_image2; ?>"  />
                          <img class="feature1" style="padding: 0;margin: 0;" alt="one R1" width="483" height="304" src="<?php echo base_url().'/images/neighbourhoods/'.$image_s->city_id.'/'.$image_s->id.'/'.$row->big_image3; ?>"  />
                    </div>
                </div>
              </div>
         </section>
         <?php 
         $local_knowledge = $this->db->where('post_id',$row->id)->where('shown',1)->get('neigh_knowledge');
		 
         if($local_knowledge->num_rows() != 0)
		 {
		 //	if($knowledges->row()->post_id == $row->id)
		 foreach($local_knowledge->result() as $knowledge)
			{

		 	?>
    	<section style="padding: 0;">
    		
	        <div>
                <div class="row">
                	<?php 
                    	if($this->Neighbourhoods_model->username($knowledge->user_id))
						{ ?>
                    <div class="span12 neighbor_city_first">
                    	<?php 
                    	$visitor = $this->db->where('username',$this->Neighbourhoods_model->username($knowledge->user_id))->where('photo_status',1)->get('users');
						
						if($visitor->num_rows() !=0 )
						{
							
							$src = base_url().'/images/users/'.$visitor->row()->id.'/userpic_profile.jpg';
		
						}
						else {
							$visitor = $this->db->where('username',$this->Neighbourhoods_model->username($knowledge->user_id))->where('photo_status',2)->get('users');
						
						if($visitor->num_rows() !=0 )
						{
							
						$image = $this->db->where('email',$visitor->row()->email)->get('profile_picture');
						if($image->num_rows()!=0)
	                     {
	                     	$src = $image->row()->src;
	                     }	
						}
						else {
							$src = base_url().'images/no_avatar-xlarge.jpg';
						}
						}
                    	?>
                          <img class="feature" width="110" height="110" style="float: left;" alt="one R1" src="<?php echo $src; ?>"  />
                    </div>
                    <?php } ?>
                    <div class="center caption caption1 neighbor_city_middle">
                        <div class="primary" style=" float:left;">
                          <p class="caption_neighborhoods" align="left">
                          	<em><?php echo $knowledge->knowledge; ?></em></p>
                        </div>
                    </div>
                    <?php 
                    $id = $this->db->where('username',$this->Neighbourhoods_model->username($knowledge->user_id))->get('users');
					if($id->num_rows()!=0)
					{
					$list = $this->db->where('user_id',$id->row()->id)->get('list');
                    if($list->num_rows() != 0)
					{
                    ?>
                    <div class="neighbor_city_view neighbor_city_last">
                        <p class="neighborhoods_user"><?php echo $this->Neighbourhoods_model->username($knowledge->user_id); ?></p>
                        <?php
						if($knowledge->user_type == 'Host')
						{
                        ?>
                        <p class="neighbor_user-host"><?php echo translate('Hosts'); ?> <a href="<?php echo base_url().'rooms/'.$knowledge->room_id; ?>"><?php echo $knowledge->room_title; ?></a></p>
                        <?php
						}
                        ?>
                    <?php ?>
                    </div>
                    <?php
					}
					else { ?>
						<div class="neighbor_city_view neighbor_city_last">
                        <p class="neighborhoods_user"><?php echo $this->Neighbourhoods_model->username($knowledge->user_id); ?></p>
                    </div>
				<?php }
				     	}
					else { ?>
						
						<div class="neighbor_city_view neighbor_city_last">
                        <p class="neighborhoods_user"><?php echo $this->Neighbourhoods_model->username($knowledge->user_id); ?></p>
                    </div>
						
				<?php }
				
					?>
					</div>
              </div>
         </section>
         <?php //} 
        // }
         }
         }
			 ?>
    </div>
  </div>
<?php 
$post_count++;
} }
}
 ?>
 <?php
if($photographers->num_rows() != 0)
{ ?>
	<div class="container">
    	<div class="row">
        	<h2 align="left" style="margin: 25px 0;"><?php echo translate('Photography');?></h2>
            <p class="align_left photograpy">
            <?php echo $this->dx_auth->get_site_title().' '.translate('works with local photographers to capture the spirit of').'<br />'.translate('neighborhoods all around the world. The photography on this page includes work by:'); ?></p>
        </div>
  <?php      
	foreach($photographers->result() as $photographer)
	{
		?>
	
       <div class="container">
        <div class="row">
                    <div class="span12 neighbor_city_first">
                          <img class="feature" width="110" height="110" style="float: left;" alt="one R1" src="<?php echo base_url().'images/neighbourhoods/photographer/'.$photographer->photographer_name.'/'.$photographer->photographer_image;?>"  />
                    </div>
                    <div class="center caption caption1 neighbor_city_middle">
                        <div class="primary" style=" float:left;">
                          <p class="caption_neighborhoods" align="left"><em>
                          	<?php
                          	echo $photographer->photographer_desc;
                          	?>
                          </em></p>
                        </div>
                    </div>
                    <div class="view neighbor_city_last">
                        <p class="neighborhoods_user"><?php echo $photographer->photographer_name; ?></p>
                        <?php
                        if(filter_var($photographer->photographer_web, FILTER_VALIDATE_URL))
                          {
                          echo '<a href="'.$photographer->photographer_web.'" class="neighbor_views neighbor_web_city">'.translate("View website").'»</a>';
                           }
						else
							{
								echo '<p class="neighbor_views">'.translate('No website').'</p>';
							}
                        ?>
                        
                    </div>
					</div>
					
    </div>
    </div>
<?php 
}
}
?>
  <div class="section section-offset" style="clear:both; margin-top:6%; margin-bottom:6%; padding-bottom:2%;">
		<div class="container">
			<h2 class="neighborhood_popular"><?php echo translate('Popular Places to Stay'); ?></h2>
				
			  	<section>
	        <div>
                <div class="row" style="margin-top:15px;">
                	
           <?php
           if($this->Common_model->place_name($this->uri->segment(4))!=FALSE)
		   {
           if($lists->num_rows() != 0 )
		   {   $i = 0; 
		   	  foreach($lists->result() as $list)
			  {
			  		
						$list_photo = $this->db->where('list_id',$list->id)->where('is_featured',1)->get('list_photo');
						if($list_photo->num_rows() != 0)
						{
			  	 ?>
			 <div class="neighborhood_cites">
			  	 <div class="span12 city_list">
            <a href="<?php echo base_url().'rooms/'.$list->id; ?>">
            	<img width="485" height="323" class="feature" alt="one R1" src="<?php echo base_url().'images/'.$list->id.'/'.$list_photo->row()->name;?>"  />
             </a> 
             <?php 
             
                    	$user_photo = $this->db->where('id',$list->user_id)->where('photo_status',1)->get('users');
						
						if($user_photo->num_rows() !=0 )
						{
							
							$src = base_url().'/images/users/'.$user_photo->row()->id.'/userpic_profile.jpg';
		
						}
						else {
							$user_photo = $this->db->where('id',$list->user_id)->where('photo_status',2)->get('users');
						
						if($user_photo->num_rows() !=0 )
						{
							
						$image = $this->db->where('email',$user_photo->row()->email)->get('profile_picture');
						if($image->num_rows()!=0)
	                     {
	                     	$src = $image->row()->src;
	                     }	
						}
						else {
							$src = base_url().'images/no_avatar-xlarge.jpg';
						}
						}
                    	?>
                        <div class="cite">
				<img width="38" height="38" src="<?php echo $src; ?>"/>
				<p class="cite_user"> <?php echo $list->title; ?> </p>
             

			 <?php
			 echo '<p class="city_price">'.translate('by').' '.$user_photo->row()->username.' - '.'$'.$list->price.'</p> </div></div>';
					}
			  }
		   }
		   else {
			   echo '<p class="neighbor_nolist">'.translate('No Listings Found.').'</p>';
		   }
		   }
           ?>
    	 
    	  </div>
              </div>
              </div>
			<div class="container" style="clear:both;">
				<a target="_blank" style="margin-top:-1px!important;margin-bottom:0px;float:left;" class="see_more" href="<?php echo base_url().'search?location='.$place; ?>"><?php echo translate('See places to stay');?></a>
			<a id='save_neigh1' class="cta save-neighborhood-cta" style="display: none" ><?php echo translate('Save this neighborhood');?></a>
			</div>
         </section>

		</div>
  </div>

  <div id="places" class="section">
    <div class="recommendations-wrapper">
        <div id="recommendations">
<div class="featured-neighborhoods section" style="padding:0 0 35px;">
      <div class="container">
        <div class="row" style="margin-top:0px;">
          <div class="span12  center">
            <h2 class="shiftbold" style="text-align:left; margin-bottom:30px;">
              <?php echo translate('Similar to').' '.$place;?>
            </h2>
            <ul class=" neighborhoods">
            	<?php
            	if(isset($index_places))
				{
					if($index_places->num_rows()!=0)
					{
            	foreach($index_places->result() as $index)
				{
			
            	?>
      <li style="" data-neighborhood-permalink="the-west-end" data-neighborhood-id="1127" class=" tile ">
        <div class="photo">
  <h3 class="shiftbold"><a class="" href="#"><?php echo $index->place_name; ?></a></h3>
  <a class="" href="<?php echo base_url().'neighbourhoods/city_detail/'.$index->city_id.'/'.$index->id.'/'.$index->place_name;?>">
  	<img width="315" height="210" src="<?php echo base_url().'images/neighbourhoods/'.$index->city_id.'/'.$index->id.'/'.$index->image_name;?>" alt="<?php echo $index->place_name.' - '.$index->city_name; ?>"></a>
</div>
  <div class="blurb">
  <p><?php echo $index->quote; ?></p>
    
       <ul class="tags">
    	<?php 
    	$category_place = $this->db->where('city_name',$index->city_name)->where('place_name',$index->place_name)->where('is_featured',1)->get('neigh_city_place')->row()->category;
			
			$cat_ex = explode(',',$category_place);
			foreach($cat_ex as $row_cat)
			{
				echo '<li>'.$this->db->where('id',$row_cat)->get('neigh_category')->row()->category.'</li>';
			}
			
			?>
    </ul>

  </div>
<div style="display: none;" class="sub friends">
  <ul>
  </ul>
  <p>
  </p>
</div>
      </li>
      <?php 
  
      }
      }
else {
	echo '<p class="neighbor_nolist" style="text-align:left;">'.translate('No Neighbourhood Places').'<p>';
}
      }
else {
	echo '<p class="neighbor_nolist" style="text-align:left;">'.translate('No Neighbourhood Places').'<p>';
}
?>
</ul>
          </div>
        </div>
      </div>
    </div>

    </div>
  </div>
  </div>
<div class="neighborhood-list section section-offset">
  <div class="container">
    <div class="row knowthis">
      <div class="span12">
        <h2><?php echo translate('Know this Neighbourhood?');?></h2>
      </div>
    <div class="row">
      <div class="span12">
        <p><?php echo translate('We worked closely with our community to create this guide. Share your thoughts with us so that we can continue bringing it to life. What do you find inspiring about this neighbourhood?');?></p>
		<p><a id="knowledge"><?php echo translate('Share your local knowledge'); ?> »</a></p>
      </div>
      </div>
</div>
</div>
</div>
  <div class="neighborhood-list section section-offset" style="border-top:2px solid #DBDBDB;">
  <div class="container">
    <div class="row" style="margin: 35px 0 0px;">
      <div class="span12">
        <h4><?php echo translate('All Neighbourhoods'); ?></h4>
      </div>
    </div>
    <div class="row" style="margin: 0px 0 35px;">
      <a name="all-neighborhoods"></a>
        <div class="span3">
          <ul>
          	<?php
          		if(isset($places))
			{
						foreach($places->result() as $row)
						{
							echo '<li><a href='.base_url().'neighbourhoods/city_detail/'.$row->city_id.'/'.$row->id.'>'.$row->place_name.'</a></li>';
						 }
			}
				else
					{
							echo '<li>No Neighbourhood Places</li>';
					} ?>
          </ul>
        </div>
    </div>
  </div>
</div>
 
<form class="modal in" id="popup" style="display: none" method="post" action="<?php echo base_url().'neighbourhoods/suggest_tag?city='.$this->uri->segment(3).'&place='.$this->uri->segment(4);?>">
<div class="modal-header">
	<h3><?php echo translate('Suggest a tag'); ?></h3>
</div>
<div class="modal-body" style="text-align:left;over-flow: heiddtn;">
  <label style="float:left;"><?php echo translate('What word or phrase comes to mind when you think of this neighborhood?');?></label>
  <input type="text" style="float:left;" name="tag" class="js-feedback-body span6" title="Minimum 6 Characters"/>
</div>
<div class="modal-footer">
  <input type="submit" style="text-transform: none;" data-modal-close="true" class="btn js-save nei_submit" value="Submit Tag">
  <a data-modal-close="true" style="text-transform:none;" class="btn gray js-close" id="close"><?php echo translate('Close');?></a>
</div>
</form>

<form class="modal in" id="local_know" style="display: none" method="post" action="<?php echo base_url().'neighbourhoods/local_knowledge?city='.$this->uri->segment(3).'&place='.$this->uri->segment(4);?>">
<div class="modal-header">
	<h3><?php echo translate('Share your local knowledge'); ?></h3>
</div>
<div class="modal-body" style="text-align:left;over-flow: heiddtn;">
  <label style="float:left;"><?php echo translate('We appreciate your taking the time to provide your insights. We rely on our community to bring these guides to life.');?></label>
  <input type="text" style="float:left;" name="knowledge" class="js-feedback-body span6" title="Minimum 6 Characters"/>
</div>
<div class="modal-footer">
  <input type="submit" style="text-transform: none;" data-modal-close="true" class="btn js-save nei_submit" value="Submit">
  <a data-modal-close="true" style="text-transform:none;" class="btn gray js-close" id="close1"><?php echo translate('Close');?></a>
</div>
</form>