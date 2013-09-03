<?php
class Basket2
{
    var $id;
    var $name;
    var $items;   

    function __construct()
    {
        $this->id = 0;
        $this->name = '';
        $this->items = array();
    }
}

class ShoppingCart
{
	var $cart;
	function Add($id, $name,$quantity,$price)
	{
		if (isset($this->cart) && array_key_exists($id,$this->cart)) 
		{ 
				$this->cart[$id] = array( 			   		
			   		"id" => $id,
					"name" => $name,
					"price"=>$price,
					"quantity" => $cart[$id]["quantity"]+$quantity 
			   );
		} 
	  	else
	  	{
			$this->cart[$id] = array( 		   		
				"id" => $id,
				"name" => $name,
				"price"=>$price,
				"quantity" => $cart[$id]["quantity"]+$quantity 
			); 
	  	}
	}
	function Delete($id)
	{
		if (isset($this->cart) && array_key_exists($id,$this->cart)) 
		{
			unset($cart[$id]);
		}
	}
	function getCart()
	{
		return $this->cart;
	}
}
?>