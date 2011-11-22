<?php


class rex_shop
{
	
	function getDiscountGroups()
	{


	}

	function getAmountGroups()
	{
	
	
	}
	
	function getAmountGroupValue($id)
	{
		$r = array();
		$gg = new rex_sql;
		$gg->setQuery('select * from rex_shop_product_amount_group where id='.$id);
		if ($gg->getRows()==1)
		{
			$r["status"] = $gg->getValue("status");
			$r["name"] = $gg->getValue("name");
			$r["description"] = $gg->getValue("description");
			$r["amount"] = $gg->getValue("amount");
			return $r;
		}else
		{
			return $r;
		}
	}

}

?>