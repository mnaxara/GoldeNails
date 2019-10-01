let agenda = document.getElementById('agenda');
let service = document.getElementById('service');

agenda.addEventListener(
    'click', function() {
        generateAgenda()
    }
);

service.addEventListener(
    'click', function() {
        serviceList();
    }
);

let result = document.getElementById('result');
// Genère le calendrier au clic d'"Agenda"

async function generateAgenda () {

    let response = await fetch(agendaPath , {
        method: 'POST',
    });

    let responseData = await response.text();
    if (response.ok === false){
        console.log('erreur');
    }
    else{
        result.innerHTML = responseData;
        // showRdv();
        rdvConfirm();
    }
}

// TODO: Affiche les rendez-vous validés du jour

// function showRdv () {
//     let rdv = document.querySelectorAll('.rdv');
//     rdv.forEach(function (element) {
//         element.addEventListener('click', async function () {
//           *A FAIRE*
//             }
//         });
//     });
// }

// Fonction de confirmation de rendez vous

function rdvConfirm () {
    let button = document.querySelectorAll('.buttonsRdv');
    button.forEach(function (element) {
        element.addEventListener('click',
            async function () {
                    let type = this.dataset.type;
                    let id = this.dataset.id;
                    let param = `type=${type}&id=${id}`;
                    let response = await fetch(rdvPath , {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: param
                    });
                    let responseData = await response.text();
                    if (response.ok === false){
                        console.log('erreur');
                    }
                    else{
                        generateAgenda();
                        document.getElementById('messageRdv').innerHTML = responseData;
                    }
        });
    });
}

// Affiche la liste des prestations en cours

async function serviceList () {

    let response = await fetch(servicePath , {
        method: 'POST',
    });

    let responseData = await response.text();
    if (response.ok === false){
        console.log('erreur');
    }
    else{
        result.innerHTML = responseData;
        serviceUpdate();
    }
}

// Fonction qui active les events listener sur la liste de service affiché dynamiquement.

function serviceUpdate() {
    // Event de Modification
    let updateButton = document.querySelectorAll('.serviceUpdate');

    updateButton.forEach(function (element) {
            element.addEventListener('click', async function (e) {
                e.preventDefault();

                let action = this.dataset.action;
                let id = this.dataset.service;
                let param = `action=${action}`;
                let regex = /\/\d*$/;


                switch (action) {
                    case 'update' :
                        serviceUpdateAjaxPath = serviceUpdateAjaxPath.replace(regex, '/' + id);
                        response = await fetch(serviceUpdateAjaxPath, {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded",
                            },
                            body: param
                        });

                        responseData = await response.text();

                        if (response.ok === false) {
                            console.log('erreur');
                        } else {
                            document.querySelector(`#updateRow`).innerHTML = responseData;
                            serviceUpdate()
                        }
                        break;
                    case 'delete' :
                        deleteServicePath = deleteServicePath.replace(regex, '/'+id);

                        response = await fetch(deleteServicePath , {
                            method: 'POST'
                        });

                        if (response.ok === false){
                            console.log('erreur');
                        }
                        else{
                            serviceList();
                        }
                        break;
                    case 'addNew' :
                        form = document.getElementById('upForm');
                        formData = new FormData(form);
                        // let param = `action=${action}&form=${formdata}`;
                        response = await fetch(addServicePath , {
                            method: 'POST',
                            body: formData
                        });

                        if (response.ok === false){
                            console.log('erreur');
                        }
                        else{
                            serviceList();
                        }
                        break;
                    case 'valid' :
                        form = document.getElementById('upForm');
                        formData = new FormData(form);
                        response = await fetch(serviceUpdateAjaxPath, {
                            method: 'POST',
                            body: formData
                        });
                        if (response.ok === false) {
                            console.log('erreur');
                        } else {
                            document.querySelector(`#updateRow`).innerHTML = '';
                            serviceList();
                        }
                        break;
                    case 'addShow' :
                        response = await fetch(addServicePath , {
                            method: 'POST',
                        });

                        responseData = await response.text();

                        if (response.ok === false){
                            console.log('erreur');
                        }
                        else{
                            document.querySelector(`#updateRow`).innerHTML = responseData;
                            serviceUpdate()
                        }
                        break;
                    default :
                        break;
                }
            });
        });
}
