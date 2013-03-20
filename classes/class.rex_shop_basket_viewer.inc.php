<?php
class rex_shop_basket_viewer
{
    function getBasket()
    {
        $output = '
      <div class="simple-shop-basket">
        <fieldset>
        ' . self :: _getBasketBody($refresh = true) . '
        </fieldset>
      </div>';

        return $output;
    }

    function getHtmlBasket()
    {
        return self :: _getBasketBody($refresh = false, false);
    }

    function getPlainBasket()
    {
        $format = "%-15s | %-25s | %-10s | %-10s | %-15s\n";
        $noteformat = "%-15s   > %-25s: %15s\n";

        $plain = '';
        $plain .= "Warenkorb\n\n";
        $plain .= sprintf($format, 'Bestell Nr.', 'Artikelbezeichnung', 'Preis', 'Anzahl', 'Gesamtpreis') . "\n";

        $sum = 0;
        $vat = 0;
        $overallDiscounts = array();
        foreach (rex_shop_basket :: getBasket() as $pid => $pamount) {
            $product = new rex_shop_product($pid);
            $price = $product->getPrice($pamount);
            $aprice = $pamount * $price;
            $sum += $aprice;

            $name = htmlspecialchars($product->getName());
            if ($product->getVE() != '' && $product->getVE() > 0) $name .= ' VE: ' . htmlspecialchars($product->getVE());

            $plain .= sprintf($format,
                $product->getValue('article_number'),
                $name,
                rex_shop_utils :: formatPrice($price),
                $pamount,
                rex_shop_utils :: formatPrice($aprice));

            $productDiscount = 0;
            $discounts = $product->getDiscounts($pamount);
            if ($discounts && count($discounts) > 0) {
                $discountNote = '';
                foreach ($discounts as $name => $discount) {
                    if (!isset ($overallDiscounts[$name])) {
                        $overallDiscounts[$name] = 0;
                    }
                    $overallDiscounts[$name] += $discount;
                    $productDiscount += $discount;

                    $discountNote .= sprintf($noteformat, '', htmlspecialchars($name), rex_shop_utils::formatPrice($discount));
                }
                $plain .= $discountNote;
            }
            $vat += ($aprice + $productDiscount) * ($product->getTax() / 100);
            $plain .= "\n";
        }

        $overallDiscountsStr = '';
        $overallDiscount = 0;
        foreach ($overallDiscounts as $name => $discount) {
            $overallDiscount += $discount;
            $overallDiscountsStr .= sprintf($noteformat, '', $name, rex_shop_utils :: formatPrice($discount));
        }

        $plain .= "\n";
        $plain .= sprintf($noteformat, '', 'Gesamtbetrag', rex_shop_utils :: formatPrice($sum));
        $plain .= $overallDiscountsStr;
        $plain .= sprintf($noteformat, '', 'MwSt.', rex_shop_utils :: formatPrice($vat));
        $plain .= "                  ====================\n";
        $plain .= sprintf($noteformat, '', 'Rechnungsbetrag inkl. MwSt. **', rex_shop_utils :: formatPrice($sum + $vat + $overallDiscount));
        $plain .= sprintf($noteformat, '', '** MwSt. wird auf der Rechnung ausgewiesen', '');

        return str_replace('&euro;', '�', $plain);
    }



