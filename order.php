<?php
setlocale(LC_MONETARY, 'en_US.UTF-8');

require_once('products/hotdog.php');
require_once('products/taco.php');
require_once('products/hamburger.php');
require_once('products/pizza.php');

class Order {
	protected $bIsCalculated = false;
	protected $fTaxRate = 0.075;

	protected $aProducts = ['hotdog', 'taco', 'hamburger', 'pizza'];
	// protected $aCondiments = ['ketchup'];

	protected $aErrors = [];

	public $fTotalCost = 0;
	public $fTaxCost = 0;
	public $fGrandTotalCost = 0;
	public $iTotalNumOrdered = 0;

	public function calculateOrder() {

		$aProducts = $this->getAllProducts();

		foreach ($aProducts as $oProduct) {
			if (!isset($_POST[$oProduct->getAttrName()])) continue;

			if ($this->validateEntryValidValue($_POST[$oProduct->getAttrName()])) {
				$this->fTotalCost += $oProduct->getPrice($_POST[$oProduct->getAttrName()]);

				if (isset($_POST[$oProduct->getAttrName()."_ketchup"])) {
					if (! $this->validateEntryValidValue($_POST[$oProduct->getAttrName()."_ketchup"]))
						$this->generateError($oProduct, "condiment_valid_value");
					if (! $this->validateEntryLimit($_POST[$oProduct->getAttrName()], $_POST[$oProduct->getAttrName()."_ketchup"]))
						$this->generateError($oProduct, "condiment_more_than_product");
				}
			} else {
				$this->generateError($oProduct);
			}
		}
		
		$this->calculateTax();
		$this->calculateGrandTotal();
	}

	protected function validateEntryValidValue($entry) {
		return is_numeric($entry) && $entry >= 0;
	}

	protected function validateEntryLimit($productEntry, $condimentEntry) {
		return $condimentEntry <= $productEntry;
	}

	protected function generateError($oProduct, $sErrorType="product_valid_value") {
		switch ($sErrorType) {

			case "product_valid_value":
				$this->aErrors[$oProduct->getAttrName()] = [
					"message" => "You cannot order {$_POST[$oProduct->getAttrName()]} {$oProduct->getName()}. Please enter a valid value."
				];
				break;
			case "condiment_valid_value":
				$this->aErrors[$oProduct->getAttrName()."_ketchup"] = [
					"message" => "You cannot order {$_POST[$oProduct->getAttrName()."_ketchup"]} ketchup. Please enter a valid value."
				];
				break;
			case "condiment_more_than_product";
				$this->aErrors[$oProduct->getAttrName()."_ketchup"] = [
					"message" => "You cannot put ketchup on more {$oProduct->getName()} than you ordered. Please enter a valid value."
				];
				break;
			default:
				break;
		}
	}


	protected function calculateTax() {
		$this->fTaxCost	= $this->fTotalCost * $this->fTaxRate;
	}

	protected function calculateGrandTotal() {
		$this->fGrandTotalCost	= $this->fTotalCost + $this->fTaxCost;
	}

	public function getCountOrdered($sType) {
		if (!empty($_POST[$sType])) {
			return $_POST[$sType];
		}

		return 0;
	}

	public function getSubTotal() {
		return money_format('%.2n', $this->fTotalCost);
	}

	public function getTaxTotal() {
		return money_format('%.2n', $this->fTaxCost);
	}

	public function getGrandTotal() {
		return money_format('%.2n', $this->fGrandTotalCost);
	}

	public function getAllProducts() {
		$aProductInstances = [];

		foreach ($this->aProducts as $product) {
			$sClassName = ucfirst($product);
			$aProductInstances[] = new $sClassName;
		}

		return $aProductInstances;
	}

	public function getErrors() {
		return $this->aErrors;
	}
}