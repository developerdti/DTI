/*
-----------------------
TOOGLE LOGIN/SIGNUP
-----------------------
*/
const buttonsingup = document.getElementById("button--signUp");
const buttonlogin = document.getElementById("button--login");
const formlg = document.getElementById("form__login");
const formsg = document.getElementById("form__signUp");
buttonlogin.addEventListener("click", ChangeToLogin);
buttonsingup.addEventListener("click", ChangeTosignup);
var toggle = false;

function ChangeToLogin() {
  if (toggle) {
    formlg.classList.toggle("visibility");
    formsg.classList.toggle("visibility");
    toggle = false;
  }
}

function ChangeTosignup() {
  if (!toggle) {
    formlg.classList.toggle("visibility");
    formsg.classList.toggle("visibility");
    toggle = true;
  }
}

/*
-----------------------
LOGIN
-----------------------
*/
const SubmitLoginButton = document.getElementById("button__submit--login");
const formLogin = document.forms["form--login"];
SubmitLoginButton.addEventListener("click", LogIn);

function LogIn() {
  var status;
  fetch("Session/signIn", {
    method: "POST",
    body: new FormData(formLogin),
  })
    .then((response) => {
      status = response.status;
      return response.json();
    })
    .then((data) => {
      import("./helper.js").then((module) => {
        if (status === 200) {
          window.location.reload();
        } else if (module.statusCode.hasOwnProperty(status)) {
          module.statusCode[status](data);
        } else {
          module.statusCode["default"]();
        }
      });
    })
    .catch((error) => {
      import("./helper.js").then((module) => {
        module.statusCode["default"]();
      });
    });
}

/*
-----------------------
SIGNUP
-----------------------
*/
const SubmitsignUpButton = document.getElementById("button__submit--signup");
const formSignUp = document.forms["form__signUp"];

SubmitsignUpButton.addEventListener("click", () => {
  SubmitsignUpButton.disabled = true;
  signUp();
});

function signUp() {
  var status;
  fetch("Session/signUp", {
    method: "POST",
    body: new FormData(formSignUp),
  })
    .then((response) => {
      status = response.status;
      return response.json();
    })
    .then((data) => {
      SubmitsignUpButton.disabled = false;
      import("./helper.js").then((module) => {
        if (status === 200) {
          formSignUp.reset();
          import("./helper.min.js").then((module) => {
            module.buildWarning(data.status);
          });
          import("./helper.min.js").then((module) => {
            module.buildToastSuccess(data.exito.title, data.exito.message);
          });
        } else if (module.statusCode.hasOwnProperty(status)) {
          module.statusCode[status](data);
        } else {
          module.statusCode["default"]();
        }
      });
    })
    .catch((error) => {
      SubmitsignUpButton.disabled = false;
      import("./helper.js").then((module) => {
        module.statusCode["default"]();
      });
    });
}
