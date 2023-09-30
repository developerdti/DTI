document.addEventListener("DOMContentLoaded", () => {
    enableJobcodestemplate();
    searchUser();
    callFunction();
    // console.log(request);
});

function callFunction() {
    var callFunction = {
        'searchUsersInfo': (id, apiName) => {
            searchUsersInfo(id, apiName);
        },
        'templateSelectClient': (id, apiName) => {
            templateSelectClient(id);
        },
        'templateSelectManager': (id, apiName) => {
            templateSelectManager(id);
        },
        'managerSelectedModifyPermissions': (id, apiName) => {
            managerSelectedModifyPermissions(id);
        },
        'modifyJobcodePermissions': (id, apiName) => {
            modifyJobcodePermissions(id,apiName);
        },
        'disableUser': (id, apiName) => {
            disableUser(id,apiName);
        },
        'enableUser': (id, apiName) => {
            enableUser(id,apiName);
        },
        'enableJobcodeTemplatePills': (id, apiName) => {
            enableJobcodeTemplatePills(id);
        },
        'templateSelectProfile': (id, apiName) => {
            templateSelectProfile(id);
        },
        'getManager': (id, apiName) => {
            getManager(apiName);
        },
        'managerSelected': (id, apiName) => {
            managerSelected(id);
        },
        'showModalModifyPermissions': (id, apiName) => {
            showModalModifyPermissions(id);
        },
        'enableJobcodes': (id, apiName) => {
            enableJobcodes(id, apiName);
        },
        'refusedJobcodes': (id, apiName) => {
            refusedJobcodes(id, apiName);
        },
        default: () => {
            console.log('Error');
        },
    };

    $(document).on(
        "click",
        'button',
        function (e) {
            var functionName = this.getAttribute('function');
            
            if (callFunction.hasOwnProperty(functionName)) {
                callFunction[functionName](this.id, functionName);
            }
        }
    );

    $(document).on(
        "change",
        'select',
        function (e) {
            var functionName = this.getAttribute('function');

            if (callFunction.hasOwnProperty(functionName)) {
                callFunction[functionName](this.id, functionName);
            }
        }
    );
}

function fetchApiWithContent(apiName, Data) {
    return new Promise((resolve) => {
        let status;
        fetch("Permissions/" + apiName + "", {
            method: "POST",
            body: JSON.stringify(Data),
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
                        return resolve(data);
                    } else if (module.statusCode.hasOwnProperty(status)) {
                        module.statusCode[status](data);
                        return resolve(false);
                    } else {
                        module.statusCode["default"]();
                        return resolve(false);
                    }
                });
            })
            .catch((error) => {
                console.log(error);
                import("./helper.js").then((module) => {
                    module.statusCode["default"]();
                });
                return resolve(false);
            });
    });
}

function fetchApiWithForm(apiName, formData) {
    return new Promise((resolve) => {
        let status;
        fetch("Permissions/" + apiName + "", {
            method: "POST",
            body: new FormData(formData)
        })
            .then((response) => {
                status = response.status;
                return response.json();
            })
            .then((data) => {
                import("./helper.js").then((module) => {
                    if (status === 200) {
                        return resolve(data);
                    } else if (module.statusCode.hasOwnProperty(status)) {
                        module.statusCode[status](data);
                        return resolve(false);
                    } else {
                        module.statusCode["default"]();
                        return resolve(false);
                    }
                });
            })
            .catch((error) => {
                console.log(error);
                import("./helper.js").then((module) => {
                    module.statusCode["default"]();
                });
                return resolve(false);
            });
    });
}
/*----------------------------------------------Buscar usuarios----------------------------------------------*/
function searchUser() {
    const searchForm = document.forms['form__searchUser'];
    const divSearchUserTab = document.getElementById('div__SearchUsers-tab');
    const pillSearchUserDiv = document.getElementById('SearchUser__userResult');
    
    searchForm.addEventListener('submit', e => {
        e.preventDefault();
    })
    
    $("#input--SearchUser").on("input",async function (event) {
    var data = await fetchApiWithForm('searchUsers',searchForm);
    if (!data) {
        return;
    }
    
    pillSearchUserDiv.innerHTML = "";
    divSearchUserTab.innerHTML = data.templateTab;
    });
}

