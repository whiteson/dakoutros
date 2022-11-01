<?php 

$social_icons = get_field('social_icons');

var_dump($social_icons);
?>

<?php 
    foreach($social_icons as $icon){

        echo "<h3>{$icon['name']}</h3>";
        echo get_svg($icon['icon']);   
        echo '<h5>'.$icon['name'].'</h5>';   
    }     
?>

<?php var_dump( get_field('slider')); ?>