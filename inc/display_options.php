<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
ob_start();

global $wpdb, $wp_version,$success_msg;
  $table_name = $wpdb->prefix . "worx_faq";
  $success_msg = '';
  $error_msg = '';
  $action = '';
	 if(isset($_POST['Options_Submit']) ){
		if (  empty( $_POST ) || !check_admin_referer( 'wpw-nonce','wpwnonce'  ) || !current_user_can('edit_others_pages')) {
				wp_die( 'Failed security check' );
			}else{
			 	$action = sanitize_text_field($_POST['tab_action']);
			 	$faq_title = sanitize_text_field($_POST['faq_title']);
			 	$faq_heading = sanitize_text_field($_POST['faq_heading']);
			 	$category_title = sanitize_text_field($_POST['category_title']);
			 	$category_heading = sanitize_text_field($_POST['category_heading']);
			 	$back_to_top = sanitize_text_field($_POST['back_to_top']);
			 	$style = sanitize_text_field($_POST['style']);
			 	$ex_all = sanitize_text_field($_POST['expand_collapse_all']);
			 	$dp_ans = sanitize_text_field($_POST['display_all_answers']);
			 	$custom_css = sanitize_text_field($_POST['custom_css']);
			 	$ico_clr = sanitize_text_field($_POST['icon_color']);
			 	$ico_bg_clr = sanitize_text_field($_POST['icon_bg_color']);
			 	$que_clr = sanitize_text_field($_POST['question_color']);
			 	$que_bg_clr = sanitize_text_field($_POST['question_bg_color']);
			 	$que_hv_clr = sanitize_text_field($_POST['question_hover_color']);
			 	$que_act_clr = sanitize_text_field($_POST['question_active_color']);
			 	$que_act_bg_clr = sanitize_text_field($_POST['question_active_bg_color']);

				 		    $wpdb->update( 
			    				$wpdb->prefix . 'worx_faq', 
			    				array( 
			    					'faq_title' => $faq_title,    					
			    					'faq_heading' => $faq_heading,    					
			    					'category_title' => $category_title,    					
			    					'category_heading' => $category_heading,    					
			    					'back_to_top' => $back_to_top,    					
			    					'accordian' => $style,    					
			    					'expand_collapse_all' => $ex_all,  
			    					'display_all_answers' => $dp_ans,
			                        'custom_css' => $custom_css,
			                        'icon_color' => $ico_clr,
			                        'icon_bg_color' => $ico_bg_clr,
			    					'question_color' => $que_clr,
			    					'question_bg_color' => $que_bg_clr,
			    					'question_hover_color' => $que_hv_clr,
			    					'question_active_color' => $que_act_clr,
			    					'question_active_bg_color' => $que_act_bg_clr,
			                        'add_date' => date("Y-m-d H:i:s"),
			    				), 
			    				array('id'=> 1),
			    				array( 
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s', 
			    					'%s', 
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s',
			    					'%s'
			    				) 
			    			);

				$success_msg = '<div class="success_msg">Settings successfully saved !</div>';
				
			}	
	 }

	 $options = $wpdb->get_row( "SELECT * FROM $table_name" );
	 if(count($options)>0){
	 	 $id = $options->id;
		 $faq_title = esc_html($options->faq_title); 
		 $faq_heading = esc_html($options->faq_heading); 
		 $category_title = esc_html($options->category_title); 
		 $category_heading = esc_html($options->category_heading); 
		 $expand_collapse_all = esc_html($options->expand_collapse_all); 
		 $back_to_top = esc_html($options->back_to_top); 
		 $accordian = esc_html($options->accordian); 
		 $display_all_answers = esc_html($options->display_all_answers);
		 $custom_css = esc_html($options->custom_css);
		 $icon_color = esc_html($options->icon_color);
		 $icon_bg_color = esc_html($options->icon_bg_color);
		 $question_color = esc_html($options->question_color);
		 $question_bg_color = esc_html($options->question_bg_color);
		 $question_active_color = esc_html($options->question_active_color);
		 $question_hover_color = esc_html($options->question_hover_color);
		 $question_active_bg_color = esc_html($options->question_active_bg_color);
	 }
	$default_class = 'class="activelink"';
	$current_class= 'current';
	if($action != ''){
		$default_class = '';
		$current_class= '';
	}
