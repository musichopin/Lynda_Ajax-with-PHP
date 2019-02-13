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

    <div id="load-more-container">
      <button id="load-more" data-page="0">Load more</button>
    </div>

    <script>

      var container = document.getElementById('blog-posts');
      var load_more_button = document.getElementById('load-more');
      var spinner = document.getElementById("spinner");

      function showSpinner() {
        spinner.style.display = 'block';
      }

      function hideSpinner() {
        spinner.style.display = 'none';
      }

      function showLoadMoreButton() {
        load_more_button.parentElement.style.display = 'inline';
        // alt: load_more_button.disabled = false;
      }

      function hideLoadMoreButton() {
        load_more_button.parentElement.style.display = 'none';
        // alt: load_more_button.disabled = true;
      }

      function appendToDiv(div, new_html) {
        // Put the new HTML into a temp div
        // This causes browser to parse it as elements.
        var temp = document.createElement('div');
        temp.innerHTML = new_html;

        // Then we can find and work with those elements.
        // Use firstElementChild b/c of how DOM treats whitespace.
        var class_name = temp.firstElementChild.className;
        var items = temp.getElementsByClassName(class_name);

        var len = items.length;
        for(i=0; i < len; i++) {
          div.appendChild(items[0]);
        }
      }

      function setCurrentPage(page) {
        console.log('Incrementing page to: ' + page);
        load_more_button.setAttribute('data-page', page);
      }

      function loadMore() {

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

            hideSpinner();
            setCurrentPage(next_page);
            appendToDiv(container, result);
//append results 2 end of blog posts            
            showLoadMoreButton();
            
            console.log('Result: ' + result);
          }
        };
        xhr.send();
      }

      load_more_button.addEventListener("click", loadMore);

      // Load even the first page with Ajax
      loadMore();
    </script>

  </body>
</html>
