function previewFile(imageId, input) {
  var file = input.files[0];
  if (file) {
    var reader = new FileReader();
    reader.onload = function () {
      var image = document.getElementById(imageId);
      image.src = reader.result;
    };
    reader.readAsDataURL(file);
  }
}

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




