<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Infinite Scroll</title>
    <style>
      #blog-posts {
        width: 700px;
        margin: 0 auto;
      }
      .blog-post {
        border: 1px solid black;
        margin: 10px 10px 20px 10px;
        padding: 6px 10px;
      }
      #spinner {
        display: none;
      }
    </style>
  </head>
  <body>
    <div id="blog-posts">
    </div>

    <div id="spinner">
      <img src="spinner.gif" width="50" height="50" />
    </div>

<!-- we didn't disable loadmore button despite infinite scrolling feature -->
    <div id="load-more-container">
      <button id="load-more" data-page="0">Load more</button>
    </div>

    <script>

      var container = document.getElementById('blog-posts');
      var load_more_button = document.getElementById('load-more');
      var spinner = document.getElementById("spinner");
      var request_in_progress = false;

      function showSpinner() {
        spinner.style.display = 'block';
      }

      function hideSpinner() {
        spinner.style.display = 'none';
      }

      function showLoadMoreButton() {
        load_more_button.style.display = 'inline';
      }

      function hideLoadMoreButton() {
        load_more_button.style.display = 'none';
      }

      function appendToDiv(div, new_html) {
        // Put the new HTML into a temporary div
        // This causes browser to parse it as elements.
        var temp = document.createElement('div');
        temp.innerHTML = new_html;

        // Then we can find and work with those elements.
        // Use firstElementChild b/c of how DOM treats whitespace.
        var class_name = temp.firstElementChild.className;
        var items = temp.getElementsByClassName(class_name);
        // alt: easier: var items = temp.children;

        var len = items.length; // items.length is dynamic bc of appendChild()
        for(i=0; i < len; i++) {
          div.appendChild(items[0]);
// items nodelist gets 1 less at each iteration w/ appendChild() method
        }
      }

      function setCurrentPage(page) {
        console.log('Incrementing page to: ' + page);
        load_more_button.setAttribute('data-page', page);
      }

      function scrollReaction() {
        var content_height = container.offsetHeight;
        var current_y = window.innerHeight + window.pageYOffset;
        console.log(window.innerHeight); //fixed
        console.log(window.pageYOffset);
        console.log(current_y + '/' + content_height);
        if(current_y >= content_height) {
          loadMore();
        }
      }

      function loadMore() {
// *prevents multiple ajax requests with scrolling*
        if (request_in_progress) { return; }
        else { request_in_progress = true; }

        showSpinner();
        hideLoadMoreButton();

        var page = parseInt(load_more_button.getAttribute('data-page'));
        var next_page = page + 1;

        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'blog_posts.php?page=' + next_page, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log('Result: ' + result);

            hideSpinner();
            setCurrentPage(next_page);
            // append results to end of blog posts
            appendToDiv(container, result);
            showLoadMoreButton();
            request_in_progress = false;
          }
        };
        xhr.send();
      }

      load_more_button.addEventListener("click", loadMore);

      window.addEventListener("scroll", scrollReaction); // alt1

      // Load even the first page with Ajax
      loadMore();
    </script>

  </body>
</html>
<!-- alt1: 
  window.onscroll = function() {
    scrollReaction();
  }
-->