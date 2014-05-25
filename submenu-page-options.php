<?php switch ( $current ) {
		
case 'plugin':
	echo   '<div class="postbox-container" style="width:100%;">
			<div class="metabox-holder">
			<div class="meta-box-sortables ui-sortable">
			<div id="adminform" class="postbox">
			<h3 class="hndle"><span>Fluid Responsive Slideshow</span></h3>
			<div class="inside">';

	require( plugin_dir_path( __FILE__ ) . 'manual.php');

	echo   '</div>			
			</div>			
			</div>			
			</div>			
			</div>';
?>



<?php
break;
case $current:
?>
<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
	<br><div class="updated fade"><p><strong><?php _e('Options saved', 'pjc_slideshow_options'); ?></strong></p></div>
<?php endif; ?> 
<form method="post" action="options.php" id="frs-option-form">
<?php settings_fields('pjc_options'); ?>


<?php

  foreach ( $terms as $term ){

 
	  
	if(isset($options[$term->slug]) && $term->slug!=$current){
		foreach ($options[$term->slug] as $key => $value) {
			echo "<input type='hidden' value='$value' name='pjc_slideshow_options[$term->slug][$key]'>";
		}
	}

  }


?>

<div class="metabox-holder columns-2" style="margin-right: 300px;">
<div class="postbox-container" style="width: 100%;min-width: 463px;float: left; ">
<div class="meta-box-sortables ui-sortable">
<div id="adminform" class="postbox">
<h3 class="hndle"><span><?php echo "Slide '$current_name' Options"?></span></h3>
<div class="inside" style="z-index:1;">
<!-- Extra style for options -->
<style>
	.form-table td {
		vertical-align: middle;
	}

	.form-table th {
		width: 150px
	}

	.form-table input,.form-table select {

		width: 150px;
		margin-right: 10px;
	}

	.frs-slideshow-container {
		margin-left: auto;
		margin-right: auto;
	}

	<?php
		if($options[$current]['navigation']=='false')
			echo "
				.tonjoo_nav_option{
					display:none;
				}
			";
	?>

	label.error{
	    margin-left: 5px;
	    color: red;
	}

	.form-table tr th {
	    text-align: left;
	    font-weight: normal;
	}

	.meta-subtitle {
	    margin: 0px -22px !important;
	    border-top:1px solid rgb(238, 238, 238);
	    background-color:#f6f6f6;
	}

	@media (max-width: 767px) {
		    .meta-subtitle {
		      margin-left: -12px !important;
		    }
	}
</style>

<script type="text/javascript">
jQuery(document).ready(function($){
	$("#tonjoo-frs-textbox-bg select").change(function(){
		value = $(this).attr('value')

		$("#picture_prev").css('background-image',"url('<?php echo plugins_url( 'fluid-responsive-slideshow/backgrounds/' , dirname(__FILE__) ) ?>" + value + ".png')");
	})

	$("select[name='pjc_slideshow_options[<?php echo $current ?>][skin]']").css({"width":'250px'});

	$("select[name='pjc_slideshow_options[<?php echo $current ?>][skin]']").select2();
})
</script>

<div id="preview_slide" style="margin:25px 20px;">
<?php 
	$attr['slide_type'] = $current;
	echo pjc_gallery_print($attr) 
?>
</div>


<table class="form-table">
	<tr><td colspan=2><h3 class="meta-subtitle">Skin & Animation</h3></td></tr>

	<?php

	$dir =  dirname(__FILE__)."/skins";

	
	if(! isset($options[$current]['skin']))
			$options[$current]['skin']="default";


	$skins = scandir($dir);

	$slideshow_skin =  array();

	foreach ($skins as $key => $value) {

		$extension = pathinfo($value, PATHINFO_EXTENSION); 
		$filename = pathinfo($value, PATHINFO_FILENAME); 
		$extension = strtolower($extension);
		$the_value = strtolower($filename);
		$filename_ucwords = str_replace('-', ' ', ucwords($filename));
		$filename_ucwords = ucwords($filename_ucwords);

		if($extension=='css'){
			$data = array(
					"label"=>"$filename_ucwords",
					"value"=>"$the_value"								

				);

			array_push($slideshow_skin,$data);

		}
	}

	if(is_plugin_active("fluid-responsive-slideshow-premium/Fluid-Responsive-Slideshow-Premium.php") && function_exists('is_frs_premium_exist')) 
	{
		
		$dir =  ABSPATH . 'wp-content/plugins/fluid-responsive-slideshow-premium/skins';

		$skins = scandir($dir);

		foreach ($skins as $key => $value) {

			$extension = pathinfo($value, PATHINFO_EXTENSION); 
			$filename = pathinfo($value, PATHINFO_FILENAME); 
			$extension = strtolower($extension);
			$the_value = strtolower($filename);
			$filename_ucwords = str_replace('-', ' ', $filename);
			$filename_ucwords = ucwords($filename_ucwords);


			if($extension=='css'){
				$data = array(
						"label"=>"$filename_ucwords (Premium)",
						"value"=>"$the_value-PREMIUMtrue"

					);

				array_push($slideshow_skin,$data);

			}
		}
	}





	$option_select = array(
					"name"=>"pjc_slideshow_options[{$current}][skin]",
					"description" => "&nbsp; Select skin",
					"label" => "Skin",
					"value" => $options[$current]['skin'],
					"select_array" => $slideshow_skin,
					"id"=>"tonjoo-frs-skin"
				);

	
	 tj_print_select_option($option_select);
	?>

	<tr valign="top">
		<th scope="row">Animation</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][animation]"?>">
				<?php
				
					$navigation = array(									
						'0' => array(
							'value' =>	'horizontal-slide',
							'label' =>  'Horizontal Slide' 
						),
						'1' => array(
							'value' =>	'vertical-slide',
							'label' =>  'Vertical Slide'
						),
						'2' => array(
							'value' =>	'fade',
							'label' =>  'Fade'
						)
					);
					
				
					$selected = $options[$current]["animation"];
					$p = '';
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] ) // Make default first in list
							$p = "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $p . $r;
				?>
			</select>
			<label class="description" >The animation type in slide transition</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Dimension</h3></td></tr>

	<tr valign="top">
		<th scope="row">Width</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][width]"?>" value="<?php esc_attr_e($options[$current]["width"]); ?>" />
			<label class="description" >Maximum slider width</label>
		</td>
	</tr>
			
	<tr valign="top">
		<th scope="row">Height</th>
		<td>
			<input class="regular-text" type="text" name="pjc_slideshow_options<?php echo "[$current][height]"?>" value="<?php esc_attr_e($options[$current]["height"]); ?>" />
			<label class="description" >Slider height</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Text Box</h3></td></tr>

	 <?php 

	$slideshow_select = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);


	$option_select = array(
					"name"=>"pjc_slideshow_options[{$current}][show_textbox]",
					"description" => "Select yes if you to show the textbox",
					"label" => "Show Textbox",
					"value" => $options[$current]['show_textbox'],
					"select_array" => $slideshow_select,
					"id"=>"tonjoo-frs-is-show-textbox"
				);

	
	 tj_print_select_option($option_select);
	?>
	
	<tr valign="top">
		<th scope="row">Title Size</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][textbox_h4_size]"?>" value="<?php esc_attr_e($options[$current]["textbox_h4_size"]); ?>" />
			<label class="description" >Textbox Heading</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Description Size</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][textbox_p_size]"?>" value="<?php esc_attr_e($options[$current]["textbox_p_size"]); ?>" />
			<label class="description" >Textbox Text Size</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Slide Time</h3></td></tr>

	<tr valign="top">
		<th scope="row">Slide Time</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][fade_time]"?>" value="<?php esc_attr_e($options[$current]["fade_time"]); ?>" />
			<label class="description" >The speed image cycle (in millisecond).0 for manual slideshow</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Slide Transition Time</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][animation_time]"?>" value="<?php esc_attr_e($options[$current]["animation_time"]); ?>" />
			<label class="description" >The speed of the transisiton animation (in millisecond).</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Mouse Hover Behaviour</h3></td></tr>

	<tr valign="top">
		<th scope="row">Pause On Hover</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][pause]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["pause"];
					$p = '';
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] ) // Make default first in list
							$p = "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $p . $r;
				?>
			</select>
			<label class="description" >Select yes to pause animation on mouse hover</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Continue On Mouseout</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][start_mouseout]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["start_mouseout"];
					$p = '';
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] ) // Make default first in list
							$p = "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $p . $r;
				?>
			</select>
			<label class="description" >Select yes to continue animation ater the mouseout event. In effect when 'Pause on hover' is set yes</label>
		</td>
	</tr>
	<tr valign="top">
		<th scope="row">Delayed Start After Mouseout</th>
		<td>
			<input required class="regular-text" type="number" name="pjc_slideshow_options<?php echo "[$current][start_mouseout_after]"?>" value="<?php esc_attr_e($options[$current]["start_mouseout_after"]); ?>" />
			<label class="description" >Animation will resume after mouseout event in the given time (in ms). In effect when 'Continue on mouseout' is set yes</label>
		</td>
	</tr>

	<!-- <tr><td colspan=2><h3 class="meta-subtitle">Timer</h3></td></tr> -->

