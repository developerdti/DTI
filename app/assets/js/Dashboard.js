var buttonStatus = true;

document.addEventListener('DOMContentLoaded', () => {
    $(document).on('click', '#button__collapseDashboard', function (e) {
        if(!buttonStatus) enableDeleteImage();
        addImage();
    });

    $(document).on('click', 'button[class~="collapseDashboard__buttonPill"]', function (e) {
        getTemplate(this.value);
    });

    $(document).on('click', '#button__deleteImage', function (e) {
        enableDeleteImage();
    });
    
    $(document).on('click', 'button[class~="deleteImage__Button"]', function (e) {
        deleteImage(this);
    });
    
})

function fetchApiWithContent(apiName, Data) {
    return new Promise((resolve) => {
        let status;
        fetch("Dashboard/" + apiName + "", {
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
        fetch("Dashboard/" + apiName + "", {
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

async function addImage() {
    const formDashboard = document.getElementById("form__collapseDashboard");
    const imagesContainer = document.getElementById("collapseDashboard--deploymentHeader");
    const data = await fetchApiWithForm('addImage', formDashboard);
    if (!data) return;
    
    formDashboard.reset();
    
    imagesContainer.innerHTML = data.templateImage;
    import("./helper.min.js").then((module) => {
        module.buildWarning(data.status);
    });
    import("./helper.min.js").then((module) => {
        module.buildToastSuccess(data.exito.title, data.exito.message);
    });
}

async function getTemplate(id){
    const dataContent = [id];
    
    const deploymentDiv = document.getElementById("collapseDashboard--alterSection");
    const data = await fetchApiWithContent('getTemplateSection', dataContent);
    if (!data) return;
    
    deploymentDiv.innerHTML = data;
}

function enableDeleteImage(){
    const buttonEnableDelete = document.getElementById('button__deleteImage');
    console.log()
    if(buttonStatus){
        buttonEnableDelete.innerHTML = 'OK';
        buttonStatus = false;
    } else{
        buttonEnableDelete.innerHTML = 'Eliminar Imagen';
        buttonStatus = true;
    }
    
    let imagenes = document.getElementsByClassName('deleteImage__Button');
    let imagenToggle = imagenes[0],i = 0;
    
    while (imagenToggle) {
        imagenToggle.classList.toggle('displayNone');
        i++;
        imagenToggle = imagenes[i];
    }
}

// function addClassNone(){
//     let imagenes = document.getElementsByClassName('deleteImage__Button');
//     let imagenToggle = imagenes[0],i = 0;
    
//     while (imagenToggle) {
//         imagenToggle.classList.add('displayNone');
//         i++;
//         imagenToggle = imagenes[i];
//     }
// }

async function deleteImage(button){
    const dataContent = [button.value];
    
    const data = await fetchApiWithContent('deleteImage', dataContent);
    if (!data) return;
    
    button.parentNode.remove();
    import("./helper.min.js").then((module) => {
        module.buildToastSuccess(data.exito.title, data.exito.message);
    });
}