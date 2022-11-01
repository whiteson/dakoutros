<?php
/*
Plugin Name: PDF Product Catalog for WooCommerce
Plugin URI: http://www.brainvial.com/pdfcatalog
Description: Customizable and flexible PDF catalog generator for WooCommerce stores.
Author: Kyriakos Ktorides
Version: 1.1.5
Author URI: http://www.ktorides.com
*/

include "widget.php";

if ( ! class_exists( 'TCPDF' ) ) {
	include_once 'vendor/tecnick.com/tcpdf/tcpdf.php';
}

class PDFCatalog {

	static function  canViewCatalog() {


		$hiddenRoles = explode( ',', get_option( 'pdfcat_hiddenroles' ) );


		if ( count( $hiddenRoles ) == 0 ) {
			return true;
		} else {
			global $current_user;

			$roles = $current_user->roles;

			if ( ( count( $roles ) == 0 ) && ( array_search( 'PDF_SIGNED_OUT', $hiddenRoles ) !== false ) ) {
				return false;
			}

			foreach ( $roles as $role ) {
				if ( array_search( $role, $hiddenRoles ) !== false ) {
					return false;
				}
			}
		}

		return true;

	}

	static function  enqueue_buttons_css() {
		wp_register_style( 'pdf-buttons', plugins_url( 'pdfcatalog/buttons_user.css' ) );
	}

	static function enqueue_buttons_css2() {
		wp_enqueue_style( 'pdf-buttons' );
	}

	static function widget_init() {
		register_widget( 'PDFWidget' );
	}

	static function add_options_page_imp() {

		$tabs = array( 'template' => 0, 'colors' => 0, 'headfoot' => 0, 'cache' => 0, 'categories' => 0 );

		if ( isset( $_GET['tab'] ) ) {
			$tab = $_GET['tab'];
		} else {
			$tab = 'template';
		}

		if ( ! isset( $tabs[ $tab ] ) ) { // validate before appending to filename
			$tab = 'template';
		}

		include dirname( __FILE__ ) . '/adminpages/options_' . $tab . '.php';
	}

	static function admin_menu() {
		add_options_page( 'PDF Catalog Generator Settings', 'PDF Catalog', 'manage_options', 'pdfgensettings', array(
			'PDFCatalog',
			'add_options_page_imp'
		) );
	}

	public function init() {
		include_once 'Renderer.php';
		include_once 'PDFCatalogGenerator.php';
		$plugin = plugin_basename( __FILE__ );
		$imageSize = get_option( 'pdfcat_imagesize' );

		if ( $imageSize != 'default' ) {
			add_image_size( 'pdf_catalog', PDFCatalogGenerator::$imageSizes[ $imageSize ][1], PDFCatalogGenerator::$imageSizes[ $imageSize ][2], true );
		}
		// SET JPEG QUALITY FOR TCPDF TOO!!!

		add_action( 'wp_enqueue_scripts', array( 'PDFCatalog', 'enqueue_buttons_css' ) );

		add_action( 'widgets_init', array( 'PDFCatalog', 'widget_init' ) );

		add_action( 'wp_enqueue_scripts', array( 'PDFCatalog', 'enqueue_buttons_css2' ) );

		add_shortcode( 'pdfcatalog', array( 'PDFCatalog', 'shortCode' ) );

		add_action( 'admin_menu', array( 'PDFCatalog', 'admin_menu' ) );

		add_action( 'admin_enqueue_scripts', array( "PDFCatalog", 'load_admin_style' ) );

		$this->setupOptionPages();


	}


	function getLogoURL() {
		$src = wp_get_attachment_image_src( get_option( 'pdfcat_logo' ) );
		if ( count( $src ) > 1 ) {
			$url = $src[0];
		} else {
			$url = '';
		}

		return $url;

	}

	function getLogoFilePath() {


		return get_attached_file( get_option( 'pdfcat_logo' ) );
	}


	static function load_admin_style() {
		wp_enqueue_media();
	}



	static function getCurrentCategoryID() {
		global $wp_query;

		$cat = $wp_query->get_queried_object();

		$class = get_class( $cat );


		if ( 'WP_Post' == $class ) {
			$post = $cat;
			if ( $post->post_type == 'product' ) {

				$cats = get_the_terms( $post->ID, 'product_cat' );
				if ( count( $cats ) > 0 ) {
					return end( $cats )->term_id;
				}
			}



		}
		return null;
	}

