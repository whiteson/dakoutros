<aside id="sidebar" class="small-12 large-4 columns large-pull-8">
	<?php do_action('foundationPress_before_sidebar'); ?>
	<?php dynamic_sidebar("sidebar-widgets"); ?>
	<div class="panel">
	<?php $permalink = get_permalink($post->post_parent); ?><a href="<?php echo $permalink; ?>"><b><?php echo get_the_title($post->post_parent)  ; ?></b></a></div>
	<ul class="side-nav panel">
  <?php
  global $id;
 
  $args = array(
	'authors'      => '',
	'child_of'     => $post->post_parent,
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
	<?php do_action('foundationPress_after_sidebar'); ?>
</aside>