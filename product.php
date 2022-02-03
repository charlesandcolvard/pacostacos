<?php
	setlocale(LC_MONETARY, 'en_US.UTF-8');

	class Product {

		protected $fPrice;
		protected $sName;
		protected $sAttr;
		protected $bSanePersonMightAddKetchup;

		function __construct() {
			$this->bSanePersonMightAddKetchup = false;
		}

		public function getPrice($iQty=1) {
			return $iQty * $this->fPrice;
		}

		public function getFormattedPrice() {
			return money_format('%.2n', $this->fPrice);
		}

		public function getName() {
			return $this->sName;
		}

		public function getAttrName() {
			return $this->sAttr;
		}

		public function mightWantKetchup() {
			return $this->bSanePersonMightAddKetchup;
		}

	}