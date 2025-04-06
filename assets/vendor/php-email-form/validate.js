(function () {
  "use strict";

  let forms = document.querySelectorAll(".php-email-form");

  forms.forEach(function (e) {
    e.addEventListener("submit", function (event) {
      event.preventDefault();

      let thisForm = this;
      thisForm.querySelector(".loading").classList.add("d-block");
      thisForm.querySelector(".error-message").classList.remove("d-block");
      thisForm.querySelector(".sent-message").classList.remove("d-block");

      let formData = new FormData(thisForm);

      // Prepare EmailJS parameters
      const emailParams = {
        name: formData.get("name"),
        email: formData.get("email"),
        subject: formData.get("subject"),
        message: formData.get("message"),
      };

      // Send email using EmailJS
      emailjs
        .send("YOUR_SERVICE_ID", "YOUR_TEMPLATE_ID", emailParams)
        .then(() => {
          thisForm.querySelector(".loading").classList.remove("d-block");
          thisForm.querySelector(".sent-message").classList.add("d-block");
          thisForm.reset();
        })
        .catch((error) => {
          thisForm.querySelector(".loading").classList.remove("d-block");
          displayError(thisForm, "Failed to send message. Please try again.");
          console.error("EmailJS error:", error);
        });
    });
  });

  function displayError(thisForm, error) {
    thisForm.querySelector(".error-message").innerHTML = error;
    thisForm.querySelector(".error-message").classList.add("d-block");
  }
})();
