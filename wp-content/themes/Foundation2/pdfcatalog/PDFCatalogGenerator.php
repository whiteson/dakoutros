<?php

/**
 * Class PDFCatalogGenerator
 *
 * @param Renderer $pdf
 */
class PDFCatalogGenerator {

	static $temp = 0;
	static $pdfOut = false;
	static $pdf;

	private $products = array();
	private $currentProduct = - 1;
	private $productCount = 0;
	private $category = null;

	public $options = null;
	public $logoURL = '', $hasLogo = false;

	public $author = '';
	public $logo='';
	public $title = '';
	public $subtitle = '';
	public $subject = '';
	public $keywords = "";
	static $config = null;

	static $imageSizes = array(
		'default' => array( 'Default', - 1, - 1 ),
		'low'     => array( 'Low', 80, 80 ),
		'medium'  => array( 'Medium', 320, 320 ),
		'high'    => array( 'High', 640, 640 ),
		'super'   => array( 'Super High', 1280, 1280 )
	);

	static $template = 'basiclist';

	function prepare( $s ) {

		return $this->processShortcodes( $this->limit( $s ) );
	}

	function processShortcodes( $s ) {
		if ( $this->options->renderShortcodes == 0 ) {
			return strip_shortcodes( $s );
		} else {
			return do_shortcode( $s );
		}
	}

	function limit( $s ) {
		if ( $this->options->characterLimit == 0 ) {
			return $s;
		} else {
			return $this->htmlStringCut( $s, $this->options->characterLimit, '...', false, true );
		}
	}


	function __construct() {

		$pdfC = new PDFCatalog();
		$pdf = new K_PDFRenderer( PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false );

		$pdf->setJPEGQuality( (int) get_option( 'pdfcat_jpegquality' ) );

		$pdf->k_headerColor = PDFCatalogGenerator::hex2rgb( get_option( 'pdfcat_paperColor' ) );
		$pdf->k_headerBackground = PDFCatalogGenerator::hex2rgb( get_option( 'pdfcat_headerColor' ) );
		$pdf->k_headerTitleColor = PDFCatalogGenerator::hex2rgb( get_option( 'pdfcat_headerTitle' ) );
		$pdf->k_headerSubTitleColor = PDFCatalogGenerator::hex2rgb( get_option( 'pdfcat_headerSubTitle' ) );

		if ( function_exists( 'icl_t' ) ) {
			$pdf->k_headerTitle = $this->replaceTextVars( icl_t( 'PDF Catalog for WooCommerce', 'Header Title', '#store# catalog' ) );
			$pdf->k_headerSubTitle = $this->replaceTextVars( icl_t( 'PDF Catalog for WooCommerce', 'Sub Heading', 'This catalog was generated on #dategenerated#' ) );
		} else {
			$pdf->k_headerTitle = $this->replaceTextVars( get_option( 'pdfcat_headTitle' ) );
			$pdf->k_headerSubTitle = $this->replaceTextVars( get_option( 'pdfcat_headSubtitle' ) );
		}

		$pdf->k_logoFilePath = $pdfC->getLogoFilePath();
		$this->logo = $pdf->k_logoFilePath;
		$pdf->k_showLines = ( get_option( 'pdfcat_headLines' ) == 1 );
		$pdf->k_linesColor = PDFCatalogGenerator::hex2rgb( get_option( 'pdfcat_headLinesColor' ) );

		PDFCatalogGenerator::$template = get_option( 'pdfcat_template' );
		PDFCatalogGenerator::$pdf = $pdf;

		$this->options = new stdClass();
		$this->options->characterLimit = get_option( 'pdfcat_characterLimit' );
		$this->options->renderShortcodes = get_option( 'pdfcat_renderShortcodes' );
		$this->options->showCategoryTitle = ( get_option( 'pdfcat_showCategoryTitle' ) );
		$this->options->showCategoryProductCount = ( get_option( 'pdfcat_showCategoryProductCount' ) == 1 );
		$this->options->showSKU = ( get_option( 'pdfcat_showSKU' ) == 1 );
		$this->options->showDescription = ( get_option( 'pdfcat_showDescription' ) == 1 );
		$this->options->showPrice = ( get_option( 'pdfcat_showPrice' ) == 1 );
		PDFCatalogGenerator::$pdfOut = ( get_option( 'pdfcat_html' ) == 0 );

		// ----- ADDTO2 ---------------------------------------------
		$this->options->useShortDescription = ( get_option( 'pdfcat_useShortDescription' ) == 1 );
		// ----------------------------------------------------------
	}

