<?php
if($_POST['subscribemail']){
$mail = $_POST['subscribemail'];
$mail = preg_replace('/[^A-Za-z0-9\-@.]/', '', $mail);
$from = "info@dakoutros.gr";
$mail .= "\n\n Νέα εγγραφή απο dakoutros.gr";
$headers = 'From: members@dakoutros.gr <members@dakoutros.gr>' . PHP_EOL .
    'Reply-To: members@dakoutros.gr <members@dakoutros.gr>' . PHP_EOL .
    'X-Mailer: PHP/' . phpversion();
mail("members@dakoutros.gr","Νέα Εγγραφή",$mail,$headers);
}
?>
<!doctype html>
<html class="no-js" <?php language_attributes(); ?> >
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?php if ( is_category() ) {
			echo 'Category Archive for &quot;'; single_cat_title(); echo '&quot; | '; bloginfo( 'name' );
		} elseif ( is_tag() ) {
			echo 'Tag Archive for &quot;'; single_tag_title(); echo '&quot; | '; bloginfo( 'name' );
		} elseif ( is_archive() ) {
			wp_title(''); echo ' Archive | '; bloginfo( 'name' );
		} elseif ( is_search() ) {
			echo 'Search for &quot;'.esc_html($s).'&quot; | '; bloginfo( 'name' );
		} elseif ( is_home() || is_front_page() ) {
			bloginfo( 'name' ); echo ' | '; bloginfo( 'description' );
		}  elseif ( is_404() ) {
			echo 'Error 404 Not Found | '; bloginfo( 'name' );
		} elseif ( is_single() ) {
			wp_title('');
		} else {
			echo wp_title( ' | ', 'false', 'right' ); bloginfo( 'name' );
		} ?></title>
		
		 <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/css6/app.css" />               
                <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/css6/foundation.css" />  
                
                
		<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/css/animate.css" />
		<!--<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/css/foundation4.css" /> -->
                <link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() ; ?>/css/dakoutros1.css" />               
                            
		<link rel="icon" href="https://www.dakoutros.gr/wp-content/uploads/2016/09/ναυτιλιακά-είδη_D.png" type="image/x-icon">
		<?php wp_head(); ?>		
		<?php include_once("analyticstracking.php") ?>
		<meta name="google-translate-customization" content="8cd461998f4e336-dda939c713596e4e-g081f358ec4a2b14c-10"></meta>


<style>
    
 
    .multilevel-accordion-menu .is-accordion-submenu-parent a {
  background: #4d5158;
}
.multilevel-accordion-menu .is-accordion-submenu a {
  background: #35383d;
}
.multilevel-accordion-menu .sublevel-1 {
  text-indent: 1rem;
}
.multilevel-accordion-menu .sublevel-2 {
  text-indent: 2rem;
}
.multilevel-accordion-menu .sublevel-3 {
  text-indent: 3rem;
}
.multilevel-accordion-menu .sublevel-4 {
  text-indent: 4rem;
}
.multilevel-accordion-menu .sublevel-5 {
  text-indent: 5rem;
}
.multilevel-accordion-menu .sublevel-6 {
  text-indent: 6rem;
}
.multilevel-accordion-menu a {
  color: white;
  box-shadow: inset 0 -1px #41444a;
}
.multilevel-accordion-menu a::after {
  border-color: white transparent transparent;
}
.multilevel-accordion-menu .menu > li:not(.menu-text) > a {
  padding: 1.2rem 1rem;
}

.multilevel-accordion-menu .is-accordion-submenu-parent[aria-expanded="true"] a.subitem::before {
  content: "\f016";
  font-family: FontAwesome;
  margin-right: 1rem;
}

.multilevel-accordion-menu .is-accordion-submenu-parent[aria-expanded="true"] a::before {
  content: "\f07c";
  font-family: FontAwesome;
  margin-right: 1rem;
}

.multilevel-accordion-menu .is-accordion-submenu-parent[aria-expanded="false"] a::before {
  content: "\f07b";
  font-family: FontAwesome;
  margin-right: 1rem;
}



