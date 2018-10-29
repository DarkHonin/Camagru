<?php require_once("src/classes/Sticker.class.php") ?>

<div class='anounce'>
				Stickers
			</div>
			<span class="col-no filter">^</span>
			<?php 
				$fills = Sticker::get()->send();
				if($fills){
				if(!is_array($fills))
					$fills = [$fills];
				
					foreach($fills as $item)
						include("parts/filter.php");
					}
					 ?>
			<span class="col-no filter">v</span>