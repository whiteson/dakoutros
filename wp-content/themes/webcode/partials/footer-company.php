<?php
$address = get_field('address', 'options');
$email = get_field('email', 'options');
$telephone = get_field('telephone', 'options');
$nospacetelephone = str_replace(' ', '', $telephone);
?>

<div class="footer-company">
  <div class="footer-company__content">
    <div class="footer-company__content__text"><?php echo $address; ?></div>
    <div class="footer-company__content__text">&nbsp;<a class="email" href="tel:<?php echo $telephone; ?>"><?php echo $telephone; ?></a> ~ <span><a class="email" href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></span></div>
  </div>
</div>