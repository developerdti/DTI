// const logoutEvent = document.getElementById('pbtn');

// logoutEvent.addEventListener('click',logOut);

var fetchStatus = {
    // 200:()=> {window.location.reload();},
    422:()=> {
        alert('error 422');
    },
    303:()=> {
        alert('error 303');
    },
    default: () => {alert("algo paso");}
}

function logOut(){
    fetch('Schedule/getAgreements',{method:'POST'})
        .then(response =>{
            console.log(response);
            return response.json();
            // if(fetchStatus.hasOwnProperty(response.status)){
            //     return fetchStatus[response.status]();
            // }
            // return fetchStatus.default;
        })
        .then(data => console.log(data));
}