<?php

$song = get_field('song');
?>

<?php if (is_array($song)) : ?>
  
  <div class="sound">
    <div class="sound__on" id="soundon">
      <?php get_svg('soundon'); ?>
    </div>
    <div class="sound__off" id="soundoff">
      <?php get_svg('soundoff'); ?>
    </div>

    <div style="display:none!important" class="myaudio" data-src="<?php echo $song['url']; ?>"></div>
    <!-- <div style="display:none!important" class="myaudio" data-src="<?php echo get_template_directory_uri(); ?>/assets/dist/sound/sound.mp3"></div> -->

  </div>

<?php endif; ?>