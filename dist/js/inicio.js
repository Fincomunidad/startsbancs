$(function () {
        $('.ui.dropdown')
  		.dropdown( {
              fullTextSearch: true
          })
		;   


      $('.autumn.leaf')
      .transition('slide up')
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
        var formatter2 = {
            date: function (date, settings) {
                if (!date) return '';
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                //return day + '/' + month + '/' + year;
                return ("0" + day).slice(-2) + "/" + ("0"+month).slice(-2) + "/" + year;
            }
        }    
        /*
            onChange: function (date, text, mode) {
                var fecalta = new Date(date);
                fecalta= fecalta.getDate() + '/' + ( fecalta.getMonth() + 1) + '/' + fecalta.getFullYear();
                $('input#fechaalta').val( fecalta );
        
        */
        $('#fechaalta').calendar({
           type: 'date',
            text: dateformato,
            formatInput: true,
            today: true,
            closable: true,
            formatter: formatter,
            onChange: function (date, text, mode) {
                $('#fechaalta').val(text);
            }                           
        });

        $('#fechaconsulta').calendar({
           type: 'date',
            text: dateformato,
            formatInput: true,
            today: true,
            closable: true,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fechaconsulta').val(text);                
            }                           
        });

        $('#fechaaltad').calendar({
            type: 'date',
             text: dateformato,
             formatInput: true,
             today: true,
             closable: true,
             formatter: formatter,
             onChange: function (date, text, mode) {
                 $('#fechaaltad').val(text);
             }                           
         });       

         $('#fechaaltab').calendar({
            type: 'date',
             text: dateformato,
             formatInput: true,
             today: true,
             closable: true,
             formatter: formatter,
             onChange: function (date, text, mode) {
                 $('#fechaaltab').val(text);
             }                           
         });
/* Se lo quite por las consultas historicas
           minDate: new Date(today.getFullYear()-65, today.getMonth(), today.getDate()),*/

        $('#fecha_nac').calendar({
           type: 'date',
           text: dateformato,
           formatInput: true,
           formatter: formatter,
           maxDate: new Date(today.getFullYear()-18, today.getMonth(), today.getDate()),
           onChange: function (date, text, mode) {
            $('#fecha_nac').val(text);
            }           
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
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+61),            
//            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fecha_pago').val(text);
            }
         });

         $('#fecha_pago2').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter2,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+81),            
            onChange: function (date, text, mode) {
                $('#fecha_pago2').val(text);
            }
         });         

         $('#fecha_entrega_col').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter2,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+61),            
            onChange: function (date, text, mode) {
                $('#fecha_entrega_col').val(text);
            }
         });     
		 
         $('#fecha_apertura').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),            
            onChange: function (date, text, mode) {
                $('#fecha_apertura').val(text);
            }
         });    

         $('#fecha_pagoi').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),            
//            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fecha_pagoi').val(text);
            }
         });


         $('#fecha_pagot').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),            
//            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fecha_pagot').val(text);
            }
         });


         $('#fechaini').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),            
//            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fechaini').val(text);
            }
         });

         $('#fechafin').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),            
