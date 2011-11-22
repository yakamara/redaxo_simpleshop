<?php


class rex_shop_utils
{
	
	function formatPrice($price)
	{
		// TODO
		return number_format($price, 2, ',', ' ') . ' &euro;';
	}

}

?>