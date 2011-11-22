<?php

// TODOS.
// - relations

class rex_shop_product_viewer
{
  function showProductList($products, $recalcArticleId = false)
  {
    global $REX;
    
    $c = 0;
    $images_in_row = 3;
    
    $images = '';
    foreach($products as $product)
    {
    
      $c++;
      
      $class = '';
      $clearer = '';
      if ($c == '1')
      {
        $class = ' first';
      }
      if ($c == $images_in_row)
      {
        $c = 0;
      }
      
      $class = $class != '' ? ' class="'.trim($class).'"' : '';
      
      $price_word = '';
      $arr = $product->getPricesArray();
      if(count($arr) > 1)
      {
        $price_word = 'ab ';
      }
      
      $images .= '<li'.$class.'>
                    <dl>
                      <dt>
                        <a href="'.$product->getDetailUrl($recalcArticleId).'">
                          <img src="/index.php?rex_img_type=shoplist&rex_img_file='.$product->getImage().'" alt="'.htmlspecialchars($product->getName()).'" title="'.htmlspecialchars($product->getName()).'" />
                        </a>
                      </dt>
                      <dd>
                        <h3>
                        <a class="title" href="'.$product->getDetailUrl($recalcArticleId).'">
                        '.nl2br(htmlspecialchars($product->getName())).'
                        </a>
                        </h3>
                        
                        <span class="text">'.nl2br($product->getValue("description_short")).'</span>
                        
                        <a class="detail" href="'.$product->getDetailUrl($recalcArticleId).'">Details</a>
                        
                        <span class="price">'.$price_word.rex_shop_utils::formatPrice($product->getPrice()).'</span>
                        
                        
                        <form action="'.rex_getUrl().'" method="post">
                        <fieldset>
                          <input type="hidden" name="product_id" value="'.$product->getId().'>" />
                          <input type="hidden" name="func" value="add_product" />
                          <input type="hidden" name="product_amount" value="1" />
                          <input type="hidden" name="page" value="list" />
                          <input type="submit" class="form-submit" name="add" value="In den Warenkorb legen" />
                        </fieldset>
                        </form>
                      </dd>
                    </dl>
                  </li>
               ';

    }
    
    if (count($products)>0)
    {
      echo '<ul class="shop-productlist">'.$images.'</ul>';
    }
    else
    {

      echo '<p>Keine Produkte gefunden</p>';

    }
  }

  function showProduct($product)
  {
  	// IMPORTANT: Veraendern der groesse des PopUps erfordert eine Aenderung im CSS!!!
  	
  	if ($product->getValue('status') > 0)
  	{
      $format   = $product->getValue('description_format') != '' ? '<h4>Format</h4><p>'.nl2br($product->getValue('description_format')).'</p>' : '';
      $long     = $product->getValue('description_long') != '' ? '<h4>Beschreibung</h4><p>'.nl2br($product->getValue('description_long')).'</p>' : '';
      $number   = $product->getValue("article_number") != '' ? '<dl class="articlenumber"><dt>Best.-Nr.</dt><dd>'.$product->getValue('article_number').'</dd></dl>' : '';
      
      $price = '';
      $arr = $product->getPricesArray();
      if(count($arr) > 1)
      {
        $price .= '<dl class="price price-diff">';
        $price .= '<dt>Staffelpreis:</dt><dd><ul>';
        foreach($arr as $pamount => $amount_price)
        {
         $price .= '<li>ab '. $pamount .' VE - '. rex_shop_utils::formatPrice($amount_price) .'*</li>';
        }
        $price .= '</ul></dd>';
        $price .= '</dl>';
      }
      else
      {
        $price .= '<dl class="price">';
        $price .= '<dt>Preis:</dt><dd>'. rex_shop_utils::formatPrice($product->getPrice()) .'*</dd>';
        $price .= '</dl>';
      }
      
      
      $quantity = '';
      $quantity .= '<dl class="quantity"><dt><label>Menge:</label></dt><dd>';
      $arr = $product->getAmountsArray();
      if (count($arr) > 0)
      {
        $quantity .= '<select name="product_amount" id="amount">';
        foreach($arr as $amount => $amount_desc)
        {
          $amount_text = $amount;
          if (trim($amount_desc) != "")
            $amount_text .= " ".trim($amount_desc);
            
          $quantity .= '<option value="'.$amount.'">'.$amount_text.'</option>';
        }
        $quantity .= '</select>';
      }
      else
      {
        $quantity .= '<input type="text" name="product_amount" value="1" size="3" maxlength="3" />';
      }
      $quantity .= '</dd></dl>';
      
      
      
      echo '
        <div class="shop-detail">
          <figure class="picture"><img src="/index.php?rex_img_type=shopdetail&rex_img_file='.$product->getImage().'" alt="'.htmlspecialchars($product->getName()).'" title="'.htmlspecialchars($product->getName()).'" /></figure>
          
          <div class="information">
            <h3>'.$product->getName().'</h3>
            '.$number.'
            <form action="'.rex_getUrl().'" method="post">
            <fieldset>
              <input type="hidden" name="product_id" value="'.$product->getId().'>" />
              <input type="hidden" name="func" value="add_product" />
              
              '.$price.$quantity.'
              
              <input type="submit" class="form-submit" name="search" value="In den Warenkorb legen" />
              <p class="shipping">* Preis zzgl. <a href="javascript:getPopup(\''.rex_getUrl(52).'\');">Versandkosten</a></p>
            </fieldset>
            </form>
          </div>
          
          <div class="description">
            '.$long.$format.'
          </div>
          
        </div>';
        
  	}
  	else
  	{
  		echo '<p>Produkt wurde nicht gefunden</p>';
  	}
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
  }
}

?>