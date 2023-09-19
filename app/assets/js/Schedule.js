document.addEventListener("DOMContentLoaded", () => {
  setgraphic();
  saveNote();
  getNotes();
  followUp();
  editTable();
  cancelChanges();
  saveChanges();
  promiseStatus();
  deleteNotes();
});

function promiseStatus() {
  $(document).on("change", 'select[class~="promiseSelect"]', function (e) {
    let row = this.parentNode.parentNode,
      rowclass = row.classList[0];

    row.classList.toggle(rowclass);

    this.value === 'Si' ? row.classList.toggle('table-success')
      : (this.value === 'No' ? row.classList.toggle('table-danger')
        : row.classList.toggle('table-warning'));
  });
}

function saveChanges() {
  $(document).on('click', 'button[class~="btn-followup-save"]', function (event) {
    let row = this.parentNode.parentNode;
    let child = row.firstChild;
    let i = 1, rowElement, rowData = [];
    let status;

    while (child.nextSibling) {
      child = child.nextSibling;


      if (child.nodeName === 'TD') {
        rowElement = child.firstChild;
        if (rowElement.nodeName === 'INPUT' || rowElement.nodeName === 'SELECT') {
          rowData.push(row.cells[i].firstChild.value);
          rowElement.disabled = true;
          child.classList.toggle('TD__border');
        }
        i++;
      }
    }

    rowData.push(row.cells[1].firstChild.innerHTML);

    this.parentNode.innerHTML = `
      <button type="button" class="btn btn-info btn-followup">
          <i class="bi bi-pencil-fill"></i>
      </button>
    `;

    fetch("Schedule/saveFollowUp", {
      method: "POST",
      body: JSON.stringify(rowData),
      headers: {
        "Content-Type": "application/json",
      },
    })
      .then((response) => {
        status = response.status;
        return response.json();
      })
      .then((data) => {
        import("./helper.js").then((module) => {
          if (status === 200) {
            console.log(data['success']);
          } else if (module.statusCode.hasOwnProperty(status)) {
            module.statusCode[status](data);
          } else {
            module.statusCode["default"]();
          }
        });
      })
      .catch((error) => {
        console.log(error);
        import("./helper.js").then((module) => {
          module.statusCode["default"]();
        });
      });

  });
}

function cancelChanges() {
  $(document).on('click', 'button[class~="btn-followup-cancel"]', function (event) {
    let row = this.parentNode.parentNode;
    let child = row.firstChild;
    let i = 1, rowElement

    while (child.nextSibling) {
      child = child.nextSibling;


      if (child.nodeName === 'TD') {
        rowElement = child.firstChild;
        if (rowElement.nodeName === 'INPUT' || rowElement.nodeName === 'SELECT') {
          rowElement.disabled = true;
          child.classList.toggle('TD__border');
        }
        i++;
      }
    }

    this.parentNode.innerHTML = `
    <button type="button" class="btn btn-info btn-followup">
                            <i class="bi bi-pencil-fill"></i>
                        </button>
    `
  });
}

function editTable() {
  $(document).on('click', 'button[class~="btn-followup"]', function (event) {
    let row = this.parentNode.parentNode;
    let child = row.firstChild;
    let i = 1, rowElement;

    while (child.nextSibling) {
      child = child.nextSibling;


      if (child.nodeName === 'TD') {
        rowElement = child.firstChild;
        if (rowElement.nodeName === 'INPUT' || rowElement.nodeName === 'SELECT') {
          // console.log(row.cells[i].firstChild.value);
          rowElement.disabled = false;
          child.classList.toggle('TD__border');
        }
        i++;
      }
    }
    // console.log(row.cells[1].firstChild.innerHTML);

    this.parentNode.innerHTML = `
    <button type="button" class="btn btn-success btn-followup-save">
      <i class="bi bi-check2"></i>
    </button>
    <button type="button" class="btn btn-danger btn-followup-cancel">
      <i class="bi bi-x"></i>
    </button>
    `
  });
}

