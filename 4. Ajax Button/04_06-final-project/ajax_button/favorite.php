<?php
  // You can simulate a slow server with sleep. it shows that thx to ajax we can maintain activity on web page while below processes happens on background:
  // sleep(1);

  session_start(); // in real world we wud have db

// needless since we already set session on index.php?
  if(!isset($_SESSION['favorites'])) { $_SESSION['favorites'] = []; }

// prevents the user to access this page directly
  function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }
  if(!is_ajax_request()) { header('Location: index.php'); }

  // extract $id
  $raw_id = isset($_POST['id']) ? $_POST['id'] : '';
// blog-post-101, blog-post-102 or blog-post-103

//we are getting number part of id (if it exists) and put it in $matches arr
  if(preg_match("/blog-post-(\d+)/", $raw_id, $matches)) { // 0 or 1
    $id = $matches[1]; //$matches[0] is blog-post-102 if $matches[1] is 102
    // vers1: returns and logs $id:
    // echo $id;
    // return;    

// needless check since favorite button disappears after being clicked
    if(!in_array($id, $_SESSION['favorites'])) {
    // store in $_SESSION['favorites']
      $_SESSION['favorites'][] = $id;
    }

    echo 'true';
  } else {
    echo 'false';
  }

// alt: basic version:
// session_start();
// $id = $_POST['id'];
// if(!in_array($id, $_SESSION['favorites'])) {
//   $_SESSION['favorites'][] = $id;
// }
// echo 'true';
?>
