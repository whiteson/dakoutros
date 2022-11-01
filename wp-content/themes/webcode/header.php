<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>

<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div id="page" class="site">

		<header id="masthead" class="site-header">

			<a href="<?php echo get_home_url(); ?>">
				<?php //get_template_part('partials/scroll-text'); 
				?>
			</a>



			<div class="site-branding">
				logo
			</div>

			<nav id="site-navigation" class="main-navigation main-menu navbar navbar-expand-lg navbar-light bg-light">
				<!-- <div class="container-fluid"> -->
				<span class="main-menu--left">ml</span>
						<?php wp_nav_menu(array(
							'menu_class' => 'navbar-nav',
							'container_class' => 'container-fluid',
							'theme_location' => 'menu-main',
							'menu_id' => 'menu-main'
						)); ?>
						<!-- </div> -->
						<span class="main-menu--right">></span>
			</nav>

			<nav id="site-navigation" class="main-navigation">
				<div class="main-navigation__burger">
					<div class="main-navigation__burger__icon">
						<span></span>
						<span></span>
						<span></span>
						<span></span>
					</div>
				</div>
				<div class="main-navigation__label">
					Ναυτιλιακά Είδη
				</div>
				<?php wp_nav_menu(array('theme_location' => 'mega-menu', 'menu_id' => 'mega-menu')); ?>
			</nav>

		</header>

		<div id="content" class="site-content">