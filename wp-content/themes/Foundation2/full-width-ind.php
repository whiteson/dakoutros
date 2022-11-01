<?php
/*
Template Name: Full Width Index
*/
get_header(); ?>



<div class="row">

<?php 
//get_search_form(); 
?>
</div>

    <div class="single-main row" style="margin-top:5px">
		
		<h6 style="display:none;background: #ffa501;
    text-align: center;
    padding: 20px;
    color: black;
    font-family: unset;
    font-weight: bolder;">
			Το κατάστημά μας θα παραμείνει κλειστό από 11 εώς 19 Αυγούστου
		</h6>

<div><img src="http://www.dakoutros.gr/wp-content/uploads/2015/04/ναυτιλιακα-ειδη-δακουτρος.jpg" alt="ναυτιλιακά είδη"/></div>
<!-- Write your comments here 
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/12/ναυτιλιακα-ειδη-15.jpg" alt="ναυτιλιακά είδη"/></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/12/ναυτιλιακα-ειδη-16.jpg" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/12/ναυτιλιακα-ειδη-17.jpg" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-1.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-2.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-3.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-4.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-5.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-6.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-7.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-8.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-9.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-10.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-11.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-13.png" alt="ναυτιλιακά είδη" /></div>
<div><img src="http://www.dakoutros.gr/wp-content/uploads/2014/11/naytiliaka-14.png" alt="ναυτιλιακά είδη" /></div>-->


   </div>
   
   

<div class="row">
	<div class="small-12 large-12 columns" role="main">

	<?php /* Start loop */ ?>
	<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<header>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			
						

			<div class="entry-content">
			

<?php 
if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
	the_post_thumbnail(array('class' => ' right'));
} 
?>
 
 

				<?php the_content(); ?>
				


			</div>
			
			<div data-alert class="alert-box secondary" id="myAlert">
<ul class="inline-list">
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
 <a href="#" class="close">&times;</a>
</div>

			<footer>
				<?php wp_link_pages(array('before' => '<nav id="page-nav"><p>' . __('Pages:', 'FoundationPress'), 'after' => '</p></nav>' )); ?>
				<p><?php the_tags(); ?></p>
			</footer>
			<?php comments_template(); ?>
		</article>
	<?php endwhile; // End the loop ?>

	</div>
</div>

<?php get_footer(); ?>