	static function shortCode( $a, $title = null ) {
		if ( ! PDFCatalog::canViewCatalog() ) {
			return;
		}
		$a = shortcode_atts( array( 'slug'     => null,
		                            'catids'   => null,
		                            'catid'    => 'all',
		                            'class'    => '',
		                            'children' => false
		), $a );

		$c = new stdClass();
		$c->name = '';

		if ( $a['catids'] != null ) {
			$catIDs = PDFCatalogGenerator::sanitizeCatIDs( explode( ',', $a['catids'] ) );
			$categoryID = 'multiple';
		}
		if ( $a['slug'] != null ) {
			// check if slug exists
			$c = get_term_by( 'slug', $a['slug'], 'product_cat' );
			if ( $c !== false ) {
				$categoryID = $c->term_id;
			}
		} else if ( $a['catid'] != 'all' ) {

			if ( $a['children'] ) {

				$catIDs = get_term_children( (int) $a['catid'], 'product_cat' );
				$catIDs = array_merge( array( (int) $a['catid'] ), $catIDs );
				$categoryID = 'multiple';
				$c = get_term_by( 'id', $a['catid'], 'product_cat' );

			} else {
				
				if ( $a['catid'] == 'current' ) {
					$categoryID = static::getCurrentCategoryID();
					$c = get_term_by( 'id', $categoryID, 'product_cat' );
					if ( $categoryID == null ) {
						echo 'Cannot autodetect current category';
						exit;
					}
				} else {
					$c = get_term_by( 'id', $a['catid'], 'product_cat' );
					$categoryID = $a['catid'];
				}
			}
		} else {
			$c->name = 'Full Catalog';
			$categoryID = 'all';
		}

		ob_start();
		switch ( $categoryID ) {
			case 'all':
				$url = plugins_url( 'pdfcatalog', 'pdfcatalog' ) . '/getcatalog.php?all';
				break;
			case 'multiple':
				$url = plugins_url( 'pdfcatalog', 'pdfcatalog' ) . '/getcatalog.php?cm=' . implode( '-', $catIDs );
				break;
			default:
				$url = plugins_url( 'pdfcatalog', 'pdfcatalog' ) . '/getcatalog.php?c=' . $categoryID;
				break;
		}

		?>
		<span class="PDFCatalogButtons<?php echo ' ' . $a['class']; ?>">
			<a href="<?php echo $url; ?>">
				<?php echo ( $title == null ) ? 'Download PDF Catalog' : str_replace( '%name%', $c->name, $title ); ?>
			</a>
		</span>

		<?php
		return ob_get_clean();
	}

	static function categorysettings_section() {
		echo 'Check which product categories you would like to be included in the full store PDF product catalog.';
		echo 'By default all categories are included.';
	}

	static function visibilitysettings_section() {
		echo 'The following settings affect which products are included in PDF catalogs and who can download / view them.';
	}

	static function cachesettings_section() {
		echo 'The following settings affect the performance of PDF Catalog plugin. Unless you know what you are doing better not change anything. ';
		echo 'Generating PDF files is a CPU intensive task therefore we cache the generated PDF files ';
		echo 'in order to save CPU on your web host and send PDFs to your users instantly. ';
		echo 'If you are developing your own theme, you may want to disable caching during development time and ';
		echo 're-enable it once you are done.';
	}

	static function headersettings_section() {
		{
			echo 'Customize your Catalog\'s header. The following variables can be used in your text:';
			echo "<ul><li>#store# - The site title.</li>
<li>#dategenerated# - The formatted date when the catalog was generated.</li>
    <li>
        #timegenerated# - The formatted time when the catalog was generated.
    </li>
</ul>";
		}
	}

	static function theme_section() {
		echo 'Choose which template you would like to use for PDF Catalogs.';
	}

	static function visibility_section() {
		echo 'Use this section to hide/show different elements of the generated product catalog. Not all elements are supported by every template.';
	}

	static function colors_section() {
		echo '<div style="max-width: 60%">You can use the following settings to change the PDF catalog colors. Keep in mind that some ';
		echo 'of your store\'s users might want to print your catalog, therefore you should ensure there is';
		echo 'enough contrast between background and text.</div>';
	}

	static function images_section() {
		echo '<div style="max-width: 60%">Use the following settings to tweak the quality and size of product images in the PDF output. ';
		echo 'Keep in mind that better quality means larger PDF files, more memory needed to render, and more processing time. ';
		echo 'If your store is running on shared hosting with limited resources you might want to avoid increasing the values of these settings.';
		echo '</div>';
	}

	static function paging_section() {

	}

