<?php

require_once('product.php');

class Pizza extends Product {
	
	function __construct() {
		$this->fPrice = 1.50;
		$this->sName = "Pizza";
		$this->sAttr = "pizza";
	}
}