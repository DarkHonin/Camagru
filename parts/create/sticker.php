<img 
    class="sticker" 
    sticker_id='<?php echo $sticker->id ?>' 
    type="sticker" 
    src="<?php echo "data:image/png;base64,".$sticker->filter_image?>" 
    title="<?php echo $sticker->filter_name ?>"
    <?php if(isset($ov)){
        echo "scale='{$ov['scale']}' rotate='{$ov['rotate']}' style='left: {$ov['offset']['x']}; top: {$ov['offset']['y']};'";
    }else{
        echo 'onclick="addLayer(this)" ';
    }
    ?>
    >