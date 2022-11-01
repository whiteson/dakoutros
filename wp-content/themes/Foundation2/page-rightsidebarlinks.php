<?php
/*
Template Name: sidebar+Slider_pages
*/
get_header(); ?>

<div class="row">
	<div class="small-12 large-8 columns" role="main">

	<?php do_action('foundationPress_before_content'); ?>

	<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<header>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			<?php do_action('foundationPress_page_before_entry_content'); ?>
			<div class="entry-content">
			
			<?php 
if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
	the_post_thumbnail(array('class' => ' right'));
} 
?>
			
						
				
				<?php the_content(); ?>
				
						<?php
				global $id;
$pageChildren = get_pages('child_of='.$id.'');
if ( $pageChildren ) {
  foreach ( $pageChildren as $pageChild ) {
    echo '<h3><a href="' . get_permalink($pageChild->ID) . '">'. $pageChild->post_title.'</a></h3>';
    $post1 = get_post($pageChild->ID); 
$content1 = apply_filters('the_content', $post1->post_content); 
$content1 =strip_tags($content1);
//$content = substr($content,0,200);
$content1 = substr($content1, 0, strpos(wordwrap($content1, 400), "\n")); 
echo "<p class='panel'>";
echo $content1; 
 echo "</p>";
  }
}
?>
				




				
				<div class="single-item">
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
	'link_before'  => '',
	'link_after'   => '',
	'post_type'    => 'page',
	'post_status'  => 'publish',
	'show_date'    => '',
	'sort_column'  => 'menu_order, post_title',
        'sort_order'   => '',
	'title_li'     => '', 
	'walker'       => ''
); 
$pages = get_pages(array( 'child_of' => $post->ID)); 
  foreach ( $pages as $page ) {
  	$option = "<div><a href='" . get_page_link( $page->ID ) . "'>";
	$option .= $page->post_title;
	$option .= "</a></div>";
	echo $option;
  }
 ?>
 </div>

			</div>
			<footer>
				<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'FoundationPress'), 'after' => '</p></nav>' )); ?>
				<p><?php the_tags(); ?></p>
			</footer>
			<?php do_action('foundationPress_page_before_comments'); ?>
			<?php comments_template(); ?>
			<?php do_action('foundationPress_page_after_comments'); ?>
		</article>
	<?php endwhile;?>
 
	<?php do_action('foundationPress_after_content'); ?>

	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>