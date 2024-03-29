const buildToast = (title, message) => {
    let currentTime = new Date(),
      hour = ("0" + currentTime.getHours()).slice(-2),
      minutes = ("0" + currentTime.getMinutes()).slice(-2),
      seconds = ("0" + currentTime.getSeconds()).slice(-2),
      fullTime = `${hour}:${minutes}:${seconds}`;
  
    let toastTemplate = `
        <div class="toast-container position-fixed bottom-0 end-0" id="toast--notification">
            <div id="toast__identier-warning" class="toast" role="alert">
                <div class="toast--notification__header">
                    <div>
                        <strong>${title}</strong>
                        <small>${fullTime}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast--notification__body">${message}</div>
            </div>
        </div>
    `;
  
    const toastContainer = document.querySelector(".toast--notification");
  
    toastContainer.innerHTML = toastTemplate;
  
    const toast = document.getElementById("toast__identier-warning"),
      createToast = bootstrap.Toast.getOrCreateInstance(toast);
  
    createToast.show();
  
    toast.addEventListener("hidden.bs.toast", () => {
      toastContainer.innerHTML = "";
    });
  };
  
  const buildToastSuccess = (title, message) => {
    let currentTime = new Date(),
      hour = ("0" + currentTime.getHours()).slice(-2),
      minutes = ("0" + currentTime.getMinutes()).slice(-2),
      seconds = ("0" + currentTime.getSeconds()).slice(-2),
      fullTime = `${hour}:${minutes}:${seconds}`;
  
    let toastTemplate = `
        <div class="toast-container position-fixed bottom-0 end-0" id="toast--notification">
            <div id="toast__identier-success" class="toast" role="alert">
                <div class="toast--notification__header">
                    <div>
                        <strong>${title}</strong>
                        <small>${fullTime}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast--notification__body">${message}</div>
            </div>
        </div>
    `;
  
    const toastContainer = document.querySelector(".toast--notification");
  
    toastContainer.innerHTML = toastTemplate;
  
    const toast = document.getElementById("toast__identier-success"),
      createToast = bootstrap.Toast.getOrCreateInstance(toast);
  
    createToast.show();
  
    toast.addEventListener("hidden.bs.toast", () => {
      toastContainer.innerHTML = "";
    });
  };
  
  function buildWarning(data) {
    let inputSelector;
    Object.keys(data).forEach((e) => {
      if (inputSelector = document.querySelector("input[name= " + e + "]")) {
        inputSelector = document.querySelector("input[name= " + e + "]").parentNode;
        let smallTextMessage = document.createElement("small");
        let content = document.createTextNode(data[e].message);
  
        let hasFeedback = inputSelector.nextElementSibling;
        if (hasFeedback) {
            if(hasFeedback.nodeName === 'SMALL'){
                hasFeedback.remove();
            }
        }
  
        if (data[e].status === "invalid") {
          smallTextMessage.classList.add("small--valid__message");
          smallTextMessage.appendChild(content);
  
          inputSelector.insertAdjacentElement("afterend", smallTextMessage);
        }
      }
      if (inputSelector = document.querySelector("textarea[name= " + e + "]")) {
        inputSelector = document.querySelector("textarea[name= " + e + "]").parentNode;
        let smallTextMessage = document.createElement("small");
        let content = document.createTextNode(data[e].message);
  
        let hasFeedback = inputSelector.nextElementSibling;
        if (hasFeedback) {
            if(hasFeedback.nodeName === 'SMALL'){
                hasFeedback.remove();
            }
        }
  
        if (data[e].status === "invalid") {
          smallTextMessage.classList.add("small--valid__message");
          smallTextMessage.appendChild(content);
  
          inputSelector.insertAdjacentElement("afterend", smallTextMessage);
        }
      }
      if (inputSelector = document.querySelector("select[name= " + e + "]")) {
        inputSelector = document.querySelector("select[name= " + e + "]").parentNode;
        let smallTextMessage = document.createElement("small");
        let content = document.createTextNode(data[e].message);
  
        let hasFeedback = inputSelector.nextElementSibling;
        if (hasFeedback) {
            if(hasFeedback.nodeName === 'SMALL'){
                hasFeedback.remove();
            }
        }
  
        if (data[e].status === "invalid") {
          smallTextMessage.classList.add("small--valid__message");
          smallTextMessage.appendChild(content);
  
          inputSelector.insertAdjacentElement("afterend", smallTextMessage);
        }
      }
    });
  }
  
  export var statusCode = {
    404: (data) => {
      buildToast(data.warning.title, data.warning.message);
    },
    422: (data) => {
      buildWarning(data.status);
    },
    428: (data) => {
      buildToast(data.warning.title, data.warning.message);
    },
    500: (data) => {
      buildToast(data.warning.title, data.warning.message);
    },
    default: () => {
      buildToast("Desconocido", "No se pudo identificar el error");
    },
  };
  