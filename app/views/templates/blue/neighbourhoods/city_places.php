<?php
$city1 = $this->uri->segment(3);
$city = $this->Common_model->city_name($city1);
?>
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

<script>
$(document).ready(function() {
   $(window).scroll(function() {
       
       var savedS = $('#header').outerHeight(true);
   
       var scrollTopVal = $(this).scrollTop();
        if ( scrollTopVal > headerH ) {
            $('#saved').css({'position':'fixed','top' :'89px'});
        } else {
            $('#saved').css({'position':'static','top':'39px'});
        }
     
    });
 });
</script>

<script>
function category($filter)
{
	var filter = [];
        $(':checkbox:checked').each(function(i){
          filter[i] = $(this).val();
        });
        var filter_un = [];
         $("input:checkbox:not(:checked)").each(function(i){
          filter_un[i] = $(this).val();
        });
    	
	$.ajax({
		type: 'POST',
		data: 'filter=filter&category_id='+filter+'&un_check='+filter_un,
		url: '<?php echo base_url().'neighbourhoods/city/'.$this->uri->segment(3).'/places'; ?>',
		success : function($data){
           
                 $('#places').replaceWith($data);
                 $.ajax({
		type: 'POST',
		data: 'category_id='+filter+'&un_check='+filter_un,
		dataType: "json",
		url: '<?php echo base_url().'neighbourhoods/category_count/'.$this->uri->segment(3); ?>',
		success : function(result){
			     //alert(result);return false;
			  $.each(result, function(key, value)
			  {
			   $('.count'+value['id']).replaceWith('<span class=count'+value["id"]+'>'+value['count']+'<span>');
			  
              });
			  
         }
	})
         }
	})
}
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

<?php

