<?php define( 'WP_USE_THEMES', false );

//define( 'K_PATH_CACHE', '');
require( dirname( __FILE__ ) . '/../../../wp-blog-header.php' );

if ( ! PDFCatalog::canViewCatalog() ) {
	exit;
}
$wpml = defined( 'ICL_LANGUAGE_CODE' );
$lang = '';

if ( $wpml ) {
	$lang = '-' . ICL_LANGUAGE_CODE;
}

/*
xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);
*/

//set_time_limit( 0 );
$useCache = ( get_option( 'pdfcat_cache' ) == 1 );
$HTMLout = ( get_option( 'pdfcat_html' ) == 1 );
if ( $HTMLout ) {
	$useCache = false;
}


if ( isset ( $_GET['cm'] ) ) {
	$catIDs = PDFCatalogGenerator::sanitizeCatIDs(explode( '-', $_GET['cm'] ));

	if ( count( $catIDs ) > 0 ) {
		generateFull( $useCache, $lang, $HTMLout, $catIDs );
	} else echo 'No categories specified';

} else if ( isset( $_GET['all'] ) ) {
	// send full catalog
	$cachePath = dirname( __FILE__ ) . '/cache/categories/';
	$cacheFile = dirname( __FILE__ ) . '/cache/categories/full' . $lang . '.pdf';

	$p = new PDFCatalogGenerator();
	if ( $useCache == 1 ) {


		if ( file_exists( $cacheFile ) ) {
			$lastProductUpdateTime = getLastProductUpdateTime();
			$lastPDFGeneratedTime = filemtime( $cacheFile );

			if ( $lastProductUpdateTime > $lastPDFGeneratedTime ) {
				$p->generateFullCatalogPDF( $lang,$cacheFile );
			}
		} else {
			$p->generateFullCatalogPDF( $lang,$cacheFile );
		}
	} else {
		$p->generateFullCatalogPDF( $lang,$cacheFile );
	}


	if ( ! $HTMLout ) {

		$blogName = get_bloginfo( 'name', 'raw' );

		$slug = 'Catalog';

		headers( $slug, $lang );
		readfile( $cacheFile );
	}

} else {

	if ( isset( $_GET['c'] ) ) {
		$catID = (int) $_GET['c'];
	} else {
		$catID = 0;
	}

	$cat = get_term_by( 'id', $catID, 'product_cat' );

	if ( $cat ) {
		$slug = $cat->slug;
		$cachePath = dirname( __FILE__ ) . '/cache/categories/';
		$cacheFile = dirname( __FILE__ ) . '/cache/categories/' . $slug . $lang . '.pdf';

		if ( $useCache == 1 ) {
			if ( file_exists( $cacheFile ) ) {

				$lastProductUpdateTime = getLastProductUpdateTime( $cat );
				$lastPDFGeneratedTime = filemtime( $cacheFile );

				if ( $lastProductUpdateTime > $lastPDFGeneratedTime ) {
					generate( $cat, $lang );
				}

			} else {
				generate( $cat, $lang );
			}
		} else {
			// no cache
			generate( $cat, $lang );
		}

		if ( ! $HTMLout ) {
			headers( $slug, $lang );
			readfile( $cacheFile );

		}

	}
}

/*
$xhprof_data = xhprof_disable();


$XHPROF_ROOT = "/var/www/sites/default/xhprof";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_lib.php";
include_once $XHPROF_ROOT . "/xhprof_lib/utils/xhprof_runs.php";

$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "GetCatalog-final");
*/

function getLastProductUpdateTime( $category = null ) {
	if ( $category != null ) {
		$args = array( 'post_type' => 'product', 'posts_per_page' => 1, 'product_cat' => $category->slug );
	} else {
		$args = array( 'post_type' => 'product', 'posts_per_page' => 1 );
	}

	$loop = new WP_Query( $args );
	$posts = $loop->posts;

	if ( count( $posts ) > 0 ) {
		$lastProductUpdateTime = get_the_time( 'U', $posts[0] );
	} else {
		return PHP_INT_MAX;
	}

}

function generate( $cat, $lang ) {
	$p = new PDFCatalogGenerator();
	$p->generateCategoryPDF( $cat, $lang );

}

function headers( $slug, $lang ) {
	$blogName = get_bloginfo( 'name', 'raw' );
	$filename = preg_replace( '/[^a-z0-9]/ui', '', $blogName ) . '-' . preg_replace( '/[^a-z0-9]/ui', '', $slug );

	header( 'Content-type: application/pdf' );

	if ( get_option( 'pdfcat_downloadfile' ) == 1 ) {
		header( 'Content-Disposition: attachment; filename="' . $filename . '.pdf"' );
	} else {
		header( 'Content-Disposition: inline; filename="' . $filename . '.pdf"' );
	}
}


function generateFull( $useCache = 1, $lang, $HTMLout, $catIDs = null ) {
	$cachePath = dirname( __FILE__ ) . '/cache/categories/';

	if ( $catIDs != null ) {
		$cacheFile = dirname( __FILE__ ) . '/cache/categories/' . implode( '-', $catIDs ) . $lang . '.pdf';
	} else {
		$cacheFile = dirname( __FILE__ ) . '/cache/categories/full' . $lang . '.pdf';
	}

	$p = new PDFCatalogGenerator();
	if ( $useCache == 1 ) {
		if ( file_exists( $cacheFile ) ) {
			$lastProductUpdateTime = getLastProductUpdateTime();
			$lastPDFGeneratedTime = filemtime( $cacheFile );

			if ( $lastProductUpdateTime > $lastPDFGeneratedTime ) {
				$p->generateFullCatalogPDF( $lang, $cacheFile, $catIDs );
			}
		} else {
			$p->generateFullCatalogPDF( $lang, $cacheFile, $catIDs );
		}
	} else {
		$p->generateFullCatalogPDF( $lang, $cacheFile, $catIDs );
	}


	if ( ! $HTMLout ) {

		$blogName = get_bloginfo( 'name', 'raw' );

		$slug = 'Catalog';

		headers( $slug, $lang );
		readfile( $cacheFile );
	}

}