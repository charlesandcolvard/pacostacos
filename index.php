<?php
	error_reporting(-1);
	ini_set('display_errors', 1);

	require_once('order.php');
	$oOrder = new Order();
	$bIsPost = false;

	$aProducts = $oOrder->getAllProducts();

	if (!empty($_POST['submit'])) {
		$bIsPost = true;
		$oOrder->calculateOrder();
		$aErrors = $oOrder->getErrors();
	}
?>
<html>
	<head>
		<title>Pacos Tacos</title>

		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" crossorigin="anonymous">

		<style>

			body {
				background: url("http://scibosnian.com/wp-content/uploads/2017/12/Carnitas_Tacos.jpg") no-repeat center center fixed;
				background-size: cover;
			}

			h1 {
				color: #fff;
				text-align: center;
				text-shadow: 2px 2px 8px #222;
			}

			.container {
				padding-top: 50px;
			}

			form {
				background: rgba(22, 22, 22, 0.6);
				border-radius: 0.5em;

				padding:25px;
			}

			form:after {
				background: rgba(22, 22, 22, 0.4);
				border-radius: 0.5em;
				padding: 30px;
			}

			.table td {
				border-top: 1px solid #555;
			}

			tbody tr {
				background: transparent;
				color: #fff;
			}

			tbody tr:first-child td {
				border-top: 0;
			}

			tbody tr.product-row td:first-child {
				font-size: 1.5em;
			}

			tbody tr:not(.product-row) {
				background: transparent;
				color: #e57784;
			}

			tfoot td {
				font-weight: 700;
			}

			tfoot tr {
				color: #dbf1b7;
			}

			tfoot tr + tr {
				color: #bce477;
			}

			tfoot tr + tr + tr {
				color: #9dd737;
			}

		</style>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.slim.min.js" crossorigin="anonymous"></script>

		<!-- Latest compiled and minified JavaScript -->
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class="container">
			<div class="col-md-8 col-sm-12">
				<h1>Pacos Tacos</h1>

				<?php if (!empty($aProducts)): ?>
					
				<form method="post" action="/" class="control-group needs-validation" novalidate>

					<table class="table">
						<tbody>
							<?php
								foreach ($aProducts as $oProduct):
							?>

							<tr class="product-row">
								<td><?php echo $oProduct->getName(); ?></td>
								<td><?php echo $oProduct->getFormattedPrice(); ?>/ea</td>
								<td><input name="<?php echo $oProduct->getAttrName(); ?>" value="<?php echo $oOrder->getCountOrdered($oProduct->getAttrName()); ?>" type="number" class="form-control" id="<?php echo $oProduct->getAttrName(); ?>" oninput="checkProduct(this)"/>
									<span class="invalid-feedback">
									<?php if(isset($aErrors[$oProduct->getAttrName()])): ?>
									<?php echo $aErrors[$oProduct->getAttrName()]['message']; ?>
									<?php else: ?>
										Please enter a valid value.
									<?php endif; ?>
									</span>
								</td>
							</tr>

								<?php
									if ($oProduct->mightWantKetchup()):
								?>

								<tr>
									<td colspan="2">How many <?php echo $oProduct->getName(); ?> would you like ketchup on?</td>
									<td><input name="<?php echo $oProduct->getAttrName(); ?>_ketchup" value="<?php echo $oOrder->getCountOrdered($oProduct->getAttrName().'_ketchup'); ?>" type="number" class="form-control" oninput="checkCondiment(this)" data-parent-product="<?php echo $oProduct->getAttrName(); ?>" />
										<span class="invalid-feedback">
										<?php if(isset($aErrors[$oProduct->getAttrName().'_ketchup'])): ?>
										<?php echo $aErrors[$oProduct->getAttrName().'_ketchup']['message']; ?>
										<?php else: ?>
											Please enter a valid value.
										<?php endif; ?>
										</span>
								</td>
								<tr>

								<?php
									endif;
								?>

							<?php
								endforeach;
							?>
						</tbody>
						<?php if (!empty($bIsPost)): ?>
						<tfoot>
							<tr>
								<td colspan="2">Subtotal</td>
								<td><?php echo $oOrder->getSubTotal(); ?></td>
							</tr>
							<tr>
								<td colspan="2">Tax</td>
								<td><?php echo $oOrder->getTaxTotal(); ?></td>
							</tr>
							<tr>
								<td colspan="2">Grand Total</td>
								<td><?php echo $oOrder->getGrandTotal(); ?></td>
							</tr>
						</tfoot>
						<?php endif; ?>
					</table>
					<input type="submit" class="btn btn-primary pull-right" name="submit" value="Calculate" />
				</form>

				<?php
					else:
				?>

				<h2>Sorry, we are all out of tacos and other non-taco products.</h2>

				<?php
					endif;
				?>
			</div>
		</div>

		<script>

		function checkProduct(input) {
			if (input.value * 1 > 0) {
				input.setCustomValidity("");
			}
			else {	
				input.setCustomValidity("Please enter a valid value.");
			}
		}

		function checkCondiment(input) {
			var productInput = document.getElementById(input.dataset.parentProduct);
			if (input.value * 1 > 0 && input.value <= productInput.value) {
				input.setCustomValidity("");
			}
			else {	
				input.setCustomValidity("Please enter a valid value.");
			}
		}

		(function() {
		  'use strict';

		  window.addEventListener('load', function() {
		    // Fetch all the forms we want to apply custom Bootstrap validation styles to
		    var forms = document.getElementsByClassName('needs-validation');
		    // Loop over them and prevent submission
		    var validation = Array.prototype.filter.call(forms, function(form) {
		      form.addEventListener('submit', function(event) {
		        if (form.checkValidity() === false) {
		          event.preventDefault();
		          event.stopPropagation();
		        }
		        form.classList.add('was-validated');
		      }, false);
		    });
		  }, false);
		})();
		</script>
	</body>
</html>