if(!isset($neighbourhoods))
{
	header('HTTP/1.0 404 Not Found');
    echo "<div class='404' style='margin:100px'><h1>".translate('404 Not Found')."</h1><br>";
    echo translate("The page that you have requested could not be found.")."</div>";
	$this->load->view(THEME_FOLDER.'/includes/footer.php');
    exit;
}
//$city = $this->uri->segment(3);
foreach($cities->result() as $row)
{	?>

<div class="cities-wrapper">
    
<div id="sticky_nav" style="top: -3px;">
  

  <div class="blue breadcrumb">
    <div class="container">
   
      <ul id="neighborhood_nav" class="crumbs" style="text-transform:capitalize;">
        <li><a STYLE="TEXT-DECORATION: NONE" href="<?php echo base_url().'home/neighborhoods'; ?>"><?php echo translate('Cities')?></a></li>
          <li><a class="" STYLE="TEXT-DECORATION: NONE" href="<?php echo base_url().'neighbourhoods/city/'.$cities->row()->id;?>"><?php echo $cities->row()->city_name; ?></a></li>
      </ul>
            
<ul class="crumbs right">
		 <li class="ndrop_neighbor">
		 	<?php
		 	$saved_city = $this->db->where('city',$city)->where('user_id',$this->dx_auth->get_user_id())->get('saved_neigh'); 
		 	?>
		   <a STYLE="TEXT-DECORATION: NONE" id='saved'><span class="message"><?php echo translate('Saved neighbourhoods')?></span><span class="saved_neighbor"><?php echo $saved_city->num_rows();?></span></a>
	      	  	
						<?php
	
	if($saved_city->num_rows() != 0)
	{
	?>
			<ul class="overlay_flayout" id="overlay_flayout">
				<?php 
          		foreach($saved_city->result() as $save)
				{
          		?>
                <li>
                   <a href="<?php echo base_url().'neighbourhoods/city_detail/'.$save->city_id.'/'.$save->place_id; ?>"><?php echo $save->place; ?></a>
                    <a id="remove" href="#" onclick="remove_fun('<?php echo $save->place;?>');" class="remove">x</a>
                </li>
                 <?php } ?>
                
                 <li>
              <a href="<?php echo base_url().'search?location='.$save->place; ?>" target="_blank" class="to-p"><?php echo translate('See listings in all saved neighborhoods');?> »</a>
            </li>
          <?php } ?>
            </ul>

    </div>
  </div>
</div>

<div id="neighborhood_picker" class="city-page page">

  <div class="city hero minimal">
	<img src="<?php echo base_url().'images/neighbourhoods/'.$row->id.'/crop.jpg';?>" width=1425>

    <div class="title overlay">
      <div class="container">
        <h1 class="shiftbold" style="font-size: 40px!important;margin-top:34px;position:absolute;left:-10px;"><?php echo translate('Find a Neighbourhood in')?> <?php echo $cities->row()->city_name; ?>
        </h1>
      </div>
    </div>
  </div>


  <div class="loading container">
  </div>

  <div class="section section-offset" style="padding: 10px 0px;">
    <div class="container">
      <div class="row">
        <div class="span12">
          <h3 style="font-size:18px;"><?php echo translate('What kind of neighbourhood are you looking for?'); ?></h3>
        </div>
      </div>

      <ul id="trait_selector" class="traits" style="left:0px!important;top:40px;margin:0;width:990px;">
	<?php 
	if(isset($neighbourhoods))
{
	foreach($place_category->result() as $row_place)
		  {
			foreach($categories->result() as $row_category)
			{
				$category_btn[] = $this->db->where('id',$row_place->category_id)->get('neigh_category')->row()->category;
				
			}
		  } 
		  $category_final = array_unique($category_btn); 
		  $i = 0;
		 foreach($category_final as $row)
		 {
		 	$category_id = $this->db->where('category',$row)->get('neigh_category')->row()->id;
			?>
		
    <li data-trait-id="12" class="trait  active">

    <a data-trait-id="12" class="premote trait-link large btn"  href="#"> <?php
    	$category_id_1 = $this->db->where('category',$first_category)->get('neigh_category')->row()->id; 
		if($category_id_1==$category_id)
		{
			$s="checked='checked'";
		}
		else {
			$s="";
		}
    	?>
   <label> <input id="check1" type="checkbox" value="<?php echo $category_id; ?>" onClick="category('<?php echo $row;?>');" <?php echo $s; ?>/> <label for="check1"> </label>
     
        <span class="name"><?php echo $row; ?></span>
        <?php
        $result = $this->db->where('category',$row)->from('neigh_category')->get()->row()->id;
		//$city = $this->uri->segment(3);
		
		$count = 0;
		
       $this->db->distinct()->select('neigh_place_category.place')->where('category_id',$result)->where('neigh_place_category.city',$city);
$this->db->from('neigh_place_category');
$this->db->join('neigh_post', 'neigh_post.place = neigh_place_category.place'); 
$count_ = $this->db->get();

if($count_->num_rows()!=0)
{
foreach($count_->result() as $row)
{
	$city_p = $row->place;
}
$count   = $this->db->where('place_name',$city_p)->get('neigh_city_place')->num_rows();
}
        ?>
        <span class="count<?php echo $category_id;?>"><?php echo $count; ?></span></label>
</a>    </li>
<?php $i++; }
 ?>
 
</ul>
    </div>
  </div>

  <div class="section" id="places">
    <div class="recommendations-wrapper">
        <div id="recommendations">
    <div class="container">
      <?php
			//$city = $this->uri->segment(3);
			
			$category_id = $this->db->where('category',$first_category)->get('neigh_category')->row()->id;
			
			$count = 0;
			
           $this->db->group_by('neigh_place_category.category_id')->select('*')->where('category_id',$category_id)->where('neigh_place_category.city',$city);
           $this->db->from('neigh_place_category');
           $this->db->join('neigh_post', 'neigh_post.place = neigh_place_category.place'); 
           $count   = $this->db->get()->num_rows();
if(count($category_id)>0)
		{ 
        $join = '';
        $i=1;
		$this->db->distinct()->select('t0.place')->from('neigh_place_category t0');
       
			$this->db->join("neigh_place_category t$i","t0.place=t$i.place")->where("t$i.category_id",$category_id);
			$i++;
		
		$this->db->where('t0.city',$city);
		$count = $this->db->get()->num_rows();
		}$count = $neighbourhoods->num_rows();
			?> 
      <h3 style="margin-top:3%;font-size:18px;">
        <span class="results"><?php echo $count.' '.translate('neighbourhoods match').' "'.$first_category.'".'; ?></span> 
        <a target="_blank" href="<?php echo base_url().'search?location='.$city?>"><?php echo translate('See all listings');?></a>
      </h3>
    <p></p>
    </div>
  </div>

    </div>
    <div id="neighborhood_tiles" class="container hidden" style="display: block;">
        <ul class="trait-neighborhoods neighborhoods">
      
<?php
if($neighbourhoods->num_rows()!=0)
{
					foreach($neighbourhoods->result() as $row)
					{ ?>
    
        <li style="display: list-item;" data-neighborhood-permalink="kings-cross" data-neighborhood-id="1805" class="trait-tile tile ">
        <div class="photo">
  <h3 class="shiftbold"><a class="" href="<?php echo base_url().'neighbourhoods/city_detail/'.$row->city_id.'/'.$row->id;?>"><?php echo $row->place_name; ?></a></h3>
  <a class="" href="<?php echo base_url().'neighbourhoods/city_detail/'.$row->city_id.'/'.$row->id;?>"><img width="315" height="210" src="<?php echo base_url().'/images/neighbourhoods/'.$row->city_id.'/'.$row->id.'/'.$row->image_name; ?>" alt="<?php echo $row->place_name;?>"></a>
</div>
  <div class="blurb">
    <p>
    	<?php 
    if($row->quote)
	{
    echo $row->quote;
	}
	else
		{
			echo translate('No Quote');
		} ?>.</p>
    <ul class="tags">
    	<?php 
		$category_place = $this->db->where('city',$row->city_name)->where('place',$row->place_name)->where('shown',1)->get('neigh_tag');	
			foreach($category_place->result() as $row)
			{
				echo '<li>'.$row->tag.'</li>';
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
	?>
					</ul>
<?php
}
else
	{
		echo translate('No Data');
	}	?>
    </div>
  </div>

  <div class="neighborhood-list section section-offset">
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
          	if(isset($all_places))
			{
						foreach($all_places->result() as $row)
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


</div>

  </div>
  <?php
  }
  ?>