<?php

// TODOS.
// - relations

class rex_shop_product_viewer
{
/*
  // deprecated
  function showProductList($products, $recalcArticleId = false)
  {
    echo self::getProductList($products, $recalcArticleId);
  }

  // deprecated
  function showProduct($product)
  {
    echo self::getProduct($product);
  }
*/


  function showProductList($products, $recalcArticleId = false)
  {
    $return = '';

    $fragment = new rex_fragment();
    $fragment->setVar('products', $products, false);
    $fragment->setVar('article_id', $recalcArticleId, false);
    $return .= $fragment->parse('productlist.tpl');

    return $return;
  }


  function showProduct($product)
  {
    $return = '';

    $fragment = new rex_fragment();
    $fragment->setVar('product', $product, false);
    $return .= $fragment->parse('product.tpl');

    return $return;



/*
  // IMPORTANT: Veraendern der groesse des PopUps erfordert eine Aenderung im CSS!!!
  global $article_id, $clang;
?>
<div class="prdct">

    <p class="fl-lft">
      <span class="prdct-img">
        <span><a href="/files/<?php echo $product->getImage(); ?>" rel="lightbox[produkt]"><img src="index.php?rex_resize=119a__<?php echo $product->getImage(); ?>" alt="<?php echo htmlspecialchars($product->getName()); ?>" title="<?php echo htmlspecialchars($product->getName()); ?>" /><span>vergr&ouml;&szlig;ern</span></a></span>
      </span>
      
      <?php
      
      if (count($product->getImages()))
      {
      	echo '<span class="prdct-imgs">';
      	foreach($product->getImages() as $image)
      	{
      	echo '<span><a href="/files/'.$image.'" rel="lightbox[produkt]"><img src="index.php?rex_resize=50a__'.$image.'" alt="" title="" /></a></span>';
      	}
      	echo '</span>';
      
      }
	  
	  ?>
    </p>

    <div class="prdct-cntnt">

      <div class="frm">
      <form action="index.php" method="post">

      <input type="hidden" name="article_id" value="<?php echo $article_id ?>" />
      <input type="hidden" name="clang" value="<?php echo $clang ?>" />
      <input type="hidden" name="product_id" value="<?php echo $product->getId(); ?>" />
      <input type="hidden" name="func" value="add_product" />


      <fieldset>
        <div class="f-fldst">

          <h3><?php echo $product->getName(); ?></h3>
          <p><?php echo nl2br($product->getValue("description_short")); ?></p>

          <p><b><?php echo nl2br($product->getValue("description_format")); ?></b></p>
          <p><?php echo nl2br($product->getValue("description_long")); ?></p>

          <p>
              <?php
              $arr = $product->getPricesArray();
              if(count($arr) > 1)
              {
                echo '<strong>Staffelpreis:</strong><br />';
                  foreach($arr as $pamount => $amount_price)
                  {
                    echo ' '. rex_shop_utils::formatPrice($amount_price) .' ab '. $pamount .' VE<br />';
                  }
              }
              else
              {
                echo '<strong>Preis: '. rex_shop_utils::formatPrice($product->getPrice()) .'</strong><br />';
              }
              ?>
            <span class="clr-2"><strong>Best.-Nr. <?php echo $product->getValue("article_number"); ?></strong></span>
          </p>

          <p class="f-slct">
            <label for="amount">Menge:</label>
            <?php

              $arr = $product->getAmountsArray();
              if (count($arr)==0)
              {
                echo '<input type="text" name="product_amount" value="1" size="3" maxlength="3" />';
              }else
              {
                echo '<select name="product_amount" id="amount">';
                foreach($arr as $amount => $amount_desc)
                {
                  $amount_text = $amount;
                  if (trim($amount_desc) != "") $amount_text .= " ".trim($amount_desc);
                  echo '<option value="'.$amount.'">'.$amount_text.'</option>';
                }
                echo '</select>';
              }

            ?>
          </p>

          <p>
            Preis zzgl. Mwst. und Versandkosten <!--<a href="#">(?)</a>-->
          </p>

          <p class="f-sbmt-img">
            <input type="image" name="search" src="/files/sbmt_bttn_bskt.gif" value="In den Warenkorb legen" />
          </p>
        </div>
      </fieldset>
      </form>
      </div>
    </div>

    <div class="clearer"></div>
  </div>
  <?php
  }

  function showPreview($product)
  {
    global $REX;
    
    $mode = '400a__';
    $img = $product->getImage();
    $resize = $REX['INCLUDE_PATH'] .'/generated/files/image_resize__'. $mode . $img;
    
    if(file_exists($resize))
    {
        $size = getimagesize($resize);
        echo '<a href="javascript:self.close();" style="margin-top: -'. round($size[1] / 2) .'px; margin-left: -'. round($size[0] / 2) .'px;">
                <img src="index.php?rex_resize='. $mode . $img .'" class="prdct-prvw" />
              </a>';
    }
    else
    {
        echo '<a href="javascript:self.close();">
                <img src="index.php?rex_resize='. $mode . $img .'" class="prdct-prvw" />
              </a>';
    }
*/
  }
}

?>
