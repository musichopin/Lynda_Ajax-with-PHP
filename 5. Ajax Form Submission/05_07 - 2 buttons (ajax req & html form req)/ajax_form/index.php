<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Asynchronous Form</title>
    <style>
      #result {
        display: none;
      }
      .error {
        border: 1px solid red;
      }
      #spinner {
        display: none;
      }
    </style>
  </head>
  <body>

    <div id="measurements">
      <p>Enter measurements below to determine the total volume.</p>
<!-- action is necessary for submit button (not ajax submit) -->      
      <form id="measurement-form" action="process_measurements.php" method="POST">
        Length: <input type="text" name="length" /><br />
        <br />
        Width: <input type="text" name="width" /><br />
        <br />
        Height: <input type="text" name="height" /><br />
        <br />
<!-- html form request (direct php) for js disabled users: -->        
        <input id="html-submit" type="submit" value="Html Submit" /> 
        <!-- alt1: <button id="html-submit">Submit</button> -->
        <!-- alt2: <button id="html-submit" type="submit">Submit</button> -->
<!-- ajax request (js+php) for js enabled users (action attr önemsiz): -->        
        <input id="ajax-submit" type="button" value="Ajax Submit" />
      </form>
    </div>

    <div id="spinner">
      <img src="spinner.gif" width="50" height="50" />
    </div>

    <div id="result">
      <p>The total volume is: <span id="volume"></span></p>
    </div>

    <script> //only necessary for ajax submit button

      var result_div = document.getElementById("result");
      var volume = document.getElementById("volume");
      var button = document.getElementById("ajax-submit");
      var orig_button_value = button.value;

      function showSpinner() {
        var spinner = document.getElementById("spinner");
        spinner.style.display = 'block';
      }

      function hideSpinner() {
        var spinner = document.getElementById("spinner");
        spinner.style.display = 'none';
      }

      function disableSubmitButton() {
        button.disabled = true;
        // alt: button.style.display = 'none';
        button.value = 'Loading...';
      }

      function enableSubmitButton() {
        button.disabled = false;
        // alt: button.style.display = 'inline';
        button.value = orig_button_value;
      }

      function displayErrors(errors) { // errors is array
        var inputs = document.getElementsByTagName('input');
        // console.log(inputs)
        for(i=0; i < inputs.length; i++) {
          var input = inputs[i];
          if(errors.indexOf(input.name) >= 0) {
            input.classList.add('error');
          }
        }
      }

      function clearErrors() {
        var inputs = document.getElementsByTagName('input');
        for(i=0; i < inputs.length; i++) {
          inputs[i].classList.remove('error');
        }
      }

      function postResult(value) {
        volume.innerHTML = value;
        result_div.style.display = 'block';
      }

      function clearResult() {
        volume.innerHTML = '';
        result_div.style.display = 'none';
      }

//not used: omits textareas, select-options, checkboxes, radio buttons
      function gatherFormData(form) {
        var inputs = form.getElementsByTagName('input');
        var array = [];
        for(i=0; i < inputs.length; i++) {
          var inputNameValue = inputs[i].name + '=' + inputs[i].value;
          array.push(inputNameValue);
        }
        return array.join('&');
      }

      function calculateMeasurements() {
        clearResult();
        clearErrors();
        showSpinner();
        disableSubmitButton();

        var form = document.getElementById("measurement-form");
        var action = form.getAttribute("action");

        // gather form data
// instead of calling gatherFormData() above we used FormData obj        
        var form_data = new FormData(form);
        for ([key, value] of form_data.entries()) {
          console.log(key + ': ' + value);
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', action, true);
        // do not set content-type with FormData
        //xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log(result);

            hideSpinner();
            enableSubmitButton();

            var json = JSON.parse(result);
            console.log(json)
            if(json.hasOwnProperty('errorss') && json.errorss.length > 0) {
              displayErrors(json.errorss);
            } else if (json.hasOwnProperty('volume')) { // no error
              postResult(json.volume);
            }
          }
        };
        xhr.send(form_data);
      }

      button.addEventListener("click", calculateMeasurements);

    </script>

  </body>
</html>
