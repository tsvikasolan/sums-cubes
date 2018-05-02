<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Natural Language Form</title>
		<meta name="description" content="Natural Language Form with custom text input and drop-down lists" />
		<meta name="keywords" content="Natural Language UI, sentence form, text input, contenteditable, html5, css3, jquery" />
		<link rel="shortcut icon" href="../favicon.ico"> 
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script src="js/modernizr.custom.js"></script>
	</head>
	<body class="nl-blurred">
		<?php
		
		if(!isset($_POST['submit'])){
			//NOT returned due to error, so reset session values
			$_SESSION['user_id'] = null;
			$_SESSION['pet_ids'] = null;
		}
		
		
		function mapvalues($string){
			$map = ['dog' => 'cão', 'cat' => 'gato', 'kibble' => 'ração seca', 'canned' => 'comida húmida (enlatada)', 'cooked' => 'alimentação cozinhada (caseira, artesanal, etc)', 'raw' => 'alimentação crua (caseira, artesanal, etc)'];
			return $map[$string];
		}
		
		function field($name, $hint='', $index=null, $options=null, $required=true){
			if(isset($index)){
				$val = $_POST[substr($name, 0, -2)][$index];
			}
			else {
				$val = $_POST[$name];
			}
			if($options == null){
				echo "<input name=\"$name\" type=\"text\" value=\"$val\" placeholder=\"" . ($val ? $val :  "____") . 
					"\"" . ($hint == "" ? '' : "data-subline=\"$hint\"")  . (isset($_POST['submit']) && $required && $val == "" ? 'class="error"' : '') .  "/>";
			}
			else{
				echo "<select name=\"$name\" " . (isset($_POST['submit']) && $required && $val == "" ? 'class="error"' : '') . "><option value=\"\" " . ($val == "" ? "selected" : "") . ">____</option>";	
				foreach($options as $option){
					echo "<option value=\"$option\" " . ($val == $option ? "selected" : "") . ">" . mapvalues($option) . "</option>";
				}
				echo "</select>";
			}
		}
		?>
		<div class="container">
			<!-- <header>
				<img src="petfood-pt-logo.png" />
				<h1>Alimentos para cães e gatos, diretamente para sua casa</h1>
				<h3><a href="#">Como funciona?</a> | <a href="#">Opções de comida</a></h3>
			</header> -->
			<div class="main clearfix">
				<h2>Conte-nos um pouco sobre você e seus animais de estimação:</h2>
				<?php if(isset($_POST['submit'])): ?>
					<h2 class="error">Corrija os campos destacados abaixo:</h2>
				<?php endif; ?>
				<form id="nl-form" class="nl-form" action="form.php" method="post">
					<fieldset>
						Chamo-me <?php field('name','Seu nome, sff'); ?>, meu e-mail é <?php field('email') ?>, e meu codigo postal é <?php field('post_code') ?>.
					</fieldset>
					
					<?php $index = 0; do{ ?>
						<fieldset>
							<?php if($index > 0){ echo "<button class=\"delete\">X</button>"; } ?>
							<input type="hidden" name="pet[]" value="<?php echo (count($_SESSION['pet_ids']) > 0 ? $_SESSION['pet_ids'][$index] : "");  ?>" />
							Tenho <?php if($index > 0) echo "também"; ?> um <?php field('pet_type[]', null, $index , ['dog','cat']); ?>
							, chama-se <?php field('pet_name[]', null, $index) ?>, tem <?php field('pet_weight[]', null, $index) ?> kg, 
							e gostaria de comer <?php field('pet_food_type[]', null, $index, ['kibble','canned','cooked','raw']); ?>
							</select>.
						</fieldset>
					<?php $index++; } while($index < count($_POST['pet'])); ?>
					
					<fieldset>
						<button class="nl-adder">Tenho um outro animal em casa</button>
					</fieldset>
					
					<!--<fieldset>
						Já tenho um código de desconto: <?php field('discount',null,null,null,false); ?>
					</fieldset> -->
					
					<fieldset>
						<button name="submit" class="nl-submit" type="submit">Pronto!</button>
					</fieldset>
					
					<div id="overlay" class="nl-overlay"></div>
					
				</form>
			</div>
		</div><!-- /container -->
		
		<fieldset id="template">
			<button class="delete">X</button>
			<input type="hidden" name="pet[]" value="" />
			Tenho também um <?php field('pet_type[]', null, null , ['dog','cat']); ?>
			, chama-se <?php field('pet_name[]', null, $index) ?>, tem <?php field('pet_weight[]', null, null) ?> kg, 
			e gostaria de comer <?php field('pet_food_type[]', null, null, ['kibble','canned','cooked','raw']); ?>
			</select>.
		</fieldset>
		
		<script src="js/nlform.js"></script>
		<script>
			var nlform = new NLForm( document.getElementById( 'nl-form' ) );
		</script>
	</body>
</html>