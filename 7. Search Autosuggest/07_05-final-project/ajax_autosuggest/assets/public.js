// note: IE8 doesn't support DOMContentLoaded
document.addEventListener('DOMContentLoaded', function() {

  var suggestions = document.getElementById("suggestions");
  var form = document.getElementById("search-form");
  var search = document.getElementById("search");

  function jsonToHTML(items) {
    // <li><a href="search.php?q=alpha">Alpha</a></li>
    var output = '';

    for(i=0; i < items.length; i++) {
      output += '<li>';
      output += '<a href="search.php?q=' + items[i] + '">';
      output += items[i];
      output += '</a>';
      output += '</li>';
    }

    return output;
  }

  function showSuggestions(json) {
    var li_list = jsonToHTML(json);
    suggestions.innerHTML = li_list;
    suggestions.style.display = 'block';
  }

  function getSuggestions() {
    var q = search.value;

    if(q.length < 3) {
      suggestions.style.display = 'none';
      return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'autosuggest.php?q=' + q, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
    xhr.onreadystatechange = function () {
      if(xhr.readyState == 4 && xhr.status == 200) {
        var result = xhr.responseText;
        console.log('Result: ' + result);

        var json = JSON.parse(result);
        showSuggestions(json);
      }
    };
    xhr.send();
  }

  // use "input" event, not "change" event as it must lose focus to work
  search.addEventListener("input", getSuggestions);

});
