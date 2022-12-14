<?php
/*
Template Name: Left Sidebar
*/
get_header(); ?>
<div class="row">

<header>
                    <h1 class="entry-title"><?php the_title(); ?></h1>
                </header>
                
    <div class="small-12 large-8 large-push-4 columns" role="main">

        <?php do_action('foundationPress_before_content'); ?>

        <?php while (have_posts()) : the_post(); ?>
        
            <article <?php post_class() ?> id="post-<?php the_ID(); ?>">
                
                <?php do_action('foundationPress_page_before_entry_content'); ?>
                <div class="entry-content">
                    <?php the_content(); ?>
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
    <?php get_sidebar('left'); ?>
</div>
<?php get_footer(); ?>