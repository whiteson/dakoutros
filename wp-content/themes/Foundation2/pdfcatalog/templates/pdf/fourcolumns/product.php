πετροσ<table>
	<tr>
		<td>
			<div>
			    <?php
			    $imgid = get_post_thumbnail_id($post->ID);
			    $image_attributes = wp_get_attachment_image_src( $attachment_id = $imgid );
			    
			    ?>
				<a href="<?php echo  get_permalink($post->ID); ?>"><img src="<?php echo $image_attributes[0]; /* $post->thumbnail; */ ?>" align="left" width="140"></a>
			</div>
			<div style="color:<?php echo  $titleColor ?>">
				<a style="color:<?php echo  $titleColor ?>; font-size:14px;text-decoration: none"
				   href="<?php echo  get_permalink($post->ID); ?>"><?php echo  $post->post_title ?></a></div>
			<?php 
			if ($this->options->showDescription) {
				?>
				<div style="color:<?php echo  $textColor ?>; font-size:10px"><?php echo $this->prepare( ($this->options->useShortDescription) ? $post->post_excerpt :  $post->post_content ); ?></div>
			<?php  } ?>

			<?php  if ($this->options->showPrice) { ?><br><span style="font-size:11px;color:<?php echo  $priceColor ?>"><?php echo  $product->get_price_html(); ?></span><?php  }  ?>

			<?php  if ((get_option('pdfcat_showVariations')) && ($hasVariations)) { ?>
				<div style="font-size:11px">
					<?php  foreach ($variations as $label => $options) { ?>
						<span style="color:<?php echo $textColor?>"><?php echo  $label; ?>:</span>
						<span style="color:<?php echo $textColor?>"><?php echo  implode(', ', $options) ?></span><br>
					<?php  } ?>
				</div>
			<?php  } ?>
			<?php  if (($this->options->showSKU) && (strlen($product->get_sku())>0)) { ?><span style="font-size:11px; color:<?php echo  $titleColor ?>">(<?php echo  $product->get_sku(); ?>)</span><?php  }  ?>

		</td>

	</tr>
</table>