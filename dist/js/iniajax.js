function ajax(object) {
    return new Promise(function (resolve, reject) {
        return $.ajax(object).done(resolve).fail(reject);
    });
    }


function validaError(xhr) {
    var reject = [];
    if (xhr.status === 404 ) {
        var cadena = "";
        var pos = xhr.responseText.indexOf('{"status"');
        if (pos !== 0) {
            cadena = xhr.responseText.substring(pos);
            var arreglo = JSON.parse(cadena);
            reject = {
                newtoken: arreglo.newtoken,
                message: arreglo.message
            };
        } else {        
            reject = {
                newtoken: xhr.responseJSON.newtoken,
                message: xhr.responseJSON.message
            };
        }
    } else if (xhr.status === 409) {
        var cadena = "";
        var pos = xhr.responseText.indexOf('{"status"');
        if (pos !== 0) {
            cadena = xhr.responseText.substring(pos);
        }
        var arreglo = JSON.parse(cadena);
        reject = {
            newtoken: arreglo.newtoken,
            message: arreglo.message
        };

    }
    return reject;
}



function getWeekNo() {
    var now = new Date(), i = 0, f, sem = (new Date(now.getFullYear(), 0, 1).getDay() > 0) ? 1 : 0;
    while ((f = new Date(now.getFullYear(), 0, ++i)) < now) {
        if (!f.getDay()) {
            sem++;
        }
    }
    return sem;
}



function fechaMax(fecha){
    let fechanew = '';
    if  (fecha.substring(3,5) == 12) {
        fechanew = '01/01/' + ( parseFloat(fecha.substring(6))  + 1);
    }else if (fecha.substring(3, 5) >= 9) {
        fechanew = '01/' + (parseFloat(fecha.substring(3, 5)) + 1) + fecha.substring(5);
    }else {
        fechanew = '01/0' + ( parseFloat( fecha.substring(3, 5) ) + 1) + fecha.substring(5);
    }
    var today = new Date(fechanew.substring(6), parseFloat( fechanew.substring(3,5) -1 ) ,1);
    var newdate = new Date(fechanew.substring(6), parseFloat(fechanew.substring(3, 5) - 1), 1);
    newdate.setDate(today.getDate() - 1);
    return moment(newdate).format('DD/MM/YYYY');
}


function getWeekFec(fecha) {
    var now = new Date(fecha.substring(6), parseFloat(fecha.substring(3, 5) - 1), fecha.substring(0,2)), i = 0, f, sem = (new Date(now.getFullYear(), 0, 1).getDay() > 0) ? 1 : 0;
    while ((f = new Date(now.getFullYear(), 0, ++i)) < now) {
        if (!f.getDay()) {
            sem++;
        }
    }
    return sem;
}




