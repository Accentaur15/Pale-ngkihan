
const form = document.querySelector('.formselect');
const submitBtn = form.querySelector('.button');
const errorText = form.querySelector('.error-text');

form.onsubmit = (e) => {
  e.preventDefault();
}

submitBtn.onclick = () => {
  // Start AJAX
  let xhr = new XMLHttpRequest(); // Create XML object
  xhr.open("POST", "./php/verifylogin.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.response.trim(); // Trim whitespace from the response
        let comparisonResult = data === "success";
        if (comparisonResult) {
            console.log("success")
          location.href = "./buyer/buyermain.php";
        } else {
          errorText.textContent = data;
          errorText.style.display = "block";
        }
      }
    }
  }
  // Send data through AJAX to PHP
  let formData = new FormData(form); // Create new FormData object from the form data
  xhr.send(formData); // Send data to PHP
}
