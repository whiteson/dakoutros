<table>
    <tr nobr="true">
        <?php  if ($this->options->showSKU) { ?>
            <td>
                <span style="color:<?php echo  $titleColor ?>"><?php echo  $product->get_sku(); ?></span>
            </td>
        <?php  } ?>
        <td>
            <a style="color:<?php echo  $titleColor ?>; font-size:13px;text-decoration: none"
               href="<?php echo  get_permalink($post->ID); ?>"><?php echo  $post->post_title ?></a>
        </td>
        <?php 
        if ($this->options->showPrice) {
            ?>
            <td>
                <span style="color:<?php echo  $priceColor ?>"><?php echo  $product->get_price_html(); ?></span>
            </td>
        <?php  } ?>

    </tr>
</table>
<hr>