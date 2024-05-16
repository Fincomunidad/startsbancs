"use strict";var _createClass=function(){function n(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(e,t,a){return t&&n(e.prototype,t),a&&n(e,a),e}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var Steps=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var a=this;return React.createElement("a",{className:this.props.valor==this.props.value?"active step":"step",value:this.props.value,onClick:function(e,t){return a.props.onClick(e,t)}},React.createElement("i",{className:this.props.icon}),React.createElement("div",{className:"content"},React.createElement("div",{className:"title"},this.props.titulo),React.createElement("div",{className:"description"})))}}]),t}(),InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,type:"text",readOnly:this.props.readOnly,value:this.props.valor,placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}})))}}]),t}(),InputFieldFind=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t}return _inherits(a,React.Component),_createClass(a,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,type:"text",placeholder:this.props.placeholder,onChange:function(e){return t.setState({value:e.target.value})}}),React.createElement("i",{className:"inverted circular search link icon",onClick:function(e){return t.props.onClick(e,t.state.value)}})))}}]),a}(),InputFieldNumber=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui labeled input"},React.createElement("div",{className:"ui label"},"$"),React.createElement("input",{type:"text",id:this.props.id,name:this.props.id,readOnly:this.props.readOnly,value:this.props.valor,onChange:function(e){return t.props.onChange(e)}})))}}]),t}(),Mensaje=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={icon:"send icon",titulo:"Guardar",pregunta:"Desea enviar el registro?"},t}return _inherits(a,React.Component),_createClass(a,[{key:"render",value:function(){return React.createElement("div",{className:"ui standard test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok  button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),a}(),SelectDropDown=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t.handleSelectChange=t.handleSelectChange.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.props.valor,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),a}(),SelectOption=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myCombo)).on("change",this.handleSelectChange.bind(this))}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" inline field ",t=this.props.valores.map(function(e){return React.createElement("option",{value:e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{value:this.props.valor},"Seleccione"),t))}}]),a}();function Lista(e){var t=e.enca,a=0,n=t.map(function(e){return React.createElement("th",{key:a++},e)});return React.createElement("tr",null,n)}var RecordDetalle=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.handleEditRecord=t.handleEditRecord.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleEditRecord",value:function(){var e=this.props.registro.idacreditado;this.setState({editar:e}),alert("Editar:"+e)}},{key:"render",value:function(){return React.createElement("tr",null,React.createElement("td",null,this.props.registro.idacreditado),React.createElement("td",null,this.props.registro.nombre),React.createElement("td",null,this.props.registro.telefono),React.createElement("td",null,this.props.registro.celular_nota),React.createElement("td",null,this.props.registro.col_numero),React.createElement("td",{className:" center aligned"},React.createElement("a",{"data-tooltip":"Editar"},React.createElement("i",{className:"edit icon circular green",onClick:this.handleEditRecord}))))}}]),a}(),Table=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=[];this.props.datos.forEach(function(e){t.push(React.createElement(RecordDetalle,{registro:e}))});return React.createElement("div",null,React.createElement("table",{className:"ui selectable celled red table"},React.createElement("thead",null,React.createElement(Lista,{enca:["Número","Nombre","Teléfono","Observación","Colmena","Acción"]})),React.createElement("tbody",null,t)))}}]),t}(),Captura=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={directorio:[],opcion:1,opciones:[{value:1,name:"No Socia"},{value:2,name:"Nombre"},{value:3,name:"Colmena"}],txt_buscar:"",id_buscar:0,col_buscar:0,editar:0,telefono:"",observa:"",csrf:"",message:"",statusmessage:"ui floating hidden message",btnMostrar:"Mostrar"},t}return _inherits(a,React.Component),_createClass(a,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e})}},{key:"handleSubmit",value:function(e){e.preventDefault(),$(".ui.form.formgen").form("validate form");$(".ui.form.formgen").form("is valid")}},{key:"handleDisplayCuentas",value:function(e,t){$(".ui.form.formgen").form("validate form");var a=$(".ui.form.formgen").form("is valid");if(this.setState({message:"",statusmessage:"ui message hidden"}),1==a){var n=$(".get.soling form"),s=n.form("get values"),i=n.form("get value","csrf_bancomunidad_token"),r=this,c="";1===r.state.opcion&&(c="api/GeneralD1/get_directorio/1/"+r.state.id_buscar),2===r.state.opcion&&(c="api/GeneralD1/get_directorio/2/"+r.state.txt_buscar),3===r.state.opcion&&(c="api/GeneralD1/get_directorio/3/"+r.state.col_buscar);var o={url:base_url+c,type:"GET",dataType:"json",data:{csrf_bancomunidad_token:i,data:s}};ajax(o).then(function(e){r.setState({csrf_bancomunidad_token:i,directorio:e.directorio,message:e.message,statusmessage:"ui positive floating message "}),r.autoReset()},function(e){if(console.log("No Entro 1"),"OK"===e.statusText){var t="",a=e.responseText.indexOf('{"status"');0!==a&&(t=e.responseText.substring(a));JSON.parse(t);r.setState({csrf_bancomunidad_token:i,directorio:response.directorio,message:response.message,statusmessage:"ui positive floating message "})}else{console.log("ENTRO REJECT 1");var n=validaError(e);r.setState({csrf:n.newtoken,message:n.message,statusmessage:"ui negative floating message"})}r.autoReset()})}else this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset();console.log("Salio")}},{key:"handleOptionState",value:function(e,t){this.setState({opcion:e})}},{key:"handleInputChange",value:function(e){var t=e.target,a="checkbox"===t.type?t.checked:t.value,n=t.name;if("opcion"===n){var s=parseInt(a);this.setState({opcion:s})}else this.setState(_defineProperty({},n,a))}},{key:"autoReset",value:function(){var e=this;window.clearTimeout(e.timeout),""!=e.state.message&&(this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},2e3))}},{key:"render",value:function(){var t=this;return React.createElement("div",null,React.createElement(Mensaje,null),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(e){return t.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{className:"get soling"},React.createElement("form",{className:"ui form formgen",ref:"form",onSubmit:this.handleSubmit.bind(this),method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"three fields"},React.createElement(SelectOption,{name:"opcion",id:"opcion",label:"Filtrar por",valor:this.state.opcion,valores:this.state.opciones,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:1===this.state.opcion?"ui blue segment":"ui blue segment step hidden"},React.createElement("div",{className:"field"},React.createElement(InputFieldNumber,{id:"id_buscar",cols:"three wide",label:"No socia",valor:this.state.id_buscar,onChange:this.handleInputChange.bind(this)}),React.createElement("button",{className:"ui right labeled icon positive basic button",onClick:this.handleDisplayCuentas.bind(this,1)},"Mostrar ",React.createElement("i",{className:"right chevron icon"})))),React.createElement("div",{className:2===this.state.opcion?"ui blue segment":"ui blue segment step hidden"},React.createElement("div",{className:"field"},React.createElement(InputField,{id:"txt_buscar",label:"Nombre",valor:this.state.txt_buscar,onChange:this.handleInputChange.bind(this)}),React.createElement("button",{className:"ui right labeled icon positive basic button",onClick:this.handleDisplayCuentas.bind(this,2)},"Mostrar ",React.createElement("i",{className:"right chevron icon"})))),React.createElement("div",{className:3===this.state.opcion?"ui blue segment":"ui blue segment step hidden"},React.createElement("div",{className:"field"},React.createElement(InputFieldNumber,{id:"col_buscar",cols:"three wide",label:"No colmena",valor:this.state.col_buscar,onChange:this.handleInputChange.bind(this)}),React.createElement("button",{className:"ui right labeled icon positive basic button",onClick:this.handleDisplayCuentas.bind(this,3)},"Mostrar ",React.createElement("i",{className:"right chevron icon"})))),React.createElement("div",{className:0===this.state.editar?"ui blue segment step hidden":"ui blue segment"},React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"telefono",mayuscula:"true",label:"Teléfono",valor:this.state.telefono,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"observa",mayuscula:"true",label:"Observación",valor:this.state.observa,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"field"},React.createElement("button",{className:"ui right labeled icon positive basic button",onClick:this.handleDisplayCuentas.bind(this,3)},"Mostrar ",React.createElement("i",{className:"right chevron icon"})))),React.createElement("div",null,React.createElement(Table,{datos:this.state.directorio})))))}}]),a}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));