<!-- 	<tr valign="top">
		<th scope="row">Show Timer</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][show_timer]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["show_timer"];
					$p = '';
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] ) // Make default first in list
							$p = "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $p . $r;
				?>
			</select>
			<label class="description" >Display a small timer on the slideshow</label>
		</td>
	</tr> -->

	<tr><td colspan=2><h3 class="meta-subtitle">Arrow Navigation</h3></td></tr>

	<tr valign="top" id='tonjoo_show_navigation_arrow'>
		<th scope="row">Arrow Navigation</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][navigation]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["navigation"];
					$p = '';
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] ) // Make default first in list
							$p = "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $p . $r;
				?>
			</select>
			<label class="description" >If "no" is selected the navigation arrow will not visible</label>
		</td>
	</tr>

	<tr><td colspan=2><h3 class="meta-subtitle">Slide Pagination</h3></td></tr>

	<tr valign="top">
		<th scope="row">Slide Pagination</th>
		<td>
			<select name="pjc_slideshow_options<?php echo "[$current][bullet]"?>">
				<?php
				
					$navigation = array(
						'0' => array(
							'value' =>	'true',
							'label' =>  'Yes'
						),
						'1' => array(
							'value' =>	'false',
							'label' =>  'No' 
						)
					);
					
				
					$selected = $options[$current]["bullet"];
					$p = '';
					$r = '';

					foreach ( $navigation as $option ) {
						$label = $option['label'];
						if ( $selected == $option['value'] ) // Make default first in list
							$p = "<option selected='selected' value='" . esc_attr( $option['value'] ) . "'>$label</option>";
						else
							$r .= "<option value='" . esc_attr( $option['value'] ) . "'>$label</option>";
					}
					echo $p . $r;
				?>
			</select>
			<label class="description" >*Some skins pagination can't be disabled</label>
		</td>
	</tr>

