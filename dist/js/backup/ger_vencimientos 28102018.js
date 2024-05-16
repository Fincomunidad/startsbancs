"use strict";var _createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var r=t[a];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(t,a,r){return a&&e(t.prototype,a),r&&e(t,r),t}}();function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var SelectOption=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a}return _inherits(t,React.Component),_createClass(t,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("option",{value:e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{value:this.props.valor},"Seleccione"),t))}}]),t}(),Mensaje=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={icon:"check circle outline icon",titulo:"Autorizar",pregunta:"¿Desea autorizar la reversa del registro?"},a}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:"ui mini test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok basic button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),t}();function Lista(e){var t=e.enca,a=0,r=t.map(function(e){return React.createElement("th",{className:"two wide",key:a++},e)});return React.createElement("tr",null,r)}var RecordDetalle=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){"f"==this.props.registro.autoriza||this.props.registro.autoriza;var e={};return"Por ingresar"==this.props.registro.estatus&&(e={color:"red"}),React.createElement("tr",null,React.createElement("td",null,this.props.registro.idinversion),React.createElement("td",null,this.props.registro.nosocio),React.createElement("td",null,this.props.registro.nombre),React.createElement("td",null,this.props.registro.numero),React.createElement("td",null,this.props.registro.fecha),React.createElement("td",null,this.props.registro.fechafin),React.createElement("td",null,this.props.registro.tasa),React.createElement("td",{className:"ui center aligned"},this.props.registro.dias),React.createElement("td",{className:"ui right aligned"},numeral(this.props.registro.total).format("0,0.00")),React.createElement("td",{style:e},this.props.registro.estatus))}}]),t}(),RecordCredito=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){"f"==this.props.registro.autoriza||this.props.registro.autoriza;return React.createElement("tr",null,React.createElement("td",null,this.props.registro.idcredito),React.createElement("td",null,this.props.registro.idacreditado),React.createElement("td",null,this.props.registro.idpagare),React.createElement("td",null,this.props.registro.acreditado),React.createElement("td",{className:"ui right aligned"},numeral(this.props.registro.monto).format("0,0.00")),React.createElement("td",null,this.props.registro.fecha_primerpago),React.createElement("td",null,this.props.registro.fecha_aprov),React.createElement("td",null,this.props.registro.fecha_dispersa),React.createElement("td",null,this.props.registro.fecha_entrega),React.createElement("td",null,this.props.registro.fecha_vence1),React.createElement("td",{style:{color:"red"}},this.props.registro.estatus))}}]),t}(),Table=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={grantotal:a.props.totalxpagar},a}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=[],t=this.props.datos;t instanceof Array==!0&&t.forEach(function(t){"inversion"==this.props.name?e.push(React.createElement(RecordDetalle,{registro:t})):e.push(React.createElement(RecordCredito,{registro:t}))}.bind(this));var a=[];return a="inversion"==this.props.name?["Id","Acreditada","Nombre","No.","Apertura","Vencimiento","Tasa","Dias x Vencer","Total","Estatus"]:["Id","Acreditada","Pagaré","Nombre","Monto","Primer Pago","Fec. Aprobación","Fec. Dispersa","Fec. Entrega","Fec. Vence","Estatus"],React.createElement("div",{className:"ui grid"},React.createElement("div",{className:"wide column"},React.createElement("table",{className:"ui selectable celled blue table"},React.createElement("thead",null,React.createElement(Lista,{enca:a})),React.createElement("tbody",null,e))))}}]),t}(),Captura=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={csrf:"",message:"",statusmessage:"hidden",catCreditos:[],catInversion:[],filtro:0},a}return _inherits(t,React.Component),_createClass(t,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),this.obtenerData(0)}},{key:"obtenerData",value:function(e){$.ajax({url:base_url+"/api/CarteraV1/getVencimientos/"+e,type:"GET",dataType:"json",success:function(e){console.log(e),this.setState({catInversion:e.inver,catCreditos:e.credito})}.bind(this),error:function(e,t,a){}.bind(this)})}},{key:"handleInputChange",value:function(e){this.setState({filtro:e.target.value});var t=e.target.value;this.obtenerData(t)}},{key:"handleButton",value:function(e,t){4==e&&this.findMov()}},{key:"findMov",value:function(){this.obtenerData()}},{key:"autoReset",value:function(){var e=this;window.clearTimeout(e.timeout),this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},5e3)}},{key:"render",value:function(){var e=this;this.state.statusmessage;return React.createElement("div",null,React.createElement("div",{className:"ui segment vertical "},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Vencimientos")),React.createElement("div",{className:"ui  basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Formato PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,1)})),React.createElement("button",{className:"ui button","data-tooltip":"Actualizar"},React.createElement("i",{className:"refresh icon",onClick:this.handleButton.bind(this,4)})))),React.createElement(Mensaje,null),React.createElement("div",{className:"thirteen wide column"},React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(t){return e.setState({message:"",statusmessage:"ui message hidden"})}}))),React.createElement("h4",null,"Créditos"),React.createElement("form",{className:"ui form",action:""},React.createElement("div",{className:"fields"},React.createElement(SelectOption,{id:"filter",cols:"three wide",label:"Filtro créditos:",valor:this.state.filtro,valores:[{name:"Por Aprobar",value:"0"},{name:"Por Dispersar",value:"1"},{name:"Por Entregar",value:"2"},{name:"Todos los Anteriores",value:"3"},{name:"Por Pagar",value:"4"},{name:"Amortización Pagada",value:"5"},{name:"Las dos anteriores",value:"6"},{name:"Crédito Liquidado",value:"7"}],onChange:this.handleInputChange.bind(this)})),React.createElement("div",null,React.createElement(Table,{name:"creditos",datos:this.state.catCreditos})),React.createElement("h4",null,"Inversiones"),React.createElement("div",null,React.createElement(Table,{name:"inversion",datos:this.state.catInversion}))))}}]),t}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));