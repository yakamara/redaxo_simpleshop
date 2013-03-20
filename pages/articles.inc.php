<?php

error_reporting(E_ALL);

/*if (!rex_request('function') == 'edit') {
	echo '<h1>'.$I18N_SIMPLE_SHOP->msg("product_overview").'</h1>';
}*/

$refresh_seconds = $REX['ADDON']['simple_shop']['settings']['refresh_seconds'];
$page_url = 'index.php?page=simple_shop';

$function   = rex_request('function', 'string');
$product_id = rex_request('product_id', 'int');




// ***************************************************************************** Produkt Kategorieliste

$root_category_id = isset($REX['ADDON']['simple_shop']['settings']['root_category_id']) ? $REX['ADDON']['simple_shop']['settings']['root_category_id'] : 0;
$category_id = rex_request('category_id', 'int');


if ($category_id == $root_category_id) {
  $category_id = 0;
}

if ($category_id > 0) {
    $page_url .= '&category_id=' . $category_id;
}

$sel_cat = new rex_category_select(false, false, false, false);
$sel_cat->setSize(1);
$sel_cat->setAttribute('class', 'rxshp-wdth');
$sel_cat->setAttribute('onchange', 'this.form.submit();');
$sel_cat->setName('category_id');
$sel_cat->setId('rex-root-category-id');
$sel_cat->setRootId($root_category_id);
$sel_cat->setSelected($category_id);



echo '
<div class="rex-addon-output">

  <div class="rex-area-col-2">
    <div class="rex-area-col-a">
      <div class="rex-area-content">
        <form action="index.php" method="post">
          <input type="hidden" name="page" value="' . $mypage . '" />
          <input type="hidden" name="clang" value="' . $clang . '" />

          ' . $sel_cat->get() . '

          <input type="submit" value="' . $I18N->msg('simple_shop_show') . '" />
        </form>
      </div>
    </div>

    <div class="rex-area-col-b">
      <div class="rex-area-content">
        <div class="rex-area-col-2">

          <div class="rex-area-col-a">
            <form action="index.php" method="post">
              <input type="hidden" name="clang" value="'.$clang.'" />
              <input type="hidden" name="page" value="'.$mypage.'" />
              <input type="hidden" name="category_id" value="0" />
              <input type="submit" value="' . $I18N->msg('simple_shop_show_all') . '" />
            </form>
          </div>

          <div class="rex-area-col-b">
            <form action="index.php" method="post">
              <input type="hidden" name="clang" value="'.$clang.'" />
              <input type="hidden" name="page" value="'.$mypage.'" />
              <input type="hidden" name="category_id" value="-1" />
              <input type="submit" value="' . $I18N->msg('simple_shop_show_without_category') . '" />
            </form>
          </div>
        </div>

      </div>
     </div>

   </div>
</div>
';




// ***************************************************************************** Produktdefinitionen

// TODO: backend_image
// clang   	int(11)  	   	   	No   	0   	   	  Change   	  Drop   	  Primary   	  Index   	  Unique   	 Fulltext
// path  	varchar(255) 	latin1_swedish_ci 	  	No  	  	  	Change 	Drop 	Primary 	Index 	Unique 	Fulltext

// text|prices|Preise nach Menge (1=1000,10=900 => 1 = 10 EUR, ab 10 = 9 EUR/VE)

$form_data = '

fieldset||' . $I18N->msg('simple_shop_product') . '

hidden|page|simpleshop|REQUEST|no_db
hidden|category_id|'.$category_id.'|REQUEST|no_db
hidden|vt|-

select|status|Status|Online=1;Offline=0|0

text|prio|' . $I18N->msg('simple_shop_priority') . '


textarea|name|' . $I18N->msg('simple_shop_product_name') . '
text|article_number|' . $I18N->msg('simple_shop_product_code') . '

simple_shop_category|categories|' . $I18N->msg('simple_shop_categories') . '

fieldset||' . $I18N->msg('simple_shop_description') . '

textarea|description_short|' . $I18N->msg('simple_shop_short_description') . '
textarea|description_long|' . $I18N->msg('simple_shop_long_description') . '
textarea|description_format|' . $I18N->msg('simple_shop_description_of_the_format') . '

text|ve|' . $I18N->msg('simple_shop_package_unit') . '

select|vat|' . $I18N->msg('simple_shop_vat') . '|' . $REX['ADDON']['simple_shop']['settings']['tax_rates'] . '|

fieldset||' . $I18N->msg('simple_shop_prices') . '

be_table|prices|' . $I18N->msg('simple_shop_price_label') . '|2|' . $I18N->msg('simple_shop_amount') . ',' . $I18N->msg('simple_shop_price_per_package_unit') . '

fieldset||' . $I18N->msg('simple_shop_amount_per_order') . '

text|order_min|' . $I18N->msg('simple_shop_order_min') . '
text|order_max|' . $I18N->msg('simple_shop_order_max') . '

be_table|order_amounts|' . $I18N->msg('simple_shop_order_amounts') . '|2|' . $I18N->msg('simple_shop_amount') . ',' . $I18N->msg('simple_shop_description') . '

