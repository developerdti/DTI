document.addEventListener("DOMContentLoaded", () => {
    enableJobcodestemplate();
    profile();
    getManager();
    enableJobcodes();
    refusedJobcode();
    managerSelected();
    searchUser();
    userInfo();
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
                        <i class="bi bi-person-vcard"></i>
                        <p>
                            ${e['name']}
                        </p>
                        <p>
                            ${e['jobCode']}
                        </p>
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
            const formUserEnableJobcode = document.forms[this.parentNode.parentNode.parentNode.id];
            const jobcodeID = formUserEnableJobcode.elements[2].value;
            const divSelectClient = document.getElementById(`select--client${jobcodeID}`);
            const 
                buttonEnableUser = document.getElementById(`enableUserbuttonId${jobcodeID}`),
                divSelectManager = document.getElementById(`select--manager${jobcodeID}`);
            
            let optionsClient = "", selectClient = "";

            if (this.value === "Elige...") {
                buttonEnableUser.disabled = true;
                divSelectClient.innerHTML = "";
                divSelectManager.innerHTML = "";
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
                        <label class="" for="select__Client${jobcodeID}">Cliente</label>
                        <select class="form-select fieldsetPermission__select-client" name="Client" id="select__Client${jobcodeID}">
                            <option selected>Elige...</option>
                                ${optionsClient}
                        </select>           
                    `;
                divSelectClient.innerHTML = selectClient;
                divSelectManager.innerHTML = "";
                buttonEnableUser.disabled = true;
                return;
            }

            divSelectClient.innerHTML = "";
            divSelectManager.innerHTML = "";
            buttonEnableUser.disabled = false;
        }
    );
}

function getManager() {
    let status;
    $(document).on(
        "change",
        'select[class~="fieldsetPermission__select-client"]',
        function (e) {
            const
                formUserEnableJobcode = document.forms[this.parentNode.parentNode.parentNode.id],
                jobcodeID = formUserEnableJobcode.elements[2].value,
                selectProfile = document.getElementById(`select--Profile${jobcodeID}`),
                buttonEnableUser = document.getElementById(`enableUserbuttonId${jobcodeID}`),
                divSelectManager = document.getElementById(`select--manager${jobcodeID}`);

            let profilevalid = request.profile.find((e) => e.id === selectProfile.value),
                selectManager = '', optionsManager = '';

            if (!("needsmanager" in profilevalid)) {
                buttonEnableUser.disabled = false;
                return;
            }
            fetch("Permissions/getManager", {
                method: "POST",
                body: new FormData(formUserEnableJobcode)
            })
                .then((response) => {
                    status = response.status;
                    return response.json();
                })
                .then((data) => {
                    import("./helper.js").then((module) => {
                        if (status === 200) {
                            data['managers'].forEach((key) => {
                                optionsManager += `
                                        <option value="${key["id"]}">${key["name"]}</option>
                                    `;
                            });
                            selectManager = `
                                    <label class="" for="select__Manager${jobcodeID}">Supervisor</label>
                                    <select class="form-select fieldsetPermission__select-manager" name="Manager" id="select__Manager${jobcodeID}">
                                        <option selected>Elige...</option>
                                            ${optionsManager}
                                    </select>
                                `;
                            divSelectManager.innerHTML = selectManager;
                        } else if (module.statusCode.hasOwnProperty(status)) {
                            module.statusCode[status](data);
                            divSelectManager.innerHTML = "";
                            buttonEnableUser.disabled = true;
                        } else {
                            divSelectManager.innerHTML = "";
                            buttonEnableUser.disabled = true;
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

function managerSelected(){
    $(document).on(
        "change",
        'select[class~="fieldsetPermission__select-manager"]',
        function (e) {
            const formUserEnableJobcode = document.forms[this.parentNode.parentNode.parentNode.id];
            const jobcodeID = formUserEnableJobcode.elements[2].value;
            const buttonEnableUser = document.getElementById(`enableUserbuttonId${jobcodeID}`);
            
            if (this.value === "Elige...") {
                buttonEnableUser.disabled = true;
                return;
            }
            buttonEnableUser.disabled = false;
        }
    );
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

function refusedJobcode(){
    let status;
    $(document).on(
        "click",
        'button[class~="fieldsetPermission__button--refused"]',
        function (e) {
            const formElement = this.parentNode.parentNode.parentNode;
            const userform = document.forms[this.parentNode.parentNode.parentNode.id];

            fetch("Permissions/refusedJobcodes", {
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
                            $(formElement).remove();
                            console.log(data);

                            import("./helper.min.js").then((module) => {
                                module.buildToast(data.exito.title, data.exito.message);
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

function searchUser() {
    let status;
    $("#input--SearchUser").on("input", function (event) {
        console.log(this.value);
        const searchForm = document.forms['form__searchUser'];
        const divSearchUserTab = document.getElementById('div__SearchUsers-tab');

        fetch("Permissions/searchUsers", {
            method: "POST",
            body: new FormData(searchForm)
        })
            .then((response) => {
                status = response.status;
                return response.json();
            })
            .then((data) => {
                import("./helper.js").then((module) => {
                    if (status === 200) {
                        divSearchUserTab.innerHTML = data.templateTab;
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

function userInfo(){
    let status;
    $(document).on(
        "click",
        'button[class~="SearchUser__listPills-button"]',
        function (e) {
            console.log(this);
            // const formElement = this.parentNode.parentNode.parentNode;
            // const userform = document.forms[this.parentNode.parentNode.parentNode.id];

            // fetch("Permissions/enableJobcodes", {
            //     method: "POST",
            //     body: new FormData(userform)
            // })
            //     .then((response) => {
            //         status = response.status;
            //         return response.json();
            //     })
            //     .then((data) => {
            //         import("./helper.js").then((module) => {
            //             if (status === 200) {
            //                 // $('#' + data['jobcode'] + '-tab').remove();
            //                 // $(formElement).remove();

            //                 // import("./helper.min.js").then((module) => {
            //                 //     module.buildToastSuccess(data.exito.title, data.exito.message);
            //                 // });
            //             } else if (module.statusCode.hasOwnProperty(status)) {
            //                 module.statusCode[status](data);
            //             } else {
            //                 module.statusCode["default"]();
            //             }
            //         });
            //     })
            //     .catch((error) => {
            //         console.log(error);
            //         import("./helper.js").then((module) => {
            //             module.statusCode["default"]();
            //         });
            //     });
        }
    );
}
