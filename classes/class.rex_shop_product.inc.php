<?php

// TODOS.
// - relations

class rex_shop_product
{

    var $id;
    var $values;

    function rex_shop_product($id)
    {
        $sql = new rex_sql();
        // $sql->debugsql=1;
        $sql->setQuery('SELECT * FROM rex_shop_product WHERE id=' . $id);
        $product_array = $sql->getArray();
        if (count($product_array) != 1) return;

        $this->values = $product_array[0];
    }

    function isValid()
    {
        if ($this->values['id'] != '') return true;
        return false;
    }

    function getValue($v)
    {
        return $this->values[$v];
    }

    function getName()
    {
        return $this->values['name'];
    }

    function getCategories()
    {

        $product = explode('|', $this->values['categories']);
        $return = array();
        foreach ($product as $a) {
            if ($a != '')
                $return[] = $a;
        }
        return $return;
    }

    function getDescription()
    {
        return $this->values['description_short'];
    }

    function getDetail()
    {
        return $this->values['description_long'];
    }

    function getVE()
    {
        return $this->values['ve'];
    }

    function getId()
    {
        return $this->values['id'];
    }

    function getValidAmount($amount)
    {
        return $amount;
    }

    function getArticleNumber()
    {
        return $this->values['article_number'];
    }

    function getTax()
    {
        return $this->values['vat'];
    }

    function getAmountGroup()
    {

        $agid = (int) $this->values['amount_group_id'];
        if ($agid < 1) return false;
        return $agid;
    }

    function getPrice($amount = -1)
    {
        $prices = $this->getPricesArray();

        if ($amount == -1) {
            return current($prices);
        } else {
            krsort($prices);
            foreach ($prices as $pamount => $amount_price) {
                if ( $amount >= $pamount) {
                    return $amount_price;
                }
            }
        }
        return 0;
    }


    function getPricesArray()
    {
        $a = explode(';', $this->values['prices']);
        $arr = array();
        if (is_array($a)) {
            foreach ($a as $p) {
                $v = explode(',', $p);
                if ($v[0] != '' or $v[1] != '') $arr[$v[0]] = (float) $v[1];
            }
        }
        ksort($arr);
        return $arr;
    }

    function getOldPrice()
    {
        return (float) $this->values['oldprice'];
    }

    function getAmountsArray()
    {

        $a = explode(';', $this->values['order_amounts']);
        $arr = array();
        if (is_array($a)) {
            foreach ($a as $p) {
                $e = explode(',', $p);
                $e[0] = (int) $e[0];
                if ($e[0] > 0) $arr[$e[0]] = $e[1];
            }
        }
        return $arr;
    }

    function getMaxOrder()
    {
        return (int) $this->values['order_max'];
    }

    function getMinOrder()
    {
        return (int) $this->values['order_min'];
    }

    function checkOrderValue($ordervalue)
    {
        if ($this->getMinOrder() > 0 && $ordervalue < $this->getMinOrder()) return false;
        if ($this->getMaxOrder() > 0 && $ordervalue > $this->getMaxOrder()) return false;
        return true;
    }


    function getImage()
    {
        if ($this->values['image'] == '') return 'buecher.jpg';
        return $this->values['image'];
    }

    function getImages()
    {
        if ($this->values['images'] == '') return array();
        return explode(',', $this->values['images']);
    }

    function getStatus()
    {

        if ($this->values['status'] == 1) return 1;
        else return 0;
    }

    function getPrio()
    {
        return $this->values['prio'];
    }
    function getPath()
    {
        return $this->values['path'];
    }
    function getInStock()
    {
        return $this->values['stock_in'];
    }
    function getStockinfo()
    {
        return $this->values['stock_info'];
    }

