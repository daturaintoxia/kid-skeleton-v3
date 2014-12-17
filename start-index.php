<?php
/*
Template Name: Start Template
*/

get_header();
do_shortcode( '[responsive_slider]' );
do_action('skeleton_before_content');
get_template_part( 'start', 'temp' );
do_action('skeleton_after_content');
get_sidebar('page');
get_footer();
?>