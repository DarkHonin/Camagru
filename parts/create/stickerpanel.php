<?php require_once("src/classes/Sticker.class.php") ?>

<div class='anounce'>
				Stickers
			</div>
			<div class="body">
			<?php 
				$fills = Sticker::get()->send();
				if(!is_array($fills))
					$fills = [$fills];
				
					foreach($fills as $item)
						include("parts/filter.php");
					 ?>
			</div>