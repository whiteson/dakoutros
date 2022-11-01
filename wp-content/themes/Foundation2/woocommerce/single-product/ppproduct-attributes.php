<?php
/**
 * Product attributes
 *
 * Used by list_attributes() in the products class.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-attributes.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woothemes.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.3
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$has_row    = false;
$alt        = 1;
$attributes = $product->get_attributes();

ob_start();

?>

<?php
$attributesnames=array();
// Get the attributes
$attributes = $product->get_attributes();
// Start the loop

$tabled="";
foreach ( $attributes as $attribute ) :
  //  echo '<table style="border:1px solid grey"><tr>';
//echo  wc_attribute_label( $attribute['name'] ); 
//echo "</tr>";
?>
<?php
// Check and output, adopted from /templates/single-product/product-attributes.php
    if ( $attribute['is_taxonomy'] ) {
        $values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
        $temp= apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
    } else {
        // Convert pipes to commas and display values
        $values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
        $temp =  apply_filters( 'woocommerce_attribute', wpautop( wptexturize( implode( ', ', $values ) ) ), $attribute, $values );
    }
    $tabled .=$temp. "<hr>";
?>

<?php endforeach; ?>

	<?php
//echo $tabled;
?>
	
	<?php
	//get tableheader
	$attributesnames=array();
	$attributesvalues=array();
	?>
		<?php foreach ( $attributes as $attribute ) :
		if ( empty( $attribute['is_visible'] ) || ( $attribute['is_taxonomy'] && ! taxonomy_exists( $attribute['name'] ) ) ) {
			continue;
		} else {
			$has_row = true;
		}

		 //get  attributes in one array ;
		array_push($attributesnames, wc_attribute_label( $attribute['name'] )); 

				if ( $attribute['is_taxonomy'] ) {

					$values = wc_get_product_terms( $product->id, $attribute['name'], array( 'fields' => 'names' ) );
					array_push($attributesvalues, $values); 

				} 
			?>
	
	<?php endforeach; ?>
	<?php
	
	$output.="<table><tr>";
	$i=0;
	foreach ($attributesnames as $attributesname) {
	    
    $output.="<td>$attributesname</td>";

    
}
    $output.="</tr>";
    
    for ($x = 0; $x <= count($attributesvalues); $x++)
    {
        $output.="<tr>";
        foreach ($attributesvalues as $attributesvalue) 
        {
            $output.="<td>$attributesvalue[$x]</td>";
        }
        $output.="</tr>";
}
	    $output.="</table>";
	    
	    echo $output;

?>
<?php
if ( $has_row ) {
	echo ob_get_clean();
} else {
	ob_end_clean();
}
