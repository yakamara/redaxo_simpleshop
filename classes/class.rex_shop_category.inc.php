<?php


class rex_shop_category extends OOCategory
{

    function rex_shop_category(){

    }



    static function getProductList($id = '', $with_offline = true, $order = 'id')
    {

        global $REX;
        $add = "";
        if($id > 0) {
            $add = ' categories LIKE "%|' . $id . '|%"';
        }
        elseif($id == -1) {
            $add = ' categories LIKE"|"';
        }

        if(!$with_offline) {
            if ($add != '')
                $add .= ' AND ';

            $add .= ' status > 0 ';
        }

        if ($add != '')
            $add = 'where ' . $add;

        return rex_shop_product::hydrate('SELECT * FROM rex_shop_product ' . $add . ' ORDER BY ' . $order , 'id');
    }

    /*

        function searchProducts($search)
        {

            global $REX;
            $strings = explode(" ",$search);
            $counter=0;

            foreach($strings as $s){

                if($counter != 0) $add.= " AND ";
                $add .= "( name like '%$s%' OR description like '%$s%' OR detaildesc like '%$s%' OR code like '%$s%')";
                $counter++;
            }

            $sql = new sql;
            $sql->debugsql=0;
            $sql->setQuery("SELECT * FROM ".$REX[ADDON][tbl][art]["simple_shop"]."
                where clang='".$clang."' AND
                status>0 AND ".$add." ORDER BY name");

            $return = array();
            for($i=0; $i<$sql->rows; $i++){
            $return[] = new rex_shop_product(
                $sql->getValue("id"),
                $sql->getValue("clang"),
                $sql->getValue("name"),
                $sql->getValue("path"),
                $sql->getValue("category"),
                $sql->getValue("description"),
                $sql->getValue("code"),
                $sql->getValue("vat"),
                $sql->getValue("price"),
                $sql->getValue("old_price"),
                $sql->getValue("deliver_price"),
                $sql->getValue("detaildesc"),
                $sql->getValue("thumbnail"),
                $sql->getValue("picture"),
                $sql->getValue("relation_1"),
                $sql->getValue("relation_2"),
                $sql->getValue("relation_3"),
                $sql->getValue("prio"),
                $sql->getValue("status"),
                $sql->getValue("instock"),
                $sql->getValue("stockinfo"));
                $sql->next();
            }

             return $return;
          }

        */
}

?>