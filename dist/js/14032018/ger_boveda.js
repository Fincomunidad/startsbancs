"use strict";var _createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,a,n){return a&&e(t.prototype,a),n&&e(t,n),t}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var Mensaje=function(e){_inherits(t,React.Component);function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={icon:"send icon",titulo:"Guardar",pregunta:"¿Desea enviar el registro?"},a}return _createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:"ui mini test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok basic button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),t}(),InputFieldNumber=function(e){_inherits(t,React.Component);function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _createClass(t,[{key:"render",value:function(){var e=this,t=(void 0!==this.props.cols?this.props.cols:"")+" field";return React.createElement("div",{className:t},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui labeled input"},React.createElement("div",{className:"ui label"},"$"),React.createElement("input",{type:"text",id:this.props.id,name:this.props.id,readOnly:this.props.readOnly,value:this.props.valor,onChange:function(t){return e.props.onChange(t)},onBlur:function(t){return e.props.onBlur(t)}})))}}]),t}(),SelectDropDown=function(e){_inherits(t,React.Component);function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a.handleSelectChange=a.handleSelectChange.bind(a),a}return _createClass(t,[{key:"handleSelectChange",value:function(e){this.props.onChange(e),this.setState({value:e.target.value})}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field "+(0==this.props.visible?" hidden ":""),t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.value,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),t}(),SelectOption=function(e){_inherits(t,React.Component);function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a}return _createClass(t,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" inline field ",t=this.props.valores.map(function(e){return React.createElement("option",{value:e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{value:this.props.valor},"Seleccione"),t))}}]),t}();function Lista(e){var t=0,a=e.enca.map(function(e){return React.createElement("th",{className:"two wide",key:t++},e)});return React.createElement("tr",null,a)}var RecordDetalle=function(e){_inherits(t,React.Component);function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={cantidad:0,total:0},a}return _createClass(t,[{key:"handleChange",value:function(e){var t=this,a=(e.target.name,isNaN(e.target.value)||""==e.target.value?"0":e.target.value),n=this.props.registro.saldo;parseFloat(a)<=parseFloat(n)||"I"==this.props.movimiento?this.setState({cantidad:a,total:parseFloat(this.props.registro.nombre)*parseFloat(a)}):this.setState({cantidad:0,total:parseFloat(this.props.registro.nombre)*parseFloat(0)}),this.setState(function(a,n){var i=numeral(a.total).format("0.00"),s=numeral(t.state.total).format("0.00"),o=numeral($("#grantotal").val()).format("0.00"),r=parseFloat(o)-parseFloat(s)+parseFloat(i);t.props.onChange(e,r)})}},{key:"render",value:function(){numeral(this.state.cantidad).format("0,0.00");var e=numeral(this.state.total).format("0,0.00"),t=null,a=numeral(this.props.registro.saldo).format("0,0"),n=React.createElement("td",{className:"right aligned"},a);if(1==this.props.existe){var i=numeral(this.props.registro.cantidad).format("0,0");t=React.createElement("td",{className:"right aligned"},i)}else t=React.createElement("td",null,React.createElement("input",{className:"table-input",type:"text",name:"cantidad[]",value:this.state.cantidad,onChange:this.handleChange.bind(this)})," ");var s=null;if(1==this.props.existe){var o=numeral(this.props.registro.total).format("0,0.00");s=React.createElement("td",{className:"right aligned"}," ",o)}else s=React.createElement("td",null,React.createElement("input",{className:"table-input",type:"text",name:"total[]",value:e})," ");return React.createElement("tr",null,React.createElement("td",{style:{display:"none"}},React.createElement("input",{className:"table-input",type:"text",id:"iddenomina[]",name:"iddenomina[]",value:this.props.registro.iddenomina})," "),React.createElement("td",null,this.props.registro.nombre),n,t,s)}}]),t}(),Table=function(e){_inherits(t,React.Component);function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={grantotal:a.props.totalxpagar},a.handleChange=a.handleChange.bind(a),a}return _createClass(t,[{key:"componentDidUpdate",value:function(e,t){0==this.props.datos.length&&0!=this.state.grantotal&&this.setState({grantotal:0})}},{key:"handleChange",value:function(e,t){this.setState({grantotal:t})}},{key:"render",value:function(){var e=[],t=this.props.datos,a=0!=this.state.grantotal?numeral(this.state.grantotal).format("0,0.00"):numeral(this.props.totalxpagar).format("0,0.00"),n=null;if(t instanceof Array==!0){var i=0;t.forEach(function(t){i+=1,e.push(React.createElement(RecordDetalle,{registro:t,id:i,existe:this.props.existe,movimiento:this.props.movimiento,onChange:this.handleChange}))}.bind(this)),n=React.createElement("input",{className:"totalxpagar",type:"text",id:"grantotal",name:"grantotal",value:a})}else this.setState({grantotal:0}),n=React.createElement("input",{className:"totalxpagar",type:"text",id:"grantotal",name:"grantotal",value:"0"});return React.createElement("div",{className:"ui grid"},React.createElement("div",{className:"eight wide column"},React.createElement("table",{className:"ui selectable celled blue table"},React.createElement("thead",null,React.createElement(Lista,{enca:["Denominación","Saldo","Cantidad","Importe"]})),React.createElement("tbody",null,e),React.createElement("tfoot",{className:"full-width"},React.createElement("tr",null,React.createElement("th",{colSpan:4},React.createElement("div",{className:"ui right floated  tiny orange statistic"},n)))))))}}]),t}(),Captura=function(e){_inherits(t,React.Component);function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={csrf:"",idclave:0,catBoveda:[],catBancos:[],catDenomina:[],catDenoIni:[],ocultar:!0,boton:"Apertura",movimiento:0,labdes_ori:"Destino",labidbanco:"Caja",des_ori:0,idbanco:0,importe:0,totalxpagar:0,message:"",statusmessage:"",idmov:0,idmovdet:0,existe:0,idmovecho:0,catMov:[],idmovisible:!1,fecierreant:"",ocultacierre:!0},a.handleInputChange=a.handleInputChange.bind(a),a.handleonBlur=a.handleonBlur.bind(a),a}return _createClass(t,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),$.ajax({url:base_url+"/api/CarteraV1/bovedas",type:"GET",dataType:"json",success:function(e){this.setState({catBoveda:e.boveda,catDenoIni:e.denomina})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleInputChange",value:function(e){var t=e.target,a=t.value,n=t.name,i="",s="";if(this.setState(_defineProperty({},n,a)),"importe"!=n){var o=this.state.des_ori,r=this.state.movimiento,l=this.state.idbanco;if("idclave"!=n){if("idmovecho"==n)this.setState({idmovdet:a});else if("movimiento"==n)s="Destino","I"==a&&(s="Origen"),this.setState({labdes_ori:s}),r=a;else if("des_ori"==n){o=a,s="Caja","B"==a&&(s="Banco"),this.setState({labidbanco:s}),"Caja"==s?i="generalv1/cajasall":"Banco"==s&&(i="generalv1/bancosall");var c={url:base_url+"api/"+i,type:"GET",dataType:"json"};$(".get.bovmov form").form("set values",{idbanco:"0"});this.setState({catBancos:[]});var m=this;ajax(c).then(function(e){m.setState({catBancos:e.result,idbanco:0});$(".get.bovmov form").form("set values",{idbanco:"0"})},function(e){validaError(e)})}else"idbanco"==n&&(l=a);if("C"!=o||"I"!=r||""==l)if(""==o||"0"==o||""==r||"0"==r||""==l||"0"==l)this.state.catDenomina!=[]&&this.setState({existe:0,catDenomina:[],importe:0,totalxpagar:0});else{var u=this.state.idmov,d={url:base_url+"api/CarteraV1/getSaldoBoveda/"+u,type:"GET",dataType:"json"},p=this;ajax(d).then(function(e){p.setState({existe:0,catDenomina:e.result,importe:0,totalxpagar:0})},function(e){validaError(e);p.setState({existe:0,importe:0,catDenomina:[],totalxpagar:0}),p.autoReset()})}else{var h=this.state.idmov,v={url:base_url+"api/CarteraV1/getCorteCaja/"+h+"/"+l,type:"GET",dataType:"json"},f=this;ajax(v).then(function(e){var t=numeral(e.mov[0].importe).format("0,0.00");f.setState(_defineProperty({message:e.message,idmovdet:e.mov[0].idmovdet,idmovisible:!1,statusmessage:"ui positive floating message",existe:1,totalxpagar:t,importe:t,catDenomina:e.movdet},"totalxpagar",e.mov[0].importe)),f.autoReset()},function(e){var t=validaError(e);f.setState({message:t.message,existe:0,statusmessage:"ui negative floating message",importe:0,catDenomina:[],totalxpagar:0}),f.autoReset()})}}else{var g={url:base_url+"api/CarteraV1/getboveda/"+a,type:"GET",dataType:"json"},b=this;ajax(g).then(function(e){0==e.result?e.anterior!=[]?b.setState({boton:"Cierre",ocultar:!0,ocultacierre:!1,idmov:e.anterior[0].idmov,fecierreant:e.anterior[0].fecinicio,catMov:e.movimientos}):b.setState({boton:"Apertura",ocultar:!0,ocultacierre:!0,catMov:[]}):"1"==e.result[0].status?b.setState({boton:"Cierre",ocultar:!1,ocultacierre:!0,idmov:e.result[0].idmov,catMov:e.movimientos}):b.setState({boton:"Cerrado",ocultar:!0,ocultacierre:!0,idmov:e.result[0].idmov,catMov:e.movimientos,idmovdet:e.result[0].idmov})},function(e){var t=validaError(e);b.setState({message:t.message,statusmessage:"ui negative floating message",catMov:[]}),b.autoReset()})}}}},{key:"handleonBlur",value:function(e){var t=e.target,a=t.value,n=(t.name,numeral(a).format("0,0.00"));this.setState({importe:n})}},{key:"handleButton",value:function(e,t){if(1==e)this.printReport();else if(3==e){var a=!1;0==this.state.idmovisible&&(a=!0),this.setState({idmovisible:a})}else 4==e&&this.findMov()}},{key:"findMov",value:function(){if(""!=this.state.idmov){var e=this,t=this.state.idmov,a={url:base_url+"api/CarteraV1/getboveda_mov/"+t,type:"GET",dataType:"json"};ajax(a).then(function(t){e.setState({catMov:t.result})},function(e){})}}},{key:"printReport",value:function(){if(0!=this.state.idmovdet){var e=this.state.idmovdet,t=t="bovedarep/"+e;"Cerrado"==this.state.boton&&(t="bovedacierep/"+e);var a=document.createElement("a");a.href=base_url+"api/ReportV1/"+t,a.target="_blank",document.body.appendChild(a),a.click(),document.body.removeChild(a)}}},{key:"handleSubmitOpen",value:function(){event.preventDefault();var e=this.state.boton;$(".ui.form.formopen").form({inline:!0,on:"blur",fields:{idclave:{identifier:"idclave",rules:[{type:"empty",prompt:"Numero de Cuenta incorrecto "}]}}}),$(".ui.form.formopen").form("validate form");var t=$(".ui.form.formopen").form("is valid");if("Cierre"!=e&&"Apertura"!=e&&(t=!1),this.setState({message:"",statusmessage:"ui message hidden"}),1==t){var a=$(".get.bovopen form"),n=a.form("get values"),i=a.form("get value","csrf_bancomunidad_token"),s="openboveda";"Cierre"==e&&(s="closeboveda/"+this.state.idmov);var o=this;$(".mini.modal").modal({closable:!1,onApprove:function(){var t={url:base_url+"api/CarteraV1/"+s,type:"POST",dataType:"json",data:{csrf_bancomunidad_token:i,data:n}},a="Cierre",r=!1;ajax(t).then(function(t){"Cierre"==e&&(a="Cerrado",r=!0),o.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui positive floating message ",boton:a,idmov:t.registros,ocultar:r,ocultacierre:!0}),o.autoReset()},function(e){var t=validaError(e);o.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui negative floating message"}),o.autoReset()})}}).modal("show")}else"Cierre"!=e&&"Apertura"!=e?this.setState({message:"Boveda Cerrada!",statusmessage:"ui negative floating message"}):this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset()}},{key:"handleSubmitMov",value:function(){event.preventDefault(),$(".ui.form.formmov").form({inline:!0,on:"blur",fields:{importe:{identifier:"importe",rules:[{type:"empty",prompt:"Requiere un valor"}]},grantotal:{identifier:"grantotal",rules:[{type:"empty",prompt:"Requiere un valor"}]},match:{identifier:"importe",rules:[{type:"match[grantotal]",prompt:"Importes diferentes!"}]},movimiento:{identifier:"movimiento",rules:[{type:"empty",prompt:"Requiere un valor"}]},des_ori:{identifier:"des_ori",rules:[{type:"empty",prompt:"Requiere un valor"}]},idbanco:{identifier:"idbanco",rules:[{type:"empty",prompt:"Requiere un valor"}]}}}),$(".ui.form.formmov").form("validate form");var e=$(".ui.form.formmov").form("is valid");if(0==this.state.importe&&0==this.state.totalxpagar&&(e=!1),1==e){var t=$(".get.bovmov form"),a=t.form("get values"),n=t.form("get value","csrf_bancomunidad_token"),i=this,s=this.state.idmov;$(".mini.modal").modal({closable:!1,onApprove:function(){var e={url:base_url+"api/CarteraV1/add_boveda/"+s,type:"POST",dataType:"json",data:{csrf_bancomunidad_token:n,data:a}};ajax(e).then(function(e){0!=e.registros&&i.setState({idmovdet:e.registros});var t=i.state.catDenoIni;i.setState({csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message ",idgrupo:0,des_ori:0,movimiento:0,importe:0,catDenomina:t,totalxpagar:0});$(".get.bovmov form").form("set values",{idgrupo:"0",des_ori:"0",movimiento:"0"});i.autoReset(),i.findMov()},function(e){var t=validaError(e);i.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui negative floating message"}),i.autoReset()})}}).modal("show")}else this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset()}},{key:"autoReset",value:function(){var e=this;window.clearTimeout(e.timeout),this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},5e3)}},{key:"render",value:function(){var e=this;return React.createElement("div",null,React.createElement("div",{className:"ui segment vertical "},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Control de Boveda")),React.createElement("div",{className:"ui  basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Nuevo Registro"},React.createElement("i",{className:"plus square outline icon",onClick:this.handleButton.bind(this,0)})),React.createElement("button",{className:"ui button","data-tooltip":"Formato PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,1)}))),React.createElement("div",{className:"ui basic right floated icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Movimientos"},React.createElement("i",{className:"list icon",onClick:this.handleButton.bind(this,3)})),React.createElement("button",{className:"ui button","data-tooltip":"Actualizar"},React.createElement("i",{className:"refresh icon",onClick:this.handleButton.bind(this,4)})))),React.createElement(Mensaje,null),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(t){window.clearTimeout(e.timeout),e.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{className:"get bovopen"},React.createElement("form",{className:"ui form formopen",ref:"form",onSubmit:this.handleSubmitOpen.bind(this),method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{visible:!0,name:"idclave",id:"idclave",label:"Boveda",valor:this.state.idclave,valores:this.state.catBoveda,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectDropDown,{visible:this.state.idmovisible,name:"idmovecho",id:"idmovecho",label:"Movimientos",valor:this.state.idmovecho,valores:this.state.catMov,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:1==this.state.ocultacierre?"hidden":"ui inverted red segment"},React.createElement("div",{className:"ui header"},"Aviso importante."),React.createElement("div",{className:"ui subheader"},"Se ha identificado que no fue cerrada la boveda del día ",this.state.fecierreant,", presione el botón Cerrar para realizar el proceso")),React.createElement("div",{className:"ui vertical segment left aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui submit bottom primary basic button",type:"submit",name:"action"},React.createElement("i",{className:"send icon"})," ",this.state.boton," "))))),React.createElement("div",{className:1==this.state.ocultar?"hidden":""},React.createElement("div",{className:"get bovmov"},React.createElement("form",{className:"ui form formmov",ref:"form",onSubmit:this.handleSubmitMov.bind(this),method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"four fields"},React.createElement(SelectOption,{name:"movimiento",id:"movimiento",label:"Movimiento",valor:this.state.movimiento,valores:[{name:"Ingreso a Boveda",value:"I"},{name:"Egreso de Boveda",value:"E"}],onChange:this.handleInputChange.bind(this)}),React.createElement(SelectOption,{name:"des_ori",id:"des_ori",label:this.state.labdes_ori,valor:this.state.des_ori,valores:[{name:"Caja",value:"C"},{name:"Banco",value:"B"}],onChange:this.handleInputChange.bind(this)}),React.createElement(SelectOption,{name:"idbanco",id:"idbanco",label:this.state.labidbanco,valor:this.state.idbanco,valores:this.state.catBancos,onChange:this.handleInputChange.bind(this)}),React.createElement(InputFieldNumber,{id:"importe",label:"Importe",valor:this.state.importe,onChange:this.handleInputChange,onBlur:this.handleonBlur})),React.createElement(Table,{datos:this.state.catDenomina,existe:this.state.existe,totalxpagar:this.state.totalxpagar,movimiento:this.state.movimiento}),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui submit bottom primary basic button",type:"submit",name:"action"},React.createElement("i",{className:"send icon"})," Enviar ")))))))}}]),t}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));