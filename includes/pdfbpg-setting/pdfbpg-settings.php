<?php

class pdfbpGallerySettings{

	function __construct(){	

		add_action( 'admin_init', array($this, 'pdfbpg_register_settings' ) );
		add_action('admin_menu', array($this, 'pdfbpg_register_options_page') );
		add_filter( 'plugin_action_links_'.PDFBPGallery_BaseName, array($this, 'pdfbpg_add_action_links') );
	}

	function pdfbpg_add_action_links( $links ) {
        $mylinks = array(
            '<a href="' . admin_url( 'options-general.php?page=pdfbpg-settings' ) .'&token='.time(). '">'.__( 'Settings','post-gallery-facebook' ).'</a>',
        );
        return array_merge( $mylinks, $links );
    }

	function pdfbpg_register_settings() {
		$fbpage = get_option('pdfbpg_fbpage_name');
		if(empty($fbpage)){ 
			add_option( 'pdfbpg_fbpage_name', '');
		}
		$fbtoken = get_option('pdfbpg_fb_access_token');
   		if(empty($fbtoken)) {
   			add_option( 'pdfbpg_fb_access_token', '');
   		}
   		$no_images = get_option('pdfbpg_number_of_images');
   		if(empty($no_images)) {
   			add_option( 'pdfbpg_number_of_images', '4');
   		} 
   		register_setting( 'pdfbpg_options_group', 'pdfbpg_fbpage_name', 'pdfbpg_callback' );
   		register_setting( 'pdfbpg_options_group', 'pdfbpg_fb_access_token', 'pdfbpg_callback' );
   		register_setting( 'pdfbpg_options_group', 'pdfbpg_number_of_images', 'pdfbpg_callback' );
	}
	function  pdfbpg_register_options_page() {
	    add_options_page('PDFBPG Settings', 'Facebook Posts Gallery', 'manage_options', 'pdfbpg-settings', array($this,'pdfbpg_options_page'));
	}

	function pdfbpg_options_page() { 
	?>
		<div>
			<?php screen_icon(); ?>
			<h2><?php echo _e('Facebook Posts Gallery Settings','post-gallery-facebook');?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'pdfbpg_options_group' ); ?>
				
				<p><?php echo _e('Go to the facebook page and collect page slug.','post-gallery-facebook');?></p>
				<p><code>[PDFacebookGallery]</code> <?php echo _e('shortcode to show the gallery.','post-gallery-facebook');?></p>
				<table>
					<tr valign="top">
						<th scope="row"><label for="pdfbpg_fbpage_name"><?php echo _e('Facebook Page Slug','post-gallery-facebook');?></label></th>
						<td><input type="text" id="pdfbpg_fbpage_name" name="pdfbpg_fbpage_name" value="<?php echo (get_option('pdfbpg_fbpage_name')); ?>" /></td>
					</tr>
					<tr valign="top">
						<th scope="row"><label for="pdfbpg_fb_access_token"><?php echo _e('Facebook Access Token','post-gallery-facebook');?></label></th>
						<td><input type="text" id="pdfbpg_fb_access_token" name="pdfbpg_fb_access_token" value="<?php echo (get_option('pdfbpg_fb_access_token')); ?>" /> <button id="fb-toke-test" class="button button-info">Test</button></td>
					</tr>
					<tr><td></td><td>
						<?php echo sprintf(__('To generate the token please follow %s this link %s','post-gallery-facebook') , '<a target="_blank" href="https://developers.facebook.com/docs/apps/">', '</a>' ) ?>
						<!-- To generate the token please follow <a target="_blank" href="https://developers.facebook.com/docs/apps/">this link</a> -->
						</td></tr>
					<tr valign="top">
						<th scope="row"><label for="pdfbpg_number_of_images"><?php echo _e('Number of Images','post-gallery-facebook');?></label></th>
						<td><input type="number" id="pdfbpg_number_of_images" name="pdfbpg_number_of_images" value="<?php echo (get_option('pdfbpg_number_of_images')); ?>" /></td>
					</tr>
				</table>
				<?php  submit_button(); ?>
			</form>
			<div id="test-result"></div>
		</div>
		<script type="text/javascript">
			(function($){
				$('#fb-toke-test').click(function(eve){
					eve.preventDefault(); 
					$.ajax({ 
					    type: 'GET', 
					    url: 'https://graph.facebook.com/'+$('#pdfbpg_fbpage_name').val()+'/posts', 
					    data: { access_token: $('#pdfbpg_fb_access_token').val(), limit:'100' }, 
					    dataType: 'json',
					    success: function (data, textStatus, xhr) { 
					        $('#test-result').html('<span style="background-color:green;color: white;padding: 5px 10px;">Success</span>');
					    },
					    error: function (result) { 
				               $('#test-result').html('');
				               var html = '<p><span style="background-color:red;color: white;padding: 5px 10px;">Error</span></p>';
				               html += '<table>';
				               $.each(result.responseJSON.error, function(index, element) { 
						            html += '<tr>';
						            html += '<th>'+index+'</th>';
						            html += '<td>'+element+'</td>';
						            html += '</tr>';
					           });
					        html += '<table>';
					        $('#test-result').html(html);
				            }
					});
				});
			})(jQuery);
		</script>
	<?php
	}

}

new pdfbpGallerySettings();

?>