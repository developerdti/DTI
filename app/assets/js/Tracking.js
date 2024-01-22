document.addEventListener('DOMContentLoaded',()=>{
    $(document).on('submit','form',function(e){
        e.preventDefault();
    });

    $(document).on('click', 'button[class~="tracking__buttons--send"]', function (e) {
        sendFolio();
    });

});

function fetchApiWithContent(apiName, Data) {
    return new Promise((resolve) => {
        let status;
        fetch("Tracking/" + apiName + "", {
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
        fetch("Tracking/" + apiName + "", {
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

async function sendFolio(){
    const formTracking = document.getElementById('form--tracking');
    const tableBody = document.getElementById('tableTrack--Body');
    const data = await fetchApiWithForm('sendFolio', formTracking);
    if (!data) return;
    
    
    tableBody.innerHTML = data['tableFolioTmeplate'];
    formTracking.reset();
    import("./helper.min.js").then((module) => {
      module.buildWarning(data.status);
    });
    import("./helper.min.js").then((module) => {
      module.buildToastSuccess(data.exito.title, data.exito.message);
    });
}