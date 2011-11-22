<?php
class rex_shop_basket_viewer
{
  function getBasket()
  {
    $suffix = '
      <tr class="refresh">
        <td class="algn-rght" colspan="5"><input class="form-submit" type="submit" name="refresh_cart" value="Warenkorb aktualisieren" /></td>
      </tr>';
    $output = '
      <div class="frm frm-bskt">
        <fieldset>
        ' . rex_shop_basket_viewer :: _getBasketBody($suffix) . '
        </fieldset>
      </div>';

    return $output;
  }

  function getHtmlBasket()
  {
    return rex_shop_basket_viewer :: _getBasketBody('', false);
  }

  function getPlainBasket()
  {
  	$shipping = '3.20';
    $format = "%-15s | %-25s | %-10s | %-10s | %-15s\n";
    $noteformat = "%-15s   > %-25s: %15s\n";
    
    $plain = '';
    $plain .= "Warenkorb\n\n";
    $plain .= sprintf($format, 'Bestell Nr.','Artikelbezeichnung','Preis','Anzahl','Gesamtpreis'). "\n";

    $sum = 0;
    $vat = 0;
    $overallDiscounts = array ();
    foreach (rex_shop_basket :: getBasket() as $pid => $pamount)
    {
      $product = new rex_shop_product($pid);
      $price = $product->getPrice($pamount);
      $aprice = $pamount * $price;
      $sum += $aprice;
      
      $name = htmlspecialchars($product->getName());
      if ($product->getVE()!="" && $product->getVE()>0) $name .= ' VE: '.htmlspecialchars($product->getVE());

      $plain .= sprintf($format, 
        $product->getValue("article_number"),
        $name,
        rex_shop_utils :: formatPrice($price),
        $pamount,
        rex_shop_utils :: formatPrice($aprice));

      $productDiscount = 0;
      $discounts = $product->getDiscounts($pamount);
      if ($discounts && count($discounts) > 0)
      {
        $discountNote = '';
        foreach ($discounts as $name => $discount)
        {
          if (!isset ($overallDiscounts[$name]))
          {
            $overallDiscounts[$name] = 0;
          }
          $overallDiscounts[$name] += $discount;
          $productDiscount += $discount;
          
          $discountNote .= sprintf($noteformat, '', htmlspecialchars($name), rex_shop_utils::formatPrice($discount));
        }
        $plain .= $discountNote;
      }
//      $vat += ($aprice + $productDiscount)* ($product->getTax() / 100);
      $vat += ($aprice + $productDiscount) - (($aprice + $productDiscount) / (1 + $product->getTax() / 100));
      $plain .= "\n";
    }

    $overallDiscountsStr = '';
    $overallDiscount = 0;
    foreach ($overallDiscounts as $name => $discount)
    {
      $overallDiscount += $discount;
      $overallDiscountsStr .= sprintf($noteformat, '', $name, rex_shop_utils :: formatPrice($discount));
    }
    
    $plain .= "\n";
    $plain .= sprintf($noteformat, '', 'Gesamtbetrag', rex_shop_utils :: formatPrice($sum));
    $plain .= $overallDiscountsStr;
    $plain .= sprintf($noteformat, '', 'MwSt.', rex_shop_utils :: formatPrice($vat));
    $plain .= sprintf($noteformat, '', 'Porto und Verpackung', rex_shop_utils :: formatPrice($shipping));
    $plain .= "                  ====================\n";
    $plain .= sprintf($noteformat, '', 'Rechnungsbetrag inkl. MwSt. **', rex_shop_utils :: formatPrice($sum + $overallDiscountm + $shipping));
    $plain .= sprintf($noteformat, '', '** MwSt. wird auf der Rechnung ausgewiesen', '');

    return str_replace('&euro;', '', $plain);
  }

