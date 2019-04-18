<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates', 'views');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts'), 999 );
		add_action( 'widgets_init', array($this, 'my_register_sidebars' ));
		add_filter('timber/post/content/show_password_form_for_protected', function($maybe_show) { return true; });
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['primary_sidebar'] = Timber::get_widgets('Primary');
		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

	function enqueue_styles_scripts() {
		wp_enqueue_style( 'foundation-css', get_template_directory_uri() . '/inc/foundation/css/foundation.css', false);
		wp_enqueue_style( 'main', get_template_directory_uri().'/inc/css/style.css' );
		wp_enqueue_style( 'audiowide', 'https://fonts.googleapis.com/css?family=Audiowide&amp;subset=latin-ext' );

		wp_enqueue_script( 'What-Input-js', get_template_directory_uri() . '/inc/foundation/js/vendor/what-input.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'foundation-js', get_template_directory_uri() . '/inc/foundation/js/vendor/foundation.min.js', array( 'jquery' ),'', true );
		wp_enqueue_script( 'site-js', get_template_directory_uri() . '/inc/js/site.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'parallax-js', get_template_directory_uri() . '/inc/js/parallax.min.js', array( 'jquery' ), '', true );
		wp_enqueue_script( 'fontawesome-js', 'https://use.fontawesome.com/4590fc1b46.js', array( 'jquery' ), '', true );

	}

	function my_register_sidebars() {

		/* Register the 'primary' sidebar. */
		register_sidebar(
			array(
				'id' => 'primary',
				'name' => __( 'Primary' ),
				'description' => __( 'A short description of the sidebar.' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		register_sidebar(
			array(
				'id' => 'aktualne',
				'name' => __( 'Aktualne' ),
				'description' => __( 'A short description of the sidebar.' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '<h3 class="widget-title">',
				'after_title' => '</h3>'
			)
		);

		/* Repeat register_sidebar() code for additional sidebars. */
	}

}

new StarterSite();
