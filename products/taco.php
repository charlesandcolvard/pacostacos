<?php

require_once('product.php');

class Taco extends Product {
	
	function __construct() {
		$this->fPrice = 1.50;
		$this->sName = "Taco";
		$this->sAttr = "taco";
	}
}