$(function () {
        $('.ui.dropdown')
  		.dropdown( {
              fullTextSearch: true
          })
		;   

        $('.ui.accordion')
        .accordion()
        ;

        $('.ui.checkbox')
        .checkbox()
        ;

        $('.ui.radio.checkbox')
        .checkbox()
        ;        

        $('.ui.simple.dropdown')
        .popup({
            on: 'click',
            inline  : true,
            horever: false,
            position   : 'bottom center',
            delay: {
            show: 300,
            hide: 800
            }
        })
        ;

        $('.message .close')
        .on('click', function() {
            $(this)
            .closest('.message')
            .transition('fade')
            ;
        })
        ;     


/*
Configuracion de Calendar 
 */
        var today = new Date();
        var hoy= today.getDate() + '/' + ( today.getMonth() + 1) + '/' + today.getFullYear();        
        var dateformato = {
                days: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
                months: ['Enero', 'Febero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                monthsShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                today: 'Hoy',
                now: 'Now'
            }
        var formatter = {
            date: function (date, settings) {
            if (!date) return '';
            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();
            return day + '/' + month + '/' + year;
            }
        }    

        $('#fechaalta').calendar({
           type: 'date',
            text: dateformato,
            today: true,
            closable: true,
            formatter: formatter,
            onChange: function (date, text, mode) {
                var fecalta = new Date(date);
                fecalta= fecalta.getDate() + '/' + ( fecalta.getMonth() + 1) + '/' + fecalta.getFullYear();
                $('input#fechaalta').val( fecalta );

            }
        });

/* Se lo quite por las consultas historicas
           minDate: new Date(today.getFullYear()-65, today.getMonth(), today.getDate()),*/

        $('#fecha_nac').calendar({
           type: 'date',
           text: dateformato,
           formatter: formatter,
           maxDate: new Date(today.getFullYear()-18, today.getMonth(), today.getDate())
        });


        $('#fecha').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),            
            onChange: function (date, text, mode) {
                $('#fecha').val(text);
            }
         });
 

         $('#fecha_pago').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),            
//            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fecha_pago').val(text);
            }
         });


 /*
         $('#fecha_pago').calendar({
            type: 'date',
            text: dateformato,
            today: true,
            closable: true,
            formatter: formatter,
            minDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()-30),
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),
            onChange: function (date, text, mode) {
                var fecalta = new Date(date);
                fecalta= fecalta.getDate() + '/' + ( fecalta.getMonth() + 1) + '/' + fecalta.getFullYear();
                $('input#fecha_pago').val( fecalta );
            }
        });              

*/



        //Menu Principal
        $("#menuleft").unbind('click').on("click", function(e){
            if ($("#mainleft").hasClass('visible')) {
                $("#mainleft").removeClass("visible");
                $("#pusher").removeClass("dimmednew");
            }else {
                $("#mainleft").addClass("visible");
                $("#pusher").addClass("dimmednew");
            }
        })
        
        $(".pusher").on("click", function(e) {
            $("#pusher").removeClass("dimmednew");
            $("#mainleft").removeClass("visible");

        })
        //Fin Menu Principal

    });


