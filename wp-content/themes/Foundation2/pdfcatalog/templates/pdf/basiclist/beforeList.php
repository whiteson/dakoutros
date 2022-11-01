<?php if ($this->options->showCategoryTitle) { ?>
    <div>
        <h1 style="color:<?php echo  $categoryTitleColor ?>"><?php echo  $category->name; ?></h1>
    </div>
<?php  } ?>

<table>
    <tr>
        <?php  if ($this->options->showSKU) { ?>
            <td>
                <span style="font-weight:bold;color:<?php echo  $titleColor ?>">SKU</span>
            </td>
        <?php  } ?>
        <td style="text-align: left">
            <span style="font-weight:bold;color:<?php echo  $titleColor ?>">Title</span>
        </td>
        <?php  if ($this->options->showPrice) { ?>
        <td>
            <span style="font-weight:bold;color:<?php echo  $titleColor ?>">Price</span>
        </td>
        <?php  } ?>

    </tr>
</table>
<hr>