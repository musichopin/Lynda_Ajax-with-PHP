<?php
  $content = array(
    'short' => 'New content',
    'regular' => 'This is the new content which has been loaded by Ajax.',
    'long' => 'This content is the result of making an Ajax query to a PHP page which dynamically generated text as a response.'
  );

  echo json_encode($content); // converts to string containing json
// assoc array of php becomes json obj whereas indexed array of php becomes js array  
?>
