<aside id="sidebar" class="small-12 large-4 columns">
	<?php do_action('foundationPress_before_sidebar'); ?>
	<?php dynamic_sidebar("sidebar-widgets"); ?>
	<ul class=" side-nav panel">
	<h2><?php the_title(); ?> </h2>
	
 <?php
global $id;
$args = array(
	'authors'      => '',
	'child_of'     => $id,
	'date_format'  => get_option('date_format'),
	'depth'        => 0,
	'echo'         => 1,
	'exclude'      => '',
	'include'      => '',
	'link_after'   => '',
	'link_before'  => '',
	'post_type'    => 'page',
	'post_status'  => 'publish',
	'show_date'    => '',
	'sort_column'  => 'menu_order, post_title',
        'sort_order'   => '',
	'title_li'     => '', 
	'walker'       => ''
); 
wp_list_pages($args); ?>
</ul>
 
 <div class="panel">
 <p>Δείτε Επίσης</p>
 <a href="http://www.dakoutros.gr/συρματοσχοινα">Συρματόσχοινα</a>
 <br />
 <a href="http://www.dakoutros.gr/%CE%BC%CE%BF%CF%85%CF%81%CE%B1%CE%B2%CE%B9%CE%B5%CF%82-%CF%87%CF%81%CF%89%CE%BC%CE%B1%CF%84%CE%B1/">Μουράβιες</a>
 
 </div>
  
	<?php do_action('foundationPress_after_sidebar'); ?>
</aside>