//            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fechafin').val(text);
            }
         });


        $('#fecaini').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 15),
            //            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fecaini').val(text);
            }
        });


        $('#fecafin').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate() + 15),
            //            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()),
            onChange: function (date, text, mode) {
                $('#fecafin').val(text);
            }
        });
		
         $('#fecha_cheque').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter2,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+61),            
            onChange: function (date, text, mode) {
                $('#fecha_cheque').val(text);
            }
         });   		
		 
         $('#fecha_ini').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter2,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+15),            
            onChange: function (date, text, mode) {
                $('#fecha_cheque').val(text);
            }
         });  

         $('#fecha_fin').calendar({
            type: 'date',
            formatInput: true,
            text: dateformato,
            formatter: formatter2,
            maxDate: new Date(today.getFullYear(), today.getMonth(), today.getDate()+61),            
            onChange: function (date, text, mode) {
                $('#fecha_cheque').val(text);
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




// Función modulo, regresa el residuo de una división 
function mod(dividendo , divisor) 
{ 
  resDiv = dividendo / divisor ;  
  parteEnt = Math.floor(resDiv);            // Obtiene la parte Entera de resDiv 
  parteFrac = resDiv - parteEnt ;      // Obtiene la parte Fraccionaria de la división
  modulo = Math.round(parteFrac * divisor);  // Regresa la parte fraccionaria * la división (modulo) 
  return modulo; 
} // Fin de función mod

// Función ObtenerParteEntDiv, regresa la parte entera de una división
function ObtenerParteEntDiv(dividendo , divisor) 
{ 
  resDiv = dividendo / divisor ;  
  parteEntDiv = Math.floor(resDiv); 
  return parteEntDiv; 
} // Fin de función ObtenerParteEntDiv

// function fraction_part, regresa la parte Fraccionaria de una cantidad
function fraction_part(dividendo , divisor) 
{ 
  resDiv = dividendo / divisor ;  
  f_part = Math.floor(resDiv); 
  return f_part; 
} // Fin de función fraction_part


// function string_literal conversion is the core of this program 
// converts numbers to spanish strings, handling the general special 
// cases in spanish language. 
function string_literal_conversion(number) 
{   
   // first, divide your number in hundreds, tens and units, cascadig 
   // trough subsequent divisions, using the modulus of each division 
   // for the next. 

   centenas = ObtenerParteEntDiv(number, 100); 
   
   number = mod(number, 100); 

   decenas = ObtenerParteEntDiv(number, 10); 
   number = mod(number, 10); 

   unidades = ObtenerParteEntDiv(number, 1); 
   number = mod(number, 1);  
   string_hundreds="";
   string_tens="";
   string_units="";
   // cascade trough hundreds. This will convert the hundreds part to 
   // their corresponding string in spanish.
   if(centenas == 1){
      string_hundreds = "ciento ";
   } 
   
   
   if(centenas == 2){
      string_hundreds = "doscientos ";
   }
    
   if(centenas == 3){
      string_hundreds = "trescientos ";
   } 
   
   if(centenas == 4){
      string_hundreds = "cuatrocientos ";
   } 
   
   if(centenas == 5){
      string_hundreds = "quinientos ";
   } 
   
   if(centenas == 6){
      string_hundreds = "seiscientos ";
   } 
   
   if(centenas == 7){
      string_hundreds = "setecientos ";
   } 
   
   if(centenas == 8){
      string_hundreds = "ochocientos ";
   } 
   
   if(centenas == 9){
      string_hundreds = "novecientos ";
   } 
   
 // end switch hundreds 

   // casgade trough tens. This will convert the tens part to corresponding 
   // strings in spanish. Note, however that the strings between 11 and 19 
   // are all special cases. Also 21-29 is a special case in spanish. 
   if(decenas == 1){
      //Special case, depends on units for each conversion
      if(unidades == 1){
         string_tens = "once";
      }
      
      if(unidades == 2){
         string_tens = "doce";
      }
      
      if(unidades == 3){
         string_tens = "trece";
      }
      
      if(unidades == 4){
         string_tens = "catorce";
      }
      
      if(unidades == 5){
         string_tens = "quince";
      }
      
      if(unidades == 6){
         string_tens = "dieciseis";
      }
      
      if(unidades == 7){
         string_tens = "diecisiete";
      }
      
      if(unidades == 8){
         string_tens = "dieciocho";
      }
      
      if(unidades == 9){
         string_tens = "diecinueve";
      }
   } 
   //alert("STRING_TENS ="+string_tens);
   
   if(decenas == 2){
      string_tens = "veinti";
   }
   if(decenas == 3){
      string_tens = "treinta";
   }
   if(decenas == 4){
      string_tens = "cuarenta";
   }
   if(decenas == 5){
      string_tens = "cincuenta";
   }
   if(decenas == 6){
      string_tens = "sesenta";
   }
   if(decenas == 7){
      string_tens = "setenta";
   }
   if(decenas == 8){
      string_tens = "ochenta";
   }
   if(decenas == 9){
      string_tens = "noventa";
   }
   
    // Fin de swicth decenas


   // cascades trough units, This will convert the units part to corresponding 
   // strings in spanish. Note however that a check is being made to see wether 
   // the special cases 11-19 were used. In that case, the whole conversion of 
   // individual units is ignored since it was already made in the tens cascade. 

   if (decenas == 1) 
   { 
      string_units="";  // empties the units check, since it has alredy been handled on the tens switch 
   } 
   else 
   { 
      if(unidades == 1){
         string_units = "un";
      }
      if(unidades == 2){
         string_units = "dos";
      }
      if(unidades == 3){
         string_units = "tres";
      }
      if(unidades == 4){
         string_units = "cuatro";
      }
      if(unidades == 5){
         string_units = "cinco";
      }
      if(unidades == 6){
         string_units = "seis";
      }
      if(unidades == 7){
         string_units = "siete";
      }
      if(unidades == 8){
         string_units = "ocho";
      }
      if(unidades == 9){
         string_units = "nueve";
      }
       // end switch units 
   } // end if-then-else 
   

//final special cases. This conditions will handle the special cases which 
//are not as general as the ones in the cascades. Basically four: 

// when you've got 100, you dont' say 'ciento' you say 'cien' 
// 'ciento' is used only for [101 >= number > 199] 
if (centenas == 1 && decenas == 0 && unidades == 0) 
{ 
   string_hundreds = "cien " ; 
}  

// when you've got 10, you don't say any of the 11-19 special 
// cases.. just say 'diez' 
if (decenas == 1 && unidades ==0) 
{ 
   string_tens = "diez " ; 
} 

// when you've got 20, you don't say 'veinti', which is used 
// only for [21 >= number > 29] 
if (decenas == 2 && unidades ==0) 
{ 
  string_tens = "veinte " ; 
} 

// for numbers >= 30, you don't use a single word such as veintiuno 
// (twenty one), you must add 'y' (and), and use two words. v.gr 31 
// 'treinta y uno' (thirty and one) 
if (decenas >=3 && unidades >=1) 
{ 
   string_tens = string_tens+" y "; 
} 

// this line gathers all the hundreds, tens and units into the final string 
// and returns it as the function value.
final_string = string_hundreds+string_tens+string_units;


return final_string ; 

} //end of function string_literal_conversion()================================ 

// handle some external special cases. Specially the millions, thousands 
// and hundreds descriptors. Since the same rules apply to all number triads 
// descriptions are handled outside the string conversion function, so it can 
// be re used for each triad. 


function covertirNumLetras(number)
{
   
  //number = number_format (number, 2);
   number1=number.toString();
   //settype (number, "integer");
   cent = number1.split('.');   
   centavos = cent[1];
   
   if (centavos == 0 || centavos == undefined){
   centavos = "00";}

   if (number == 0 || number == "") 
   { // if amount = 0, then forget all about conversions, 
      centenas_final_string=" cero "; // amount is zero (cero). handle it externally, to 
      // function breakdown 
  } 
   else 
   { 
   
     millions  = ObtenerParteEntDiv(number, 1000000); // first, send the millions to the string 
      number = mod(number, 1000000);           // conversion function 
      
     if (millions != 0)
      {                      
      // This condition handles the plural case 
         if (millions == 1) 
         {              // if only 1, use 'millon' (million). if 
            descriptor= " millon ";  // > than 1, use 'millones' (millions) as 
            } 
         else 
         {                           // a descriptor for this triad. 
              descriptor = " millones "; 
            } 
      } 
      else 
      {    
         descriptor = " ";                 // if 0 million then use no descriptor. 
      } 
      millions_final_string = string_literal_conversion(millions)+descriptor; 
          
      
      thousands = ObtenerParteEntDiv(number, 1000);  // now, send the thousands to the string 
        number = mod(number, 1000);            // conversion function. 
      //print "Th:".thousands;
     if (thousands != 1) 
      {                   // This condition eliminates the descriptor 
         thousands_final_string =string_literal_conversion(thousands) + " mil "; 
       //  descriptor = " mil ";          // if there are no thousands on the amount 
      } 
      if (thousands == 1)
      {
         thousands_final_string = " mil "; 
     }
      if (thousands < 1) 
      { 
         thousands_final_string = " "; 
      } 
  
      // this will handle numbers between 1 and 999 which 
      // need no descriptor whatsoever. 

     centenas  = number;                     
      centenas_final_string = string_literal_conversion(centenas) ; 
      
   } //end if (number ==0) 

   /*if (ereg("un",centenas_final_string))
   {
     centenas_final_string = ereg_replace("","o",centenas_final_string); 
   }*/
   //finally, print the output. 

   /* Concatena los millones, miles y cientos*/
   cad = millions_final_string+thousands_final_string+centenas_final_string; 
   
   /* Convierte la cadena a Mayúsculas*/
   cad = cad.toUpperCase();       

   if (centavos.length>2)
   {   
      if(centavos.substring(2,3)>= 5){
         centavos = centavos.substring(0,1)+(parseInt(centavos.substring(1,2))+1).toString();
      }   else{
        centavos = centavos.substring(0,2);
       }
   }

   /* Concatena a los centavos la cadena "/100" */
   if (centavos.length==1)
   {
      centavos = centavos+"0";
   }
   centavos = centavos+ "/100"; 


   /* Asigna el tipo de moneda, para 1 = PESO, para distinto de 1 = PESOS*/
   if (number == 1)
   {
      moneda = " PESO ";  
   }
   else
   {
      moneda = " PESOS ";  
   }
   /* Regresa el número en cadena entre paréntesis y con tipo de moneda y la fase M.N.*/
   return "("+cad+moneda+centavos+" M.N.)";
}


function formatDate(fecha, formato) {
	let sMes = fecha.getMonth() + 1
	sMes = sMes <= 9?"0" + sMes:sMes;
	const map = {
		dd: fecha.getDate(),
		mm: sMes,
		yy: fecha.getFullYear().toString().slice(-2),
		yyyy: fecha.getFullYear(),
  }
  return formato.replace(/dd|mm|yyyy|yy/gi, matched => map[matched])
}