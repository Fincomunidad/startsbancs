"use strict";var _createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,a,n){return a&&e(t.prototype,a),n&&e(t,n),t}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var Steps=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this;return React.createElement("a",{className:this.props.valor==this.props.value?"active step":"step",value:this.props.value,onClick:function(t,a){return e.props.onClick(t,a)}},React.createElement("i",{className:this.props.icon}),React.createElement("div",{className:"content"},React.createElement("div",{className:"title"},this.props.titulo),React.createElement("div",{className:"description"})))}}]),t}(),InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this,t=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:t},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,type:"text",readOnly:this.props.readOnly,value:this.props.valor,placeholder:this.props.placeholder,onChange:function(t){return e.props.onChange(t)}})))}}]),t}(),InputFieldFind=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this,t=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:t},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,type:"text",placeholder:this.props.placeholder,onChange:function(t){return e.setState({value:t.target.value})}}),React.createElement("i",{className:"inverted circular search link icon",onClick:function(t){return e.props.onClick(t,e.state.value)}})))}}]),t}(),InputFieldNumber=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this,t=(void 0!==this.props.cols?this.props.cols:"")+" field";return React.createElement("div",{className:t},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui labeled input"},React.createElement("div",{className:"ui label"},"$"),React.createElement("input",{type:"text",id:this.props.id,name:this.props.id,readOnly:this.props.readOnly,value:this.props.valor,onChange:function(t){return e.props.onChange(t)}})))}}]),t}(),Mensaje=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={icon:"send icon",titulo:"Guardar",pregunta:"Desea enviar el registro?"},a}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:"ui standard test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok  button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),t}(),Calendar=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.handleChange=a.handleChange.bind(a),a}return _inherits(t,React.Component),_createClass(t,[{key:"handleChange",value:function(e){console.log(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myCalen)).on("onChange",this.handleChange)}},{key:"render",value:function(){return React.createElement("div",{className:"ui calendar",id:this.props.name},React.createElement("div",{className:"field"},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui input left icon"},React.createElement("i",{className:"calendar icon"}),React.createElement("input",{ref:"myCalen",type:"text",name:this.props.name,id:this.props.name,value:this.props.valor,placeholder:"Fecha",onChange:this.handleChange.bind(this)}))))}}]),t}(),SelectDropDown=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a.handleSelectChange=a.handleSelectChange.bind(a),a}return _inherits(t,React.Component),_createClass(t,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.props.valor,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),t}(),SelectOption=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a}return _inherits(t,React.Component),_createClass(t,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myCombo)).on("change",this.handleSelectChange.bind(this))}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" inline field ",t=this.props.valores.map(function(e){return React.createElement("option",{value:e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{value:this.props.valor},"Seleccione"),t))}}]),t}();function Lista(e){var t=e.enca,a=0,n=t.map(function(e){return React.createElement("th",{key:a++},e)});return React.createElement("tr",null,n)}var RecordDetalle=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("tr",null,React.createElement("td",null,this.props.registro.idpoliza),React.createElement("td",null,this.props.registro.idmovimiento),React.createElement("td",null,this.props.registro.fecha),React.createElement("td",null,this.props.registro.tipo),React.createElement("td",null,this.props.registro.numero),React.createElement("td",null,this.props.registro.concepto),React.createElement("td",null,this.props.registro.referencia),React.createElement("td",null,this.props.registro.debe),React.createElement("td",null,this.props.registro.haber),React.createElement("td",null,this.props.registro.saldo))}}]),t}(),Table=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=[];this.props.datos.forEach(function(t){e.push(React.createElement(RecordDetalle,{registro:t}))});return React.createElement("div",null,React.createElement("table",{className:"ui selectable celled red table"},React.createElement("thead",null,React.createElement(Lista,{enca:["idpoliza","idmovimiento","Fecha","Tipo","Número","Concepto","Referencia","debe","haber","Saldo"]})),React.createElement("tbody",null,e)))}}]),t}(),Captura=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={idsucursal:"01",idcuenta:"",cataux:[],auxiliar:[],csrf:"",message:"",statusmessage:"ui floating hidden message",btnMostrar:"Mostrar"},a}return _inherits(t,React.Component),_createClass(t,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),$.ajax({url:base_url+"/api/ContableD1/get_auxiliar_init/",type:"GET",dataType:"json",success:function(e){this.setState({cataux:e.cataux})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleInputChange",value:function(e){var t=e.target,a="checkbox"===t.type?t.checked:t.value,n=t.name;if(this.setState(_defineProperty({},n,a)),"ejercicio"===n);}},{key:"handleSubmit",value:function(e){e.preventDefault(),$(".ui.form.formgen").form("validate form");$(".ui.form.formgen").form("is valid")}},{key:"handleDisplayCuentas",value:function(e){var t=$(".get.balanza form"),a=t.form("get values");t.form("get value","csrf_bancomunidad_token");$.ajax({url:base_url+"api/ContableD1/get_auxiliar/"+this.state.idcuenta,type:"GET",dataType:"json",data:{data:a},success:function(e){"OK"===e.status&&this.setState({auxiliar:e.auxiliar,message:e.message,statusmessage:"ui positive floating message "})}.bind(this),error:function(e,t,a){if(404===e.status)this.setState({message:e.responseJSON.message,statusmessage:"ui negative floating message"});else if(409===e.status){var n="",r=e.responseText.indexOf('{"status"');0!==r&&(n=e.responseText.substring(r));var s=JSON.parse(n);this.setState({message:s.message,statusmessage:"ui negative floating message"})}}.bind(this)})}},{key:"render",value:function(){var e=this;return React.createElement("div",null,React.createElement(Mensaje,null),React.createElement("div",{className:"get balanza"},React.createElement("form",{className:"ui form formgen",ref:"form",onSubmit:this.handleSubmit.bind(this),method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"three fields"},React.createElement(SelectDropDown,{id:"idcuenta",label:"Cuenta:",valor:this.state.idcuenta,valores:this.state.cataux,onChange:this.handleInputChange.bind(this)}),React.createElement("button",{className:"ui right labeled icon positive basic button",onClick:this.handleDisplayCuentas.bind(this)},"Mostrar auxiliar ",React.createElement("i",{className:"right chevron icon"})," ")),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(t){return e.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",null,React.createElement(Table,{datos:this.state.auxiliar})))))}}]),t}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));