    function getDetailUrl($recalcArticleId = false)
    {
        $articleId = '';
        if ($recalcArticleId) {
            // ggf Link schaetzen um die Detailseite anzeigen zu koennen
            // z.b. fuer die suche
            $categories = $this->getCategories();
            foreach ($categories as $category) {
                $slice = OOArticleSlice::getSlicesForArticleOfType($category, 11);
                if ($slice != null) {
                    $articleId = $category;
                    break;
                }
            }
        }
        return rex_getUrl($articleId, '', array('product_id' => $this->getId(), 'product_title' => $this->getName()));
    }

    // ToDo - ueberarbeiten, da relation Tabelle geloescht wurde. field = product.discount_group_ids
    function getDiscountGroupIds()
    {
        $sql = rex_sql::factory();
        $sql->setQuery('SElECT discount_group_ids FROM rex_shop_product WHERE id = "' . $this->getId() . '" LIMIT 2');
        if ($sql->getRows() == 1) {
            return explode(',', $sql->getValue('discount_group_ids'));
        }

        return false;

    }

    // ToDo - ueberarbeiten, da relation Tabelle geloescht wurde. field = product.discount_group_ids
    function getDiscounts($amount)
    {
        $discount_group_ids = self::getDiscountGroupIds();
        if ($discount_group_ids) {

            $where = implode(' OR ', $discount_group_ids);

            $sql = rex_sql::factory();
            $sql->setQuery('SELECT * FROM rex_shop_product_discount_group WHERE ' . $where);

            $discounts = array();
            $price = $this->getPrice();
            if ($sql->getRows() >= 1) {

                for ($i = 1; $i <= $sql->getRows(); $i++, $sql->next()) {
                    if ($sql->getValue('amount') != 0 && $amount >= $sql->getValue('amount')
                    ||
                    $sql->getValue('price') != 0 && ($amount * $price) >= $sql->getValue('price')
                    ) {
                        $discountName = $sql->getValue('name');

                        if ($sql->getValue('discount_percent') != 0) {
                            $discounts[$discountName] = $price * -$sql->getValue('discount_percent') / 100 * $amount;
                        } elseif ($sql->getValue('discount_value') != 0) {
                            $discounts[$discountName] = -$sql->getValue('discount_value');
                        }
                    }

                }
            }

            return $discounts;
        }

        return false;
    }

    // Bisher noch nicht verwendet ..
    function getRelatedProducts()
    {
        // return new rex_shop_product($this->values["relations"]);
    }

    /**
     * �bersetzt ein rex_sql Resultset aus dem Query $qry in ein Objektarry (hydrating)
     */
    static function hydrate($qry, $idColumnName = 'product_id')
    {
        $sql = new rex_sql();
        //$sql->debugsql = true;
        $sql->setQuery($qry);

        $result = array();
        for ($i = 0; $i < $sql->getRows(); $i++) {
            $product = new self($sql->getValue($idColumnName));
            if ($product->isValid()) {
                $result[] = $product;
            }
            $sql->next();
        }
        return $result;
    }

    function searchProductsByOrderId($orderId)
    {
        return self::hydrate('SELECT `product_id` FROM `rex_shop_order_product` WHERE status=1 and order_id=' . $orderId);
    }

    function searchProducts($searchString)
    {
        return self::hydrate('SELECT `id`
                                          FROM `rex_shop_product`
                            WHERE status=1 and
                            vt LIKE "%' . $searchString . '%" ',
            'id');
    }

    public function getModuleProductName($scheme = '')
    {
        if ($scheme == '')
            return $this->getName();

        preg_match_all('/###([a-z_]+)###/', $scheme, $matches, PREG_SET_ORDER);

        if (count($matches) > 0) {
            $search  = array();
            $replace = array();
            foreach ($matches as $match) {
                $search[]  = $match[0];
                $replace[] = $this->getValue($match[1]);
            }

            $name = str_replace($search, $replace, $scheme);
            if (strlen($name) > 150)
                $name = substr($name, 0, 150) . ' …';

            return $name;
        }
    }

}