</style>
<style>

.top-bar, .top-bar ul {
    background-color:transparent!important}



.price,.single_add_to_cart_button{display:none!important;}
.label{background-color: transparent!important}
table tr td {
    padding: 0px;
}
#ajaxsearchlite1, .probox {background-color: #3156a6!important;background-image:none!important;    border: 2px solid #111f51;}

#ajaxsearchliteres ,.vertical {
    background: rgb(49, 86, 166)!improtant;
  
}

.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt {
    background-color: #111F51!important;

}
.woocommerce ul.products li.product h3 {
    font-size: 1.2em!important;
}

.woocommerce ul.products li.product, .woocommerce-page ul.products li.product {
width:45%!important;
}

h1 {
    font-size: 1.1rem;
}

ul li{list-style-type: none;}

.woocommerce ul.products li.product h3 {
  
    font-size: 0.9em;
}

.woocommerce ul.products li.product, .woocommerce-page ul.products li.product {
    padding: 10px;

}

.logotop .right{display:block!important;}

.aws_result_price{display:none!important;}

.aws-container .aws-search-field{border:2px solid #3257a6;border-bottom:2px solid red;}
.aws-container .aws-search-field{border:2px solid red!important;border-bottom:2px solid red;}



</style>
	</head>
	<body <?php body_class(); ?>>
	 


	
	
	<?php do_action('foundationPress_after_body'); ?>
	
	<div class="off-canvas-wrap" data-offcanvas>
	<div class="inner-wrap">
	
	<?php do_action('foundationPress_layout_start'); ?>
	
	
	
	<div class="logotop row animatedd bounceInLeft  ">
	 
	         <div class="right">
	            <ul class="inline-list" style="font-size:95%"> 
	      
	            <li><a target="_blank" href="https://www.dakoutros.gr/wp-content/uploads/2017/11/exportproducts.pdf" class="animated infinite flash red"><b>EXPORT CATALOG</b></a></li>
	            
 <li><a href="https://www.dakoutros.gr/catalog-low-quality.pdf" target="_blank" rel="publisher"><b>ΚΑΤΑΛΟΓΟΣ 2019</b></a></li>

 <li class="hooverwhite"><a href="https://www.tmaxwinches.com/" class="animated flash yellow "><b>ΤΜΑΧ Εργάτες</b></a></li>
<li><a href="https://www.dakoutros.gr/επικοινωνια/">Επικοινωνία</a></li>
<li><a href="https://www.facebook.com/pages/Dakoutros-Marine-Products/154378001307128" target="_blank">
    <img src="https://www.dakoutros.gr/wp-content/uploads/2014/12/facebook-dakoutros.gr_.png" width="17px" /></a></li>
</ul>

</div>

 <div id="g1111oogle_translate_element"></div> 

	         <a href="https://www.dakoutros.gr"><img  src="<?php echo get_stylesheet_directory_uri() ; ?>/assets/img/icons/dakoutros-ναυτιλιακα-ειδη-logo.min.png" class="logo animated2 bounceInDown" /></a>
	         
	</div>
	<nav class="tab-bar show-for-small-only">
		<section class="left-small">
			<a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a>
		</section>
		<section class="middle tab-bar-section">
			
			<h1 class="animated2 bounceInLeft title"><?php   bloginfo( 'name' ); ?></h1>
			
		</section>
	</nav>

	<?php get_template_part('parts/off-canvas-menu'); ?>

	<?php get_template_part('parts/top-bar'); ?>

   <ul style="display:block;margin:auto auto ; width:400px" class="inline-list"> 
	       
	</ul>

       
<section class="container animated2 bounceInDown" role="document">
<div class="row"><?php // echo do_shortcode('[wpdreams_ajaxsearchlite]'); ?>
</div>
<div class="row">
    <h5><b>ΑΝΑΖΗΤΗΣΗ ΠΡΟΪΟΝΤΩΝ</b></h5>
    <?php echo do_shortcode('[aws_search_form]'); ?></div>
	<?php do_action('foundationPress_after_header'); ?>

