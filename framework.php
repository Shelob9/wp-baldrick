<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}
// Define constants to be used within - ( change paths if theme )
define( 'BALDRICK_PATH'		, plugin_dir_path( __FILE__ ) );
define( 'BALDRICK_URL'		, plugin_dir_url( __FILE__ ) );
define( 'BALDRICK_VER'		, '1.0.0');


// comma separated list of screens to include the framework on
// see http://codex.wordpress.org/Class_Reference/WP_Screen for more.
define( 'BALDRICK_SCREENS'	, 'post,edit' );

// Setup Action for Enqueuing scripts & styles
add_action( 'init', 					'baldrick_register_libs' );
add_action( 'admin_enqueue_scripts', 	'baldrick_enqueue_libs' );
add_action( 'wp_enqueue_scripts', 		'baldrick_enqueue_libs' );

// Setup Action for footer script inits
add_action( 'wp_footer', 'baldrick_init_scripts' );

function baldrick_register_libs(){
	
	// register scripts
	wp_register_script( 'handlebars' 			, BALDRICK_URL . 'js/handlebars.js', 			array(), 										 BALDRICK_VER );
	wp_register_script( 'baldrick' 				, BALDRICK_URL . 'js/jquery.baldrick.js', 		array('jquery'), 								 BALDRICK_VER );
	wp_register_script( 'baldrick-handlebars' 	, BALDRICK_URL . 'js/handlebars.baldrick.js', 	array('baldrick', 'handlebars'), 				 BALDRICK_VER );
	wp_register_script( 'baldrick-modals' 		, BALDRICK_URL . 'js/modal.baldrick.js', 		array('baldrick'), 								 BALDRICK_VER );
	
	if(is_admin()){
		// register the admin init script
		wp_register_script( 'wp-baldrick-admin' 	, BALDRICK_URL . 'js/wp-baldrick.js',	 		array('baldrick-handlebars', 'baldrick-modals'), BALDRICK_VER );
	}

	// register styles
	wp_register_style( 'baldrick-modals', BALDRICK_URL . 'css/baldrick-modals.css', array(), BALDRICK_VER );
}

function baldrick_init_scripts(){
	// This part outputs the baldrick init code in the frontend footer.
	?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			
			// initialise baldrick triggers
			// selector ".wp-baldrick" can be anything you require and you can make multiple 
			// inits with different options set.
			$('.wp-baldrick').baldrick({
				request			:	'<?php echo admin_url( 'admin-ajax.php' ); ?>',
				method			:	'POST'
			});

		});
	</script>
	<?php
}

function baldrick_enqueue_libs(){
	// The wonderful thing about the dependencies is that you only need to include the wp-baldrick-admin or 
	// baldrick-handlebars & baldrick-modals for frontend.
	// The dependent scripts are automatically included
	if( is_admin() ){
		// include admin init and set
		// optionallay set which screen to have scripts onwithin admin
		/*
		$screen = get_current_screen();
		if( !in_array( $screen->base, explode( ',', BALDRICK_SCREENS ) ) ){ return; }
		*/		
		wp_enqueue_script( 'wp-baldrick-admin' );
	}else{
		wp_enqueue_script( 'baldrick-handlebars' );
		wp_enqueue_script( 'baldrick-modals' );
	}

	// Enqueue the style for the modals.
	wp_enqueue_style( 'baldrick-modals' );
}
