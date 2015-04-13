<script>
$(document).ready(function() {
   $(window).scroll(function() {
       
       var headerH = $('#header').outerHeight(true);
    //   console.log(headerH);
//this will calculate header's full height, with borders, margins, paddings
       var scrollTopVal = $(this).scrollTop();
        if ( scrollTopVal > headerH ) {
            $('#sticky_nav').css({'position':'fixed','top' :'0px'});
        } else {
            $('#sticky_nav').css({'position':'static','top':'0px'});
        }
     
    });
 });
</script>

<!--<script>
            $(document).ready(function(){
                $("#ndrop_neighbor").mouseover(function() {
                    $("#overlay_flayout").css("position","fixed");
                })
                $("#ndrop_neighbor").mouseleave(function() {
                    $("#overlay_flayout").css( "display", "none" );
					alert("ahh");
                })
            })
        </script>-->

<script>
$(document).ready(function() {
   $(window).scroll(function() {
       
       var sticky_nav1 = $('#sticky_nav1').outerHeight(true);
   
       var scrollTopVal = $(this).scrollTop();
        if ( scrollTopVal < sticky_nav1 ) {
            $('#saved_m').css({'position':'fixed','top' :'89px'});
        }
		else {
            $('#saved_m').css({'position':'fixed','top':'39px'});
		}
    });
    
 });
 function remove_fun($place)
    {
    	
    	var place = $place;
		var city = '<?php echo $this->Common_model->city_name($this->uri->segment(3)); ?>';
		
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
$city = $this->uri->segment(3);
//if(isset($city))
// {
 $city = $this->Common_model->city_name($city);
 //}	
 
foreach($cities->result() as $row)
{	?>

<div id="sticky_nav">
  <div class="blue breadcrumb">
    <div class="container">
     <!--  <div id="neighborhood_cart"><p>Saved this neighborhood!</p>
</div>-->
      <ul id="neighborhood_nav" class="crumbs">
        <li><a STYLE="TEXT-DECORATION: NONE" href="<?php echo base_url().'home/neighborhoods'; ?>"><?php echo translate('Cities')?></a></li>
           <?php
       if(isset($place))
	   {
	   	?>
          <li><a class="" STYLE="TEXT-DECORATION: NONE" href="<?php echo base_url().'neighbourhoods/city/'.$cities->row()->id;?>"><?php echo $cities->row()->city_name; ?></a></li>
      <?php } ?>
      </ul>
	<ul class="crumbs right">
		 <li class="ndrop_neighbor" id="ndrop_neighbor">
		 	<?php
		 	$saved_city = $this->db->where('city',$city)->where('user_id',$this->dx_auth->get_user_id())->get('saved_neigh'); 
		 	?>
			<a style="text-decoration:none" id='saved'><span class="message"><?php echo translate('Saved neighbourhoods')?></span><span class="saved_neighbor"><?php echo $saved_city->num_rows();?></span></a>
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
          </li>
		</ul>
     
    </div>
  </div>
</div>

<div class="city-page page" id="neighborhood_picker">
  <div class="city hero minimal_1" style="margin-bottom:0;background-image:url('<?php echo base_url().'images/neighbourhoods/'.$row->id.'/'.$row->image_name; ?>');">
 
    <!-- <img src="http://demo.cogzideltemplates.com/client/dropinn-166/css/templates/blue/images/full_img.png"/>-->
      <img width="1425" height="500" src="<?php echo base_url().'images/neighbourhoods/'.$row->id.'/'.$row->image_name; ?>">

    <div class="title overlay" style="padding: 105px 4px 0 0;">
      <div class="container">
        <h1 class="shiftbold"><?php echo $row->city_name; ?></h1>
        <h2><?php echo $row->city_desc; ?></h2>
        <?php
echo '<a class="btn large gray" href='.base_url().'neighbourhoods/city/'.$row->id.'/places>'.translate('Find a neighbourhood').'</a>';
?>	
      </div>
    </div>
  </div>

  <div class="loading container">
  </div>

  <div class="section section-offset" style="margin-top: 0;  padding-bottom: 30px; padding-right: 100px; padding-top:0;">
    <div class="container">
      <div class="row">
        <div class="span12">
            <div class="span3 widget">
            
                <h5><?php echo translate('Get around with'); ?></h5>
                  <big class="shiftbold"><?php echo $row->around; ?></big>
                  <div class="extra">
                  </div>
              </div>
          
        </div>
      </div>
      <div class="span3 widget">
            <h5><?php
	echo translate('Places to stay').'<br>';
	?></h5>
	<?php
	//$city = $this->uri->segment(3);
	//$places         = $this->db->where('city_name',$city)->where('is_featured',1)->get('neigh_city_place');
//	if($places->num_rows() != 0)
//	{
	 $list = $this->db->like('address',$city)->where('is_enable',1)->get('list');
	    if($list->num_rows() != 0)
	    {
	    	$count = $list->num_rows();
	    	?>
            <big class="shiftbold"><?php echo $count; ?></big><br/>
            <a class="span_13" target="_blank" href="<?php echo base_url().'search?location='.$city; ?>"><?php echo translate('See').' '.$count.' '.translate('listings'); ?> »</a>
            <?php
	    }
		else { ?>
			<big class="shiftbold">0</big>
		<?php	echo translate('No Listings Found.');
		}
	//}
	    ?>
          </div>
      

		<div class="span3 widget" style="width:485px">
              <h5><?php echo translate('Known For'); ?></h5>
              <p class="neighbor_widgth"><?php echo $row->known; ?></p>
          </div>
          
    </div>
  </div>

  <div id="places" class="section"style="padding: 5px 0px;margin-top: -37px;">
    <div class="recommendations-wrapper">
        <div id="recommendations">
<div class="featured-neighborhoods section" style="padding-bottom: 0px;">
      <div class="container">
        <div class="row">
          <div class="span12  center">
            <h2 class="shiftbold"style="margin-bottom: 26px; margin-top:7%;">
             <?php
	echo translate('Featured Neighbourhoods');
	?>
            </h2>
            
            <ul class=" neighborhoods"style="margin-bottom: 19px;">
            	<?php
            	if(isset($index_places))
				{
		foreach($index_places->result() as $row_place)
		{		?>
      <li style="" data-neighborhood-permalink="the-west-end" data-neighborhood-id="1127" class=" tile ">
        <div class="photo">
  <h3 class="shiftbold"><a class="" href="<?php echo base_url().'neighbourhoods/city_detail/'.$row_place->city_id.'/'.$row_place->id;?>"><?php echo $row_place->place_name; ?></a></h3>
  <a href="<?php echo base_url().'neighbourhoods/city_detail/'.$row_place->city_id.'/'.$row_place->id;?>">
  	<img width="315" height="210" src="<?php echo base_url().'images/neighbourhoods/'.$row_place->city_id.'/'.$row_place->id.'/'.$row_place->image_name; ?>" alt="<?php echo $row_place->place_name.' - '.$row_place->city_name; ?>"></a>
</div>
  <div class="blurb">
    <p><?php echo $row_place->quote; ?></p>
    <ul class="tags">
    	
			<?php 
    	$category_place = $this->db->where('city',$row_place->city_name)->where('place',$row_place->place_name)->where('shown',1)->get('neigh_tag');
			//if(strpos(',',$category_place))
			//{
			//$cat_ex = explode(',',$category_place);
			if($category_place->num_rows()!=0)
			{
			foreach($category_place->result() as $row)
			{
				echo '<li>'.$row->tag.'</li>';
		    }
			}
			//}
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
else {  echo translate('No Neighbourhood Places'); 
} ?>
</ul>
            <?php
            if(isset($city))
			{
echo '<a class="btn large center more" style="text-transform:none;" href='.base_url().'neighbourhoods/city/'.$this->uri->segment(3).'/places>'.translate('More neighbourhoods').' »</a>';

}
?>
          </div>
        </div>
      </div>
    </div>

    </div>
  </div>

 <div class="neighborhood-list section section-offset" style="padding-top: 0px;padding-bottom: 38px;border-top:2px solid #DBDBDB;">
  <div class="container">
    <div class="row" style="margin: 35px 0 0px;">
      <div class="span12" style="font-size: 15px;">
        <h4><?php echo translate('All Neighbourhoods'); ?></h4>
      </div>
    </div>
    <div class="row" style="margin: 0px 0 35px;">
      <a name="all-neighborhoods"></a>
        <div class="span3"style="font-size: 13px;">
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
<?php // exit; 
} ?>