async function searchUsersInfo(buttonId, apiName) {
    const searchUserButton = document.getElementById(buttonId);
    const rowData = [searchUserButton.value];
    const pillSearchUserDiv = document.getElementById('SearchUser__userResult');

    var data = await fetchApiWithContent(apiName, rowData);
    if (!data) {
        return
    }
    console.log(data);
    pillSearchUserDiv.innerHTML = data.templatePills;
    userInfoSearch = data.userInfo;
}

function showModalModifyPermissions(buttonId) {
    const 
        modifyUserPermissionsButton = document.getElementById(buttonId),
        selectProfile = document.getElementById('modifyPermissions-selectProfile'),
        divSelectClient = document.getElementById(`modifyPermissions--client`),
        divSelectManager = document.getElementById(`modifyPermissions--manager`),
        divButtons = document.getElementById('fieldsetmodifyPermissions--buttonsDiv');
        
    var options;
    request.profile.forEach((e) => {
        options += `
            <option value="${e['id']}">${e['name']}</option>
        `;
    });
    divSelectClient.innerHTML = "";
    divSelectManager.innerHTML = "";

    divButtons.innerHTML = `
        <input type="hidden" name="jobcode" value="${modifyUserPermissionsButton.value}">
        <button id="modifyPermissionsEnableButton" value="${modifyUserPermissionsButton.value}" type="button" 
        class="enableUser__button--enable" function="modifyJobcodePermissions" data-bs-dismiss="modal"
        disabled>Modificar permisos</button>
    `
    
    selectProfile.innerHTML = `
        <option selected>Elige...</option>
            ${options}
    `;
}

function templateSelectClient(selectId){
    const
        selectProfile = document.getElementById(selectId),
        divSelectClient = document.getElementById(`modifyPermissions--client`),
        divSelectManager = document.getElementById(`modifyPermissions--manager`),
        buttonModifyPermissions = document.getElementById(`modifyPermissionsEnableButton`);

    var optionsClient, selectClient,
        profile = request.profile.find((e) => e.id === selectProfile.value);

    if (selectProfile.value === "Elige...") {
        buttonModifyPermissions.disabled = true;
        divSelectClient.innerHTML = "";
        divSelectManager.innerHTML = "";
        return;
    }
    if (!("needsgroup" in profile)) {
        request.client.forEach((key) => {
            optionsClient += `
                <option value="${key["id"]}">${key["name"]}</option>
            `;
        });
        selectClient = `
            <label class="" for="modifyPermissions--selectClient">Cliente</label>
            <select id="modifyPermissions--selectClient" function="templateSelectManager"
            class="form-select modifyPermissions__selectClient" name="Client">
                <option selected>Elige...</option>
                    ${optionsClient}
            </select>           
        `;
        divSelectClient.innerHTML = selectClient;
        divSelectManager.innerHTML = "";
        buttonModifyPermissions.disabled = true;
        return;
    }

    divSelectClient.innerHTML = "";
    divSelectManager.innerHTML = "";
    buttonModifyPermissions.disabled = false;
}

