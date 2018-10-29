<?php require_once("src/classes/Sticker.class.php"); ?>

<div class='anounce'>
	Stickers
</div>
<span class="col-no" onclick="filter_nav(document.querySelector('.filter'))">^</span>
<?php 
	if(isset($payload["filter_step"])){
		$pos = intval($payload["filter_step"]);
		$fills = Sticker::get()->where("id > $pos")->limit(3)->send();
	}else
		$fills = Sticker::get()->limit(3)->send();
	if($fills){
	if(!is_array($fills))
		$fills = [$fills];
	
		foreach($fills as $item)
			include("parts/filter.php");
		}
			?>
<span class="col-no" onclick="filter_nav(document.querySelector('.filter:last-of-type'))">v</span>