fieldset||' . $I18N->msg('simple_shop_images') . '

be_mediapool|image|' . $I18N->msg('simple_shop_image') . '
be_medialist|images|' . $I18N->msg('simple_shop_images') . '

fieldset||' . $I18N->msg('simple_shop_other') . '

be_manager_relation|discount_group_ids|' . $I18N->msg('simple_shop_discount_groups') . '|rex_shop_product_discount_group|name|1|1

select_sql|amount_group_id|' . $I18N->msg('simple_shop_amount_group') . '|SELECT id, name FROM rex_shop_product_amount_group ORDER BY name|||1|--- ' . $I18N->msg('simple_shop_no_selection') . ' ---

textarea|keywords|' . $I18N->msg('simple_shop_keywords') . '

validate|empty|name|' . $I18N->msg('simple_shop_product_name_error') . '

';


// text|price|Sichtbarer Preis in Cent
// text|price_old|Alter Preis in Cent

// file|image|Produktbild
// product|productrelations
// textarea|description_amount|Beschreibung der Menge

// text|stock_in|Produkt auf Lager
// textarea|stock_info|Lagerinfo

$form_data = trim(str_replace('<br />', '', rex_xform::unhtmlentities($form_data)));
$xform = new rex_xform();
$xform->setDebug(TRUE);
$xform->setFormData($form_data);
$xform->objparams["actions"][] = array('type' => 'showtext', 'elements' => array('action', 'showtext', '', rex_info( $I18N->msg('simple_shop_message_updated') ), '', ),);


$xform->setObjectparams('main_table', 'rex_shop_product'); // fuer db speicherungen und unique abfragen





echo '<div class="rex-addon-output">';


// ***************************************************************************** Produkt kopieren

