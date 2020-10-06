import { load } from "recaptcha-v3";

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#maintenance_subscription");

  if (!form) {
    return;
  }

  const validateField = () => {
    return true;
  };

  const addInputListeners = (element) => {
    element.addEventListener("keyup", handleKeyUp);
    element.addEventListener("keydown", handleKeyDown);
    element.addEventListener("change", handleInputChange);
  };

  const handleFormResponse = (response) => {
    const responseElement = form.querySelector(
      ".stuffs-maintenance-form__response"
    );
    const closeButton = responseElement.querySelector("button");

    form.setAttribute("responded", "");
    responseElement.querySelector(".title").textContent = response.title;
    responseElement.querySelector(".content").textContent = response.message;

    if (response.success) {
      form.reset();
      responseElement.setAttribute("success", "");
    } else {
      responseElement.setAttribute("error", "");
    }

    closeButton.addEventListener("click", () => {
      form.removeAttribute("responded");
      responseElement.removeAttribute("error");
      responseElement.removeAttribute("success");
      responseElement.querySelector(".title").textContent = "";
      responseElement.querySelector(".content").textContent = "";
    });
  };

  const submitForm = async (email, form) => {
    try {
      let query = `email=${email}`;

      if (stuffs_maintenance_vars.recaptcha_active) {
        const recaptcha = await load(stuffs_maintenance_vars.recaptcha_site_key);
        const token = await recaptcha.execute("submit");
        if (!token) {
          return {
            success: false,
            title: "Failed Recaptcha",
            message: "You seem to have failed Google's reCaptcha test",
          };
        }
        query += `&token=${token}`;
      }

      const request = await fetch(`${form.action}`, {
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
          Accept: "application/json",
        },
        method: "POST",
        body: query,
      });
      const data = await request.json();
      return data;
    } catch (error) {
      return {
        success: false,
        title: "There's Been an Error",
        message: error
          ? error
          : "There seems to be an issue with processing requests at the moment. Try again later.",
      };
    }
  };

  const setLoading = (loading = false) =>
    loading
      ? form.setAttribute("loading", "")
      : form.removeAttribute("loading");

  async function handleSubmitPress(event) {
    event.preventDefault();
    setLoading(true);
    const email = Array.from(this.elements).find(
      (element) => element.name === "email"
    );
    const isValid = validateField(email);
    const response = isValid
      ? await submitForm(email.value, this)
      : {
          success: false,
          title: "There's Been an Error",
          message:
            "There was an error validating your email address. Please check it and try again.",
        };

    setTimeout(() => (handleFormResponse(response), setLoading()), 2000);
  }

  function handleInputChange(event) {
    if (this.value !== "") {
      this.parentNode.setAttribute("filled", "");
    } else {
      this.parentNode.removeAttribute("filled");
    }
  }

  function handleKeyUp(event) {
  }

  function handleKeyDown(event) {
  }

  form.addEventListener("submit", handleSubmitPress);

  for (let element of form) {
    if (element.type === "submit") {
      return;
    }
    addInputListeners(element);
  }
});
