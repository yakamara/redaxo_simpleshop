<?php


$REX['PAGE_NO_NAVI'] = true;
require $REX['INCLUDE_PATH'].'/layout/top.php';

$mypage = 'simple_shop';
$subpage = 'relations';
$product_id = '';
$relation_id = rex_request('relation_id', 'int');
$category_id = rex_request('category_id', 'int');



rex_title($I18N->msg('simple_shop_header_relations'));



// ----------------------->  Suche der Artikel über die Kategorien

$c = new rex_category_select(false, false, true, false);
$c->setName('category_id');
$c->setSelected($category_id);
$c->setSize(1);
$c->setAttribute('onchange', 'this.form.submit();');
$c->setStyle("width:200px;");
$c->setRootId($REX['ADDON']['simple_shop']['settings']['root_category_id']);

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

<div class="rex-addon-output">
<table class="rex-table">
	  <colgroup>
	    <col width="40" />
	    <col width="*" />
	    <col width="153" />
	  </colgroup>
  <tr>
    <th class="rex-icon" width="30">&nbsp;</th>
    <th colspan="2">'.$I18N->msg('simple_shop_product_overview').'</th>
  </tr>
  
  <tr>
    <td>&nbsp;</td>	 
    <td style="vertical-align: middle;">
      <form action="index.php" method="post" name="catsearch">
        <input type="hidden" name="page" value="'.$mypage.'" />
        <input type="hidden" name="subpage" value="'.$subpage.'" />
        <input type="hidden" name="product_id" value="'.$product_id.'" />
        <input type="hidden" name="relation_id" value="'. $relation_id.'" />
        '.$c->get().'
        <input type="submit" name="cs" value="' . $I18N->msg('simple_shop_show') . '" />
      </form>
    </td>
  
    <td style="vertical-align: middle;">
      <form action="index.php" method="post" name="catsearch">
        <input type="hidden" name="page" value="'.$mypage.'" />
        <input type="hidden" name="subpage" value="'.$subpage.'" />
        <input type="hidden" name="product_id" value="'.$product_id.'" />
        <input type="hidden" name="relation_id" value="'.$relation_id.'" />
        <input type="hidden" name="category_id" value="0" />
        <input type="submit" name="cs" value="' . $I18N->msg('simple_shop_show_all') . '" />
      </form>
    </td>
  </tr>
</table>
</div>';

if(isset($category_id)) {
  //---------------------------------- Liste der Artikel

  $products = rex_shop_category :: getProductList($category_id, false);

  echo '<div class="rex-addon-output">';
  echo '
  <table class="rex-table">
	  <colgroup>
	    <col width="40" />
	    <col width="200" />
	    <col width="*" />
	    <col width="153" />
	  </colgroup>
	  <thead>
      <tr>
        <th class="rex-icon"><span class="rex-i-element rex-i-article-add"></span></th>
        <th>'.$I18N->msg('simple_shop_header_article').'</th>
        <th>'.$I18N->msg('simple_shop_short_description').'</th>
        <th>'.$I18N->msg('simple_shop_header_status_function').'</th>
      </tr>
     </thead>
     <tbody>

  ';
  foreach($products as $product) {
    $name = $product->getModuleProductName( $REX['ADDON']['simple_shop']['settings']['module_relationlist_scheme'] );
    echo '
      <tr>
        <td class="rex-icon"><span class="rex-i-element rex-i-article"></span></td>
        <td>' . $product->getName() . '</td>
				<td>' . htmlspecialchars($product->getValue('description_short')) . '</td>
        <td><a href="javascript:opener.selectRelationlist(\''.$product->getId().'\',\'' . $name . '\');")>' . $I18N->msg('simple_shop_header_relation_add') . '</a></td>
      </tr>';
  }
  echo '</tbody></table>';
  echo '</div>';
}

require $REX['INCLUDE_PATH'].'/layout/bottom.php';