async function templateSelectManager(selectId){
    const
        selectClient = document.getElementById(selectId),
        selectProfile = document.getElementById('modifyPermissions-selectProfile'),
        buttonModifyPermissionsUser = document.getElementById(`modifyPermissionsEnableButton`),
        divSelectManager = document.getElementById(`modifyPermissions--manager`),
        formData = document.forms['form__modifyPermissions'];
    var selectManagers, optionsManager;

    if (selectClient.value === "Elige...") {
        buttonModifyPermissionsUser.disabled = true;
        return;
    }
    var profilevalid = request.profile.find((e) => e.id === selectProfile.value);
    if (!("needsmanager" in profilevalid)) {
        buttonModifyPermissionsUser.disabled = false;
        return;
    }
    var data = await fetchApiWithForm('getManager',formData);
    if (!data) {
        divSelectManager.innerHTML = "";
        buttonModifyPermissionsUser.disabled = true;
        return;
    }

    data['managers'].forEach((key) => {
        optionsManager += `
            <option value="${key["id"]}">${key["name"]}</option>
        `;
    });
    selectManagers = `
        <label class="" for="modifyPermissions--selectManager">Supervisor</label>
        <select class="form-select modifyPermissions__selectManager"  function="managerSelectedModifyPermissions"
        name="Manager" id="modifyPermissions--selectManager">
        <option selected>Elige...</option>
            ${optionsManager}
        </select>
    `;
    divSelectManager.innerHTML = selectManagers;
}

function managerSelectedModifyPermissions(selectId) {
    const
        buttonModifyPermissionsUser = document.getElementById(`modifyPermissionsEnableButton`),
        selectManager = document.getElementById(selectId);

    if (selectManager.value === "Elige...") {
        buttonModifyPermissionsUser.disabled = true;
        return;
    }
    buttonModifyPermissionsUser.disabled = false;
}
async function modifyJobcodePermissions(buttonid, apiName) {
    const formData = document.forms['form__modifyPermissions'];
    var data = await fetchApiWithForm(apiName, formData);
    if (!data) {
        return;
    }

    searchUsersInfo(buttonid,'searchUsersInfo')
}

async function disableUser(buttonid,apiName){
    const disableUserButton = document.getElementById(buttonid);
    const rowData = [disableUserButton.value];
    var data = await fetchApiWithContent(apiName, rowData);
    if (!data) {
        return
    }

    searchUsersInfo(buttonid,'searchUsersInfo')
}
async function enableUser(buttonid,apiName){
    const disableUserButton = document.getElementById(buttonid);
    const rowData = [disableUserButton.value];
    var data = await fetchApiWithContent(apiName, rowData);
    if (!data) {
        return
    }

    searchUsersInfo(buttonid,'searchUsersInfo')
}
/*---------------------------------------------habilitar claves-----------------------------------------------*/
function enableJobcodestemplate() {
    let listTabs = "";

    const enableJobcodesTabList = document.getElementById('enableJobcodes-tabList');
    request.users.forEach((e) => {
        listTabs += `
                <li>
                    <button class="nav-link" id="${e['jobCode']}-tab" value="${e['jobCode']}" 
                    function="enableJobcodeTemplatePills" 
                    data-bs-toggle="pill" type="button">
                        ${e['name']}<span>Claves: </span> ${e['jobCode']}
                    </button>
                </li>
            `;
    });
    enableJobcodesTabList.innerHTML = listTabs;
}

function enableJobcodeTemplatePills(buttonId) {
    const enableJobcodesButton = document.getElementById(buttonId);
    var user = request.users.find((e) => e.jobCode === enableJobcodesButton.value);
    const enableJobcodesPill = document.getElementById('enableJobcodes-pill');
    var options = '';

    request.profile.forEach((e) => {
        options += `
                <option value="${e['id']}">${e['name']}</option>
            `;
    });

    var userInfo = `
        <div class="tab-pane enableUser">
            <form id="form__enableUser" name="form--enableUser" class="enableUser__form">
                <fieldset class="enableUser__fieldset">
                    <span>
                        <i class="bi bi-person-vcard"></i>
                        <p>
                            ${user['name']}
                        </p>
                        <p>
                            ${user['jobCode']}
                        </p>
                    </span>

                    <input type="hidden" name="name" value="${user['name']}">
                    <input type="hidden" name="jobcode" value="${user['jobCode']}">

                    <div class="enableUser__selectProfile">
                        <label for="enableUser--selectProfile">Perfil</label>
                        <select id="enableUser--selectProfile" function="templateSelectProfile" 
                        class="form-select enableUser__selectProfile" name="profile">
                            <option selected>Elige...</option>
                            ${options}
                        </select>
                    </div>
                    <div class="enableUser__selectClient" id="enableUser--client">

                    </div>
                    <div class="enableUser__selectManager" id="enableUser--manager">

                    </div>
                    <div class="enableUser__button enableUser__button">
                        <button id="enableUserbuttonId" type="button" value="${user['jobCode']}" function="enableJobcodes" 
                        class="enableUser__button--enable" disabled>Habilitar usuario</button>
                        <button id="refusedUserbuttonId" type="button" value="${user['jobCode']}" function="refusedJobcodes" 
                        class="enableUser__button--refused">Rechazar Usuario</button>
                    </div>
                </fieldset>
            </form>
        </div>
        `;

    enableJobcodesPill.innerHTML = userInfo;
}

