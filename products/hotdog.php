<?php

require_once('product.php');

class Hotdog extends Product {
	
	function __construct() {
		$this->fPrice = 1.00;
		$this->sName = "Hotdog";
		$this->sAttr = "hotdog";
		$this->bSanePersonMightAddKetchup = true;
	}
}