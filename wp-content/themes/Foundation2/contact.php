<?php
/*
Template Name: Contact
*/
get_header(); ?>
<?php
if ( $_POST['kodikos']=="14"){
 
 
$errormessage=""; 
$email = $_POST['email']; // Invalid email address 
$email = preg_replace('/[^A-Za-z0-9\-@.]/', '', $email);
 
$minima = $_POST['minima']; // Invalid email address 
$minima = str_replace(array(' ', '<', '>', '&', '{', '}', '*','%','/'), array('-'), $minima);


 
$tilefono = $_POST['tilefono']; 

$tilefono = str_replace(array(' ', '<', '>', '&', '{', '}', '*','%'), array('-'), $tilefono);

$onoma = $_POST['onoma'];  


$onoma = str_replace(array(' ', '<', '>', '&', '{', '}', '*','%'), array('-'), $onoma);

 // echo  $onoma.$email.$minima.$tilefono ;
  
$message = "Όνομα :".$onoma." | Ηλ. Ταχυδρομειο :".$email." | Μύνημα :".$minima."  | Τηλέφωνο: ".$tilefono." | ";
if ( wp_mail( 'info@dakoutros.gr', 'Dakoutros.gr', $message ) ) {
 
    $errormessage = "<div class='alert-box'>Λαβαμε το Μύνημα σας, Θα επικοινωνήσουμε μαζί σας, άμεσα</div>";
} else { 
     $errormessage=  " <div class='alert-box alert'> Λυπούμαστε, δεν λάβαμε το Μύνημα σας, επικοινωνήστε μαζί μας τηλεφωνικά.</div>";
} 




}
?>


<div class="row">
	<div class="small-12 large-12 columns" role="main">

	<?php /* Start loop */ ?>
	<?php while (have_posts()) : the_post(); ?>
		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<header>
				<h1 class="entry-title"><?php the_title(); ?></h1>
			</header>
			
				<?php echo $errormessage  ;   ?>

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