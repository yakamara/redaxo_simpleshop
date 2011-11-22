<?php

class rex_xform_simple_shop_basket extends rex_xform_abstract
{

	function enterObject()
	{	
		$output = '';
		$this->params["shop_warning"] = FALSE;
		
		if($this->params['send'] == 1 && isset($_POST['refresh_cart']))
		{
			// Error. Fehlermeldung ausgeben
			$msg = 'Warenkorb wurde aktualisiert!';
			$this->params["warning"][] = $msg;
			$this->params["warning_messages"][] = $msg;
		}

		$productListId = !empty($_SESSION["LAST_PRODUCT_LIST_ART"]) ? $_SESSION["LAST_PRODUCT_LIST_ART"] : 44;

		// überprüft Mindestbestellmengengruppen
		// inkorrekte Produkte werden als Array zurückgegeben
		$wrong_products = rex_shop_basket::checkAmountValues();

		if (count($wrong_products)>0)
		{
			$msg = "";
			foreach($wrong_products as $amount_group_id => $p)
			{
				if ($msg != "") $msg .= '<br />';
				
				$ag = rex_shop::getAmountGroupValue($amount_group_id);
				$msg .= 'Die Mindestbestellmenge der Produktgruppe "'.$ag["name"].'" liegt bei '.$ag["amount"].' VE, die Sie aus den einzelnen Produkten dieser Gruppe auswählen können.';
				
				foreach($p as $pid)
				{
					$a = new rex_shop_product($pid);
					$msg .= '<br />* '.$a->getName();
				}
			}
			$this->params["warning"][] = $msg;
			$this->params["warning_messages"][] = $msg;
			$this->params["shop_warning"] = TRUE;
			
		}

		if (count(rex_shop_basket::getBasket()) < 1)
		{
			$output = '
				<!-- <h2>Warenkorb ist leer</h2> -->
			';
			$msg = 'Warenkorb ist leer, daher kann keine Bestellung abgeschickt werden';
			$this->params["warning"][] = $msg;
			$this->params["warning_messages"][] = $msg;
			
			$this->params["shop_warning"] = TRUE;
		}else
		{

			foreach (rex_shop_basket :: getBasket() as $pid => $pamount)
		    {
		      $product = new rex_shop_product($pid);
		      if(!$product->checkOrderValue($pamount))
		      {
		        $msg = "";
		      	$max = $product->getMaxOrder(); 
		      	if ($max>0 && $product->getMaxOrder()<$pamount) $msg .= 'Bitte beachten Sie die maximale Bestellmenge von '.$max.' VE bei "'.htmlspecialchars($product->getName()).'" ';
		      	$min = $product->getMinOrder(); 
		      	if ($min>0 && $product->getMinOrder()>$pamount) $msg .= 'Bitte beachten Sie die minimale Bestellmenge von '.$min.' VE bei "'.htmlspecialchars($product->getName()).'"';
				$this->params["warning"][] = $msg;
				$this->params["warning_messages"][] = $msg;
		      }
			}




			$output = '
	
				<p class="tx2"><a href="'. rex_getUrl($productListId) .'">zurück zur Produktübersicht</a></p>
				'. rex_shop_basket_viewer::getBasket() .'
	
			';
		}

		
		$this->params["form_output"][$this->getId()] = $output;
		
	}
	
	function getDescription()
	{
		return "simple_shop_basket -> Beispiel: simple_shop_basket";
	}
}

?>