	static function admin_init() {
		{

			if ( function_exists( 'icl_register_string' ) ) {
				icl_register_string( 'PDF Catalog for WooCommerce', 'Header Title', get_option( 'pdfcat_headTitle' ) );
				icl_register_string( 'PDF Catalog for WooCommerce', 'Sub Heading', get_option( 'pdfcat_headSubtitle' ) );
				icl_register_string( 'PDF Catalog for WooCommerce', 'Bottom Text', get_option( 'pdfcat_footerText' ) );
				icl_register_string( 'PDF Catalog for WooCommerce', 'total products', 'total products.' );
			}


			// ---- Categories --------------------------
			add_settings_section(
				'categorysettings_section', // ID
				'Category Settings', // Title
				array( 'PDFCatalog', 'categorysettings_section' )
				, // Callback
				'pdfcategorysettings' // Page
			);

			add_settings_field(
				'pdfcat_categories', // ID
				'Included Categories', // Title
				array( "PDFCatalog", 'field_categories' ),
				'pdfcategorysettings', // Page
				'categorysettings_section' // Section
			);


			add_settings_section(
				'visibilitysettings_section', // ID
				'Visibility Settings', // Title
				array( 'PDFCatalog', 'visibilitysettings_section' )
				, // Callback
				'pdfcategorysettings' // Page
			);


			add_settings_field(
				'pdfcat_showhidden', // ID
				'Product Visibility', // Title
				array( "PDFCatalog", 'fld_showhidden' ),
				'pdfcategorysettings', // Page
				'visibilitysettings_section' // Section
			);

			add_settings_field(
				'pdfcat_hideoutofstock', // ID
				'Hide Out-of-stock Products', // Title
				array( "PDFCatalog", 'fld_hideoutofstock' ),
				'pdfcategorysettings', // Page
				'visibilitysettings_section' // Section
			);


			add_settings_field(
				'pdfcat_hiddenroles', // ID
				'Hide from roles', // Title
				array( "PDFCatalog", 'fld_hiddenroles' ),
				'pdfcategorysettings', // Page
				'visibilitysettings_section' // Section
			);

			register_setting( 'pdfgen-page5', 'pdfcat_showhidden', array( "PDFCatalog", 'invalidate_Cache' ) );
			register_setting( 'pdfgen-page5', 'pdfcat_hideoutofstock', array( "PDFCatalog", 'invalidate_Cache' ) );
			register_setting( 'pdfgen-page5', 'pdfcat_categories', array( "PDFCatalog", 'invalidate_Cache' ) );
			register_setting( 'pdfgen-page5', 'pdfcat_hiddenroles' );


			// ---- Cache -------------------------------
			add_settings_section(
				'cachesettings_section', // ID
				'Cache Settings', // Title
				array( 'PDFCatalog', 'cachesettings_section' ), // Callback
				'pdfcachesettings' // Page
			);

			add_settings_field(
				'pdfcat_cache', // ID
				'Use Cache', // Title
				array( "PDFCatalog", 'field_cache_checkbox' ),
				'pdfcachesettings', // Page
				'cachesettings_section' // Section
			);

			register_setting( 'pdfgen-page4', 'pdfcat_cache', array( "PDFCatalog", 'invalidate_Cache' ) );


			add_settings_field(
				'pdfcat_downloadfile', // ID
				'Force file download', // Title
				array( "PDFCatalog", 'field_download_checkbox' ),
				'pdfcachesettings', // Page
				'cachesettings_section' // Section
			);

			register_setting( 'pdfgen-page4', 'pdfcat_downloadfile' );


			// ---- Header/Footer -----------------------
			add_settings_section(
				'headersettings_section', // ID
				'Header Settings', // Title
				array( 'PDFCatalog', 'headersettings_section' ), // Callback
				'pdfheadsettings' // Page
			);

			register_setting( 'pdfgen-page3', 'pdfcat_header', array( "PDFCatalog", 'invalidate_Cache' ) );


			add_settings_field(
				'pdfcat_logo', // ID
				'Logo', // Title
				array( "PDFCatalog", 'field_logo' ),
				'pdfheadsettings', // Page
				'headersettings_section' // Section
			);

			register_setting( 'pdfgen-page3', 'pdfcat_logo' );

			add_settings_field(
				'pdfcat_headTitle', // ID
				'Title', // Title
				array( "PDFCatalog", 'field_headtitle_text' ),
				'pdfheadsettings', // Page
				'headersettings_section' // Section
			);

			register_setting( 'pdfgen-page3', 'pdfcat_headTitle' );


			add_settings_field(
				'pdfcat_headSubtitle', // ID
				'Sub heading', // Title
				array( "PDFCatalog", 'field_headsubtitle_text' ),
				'pdfheadsettings', // Page
				'headersettings_section' // Section
			);

			register_setting( 'pdfgen-page3', 'pdfcat_headSubtitle' );


			add_settings_field(
				'pdfcat_footerText', // ID
				'Bottom Text', // Title
				array( "PDFCatalog", 'field_footer_text' ),
				'pdfheadsettings', // Page
				'headersettings_section' // Section
			);

			register_setting( 'pdfgen-page3', 'pdfcat_footerText' );


			// ---- Template ----------------------------

			add_settings_section(
				'theme_section', // ID
				'PDF Catalog Template', // Title
				array( 'PDFCatalog', 'theme_section' ), // Callback
				'pdfgensettings' // Page
			);


			register_setting( 'pdfgen-page1', 'pdfcat_template', array( "PDFCatalog", 'invalidate_Cache' ) );

			add_settings_section(
				'visibility_section', // ID
				'Included Catalog Elements', // Title
				array( 'PDFCatalog', 'visibility_section' ), // Callback
				'pdfgensettings' // Page
			);

			add_settings_section(
				'paging_section', // ID
				'Paging Settings', // Title
				array( 'PDFCatalog', 'paging_section' ), // Callback
				'pdfgensettings' // Page
			);


			add_settings_field(
				'pdfcat_template', // ID
				'Template', // Title
				array( "PDFCatalog", 'field_template_select' ),
				'pdfgensettings', // Page
				'theme_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_showSKU' );
			/*
						add_settings_field(
							'pdfcat_orderby', // ID
							'Sort products by', // Title
							array($this, 'field_orderby_select'),
							'pdfgensettings', // Page
							'theme_section' // Section
						);

						register_setting('pdfgen-page1', 'pdfcat_orderby');

						add_settings_field(
							'pdfcat_order', // ID
							'Sort direction', // Title
							array($this, 'field_order_select'),
							'pdfgensettings', // Page
							'theme_section' // Section
						);

						register_setting('pdfgen-page1', 'pdfcat_order');
			*/

			add_settings_field(
				'pdfcat_characterLimit',
				'Description Character Limit',
				array( "PDFCatalog", 'field_characterlimit' ),
				'pdfgensettings',
				'paging_section'
			);

			register_setting( 'pdfgen-page1', 'pdfcat_characterLimit' );

			// ---- ADDTO2 -------------------------
			add_settings_field(
				'pdfcat_useShortDescription',
				'Use Short Product Description',
				array( "PDFCatalog", 'field_useShortDescription' ),
				'pdfgensettings',
				'paging_section'
			);

			register_setting( 'pdfgen-page1', 'pdfcat_useShortDescription' );
			// -------------------------------------

			add_settings_field(
				'pdfcat_startOnNewPage',
				'Start Category on New Page',
				array( "PDFCatalog", 'field_startonnewpage_checkbox' ),
				'pdfgensettings',
				'paging_section'
			);

			register_setting( 'pdfgen-page1', 'pdfcat_startOnNewPage' );

			add_settings_field(
				'pdfcat_showSKU', // ID
				'Show Product SKU', // Title
				array( "PDFCatalog", 'field_sku_checkbox' ),
				'pdfgensettings', // Page
				'visibility_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_showSKU' );


			add_settings_field(
				'pdfcat_showPrice', // ID
				'Show Prices', // Title
				array( "PDFCatalog", 'field_price_checkbox' ),
				'pdfgensettings', // Page
				'visibility_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_showDescription' );

			add_settings_field(
				'pdfcat_showDescription', // ID
				'Show Description', // Title
				array( "PDFCatalog", 'field_description_checkbox' ),
				'pdfgensettings', // Page
				'visibility_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_renderShortcodes' );

			add_settings_field(
				'pdfcat_renderShortcodes', // ID
				'Render Shortcodes', // Title
				array( "PDFCatalog", 'field_render_shortcodes' ),
				'pdfgensettings', // Page
				'visibility_section' // Section
			);


			register_setting( 'pdfgen-page1', 'pdfcat_showPrice' );


			add_settings_field(
				'pdfcat_showVariations', // ID
				'Show Variations', // Title
				array( "PDFCatalog", 'field_variations_checkbox' ),
				'pdfgensettings', // Page
				'visibility_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_showVariations' );


			add_settings_field(
				'pdfcat_showCategoryTitle', // ID
				'Show Category Title', // Title
				array( "PDFCatalog", 'field_categorytitle_checkbox' ),
				'pdfgensettings', // Page
				'visibility_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_showCategoryTitle' );


			add_settings_field(
				'pdfcat_showCategoryProductCount', // ID
				'Show Product Counts', // Title
				array( "PDFCatalog", 'field_productcount_checkbox' ),
				'pdfgensettings', // Page
				'visibility_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_showCategoryProductCount' );


			add_settings_field(
				'pdfcat_headLines', // ID
				'Header/Footer Lines', // Title
				array( "PDFCatalog", 'field_headlines_checkbox' ),
				'pdfgensettings', // Page
				'visibility_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_headLines' );


			add_settings_field(
				'pdfcat_html', // ID
				'Render HTML', // Title
				array( "PDFCatalog", 'field_renderhtml_checkbox' ),
				'pdfgensettings', // Page
				'theme_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_html' );

			add_settings_field(
				'pdfcat_subsetting', // ID
				'Font Subsetting', // Title
				array( "PDFCatalog", 'field_subsetting_checkbox' ),
				'pdfgensettings', // Page
				'theme_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_unicode' );

			add_settings_field(
				'pdfcat_unicode', // ID
				'Unicode Support', // Title
				array( "PDFCatalog", 'field_unicode_checkbox' ),
				'pdfgensettings', // Page
				'theme_section' // Section
			);

			register_setting( 'pdfgen-page1', 'pdfcat_unicode' );


			// --- COLORS ----------------------------------------------------------

			add_settings_section(
				'colors_section', // ID
				'Colors', // Title
				array( 'PDFCatalog', 'colors_section' ), // Callback
				'pdfcolsettings' // Page
			);


			add_settings_section(
				'images_section', // ID
				'Image Settings', // Title
				array( 'PDFCatalog', 'images_section' ), // Callback
				'pdfcolsettings' // Page
			);


			add_settings_field(
				'pdfcat_imagesize', // ID
				'Image Resolution', // Title
				array( "PDFCatalog", 'field_image_resolution' ),
				'pdfcolsettings', // Page
				'images_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_imagesize', array( "PDFCatalog", 'invalidate_Cache' ) );


			add_settings_field(
				'pdfcat_jpegquality', // ID
				'JPEG Quality', // Title
				array( "PDFCatalog", 'field_jpeg_quality' ),
				'pdfcolsettings', // Page
				'images_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_jpegquality', array( "PDFCatalog", 'invalidate_Cache' ) );

			add_settings_field(
				'pdfcat_paperColor', // ID
				'Paper (Background)', // Title
				array( "PDFCatalog", 'field_paper_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_paperColor', array( "PDFCatalog", 'invalidate_Cache' ) );

			add_settings_field(
				'pdfcat_headerColor', // ID
				'Header Background', // Title
				array( "PDFCatalog", 'field_header_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headerColor' );


			add_settings_field(
				'pdfcat_mainText', // ID
				'Product Title', // Title
				array( "PDFCatalog", 'field_text_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_mainText' );

			add_settings_field(
				'pdfcat_lightText', // ID
				'Product Description', // Title
				array( "PDFCatalog", 'field_light_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_lightText' );

			add_settings_field(
				'pdfcat_priceColor', // ID
				'Prices', // Title
				array( "PDFCatalog", 'field_price_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_priceColor' );

			add_settings_field(
				'pdfcat_categoryColor', // ID
				'Category Title', // Title
				array( "PDFCatalog", 'field_category_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_categoryColor' );

			add_settings_field(
				'pdfcat_headerTitle', // ID
				'Header Title Color', // Title
				array( "PDFCatalog", 'field_header_title_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headerTitle' );

			add_settings_field(
				'pdfcat_headerSubtitle', // ID
				'Header Subtitle Color', // Title
				array( "PDFCatalog", 'field_header_subtitle_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headerSubTitle' );

			add_settings_field(
				'pdfcat_headerSubtitle', // ID
				'Header Subtitle Color', // Title
				array( "PDFCatalog", 'field_header_subtitle_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headerSubTitle' );

			add_settings_field(
				'pdfcat_headLinesColor', // ID
				'Header/Footer Line Color', // Title
				array( "PDFCatalog", 'field_header_lines_color' ),
				'pdfcolsettings', // Page
				'colors_section' // Section
			);

			register_setting( 'pdfgen-page2', 'pdfcat_headLinesColor' );

			// ---- ADDTO2 -----------------------------------
			add_settings_field(
				'pdfcat_buttonCSS',
				'Download Button Style',
				array( "PDFCatalog", 'field_buttonCSS' ),
				'pdfcolsettings',
				'colors_section'
			);

			register_setting( 'pdfgen-page2', 'pdfcat_buttonCSS', array( "PDFCatalog", 'createCSSFile' ) );

			// ----------------------------------------
		}
	}

	function setupOptionPages() {

		// Additional
		add_option( 'pdfcat_useShortDescription', 0 );
		add_option( 'pdfcat_buttonCSS', '' );

		// Template Options
		add_option( 'pdfcat_showSKU', 0 );
		add_option( 'pdfcat_showPrice', 1 );
		add_option( 'pdfcat_showVariations', 0 );
		add_option( 'pdfcat_showDescription', 1 );
		add_option( 'pdfcat_renderShortcodes', 0 );
		add_option( 'pdfcat_showCategoryTitle', 1 );
		add_option( 'pdfcat_showCategoryProductCount', 1 );
		add_option( 'pdfcat_template', 'thumbnaillist' );
		add_option( 'pdfcat_html', 0 );
		add_option( 'pdfcat_unicode', 0 );
		add_option( 'pdfcat_subsetting', 0 );
		add_option( 'pdfcat_headLines', 1 );
		add_option( 'pdfcat_order', 'desc' );
		add_option( 'pdfcat_orderby', 'date' );
		add_option( 'pdfcat_startOnNewPage', 0 );
		add_option( 'pdfcat_characterLimit', 0 );

		// Color Options
		add_option( 'pdfcat_paperColor', '#ffffff' );
		add_option( 'pdfcat_headerColor', '#ffffff' );
		add_option( 'pdfcat_mainText', '#333333' );
		add_option( 'pdfcat_lightText', '#555555' );
		add_option( 'pdfcat_priceColor', '#555555' );
		add_option( 'pdfcat_categoryColor', '#333333' );
		add_option( 'pdfcat_headerTitle', '#333333' );
		add_option( 'pdfcat_headerSubTitle', '#777777' );
		add_option( 'pdfcat_headLinesColor', '#777777' );
		add_option( 'pdfcat_imagesize', 'default' );
		add_option( 'pdfcat_jpegquality', '75' );

		// Header+Footer Options
		add_option( 'pdfcat_headTitle', '#store# catalog' );
		add_option( 'pdfcat_headSubtitle', 'This catalog was generated on #dategenerated#' );
		add_option( 'pdfcat_logo', '' );
		add_option( 'pdfcat_footerText', '' );

		// Cache Options
		add_option( 'pdfcat_cache', 1 );
		add_option( 'pdfcat_downloadfile', 0 );

		// Category Options
		add_option( 'pdfcat_categories', '' );
		add_option( 'pdfcat_showhidden', 1 );
		add_option( 'pdfcat_hideoutofstock', 0 );
		add_option( 'pdfcat_hiddenroles', '' );


		//pdfheadsettings

		add_action( 'admin_init', array( 'PDFCatalog', 'admin_init' ) );


	}


	static function createCSSFile( $inp ) {
		file_put_contents( dirname( __FILE__ ) . '/buttons_user.css', $inp );
		return $inp;
	}

	static function invalidate_Cache( $inp ) {
		$cachePath = dirname( __FILE__ ) . '/cache/categories/';

		$files = glob( $cachePath . '/*.pdf' );

		if ( is_array( $files ) ) {
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					unlink( $file );
				}
			}
		}

		return $inp;

	}

	static function field_logo() {
		$imgid = get_option( 'pdfcat_logo' );
		$pdf = new PDFCatalog();
		$url = $pdf->getLogoURL();
		?>
		<input type="hidden" name="pdfcat_logo" id="pdfcat_logo" value="<?php echo get_option( 'pdfcat_logo' ); ?>"/>
		<img src="<?php echo $url; ?>"
		     style="max-width: 150px;max-height:150px; min-width: 80px; min-height: 80px; border:1px solid black; background: <?php echo get_option( 'pdfcat_headerColor' ); ?>"
		     id="pdfcat_logo_preview">
		<input class="button" id="fld_pdflogo_button" type="button" value="Choose Image"/>
	<?php
	}


	static function field_footer_text() {
		?>
		<p>
			The following text will appear at the end of each PDF file. Enter here copyright information, contact
			details etc.
		</p>
		<textarea id="headSubtitle" name="pdfcat_footerText"
		          style="width: 400px"><?php echo get_option( 'pdfcat_footerText' ); ?></textarea>

	<?php
	}

	static
	function field_headsubtitle_text() {
		?>
		<input type="text" id="headSubtitle" name="pdfcat_headSubtitle"
		       value="<?php echo get_option( 'pdfcat_headSubtitle' ); ?>" style="width: 400px">

	<?php
	}


	static function field_headtitle_text() {
		?>
		<input type="text" id="pdfcat_headTitle" name="pdfcat_headTitle"
		       value="<?php echo get_option( 'pdfcat_headTitle' ); ?>" style="width: 400px">
		<label for="pdfcat_headTitle">The main header title.</label>
	<?php
	}


	static function field_header_title_color() {
		?>
		<input type="color" id="pdfcat_headerTitle" name="pdfcat_headerTitle"
		       value="<?php echo get_option( 'pdfcat_headerTitle' ); ?>">
		<label for="pdfcat_headerTitle">used in header title.</label>
	<?php
	}


	static function field_category_color() {
		?>
		<input type="color" id="pdfcat_categoryColor" name="pdfcat_categoryColor"
		       value="<?php echo get_option( 'pdfcat_categoryColor' ); ?>">
		<label for="pdfcat_categoryColor">used in category titles.</label>
	<?php
	}


	static function field_header_lines_color() {
		?>
		<input type="color" id="pdfcat_headLinesColor" name="pdfcat_headLinesColor"
		       value="<?php echo get_option( 'pdfcat_headLinesColor' ); ?>">
		<label for="pdfcat_headLinesColor">used header & footer separator lines.</label>
	<?php
	}


	static function field_header_subtitle_color() {
		?>
		<input type="color" id="pdfcat_headerSubTitle" name="pdfcat_headerSubTitle"
		       value="<?php echo get_option( 'pdfcat_headerSubTitle' ); ?>">
		<label for="pdfcat_headerSubTitle">used header subtitle and page numbers.</label>
	<?php
	}

	static function field_price_color() {
		?>
		<input type="color" id="pdfcat_priceColor" name="pdfcat_priceColor"
		       value="<?php echo get_option( 'pdfcat_priceColor' ); ?>">
		<label for="pdfcat_priceColor">used in prices</label>
	<?php
	}

	static function field_light_color() {
		?>
		<input type="color" id="pdfcat_lightText" name="pdfcat_lightText"
		       value="<?php echo get_option( 'pdfcat_lightText' ); ?>">
		<label for="pdfcat_lightText">used in product descriptions</label>
	<?php
	}

	static function field_header_color() {
		?>
		<input type="color" id="pdfcat_headerColor" name="pdfcat_headerColor"
		       value="<?php echo get_option( 'pdfcat_headerColor' ); ?>">
		<label for="pdfcat_headerColor">header background color. Usually the same a paper color.</label>
	<?php
	}

	static function field_image_resolution() {
		//pdfcat_imagesize
		?>
		<select id="pdfcat_imagesize" name="pdfcat_imagesize">
			<?php
			foreach ( PDFCatalogGenerator::$imageSizes as $key => $size ) {
				?>
				<option value="<?php echo $key; ?>"<?php if ( $key == get_option( 'pdfcat_imagesize' ) ) {
					echo ' selected';
				} ?>>
					<?php echo $size[0]; ?>
				</option>
			<?php } ?>
		</select>

		<p style="max-width: 800px">
			By default PDF catalogs are using the same image resolution used by your current theme for product images.
			This sometimes can be too low for PDF (especially if you want to pint out the catalog). You can use this
			option to set it to a higher resolution. Keep in mind that, the higher the resolution the slower the
			process, the larger the PDF output files will be, and the more memory it will require to generate them, so
			be careful with this option.<br>
		</p>
		<p style="max-width: 800px">
			WordPress does not automatically resize old images, it only resizes them when they are firest uploaded, so
			if you change this option you should also install an image rebuilding plugin. There are several free plugins
			that do this job out there but we recommend <a href="https://wordpress.org/plugins/ajax-thumbnail-rebuild/">AJAX
				Thumbnail Rebuild</a> since we tested it and works well.
		</p>


	<?php
	}

	static function field_jpeg_quality() {
		?>
		<input type="range" id="pdfcat_jpegquality" name="pdfcat_jpegquality"
		       style="vertical-align: middle; margin-right: 1em" min="30" max="100" step="1"
		       value="<?php echo get_option( 'pdfcat_jpegquality' ); ?>">
		<span id="view_jpegquality"><?php echo get_option( 'pdfcat_jpegquality' ); ?>%</span>

		<p style="max-width: 800px">
			This option affects the way JPEG images are compressed. The lower the value the less emphasis is given on
			the image quality.
			Higher values means better quality but larger file size.
		</p>
		<script>
			jQuery(function () {
				var $ = jQuery;
				var el = $('#view_jpegquality');
				var inp = $('#pdfcat_jpegquality');
				inp.change(function (e) {
					el.text(inp.val() + '%');
				});
			});
		</script>
	<?php
	}

	static function field_buttonCSS() {
		?>
		<textarea name="pdfcat_buttonCSS" id="pdfcat_buttonCSS" style="width: 50%"
		          rows="10"><?php $css = get_option( 'pdfcat_buttonCSS' );
			if ( strlen( trim( $css ) ) == 0 ) {
				include dirname( __FILE__ ) . DIRECTORY_SEPARATOR . 'buttons.css';
			} else {
				echo $css;
			}
			?></textarea>

		<p style="max-width: 800px">
			Use the above field to customize the look and feel of the catalog buttons using CSS.
		</p>
	<?php
	}

	static function field_paper_color() {
		?>
		<input type="color" id="paper_color" name="pdfcat_paperColor"
		       value="<?php echo get_option( 'pdfcat_paperColor' ); ?>">
		<label for="paper_color">catalog background color</label>
	<?php
	}


	static function field_text_color() {
		?>
		<input type="color" id="mainTextColor" name="pdfcat_mainText"
		       value="<?php echo get_option( 'pdfcat_mainText' ); ?>">
		<label for="mainTextColor">used in titles and headings</label>
	<?php
	}


	static function field_order_select() {
		?>
		<select name="pdfcat_order">
			<option value="desc">New to Old Products</option>
			<option value="asc">Old to New Products</option>
		</select>
	<?php
	}

	static function field_orderby_select() {
		$orderby = get_option( 'pdfcat_orderby' );
		?>
		<select name="pdfcat_orderby">
			<option value="date">Date product was created</option>
			<option value="price">Price</option>
		</select>
	<?php
	}

	static function field_template_select() {
		$templates = PDFCatalogGenerator::getTemplates();
		$current = get_option( 'pdfcat_template' );
		foreach ( $templates as $id => $t ) {
			?>
			<p>
				<input type="radio" name="pdfcat_template" id="<?php echo $id; ?>"
				       value="<?php echo $id ?>" <?php if ( $current == $id ) {
					echo 'checked';
				} ?>>
				<label for="<?php echo $id ?>">
					<?php echo $t[0]; ?>
					<br>
					<?php echo $t[1] ?>
				</label>
			</p>
		<?php } ?>
	<?php

	}


	private function getCategoryTree( $parent ) {
		$cats = get_categories( array(
			'taxonomy'     => 'product_cat',
			'hierarchical' => 1,
			'parent'       => $parent
		) );

		foreach ( $cats as $k => $cat ) {
			$cats[ $k ]->children = $this->getCategoryTree( $cat->term_id );
		}

		return $cats;
	}

	private function renderCategoryTree( $cats, $preselected ) {
		echo '<ul>';
		foreach ( $cats as $cat ) {
			echo '<li>';

			echo '<input ';
			if ( array_search( $cat->term_id, $preselected ) !== false ) {
				echo 'checked ';
			}
			echo 'type="checkbox" id="fchk' . $cat->term_id . '" value="' . $cat->term_id . '"><label for="fchk' . $cat->term_id . '">' . $cat->name . '</label>';
			if ( count( $cat->children ) > 0 ) {
				$this->renderCategoryTree( $cat->children, $preselected );
			}
			echo '</li>';
		}
		echo '</ul>';
	}


	static function fld_hideoutofstock() {
		?>
		<input type="checkbox" id="pdfcat_hideoutofstock" name="pdfcat_hideoutofstock"
		       value="1" <?php echo ( get_option( 'pdfcat_hideoutofstock' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_hideoutofstock">Enable this option to hide out-of-stock items from PDF output.</label>
	<?php
	}

	static function fld_showhidden() {
		?>
		<input type="checkbox" id="pdfcat_showhidden" name="pdfcat_showhidden"
		       value="1" <?php echo ( get_option( 'pdfcat_showhidden' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_showhidden">Disable this option if you want products which have their catalog visibility set
			as Hidden to be excluded from PDF output.</label>
	<?php
	}

	static function fld_hiddenroles() {
		$roles = get_option( 'pdfcat_hiddenroles' );
		$rolesArr = array_flip( explode( ',', $roles ) );

		?>
		By default all user roles can download PDF catalogs from your store. The user roles that are selected here will not be able to view catalogs in PDF
		<br>and will not be able to see the PDF Catalog Widget or shortcode output.
		<?php
		global $wp_roles;

		echo '<ul id="pdf_roles">';
		?>
		<li style="margin-bottom: 1em"><input <?php if ( isset( $rolesArr['PDF_SIGNED_OUT'] ) ) {
				echo 'checked ';
			} ?>type="checkbox" value="PDF_SIGNED_OUT" id="rolePDF_SIGNED_OUT">
			<label for="rolePDF_SIGNED_OUT">Unregistered Users</label>
		</li>

		<?php
		foreach ( $wp_roles->roles as $key => $role ) {
			?>

			<li><input <?php if ( isset( $rolesArr[ $key ] ) ) {
					echo 'checked ';
				} ?>type="checkbox" value="<?php echo $key; ?>" id="role<?php echo $key; ?>">
				<label for="role<?php echo $key; ?>"><?php echo $role['name']; ?></label>
			</li>
		<?php
		}
		echo '</ul>';
		?>
		<input type="hidden" name="pdfcat_hiddenroles" id="pdfcat_hiddenroles" value="<?php echo $roles; ?>">
		<script>
			jQuery(function () {
				var $ = jQuery;
				pdc_updateRoles();
				$('#pdf_roles').on('click', 'input[type=checkbox]', function (e) {
					pdc_updateRoles();
				});

				function pdc_updateRoles() {
					var checked = $('#pdf_roles input[type=checkbox]').filter(function () {
						return $(this).is(':checked');
					}).map(function () {
						return $(this).val();
					});

					$('#pdfcat_hiddenroles').val(checked.get().join(','));

				}

			});
		</script>
	<?php
	}


	static function field_categories() {
		/*        $c = get_categories(array(
					'taxonomy' => 'product_cat',
					'hierarchical' => 1,
					'parent' =>0
				));
				var_dump($c);
		*/
		$pdf = new PDFCatalog();
		$cats = $pdf->getCategoryTree( 0 );
		?>
		<style>
			#pdfcat_cp {
				background: #fff;
				padding: 1em;
				display: inline-block;
			}

			#pdfcat_cp input[type=checkbox] {
				margin-right: .8em;
			}

			#pdfcat_cp ul {
				padding-left: 0;
				margin-left: 1.5em;
				margin-bottom: 5px;
			}

			#pdfcat_cp > ul {
				margin-left: 0;

			}

			#pdfcat_cp > ul > li {
				margin-bottom: 10px;
			}


		</style>
		<div id="pdfcat_cp">
			<?php
			$preselected = get_option( 'pdfcat_categories' );
			$preselected = explode( ',', $preselected );
			$pdf->renderCategoryTree( $cats, $preselected );
			?>
		</div>
		<div style="clear: left;margin-top: 10px">
			<input class="button" type="button" id="btn_all" value="All"><input type="button" id="btn_none"
			                                                                    class="button" value="None">

			<p>Note: Deselecting all categories is the same as selecting all categories.</p>
		</div>
		<input id="pdfcat_categories" type="hidden" name="pdfcat_categories"
		       value="<?php echo get_option( 'pdfcat_categories' ); ?>">

		<script>
			jQuery(function () {
				var $ = jQuery;
				$('#btn_all').click(function (e) {
					e.preventDefault();
					$('#pdfcat_cp li input[type=checkbox]').attr('checked', 'checked');
					pdc_updateCategories();
				});

				$('#btn_none').click(function (e) {
					e.preventDefault();
					$('#pdfcat_cp li input[type=checkbox]').removeAttr('checked');
					pdc_updateCategories();
				});

				$('#pdfcat_cp').on('click', 'input', function (e) {
					e.stopPropagation();
					pdc_updateCategories();
				});

				function pdc_updateCategories() {
					var checked = $('#pdfcat_cp li input[type=checkbox]').filter(function () {
						return $(this).is(':checked');
					}).map(function () {
						return $(this).val();
					});

					$('#pdfcat_categories').val(checked.get().join(','));

				}


			});
		</script>

	<?php
	}

	static function field_cache_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_cache" name="pdfcat_cache"
		       value="1" <?php echo ( get_option( 'pdfcat_cache' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_cache">keep catalogs for reuse if no changes were made to products. Disabling will slow down
			PDF delivery considerably.</label>
	<?php
	}


	static function field_download_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_downloadfile" name="pdfcat_downloadfile"
		       value="1" <?php echo ( get_option( 'pdfcat_downloadfile' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_downloadfile">force user browser to download PDF file (instead of viewing in
			browser).</label>
	<?php
	}


	static function field_productcount_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_showCategoryProductCount" name="pdfcat_showCategoryProductCount"
		       value="1" <?php echo ( get_option( 'pdfcat_showCategoryProductCount' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_showCategoryProductCount">show total number of products found under each category.</label>
	<?php
	}


	static function field_categorytitle_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_showCategoryTitle" name="pdfcat_showCategoryTitle"
		       value="1" <?php echo ( get_option( 'pdfcat_showCategoryTitle' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_showCategoryTitle">show category title in category-specific PDFs.</label>
	<?php
	}

	static function field_renderhtml_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_html" name="pdfcat_html"
		       value="1" <?php echo ( get_option( 'pdfcat_html' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_html">output HTML instead of PDF (enable to debug your own templates)</label>
	<?php
	}

	static function field_subsetting_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_subsetting" name="pdfcat_subsetting"
		       value="1" <?php echo ( get_option( 'pdfcat_subsetting' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_subsetting"> be default the whole font face is embedded in the PDF file but if you enable
			this option only characters that are used are embedded making the final PDF file smaller but takes more
			memory to generate the PDF output.</label>
	<?php
	}

	static function field_unicode_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_unicode" name="pdfcat_unicode"
		       value="1" <?php echo ( get_option( 'pdfcat_unicode' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_unicode">enable international character support. Enable only if needed, this option increases
			processing time, memory requirements and the size of the PDF files by around 300kb.</label>
	<?php
	}


	static function field_characterlimit() {
		?>
		<input type="text" id="pdfcat_characterLimit" name="pdfcat_characterLimit" size="4"
		       value="<?php echo (int) get_option( 'pdfcat_characterLimit' ); ?>">
		<br><label for="pdfcat_characterLimit">Truncate description to the specified number of characters (enter 0 for
			the full description).</label>
	<?php

	}


	static function field_startonnewpage_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_startOnNewPage" name="pdfcat_startOnNewPage"
		       value="1" <?php echo ( get_option( 'pdfcat_startOnNewPage' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_startOnNewPage">start categories on a fresh page in full store catalog.</label>
	<?php

	}

	// ------------ ADDTO2 --------------------------
	static function field_useShortDescription() {
		?>
		<input type="checkbox" id="pdfcat_useShortDescription" name="pdfcat_useShortDescription"
		       value="1" <?php echo ( get_option( 'pdfcat_useShortDescription' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_useShortDescription">use Short Product Description instead of the default long
			description.</label>
	<?php
	}

	// ----------------------------------------------

	static function field_sku_checkbox() {
		?>
		<input type="checkbox" id="show_sku" name="pdfcat_showSKU"
		       value="1" <?php echo ( get_option( 'pdfcat_showSKU' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="show_sku">include product SKUs in PDF catalog.</label>
	<?php
	}


	static function field_headlines_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_headLines" name="pdfcat_headLines"
		       value="1" <?php echo ( get_option( 'pdfcat_headLines' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_headLines">show separator lines between header/footer.</label>
	<?php
	}

	static function field_description_checkbox() {
		?>
		<input type="checkbox" id="pdfcat_showDescription" name="pdfcat_showDescription"
		       value="1" <?php echo ( get_option( 'pdfcat_showDescription' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_showDescription">show product description.</label>
	<?php
	}

	static function field_render_shortcodes() {
		?>
		<input type="checkbox" id="pdfcat_renderShortcodes" name="pdfcat_renderShortcodes"
		       value="1" <?php echo ( get_option( 'pdfcat_renderShortcodes' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="pdfcat_renderShortcodes"> process shortcodes (will strip shortcodes if disabled).</label>
	<?php
	}


	static function field_price_checkbox() {
		?>
		<input type="checkbox" id="show_price" name="pdfcat_showPrice"
		       value="1" <?php echo ( get_option( 'pdfcat_showPrice' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="show_price">include product prices in PDF catalog.</label>
	<?php
	}

	static function field_variations_checkbox() {
		?>
		<input type="checkbox" id="show_variations" name="pdfcat_showVariations"
		       value="1" <?php echo ( get_option( 'pdfcat_showVariations' ) == 1 ) ? 'checked' : ''; ?>>
		<label for="show_variations">show variation attributes for each product.</label>
	<?php
	}

}

$pdf = new PDFCatalog();
$pdf->init();