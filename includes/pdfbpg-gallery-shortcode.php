<?php

class PDFBPGalleryShortcode{
    public $settings = array();

	function __construct(){	

		//add_shortcode( 'srtcode_my_testimonial', 'pdfbpg_gallery_func');
		add_shortcode( 'PDFacebookGallery', array($this, 'pdfbpg_gallery_func' ));

		//Injecting settings
		$this->settings = $this->pdfbpag_fetch_settings();

	}

	function pdfbpg_gallery_func(){ 
		$fbdata = $this->pdfbpag_fetch_data_from_facebook();
		if ($fbdata == false) {
			echo _e('Please check the settings once again.','facebook-post-gallery');
			return;
		}
	?>

		<div id="pdfbpg-gallery" class="pdfbpg-gallery-slider">
			<?php
			$i = 1;
			foreach ($fbdata->data as $fbpost ) {

				if (!empty($fbpost->full_picture)) {
					do_action("pdfbpg_before_gallery_tile", array($fbpost));
					
					echo '<div class="pdfb-tile">';
					echo '<a target="_blank" href="'.esc_url($fbpost->permalink_url).'">';
					
					do_action("pdfbpg_before_gallery_tile_image", array($fbpost));
					echo '<img src="'.esc_url($fbpost->full_picture).'" alt="FB Tile'.$i.'" />';
					do_action("pdfbpg_after_gallery_tile_image", array($fbpost));
					
					echo '</a>';
					echo '</div>';
					
					do_action("pdfbpg_after_gallery_tile", array($fbpost));
					
					if ($i++ >= $this->settings['fb_num_of_images']) {
						break;
					}
				}
				
				//$i++;
			} ?>
		</div>

	<?php } 

	/**
	 * Fetching all the attributes for future updations
	 */
	function pdfbpag_fetch_data_from_facebook(){
		if ($this->settings['fb_page_slug'] != "" && $this->settings['fb_access_token'] != "") {
			$page_id = $this->settings['fb_page_slug'];
			$access_token = $this->settings['fb_access_token'];
			
			//Get the JSON
			// Getting all the values for future updations
			$json_object = @file_get_contents('https://graph.facebook.com/' . $page_id .'/posts?access_token=' . $access_token.'&limit=100&fields=permalink_url,id,message,full_picture,created_time');
			
			//Interpret data
			$fbdata = json_decode($json_object); 
			return $fbdata;
		}else{
			return false;
		}
	}

	/**
	 * Fetching all the settings
	 */
	public function pdfbpag_fetch_settings(){

		$settings = array();
		$fbpage = get_option('pdfbpg_fbpage_name');
		$settings['fb_page_slug'] = !empty($fbpage) ? $fbpage : '';

		$fb_token = get_option('pdfbpg_fb_access_token');
		$settings['fb_access_token'] = !empty($fb_token) ? $fb_token : '';

		$no_images = get_option('pdfbpg_number_of_images');
		$settings['fb_num_of_images'] = !empty($no_images) ? $no_images : '';
		return apply_filters( "fb_gallery_settings", $settings );
	}
}

// create an instance of PDFBPGalleryShortcode
$pdfbpg_srt = new PDFBPGalleryShortcode();
