"use strict";var _createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,a,n){return a&&e(t.prototype,a),n&&e(t,n),t}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var SelectDropDown=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a.handleSelectChange=a.handleSelectChange.bind(a),a}return _inherits(t,React.Component),_createClass(t,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=void 0;return!1!==this.props.valores&&(t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)})),React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.props.valor,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),t}(),CheckBox=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this,t="1"==this.props.valor?"ui checkbox checked":"ui checkbox";return React.createElement("div",{className:"field"},React.createElement("label",null,"Seleccione"),React.createElement("div",{className:"four fields"},React.createElement("div",{className:"ten wide inline field"},React.createElement("div",{className:t,onClick:function(t){return e.props.onClickCheck(t,e.props.name,e.props.valor)}},React.createElement("input",{type:"checkbox",value:1==this.props.valor?"on":"off",id:this.props.name,name:this.props.name,tabindex:"0",class:"hidden"}),React.createElement("label",null,this.props.titulo)))))}}]),t}();function Lista(e){var t=e.enca,a=0,n=t.map(function(e){return React.createElement("th",{key:a++},e)});return React.createElement("tr",null,n)}var RecordDetalle=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.handleDeleteRecord=a.handleDeleteRecord.bind(a),a}return _inherits(t,React.Component),_createClass(t,[{key:"handleDeleteRecord",value:function(e){}},{key:"render",value:function(){this.props.registro.activo&&React.createElement("i",{className:"green checkmark icon"});return React.createElement("tr",null,React.createElement("td",null,this.props.registro.idahorro),React.createElement("td",null,this.props.registro.numero_cuenta),React.createElement("td",null,this.props.registro.nombre),React.createElement("td",null,this.props.registro.fecha_alta),React.createElement("td",{className:" center aligned"},React.createElement("a",{"data-tooltip":"Cancelar registro"},React.createElement("i",{className:"trash outline icon circular red",onClick:this.handleDeleteRecord}))))}}]),t}(),Table=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=[],t=this.props.datos;t instanceof Array==!0&&0!=t.length&&t.forEach(function(t){e.push(React.createElement(RecordDetalle,{registro:t}))});return React.createElement("div",null,React.createElement("table",{className:"ui selectable celled red table"},React.createElement("thead",null,React.createElement(Lista,{enca:["No. Ahorro","Cuenta","Producto","Fecha alta","Acción"]})),React.createElement("tbody",null,e)))}}]),t}(),InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this,t=(void 0!==this.props.cols?this.props.cols:"")+" field ",a="true"==this.props.mayuscula?"mayuscula":"";return React.createElement("div",{className:t},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:a,id:this.props.id,readOnly:this.props.readOnly,name:this.props.id,type:"text",value:this.props.valor,placeholder:this.props.placeholder,onChange:function(t){return e.props.onChange(t)}})))}}]),t}(),InputFieldFind=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this,t=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:t},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,value:this.props.valor,type:"text",placeholder:this.props.placeholder,onChange:function(t){return e.props.onChange(t)}}),React.createElement("i",{className:this.props.icons,onClick:function(t){return e.props.onClick(t,e.props.valor,e.props.id)}})))}}]),t}(),Mensaje=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={icon:"send icon",titulo:"Guardar",pregunta:"¿Desea enviar el registro?"},a}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:"ui mini test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok basic button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),t}(),SelectOption=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a}return _inherits(t,React.Component),_createClass(t,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("option",{value:e.value,"data-cuenta":e.idcuenta},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{value:this.props.valor,cuenta:this.state.value},"Seleccione"),t))}}]),t}(),Captura=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={catProductos:[],catCuentas:[],activo:0,alta:0,idacreditado:"",acreditadoid:0,nombre:"",idproducto:"",numero_cuentanew:"",idahorro:0,nombre1:"",nombre2:"",apaterno:"",amaterno:"",curp:"",idparentesco:"",catparentesco:[],csrf:"",message:"",statusmessage:"ui floating hidden message",icons1:"inverted circular search link icon",visible:!1},a.handleClickMessage=a.handleClickMessage.bind(a),a}return _inherits(t,React.Component),_createClass(t,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),$.ajax({url:base_url+"/api/CarteraV1/getProductos",type:"GET",dataType:"json",success:function(e){this.setState({catProductos:e.catproductos,catparentesco:e.catparentescos})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleonClickCheck",value:function(e,t,a){this.setState(_defineProperty({},t,"1"==a?"0":"1"))}},{key:"handleInputChange",value:function(e){var t=e.target,a=t.value,n=t.name;if(this.setState(_defineProperty({},n,a)),"idproducto"==n){this.setState({idproducto:a});var r=this.state.acreditadoid,i=a,s=this,c={url:base_url+"api/CarteraV1/getCuentaNew",type:"GET",dataType:"json",data:{acreditadoid:r,idproducto:i}};ajax(c).then(function(e){var t=i;if(0==e.acre.length)t+="1";else{var a=1;if(""!=e.acre[0].numero_cuenta.substring(2)){var n=e.acre[0].numero_cuenta.substring(2);a=parseInt(n)+1}t=e.acre[0].numero_cuenta.substring(0,2)+a}s.setState({numero_cuentanew:t})},function(e){validaError(e);s.setState({message:"Error en cuenta!",numero_cuentanew:""}),s.autoReset()})}}},{key:"getCatCuentas",value:function(e){var t=this,a={url:base_url+"api/CarteraV1/getCuentasAcre/"+e,type:"GET",dataType:"json"};ajax(a).then(function(e){t.setState({nombre:e.acre[0].nombre,acreditadoid:e.acre[0].acreditadoid,activo:1,icons1:"inverted circular search link icon",catCuentas:e.catcuentas})},function(e){validaError(e);t.setState({message:"Acreditado inexistente!",nombre:"",statusmessage:"ui negative floating message",icons1:"inverted circular search link icon",catCuentas:[],acreditadoid:0}),t.autoReset()})}},{key:"handleFind",value:function(e,t,a){"idacreditado"==a&&this.setState({idacredita:t,icons1:"spinner circular inverted blue loading icon"}),this.getCatCuentas(t)}},{key:"handleButton",value:function(e,t){if(0==e){this.setState({activo:0,idacreditado:"",nombre:"",idcredito:0,catCuentas:[],visible:!1,acreditadoid:0,idproducto:0,nombre1:"",nombre2:"",apaterno:"",amaterno:"",curp:"",idparentesco:""});$(".get.disper form").form("set values",{idparentesco:"",idproducto:0})}else if(2==e)0!=this.state.acreditadoid&&this.setState({visible:!0,numero_cuentanew:""});else if(3==e)this.setState({visible:!1});else{var a="";if(20==e){var n=this.state.idpagare;"0"==n&&""==n||(a=base_url+"api/ReportV1/edocta/"+n)}else{var r=this.state.idacreditado;""!=r&&10==e&&(a=base_url+"api/ReportV1/cuenta_acre/"+r)}if(""==a)return;var i=document.createElement("a");i.href=a,i.target="_blank",document.body.appendChild(i),i.click(),document.body.removeChild(i)}}},{key:"handleClickMessage",value:function(e){this.setState(function(e){return{message:"",statusmessage:"ui message hidden"}})}},{key:"handleSubmit",value:function(e){}},{key:"handleClickTable",value:function(e,t){}},{key:"handleSubmit",value:function(){event.preventDefault();var e=[],t=[],a=[],n=[],r=[],i=[];if("03"==this.state.idproducto&&(e=[{type:"empty",prompt:"Capture el Primer Nombre"},{type:"minLength[2]",prompt:"Minimo 2 caracteres"},{type:"maxLength[75]",prompt:"Longitu máxima de 75 caracteres"}],t=[{type:"minLength[2]",prompt:"Minimo 2 caracteres"},{type:"maxLength[75]",prompt:"Longitu máxima de 75 caracteres"}],a=[{type:"minLength[2]",prompt:"Minimo 2 caracteres"},{type:"maxLength[25]",prompt:"Longitu máxima de 25 caracteres"}],n=[{type:"minLength[2]",prompt:"Minimo 2 caracteres"},{type:"maxLength[25]",prompt:"Longitu máxima de 25 caracteres"}],r=[{type:"empty",prompt:"Teclee la CURP"},{type:"exactLength[18]",prompt:"Longitud de 18 caracteres"},{type:"regExp[/^[A-Za-z]{4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z]{6})([A-Za-z0-9]{1})([0-9]{1})$/]",prompt:"Formato incorrecto"}],i=[{type:"empty",prompt:"Seleccione parentesco"}]),$(".ui.form.formdis").form({inline:!0,on:"blur",fields:{numero_cuentanew:{identifier:"numero_cuentanew",rules:[{type:"empty",prompt:"Requiere un valor"}]},idproducto:{identifier:"idproducto",rules:[{type:"empty",prompt:"Requiere un valor"}]},idacreditado:{identifier:"idacreditado",rules:[{type:"empty",prompt:"Requiere un valor"}]},nombre1:{identifier:"nombre1",rules:e},nombre2:{identifier:"nombre2",optional:!0,rules:t},apaterno:{identifier:"apaterno",optional:!0,rules:a},amaterno:{identifier:"amaterno",optional:!0,rules:n},curp:{identifier:"curp",rules:r},idparentesco:{identifier:"idparentesco",rules:i}}}),$(".ui.form.formdis").form("validate form"),1==$(".ui.form.formdis").form("is valid")){var s=$(".get.disper form"),c=s.form("get values"),o=s.form("get value","csrf_bancomunidad_token"),l=this,u=this.state.acreditadoid;$(".mini.modal").modal({closable:!1,onApprove:function(){var e={url:base_url+"api/CarteraV1/add_cuentaho/"+u,type:"POST",dataType:"json",data:{csrf_bancomunidad_token:o,data:c}};ajax(e).then(function(e){l.setState({csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message "}),l.autoReset();var t=l.state.idacreditado;l.getCatCuentas(t),l.setState({visible:!1,numero_cuentanew:"",nombre1:"",nombre2:"",apaterno:"",amaterno:"",curp:"",idparentesco:""});$(".get.disper form").form("set values",{idparentesco:"",idproducto:""})},function(e){var t=validaError(e);l.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui negative floating message"}),l.autoReset()})}}).modal("show")}else this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset()}},{key:"autoReset",value:function(){var e=this;window.clearTimeout(e.timeout),this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},5e3)}},{key:"render",value:function(){var e=this;return React.createElement("div",null,React.createElement("div",{className:"ui segment vertical"},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Cuentas de ahorro")),React.createElement("div",{className:"ui secondary menu"},React.createElement("div",{className:"ui basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Nuevo Registro"},React.createElement("i",{className:"plus square outline icon",onClick:this.handleButton.bind(this,0)})),React.createElement("button",{className:"ui button","data-tooltip":"Formato PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,1)}))),React.createElement("div",{className:"right menu"},React.createElement("div",{className:"item ui fluid category search searchtext"},React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:"prompt mayuscula",type:"text",placeholder:"Buscar Nombre"}),React.createElement("i",{className:"search link icon"})),React.createElement("div",{className:"results"}))))),React.createElement(Mensaje,null),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(t){return e.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{className:"get disper"},React.createElement("form",{className:"ui form formdis",ref:"form",onSubmit:this.handleSubmit.bind(this),method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:1===this.state.activo?"disablediv":""},React.createElement("div",{className:"fields"},React.createElement(InputFieldFind,{icons:this.state.icons1,id:"idacreditado",cols:"three wide",mayuscula:"true",name:"idacreditado",valor:this.state.idacreditado,label:"Acreditado",placeholder:"Buscar",onChange:this.handleInputChange.bind(this),onClick:this.handleFind.bind(this)}),React.createElement(InputField,{id:"nombre",cols:"thirteen wide",label:"Nombre",readOnly:"readOnly",valor:this.state.nombre,onChange:this.handleInputChange.bind(this)}))),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"button",name:"action",onClick:this.handleButton.bind(this,2)},React.createElement("i",{className:"send icon"}),"Nuevo"))),React.createElement(Table,{datos:this.state.catCuentas,onClick:this.handleClickTable.bind(this)}),React.createElement("br",null),React.createElement("div",{className:!0===this.state.visible?"":"hidden"},React.createElement("div",{className:"four fields"},React.createElement(SelectOption,{id:"idproducto",label:"Producto",valor:this.state.idproducto,valores:this.state.catProductos,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"numero_cuentanew",readOnly:"readOnly",label:"Cuenta de Ahorro",valor:this.state.numero_cuentanew,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"03"==this.state.idproducto?"":"hidden"},React.createElement("div",{className:"four fields"},React.createElement(InputField,{id:"nombre1",mayuscula:"true",label:"Primer Nombre",valor:this.state.nombre1,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"nombre2",mayuscula:"true",label:"Segundo Nombre",valor:this.state.nombre2,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"apaterno",mayuscula:"true",label:"Apellido Paterno",valor:this.state.apaterno,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"amaterno",mayuscula:"true",label:"Apellido Materno",valor:this.state.amaterno,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"four fields"},React.createElement(InputField,{id:"curp",mayuscula:"true",label:"CURP",valor:this.state.curp,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectDropDown,{id:"idparentesco",label:"Parentesco",valor:this.state.idparentesco,valores:this.state.catparentesco,onChange:this.handleInputChange.bind(this)}))),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui submit bottom secondary basic button",type:"button",name:"cerrar",onClick:this.handleButton.bind(this,3)},React.createElement("i",{className:"send icon"}),"Cerrar"),React.createElement("button",{className:"ui submit bottom primary basic button",type:"submit",name:"action"},React.createElement("i",{className:"send icon"}),"Enviar")))))))}}]),t}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root")),$(".ui.search").search({type:"category",minCharacters:8,apiSettings:{url:base_url+"api/CarteraD1/find_acreditados?q={query}",onResponse:function(e){var t={results:{}};if(e&&e.result)return $.each(e.result,function(e,a){var n=a.idacreditado||"Sin asignar";if(e>=8)return!1;void 0===t.results[n]&&(t.results[n]={name:n,results:[]}),t.results[n].results.push({title:a.nombre,description:a.idcredito+" : "+a.idpagare})}),t}}});