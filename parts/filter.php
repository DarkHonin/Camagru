
<div class="filter col-no" title="<?php echo $item->title ?>" image="<?php echo $item->id ?>" id="<?php echo $item->id ?>" onclick="document.create.userImage.addFilter(this)">
	<img class="icon" src="/assets/img/<?php echo $item->image ?>" id="filter_<?php echo $item->id ?>">
	<span class="title"><?php echo $item->title ?></span>
</div>