function previewFile(imageId, input) {
  var file = input.files[0];
  if (file) {
      var reader = new FileReader();
      reader.onload = function () {
          var image = document.getElementById(imageId);
          if (image) {
              image.src = reader.result;
          } else {
              console.error("Image element with ID '" + imageId + "' not found.");
          }
      };
      reader.readAsDataURL(file);
  }
}

function showFullImage(img) {
  var overlay = document.createElement("div");
  overlay.className = "overlay";

  var fullImage = document.createElement("img");
  fullImage.className = "full-image";
  fullImage.src = img.src;

  overlay.appendChild(fullImage);
  document.body.appendChild(overlay);

  overlay.addEventListener("click", function() {
    document.body.removeChild(overlay);
  });
}


