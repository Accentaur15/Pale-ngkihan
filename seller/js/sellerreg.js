//showdiv


function showDiv(select) {
    if (select.value.localeCompare("Yes") === 0) {
        document.getElementById('showpermit').style.display = "block";
        document.getElementById('businesspermit').setAttribute('required', '');
        document.getElementById('dtipermit').setAttribute('required', '');
        document.getElementById('mayorspermit').setAttribute('required', '');
    } else {
        document.getElementById('showpermit').style.display = "none";
        document.getElementById('businesspermit').removeAttribute('required');
        document.getElementById('dtipermit').removeAttribute('required');
        document.getElementById('mayorspermit').removeAttribute('required');
    }
}

// Trigger the function on page load
window.addEventListener('DOMContentLoaded', function() {
    var selectElement = document.getElementById('yourSelectElementId');
    showDiv(selectElement);
});

function showDiv(select) {
  var selectedValue = select.value;

  if (selectedValue === "Yes") {
      // Code to execute when Yes is selected
      document.getElementById('showpermit').style.display = "block";
      document.getElementById('businesspermit').setAttribute('required', '');
      document.getElementById('dtipermit').setAttribute('required', '');
      document.getElementById('mayorspermit').setAttribute('required', '');
  } else if (selectedValue === "No") {
      // Code to execute when No is selected
      document.getElementById('showpermit').style.display = "none";
      document.getElementById('businesspermit').removeAttribute('required');
      document.getElementById('dtipermit').removeAttribute('required');
      document.getElementById('mayorspermit').removeAttribute('required');
  }
}

// Trigger the function on page load
window.addEventListener('DOMContentLoaded', function() {
  var selectElement = document.getElementsByName('ispstore')[0];
  showDiv(selectElement); // Call the function initially

  selectElement.addEventListener('change', function() {
      showDiv(this); // Call the function when the select element value changes
  });
});
//previewFIle

function previewFile(imageId, input) {
    var file = input.files[0];
    if (file) {
      var reader = new FileReader();
      reader.onload = function () {
        var image = document.getElementById(imageId);
        image.src = reader.result;
        image.onclick = function () {
          var overlay = document.createElement("div");
          overlay.className = "overlay";
          var fullImage = document.createElement("img");
          fullImage.className = "full-image";
          fullImage.src = reader.result;
          overlay.appendChild(fullImage);
          document.body.appendChild(overlay);
          overlay.onclick = function () {
            document.body.removeChild(overlay);
          };
        };
      };
      reader.readAsDataURL(file);
    }
  }
  
  //showfull image
  function showFullImage(img) {
    var overlay = document.createElement("div");
    overlay.className = "overlay";
    overlay.onclick = function() {
      document.body.removeChild(overlay);
    };
  
    var fullImage = document.createElement("img");
    fullImage.className = "full-image";
    fullImage.src = img.src;
  
    overlay.appendChild(fullImage);
    document.body.appendChild(overlay);
  }

