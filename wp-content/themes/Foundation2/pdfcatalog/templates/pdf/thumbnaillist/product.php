<table>
    <tr>
        <td width="180">
            <div>
	            <a href="<?php echo  get_permalink($post->ID); ?>"><img src="<?php echo  $post->thumbnail; ?>" align="left" width="140" height="140"></a>
            </div>
        </td>

        <td width="420">

            <div><a style="color:<?php echo  $titleColor ?>; font-size:16px;text-decoration: none" href="<?php echo  get_permalink($post->ID); ?>"><?php echo  $post->post_title ?></a></div>

            <?php 
            if ($this->options->showDescription) {
            ?>
            <div style="color:<?php echo  $textColor ?>"><?php echo $this->prepare( ($this->options->useShortDescription) ? $post->post_excerpt :  $post->post_content ); ?></div>
            <?php  } ?>

            <?php  if ($this->options->showPrice) { ?>
                <span style="color:<?php echo  $priceColor ?>"><?php echo  $product->get_price_html(); ?></span>
            <?php  } ?>


            <?php  if (($this->options->showSKU) && ($product->get_sku() != '')) { ?>
                <span style="color:<?php echo  $textColor ?>; font-size:10px">(<?php echo  $product->get_sku(); ?>)</span>
            <?php  } ?>

            <?php  if ((get_option('pdfcat_showVariations')) && ($hasVariations)) { ?>
                <br>

                <?php  foreach ($variations as $label => $options) { ?>
                    <span style="color:#333; font-size:11px"><?php echo  $label; ?>:</span>
                    <span style="color:#555; font-size: 11px"><?php echo  implode(', ', $options) ?></span><br>
                <?php  } ?>

            <?php  } ?>

        </td>
    </tr>
</table>