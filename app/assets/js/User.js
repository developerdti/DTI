document.addEventListener('DOMContentLoaded',() =>{
    changePassword();
})

function changePassword(){
    const changePasswordForm = document.forms['user__changepassword'];
    let status;

    $('#button__submit--changepassword').on('click',()=>{
        fetch('User/changePassword',{
            method: 'POST',
            body: new FormData(changePasswordForm)
        })
        .then(response =>{
            status = response.status;
            return response.json();
        })
        .then(data =>{
            import("./helper.js").then((module) => {
                if (status === 200) {
                  changePasswordForm.reset();
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
    })
    
}