function templateSelectProfile(selectId) {
    const
        selectProfile = document.getElementById(selectId),
        divSelectClient = document.getElementById(`enableUser--client`),
        buttonEnableUser = document.getElementById(`enableUserbuttonId`),
        divSelectManager = document.getElementById(`enableUser--manager`);

    var optionsClient, selectClient,
        profile = request.profile.find((e) => e.id === selectProfile.value);

    if (selectProfile.value === "Elige...") {
        buttonEnableUser.disabled = true;
        divSelectClient.innerHTML = "";
        divSelectManager.innerHTML = "";
        return;
    }
    if (!("needsgroup" in profile)) {
        request.client.forEach((key) => {
            optionsClient += `
                <option value="${key["id"]}">${key["name"]}</option>
            `;
        });
        selectClient = `
            <label class="" for="enableUser--selectClient">Cliente</label>
            <select id="enableUser--selectClient" function="getManager"
            class="form-select enableUser__selectClient" name="Client">
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

async function getManager(apiName) {
    const
        selectProfile = document.getElementById('enableUser--selectProfile'),
        buttonEnableUser = document.getElementById(`enableUserbuttonId`),
        divSelectManager = document.getElementById(`enableUser--manager`),
        formData = document.forms['form__enableUser'];
    var selectManagers, optionsManager;

    var profilevalid = request.profile.find((e) => e.id === selectProfile.value);
    if (!("needsmanager" in profilevalid)) {
        buttonEnableUser.disabled = false;
        return;
    }
    var data = await fetchApiWithForm(apiName, formData);
    if (!data) {
        divSelectManager.innerHTML = "";
        buttonEnableUser.disabled = true;
        return;
    }

    data['managers'].forEach((key) => {
        optionsManager += `
            <option value="${key["id"]}">${key["name"]}</option>
        `;
    });
    selectManagers = `
        <label class="" for="enableUser--selectManager">Supervisor</label>
        <select class="form-select enableUser__selectManager" function="managerSelected" name="Manager" id="enableUser--selectManager">
        <option selected>Elige...</option>
            ${optionsManager}
        </select>
    `;
    divSelectManager.innerHTML = selectManagers;
}

function managerSelected(selectId) {
    const
        buttonEnableUser = document.getElementById('enableUserbuttonId'),
        selectManager = document.getElementById(selectId);

    if (selectManager.value === "Elige...") {
        buttonEnableUser.disabled = true;
        return;
    }
    buttonEnableUser.disabled = false;
}

async function enableJobcodes(buttonid, apiName) {
    const buttonEnableJobcode = document.getElementById(buttonid);
    const formElement = document.getElementById('form__enableUser');
    const formData = document.forms['form__enableUser'];
    var data = await fetchApiWithForm(apiName, formData);
    if (!data) {
        return;
    }

    $('#' + buttonEnableJobcode.value + '-tab').remove();
    $(formElement).remove();
}

async function refusedJobcodes(buttonid, apiName) {
    const buttonEnableJobcode = document.getElementById(buttonid);
    const formElement = document.getElementById('form__enableUser');
    const formData = document.forms['form__enableUser'];
    var data = await fetchApiWithForm(apiName, formData);
    if (!data) {
        return;
    }

    $('#' + buttonEnableJobcode.value + '-tab').remove();
    $(formElement).remove();
}



