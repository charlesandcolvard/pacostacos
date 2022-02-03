<?php

require_once('product.php');

class Hamburger extends Product {
	
	function __construct() {
		$this->fPrice = 2.50;
		$this->sName = "Hamburger";
		$this->sAttr = "hamburger";
		$this->bSanePersonMightAddKetchup = true;
	}
}