    function _getBasketBody($refresh = false, $allowUserInput = true)
    {
        global $REX;

        $shipping     = $REX['ADDON']['simple_shop']['settings']['shipping_rates'];
        $gross_prices = $REX['ADDON']['simple_shop']['settings']['gross_prices'];


        $items = array();


        $sum = 0;
        $vat = 0;
        $overallDiscounts = array();
        foreach (rex_shop_basket :: getBasket() as $pid => $pamount) {
            $product = new rex_shop_product($pid);
            $price = $product->getPrice($pamount);
            $aprice = $pamount * $price;
            $sum += $aprice;


            $item = array();

            if ($allowUserInput) {
                $item['image']  = $product->getImage();
                $item['amount'] = '<input type="text" name="amount[' . $pid . ']" value="' . $pamount . '" />';
                $item['delete'] = '<a href="' . rex_getUrl('', '', array('func' => 'del_product', 'product_id' => $product->getId())) . '">' . rex_i18n::msg('simple_shop_delete') . '</a>';
            }
            else {
                $item['amount'] = $pamount;
            }

            $item['article_number']      = $product->getValue('article_number');
            $item['name']                = $product->getName();
            $item['description_short']   = $product->getValue('description_short');
            $item['description_format']  = $product->getValue('description_format');
            $item['unit_price']          = rex_shop_utils :: formatPrice($price);
            $item['total_price']         = rex_shop_utils :: formatPrice($aprice);
            $item['vat']                 = $product->getTax();

            $item['ve']             = '';
            if ($product->getVE() != '' && $product->getVE() > 0) {
                $item['ve']             = $product->getVE();
            }


            $productDiscount = 0;
            $discounts = $product->getDiscounts($pamount);
            if ($discounts && count($discounts) > 0) {
                foreach ($discounts as $name => $discount) {
                    if (!isset ($overallDiscounts[$name])) {
                        $overallDiscounts[$name] = 0;
                    }
                    $overallDiscounts[$name] += $discount;
                    $productDiscount += $discount;

                    $item['discount_name']  = htmlspecialchars($name);
                    $item['discount_price'] = rex_shop_utils::formatPrice($discount);

                }
            }

            if ($gross_prices) {
                $vat += ($aprice + $productDiscount) - (($aprice + $productDiscount) / (1 + $product->getTax() / 100));
            }
            else {
                $vat += ($aprice + $productDiscount) * ($product->getTax() / 100);
            }


            $items[] = $item;
            unset($item);
        }

        $summary = array();


        $s = array();
        $s['price'] = rex_shop_utils :: formatPrice($sum);
        $summary['net'] = $s;
        unset($s);


        $overallDiscount = 0;
        foreach ($overallDiscounts as $name => $discount) {
            $overallDiscount += $discount;

            $s = array();
            $s['name']  = $name;
            $s['price'] = rex_shop_utils :: formatPrice($discount);

            $summary['discount'] = $s;
            unset($s);
        }

        $s = array();
        $s['price'] = rex_shop_utils :: formatPrice($vat);
        $summary['vat'] = $s;
        unset($s);
/*
        $s = array();
        $s['price'] = rex_shop_utils :: formatPrice($sum + $vat);
        $summary['gross'] = $s;
        unset($s);
*/
        $s = array();
        $s['price'] = rex_shop_utils :: formatPrice($shipping);
        $summary['shipping'] = $s;
        unset($s);

        $s = array();
        //$s['price'] = rex_shop_utils :: formatPrice($sum + $vat + $overallDiscount + $shipping);
        $s['price'] = rex_shop_utils :: formatPrice($sum + $overallDiscount + $shipping);
        $summary['grandtotal'] = $s;
        unset($s);


        if ($refresh) {
            $s = array();
            $s['field'] = '<input type="submit" name="refresh_cart" value="' . rex_i18n::msg('simple_shop_basket_update') . '" />';
            $summary['refresh'] = $s;
            unset($s);
        }



        $fragment = new rex_fragment();
        $fragment->setVar('products', $items, false);
        $fragment->setVar('summary', $summary, false);
        return $fragment->parse('basket.tpl');


        $output .= '
        <tfoot>
          <tr class="tbl-row-1">
            <td class="tbl-col-1 algn-rght" colspan="3">Gesamtbetrag</td>
            <td class="algn-rght" colspan="2">' . rex_shop_utils :: formatPrice($sum) . '</td>
            ' . $col6 . '
          </tr>
          ' . $overallDiscountsStr . '
          <tr class="tbl-row-3">
            <td class="tbl-col-1 algn-rght" colspan="3">MwSt.</td>
            <td class="algn-rght" colspan="2">' . rex_shop_utils :: formatPrice($vat) . '</td>
            ' . $col6 . '
          </tr>
          <tr class="tbl-row-4">
            <td class="tbl-col-1 algn-rght" colspan="3">Rechnungsbetrag inkl. MwSt. **</td>
            <td class="algn-rght" colspan="2">' . rex_shop_utils :: formatPrice($sum + $vat + $overallDiscount) . '</td>
            ' . $col6 . '
          </tr>
          <tr class="tbl-row-5">
            <td class="tbl-col-1 algn-rght" colspan="3">** MwSt. wird auf der Rechnung ausgewiesen:</td>
            <td class="algn-rght" colspan="2"></td>
            ' . $col6 . '
          </tr>
          ' . $suffix . '
        </tfoot>
      </table>';

        return $output;
    }
}
