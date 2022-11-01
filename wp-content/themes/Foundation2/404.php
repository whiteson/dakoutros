<?php get_header(); ?>

<div class="row">
	<div class="small-12 large-12 columns" role="main">

		<article <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<header>
				<h1 class="entry-title">Η σελίδα που ζητήσατε δεν βρέθηκε</h1><h3> Mπορείτε να δείτε δεξιά ολες τις σελίδες μας, στην αρχή και στο τέλος της σελίδας!</h3>
			</header>
			<div class="entry-content">
				<div class="error">
					<p class="bottom"><?php _e('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'FoundationPress'); ?></p>
				</div>
				<p><?php _e('Please try the following:', 'FoundationPress'); ?></p>
				<ul>
					<li><?php _e('Check your spelling', 'FoundationPress'); ?></li>
					<li><?php printf(__('Return to the <a href="%s">Ναυτιλιακά Είδη</a>', 'FoundationPress'), home_url()); ?></li>
					<li><?php _e('Click the <a href="javascript:history.back()">Back</a> button', 'FoundationPress'); ?></li>
				</ul>
			</div>
		</article>

	</div>
	
	 
</div>
<?php get_footer(); ?>