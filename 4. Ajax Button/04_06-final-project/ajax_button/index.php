<?php
//*session is used for displaying the hearts after refreshing the page. if we didnt call is_favorite() on three divs below we wudnt be able to display the hearts when we refresh the page (though we could toggle the hearts without refreshing using ajax request)*
  session_start();

// used if cookie of the page is deleted
  if(!isset($_SESSION['favorites'])) { $_SESSION['favorites'] = []; }

  function is_favorite($id) {
    return in_array($id, $_SESSION['favorites']); // value & array
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Asynchronous Button</title>
    <style>
      #blog-posts {
        width: 700px;
        margin: 0 auto
      }
      .blog-post {
        border: 1px solid black;
        margin: 10px 10px 20px 10px;
        padding: 6px 10px;
      }

      button.favorite-button, button.unfavorite-button {
        background: #0000FF;
        color: white;
        text-align: center;
        width: 80px;
      }
      button.favorite-button:hover, button.unfavorite-button:hover {
        background: #000099;
      }

/*below 4 styling displays favorite/unfavorite buttons alternately*/
      button.favorite-button { /*when fav button isnt clicked*/
        display: inline;
      }
      button.unfavorite-button { /*when fav button isnt clicked*/
        display: none;
      }
      .favorite button.favorite-button { /*when fav button is clicked*/
        display: none;
      }
      .favorite button.unfavorite-button { /*when fav button is clicked*/
        display: inline;
      }

      .favorite-heart {
        color: red;
        font-size: 2em;
        float: right;
        display: none;
      }
      .favorite .favorite-heart {
        display: block; /*makes heart visible when fav button clicked*/
      }
    </style>
  </head>
  <body>
    <div id="blog-posts">
      <div id="blog-post-101" class="blog-post <?php if(is_favorite(101)) { echo 'favorite'; } ?>">
        <span class="favorite-heart">&hearts;</span>
        <h3>Blog Post 101</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed scelerisque nunc malesuada mauris fermentum commodo. Integer non pellentesque augue, vitae pellentesque tortor. Ut gravida ullamcorper dolor, ac fringilla mauris interdum id. Nulla porta egestas nisi, et eleifend nisl tincidunt suscipit. Suspendisse massa ex, fringilla quis orci a, rhoncus porta nulla. Aliquam diam velit, bibendum sit amet suscipit eget, mollis in purus. Sed mattis ultricies scelerisque. Integer ligula magna, feugiat non purus eget, pharetra volutpat orci. Duis gravida neque erat, nec venenatis dui dictum vel. Maecenas molestie tortor nec justo porttitor, in sagittis libero consequat. Maecenas finibus porttitor nisl vitae tincidunt.</p>
        <button class="favorite-button">Favorite</button>
        <button class="unfavorite-button">Unfavorite</button>
      </div>
      <div id="blog-post-102" class="blog-post <?php if(is_favorite(102)) { echo 'favorite'; } ?>">
        <span class="favorite-heart">&hearts;</span>
        <h3>Blog Post 102</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed scelerisque nunc malesuada mauris fermentum commodo. Integer non pellentesque augue, vitae pellentesque tortor. Ut gravida ullamcorper dolor, ac fringilla mauris interdum id. Nulla porta egestas nisi, et eleifend nisl tincidunt suscipit. Suspendisse massa ex, fringilla quis orci a, rhoncus porta nulla. Aliquam diam velit, bibendum sit amet suscipit eget, mollis in purus. Sed mattis ultricies scelerisque. Integer ligula magna, feugiat non purus eget, pharetra volutpat orci. Duis gravida neque erat, nec venenatis dui dictum vel. Maecenas molestie tortor nec justo porttitor, in sagittis libero consequat. Maecenas finibus porttitor nisl vitae tincidunt.</p>
        <button class="favorite-button">Favorite</button>
        <button class="unfavorite-button">Unfavorite</button>
      </div>
      <div id="blog-post-103" class="blog-post <?php if(is_favorite(103)) { echo 'favorite'; } ?>">
        <span class="favorite-heart">&hearts;</span>
        <h3>Blog Post 103</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed scelerisque nunc malesuada mauris fermentum commodo. Integer non pellentesque augue, vitae pellentesque tortor. Ut gravida ullamcorper dolor, ac fringilla mauris interdum id. Nulla porta egestas nisi, et eleifend nisl tincidunt suscipit. Suspendisse massa ex, fringilla quis orci a, rhoncus porta nulla. Aliquam diam velit, bibendum sit amet suscipit eget, mollis in purus. Sed mattis ultricies scelerisque. Integer ligula magna, feugiat non purus eget, pharetra volutpat orci. Duis gravida neque erat, nec venenatis dui dictum vel. Maecenas molestie tortor nec justo porttitor, in sagittis libero consequat. Maecenas finibus porttitor nisl vitae tincidunt.</p>
        <button class="favorite-button">Favorite</button>
        <button class="unfavorite-button">Unfavorite</button>
      </div>
    </div>

    <script> 
//when clicking button we shud normally notify server, update db and make visual change to this page to indicate the change in state
      function favorite() {
        var parent = this.parentElement; // parent of clicked button

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'favorite.php', true);
// since we wud update session (or db) this wud become post req and for post request we set header as content-type
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
// we set header to indicate this is an ajax request, not a normal one (header value (ie XMLHttpRequest) is random and may change)       
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log('Result: ' + result);
            if(result == 'true') {
              parent.classList.add("favorite"); // makes heart visible
            }
          }
        };
        xhr.send("id=" + parent.id); // 4 post request we set params here
      }

      var buttons = document.getElementsByClassName("favorite-button");
      for(i=0; i < buttons.length; i++) {
        buttons.item(i).addEventListener("click", favorite);
// alt: buttons[i].addEventListener("click", favorite);        
      }

      function unfavorite() {
        var parent = this.parentElement;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'unfavorite.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log('Result: ' + result);
            if(result == 'true') {
              parent.classList.remove("favorite");
            }
          }
        };
        xhr.send("id=" + parent.id);
      }

      var buttons = document.getElementsByClassName("unfavorite-button");
      for(i=0; i < buttons.length; i++) {
        buttons.item(i).addEventListener("click", unfavorite);
// alt: buttons[i].addEventListener("click", unfavorite);        
      }
    </script>

  </body>
</html>
