document.addEventListener("DOMContentLoaded", () => {
    enableJobcodestemplate();
    profile();
    getManager();
    enableJobcodes();
    console.log(request);
});

function enableJobcodestemplate() {
    let userInfo = "",
        options = "",
        listTabs = "";

    const divEnableJobcode = document.getElementById("EnableJobcodes");

    request.users.forEach((e) => {
        listTabs += `
                <li>
                    <button class="nav-link" id="${e['jobCode']}-tab" data-bs-toggle="pill" 
                    data-bs-target="#${e['jobCode']}" type="button">
                        ${e['name']}<span>Claves: </span> ${e['jobCode']}
                    </button>
                </li>
            `;
    });
    let UsersRequest = `
        <span>Solicitudes de usuarios</span> 
        <div class ="enableJobcodes">
            <div class="enableJobcodes__scrollTabs">
                <ul class="nav nav-pills enableJobcodes__tab-list">
                    ${listTabs}
                </ul>
            </div>
        </div>
    `;

    request.profile.forEach((e) => {
        options += `
            <option value="${e['id']}">${e['name']}</option>
        `;
    });
    request.users.forEach((e) => {
        userInfo += `
        <div class="tab-pane div--enableUsers" id="${e['jobCode']}">
            <form id="form__enableUser${e['jobCode']}" name="form--enableUser" class="form--enableUser">
                <fieldset class="form--enableUser__fieldset fieldsetPermission">
                    <span>
                        <i class="h2 bi bi-person"></i>${e['name']}
                    </span>
                    <span>
                        <i class="h2 bi bi-key-fill"></i>${e['jobCode']}
                    </span>
                    <input type="hidden" name="name" value="${e['name']}">
                    <input type="hidden" name="jobcode" value="${e['jobCode']}">

                    <div class="fieldsetPermission__profile">
                        <label for="select--Profile${e['jobCode']}">Perfil</label>
                        <select id="select--Profile${e['jobCode']}" class="form-select fieldsetPermission__select-profile" name="profile">
                            <option selected>Elige...</option>
                            ${options}
                        </select>
                    </div>
                    <div class="fieldsetPermission__client" id="select--client${e['jobCode']}">

                    </div>
                    <div class="fieldsetPermission__manager" id="select--manager${e['jobCode']}">

                    </div>
                    <div class="fieldsetPermission__button">
                        <button id="enableUserbuttonId${e['jobCode']}" type="button" class="fieldsetPermission__button--enable" disabled>Habilitar usuario</button>
                        <button type="button" class="fieldsetPermission__button--refused">Rechazar Usuario</button>
                    </div>
                </fieldset>
            </form>
        </div>
        `;
    });

    UsersRequest += `
        <div class="tab-content">
            ${userInfo}
        </div>
    `;

    divEnableJobcode.innerHTML = UsersRequest;
}

function profile() {
    $(document).on(
        "change",
        'select[class~="fieldsetPermission__select-profile"]',
        function (e) {
            const formpru = document.forms[this.parentNode.parentNode.parentNode.id];
            const jobcodeID = formpru.elements[2].value;
            let divSelectClient = document.getElementById(`select--client${jobcodeID}`);
            let buttonEnableUserID = document.getElementById(`enableUserbuttonId${jobcodeID}`);
            let optionsClient = "",
                selectClient = "";

            if (this.value === "Elige...") {
                buttonEnableUserID.disabled = true;
                divSelectClient.innerHTML = "";
                return;
            }
            let profile = request.profile.find((e) => e.id === this.value);
            if (!("needsgroup" in profile)) {
                request.client.forEach((key) => {
                    optionsClient += `
                            <option value="${key["id"]}">${key["name"]}</option>
                        `;
                });
                selectClient = `
                        <label class="" for="select__Client">Cliente</label>
                        <select class="form-select fieldsetPermission__select-client" name="Client" id="select__Client">
                            <option selected>Elige...</option>
                                ${optionsClient}
                        </select>           
                    `;
                divSelectClient.innerHTML = selectClient;
                buttonEnableUserID.disabled = true;
                return;
            }

            divSelectClient.innerHTML = "";
            buttonEnableUserID.disabled = false;
        }
    );
}

function getManager() {
    let status;
    $(document).on(
        "change",
        'select[class~="fieldsetPermission__select-client"]',
        function (e) {
            const profile = this.parentNode.previousElementSibling;
            let formManager = this.parentNode.nextElementSibling;
            let profilevalid = request.profile.find((e) => e.id === profile.childNodes[3].value);
            let validSibling = this.parentNode.nextElementSibling.nextElementSibling.firstChild;
            while (validSibling) {
                if (validSibling.className === 'fieldsetPermission__button--enable') {
                    buttonEnable = validSibling;
                }
                validSibling = validSibling.nextSibling;
            }

            if (!("needsmanager" in profilevalid)) {
                buttonEnable.disabled = false;
                return;
            }
            const manager = document.forms[this.parentNode.parentNode.parentNode.id];
            let selectManager = '', optionsManager = '';
            fetch("Permissions/getManager", {
                method: "POST",
                body: new FormData(manager)
            })
                .then((response) => {
                    status = response.status;
                    return response.json();
                })
                .then((data) => {
                    import("./helper.js").then((module) => {
                        if (status === 200) {
                            console.log(data['managers']);
                            data['managers'].forEach((key) => {
                                optionsManager += `
                                        <option value="${key["id"]}">${key["name"]}</option>
                                    `;
                            });
                            selectManager = `
                                    <label class="" for="select__Manager">Supervisor</label>
                                    <select class="form-select fieldsetPermission__select-client" name="Manager" id="select__Manager">
                                        <option selected>Elige...</option>
                                            ${optionsManager}
                                    </select>
                                `;
                            formManager.innerHTML = selectManager;
                            buttonEnable.disabled = false;
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
        }
    );
}

function searchUser() {
    $("#input--SearchUser").on("input", function (event) {
        console.log(this.value);
    });
}

function enableJobcodes() {
    let status;
    $(document).on(
        "click",
        'button[class~="fieldsetPermission__button--enable"]',
        function (e) {
            const formElement = this.parentNode.parentNode.parentNode;
            const userform = document.forms[this.parentNode.parentNode.parentNode.id];

            fetch("Permissions/enableJobcodes", {
                method: "POST",
                body: new FormData(userform)
            })
                .then((response) => {
                    status = response.status;
                    return response.json();
                })
                .then((data) => {
                    import("./helper.js").then((module) => {
                        if (status === 200) {
                            $('#' + data['jobcode'] + '-tab').remove();
                            console.log(data.success);
                            $(formElement).remove();

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
        }
    );
}
