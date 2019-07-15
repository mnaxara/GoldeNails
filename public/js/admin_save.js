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
                serviceUpdateAjaxPath = serviceUpdateAjaxPath.replace(regex, '/' + id);
                console.log(id, serviceUpdateAjaxPath);

                if (action === 'update') {
                    let response = await fetch(serviceUpdateAjaxPath, {
                        method: 'POST',
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: param
                    });

                    let responseData = await response.text();

                    if (response.ok === false) {
                        console.log('erreur');
                    } else {
                        document.querySelector(`#updateRow`).innerHTML = responseData;
                        serviceUpdate()
                    }
                } else {
                    let form = document.getElementById('upForm');
                    let formData = new FormData(form);
                    // let param = `action=${action}&form=${formdata}`;
                    let response = await fetch(serviceUpdateAjaxPath, {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok === false) {
                        console.log('erreur');
                    } else {
                        document.querySelector(`#updateRow`).innerHTML = '';
                        serviceList();
                    }
                }

            });
        });


    // Suppression d'un Service

    let deleteButton = document.querySelectorAll('.serviceDelete');

    deleteButton.forEach(function (element) {
        element.addEventListener('click', async function (e) {
            e.preventDefault();

            let id = this.dataset.service;
            let regex = /\/\d*$/;
            deleteServicePath = deleteServicePath.replace(regex, '/'+id);

            let response = await fetch(deleteServicePath , {
                method: 'POST'
            });

            if (response.ok === false){
                console.log('erreur');
            }
            else{
                serviceList();
            }

        });
    });

        // Event Ajout Service

    let addButton = document.querySelector('#addButton');

    addButton.addEventListener('click', async function (e) {
        e.preventDefault();

        let response = await fetch(addServicePath , {
            method: 'POST',
        });

        let responseData = await response.text();

        if (response.ok === false){
            console.log('erreur');
        }
        else{
            document.querySelector(`#updateRow`).innerHTML = responseData;
            serviceUpdate()
        }


    });

    let serviceAdd = document.querySelector('#serviceAdd');

    serviceAdd.addEventListener('click',
        async function (e) {
                e.preventDefault();

                let form = document.getElementById('upForm');
                let formData = new FormData(form);
                // let param = `action=${action}&form=${formdata}`;
                let response = await fetch(addServicePath , {
                    method: 'POST',
                    body: formData
                });

                if (response.ok === false){
                    console.log('erreur');
                }
                else{
                    serviceList();
                }


            })
}
