<?php


class rex_shop_basket
{
	
	function getBasket()
	{
		rex_shop_basket::checkBasket();
		if(!is_array($_SESSION["wk"])) $_SESSION["wk"] = array();
		return $_SESSION["wk"];
	}
	
	function setProductAmount($pid,$product_amount)
	{
		$_SESSION["wk"][$pid] = $product_amount;
		rex_shop_basket::checkBasket();		
	}

	function addToBasket($pid,$product_amount)
	{
		$product = new rex_shop_product($pid);
		if ($product->isValid())
		{
		  $amount = (int) @$_SESSION["wk"][$pid];
			$wk_product_amount = $amount + $product_amount;
			$_SESSION["wk"][$pid] = $product->getValidAmount($wk_product_amount);
			return true;
		}
		return false;
	}

	function deleteFromBasket($pid)
	{
		if(isset($_SESSION["wk"][$pid]))
		{
			unset($_SESSION["wk"][$pid]);
			return true;
		}
		return false;
	}

	function checkBasket()
	{
		$cb = array();
		if (is_array($_SESSION["wk"]))
		{
			foreach($_SESSION["wk"] as $pid => $product_amount)
			{
				$product_amount = (int) $product_amount;
				$product = new rex_shop_product($pid);
				if (!$product->isValid() || $product_amount<1 || $product_amount>100000)
				{
					rex_shop_basket::deleteFromBasket($pid);
				}
			}
			
		}
		return $cb;
	}


	// return: nicht korrekte produktobjekte	
	function checkAmountValues()
	{
		// Alle Produkte auslesen
		// Alle Mindestbestellmengengruppen speichern
		// Alle Mindestbestellmengengruppen prüfen.

		$ag = array();		
		if (is_array($_SESSION["wk"]))
		{
			foreach($_SESSION["wk"] as $pid => $product_amount)
			{
				$product_amount = (int) $product_amount;
				$product = new rex_shop_product($pid);
				if ($product->isValid())
				{
					if ($product->getAmountGroup())
					{
						$ag[$product->getAmountGroup()]["amount"] = $ag[$product->getAmountGroup()]["amount"] + $product_amount;
						$ag[$product->getAmountGroup()]["products"][] = $pid;
					}
					// echo "<br />$pid: amountgroup: ".$product->getAmountGroup()." -- $product_amount";
				}
			}
		}
		
		$cb = array(); // Produkte die zusammen nicht die Mindestmengen haben
		foreach($ag as $amount_group_id => $ps)
		{
			$all_amount = rex_shop::getAmountGroupValue($amount_group_id);
			if($ps["amount"] < $all_amount["amount"])
			{
				foreach($ps["products"] as $p)
				{
					$cb[$amount_group_id][] = $p;
				}
			}
		}
		
		return $cb;
	
	}
	
	
	function getOverallPrice()
	{
		$sum = 0;
		foreach(rex_shop_basket::getBasket() as $pid => $pamount)
		{
			$product = new rex_shop_product($pid);
			$price = $product->getPrice();
			$aprice = $pamount * $price;
			$sum += $aprice;
		}
		return $sum;
	}
	
	function clearBasket()
	{
		$_SESSION["wk"] = array();
	}
	
}

?>