<?php

$setting_shop_category = 44;

//echo '<div class="rex-addon-output">';


$function = "";
if (isset($_REQUEST["function"])) $function = $_REQUEST["function"];

$product_id = "";
if (isset($_REQUEST["product_id"])) $product_id = $_REQUEST["product_id"];





// ***************************************************************************** Produkt Kategorieliste

// -----------------------> zugriff auf categorien
function add_cat_options( &$select, &$cat, &$cat_ids, $groupName = '') {
    if( empty( $cat)) {
        return;
    }

    $cat_ids[] = $cat->getId();
    $select->addOption($cat->getName(),$cat->getId(), $cat->getId(),$cat->getParentId());
    $childs = $cat->getChildren();

    if ( is_array( $childs)) {
        foreach ( $childs as $child) {
            add_cat_options( $select, $child, $cat_ids, $cat->getName());
        }
    }
}

// ----------------------->  Suche der Artikel über die Kategorien

$category_id = 0;
$category_id = '';
if (isset($_REQUEST["category_id"])) $category_id = $_REQUEST["category_id"];

$sel_cat = new rex_select;
$sel_cat->setSize(1);
$sel_cat->setAttribute('class',"rxshp-wdth");
$sel_cat->setName("category_id");
$sel_cat->setId("category_id");
$sel_cat->setSelected($category_id);
$STYLE= "onchange='document.forms[0].submit();'";
$sel_cat->addOption("Bitte Kategorie auswählen","0");
$cat_ids = array();
if ($rootCat = OOCategory::getCategoryById($setting_shop_category))
{
    add_cat_options( $sel_cat, $rootCat, $cat_ids);
}

echo '<h2>'.$I18N_SIMPLE_SHOP->msg("product_overview").'</h2>';

echo '
<table class="rex-table">
	<tr>
		<td class="rex-icon"></td>
		<td style="vertical-align: middle;">
      <form action="index.php" method="post" name="catsearch">
        <input type="hidden" name="page" value="'.$mypage.'" />
        <input type="hidden" name="clang" value="'.$clang.'" />
	   	  '.$sel_cat->get().'
  		  <input type="submit" name="cs" value="'.$I18N_SIMPLE_SHOP->msg("show").'" />
			</form>
		</td>
    <td style="vertical-align: middle;">
      <form action="index.php" method="post" name="catsearch">
        <input type="hidden" name="clang" value="'.$clang.'" />
        <input type="hidden" name="page" value="'.$mypage.'" />
        <input type="hidden" name="category_id" value="" />
        <input type="submit" name="cs" value="'.$I18N_SIMPLE_SHOP->msg("show_all").'" />
      </form>
    </td>
    
    <td style="vertical-align: middle;">
      <form action="index.php" method="post" name=catsearch>
        <input type="hidden" name="clang" value="'.$clang.'" />
        <input type="hidden" name="page" value="'.$mypage.'" />
        <input type="hidden" name="category_id" value="0" />
        <input type=submit name="cs" value="'.$I18N_SIMPLE_SHOP->msg("show_wo_cat").'" />
      </form>
    </td>
 	</tr>
</table>';




// ***************************************************************************** Produktdefinitionen

// TODO: backend_image
// clang   	int(11)  	   	   	No   	0   	   	  Change   	  Drop   	  Primary   	  Index   	  Unique   	 Fulltext
// path  	varchar(255) 	latin1_swedish_ci 	  	No  	  	  	Change 	Drop 	Primary 	Index 	Unique 	Fulltext

// text|prices|Preise nach Menge (1=1000,10=900 => 1 = 10 EUR, ab 10 = 9 EUR/VE)

$form_data = '

hidden|page|simpleshop|REQUEST|no_db
hidden|category_id|'.$category_id.'|REQUEST|no_db
hidden|vt|-

select|status|Status|Online=1;Offline=0|0

textarea|name|Produktbezeichnung
text|article_number|Produkt/Bestellnummer

simple_shop_category|categories|Kategorien|69

textarea|description_short|Kurzbeschreibung
textarea|description_long|Langbeschreibung
textarea|description_format|Beschreibung des Formates

text|ve|VE / Verpackungseinheit

select|vat|Steuersatz|19%=19;7%=7|19

be_table|prices|(Staffel)preise in Euro z.B. "11.23" |2|Menge,Preis/VE

text|order_min|Minimum Anzahl bei Bestellung
text|order_max|Maximale Anzahl bei einer Bestellung

be_table|order_amounts|Auswahlmöglichkeiten bei Anzahl|2|Anzahl,Beschreibung

be_mediapool|image|Bild

text|prio|Priorität

select_multiple_sql|rex_shop_rel_product_discountgroup|product_id|group_id|Rabattgruppen:|select * from rex_shop_product_discount_group where status>0 order by name|id|name

select_single_sql|amount_group_id|Mindestbestellmenge Gruppe:|--- keine Auswahl ---|select * from rex_shop_product_amount_group order by name|id|name

