<table>
	<tr>
		<td>
			<div>
				<a href="<?php echo  get_permalink($post->ID); ?>"><img src="<?php echo  $post->thumbnail; ?>" align="left" width="140" height="140"></a>
			</div>
			<div style="color:<?php echo  $titleColor ?>">
				<a style="color:<?php echo  $titleColor ?>; font-size:14px;text-decoration: none"
				   href="<?php echo  get_permalink($post->ID); ?>"><?php echo  $post->post_title ?></a></div>
			<?php 
			if ($this->options->showDescription) {
				?>
				<div style="color:<?php echo  $textColor ?>; font-size:10px"><?php echo $this->prepare( ($this->options->useShortDescription) ? $post->post_excerpt :  $post->post_content ); ?></div>
			<?php  } ?>

			<?php  if ($this->options->showPrice) { ?><br><span style="color:<?php echo  $priceColor ?>"><?php echo  $product->get_price_html(); ?></span><?php  }  ?>

			<?php  if ((get_option('pdfcat_showVariations')) && ($hasVariations)) { ?>
				<div>
					<?php  foreach ($variations as $label => $options) { ?>
						<span style="color:#333"><?php echo  $label; ?>:</span>
						<span style="color:#555"><?php echo  implode(', ', $options) ?></span><br>
					<?php  } ?>
				</div>
			<?php  } ?>
			<?php  if (($this->options->showSKU) && (strlen($product->get_sku())>0)) { ?>(<span style="color:<?php echo  $titleColor ?>"><?php echo  $product->get_sku(); ?></span>)<?php  }  ?>

		</td>

	</tr>
</table>