  function _getBasketBody($suffix, $allowUserInput = true)
  {
    $th6 = '';
    $col6 = '';
    
  	$shipping = '3.20';
  	
    if($allowUserInput)
    {
        $th6 = '<th class="delete">&nbsp;</th>';
        $col6 = '<td>&nbsp;</td>';
    }
    /*
        <tr class="tbl-row-1">
          <th class="tbl-col-1" colspan="6"><h3>Warenkorb</h3></th>
        </tr>
    */
    $output = '
    <table class="shop-basket">
      <thead>
        <tr class="tbl-row-1">
          <th class="image"></th>
          <th class="title">Artikelbezeichnung</th>
          <th class="amount">Anzahl</th>
          <th class="price">Gesamtpreis</th>
          '. $th6 .'
        </tr>
      </thead>';

    $output .= '<tbody>';
    $sum = 0;
    $vat = 0;
    $overallDiscounts = array ();
    foreach (rex_shop_basket :: getBasket() as $pid => $pamount)
    {
      $product = new rex_shop_product($pid);
      $price = $product->getPrice($pamount);
      $aprice = $pamount * $price;
      $sum += $aprice;


      $first_column = '';
      if($allowUserInput)
      {
        $colspan = 3;
        $first_column = '<img src="/index.php?rex_img_type=shopbasket&rex_img_file='.$product->getImage().'" alt="'.htmlspecialchars($product->getName()).'" title="'.htmlspecialchars($product->getName()).'" />';
        $user_amount = '<input class="f-txt f-amnt" type="text" name="amount[' . $pid . ']" value="' . $pamount . '" />';
/*
        $user_amount = '<span>'.$pamount.'</span>
                        <a class="raise" href="'.rex_getUrl('', '', array ('func' => 'plus_product', 'product_id' => $product->getId())).'">um 1 erhöhen</a>
                        <a class="reduce" href="'.rex_getUrl('', '', array ('func' => 'minus_product', 'product_id' => $product->getId())).'">um 1 verringern</a>'
*/
        $art_delete = '<td class="delete"><a href="' . rex_getUrl('', '', array ('func' => 'del_product', 'product_id' => $product->getId())) . '" class="shop-basket-delete" title="Artikel löschen"></a></td>';
      }
      else
      {
        $colspan = 2;
        $first_column = $product->getValue("article_number");
        $user_amount = $pamount;
        $art_delete = '';
      }

//            <td class="tbl-col-1">' . $product->getValue("article_number") . '</td>
      $output .= '
          <tr class="shop-product">
            <td class="image">'.$first_column.'</td>
            <td class="title">' . htmlspecialchars($product->getName());
      
//      if ($product->getVE()!="" && $product->getVE()>0) $output .= '<br />VE: '.$product->getVE();
      
//            <td class="tbl-col-3 algn-rght">' . rex_shop_utils :: formatPrice($price) . '<br />['. $product->getTax() .'%&nbsp;MwSt.]</td>
      $output .=  '<span class="price"> '. rex_shop_utils :: formatPrice($price) .'</span></td>
            <td class="amount">'. $user_amount .'</td>
            <td class="price algn-rght">' . rex_shop_utils :: formatPrice($aprice) . '</td>
            '. $art_delete .'
          </tr>
        ';

      $productDiscount = 0;
      $discounts = $product->getDiscounts($pamount);
      if ($discounts && count($discounts) > 0)
      {
        $last = end($discounts);
        foreach ($discounts as $name => $discount)
        {
          if (!isset ($overallDiscounts[$name]))
          {
            $overallDiscounts[$name] = 0;
          }
          $overallDiscounts[$name] += $discount;
          $productDiscount += $discount;
          
          $class = $discount == $last ? ' shop-product-information-list' : '';
          
            $output .= '
              <tr class="shop-product-information'. $class .'">
                <td class="image"></td>
                <td class="title">'. htmlspecialchars($name) .'</td>
                <td class="price">' . rex_shop_utils::formatPrice($discount) .'</td>
                <td class="tbl-col-4" colspan="'. $colspan .'"></td>
              </tr>
            ';
        }
      }
//      $vat += ($aprice + $productDiscount)* ($product->getTax() / 100);
      $vat += ($aprice + $productDiscount) - (($aprice + $productDiscount) / (1 + $product->getTax() / 100));
    }
    $output .= '</tbody>';

    /*
        <tr class="prdct">
          <td class="tbl-col-1">40972</td>
          <td class="tbl-col-2">Aushängefahnen</td>
          <td class="tbl-col-3">85,90 &euro;</td>
          <td class="tbl-col-4"><input class="f-txt f-amnt" type="text" name="amount" value="1" /></td>
          <td class="tbl-col-5 algn-rght">257,70 &euro;</td>
          <td class="tbl-col-6"><a href="#" class="link-dlt" title="Artikel löschen"></a></td>
        </tr>
    */

    $overallDiscountsStr = '';
    $overallDiscount = 0;
    foreach ($overallDiscounts as $name => $discount)
    {
      $overallDiscount += $discount;
      $overallDiscountsStr .= '
          <tr class="tbl-row-2">
            <td class="tbl-col-1 algn-rght" colspan="3">' . $name . '</td>
            <td class="algn-rght" colspan="2">' . rex_shop_utils :: formatPrice($discount) . '</td>
            '. $col6 .'
          </tr>
          ';
    }

//            <td class="algn-rght" colspan="2">' . rex_shop_utils :: formatPrice($sum + $vat + $overallDiscount) . '</td>
    $output .= '
        <tfoot>
          <tr class="price">
            <td class="text algn-rght" colspan="3">Gesamtbetrag</td>
            <td class="price algn-rght">' . rex_shop_utils :: formatPrice($sum) . '</td>
            '. $col6 .'
          </tr>
          ' . $overallDiscountsStr . '
          <tr class="vat">
            <td class="text algn-rght" colspan="3">inklusive 19% Mehrwertsteuer</td>
            <td class="price algn-rght">' . rex_shop_utils :: formatPrice($vat) . '</td>
            '. $col6 .'
          </tr>
          <tr class="shiping">
            <td class="text algn-rght" colspan="3">zzgl. Porto und Verpackung</td>
            <td class="price algn-rght">' . rex_shop_utils :: formatPrice($shipping) . '</td>
            '. $col6 .'
          </tr>
          <tr class="invoice">
            <td class="text algn-rght" colspan="3">Rechnungsbetrag inklusive Mehrwertsteuer **</td>
            <td class="price algn-rght">' . rex_shop_utils :: formatPrice($sum + $overallDiscount + $shipping) . '</td>
            '. $col6 .'
          </tr>
          ' . $suffix . '
          <tr class="vat-information">
            <td class="text" colspan="3">** Mehrwertsteuer wird auf der Rechnung ausgewiesen</td>
            <td class="algn-rght"></td>
            '. $col6 .'
          </tr>
        </tfoot>
      </table>';

    return $output;
  }
}
?>