textarea|keywords|Suchbegriffe

validate|notEmpty|name|Bitte geben Sie die Produktbezeichnung ein

';


$form_data = '

hidden|page|simpleshop|REQUEST|no_db
hidden|category_id|'.$category_id.'|REQUEST|no_db
hidden|vt|-

select|status|Status|Online=1,Offline=0|0

textarea|name|Produktbezeichnung
text|article_number|Produkt/Bestellnummer

simple_shop_category|categories|Kategorien|44

textarea|description_short|Kurzbeschreibung
textarea|description_long|Langbeschreibung
textarea|description_format|Beschreibung des Formates

text|ve|VE / Verpackungseinheit

select|vat|Steuersatz|19%=19,7%=7|19

be_table|prices|(Staffel)preise in Euro z.B. "11.23" |2|Menge,Preis/VE

text|order_min|Minimum Anzahl bei Bestellung
text|order_max|Maximale Anzahl bei einer Bestellung

be_table|order_amounts|Auswahlmöglichkeiten bei Anzahl|2|Anzahl,Beschreibung

be_mediapool|image|Bild

text|prio|Priorität

select_multiple_sql|rex_shop_rel_product_discountgroup|product_id|group_id|Rabattgruppen:|select * from rex_shop_product_discount_group where status>0 order by name|id|name

select_single_sql|amount_group_id|Mindestbestellmenge Gruppe:|--- keine Auswahl ---|select * from rex_shop_product_amount_group order by name|id|name

textarea|keywords|Suchbegriffe

validate|notEmpty|name|Bitte geben Sie die Produktbezeichnung ein


';

// text|price|Sichtbarer Preis in Cent
// text|price_old|Alter Preis in Cent

// file|image|Produktbild
// product|productrelations
// textarea|description_amount|Beschreibung der Menge

// text|stock_in|Produkt auf Lager
// textarea|stock_info|Lagerinfo

$form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));
$xform = new rex_xform;
$xform->setDebug(TRUE);
$xform->setFormData($form_data);
$xform->objparams["actions"][] = array("type" => "showtext","elements" => array("action","showtext",'','<p style="padding:20px;color:#f90;">Vielen Dank für die Aktualisierung</p>',"",),);


/*
$xform->objparams["actions"][] = array("type" => "fulltext_value","elements" => array("action","fulltext_value","vt","status,name,article_number,description_short,description_long,description_format,vat,prices,keywords"),);
*/




// $xform->setRedaxoVars('',''); 
// if ("REX_VALUE[10]" != "") $xform->setGetdata(true); // Datein vorher auslesen ?
// $xform->setObjectparams("answertext","REX_VALUE[6]"); // Antworttext
$xform->setObjectparams("main_table","rex_shop_product"); // für db speicherungen und unique abfragen







// ***************************************************************************** Produkt kopieren

if ($function=="copy")
{
	$_REQUEST["function"] = "add";
	$form_data .= '
hidden|function|add|REQUEST|no_db';

	$form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform;
	// $xform->setDebug(TRUE);
	$xform->setFormData($form_data);

	$xform->objparams["actions"][] = array("type" => "showtext","elements" => array("action","showtext",'','<p style="padding:20px;color:#f90;">Vielen Dank für die Aktualisierung</p>',"",),);

	$xform->objparams["actions"][] = array("type" => "fulltext_value","elements" => array("action","fulltext_value","vt","status,name,article_number,description_short,description_long,description_format,vat,prices,keywords"),);

	$xform->objparams["actions"][] = array("type" => "db","elements" => array("action","db","rex_shop_product","id=$product_id"),);

	$xform->setObjectparams("main_id","$product_id");
	$xform->setObjectparams("main_where","id=$product_id");
	$xform->setObjectparams("main_table","rex_shop_product"); // für db speicherungen und unique abfragen
	$xform->setObjectparams('getdata', true); // Datein vorher auslesen

  echo '<div id="rex-addon-editmode" class="rex-form">';
	echo $xform->getForm();
	echo '</div>';

	// rex_xform::showHelp();
}






// ***************************************************************************** Produkt editieren

if ($function=="edit")
{

	$form_data .= '
hidden|function|edit|REQUEST|no_db
hidden|product_id|'.$product_id.'|REQUEST|no_db';

	$form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform;
//	$xform->setDebug(TRUE);
	$xform->setFormData($form_data);

	$xform->objparams["actions"][] = array("type" => "showtext","elements" => array("action","showtext",'','<p style="padding:20px;color:#f90;">Vielen Dank für die Aktualisierung</p>',"",),);

	$xform->objparams["actions"][] = array("type" => "fulltext_value","elements" => array("action","fulltext_value","vt","status,name,article_number,description_short,description_long,description_format,vat,prices,keywords"),);

	$xform->objparams["actions"][] = array("type" => "db","elements" => array("action","db","rex_shop_product","id=$product_id"),);

	$xform->setObjectparams("main_id","$product_id");
	$xform->setObjectparams("main_where","id=$product_id");
	$xform->setObjectparams("main_table","rex_shop_product"); // für db speicherungen und unique abfragen
//	$xform->setGetdata(true); // Datein vorher auslesen
	$xform->setObjectparams('getdata', true); // Datein vorher auslesen

  echo '<div id="rex-addon-editmode" class="rex-form">';
	echo $xform->getForm();
	echo '</div>';

	// rex_xform::showHelp();
}




