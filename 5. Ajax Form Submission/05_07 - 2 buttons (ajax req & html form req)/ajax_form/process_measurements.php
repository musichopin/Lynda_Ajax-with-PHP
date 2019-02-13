<?php
  // You can simulate a slow server with sleep
  sleep(1);

  function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }

  $length = isset($_POST['length']) ? (int) $_POST['length'] : '';
  $width = isset($_POST['width']) ? (int) $_POST['width'] : '';
  $height = isset($_POST['height']) ? (int) $_POST['height'] : '';

  $errors = [];
  if($length == '') { $errors[] = 'length'; }
  if($width == '') { $errors[] = 'width'; }
  if($height == '') { $errors[] = 'height'; }

  if(!empty($errors)) { // if there are errors
    if(is_ajax_request()) { // ajax error
      // won't work b/c of single-quotes
      // echo "{ 'errors': " . json_encode($errors) . "}";
      $result_array = array('errorss' => $errors); //md array
      echo json_encode($result_array);
    } else { // php error
      echo "<p>There were errors on: " . implode(', ', $errors) . "</p>";
      echo "<p><a href=\"index.php\">Back</a></p>";
    }
    exit;
  }

  $volume = $length * $width * $height; // no errors until this part

  if(is_ajax_request()) { // ajax req
    echo json_encode(array('volume' => $volume));
  } else { // php req w/o ajax
    echo "<p>The total volume is: " . $volume . "</p>";
    echo "<p><a href=\"index.php\">Back</a></p>";
  }
// assoc array of php becomes json obj whereas indexed array of php becomes js array  
?>
