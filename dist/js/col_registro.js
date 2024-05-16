"use strict";var _createClass=function(){function n(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(e,t,a){return t&&n(e.prototype,t),a&&n(e,a),e}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ",a="true"==this.props.mayuscula?"mayuscula":"";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:a,id:this.props.id,readOnly:this.props.readOnly,name:this.props.id,type:"text",value:this.props.valor,placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}})))}}]),t}(),InputFieldNumber=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui labeled input"},React.createElement("div",{className:"ui label"},"$"),React.createElement("input",{className:"text-right",type:"text",id:this.props.id,name:this.props.id,readOnly:this.props.readOnly,value:this.props.valor,onChange:function(e){return t.props.onChange(e)},onBlur:function(e){return t.props.onBlur(e)}})))}}]),t}(),Nota=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:!0===this.props.visible?"":"hidden"},React.createElement("div",{className:"center aligned content "},React.createElement("p",{className:"ui rojo"},"Existen créditos por Entregar a la  Acreditada: ",this.props.notavis_lista)))}}]),t}(),Mensaje=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={icon:"send icon",titulo:"Guardar",pregunta:"¿Desea enviar el registro?"},t}return _inherits(a,React.Component),_createClass(a,[{key:"render",value:function(){return React.createElement("div",{className:"ui mini test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.props.icon}),this.props.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.props.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok basic button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),a}(),SelectDropDown=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t.handleSelectChange=t.handleSelectChange.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e),this.setState({value:e.target.value})}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.value,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),a}(),SelectOption=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:0},t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.setState({value:e.target.value}),this.props.onChange(e)}},{key:"componentDidMount",value:function(){}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("option",{key:e.value,value:e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{key:"0",value:this.props.valor},"Seleccione"),t))}}]),a}(),Calendar=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e="ui calendar "+(0==this.props.visible?" hidden ":"");return React.createElement("div",{className:e,id:this.props.name},React.createElement("div",{className:"field"},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui input left icon"},React.createElement("i",{className:"calendar icon"}),React.createElement("input",{ref:"myCalen",type:"text",readOnly:this.props.readOnly,name:this.props.name,id:this.props.name,placeholder:"Fecha"}))))}}]),t}();function Lista(e){var t=e.enca,a=0,n={display:"none"},s={display:"display"},r=t.map(function(e){return React.createElement("th",{key:a++,style:"id"==e||"Pagaré"==e||"ahorroc"==e||"ahorrov"==e||"SubTotal"==e||"idcredito"==e||"idahorro"==e||"Capital"==e||"Interes"==e||"IVA"==e||"Ahorro comprometido"==e?n:s},e)});return React.createElement("tr",null,r)}var RecordDetalle=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={idcredito:t.props.registro.idcredito,numero:t.props.registro.numero,nopagos:t.props.registro.pagos_col,ahocorriente:t.props.registro.ahorro_vol,capital:t.props.registro.capital,interes:t.props.registro.interes,iva:t.props.registro.iva,importepago:t.props.registro.importepago,ahocomprome:t.props.registro.ahocomprome,ajuste:t.props.registro.ajuste,total:t.props.registro.total,asistencia:t.props.registro.asistencia,incidencia:t.props.registro.incidencia,catIncidencia:[]},t}return _inherits(a,React.Component),_createClass(a,[{key:"componentWillMount",value:function(){}},{key:"handleInputChange",value:function(e){this.setState({nopagos:e.target.value})}},{key:"cargaIncidencias",value:function(e){$.ajax({url:base_url+"/api/ColmenasV1/incidencia/"+e,type:"GET",dataType:"json",success:function(e){this.setState({catIncidencia:e.result})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleChange",value:function(i){var o=this,e=i.target.name;if("ahocorriente[]"==e){var t=this.state.importepago,a=this.state.ahocomprome,n=isNaN(i.target.value)||""==i.target.value?"0":i.target.value;this.setState({ahocorriente:n,total:parseFloat(t)+parseFloat(a)+parseFloat(n)}),this.setState(function(e,t){var a=numeral(e.total).format("0.00"),n=numeral(o.state.total).format("0.00"),s=numeral($("#pagototal").val()).format("0.00"),r=parseFloat(s)-parseFloat(n)+parseFloat(a);o.props.onChange(i,r)})}else if("asistencia[]"==e){i.target.value;this.setState({asistencia:i.target.value})}else if("incidencia[]"==e)this.setState({incidencia:i.target.value});else{var s=i.target.value;if(1==s)this.setState({nopagos:s,capital:this.props.registro.capital,interes:this.props.registro.interes,iva:this.props.registro.iva,importepago:this.props.registro.importepago,ahocomprome:this.props.registro.ahocomprome,ajuste:this.props.registro.ajuste,total:parseFloat(this.props.registro.importepago)+parseFloat(this.props.registro.ahocomprome)+parseFloat(this.props.registro.ajuste)}),this.setState(function(e,t){var a=numeral(e.total).format("0.00"),n=numeral(o.state.total).format("0.00"),s=numeral($("#pagototal").val()).format("0.00"),r=parseFloat(s)-parseFloat(n)+parseFloat(a);o.props.onChange(i,r)});else{var l=this,r=this.state.idcredito,c=parseFloat(this.state.numero)+parseFloat(s),u={url:base_url+"api/CarteraV1/amortizaMas/"+r+"/"+c,type:"GET",dataType:"json"};ajax(u).then(function(e){var r=l.state.total;l.setState({nopagos:s,capital:e.result[0].capital,interes:e.result[0].interes,iva:e.result[0].iva,importepago:e.result[0].importepago,ahocomprome:e.result[0].ahocomprome,ajuste:e.result[0].ajuste,total:parseFloat(e.result[0].importepago)+parseFloat(e.result[0].ahocomprome)+parseFloat(e.result[0].ajuste)}),l.setState(function(e,t){var a=numeral(l.state.total).format("0.00"),n=numeral($("#pagototal").val()).format("0.00"),s=parseFloat(n)-parseFloat(r)+parseFloat(a);l.props.onChange(i,s)})},function(e){var t=validaError(e);l.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui negative floating message"}),l.autoReset()})}}}},{key:"handeClick",value:function(){var e="";e="t"==this.state.asistencia?"f":"t",this.setState({asistencia:e})}},{key:"autoReset",value:function(){var e=this;window.clearTimeout(e.timeout),this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},5e3)}},{key:"render",value:function(){var e=numeral(this.state.capital).format("0,0.00"),t=numeral(this.state.interes).format("0,0.00"),a=numeral(this.state.iva).format("0,0.00"),n=numeral(this.state.importepago).format("0,0.00"),s=numeral(this.state.ahocomprome).format("0,0.00"),r=numeral(this.state.ajuste).format("0,0.00"),i=[],o=this.props.registro.nopagos,l=0;l=null==this.state.ahocorriente?0:this.state.ahocorriente;var c=numeral(parseFloat(this.state.importepago)+parseFloat(numeral(this.state.ahocomprome).format("0.00"))+parseFloat(this.state.ajuste)+parseFloat(l)).format("0,0.00");o.forEach(function(e){i.push(React.createElement("option",{value:e.value},e.name))});var u=[];this.props.catAsistencia.forEach(function(e){u.push(React.createElement("option",{value:e.value},e.name))});var p=[];this.props.catIncidencia.forEach(function(e){p.push(React.createElement("option",{value:e.value},e.name))});var m={display:"none"};return React.createElement("tr",null,React.createElement("td",null,React.createElement("input",{type:"text",className:"styleNo table-input",id:"idacreditado[]",name:"idacreditado[]",value:this.props.registro.idacreditado})," "),React.createElement("td",{style:m},React.createElement("input",{type:"text",className:"styleNo",id:"numero_cuentac[]",name:"numero_cuentac[]",value:this.props.registro.numero_cuentac})," "),React.createElement("td",{style:m},React.createElement("input",{type:"text",className:"styleNo",id:"numero_cuentav[]",name:"numero_cuentav[]",value:this.props.registro.numero_cuentav})," "),React.createElement("td",{style:m},React.createElement("input",{type:"text",className:"styleNo",id:"idcredito[]",name:"idcredito[]",value:this.props.registro.idcredito})," "),React.createElement("td",null,React.createElement("input",{type:"text",className:"styleNo table-input",id:"numero[]",name:"numero[]",value:this.props.registro.numero})),React.createElement("td",null,this.props.registro.acreditado," "),React.createElement("td",{style:m},React.createElement("input",{type:"text",className:"styleNo",id:"idpagare[]",name:"idpagare[]",value:this.props.registro.idpagare})," "),React.createElement("td",null,React.createElement("select",{className:"ui dropdown",value:this.state.nopagos,name:"nopagos[]",onChange:this.handleChange.bind(this)},i)),React.createElement("td",{style:m},React.createElement("input",{className:"table-input",type:"text",readOnly:"readOnly",id:"capital[]",name:"capital[]",value:e})," "),React.createElement("td",{style:m},React.createElement("input",{className:"table-input",type:"text",readOnly:"readOnly",id:"interes[]",name:"interes[]",value:t})," "),React.createElement("td",{style:m},React.createElement("input",{className:"table-input",type:"text",readOnly:"readOnly",id:"iva[]",name:"iva[]",value:a})," "),React.createElement("td",{style:m},React.createElement("input",{className:"table-input",readOnly:"readOnly",type:"text",id:"importepago[]",name:"importepago[]",value:n})," "),React.createElement("td",{style:m},React.createElement("input",{className:"table-input",type:"text",readOnly:"readOnly",id:"ahocomprome[]",name:"ahocomprome[]",value:s})," "),React.createElement("td",null,React.createElement("input",{className:"table-input",type:"text",id:"ahocorriente[]",name:"ahocorriente[]",value:this.state.ahocorriente,onChange:this.handleChange.bind(this)})," "),React.createElement("td",null,React.createElement("input",{className:"table-input",type:"text",readOnly:"readOnly",id:"total[]",name:"total[]",value:c})," "),React.createElement("td",{style:m},React.createElement("input",{className:"table-input",type:"text",readOnly:"readOnly",id:"ajuste[]",name:"ajuste[]",value:r})," "),React.createElement("td",{className:"center aligned"},React.createElement("select",{className:"ui dropdown",value:this.state.asistencia,name:"asistencia[]",onChange:this.handleChange.bind(this)},u)),React.createElement("td",null,React.createElement("select",{className:"ui dropdown",value:this.state.incidencia,name:"incidencia[]",onChange:this.handleChange.bind(this)},p)))}}]),a}(),Table=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.handleChange=t.handleChange.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleChange",value:function(e,t){this.props.onChange(e,t)}},{key:"render",value:function(){var t=[],e=this.props.datos;if(e instanceof Array==!0){var a=this.props.asistencia,n=this.props.incidencia;e.forEach(function(e){t.push(React.createElement(RecordDetalle,{registro:e,catAsistencia:a,catIncidencia:n,onChange:this.handleChange}))}.bind(this))}return React.createElement("div",null,React.createElement("table",{className:"ui selectable celled blue table"},React.createElement("thead",null,React.createElement(Lista,{enca:["No. Socia","ahorroc","ahorrov","id","No","Nombre","Pagaré","No.Pagos","Capital","Interes","IVA","SubTotal","Ahorro comprometido","Ahorro","Total","Asistencia","Incidencia"]})),React.createElement("tbody",null,t)))}}]),a}(),Captura=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={idcredito:0,idcolmena:0,fecha_pago:"",vales:"",new:!0,catColmenas:[],catGrupos:[],catPagares:[],catAsistencia:[],catVales:[],idgrupo:0,idpagare:"",totalcompara:0,btnreversa:!1,notavis:!1,notavis_lista:"",icon:"send icon",titulo:"Guardar",pregunta:"¿Desea enviar el registro?",sumatotal:0,totalxpagar:0,supervisor:!1,fecha:"",hora:"",vale:"",semana:"",caja:"",idautoriza:0,catAutoriza:[],totalautoriza:0,deposito:0,catIncidencia:[]},t.handleonBlur=t.handleonBlur.bind(t),t.handleChangeTotal=t.handleChangeTotal.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e});var t=getWeekNo();this.setState({semana:t}),$.ajax({url:base_url+"/api/ColmenasV1/colmenas",type:"GET",dataType:"json",success:function(e){this.setState({catColmenas:e.catcolmenas})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)}),$.ajax({url:base_url+"/api/ColmenasV1/catAIV",type:"GET",dataType:"json",success:function(e){this.setState({catAsistencia:e.asistencia,catIncidencia:e.incidencia,catVales:e.vales})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)}),this.getAutoriza()}},{key:"getAutoriza",value:function(){$.ajax({url:base_url+"/api/BancosV1/autorizacion",type:"GET",dataType:"json",success:function(e){this.setState({catAutoriza:e.result})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleSubmit",value:function(e){e.preventDefault(),$(".ui.form.formgen").form({inline:!0,on:"blur",fields:{totalcompara:{identifier:"totalcompara",rules:[{type:"empty",prompt:"Requiere un valor"}]},pagototal:{identifier:"pagototal",rules:[{type:"empty",prompt:"Requiere un valor"}]},match:{identifier:"totalcompara",rules:[{type:"match[pagototal]",prompt:"Importes diferentes!"}]},autorizacion:{identifier:"autorizacion",rules:[{type:"empty",prompt:"Requiere un valor"}]},fecha:{identifier:"fecha",rules:[{type:"empty",prompt:"Requiere un valor"}]},hora:{identifier:"hora",rules:[{type:"empty",prompt:"Requiere un valor"}]},vale:{identifier:"vale",rules:[{type:"empty",prompt:"Requiere un valor"}]},semana:{identifier:"semana",rules:[{type:"empty",prompt:"Requiere un valor"}]},caja:{identifier:"caja",rules:[{type:"empty",prompt:"Requiere un valor"}]},deposito:{identifier:"deposito",rules:[{type:"empty",prompt:"Requiere un valor"}]}}}),$(".ui.form.formgen").form("validate form");var t=$(".ui.form.formgen").form("is valid");if(0==this.state.totalcompara&&0==this.state.pagototal&&(t=!1),this.setState({message:"",statusmessage:"ui message hidden"}),1==t){var a=$(".get.soling form"),n=a.form("get values"),s=a.form("get value","csrf_bancomunidad_token"),r=this;$(".mini.modal").modal({closable:!1,onApprove:function(){var e={url:base_url+"api/ColmenasV1/add_aplica_col",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:s,data:n}};ajax(e).then(function(e){r.setState({catPagares:[],idgrupo:0,csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message ",totalxpagar:0,totalcompara:0,pagototal:0,vale:"",hora:"",caja:"",deposito:0,fecha:"",idautoriza:0}),r.autoReset();$(".get.soling form").form("set values",{idgrupo:"0",idautoriza:0});var t=e.nomov;r.setState({nomovgrupal:t})},function(e){var t=validaError(e);r.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui negative floating message"}),r.autoReset()})}}).modal("show")}else this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset()}},{key:"handleButton",value:function(){}},{key:"handleReversa",value:function(){}},{key:"handleChangeTotal",value:function(e,t){var a=numeral(t).format("0,0.00");this.setState({pagototal:a})}},{key:"handleonBlur",value:function(e){var t=e.target,a=t.value,n=t.name,s=numeral(a).format("0,0.00");this.setState(_defineProperty({},n,s))}},{key:"handleInputChange",value:function(e){var t=e.target,a=t.value,n=t.name;if(this.setState(_defineProperty({},n,a)),"idcolmena"===n){var s=this,r={url:base_url+"/api/CarteraV1/colmenas_grupos/"+a,type:"GET",dataType:"json"};ajax(r).then(function(e){var t;s.setState((_defineProperty(t={catGrupos:e.result,idgrupo:0,catPagares:[],totalxpagar:9},"totalxpagar",0),_defineProperty(t,"totalcompara",0),_defineProperty(t,"btnreversa",!1),_defineProperty(t,"nomovreversa",0),_defineProperty(t,"supervisor",!1),t))},function(e){var t;s.setState((_defineProperty(t={message:validaError(e).message,statusmessage:"ui negative floating message",idgrupo:0,catPagares:[],totalxpagar:9},"totalxpagar",0),_defineProperty(t,"totalcompara",0),_defineProperty(t,"btnreversa",!1),_defineProperty(t,"nomovreversa",0),_defineProperty(t,"supervisor",!1),t))})}else if("idgrupo"==n){var i=a;if("0"==i||""==i)this.setState({catPagares:[],totalxpagar:0,totalcompara:0,btnreversa:!1,nomovreversa:0});else{this.setState({catPagares:[],grantotal:0,totalxpagar:0,totalcompara:0,btnreversa:!1,nomovreversa:0});var o=this,l={url:base_url+"/api/CarteraV1/amortiza/"+i,type:"GET",dataType:"json"};ajax(l).then(function(e){var t=numeral(e.totalxpagar).format("0,0.00"),a=e.pago;o.setState({catPagares:e.result,totalxpagar:e.totalxpagar,totalcompara:0,pagototal:t,supervisor:e.supervisor,message:e.message,statusmessage:"ui positive floating message "});var n=e.fecha_pago_col;if(null!=n&&""!=n){var s=o.state.catAutoriza.find(function(e){return e.fecha_pago_colmena==n&&e.grupoid==i});o.setState({idautoriza:s.id,new:!1});$(".get.soling form").form("set values",{autorizacion:s.id})}else{o.setState({idautoriza:"",fecha:"",hora:"",vale:"",caja:"",new:!0});$(".get.soling form").form("set values",{autorizacion:""})}o.setState({notavis:e.falta}),1==e.falta&&o.setState({notavis_lista:e.falta_lista}),o.autoReset(),0!=a.length&&o.setState({btnreversa:!0,nomovreversa:a[0].nomov})},function(e){o.setState({catPagares:[],grantotal:0,totalcompara:0,pagototal:0,message:validaError(e).message,statusmessage:"ui negative floating message",notavis:!1,notavis_lista:"",btnreversa:!1,nomovreversa:0,supervisor:!1}),o.autoReset()})}}if("autorizacion"==n){var c=this.state.catAutoriza.find(function(e){return e.value==a});null==c.vale?this.setState({new:!0}):this.setState({new:!1}),this.setState({fecha:c.fechao,deposito:numeral(c.deposito).format("0,0.00"),vale:c.vale,semana:c.semana,caja:c.caja}),"12:12:00"!=c.hora&&this.setState({hora:c.hora})}}},{key:"autoReset",value:function(){var e=this;window.clearTimeout(e.timeout),this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},5e3)}},{key:"render",value:function(){var t=this,e=(this.state.statusmessage,null);0==this.state.supervisor&&(e=React.createElement("button",{className:!0===this.state.notavis?"ui submit bottom primary basic button disabled":"ui submit bottom primary basic button",type:"submit",name:"action"},React.createElement("i",{className:"send icon"}),"Enviar"));var a;a=React.createElement(InputField,{id:"vale",label:"Vale",cols:"two wide",valor:this.state.vale,onChange:this.handleInputChange.bind(this)});var n=null,s=null,r=null,i=null,o=null,l=null,c=null;return"fin."==esquema||"FIN."==esquema||(n=React.createElement(SelectDropDown,{name:"autorizacion",cols:"three wide",id:"autorizacion",label:"Autorización",valor:this.state.idautoriza,valores:this.state.catAutoriza,onChange:this.handleInputChange.bind(this)}),s=React.createElement(InputField,{id:"fecha",label:"Fecha",readOnly:"readOnly",cols:"two wide",valor:this.state.fecha,onChange:this.handleInputChange.bind(this)}),r=React.createElement(InputField,{id:"hora",label:"Hora",cols:"two wide",valor:this.state.hora,onChange:this.handleInputChange.bind(this)}),i=React.createElement(InputField,{id:"semana",label:"Semana",cols:"two wide",valor:this.state.semana,onChange:this.handleInputChange.bind(this)}),o=React.createElement(InputField,{id:"caja",label:"Caja",cols:"two wide",valor:this.state.caja,onChange:this.handleInputChange.bind(this)}),l=React.createElement(InputFieldNumber,{readOnly:"readOnly",id:"deposito",label:"Deposito",valor:this.state.deposito,onChange:this.handleInputChange.bind(this),onBlur:this.handleonBlur}),c=React.createElement(InputFieldNumber,{readOnly:"readOnly",id:"totalautoriza",label:"Total Autorizacion",valor:this.state.totalautoriza,onChange:this.handleInputChange.bind(this),onBlur:this.handleonBlur})),React.createElement("div",{className:"main ui intro  top"},React.createElement("div",{className:"ui segment vertical"},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Aplicación de pagos Grupal"))),React.createElement("div",{className:"ui secondary menu"},React.createElement("div",{className:"ui basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Último movimiento",onClick:this.handleButton.bind(this,20)},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,20)})))),React.createElement("div",{className:"get soling"},React.createElement("form",{className:"ui form formgen",ref:"form",onSubmit:this.handleSubmit.bind(this),method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"fields"},React.createElement(SelectDropDown,{name:"idcolmena",cols:"five wide",id:"idcolmena",label:"Colmena",valor:this.state.idcolmena,valores:this.state.catColmenas,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectOption,{name:"idgrupo",cols:"three wide",id:"idgrupo",label:"Grupo",valor:this.state.idgrupo,valores:this.state.catGrupos,onChange:this.handleInputChange.bind(this)}),React.createElement("div",{className:" hidden "},React.createElement(Calendar,{name:"fecha_pago",cols:"three wide",label:"Fecha",valor:this.state.fecha_pago,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui vertical segment eight wide field right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:1==this.state.btnreversa?"ui bottom primary basic button":"ui bottom primary basic button disabled",type:"button",name:"reversa",onClick:this.handleReversa.bind(this)},React.createElement("i",{className:"send icon"}),"Reversa")))),React.createElement(Nota,{visible:this.state.notavis,notavis_lista:this.state.notavis_lista}),React.createElement(Mensaje,{icon:this.state.icon,titulo:this.state.titulo,pregunta:this.state.pregunta}),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(e){return t.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{id:"pagares",className:"row"},React.createElement("div",{className:"row"},React.createElement("div",{className:"four fields"},React.createElement(InputFieldNumber,{id:"totalcompara",label:"Total Pago",valor:this.state.totalcompara,onChange:this.handleInputChange.bind(this),onBlur:this.handleonBlur})),React.createElement(Table,{datos:this.state.catPagares,totalxpagar:this.state.totalxpagar,onChange:this.handleChangeTotal.bind(this),asistencia:this.state.catAsistencia,incidencia:this.state.catIncidencia}),React.createElement("div",{className:"clear"}),React.createElement("div",{className:"ui right floated  tiny orange statistic"},React.createElement("input",{className:"totalxpagar",type:"text",id:"pagototal",name:"grantotal",value:this.state.pagototal,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},e)),React.createElement("div",{className:"fields"},n,s,r,a,i,o),React.createElement("div",{className:"fields"},l,c))))))}}]),a}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));