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



