<?php

//echo '<div class="rex-addon-output">';


$function = "";
if (isset($_REQUEST["function"])) $function = $_REQUEST["function"];

$group_id = "";
if (isset($_REQUEST["group_id"])) $group_id = $_REQUEST["group_id"];


// ***************************************************************************** Produktdefinitionen

// TODO: backend_image
// clang   	int(11)  	   	   	No   	0   	   	  Change   	  Drop   	  Primary   	  Index   	  Unique   	 Fulltext
// path  	varchar(255) 	latin1_swedish_ci 	  	No  	  	  	Change 	Drop 	Primary 	Index 	Unique 	Fulltext

// text|prices|Preise nach Menge (1=1000,10=900 => 1 = 10 EUR, ab 10 = 9 EUR/Stück)

$form_data = '

hidden|page|simpleshop|REQUEST|no_db
hidden|subpage|discountgroups|REQUEST|no_db

select|status|Status|Online=1;Offline=0|0


text|name|Bezeichnung
textarea|description|Beschreibung

html|<h2 style="clear:both;margin:10px 0px;">Wenn folgendes erreicht wurde:</h2>

text|amount|Mindestens diese Menge
html|<p><b>oder</b></p>
text|price|Mindestens dieser Preis

html|<h2 style="clear:both;margin:10px 0px;">Dann folgenden Rabatt geben:</h2>

text|discount_percent|Rabatt in Prozent
html|<p><b>oder</b></p>
text|discount_value|Rabatt als festen Betrag

validate|notEmpty|name|Bitte geben Sie die Gruppenbezeichnung ein

';

$form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));
$xform = new rex_xform;
$xform->setDebug(TRUE);
$xform->setFormData($form_data);
$xform->objparams["actions"][] = array("type" => "showtext","elements" => array("action","showtext",'','<p style="padding:20px;color:#f90;">Vielen Dank für die Aktualisierung</p>',"",),);
// $xform->setRedaxoVars('',''); 
// if ("REX_VALUE[10]" != "") $xform->setGetdata(true); // Datein vorher auslesen ?
// $xform->setObjectparams("answertext","REX_VALUE[6]"); // Antworttext
$xform->setObjectparams("main_table","rex_shop_product_discount_group"); // für db speicherungen und unique abfragen


// ***************************************************************************** Produkt editieren

if ($function=="edit")
{

	$form_data .= '
hidden|function|edit|REQUEST|no_db
hidden|group_id|'.$group_id.'|REQUEST|no_db';

	$form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform;
	// $xform->setDebug(TRUE);
	$xform->setFormData($form_data);

	$xform->objparams["actions"][] = array("type" => "showtext","elements" => array("action","showtext",'','<p style="padding:20px;color:#f90;">Vielen Dank für die Aktualisierung</p>',"",),);
	$xform->objparams["actions"][] = array("type" => "db","elements" => array("action","db","rex_shop_product_discount_group","id=$group_id"),);

	$xform->setObjectparams("main_id","$group_id");
	$xform->setObjectparams("main_where","id=$group_id");
	$xform->setObjectparams("main_table","rex_shop_product_discount_group"); // für db speicherungen und unique abfragen
	$xform->setGetdata(true); // Datein vorher auslesen

	echo $xform->getForm();

	// rex_xform::showHelp();
}




// ***************************************************************************** Produkt hinzufügen

if ($function=="add")
{

	$form_data .= '
hidden|function|add|REQUEST|no_db';

	$form_data = trim(str_replace("<br />","",rex_xform::unhtmlentities($form_data)));
	$xform = new rex_xform;
	// $xform->setDebug(TRUE);
	$xform->setFormData($form_data);

	$xform->objparams["actions"][] = array("type" => "showtext","elements" => array("action","showtext","",'<p style="padding:20px;color:#f90;">Vielen Dank für die Aktualisierung</p>',"",),);
	$xform->objparams["actions"][] = array("type" => "db","elements" => array("action","db","rex_shop_product_discount_group"),);

	$xform->setObjectparams("main_table","rex_shop_product_discount_group"); // für db speicherungen und unique abfragen
	echo $xform->getForm();

	// rex_xform::showHelp();
}







// ***************************************************************************** Produktformularausgabe







// ***************************************************************************** Produktliste


//----------------------------------- Gruppe löschen
if($function=="delete_group"){

	$sql = new rex_sql;
	$sql->setQuery('delete from rex_shop_product_discount_group WHERE id='.$group_id);
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






	echo	'<table class=rex-table border=0 cellpadding=5 cellspacing=1>
				<tr>
					<th class=rex-icon><a href="index.php?page=simple_shop&subpage=discountgroups&function=add"><img src="media/document_plus.gif" width=16 height=16 border=0 title="" alt=""></a></th>
					<th align=left>Gruppe</th>
					<th width=200 align=left>'.$I18N_SIMPLE_SHOP->msg("header_edit").'</th>
					<th align=left width=140>'.$I18N_SIMPLE_SHOP->msg("header_status").'</th>
					<th align=left width=140>Func</th>
				</tr>
				';

	$gg = new rex_sql;
	$gg->setQuery("select * from rex_shop_product_discount_group order by name");
	$gg_array = $gg->getArray();


	if(count($gg_array)>0)
	{
	
		foreach($gg_array as $group)
		{
			echo '
				<tr>
				<td width=30 align=center><a href="index.php?page=simple_shop&subpage=discountgroups&function=edit_group&group_id='.$group["id"].'"><img src="media/document.gif" border="0" height="16" width="16"></a></td>
				<td>'.htmlspecialchars($group["name"]).'</td>
				<td width=250><a href="index.php?page=simple_shop&subpage=discountgroups&function=edit&group_id='.$group["id"].'">Gruppe editieren</td>';

				if ($group["status"] == 0)
				{ 
					$group_status = '<a href="index.php?page=simple_shop&subpage=discountgroups&group_id='.$group["id"].'&function=online_article" style="color:#f00;">'.$I18N->msg("status_offline").'</a>'; 
				}else{ 
					$group_status = '<a href=index.php?page=simple_shop&subpage=discountgroups&group_id='.$group["id"].'&function=offline_article" style="color:#0f0;">'.$I18N->msg("status_online").'</a>'; 
				}
				echo '<td width=153>'.$group_status.'</td>';


				echo '<td width=153><a href="index.php?page=simple_shop&subpage=discountgroups&function=delete_group&group_id='.$group["id"].'">löschen</a></td>';

				echo '</tr>';
		}
	}
	echo '</table>';
}


?>