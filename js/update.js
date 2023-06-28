const form = document.querySelector('.card-registration form');
const submitBtn = form.querySelector('.button');
const errorText = form.querySelector('.error-text');

form.onsubmit = (e) => {
  e.preventDefault();
};

submitBtn.onclick = () => {
  // Start AJAX
  let xhr = new XMLHttpRequest(); // Create XML object
  xhr.open("POST", "../php/updatebuyer.php", true);
  xhr.onload = () => {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      if (xhr.status === 200) {
        let data = xhr.responseText.trim(); // Trim whitespace from the response
        let comparisonResult = data === "success";
        if (comparisonResult) {
          location.href = "buyermyaccount.php";
        } else {
          console.log(data);
          errorText.textContent = data;
          errorText.style.display = "block";
        }
      }
    }
  };
  // Send data through AJAX to PHP
  let formData = new FormData(form); // Create new FormData object from the form data
  xhr.send(formData); // Send data to PHP
};