// ***************************************************************************** Produkt hinzufügen

if ($function=="add")
{

	$form_data .= '
hidden|function|add|REQUEST|no_db';

	$form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform;
//	$xform->setDebug(TRUE);
	$xform->setFormData($form_data);

	$xform->objparams["actions"][] = array("type" => "showtext","elements" => array("action","showtext","",'<p style="padding:20px;color:#f90;">Vielen Dank für die Aktualisierung</p>',"",),);

	$xform->objparams["actions"][] = array("type" => "fulltext_value","elements" => array("action","fulltext_value","vt","status,name,article_number,description_short,description_long,description_format,vat,prices,keywords"),);

	$xform->objparams["actions"][] = array("type" => "db","elements" => array("action","db","rex_shop_product"),);

	$xform->setObjectparams("main_table","rex_shop_product"); // für db speicherungen und unique abfragen
	
  echo '<div id="rex-addon-editmode" class="rex-form">';
	echo $xform->getForm();
	echo '</div>';

	// rex_xform::showHelp();
}







// ***************************************************************************** Produktformularausgabe







// ***************************************************************************** Produktliste

//---------------------------------- Online / Offline switch
if($function=="online_article"){

	$sql=new sql;
	$sql->debugsql = 1;
	$sql->setQuery('update rex_shop_product set status=1 WHERE id='.$product_id);
	$function = "";
}
if($function=="offline_article"){

	$sql=new sql;
	$sql->setQuery('update rex_shop_product set status=0 WHERE id='.$product_id);
	$function = "";
}
//----------------------------------- Artikel löschen
if($function=="delete_article"){

	$sql=new sql;
	$sql->setQuery('delete from rex_shop_product WHERE id='.$product_id);
$message_corpus = "";
	if($sql->error == ""){
		echo preg_replace("!##msg##!",  $I18N_SIMPLE_SHOP->msg("product_deleted"), $message_corpus);
	}else{
		echo preg_replace("!##msg##!",  $I18N_SIMPLE_SHOP->msg("error"), $message_corpus);
	}
	$function = "";
}



if($function == "")
{






	echo	'<table class="rex-table">
				<tr>
					<th class=rex-icon><a href="index.php?page=simple_shop&function=add&category_id='.$category_id.'"><img src="media/document_plus.gif" width=16 height=16 border=0 title="'.$I18N->msg("article_add").'" alt="'.$I18N->msg("article_add").'"></a></th>
					<th>Prio</th>
					<th style="width: 300px;">'.$I18N_SIMPLE_SHOP->msg("header_article").'</th>
					<th>'.$I18N_SIMPLE_SHOP->msg("header_edit").'</th>
					<th>'.$I18N_SIMPLE_SHOP->msg("header_status").'</th>
					<th>Func</th>
				</tr>
				';

	if(isset($category_id))
	{
	
		//---------------------------------- Liste der Artikel
		$articles = rex_shop_category::getProductList($category_id);

		foreach($articles as $article)
		{
			echo '
				<tr>

				<td><a href="index.php?page=simple_shop&function=edit_article&product_id='.$article->getId().'&category_id='.$category_id.'"><img src="media/document.gif" border="0" height="16" width="16"></a></td>
				<td>'.htmlspecialchars($article->getPrio()).'</td>
				<td>'.(htmlspecialchars($article->getName())).'</td>
				<td><a href="index.php?page=simple_shop&function=edit&product_id='.$article->getId().'&category_id='.$category_id.'">Produkt editieren</td>';

				
				if ($article->getStatus() == 0)
				{ 
					$article_status = '<a href="index.php?page=simple_shop&product_id='.$article->getId().'&function=online_article&category_id='.$category_id.'" style="color:#f00;">'.$I18N->msg("status_offline").'</a>'; 
				}elseif($article->getStatus() == 1){ 
					$article_status = '<a href=index.php?page=simple_shop&product_id='.$article->getId().'&function=offline_article&category_id='.$category_id.'" style="color:#0f0;">'.$I18N->msg("status_online").'</a>'; 
				}
				echo '<td>'.$article_status.'</td>';


				echo '<td>
					<a href="index.php?page=simple_shop&function=delete_article&product_id='.$article->getId().'&category_id='.$category_id.'">löschen</a>
					| <a href="index.php?page=simple_shop&function=copy&product_id='.$article->getId().'&category_id='.$category_id.'">kopieren</td>';

				echo '</tr>';
		}
	}
	echo '</table>';
}


?>