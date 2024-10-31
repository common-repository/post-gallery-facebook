<?php
/*
Plugin Name:  Post Gallery Facebook
Plugin URI:   https://developer.wordpress.org/plugins/facebook-post-gallery/
Description:  Simple solution to create Gallery from your facebook page posts.
Version:      1.0.11
Author:       Pravin Durugkar and Suraj Wasnik
Author URI:   https://profiles.wordpress.org/pravind
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  post-gallery-facebook
Domain Path:  /languages
*/

define( 'PDFBPG_DIR',  plugin_dir_path( __FILE__ ) );
define( 'PDFBPG_URI', plugin_dir_url( __FILE__ ), true );
define( 'PDFBPGallery_BaseName', plugin_basename(__FILE__) );

class PDFBPGallery
{
    function __construct(){      
        
        add_action('init', array($this, 'pdfbpg_include_file'));
        //run activation steps
        register_activation_hook( __FILE__, array($this, 'pdfbpg_gallery_activation' ) );
        //run deactivation steps
        register_deactivation_hook( __FILE__, array($this, 'pdfbpg_gallery_deactivation' ) );

        if ( ! function_exists( 'pdfbpg_add_script_style' ) ) {
            add_action( 'wp_enqueue_scripts', array($this, 'pdfbpg_add_script_style' ), 10 );
        }

    }
    
    function pdfbpg_include_file() {
        include PDFBPG_DIR . 'includes/pdfbpg-gallery-shortcode.php';
        include PDFBPG_DIR . 'includes/pdfbpg-setting/pdfbpg-settings.php';
    }
    
    function pdfbpg_gallery_activation() {        
        // future updations
    }
    
    function pdfbpg_gallery_deactivation() {
        //future updations
    }  

    /*Ends Post Type*/      
    function pdfbpg_add_script_style() {
        /* Register & Enqueue Styles. */ 
        wp_register_style( 'pdfbpg-style', pdfbpg_URI.'assets/css/facebook-posts-gallery.css' );
        wp_enqueue_style( 'pdfbpg-style' );  

        /*  Future Updations
        wp_register_style( 'pdfbpg-style1', pdfbpg_URI. 'css/owl.carousel.min.css' );
        wp_enqueue_style( 'pdfbpg-style1' );
        wp_register_style( 'pdfbpg-style2', pdfbpg_URI.'css/owl.theme.default.min.css' );
        wp_enqueue_style( 'pdfbpg-style2' );
        */
        /* Register & Enqueue scripts. */  //Future updations
        /*      
        wp_register_script( 'my-script2', pdfbpg_URI.'js/owl.carousel.min.js', FALSE, '', TRUE );
        wp_enqueue_script( 'my-script2'); 
        wp_register_script( 'my-script3', pdfbpg_URI.'js/fg-gallery.js', FALSE, '', TRUE);
        wp_enqueue_script( 'my-script3');
        */
    }

}

// create an instance of pdfbpggallerys
if ( !isset($pdfbpg_gallery) ) {
    $pdfbpg_gallery = new PDFBPGallery();
}
