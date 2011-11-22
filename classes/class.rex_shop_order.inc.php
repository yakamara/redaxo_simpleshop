<?php

class rex_shop_order{
	
	var $_product;
	var $_user;
	var $_status;
	var $_overallsum;
	var $_name;
	var $_mailtxt;
	
	function rex_shop_order(){

		$this->_status 		= "0";
        $this->_overallsum 	= "0";
        $this->_name 		= "";
        $this->_mailtxt 	= "";
        
 	}
	
	
	function setUser($uid){

		$this->_user = $uid;

	}

    function setStatus($status){

		$this->_status= $status;

 	}

    function setName($name){

		$this->_name= $name;

	}

	function setMailtext($mailtxt){

		$this->_mailtxt= $mailtxt;

 	}
	
	function addProduct($pid, $name, $amount=1, $price){

		$this->_product['pid'][] = $pid;
		$this->_product['name'][] = $name;
		$this->_product['amount'][] = $amount;
		$this->_product['price'][] = $price;
		$this->_product['userId'][] = $userId;

		$this->_overallsum = $this->_overallsum + $price;
	}

	function insertOrder(){
		
		$sql = new sql;
		$sql->debugsql=0;
		$sql->setTable("rex_4_order");
		$sql->setValue("overallsum", $this->_overallsum);
		$sql->setValue("status", $this->_status);
		$sql->setValue("date", date("Y-m-d H:i:s"));
		$sql->setValue("name", $this->_name);
		$sql->setValue("mailtext", $this->_mailtxt);
		$sql->insert();
	
		if($sql->error == ""){
			$order_id = $sql->last_insert_id;
		
			$sql->flush();
			$counter = 0;
			if(is_array($this->_product)){

				for($i=0; $i<count($this->_product['pid']);$i++ ){

	                $sql->setTable("rex_4_order_product");
	                $sql->setValue("order_id", $order_id);
	                $sql->setValue("product_id", $this->_product['pid'][$i]);
	                $sql->setValue("product_name", $this->_product['name'][$i]);
	                $sql->setValue("amount", $this->_product['amount'][$i]);
	                $sql->setValue("price", $this->_product['price'][$i]);
					$sql->insert();
					$sql->flush();

					if($sql->error == ""){
						$counter++;
					}
		  		}
			}

			if($counter == count($this->_product['pid'])){
				return true;
			}else{
				return false;
	  		}
		}else{

			return false;

  		}
		
 	}

}
?>
