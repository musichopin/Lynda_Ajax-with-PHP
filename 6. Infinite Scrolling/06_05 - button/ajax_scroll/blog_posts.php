<?php
  // You can simulate a slow server with sleep
  sleep(1);

  function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }

  if(!is_ajax_request()) { exit; }

  // Typically, this would be a call to a database (read of CRUD)
  function find_blog_posts($page) {
    $first_post = 101; //id of first post
    $per_page = 3; //post per page
    $offset = (($page - 1) * $per_page) + 1; //1,4,7,10 etc

    $blog_posts = [];
    // This is our "fake" database
    for($i=0; $i < $per_page; $i++) {
      $id = $first_post + $offset - 1 + $i;
      $blog_post = [
        'id' => $id,
        'title' => "Blog Post #{$id}",
        'content' => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed scelerisque nunc malesuada mauris fermentum commodo. Integer non pellentesque augue, vitae pellentesque tortor. Ut gravida ullamcorper dolor, ac fringilla mauris interdum id. Nulla porta egestas nisi, et eleifend nisl tincidunt suscipit. Suspendisse massa ex, fringilla quis orci a, rhoncus porta nulla. Aliquam diam velit, bibendum sit amet suscipit eget, mollis in purus. Sed mattis ultricies scelerisque. Integer ligula magna, feugiat non purus eget, pharetra volutpat orci. Duis gravida neque erat, nec venenatis dui dictum vel. Maecenas molestie tortor nec justo porttitor, in sagittis libero consequat. Maecenas finibus porttitor nisl vitae tincidunt."
      ];
      $blog_posts[] = $blog_post;
    }
    return $blog_posts;
  }

  $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

  $blog_posts = find_blog_posts($page); //md array

?>
<?php foreach($blog_posts as $blog_post) { ?>
  <div id="blog-post-<?php echo $blog_post['id']; ?>" class="blog-post">
    <h3><?php echo $blog_post['title']; ?></h3>
    <p><?php echo $blog_post['content']; ?></p>
  </div>
<?php } ?>


<?php //alt1: didnt work
/*foreach($blog_posts as $blog_post) {
  echo "<div id='blog-post-".$blog_post["id"]."'> class='blog-post'>";
  echo "<h3>{$blog_post["title"]}</h3>";
  echo "<p>{$blog_post["content"]}</p>";
  echo "</div>";
}*/

//alt2: didnt work
/*foreach($blog_posts as $blog_post) {
  echo "<div id='blog-post-{$blog_post["id"]}'> class='blog-post'>";
  echo "<h3>{$blog_post["title"]}</h3>";
  echo "<p>{$blog_post["content"]}</p>";
  echo "</div>";
} */
?>