?>
<div class="setting-outer">
	<?php   echo $success_msg;  ?>

	<form method="post" action="<?php echo admin_url('edit.php?post_type=worxfaq&page=worxFaq') ?>" name="opt_setting" id="opt_setting">
		<input type="hidden" name="tab_action" value="tab-1"  id="tab_value"/>
		 <?php wp_nonce_field( 'wpw-nonce','wpwnonce' ); ?>
		<div class="tab-ul">
			<ul>
				<li><a  <?php echo $default_class; ?> <?php if($action == "tab-1") {  echo 'class="activelink"'; }  ?> data-tab="tab-1" href="javascript:void();">Basic</a></li>
				<li><a data-tab="tab-2" <?php if($action == "tab-2") {  echo 'class="activelink"'; }  ?>  href="javascript:void();">Styling</a></li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="setting-table-outer <?php echo $current_class;  if($action == "tab-1") {  echo 'current'; }  ?>" id="tab-1" >
				<div class="faq-table">
					<table class="form-table table-bordered">

			   			<tr>
					         <th scope="row">FAQ Title</th>
					         <td>
					            <fieldset>
					               <legend class="screen-reader-text"><span>FAQ Title</span></legend>
					               <label title="FAQ Title"><input type="text" name="faq_title" value="<?php  echo $faq_title; ?>"></label><br>
					               <p>Should there be a control to open and close all FAQs simultaneously?</p>
					            </fieldset>
					         </td>
				      	</tr>
				      	<tr>
			         	<th scope="row">FAQ Heading</th>
			         	<td>
				            <fieldset>
				               <legend class="screen-reader-text"><span>FAQ Heading</span></legend>
				               <label title="FAQ Heading">
				                  <select name="faq_heading" >
				                     <option value="">Select Heading</option>
				                     <option value="h1" <?php if($faq_heading == 'h1') { echo 'selected="selected"';} ?>>H1</option>
				                     <option value="h2" <?php if($faq_heading == 'h2') { echo 'selected="selected"';} ?>>H2</option>
				                     <option value="h3" <?php if($faq_heading == 'h3') { echo 'selected="selected"';} ?>>H3</option>
				                  </select>
				               </label>
				               <br>
				               <p>Should there be a control to open and close all FAQs simultaneously?</p>
				            </fieldset>
			         	</td>
				      	</tr>
			   		</table>
				</div>
				<div class="faq-table">
				   	<table class="form-table table-bordered">
				      <tr>
				         <th scope="row">Category Title</th>
				         <td>
				            <fieldset>
				               <legend class="screen-reader-text"><span>Category Title</span></legend>
				               <label title="Show"><input type="radio" name="category_title" value="show" <?php echo (isset($category_title) && $category_title =='show')? 'checked' :''; ?> > <span>Show</span></label><br>
				               <label title="Hide"><input type="radio" name="category_title" value="hide" <?php echo (isset($category_title) && $category_title =='hide')? 'checked' :''; ?>> <span>Hide</span></label><br>
				               <p>Should there be a control to open and close all FAQs simultaneously?</p>
				            </fieldset>
				         </td>
				      </tr>
				      <tr>
				         <th scope="row">Category Heading</th>
				         <td>
				            <fieldset>
				               <legend class="screen-reader-text"><span>Category Heading</span></legend>
				               <label title="Category Heading">
				                  <select name="category_heading" >
				                     <option value="">Select Heading</option>
				                     <option value="h1" <?php if($category_heading == 'h1') { echo 'selected="selected"';} ?>>H1</option>
				                     <option value="h2" <?php if($category_heading == 'h2') { echo 'selected="selected"';} ?>>H2</option>
				                     <option value="h3" <?php if($category_heading == 'h3') { echo 'selected="selected"';} ?>>H3</option>
				                  </select>
				               </label>
				               <br>
				               <p>Should there be a control to open and close all FAQs simultaneously?</p>
				            </fieldset>
				         </td>
				      </tr>
				      <tr>
				         <th scope="row">Back to Top</th>
				         <td>
				            <fieldset>
				               <legend class="screen-reader-text"><span>Back to Top</span></legend>
				               <label title="Yes"><input type="radio" name="back_to_top" value="Yes" <?php echo (isset($back_to_top) && $back_to_top =='Yes')? 'checked' :''; ?> > <span>Yes</span></label><br>
				               <label title="No"><input type="radio" name="back_to_top" value="No" <?php echo (isset($back_to_top) && $back_to_top =='No')? 'checked' :''; ?>> <span>No</span></label><br>
				               <p>Should there be a control to open and close all FAQs simultaneously?</p>
				            </fieldset>
				         </td>
				      </tr>
				      <tr>
				         <th scope="row">Style</th>
				         <td>
				            <fieldset>
				               <legend class="screen-reader-text"><span>Style</span></legend>
				               <label title="Accordian"><input type="radio" name="style" value="accordian" <?php echo (isset($accordian) && $accordian =='accordian')? 'checked' :''; ?> > <span>Accordian</span></label><br>
				               <label title="Toggle"><input type="radio" name="style" value="toggle" <?php echo (isset($accordian) && $accordian =='toggle')? 'checked' :''; ?>> <span>Toggle</span></label><br>
				               <p>Should there be a control to open and close all FAQs simultaneously?</p>
				            </fieldset>
				         </td>
				      </tr>
				      <tr>
				         <th scope="row">FAQ Expand/Collapse All</th>
				         <td>
				            <fieldset>
				               <legend class="screen-reader-text"><span>FAQ Expand/Collapse All</span></legend>
				               <label title="Yes"><input type="radio" name="expand_collapse_all" value="Yes" <?php echo (isset($expand_collapse_all) && $expand_collapse_all =='Yes')? 'checked' :''; ?> > <span>Yes</span></label><br>
				               <label title="No"><input type="radio" name="expand_collapse_all" value="No" <?php echo (isset($expand_collapse_all) && $expand_collapse_all =='No')? 'checked' :''; ?>> <span>No</span></label><br>
				               <p>Should there be a control to open and close all FAQs simultaneously?</p>
				            </fieldset>
				         </td>
				      </tr>
				      <tr>
				        	<th scope="row">Display All Answers</th>
				        	<td>
					           <fieldset>
					              <legend class="screen-reader-text"><span>Display All Answers</span></legend>
					              <label title="Yes"><input type="radio" name="display_all_answers" value="Yes" <?php echo (isset($display_all_answers) && $display_all_answers =='Yes')? 'checked' :''; ?>> <span>Yes</span></label><br>
					              <label title="No"><input type="radio" name="display_all_answers" value="No" <?php echo (isset($display_all_answers) && $display_all_answers =='No')? 'checked' :''; ?>> <span>No</span></label><br>
					              <p>Should all answers be displayed when the page loads? (Careful if FAQ Accordion is on)</p>
					           </fieldset>
				        	</td>
			 			</tr>
				      </tbody>
				   	</table>
			   	</div>
			</div>
			<div class="setting-table-outer <?php  if($action == "tab-2") {  echo 'current'; }  ?>" id="tab-2">
				<div class="faq-table">
				   	<table class="form-table table-bordered">
				      <tbody>
				         <tr>
				            <th scope="row">Add Custom CSS</th>
				            <td>
				               <textarea name="custom_css"><?php echo $custom_css; ?></textarea>
				            </td>
				         </tr>
				         <tr>
				            <th scope="row">Icon Color</th>
				            <td>
				               <input type="text" name="icon_color" id="ico_color" class="color-field" value="<?php echo (isset($icon_color) && $icon_color!='')? $icon_color :'';?>">
				            </td>
				         </tr>
				         <tr>
				            <th scope="row">Icon Background Color</th>
				            <td>
				               <input type="text" name="icon_bg_color"  class="color-field" value="<?php echo (isset($icon_bg_color) && $icon_bg_color!='')? $icon_bg_color :'';?>">
				            </td>
				         </tr>
				         <tr>
				            <th scope="row">Question Color</th>
				            <td>
				               <input type="text" name="question_color" class="color-field" value="<?php echo (isset($question_color) && $question_color!='')? $question_color :'';?>">
				            </td>
				         </tr>
				         <tr>
				            <th scope="row">Question Background Color</th>
				            <td>
				               <input type="text" name="question_bg_color" class="color-field" value="<?php echo (isset($question_bg_color) && $question_bg_color!='')? $question_bg_color :'';?>">
				            </td>
				         </tr>
				         <tr>
				            <th scope="row">Question Hover Color</th>
				            <td>
				               <input type="text" name="question_hover_color" class="color-field" value="<?php echo (isset($question_hover_color) && $question_hover_color!='')? $question_hover_color :'';?>">
				            </td>
				         </tr>
				         <tr>
				            <th scope="row">Question Active Color</th>
				            <td>
				               <input type="text" name="question_active_color" class="color-field" value="<?php echo (isset($question_active_color) && $question_active_color!='')? $question_active_color :'';?>">
				            </td>
				         </tr>
				         <tr>
				            <th scope="row">Question Active BG Color</th>
				            <td>
				               <input type="text" name="question_active_bg_color" class="color-field" value="<?php echo (isset($question_active_bg_color) && $question_active_bg_color!='')? $question_active_bg_color :'';?>">
				            </td>
				         </tr>
				      </tbody>
				   	</table>
			   	</div>
		   	</div>
		   	<p class="submit"><input type="submit" name="Options_Submit" id="submit" class="button button-primary" value="Save Changes"></p>
		</div>
		<div class="clearfix"></div>
	</form>
</div>

<script type="text/javascript">
    jQuery(document).ready(function(){
		jQuery('.tab-ul a').click(function(){
			var tab_id = jQuery(this).attr('data-tab');
			jQuery('.tab-ul a').removeClass('activelink');
			jQuery('.setting-table-outer').removeClass('current');
			jQuery(this).addClass('activelink');
			jQuery("#"+tab_id).addClass('current');
			var tab_val = jQuery(".activelink").attr("data-tab");
    		jQuery("#tab_value").val(tab_val);
		});

	});
</script>