	function replaceTextVars( $t ) {
		$vars = array( '#store#', '#dategenerated#', '#timegenerated#' );
		$replace = array(
			get_bloginfo(),
			date( get_option( 'date_format' ) ),
			date( 'H:i' )
		);

		return str_replace( $vars, $replace, $t );
	}

	static function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );

		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = array( $r, $g, $b );

		return $rgb;
	}

	static function getTemplates() {
		require dirname( __FILE__ ) . '/templates/pdf/templates.php';

		return $templates;
	}


	private function htmlStringCut( $text, $length = 100, $ending = '...', $exact = false, $considerHtml = true ) {
		$open_tags = array();
		if ( $considerHtml ) {

			if ( strlen( preg_replace( '/<.*?>/', '', $text ) ) <= $length ) {
				return $text;
			}

			preg_match_all( '/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER );
			$total_length = strlen( $ending );

			$truncate = '';
			foreach ( $lines as $line_matchings ) {
				if ( ! empty( $line_matchings[1] ) ) {
					if ( preg_match( '/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1] ) ) {
					} else if ( preg_match( '/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings ) ) {
						$pos = array_search( $tag_matchings[1], $open_tags );
						if ( $pos !== false ) {
							unset( $open_tags[ $pos ] );
						}
					} else if ( preg_match( '/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings ) ) {
						array_unshift( $open_tags, strtolower( $tag_matchings[1] ) );
					}
					$truncate .= $line_matchings[1];
				}
				$content_length = strlen( preg_replace( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2] ) );
				if ( $total_length + $content_length > $length ) {
					$left = $length - $total_length;
					$entities_length = 0;
					if ( preg_match_all( '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE ) ) {
						foreach ( $entities[0] as $entity ) {
							if ( $entity[1] + 1 - $entities_length <= $left ) {
								$left --;
								$entities_length += strlen( $entity[0] );
							} else {
								break;
							}
						}
					}
					$truncate .= substr( $line_matchings[2], 0, $left + $entities_length );
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				if ( $total_length >= $length ) {
					break;
				}
			}
		} else {
			if ( strlen( $text ) <= $length ) {
				return $text;
			} else {
				$truncate = substr( $text, 0, $length - strlen( $ending ) );
			}
		}
		if ( ! $exact ) {
			$spacepos = strrpos( $truncate, ' ' );
			if ( isset( $spacepos ) ) {
				$truncate = substr( $truncate, 0, $spacepos );
			}
		}
		$truncate .= $ending;
		if ( $considerHtml ) {
			foreach ( $open_tags as $tag ) {
				$truncate .= '</' . $tag . '>';
			}
		}

		return $truncate;
	}


	function hasCustomHeader() {
		return file_exists( $this->getTemplatePath() . 'header.php' );
	}

	function hasCustomFooter() {
		return file_exists( $this->getTemplatePath() . 'footer.php' );
	}

	function getCustomFooter() {
		if ($this->hasCustomFooter()) {
			/* @var $pdf K_PDFRenderer */
			$pdf = PDFCatalogGenerator::$pdf;
			$subtitle  = $pdf->k_headerSubTitle;
			$title = $pdf->k_headerTitle;
			$logo = $this->logo;

			ob_start();
			include $this->getTemplatePath().'footer.php';
			return ob_get_clean();
		} else return '';
	}


	function getCustomHeader() {
		if ($this->hasCustomHeader()) {
			/* @var $pdf K_PDFRenderer */
			$pdf = PDFCatalogGenerator::$pdf;
			$subtitle  = $pdf->k_headerSubTitle;
			$title = $pdf->k_headerTitle;
			$logo = $this->logo;

			ob_start();
			include $this->getTemplatePath().'header.php';
			return ob_get_clean();
		} else return '';
	}

	function pdfHeader() {

		/* @var $pdf K_PDFRenderer */

		$pdf = PDFCatalogGenerator::$pdf;
		$pdf->k_customHeader = $this->hasCustomHeader();
		$pdf->k_headerHTML = $this->getCustomHeader();
		$pdf->k_customFooter = $this->hasCustomFooter();
		$pdf->k_footerHTML = $this->getCustomFooter();

		if ( (int) get_option( 'pdfcat_unicode' ) == 1 ) {
			$font = 'dejavusans';
		} else {
			$font = 'helvetica';
		}


		$pdf->setFontSubsetting( ( (int) get_option( 'pdfcat_subsetting' ) ) == 1 );
		$pdf->SetCreator( 'PDF Product Catalog Generator for WooCommerce' );


		$pdf->SetAuthor( $this->author );
		$pdf->SetTitle( $this->title );
		$pdf->SetSubject( $this->subject );
		$pdf->SetKeywords( $this->keywords );


		$pdf->SetHeaderData( 'logo.png', 20, $this->title, $this->subtitle, array( 50, 50, 50 ), array(
			150,
			150,
			150
		) );


		$pdf->setFooterData( array( 0, 64, 0 ), array( 0, 64, 128 ) );


		$pdf->setHeaderFont( Array( $font, '', PDF_FONT_SIZE_MAIN ) );
		$pdf->setFooterFont( Array( $font, '', PDF_FONT_SIZE_DATA ) );

		$pdf->SetDefaultMonospacedFont( PDF_FONT_MONOSPACED );


		$pdf->SetMargins( PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT );
		$pdf->SetHeaderMargin( PDF_MARGIN_HEADER );
		$pdf->SetFooterMargin( PDF_MARGIN_FOOTER );

		$pdf->SetAutoPageBreak( false, PDF_MARGIN_BOTTOM );
		$pdf->setImageScale( PDF_IMAGE_SCALE_RATIO );


		$pdf->SetFont( $font, '', 10, '', 'false' );

		$pdf->AddPage();

	}

	function getCategoryItems( $category, $includeExtras = false, $nochildren = true ) {

		$order = get_option( 'pdfcat_order' );
		$orderby = get_option( 'pdfcat_orderby' );

		$showHidden = get_option( 'pdfcat_showhidden' );
		$hideOutOfStock = get_option( 'pdfcat_hideoutofstock' );

		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => - 1,
			'product_cat'    => $category,
			'order'          => $order,
			'orderby'        => $orderby,
		);

		if ($nochildren) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat',
					'field' => 'slug',
					'terms' => $category,
					'include_children' => false
				)
			);
		}

		if ( $showHidden != 1 ) {
			$args['meta_query'][] =
				array(
					'key'     => '_visibility',
					'value'   => array( 'catalog', 'visible' ),
					'compare' => 'IN'
				);
		}

		if ( $hideOutOfStock == 1 ) {
			$args['meta_query'][] =
				array(
					'key'     => '_stock_status',
					'value'   => 'instock',
					'compare' => '='
				);
		}


		$loop = new WP_Query( $args );
	//	var_dump($args,$loop);
		$items = array();

		$posts = $loop->posts;

		$products = array();


		if ( get_option( 'pdfcat_imagesize' ) == 'default' ) {
			$addImageSize = 'thumbnail';
		} else {
			$addImageSize = 'pdf_catalog';
		}

		if ( $includeExtras ) {
			foreach ( $posts as $post ) {

				$product = get_product( $post->ID );

				$terms = get_the_terms( $post->ID, 'product_cat' );


				$product->post->thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), $addImageSize );

				if ( isset( $product->post->thumbnail[0] ) ) {
					$product->post->thumbnail = $product->post->thumbnail[0];
				} else {
					// no image found
				}


				$products[] = $product;


			}
		}

		return $products;
	}

	function row() {
		$pdf = $this;
		$productCount = $this->productCount;
		$count = $this->currentProduct + 1;
		$category = $this->category;
		$templatePath = PDFCatalogGenerator::getTemplatePath();

		$_renderer = PDFCatalogGenerator::$pdf;
		/* @var $_renderer Renderer */

		ob_start();
		include $templatePath . 'row.php';
		$html = ob_get_clean();

		if ( PDFCatalogGenerator::$pdfOut ) {

			$_renderer->startTransaction();
			$_renderer->writeHTMLCell( 0, 0, '', '', $html, 0, 1, false, true, '', true );

			if ( $_renderer->GetY() > 285 ) {
				$_renderer->rollbackTransaction( true );
				$_renderer->AddPage();
				$_renderer->SetY( '35' );
				$_renderer->writeHTMLCell( 0, 0, '', '', $html, 0, 1, false, true, '', true );

			} else {
				$_renderer->commitTransaction();
			}

		} else {
			echo $html;
		}


	}

	function hasMoreProducts() {
		return ! ( $this->currentProduct + 1 >= $this->productCount );

	}

	function product() {


		$templatePath = PDFCatalogGenerator::getTemplatePath();
		$this->currentProduct ++;
		if ( $this->currentProduct > $this->productCount - 1 ) {
			return;
		}

		$product = $this->products[ $this->currentProduct ];
		$post = $product->post;

		$paperColor = get_option( 'pdfcat_paperColor' );
		$titleColor = get_option( 'pdfcat_mainText' );
		$textColor = get_option( 'pdfcat_lightText' );
		$priceColor = get_option( 'pdfcat_priceColor' );
		$categoryTitleColor = get_option( 'pdfcat_categoryColor' );


		$hasVariations = false;

		$variations = array();
		if ( $product->product_type == 'variable' ) {
			$hasVariations = true;
			$attributes = $product->get_variation_attributes();
			foreach ( $attributes as $k => $a ) {
				$variations [ wc_attribute_label( $k ) ] = $a;
			}

		}

		include $templatePath . 'beforeProduct.php';
		include $templatePath . 'product.php';
		include $templatePath . 'afterProduct.php';


	}


	static function getTemplatePath() {
		return dirname( __FILE__ ) . '/templates/pdf/' . PDFCatalogGenerator::$template . '/';
	}

	function getProductCategoriesFromList( $catIDs ) {
		$args = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'term_group',
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 1,
			'include'      => implode( ',', $catIDs )
		);

		$categories = get_categories( $args );

		return $categories;
	}

	function getProductCategories() {

		$args = array(
			'taxonomy'     => 'product_cat',
			'orderby'      => 'name',
			'pad_counts'   => 0,
			'hierarchical' => 1,
			'title_li'     => '',
			'hide_empty'   => 1
		);

		$categories = get_categories( $args );
		$selected = get_option( 'pdfcat_categories' );

		$all = ( $selected == '' );

		if ( ! $all ) {
			$selected = array_flip( explode( ',', $selected ) );
		}

		$out = array();

		if ( $all ) {
			$out = $categories;
		} else {
			foreach ( $categories as $key => $category ) {
				if ( isset( $selected[ $category->term_id ] ) ) {
					$out[] = $category;
				}
			}
		}

		return $out;


	}

	static function sanitizeCatIDs( $catIDs ) {
		for ( $i = 0; $i < count( $catIDs ); $i ++ ) {
			$catIDs[ $i ] = (int) $catIDs[ $i ];
		}

		$catIDs2 = array_unique( $catIDs );
		sort( $catIDs2, SORT_NUMERIC );

		return $catIDs2;
	}


	function generateFullCatalogPDF( $lang, $cacheFile = '', $list = null ) {
		if ( $list != null ) {
			$categories = $this->getProductCategoriesFromList( $list );

		} else {
			$categories = $this->getProductCategories();
		}

		//var_dump($categories); exit;

		if ( PDFCatalogGenerator::$pdfOut ) {
			$this->pdfHeader();
		}

		$pdf = PDFCatalogGenerator::$pdf;
		$tagvs = array(
			'p' => array(
				0 => array( 'h' => 0, 'n' => 0 ),
				1 => array(
					'h' => 0,
					'n'
					    => 0
				)
			)
		);

		if ( PDFCatalogGenerator::$pdfOut ) {
			$pdf->setHtmlVSpace( $tagvs );
		}

		$notFirst = false;
		$startOnNew = ( get_option( 'pdfcat_startOnNewPage' ) == 1 );

		foreach ( $categories as $category ) {
			$products = $this->getCategoryItems( $category->slug, true );
			if ( count( $products ) > 0 ) { // skip empty categories
				if ( ( $startOnNew ) && ( $notFirst ) ) {
					PDFCatalogGenerator::$pdf->AddPage();
				} else {
					$notFirst = true;
				}
				$this->currentProduct = - 1;
				$this->generateCategory( $category, $products );
			}
		}

		if ( PDFCatalogGenerator::$pdfOut ) {
			$pdf->writeHTMLCell( 0, 0, '', '', '<br><div>' . get_option( 'pdfcat_footerText', '' ) . '</div>', 0, 1, false, true, '', true );
		} else {
			echo get_option( 'pdfcat_footerText', '' );
		}

		if ( PDFCatalogGenerator::$pdfOut ) {
			$pdf->Output( $cacheFile, 'F' );
		}

	}

	function generateCategoryPDF( $category, $lang ) {

		if ( PDFCatalogGenerator::$pdfOut ) {
			$this->pdfHeader();
		}

		$pdf = PDFCatalogGenerator::$pdf;
		$tagvs = array(
			'p' => array(
				0 => array( 'h' => 0, 'n' => 0 ),
				1 => array(
					'h' => 0,
					'n'
					    => 0
				)
			)
		);

		if ( PDFCatalogGenerator::$pdfOut ) {
			$pdf->setHtmlVSpace( $tagvs );
		}

		$this->generateCategory( $category );

		if ( PDFCatalogGenerator::$pdfOut ) {
			$pdf->writeHTMLCell( 0, 0, '', '', '<br><div>' . get_option( 'pdfcat_footerText', '' ) . '</div>', 0, 1, false, true, '', true );
		} else {
			echo get_option( 'pdfcat_footerText', '' );
		}

		if ( PDFCatalogGenerator::$pdfOut ) {
			$pdf->Output( dirname( __FILE__ ) . '/cache/categories/' . $category->slug . $lang . '.pdf', 'F' );
		}
	}


	private function  generateCategory( $category, $products = null ) {
		$pdf = PDFCatalogGenerator::$pdf;

		if ( $products == null ) {
			$this->products = $this->getCategoryItems( $category->slug, true );
		} else {
			$this->products = $products;
		}
		$templatePath = PDFCatalogGenerator::getTemplatePath();


		$this->productCount = count( $this->products );
		$this->category = $category;

		$paperColor = get_option( 'pdfcat_paperColor' );
		$titleColor = get_option( 'pdfcat_mainText' );
		$textColor = get_option( 'pdfcat_lightText' );
		$priceColor = get_option( 'pdfcat_priceColor' );
		$categoryTitleColor = get_option( 'pdfcat_categoryColor' );


		ob_start();
		include $templatePath . 'beforeList.php';
		$html = ob_get_clean();
		if ( PDFCatalogGenerator::$pdfOut ) {
			$pdf->writeHTMLCell( 0, 0, '', '', $html, 0, 1, false, true, '', true );
		} else {
			echo $html;
		}

		while ( $this->hasMoreProducts() ) {
			$this->row();
		}

		ob_start();
		include $templatePath . 'afterList.php';
		$html = ob_get_clean();
		if ( PDFCatalogGenerator::$pdfOut ) {
			$pdf->writeHTMLCell( 0, 0, '', '', $html, 0, 1, false, true, '', true );
		} else {
			echo $html;
		}


	}
}
