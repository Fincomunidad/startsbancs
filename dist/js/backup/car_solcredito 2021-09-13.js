"use strict";var _createClass=function(){function i(e,t){for(var a=0;a<t.length;a++){var i=t[a];i.enumerable=i.enumerable||!1,i.configurable=!0,"value"in i&&(i.writable=!0),Object.defineProperty(e,i.key,i)}}return function(e,t,a){return t&&i(e.prototype,t),a&&i(e,a),e}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var Steps=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var a=this;return React.createElement("a",{className:this.props.valor==this.props.value?"active step":"step",value:this.props.value,onClick:function(e,t){return a.props.onClick(e,t)}},React.createElement("i",{className:this.props.icon}),React.createElement("div",{className:"content"},React.createElement("div",{className:"title"},this.props.titulo),React.createElement("div",{className:"description"})))}}]),t}(),InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ",a="password"==this.props.id?"password":"text",i="true"==this.props.mayuscula?"mayuscula":"";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:i,id:this.props.id,name:this.props.id,type:a,readOnly:this.props.readOnly,value:this.props.valor,placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}})))}}]),t}(),InputFieldFind=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t}return _inherits(a,React.Component),_createClass(a,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,value:this.props.valor,type:"text",placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}}),React.createElement("i",{className:this.props.icons,onClick:function(e){return t.props.onClick(e,t.props.valor,t.props.id)}})))}}]),a}(),InputFieldNumber=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui labeled input"},React.createElement("div",{className:"ui label"},"$"),React.createElement("input",{type:"text",id:this.props.id,name:this.props.id,readOnly:this.props.readOnly,value:this.props.valor,onChange:function(e){return t.props.onChange(e)}})))}}]),t}(),Mensaje=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={icon:"send icon",titulo:"Guardar",pregunta:"¿Desea enviar el registro?"},t}return _inherits(a,React.Component),_createClass(a,[{key:"render",value:function(){return React.createElement("div",{className:"ui mini test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok basic button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),a}(),Calendar=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:"ui calendar",id:this.props.name},React.createElement("div",{className:"field"},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui input left icon"},React.createElement("i",{className:"calendar icon"}),React.createElement("input",{ref:"myCalen",type:"text",name:this.props.name,id:this.props.name,placeholder:"Fecha"}))))}}]),t}(),SelectDropDown=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t.handleSelectChange=t.handleSelectChange.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.props.valor,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),a}(),SelectOption=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myCombo)).on("change",this.handleSelectChange.bind(this))}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" inline field ",t=this.props.valores.map(function(e){return React.createElement("option",{value:e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{value:this.props.valor},"Seleccione"),t))}}]),a}();function Lista(e){var t=e.enca,a=0,i=t.map(function(e){return React.createElement("th",{key:a++},e)});return React.createElement("tr",null,i)}var RecordDetalle=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("tr",null,React.createElement("td",null,this.props.registro.numero),React.createElement("td",null,this.props.registro.fecha_vence),React.createElement("td",null,this.props.registro.capital),React.createElement("td",null,this.props.registro.interes),React.createElement("td",null,this.props.registro.iva),React.createElement("td",null,this.props.registro.garantia),React.createElement("td",null,this.props.registro.total))}}]),t}(),Table=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=[];this.props.datos.forEach(function(e){t.push(React.createElement(RecordDetalle,{registro:e}))});return React.createElement("div",null,React.createElement("table",{className:"ui selectable celled red table"},React.createElement("thead",null,React.createElement(Lista,{enca:["Semana","Vencimiento","Capital","Interes","IVA","Garantia","Total"]})),React.createElement("tbody",null,t)))}}]),t}(),Captura=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={blnActivar:!0,blnExiste:!1,blnCreditoColmena:!1,blnCreditoTemporada:!1,idcredito:"",fecha:"",idexiste:"",fecha_pago:"",fecha_entrega_col:"",idaval1:"0",idaval2:"0",cataval1:[],cataval2:[],idAvalTemp1:"",idAvalTemp2:"",edocivil_nombre:"",actividad_nombre:"",idacreditado:"",nosocio:"",catsocio:[],domicilio:"",acreditado_id:"",idpagare:"",idnivel:"",nivel:"",catnivel:[],num_pagos:"",monto:0,tasa:"",proy_nombre:"",proy_descri:"",proy_lugar:"",proy_observa:"",idproducto:1,idchecklist:1,catchklst:[],intCheckListReq:0,nocolmena:"",idcolmena:"",colmena_nombre:"",colmena_grupo:"",blnGrupo:!1,idgrupo:"",csrf:"",message:"",statusmessage:"ui floating hidden message",sancion:"",stepno:1,fecha_aprov:null,usuario_aprov:null,identify:null,password:null,amortizaciones:[],boton:"Enviar",btnAutoriza:"Autorizar",icons1:"inverted circular search link icon"},t}return _inherits(a,React.Component),_createClass(a,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),$.ajax({url:base_url+"/api/GeneralD1/get_solicitud_credito",type:"GET",dataType:"json",success:function(e){this.setState({catsocio:e.catsocio,catnivel:e.catnivel2,catchklst:e.catchklst})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleInputChange",value:function(e){var t=e.target,a="checkbox"===t.type?t.checked:t.value,i=t.name;if(this.setState(_defineProperty({},i,a)),"idnivel"===i&&""!=e.target.value){var n="get_importe_by_idnivel";n=n+"/"+e.target.value,$.ajax({url:base_url+"/api/CarteraD1/"+n,type:"GET",dataType:"json",success:function(e){this.setState({monto:e.importe,nivel:e.nivel,tasa:e.tasa})}.bind(this),error:function(e,t,a){console.log("error"+a),this.setState({monto:0,tasa:0})}.bind(this)})}if("idacreditado"===i&&""!=e.target.value){var s="";"idacreditado"===i&&(s="get_acreditado"),s=s+"/"+e.target.value,$.ajax({url:base_url+"/api/CarteraD1/"+s,type:"GET",dataType:"json",success:function(e){"idacreditado"===i&&(this.asignaAcreditado(e.acreditado[0]),this.setState({cataval1:e.cataval1,cataval2:e.cataval2,sancion:e.sancion}))}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}}},{key:"handleSubmit",value:function(e){e.preventDefault(),$(".ui.form.formgen").form({inline:!0,on:"blur",fields:{idacreditado:{identifier:"idacreditado",rules:[{type:"empty",prompt:"Seleccione al acreditado"}]},idcolmena:{identifier:"idcolmena",rules:[{type:"empty",prompt:"Seleccione la colmena"}]},idgrupo:{identifier:"idgrupo",rules:[{type:"empty",prompt:"Seleccione el grupo"}]},proy_nombre:{identifier:"proy_nombre",rules:[{type:"empty",prompt:"Capture el nombre del proyecto"},{type:"minLength[5]",prompt:"Minimo 5 caracteres"},{type:"maxLength[80]",prompt:"Longitu máxima de 80 caracteres"}]},proy_descri:{identifier:"proy_descri",rules:[{type:"empty",prompt:"Capture la descripción del proyecto"},{type:"minLength[5]",prompt:"Minimo 5 caracteres"},{type:"maxLength[250]",prompt:"Longitu máxima de 250 caracteres"}]},proy_lugar:{identifier:"proy_lugar",rules:[{type:"empty",prompt:"Capture el lugar donde se ejecutará el proyecto"},{type:"minLength[5]",prompt:"Minimo 5 caracteres"},{type:"maxLength[250]",prompt:"Longitu máxima de 250 caracteres"}]},proy_observa:{identifier:"proy_observa",rules:[{type:"empty",prompt:"Capture las notas y observaciones al proyecto"},{type:"minLength[5]",prompt:"Minimo 5 caracteres"},{type:"maxLength[250]",prompt:"Longitu máxima de 250 caracteres"}]},idchecklist:{identifier:"idchecklist",rules:[{type:"empty",prompt:"Seleccione el CheckList"}]},idnivel:{identifier:"idnivel",rules:[{type:"empty",prompt:"Seleccione el nivel"}]},num_pagos:{identifier:"num_pagos",rules:[{type:"empty",prompt:"Seleccione el número de pagos"}]}}}),$(".ui.form.formgen").form("validate form");var t=$(".ui.form.formgen").form("is valid");if(this.setState({message:"",statusmessage:"ui message hidden"}),1==t){var a=$(".get.solcred form"),i=a.form("get values"),n=a.form("get value","csrf_bancomunidad_token"),s="Enviar"===this.state.boton?"POST":"PUT",o=this;$(".mini.modal").modal({closable:!1,onApprove:function(){var e={url:base_url+"api/CarteraD1/add_credito",type:s,dataType:"json",data:{csrf_bancomunidad_token:n,data:i}};ajax(e).then(function(e){if("Enviar"===o.state.boton){var t=e.insert_id;o.setState({idcredito:t,idexiste:t,csrf:e.newtoken,message:e.message+e.insert_id,statusmessage:"ui positive floating message ",boton:"Enviar"})}else o.setState({csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message ",boton:"Actualizar"});o.autoReset()},function(e){if("OK"===e.statusText){var t="",a=e.responseText.indexOf('{"status"');0!==a&&(t=e.responseText.substring(a));var i=JSON.parse(t),n=i.insert_id;o.setState({idcredito:n,idexiste:n,csrf:i.newtoken,message:i.message+i.insert_id,statusmessage:"ui positive floating message ",boton:"Enviar"})}else{console.log("ENTRO REJECT 1");var s=validaError(e);o.setState({csrf:s.newtoken,message:s.message,statusmessage:"ui negative floating message"})}o.autoReset()})}}).modal("show")}else this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset()}},{key:"autoReset",value:function(){var e=this;window.clearTimeout(e.timeout),""!=e.state.message&&(this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},5e3))}},{key:"handleSubmitAutoriza2",value:function(e){e.preventDefault();var t=$(".get.solcredaut form"),a=t.form("get values"),i=t.form("get value","csrf_bancomunidad_token"),r=this;$(".test.modal").modal({closable:!1,onApprove:function(){$.ajax({url:base_url+"auth/Autho/validateAutUser/10031",type:"POST",dataType:"json",data:{csrf_bancomunidad_token:i,data:a},success:function(e){if("OK"===e.status){r.setState({csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message ",btnAutoriza:"Autoriza2"});var t=$(".get.solcred form"),a=t.form("get values"),i=t.form("get value","csrf_bancomunidad_token"),o=this;$(".test.modal").modal({closable:!1,onApprove:function(){$.ajax({url:base_url+"api/CarteraD1/aut_credito",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:i,data:a},success:function(e){"OK"===e.status&&o.setState({csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message ",btnAutoriza:"Autorizar"})}.bind(this),error:function(e,t,a){if(404===e.status)o.setState({csrf:e.responseJSON.newtoken,message:e.responseJSON.message,statusmessage:"ui negative floating message"});else if(409===e.status){var i="",n=e.responseText.indexOf('{"status"');0!==n&&(i=e.responseText.substring(n));var s=JSON.parse(i);o.setState({csrf:s.newtoken,message:s.message,statusmessage:"ui negative floating message"})}}.bind(this)})}}).modal("show")}}.bind(this),error:function(e,t,a){if(404===e.status)r.setState({csrf:e.responseJSON.newtoken,message:e.responseJSON.message,statusmessage:"ui negative floating message"});else if(409===e.status){var i="",n=e.responseText.indexOf('{"status"');0!==n&&(i=e.responseText.substring(n));var s=JSON.parse(i);r.setState({csrf:s.newtoken,message:s.message,statusmessage:"ui negative floating message"})}}.bind(this)})}}).modal("show")}},{key:"handleSubmitAutoriza",value:function(e){e.preventDefault();var t=$(".get.solcred form"),a=t.form("get values"),i=t.form("get value","csrf_bancomunidad_token"),o=this;$(".test.modal").modal({closable:!1,onApprove:function(){$.ajax({url:base_url+"api/CarteraD1/aut_credito",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:i,data:a},success:function(e){"OK"===e.status&&o.setState({csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message ",btnAutoriza:"Autorizar"})}.bind(this),error:function(e,t,a){if(404===e.status)o.setState({csrf:e.responseJSON.newtoken,message:e.responseJSON.message,statusmessage:"ui negative floating message"});else if(409===e.status){var i="",n=e.responseText.indexOf('{"status"');0!==n&&(i=e.responseText.substring(n));var s=JSON.parse(i);o.setState({csrf:s.newtoken,message:s.message,statusmessage:"ui negative floating message"})}}.bind(this)})}}).modal("show")}},{key:"handleClickNext",value:function(e){this.state.stepno<2&&this.setState(function(e,t){return{stepno:e.stepno+1}})}},{key:"handleClickPrevious",value:function(e){1<this.state.stepno&&this.setState(function(e,t){return{stepno:e.stepno-1}})}},{key:"asignaAcreditado",value:function(e){var t=new Date;this.setState({nosocio:e.idacreditado,nombre:e.nombre,idpagare:"F"+t.getFullYear()+(t.getMonth()+1)+t.getDate()+"-"+e.idacreditado,colmena_nombre:e.col_nombre,colmena_grupo:e.grupo_nombre,actividad_nombre:e.actividad_nombre,edocivil_nombre:e.edocivil_nombre,domicilio:null==e.direccion?"":e.direccion,idgrupo:e.idgrupo});$(".get.solcred form").form("set values",{idgrupo:e.idgrupo})}},{key:"asignaSolicitudCreditoNew",value:function(){this.setState({blnActivar:!0,idacreditado:"0",nosocio:"",domicilio:"",fecha:"",idexiste:"",fecha_pago:"",edocivil_nombre:"",actividad_nombre:"",idAval1:0,idAval2:0,cataval1:[],cataval2:[],idpagare:"",idnivel:"",monto:0,num_pagos:"",proy_nombre:"",proy_descri:"",proy_lugar:"",proy_observa:"",idproducto:1,idchecklist:1,nocolmena:"",colmena_nombre:"",grupo_nombre:"",idgrupo:"",blnGrupo:!1,stepno:1,amortizaciones:[],usuario_aprov:null,fecha_aprov:null,sancion:"",boton:"Enviar"});$(".get.solcred form").form("set values",{sancion:"",nombre:"",idcolmena:"",nocolmena:"",idnivel:"",num_pagos:"",idgrupo:"",idaval1:"0",idaval2:"0",idchecklist:1,fecha_aprov:null});$(".get.solcred .ui.dropdown").dropdown("clear")}},{key:"asignaSolicitudCredito",value:function(e){var t=moment(e.fecha).format("DD/MM/YYYY"),a=moment(e.fecha_pago).format("DD/MM/YYYY"),i=moment(e.fecha_entrega_col).format("DD/MM/YYYY");$("#fecha").val(t),$("#fecha_pago").val(a),$("#fecha_entrega_col").val(i),this.setState({fecha:t,fecha_pago:a,fecha_entrega_col:i,idpagare:e.idpagare,proy_nombre:e.proy_nombre,proy_descri:e.proy_descri,proy_lugar:e.proy_lugar,proy_observa:e.proy_observa,fecha_aprov:e.fecha_aprov,usuario_aprov:e.usuario_aprov,idAvalTemp1:e.idaval1,idAvalTemp2:e.idaval2,idaval1:e.idaval1,idaval2:e.idaval2,actividad_nombre:e.actividad_nombre,edocivil_nombre:e.edocivil_nombre,colmena_grupo:e.nomgrupo,colmena_nombre:e.nomcolmena,domicilio:e.direccion,idnivel:e.idnivel});$(".get.solcred form").form("set values",{fecha:t,fecha_pago:a,fecha_entrega_col:i,idacreditado:e.acreditadoid,idcolmena:e.idcolmena,idnivel:e.idnivel,num_pagos:e.num_pagos,idgrupo:e.idgrupo,idchecklist:e.idchecklist,actividad_nombre:e.actividad_nombre,edocivil_nombre:e.edocivil_nombre,colmena_grupo:e.nomgrupo,colmena_nombre:e.nomcolmena,domicilio:e.direccion})}},{key:"handleFind",value:function(e,t){if(""!=t){this.setState({icons1:"spinner circular inverted blue loading icon"});var a=this,i={url:base_url+"api/CarteraD1/get_solicitud_credito/"+t,type:"GET",dataType:"json"};ajax(i).then(function(e){a.setState({cataval1:e.cataval1,cataval2:e.cataval2}),a.asignaSolicitudCredito(e.solcredito[0]),a.setState({intCheckListReq:e.checklist[0].total,amortizaciones:e.amortizaciones,message:e.message,statusmessage:"ui positive floating message ",boton:"Actualizar",blnActivar:!1,icons1:"inverted circular search link icon"}),a.setState(function(e,t){return{idexiste:idcredito}}),a.autoReset()},function(e){var t=validaError(e);a.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui negative floating message",icons1:"inverted circular search link icon"}),a.asignaSolicitudCreditoNew(),a.autoReset()})}else{this.setState({message:"Ingrese el número de la solicitud de crédito",statusmessage:"ui negative floating message",icons1:"inverted circular search link icon"}),this.autoReset()}}},{key:"handleButton",value:function(e,t){if(e<2){this.asignaSolicitudCreditoNew();$(".get.solcred form").form("set values",{idcredito:""})}else if(9<e&&e<21)if("Actualizar"==this.state.boton){new Date,this.state.idcredito;var a="";10===e?a="pdf_solicitud_credito":11===e?a="pdf_pagare":12===e?a="pdf_tabla_amortizacion":13===e?a="pdf_checklist":14===e?a="pdf_contrato":15===e?a="pdf_ahorro":16===e?a="pdf_convenio":17===e?a="pdf_retgarantia":18===e?a="pdf_retgarantia":19===e?a="pdf_plan_pago":20===e&&(a="pdf_tabla_amortizacion_nueva"),a=a+"/"+this.state.idcredito,(i=document.createElement("a")).href=base_url+"api/ReportD1/"+a,i.target="_blank",document.body.appendChild(i),i.click(),document.body.removeChild(i)}else{var i,n="";new Date;n="pdf_creditosfecha",n+="/111",alert(n),(i=document.createElement("a")).href=base_url+"api/ReportD1/"+n,i.target="_blank",document.body.appendChild(i),i.click(),document.body.removeChild(i)}}},{key:"handleState",value:function(e,t){this.setState({stepno:e})}},{key:"render",value:function(){var t=this;new Date;return React.createElement("div",null,React.createElement("div",{className:"ui segment vertical "},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Solicitud de credito de socio")),React.createElement("div",{className:"ui secondary menu"},React.createElement("div",{className:"ui  basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Nuevo Registro"},React.createElement("i",{className:"plus square outline icon",onClick:this.handleButton.bind(this,0)})),React.createElement("button",{className:"ui button","data-tooltip":"Cancelar captura"},React.createElement("i",{className:"minus square outline icon",onClick:this.handleButton.bind(this,1)})),React.createElement("button",{className:"ui button","data-tooltip":"Solicitud credito PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,10)})),React.createElement("button",{className:"ui button","data-tooltip":"Pagare PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,11)})),React.createElement("button",{className:"ui button","data-tooltip":"Tabla de amortizaciones PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,12)})),React.createElement("button",{className:"ui button","data-tooltip":"Checklist PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,13)})),React.createElement("button",{className:"ui button","data-tooltip":"Contrato PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,14)})),React.createElement("button",{className:"ui button","data-tooltip":"Ahorro PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,15)})),React.createElement("button",{className:"ui button","data-tooltip":"Convenio PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,16)})),React.createElement("button",{className:"ui button","data-tooltip":"Retiro garantia PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,17)})),React.createElement("button",{className:"ui button","data-tooltip":"Plan de pagos PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,19)})),React.createElement("button",{className:"ui button","data-tooltip":"Amortizaciones nueva"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,20)}))),React.createElement("div",{className:"right menu"},React.createElement("div",{className:"ui category search"},React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:"prompt",type:"text",placeholder:"Buscar Nombre"}),React.createElement("i",{className:"search link icon"})),React.createElement("div",{className:"results"}))))),React.createElement(Mensaje,null),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(e){return t.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{className:"get solcred"},React.createElement("form",{className:"ui form formgen",ref:"form",onSubmit:this.handleSubmit.bind(this),method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("input",{type:"hidden",name:"nivel",value:this.state.nivel}),React.createElement("div",{className:!1===this.state.blnActivar?"disablediv":""},React.createElement("div",{className:"two fields"},React.createElement(InputFieldFind,{icons:this.state.icons1,id:"idcredito",cols:"two wide",label:"No. solicitud",placeholder:"Buscar",valor:this.state.idcredito,onChange:this.handleInputChange.bind(this),onClick:this.handleFind.bind(this)}),React.createElement("div",{className:"three wide field"},React.createElement(Calendar,{name:"fecha",label:"Fecha alta",valor:this.state.fecha,onChange:this.handleInputChange.bind(this)})),React.createElement(InputField,{id:"idpagare",cols:"three wide",label:"Pagare",readOnly:"readOnly",valor:this.state.idpagare,onChange:this.handleInputChange.bind(this)}))),React.createElement("div",{className:"ui mini steps"},React.createElement(Steps,{valor:this.state.stepno,value:"1",icon:"folder outline icon",titulo:"Datos Personales",onClick:this.handleState.bind(this,1)}),React.createElement(Steps,{valor:this.state.stepno,value:"2",icon:"check circle outline icon",titulo:"Datos del Credito",onClick:this.handleState.bind(this,2)}),React.createElement(Steps,{valor:this.state.stepno,value:"3",icon:"check circle outline icon",titulo:"Amortizaciones",onClick:this.handleState.bind(this,3)}),React.createElement(Steps,{valor:this.state.stepno,value:"4",icon:"check circle outline icon",titulo:"Autorizacion",onClick:this.handleState.bind(this,4)})),React.createElement("div",{className:1===this.state.stepno?"ui blue segment":"ui blue segment step hidden"},React.createElement("div",{className:!1===this.state.blnActivar?"disablediv":""},React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{id:"idacreditado",label:"Nombre",valor:this.state.idacreditado,valores:this.state.catsocio,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"sancion",label:"sancion",readOnly:"readOnly",valor:this.state.sancion,onChange:this.handleInputChange.bind(this)}))),React.createElement("div",{className:"field"},React.createElement(InputField,{id:"domicilio",label:"Domicilio",readOnly:"readOnly",valor:this.state.domicilio,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"actividad_nombre",label:"Actividad",readOnly:"readOnly",valor:this.state.actividad_nombre,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"edocivil_nombre",label:"Estado Civil",readOnly:"readOnly",valor:this.state.edocivil_nombre,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"colmena_nombre",label:"Colmena:",readOnly:"readOnly",valor:this.state.colmena_nombre,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"colmena_grupo",label:"Grupo:",readOnly:"readOnly",valor:this.state.colmena_grupo,onChange:this.handleInputChange.bind(this)})),React.createElement("input",{type:"hidden",name:"idgrupo",value:this.state.idgrupo})),React.createElement("div",{className:2===this.state.stepno?"ui blue segment":"ui blue segment step hidden"},React.createElement("div",{className:"field"},React.createElement(InputField,{id:"proy_nombre",mayuscula:"true",label:"Titulo del proyecto",valor:this.state.proy_nombre,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"three fields"},React.createElement(SelectOption,{name:"idnivel",cols:"five wide",id:"idnivel",label:"Nivel",valor:this.state.idnivel,valores:this.state.catnivel,onChange:this.handleInputChange.bind(this)}),React.createElement(InputFieldNumber,{id:"monto",cols:"three wide",label:"Monto credito",readOnly:"readOnly",valor:this.state.monto,onChange:this.handleInputChange.bind(this)}),React.createElement(InputFieldNumber,{id:"tasa",cols:"three wide",label:"Tasa",readOnly:"readOnly",valor:this.state.tasa,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"three fields"},React.createElement("div",{className:"field"},React.createElement(Calendar,{name:"fecha_entrega_col",label:"Fecha de entrega:",valor:this.state.fecha_entrega_col,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"field"},React.createElement(Calendar,{name:"fecha_pago",label:"Fecha primer pago",valor:this.state.fecha_pago,onChange:this.handleInputChange.bind(this)}))),React.createElement("div",{className:"three fields"},React.createElement(SelectOption,{name:"idaval1",id:"idaval1",label:"Aval de grupo",valor:this.state.idaval1,valores:this.state.cataval1,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectOption,{name:"idaval2",id:"idaval2",label:"Aval de colmena",valor:this.state.idaval2,valores:this.state.cataval2,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"field"},React.createElement(InputField,{id:"proy_lugar",mayuscula:"true",label:"Lugar donde se realizara el proyecto",valor:this.state.proy_lugar,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"field"},React.createElement(InputField,{id:"proy_descri",mayuscula:"true",label:"Descripcion proyecto",valor:this.state.proy_descri,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"field"},React.createElement(InputField,{id:"proy_observa",mayuscula:"true",label:"Observación al crédito",valor:this.state.proy_observa,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{id:"idchecklist",label:"CheckList",valor:this.state.idchecklist,valores:this.state.catchklst,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui submit bottom primary basic button",type:"submit",name:"action"},React.createElement("i",{className:"send icon"})," ",this.state.boton," ")))))),React.createElement("div",{className:"get solcredamor"},React.createElement("form",{className:"ui form formaut",ref:"formaut"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:3===this.state.stepno?"ui blue segment":"ui blue segment step hidden"},React.createElement("div",null,React.createElement(Table,{datos:this.state.amortizaciones}))))),React.createElement("div",{className:"get solcredaut"},React.createElement("form",{className:"ui form formaut",ref:"formaut",onSubmit:this.handleSubmitAutoriza.bind(this),method:"POST"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:4===this.state.stepno?"ui blue segment":"ui blue segment step hidden"},React.createElement("div",{className:""==this.state.idexiste?"step hidden":""},React.createElement("div",{className:null==this.state.usuario_aprov&&0<this.state.intCheckListReq?"":"step hidden"},React.createElement("br",null),React.createElement("div",{className:"field"},React.createElement("label",null,"Faltan ",this.state.intCheckListReq," documentos por requisitar para poder autorizar el credito. "),React.createElement("label",null,"Ingrese a la sección CheckList para completar la documentación. ")),React.createElement("br",null)),React.createElement("div",{className:null==this.state.usuario_aprov&&0==this.state.intCheckListReq?"":"step hidden"},React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"identify",label:"Usuario autoriza:",valor:this.state.identify,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"password",label:"Contraseña:",valor:this.state.password,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui submit bottom primary basic button",type:"submit",name:"action"},React.createElement("i",{className:"send icon"})," ",this.state.btnAutoriza," ")))),React.createElement("div",{className:null==this.state.usuario_aprov?"step hidden":""},React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"usuario_aprov",label:"Usuario que autorizó:",readOnly:"readOnly",valor:this.state.usuario_aprov,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"fecha_aprov",label:"Fecha autorización:",readOnly:"readOnly",valor:this.state.fecha_aprov,onChange:this.handleInputChange.bind(this)})))),React.createElement("div",{className:"row"})))),React.createElement("div",{className:"ui vertical segment"},React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("button",{className:"ui labeled icon positive basic button",onClick:this.handleClickPrevious.bind(this)},React.createElement("i",{className:"left chevron icon"})," Anterior "),React.createElement("button",{className:"ui right labeled icon positive basic button",onClick:this.handleClickNext.bind(this)},"Siguiente ",React.createElement("i",{className:"right chevron icon"})," "))))}}]),a}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root")),$(".ui.search").search({type:"category",minCharacters:8,apiSettings:{url:base_url+"api/CarteraD1/find_acreditados?q={query}",onResponse:function(e){var i={results:{}};if(e&&e.result)return $.each(e.result,function(e,t){var a=t.idacreditado||"Sin asignar";if(8<=e)return!1;void 0===i.results[a]&&(i.results[a]={name:a,results:[]}),i.results[a].results.push({title:t.nombre,description:t.idcredito+" : "+t.idpagare})}),i}}});