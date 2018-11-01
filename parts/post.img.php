<?php
header("Content-Type: image/png");
ob_clean();
echo base64_decode($post->image_data);
return;
?>