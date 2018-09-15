<?php
$var = 'new content';
// vers1: for debugging we cud just echo the var and return to send the var back as response:
// echo $var;
// return;
?>

<span style="color: blue;">This is the <strong><?php echo $var; ?></strong> which has been loaded by Ajax.</span>
