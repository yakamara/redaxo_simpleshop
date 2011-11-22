
<?php 

$setting_shop_category = 44;

$REX["PAGE_NO_NAVI"] = true;
require $REX['INCLUDE_PATH'].'/layout/top.php'; 

$mypage = "simple_shop";
$prod_id = '';
$rel_id = rex_request('rel_id', 'int');
$category_id = rex_request('category_id', 'int');



rex_title($I18N_SIMPLE_SHOP->msg("header_relations"));



// ----------------------->  Suche der Artikel über die Kategorien

$c = new rex_category_select(false, false, true, false);
$c->setName('category_id');
$c->setSelected($category_id);
$c->setSize(1);
$c->setStyle("width:200px;");
$c->setRootId($setting_shop_category);

echo '
<style type="text/css">
  #rex-website {
    width: 100% !important;
  }
  #rex-main {
    display: none;
  }
  #rex-wrapper {
    width: 100% !important;
    margin-right: 0px !important;
  }
  #rex-wrapper2 {
    padding: 0 16px !important;
  }
</style>

<table class="rex-table" width="100%">
  <tr>
    <th class="icon" width="30">&nbsp;</th>
    <th colspan="2">'.$I18N_SIMPLE_SHOP->msg("product_overview").'</th>
  </tr>
  
  <tr>
    <td>&nbsp;</td>	 
    <td style="vertical-align: middle;">
      <form action="index.php" method="post" name="catsearch">
        <input type="hidden" name="page" value="'.$mypage.'" />
        <input type="hidden" name="subpage" value="'.$subpage.'" />
        <input type="hidden" name="prod_id" value="'.$prod_id.'" />
        <input type="hidden" name="rel_id" value="'.$rel_id.'" />
        '.$c->get().'
        <input type="submit" name="cs" value="'.$I18N_SIMPLE_SHOP->msg("show").'" />
      </form>
    </td>
  
    <td style="vertical-align: middle;">
      <form action="index.php" method="post" name="catsearch">
        <input type="hidden" name="page" value="'.$mypage.'" />
        <input type="hidden" name="subpage" value="'.$subpage.'" />
        <input type="hidden" name="prod_id" value="'.$prod_id.'" />
         <input type="hidden" name="rel_id" value="'.$rel_id.'" />
        <input type="hidden" name="articlesearch" value="" />
        <input type="submit" name="cs" value="'.$I18N_SIMPLE_SHOP->msg("show_all").'" />
      </form>
    </td>
  </tr>
</table>';

if($category_id > 0)
{
  //---------------------------------- Liste der Artikel
	
	$products = rex_shop_category :: getProductList($category_id, false);

	echo '<table class="rex-table">';
  foreach($products as $product)
  {	
    echo '
      <tr>
      <td>
        <img src="media/document.gif" height="16" width="16" />
      </td>
      <td>
        '.$product->getName().'
      </td>
      <td>
        <a href="javascript:opener.setREXShop(\''.$rel_id.'\',\''.$product->getId().'\',\''.$product->getName().'\');self.close();")>'.$I18N_SIMPLE_SHOP->msg("header_relation_add").'</a>
      </td>
    </tr>';
    
  }
  echo '</table>';
}

?>


<?php 

require $REX['INCLUDE_PATH'].'/layout/bottom.php'; 

?>





