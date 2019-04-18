<?php
/**
 * Template Name: głowna
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/views/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::get_context();
$post = new TimberPost();
//$post = Timber::get_posts([ 'post_id' => '2535-2'])[0];
$typy = Timber::get_terms('typ_szkoly', true);
$typy = array_reverse($typy);
$context['oferta']=[];

foreach( $typy as $typ) {
  $args = array(
  	'post_type' => 'oferta',
  	'tax_query' => array(
  		array(
  			'taxonomy' => 'typ_szkoly',
  			'field'    => 'slug',
  			'terms'    => $typ,
  		),
  	),
  );
  $kursy = Timber::get_posts($args);
  $context['oferta'][$typ->slug] = $kursy;
}

$context['typy'] = $typy;
$context['post'] = $post;
$context['parallax'] = get_template_directory_uri()."/inc/img/parallax.jpg";
$context['oferta_img'] = get_template_directory_uri()."/inc/img/oferta-img.png";
$context['strefa_img'] = get_template_directory_uri()."/inc/img/strefa.png";

$args = array( 'post_type' => 'post', 'posts_per_page' => '6', 'no_found_rows' => 'true' );
$context['newsy'] = Timber::get_posts($args);

$args = array( 'post_type' => 'page', 'post_parent' => 87, 'orderby' => 'title', 'no_found_rows' => 'true' );
$context['strefa'] = Timber::get_posts($args);
$context['home'] = true;

Timber::render( array('page-glowna.twig', 'page-' . $post->post_name . '.twig', 'page.twig' ), $context );
