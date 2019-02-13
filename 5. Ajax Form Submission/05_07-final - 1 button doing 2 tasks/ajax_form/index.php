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

<!--result: disabled(css) disabled(ajax start) enabled(ajax end)
    error: disabled disabled enabled;
    spinner: disabled enabled disabled
    submit: enabled disabled enabled -->    

    <div id="measurements">
      <p>Enter measurements below to determine the total volume.</p>
      <form id="measurement-form" action="process_measurements.php" method="POST">
        Length: <input type="text" name="length" /><br />
        <br />
        Width: <input type="text" name="width" /><br />
        <br />
        Height: <input type="text" name="height" /><br />
        <br />
        <input id="submit" type="submit" value="Submit" />
        <!-- alt: <button id="submit">Submit</button> -->
<!-- *we use just 1 button for both ajax requests on js enabled users and html form requests (direct php) on js disabled users. we prevented default behavior for ajax requests at the bottom of the page* -->   
      </form>
    </div>

    <div id="spinner">
      <img src="spinner.gif" width="50" height="50" />
    </div>

    <div id="result">
      <p>The total volume is: <span id="volume"></span></p>
    </div>

    <script>

      var result_div = document.getElementById("result");
      var volume = document.getElementById("volume");
      var button = document.getElementById("submit");
      var orig_button_value = button.value;
      var spinner = document.getElementById("spinner");

      function showSpinner() {
        spinner.style.display = 'block';
      }

      function hideSpinner() {
        spinner.style.display = 'none';
      }

      function disableSubmitButton() {
        button.disabled = true;
        button.value = 'Loading...';
      }

      function enableSubmitButton() {
        button.disabled = false;
        button.value = orig_button_value;
      }

      function displayErrors(errors) {
        var inputs = document.getElementsByTagName('input');
        for(i=0; i < inputs.length; i++) {
          var input = inputs[i];
          if(errors.indexOf(input.name) >= 0) {
            input.classList.add('error');
// alt: input.style.border = "1px solid red";
          }
        }
      }

      function clearErrors() {
        var inputs = document.getElementsByTagName('input');
        for(i=0; i < inputs.length; i++) {
          inputs[i].classList.remove('error');
// alt: inputs[i].style.border = "1px solid grey";
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

      function gatherFormData(form) { // not used
// omits textareas, select-options, checkboxes, radio buttons
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

// instead of calling gatherFormData() above we used FormData obj
        var form_data = new FormData(form);
        // for ([key, value] of form_data.entries()) {
        //   console.log(key + ': ' + value);
        // }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', action, true);
        // we do not set content-type with FormData obj as we cannot use x-www-form-urlencoded as content type when we have files (if we used gatherFormData() instead of FormData obj we wudnt comment it out):
        //xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        //indicates ajax request to process_measurements.php
        xhr.onreadystatechange = function () {
          if(xhr.readyState == 4 && xhr.status == 200) {
            var result = xhr.responseText;
            console.log('Result: ' + result);

            hideSpinner();
            enableSubmitButton();

            var json = JSON.parse(result);
            if(json.hasOwnProperty('errors') && json.errors.length > 0) {
              displayErrors(json.errors); // json.errors is array
            } else {
              postResult(json.volume); // json.volume is number
            }
          }
        };
        xhr.send(form_data);
        // alt: form.getElementsByTagName('input')[i].name ile form.getElementsByTagName('input')[i].value 1, 2 ve 3 indisleri için manual olarak kullanılabilir ve aşağıdakine benzer bir sonuç ortaya çıkar:
        // xhr.send('length=12&width=5&height=3');   
      }

      button.addEventListener("click", function(event) {
        event.preventDefault();//matters 2 prevent html form request (php) in form
        calculateMeasurements();
      });

    </script>

  </body>
</html>
