<?php
class ShoppingCartClass 
{

    var $contents, $total, $total_weight;
	
	//initials class
	function ShoppingCartClass() 
	{
		$this->reset();
	}


	//reset shopping cart
	function reset() 
	{
		$this->contents = array();
		$this->total = 0;
	}


	//add a product to cart
	function add_cart($name, $quantity, $price, $arrListProduct, $icon="", $basketid = 0)
	{
		$p_id = $this->check_basket($arrListProduct);
		if (!$p_id)
		{
			$id = time();
			$this->contents[$id]['quantity'] = $quantity;
			$this->contents[$id]['price'] = $price;
			$this->contents[$id]['name'] = $name;
			$this->contents[$id]['id'] = $id;
			$this->contents[$id]['icon'] = $icon;
			$this->contents[$id]['basketid'] = $basketid;			
			$this->contents[$id]['basket'] = $arrListProduct;
			
			//$this->update_basket($id, $quantity);
		}
		else
		{
			$this->contents[$p_id]['quantity'] += $quantity;
			//$this->update_basket($p_id, $this->contents[$p_id]['quantity']);
		}
	}
	
	function update_basket($id, $quantity)
	{
		for ($i=0; $i<count($this->contents[$id]['basket']); $i++)
		{				
			$this->contents[$id]['basket'][$i]['quantity'] *= $quantity;
		}
	}
	
	function check_basket($arrList)
	{
		foreach ($this->contents as $id=>$product) 
		{				
			$diff = false;
			for ($i=0; $i<count($product['basket']); $i++)
			{
				if ($product['basket'][$i]['id'] != $arrList[$i]['id'] || $product['basket'][$i]['quantity'] != $arrList[$i]['quantity'])
					$diff = true;
			}

			if ($diff == false)
				return $id;
		}
		
		return 0;
	}
	
	function get_key($product_id, $color, $size)
	{
		foreach ($this->contents as $id=>$product) 
		{							
			foreach ($product["color"] as $col=>$quantity)
			{
				if ($col == $color && $product_id == $product['id'] && $size == $product['size'])
					return $id;
			}
		}
		return false;
	}


	//add more quantity of products in cart

    function add_quantity($product_id, $color, $quantity, $size)
	{
		$id = $this->get_key($product_id, $color, $size);
		if ($id != false)
		{
			$this->contents[$id]["color"]["$color"] += $quantity;
			$this->contents[$id]['quantity'] += $quantity;
		}
    }


	//update quantity of products in cart

    function update_quantity($product_id, $quantity, $color, $size)
	{
		$this->contents[$product_id]["color"]["$color"] = $quantity;
		$this->contents[$product_id]['quantity'] = $quantity;
    }
	
	function count_total_contents() 
	{  
		$total_items = 0;
		if (is_array($this->contents)) 
		{				
			foreach ($this->contents as $product_id=>$product) 
			{
				$total_items+= $product['quantity'];
			}
		}

		return $total_items;
    }


	//get total number of items in cart  

    function count_contents() 
	{  
		$total_items = 0;
		if (is_array($this->contents)) 
		{				
			foreach ($this->contents as $product_id=>$product) 
			{
				if ($product_id == -1)
					continue;
					
				foreach ($product["color"] as $color=>$quantity)
					$total_items += $quantity;
			}
		}

		return $total_items;
    }
	
	
	//check if a product is in cart

    function in_cart($product_id) 
	{
		if (isset($this->contents[$product_id])) 
		{
			return true;
		} else {
			return false;
		}
    }


	//remove a product from cart

    function remove($product_id) 
	{
		unset($this->contents[$product_id]);
    }





	//remove all products from cart

    function remove_all() 
	{
		$this->reset();
    }


	//get a string list id of products

    function get_product_id_list() 
	{
		$product_id_list = '';
		$comas = "";		
		foreach ($this->contents as $id=>$product) 
		{				
			for ($i=0; $i<count($product['basket']); $i++)
			{
				if ($product['basket'][$i]['id']>0){
					$product_id_list .= $comas . $product['basket'][$i]['id'];
					$comas = ",";
				}
			}			
		}
		return $product_id_list;
    }



	

	//get total amount of the cart

    function calculate() 
	{
		$this->total = 0;

		foreach ($this->contents as $product_id=>$product)
		{
			$this->total += $product['price'] * $product['quantity'];
		}
    }
	
	
	function new_calculate_weight()
	{
		$total_item = $this->count_contents();
		$this->total_weight = 2 * $total_item;

		return $this->total_weight;
	}
	
	    
    function getTotalTax()
    {
		$tax_gst = $this->calculate_total_tax() * TAX_GST;
		$total = $this->total + $tax_gst;
		return $total;
    }


	function total_weight()
	{
		$total_item = $this->count_contents();
		$total_weight = 2 + ($total_item-1);

		return $total_weight;
	}


	function total_shipping($country)
	{

		$total_item = $this->count_contents();

		switch ($country)
		{
			case 1:
				$total_shipping = 20 + ($total_item-1);
				break;

			case 2:
				$total_shipping = 25 + ($total_item-1) * 2;
				break;
				
			case 4:
				$total_shipping = 0;
				break;

			default:
				$total_shipping = 30 + ($total_item-1) * 5;
				break;
		}

		return $total_shipping;
	}   
	
	
	//get total amount of the cart

    function is_certificate_gift() 
	{
		foreach ($this->contents as $product_id=>$product)
		{
			if ($product_id != -1 && count($product["color"]) > 0)
				return false;
		}
				
		return true;
    }
	
	function is_not_certificate_gift() 
	{
		foreach ($this->contents as $product_id=>$product)
		{
			if ($product_id == -1)
				return true;
		}
				
		return false;
    }
	
	//get total amount of the cart except gift certificate for calculating tax

    function calculate_total_tax() 
	{
		$total = 0;

		foreach ($this->contents as $product_id=>$product)
		{
			if ($product_id == -1)
				continue;
					
			foreach ($product["color"] as $color=>$quantity)
			{				
				$price = $product['price'];
				$subtotal = $price * $quantity;
				$total += $subtotal;
			}
		}
		return $total;
    }
	
	function get_quantity_product($id) 
	{  
		$total_items = 0;
		if (is_array($this->contents)) 
		{				
			foreach ($this->contents as $product_id=>$product) 
			{
				if ($product_id == -1)
					continue;
				
				if ($product['id'] == $id)
				{
					foreach ($product["color"] as $color=>$quantity)
						$total_items += $quantity;
				}
			}
		}

		return $total_items;
    }
	
	function has_promotion_product()
	{
		foreach ($this->contents as $product_id=>$product)
			foreach ($product["color"] as $color=>$quantity)
			{
				if ($product['price'] == 0)
					return true;
			}
			
		return false;
    }
	
	function remove_promotion_product()
	{
		foreach ($this->contents as $product_id=>$product)
			foreach ($product["color"] as $color=>$quantity)
			{
				if ($product['price'] == 0)
				{
					unset($this->contents[$product_id]);
					break;
				}
			}		
	}
}

?>