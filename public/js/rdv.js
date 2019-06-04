// Variable definies dans rdv/index/html.twig

$(function () {
    // REQUETES AJAX DES CHANGEMENT DE MOIS DU CALENDRIER
    $('body')
    .on('click', '#monthDown',  async function (e){
    // Definition de la fonction Asyncrone afin d'attendre les rlts
        e.preventDefault();
        let param = `number=${monthNumberDown}&year=${year}`;
        // La variable response prendS les resultats de la requete via fetch et forme de promesse
        let response = await fetch(path , {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: param,
        });
        // responseData recupere la réponse de 'response' quand elle est dispo (.text ou .json ou autre voir doc)
        let responseData = await response.text();
        if (response.ok === false){
            //ERRORS
            console.log('erreur');
        }
        else{
            calendar.innerHTML = responseData;
            monthNumberUp = monthNumberDown + 1 ;
            monthNumberDown = monthNumberDown === 1 ? 1 : monthNumberDown - 1;
        }

    })
    .on('click', '#monthUp',  async function (e){
        // Definition de la fonction Asyncrone afin d'attendre les rlts
        e.preventDefault();

        // La variable response prend les rslts de la requete via fetch et forme de promesse

        let param = `number=${monthNumberUp}&year=${year}`;

        let response = await fetch(path, {
            method: 'POST',
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: param,
        });
        // responseData recupere la reponse de response quand elle est dispo (.text ou .json ou autre voir doc)
        let responseData = await response.text();
        if (response.ok === false){
            //ERRORS
            console.log('erreur');
        }
        else{
            calendar.innerHTML = responseData;
            monthNumberDown = monthNumberUp - 1;
            monthNumberUp = monthNumberUp === 12 ? 12 : monthNumberUp + 1;
        }
    });

    // COUT DES PRESTATIONS CALCULES A PARTIR DES SERVICES COCHES

    let totalCost = 0;
    $('input:checkbox').on('change',function () {
        if ($(this).prop( "checked" )){
            totalCost = totalCost + $(this).data('price');
            $('#pricetotal').html(totalCost);
        }
        else{
            totalCost = totalCost - $(this).data('price');
            $('#pricetotal').html(totalCost);
        }
        if (totalCost > 0){
            $('#reservate').removeAttr('disabled')
        }
        else{
            $('#reservate').attr('disabled', true)
        }
    });
    //**************************************************************
    $('body').on('click', '.rdv', function () {
        let date = document.getElementById('date');
        let code = document.getElementById('code');
        let codeValue = this.dataset.code;
        let getDate = this.parentElement.dataset.date.split('-');
        let getHour = this.dataset.hour;
        date.setAttribute('value',getDate[1]+'/'+getDate[0]+'/2019' + ' à '+ getHour + "h00")
        code.setAttribute('value',codeValue);
    })
});
// EVENEMENTS DE PRISES DE RDV - JS Natif
// let rdv = document.querySelectorAll('.rdv:not(.unavaible)');
// let date = document.getElementById('date');
// let code = document.getElementById('code');
//
// rdv.forEach( function (element) {
//     element.addEventListener('click', () => {
//         let codeValue = element.dataset.code;
//         let getDate = element.parentElement.dataset.date.split('-');
//         let getHour = element.dataset.hour;
//         date.setAttribute('value',getDate[1]+'/'+getDate[0]+'/2019' + ' à '+ getHour + "h00")
//         code.setAttribute('value',codeValue);
//     });
//
// });