</table>

<br>
<br>
<input type="submit" class="button-primary" value="<?php _e('Save Options', 'pjc_slideshow_options'); ?>" />			
</div>			
</div>			
</div>			
</div>			


<div class="postbox-container" style="float: right;margin-right: -300px;width: 280px;">
<div class="metabox-holder" style="padding-top:0px;">	
<div class="meta-box-sortables ui-sortable">
	<div id="email-signup" class="postbox">
		<h3 class="hndle"><span>Save Options</span></h3>
		<div class="inside" style="padding-top:10px;">
			Save your changes to apply the options
			<br>
			<br>
			<input type="submit" class="button-primary" value="<?php _e('Save Options', 'pjc_slideshow_options'); ?>" />			
			</form>
		</div>
	</div>
	<div class="postbox">
		<script type="text/javascript">
			jQuery(function(){
				var url = 'http://tonjoo.com/about/?frs-jsonp=promo';

				jQuery.ajax({url: url, dataType:'jsonp'}).done(function(data){
					//promo_1
					if(typeof data =='object'){
						jQuery("#promo_1 a").attr("href",data.permalink_promo_1);
						jQuery("#promo_1 img").attr("src",data.img_promo_1);

						//promo_2
						jQuery("#promo_2 a").attr("href",data.permalink_promo_2);
						jQuery("#promo_2 img").attr("src",data.img_promo_2);
					}
				});
			});
		</script>

		<!-- <h3 class="hndle"><span>This may interest you</span></h3> -->
		<div class="inside" style="margin: 23px 10px 6px 10px;">
			<div id="promo_1" style="text-align: center;padding-bottom:17px;">
				<a href="http://tonjoo.com" target="_blank">
					<img src="<?php echo plugins_url("fluid-responsive-slideshow/assets/loading-big.gif") ?>" width="100%" alt="WordPress Security - A Pocket Guide">
				</a>
			</div>
			<div id="promo_2" style="text-align: center;">
				<a href="http://tonjoo.com" target="_blank">
					<img src="<?php echo plugins_url("fluid-responsive-slideshow/assets/loading-big.gif") ?>" width="100%" alt="WordPress Security - A Pocket Guide">
				</a>
			</div>

			<!-- <p>Get the lastest Fluid Responsive Slideshow Premium Edition and make your website more beauty with 35+ awesome FRS theme! <br><br> Visit our website for more information</p>
			<a href="http://tonjoo.com" class="button" target="_blank">Visit Tonjoo</a>	 -->			
		</div>
	</div>
	<!-- <div id="prioritysupport" class="postbox">
		<h3 class="hndle"><span>Donate</span></h3>
		<div class="inside">
			<p>You can always support this free plugin by donate for our development team</p>
			<a href="http://tonjoo.com/donate" class="button" target="_blank">Donate</a>				
		</div>
	</div> -->
</div>
</div>
</div>	

</div>
			
	<?php
	break;
} ?>