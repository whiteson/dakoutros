<?php

$posts = (get_posts(
  array(
    'order_by' => 'menu_order',
    'order' => 'ASC',
    'numberposts' => '-1'
  )
));

?>

<div class="post-grid">
  <?php foreach ($posts as $article) : ?>
    <a href="<?php echo get_permalink($article->ID); ?>" class="post-grid__article"  data-image-animation>
      
      <div class="post-grid__article__title" data-title-animation>
        <?php echo $article->post_title; ?>
      </div>

      <div class="post-grid__article__image">
        <?php echo get_the_post_thumbnail($article->ID); ?>
      </div>

    </a>
  <?php endforeach; ?>


</div>