<?php
/*
Plugin Name: Phonak Project Request
*/


add_shortcode( 'phonak_marketing_request_app', 'phonak_marketing_request_app' );
function phonak_marketing_request_app() {
	return '<div id="phonak_app"></div>';
}


function phonak_marketing_request_enqueue_style() {
	//wp_enqueue_style( 'bootstrap', plugins_url().'/phonak-project-request/libraries/bootstrap-4.0.0/css/bootstrap.min.css', false ); 
}

function phonak_marketing_request_enqueue_script() {
	//wp_enqueue_script( 'tether', plugins_url().'/phonak-project-request/libraries/tether/tether.min.js', array('jquery') );
	//wp_enqueue_script( 'bootstrap', plugins_url().'/phonak-project-request/libraries/bootstrap-4.0.0/js/bootstrap.min.js', array('jquery','tether') );

	wp_enqueue_script( 'react', plugin_dir_url( __FILE__ ) . 'js/react/react.min.js' );
	wp_enqueue_script( 'react-dom', plugin_dir_url( __FILE__ ) . 'js/react/react-dom.min.js' );
	wp_enqueue_script( 'babel', 'https://npmcdn.com/babel-core@5.8.38/browser.min.js', '', null, false );

	wp_enqueue_script( 'phonak-project-request', plugin_dir_url( __FILE__ ) . 'js/app.js' );

}

add_action( 'wp_enqueue_scripts', 'phonak_marketing_request_enqueue_style',20 );
add_action( 'wp_enqueue_scripts', 'phonak_marketing_request_enqueue_script' );

// Add "babel" type to phonak-project-request
add_filter( 'script_loader_tag', 'wpshout_react_quiz_add_babel_type', 10, 3 );
function wpshout_react_quiz_add_babel_type( $tag, $handle, $src ) {
	if ( $handle !== 'phonak-project-request' ) {
		return $tag;
	}

	return '<script src="' . $src . '" type="text/babel"></script>' . "\n";
}