if ($function == 'copy') {

  $_REQUEST['function'] = "add";
  
	$form_data .= '
hidden|function|add|REQUEST|no_db';

	$form_data = trim(str_replace('<br />', '', rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform();
	// $xform->setDebug(TRUE);
	$xform->setFormData($form_data);

  $xform->objparams['actions'][] = array('type' => 'showtext', 'elements' => array('action', 'showtext', '', rex_info( $I18N->msg('simple_shop_message_product_added') . '<br /><br />' . $I18N->msg('simple_shop_redirect', $refresh_seconds, $page_url) ), '', ),);

	$xform->objparams['actions'][] = array('type' => 'fulltext_value', 'elements' => array('action', 'fulltext_value', 'vt', 'status, name, article_number, description_short, description_long, description_format, vat, prices, keywords'),);

	$xform->objparams['actions'][] = array('type' => 'db', 'elements' => array('action', 'db', 'rex_shop_product', 'id="' . $product_id . '"'),);

	$xform->setObjectparams('main_id', $product_id);
	$xform->setObjectparams('main_where', 'id="' . $product_id . '"');
	$xform->setObjectparams('main_table', 'rex_shop_product'); // fuer db speicherungen und unique abfragen
	$xform->setObjectparams('getdata', true); // Dateien vorher auslesen

	echo $xform->getForm();

  if ($xform->objparams['postactions_executed'] == 1) {
    header('refresh:' . $refresh_seconds . '; url=' . $page_url);
    exit();
  }
}






// ***************************************************************************** Produkt editieren

if ($function == 'edit') {

	$form_data .= '
hidden|function|edit|REQUEST|no_db
hidden|product_id|'.$product_id.'|REQUEST|no_db';

  $form_data = trim(str_replace('<br />', '', rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform();
	// $xform->setDebug(TRUE);
	$xform->setFormData($form_data);

  $xform->objparams['actions'][] = array('type' => 'showtext', 'elements' => array('action', 'showtext', '', rex_info( $I18N->msg('simple_shop_message_product_updated') . '<br /><br />' . $I18N->msg('simple_shop_redirect', $refresh_seconds, $page_url) ), '', ),);

  $xform->objparams['actions'][] = array('type' => 'fulltext_value', 'elements' => array('action', 'fulltext_value', 'vt', 'status, name, article_number, description_short, description_long, description_format, vat, prices, keywords'),);

  $xform->objparams['actions'][] = array('type' => 'db', 'elements' => array('action', 'db', 'rex_shop_product', 'id="' . $product_id . '"'),);

  $xform->setObjectparams('main_id', $product_id);
  $xform->setObjectparams('main_where', 'id="' . $product_id . '"');
  $xform->setObjectparams('main_table', 'rex_shop_product'); // fuer db speicherungen und unique abfragen
  $xform->setObjectparams('getdata', true); // Dateien vorher auslesen

    echo $xform->getForm();

  if ($xform->objparams['postactions_executed'] == 1) {
    header('refresh:' . $refresh_seconds . '; url=' . $page_url);
    exit();
  }
}




// ***************************************************************************** Produkt hinzuf�gen

if ($function == 'add') {

	$form_data .= '
hidden|function|add|REQUEST|no_db';

  $form_data = trim(str_replace('<br />', '', rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform();
	//$xform->setDebug(TRUE);
	$xform->setFormData($form_data);

  $xform->objparams['actions'][] = array('type' => 'showtext', 'elements' => array('action', 'showtext', '', rex_info( $I18N->msg('simple_shop_message_product_added') . '<br /><br />' . $I18N->msg('simple_shop_redirect', $refresh_seconds, $page_url) ), '', ),);

  $xform->objparams['actions'][] = array('type' => 'fulltext_value', 'elements' => array('action', 'fulltext_value', 'vt', 'status, name, article_number, description_short, description_long, description_format, vat, prices, keywords'),);

  $xform->objparams['actions'][] = array('type' => 'db', 'elements' => array('action', 'db', 'rex_shop_product'),);

	$xform->setObjectparams('main_table', 'rex_shop_product'); // fuer db speicherungen und unique abfragen


	echo $xform->getForm();

  if ($xform->objparams['postactions_executed'] == 1) {
    header('refresh:' . $refresh_seconds . '; url=' . $page_url);
    exit();
  }
}







// ***************************************************************************** Produktformularausgabe







// ***************************************************************************** Produktliste

//---------------------------------- Online / Offline switch
if($function == 'online_article') {

	$sql = rex_sql::factory();
  $sql->setTable('rex_shop_product');
  $sql->setWhere('id = '.$product_id);
  $sql->setValue('status', 1);
  $sql->update();
	$function = '';
}
if($function == 'offline_article'){

  $sql = rex_sql::factory();
  $sql->setTable('rex_shop_product');
  $sql->setWhere('id = '.$product_id);
  $sql->setValue('status', 0);
  $sql->update();
	$function = '';
}
//----------------------------------- Artikel loeschen
if($function == 'delete_article') {

  $sql = rex_sql::factory();
  $sql->setTable('rex_shop_product');
  $sql->setWhere('id = '.$product_id);
  if ($sql->delete()) {
    echo rex_info($I18N->msg('simple_shop_product_deleted'));
  }
  else {
    echo rex_warning($I18N->msg('simple_shop_error') . ' ' . $I18N->msg('simple_shop_product_has_not_been_deleted'));
  }

	$function = '';
}

if($function == '') {

	echo	'
	<table class="rex-table">
	  <colgroup>
	    <col width="40" />
	    <col width="40" />
	    <col width="200" />
	    <col width="*" />
	    <col width="51" />
	    <col width="50" />
	    <col width="50" />
	    <col width="50" />
	  </colgroup>
	  <thead>
      <tr>
        <th class="rex-icon"><a class="rex-i-element rex-i-article-add" href="index.php?page=simple_shop&function=add&category_id=' . $category_id . '"><span class="rex-i-element-text">' . $I18N->msg('article_add') . '</span></a></th>
        <th>'.$I18N->msg('simple_shop_header_priority').'</th>
        <th>'.$I18N->msg('simple_shop_header_article').'</th>
        <th>'.$I18N->msg('simple_shop_short_description').'</th>
        <th colspan="4">'.$I18N->msg('simple_shop_header_status_function').'</th>
      </tr>
     </thead>
     <tbody>
				';

	if(isset($category_id)) {
	
		//---------------------------------- Liste der Artikel
		$articles = rex_shop_category::getProductList($category_id, true, 'prio, id');

		foreach($articles as $article) {
      echo '
				<tr>

				<td class="rex-icon"><a class="rex-i-element rex-i-article" href="index.php?page=simple_shop&function=edit_article&product_id='.$article->getId().'&category_id='.$category_id.'"><span class="rex-i-element-text">' . $I18N->msg('simple_shop_product_edit') . '</span></a></td>
				<td>' . htmlspecialchars($article->getPrio()) . '</td>
				<td>' . htmlspecialchars($article->getName()) . '</td>
				<td>' . htmlspecialchars($article->getValue('description_short')) . '</td>
				<td><a href="index.php?page=simple_shop&function=edit&product_id='.$article->getId().'&category_id='.$category_id.'">' . $I18N->msg('simple_shop_edit') . '</td>';


      echo '<td><a href="index.php?page=simple_shop&function=copy&product_id='.$article->getId().'&category_id='.$category_id.'">' .$I18N->msg('simple_shop_copy') . '</a></td>';


      $article_status = '';
      if ($article->getStatus() == 0) {
        $article_status = '<a class="rex-offline" href="index.php?page=simple_shop&product_id='.$article->getId().'&function=online_article&category_id='.$category_id.'">' . $I18N->msg('simple_shop_status_offline') . '</a>';
      }
      elseif($article->getStatus() == 1) {
        $article_status = '<a class="rex-online" href=index.php?page=simple_shop&product_id='.$article->getId().'&function=offline_article&category_id='.$category_id.'">' .$I18N->msg('simple_shop_status_online') . '</a>';
      }
      echo '<td>'.$article_status.'</td>';

      echo '<td><a href="index.php?page=simple_shop&function=delete_article&product_id='.$article->getId().'&category_id='.$category_id.'" onclick="return confirm(\'' . $I18N->msg('simple_shop_product_really_delete') . '\')">' .$I18N->msg('simple_shop_delete') . '</a></td>';

      echo '</tr>';
		}
	}
	echo '</tbody></table>';
}

echo '</div>';