function setgraphic() {
  (async function () {
    var data = [
      {
        name: "Mes anterior",
        bankPayment: beforeBankpayment,
        amount: beforeAmount,
        capital: beforeCapital,
      },
      {
        name: "Mes actual",
        bankPayment: currentBankpayment,
        amount: currentAmount,
        capital: currentCapital,
      },
      {
        name: "Hoy",
        bankPayment: dayBankpayment,
        amount: dayAmount,
        capital: dayCapital,
      },
    ];

    new Chart(document.getElementById("Promises"), {
      type: "bar",
      data: {
        labels: data.map((row) => row.name),
        datasets: [
          {
            label: "Monto",
            data: data.map((row) => row.amount),
          },
          {
            label: "Capital",
            data: data.map((row) => row.capital),
          },
          {
            label: "Pago Banco",
            data: data.map((row) => row.bankPayment),
          },
        ],
      },
    });
  })();
}

function saveNote() {
  const commentButton = document.getElementById("sendForm--comment");
  const formNote = document.forms["scheduleForm"];
  const form = document.getElementById("scheduleForm");
  var status;
  commentButton.addEventListener("click", () => {
    fetch("Schedule/notes", {
      method: "POST",
      body: new FormData(formNote),
    })
      .then((response) => {
        status = response.status;
        return response.json();
      })
      .then((data) => {
        import("./helper.js").then((module) => {
          if (status === 200) {
            form.reset();
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
        console.log(error);
        import("./helper.js").then((module) => {
          module.statusCode["default"]();
        });
      });
  });
}

function followUp() {
  const buttonfollowup = document.getElementById("buttonModal--follow-up");
  const modalTable = document.getElementById("tableBody");
  const title = document.getElementById("title--modal");
  let status;
  buttonfollowup.addEventListener("click", () => {
    fetch("Schedule/getFollowUp", {
      method: "POST",
    })
      .then((response) => {
        status = response.status;
        return response.json();
      })
      .then((data) => {
        import("./helper.js").then((module) => {
          if (status === 200) {
            modalTable.innerHTML = data['table'];
            title.innerHTML = "Seguimiento";
          } else if (module.statusCode.hasOwnProperty(status)) {
            module.statusCode[status](data);
          } else {
            module.statusCode["default"]();
          }
        });
      })
      .catch((error) => {
        console.log(error);
        import("./helper.js").then((module) => {
          module.statusCode["default"]();
        });
      });
  });
}

function getNotes() {
  const buttonviewnote = document.getElementById("buttonModal--notes");
  const modalTable = document.getElementById("tableBody");
  const title = document.getElementById("title--modal");
  let status;
  buttonviewnote.addEventListener("click", () => {
    fetch("Schedule/getNotes", {
      method: "POST",
    })
      .then((response) => {
        status = response.status;
        return response.json();
      })
      .then((data) => {
        import("./helper.js").then((module) => {
          if (status === 200) {
            modalTable.innerHTML = data['notes'];
            title.innerHTML = 'Notas';
          } else if (module.statusCode.hasOwnProperty(status)) {
            module.statusCode[status](data);
          } else {
            module.statusCode["default"]();
          }
        });
      })
      .catch((error) => {
        console.log(error);
        import("./helper.js").then((module) => {
          module.statusCode["default"]();
        });
      });
  });
}

function deleteNotes() {
  $(document).on('click', 'button[class~="btn_delete__notes"]', function (event) {
    let button = this.id;
    let status;
    var rowData = {
      'id': button
    };

    fetch("Schedule/deleteNote", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify(rowData),
    })
      .then((response) => {
        status = response.status;
        return response.json();
      })
      .then((data) => {
        import("./helper.js").then((module) => {
          if (status === 200) {
            $(this).closest('tr').remove();
          } else if (module.statusCode.hasOwnProperty(status)) {
            module.statusCode[status](data);
          } else {
            module.statusCode["default"]();
          }
        });
      })
      .catch((error) => {
        console.log(error);
        import("./helper.js").then((module) => {
          module.statusCode["default"]();
        });
      });

  });
}
