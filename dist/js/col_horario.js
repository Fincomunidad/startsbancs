"use strict";var _createClass=function(){function n(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(e,t,a){return t&&n(e.prototype,t),a&&n(e,a),e}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var SelectDropDown=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t.handleSelectChange=t.handleSelectChange.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.props.valor,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),a}(),Mensaje=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={icon:"send icon",titulo:"Guardar",pregunta:"¿Desea enviar el registro?"},t}return _inherits(a,React.Component),_createClass(a,[{key:"render",value:function(){return React.createElement("div",{className:"ui mini test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok basic button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),a}(),Captura=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={esquema:"",idpromotor:"",catpromotores:[],boton:"Enviar",message:"",statusmessage:"ui floating hidden message",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"},t}return _inherits(a,React.Component),_createClass(a,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),$.ajax({url:base_url+"/api/GeneralD1/get_promotor/1",type:"GET",dataType:"json",success:function(e){this.setState({catpromotores:e.catpromotores,idpromotor:0,esquema:e.esquema})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleInputChange",value:function(e){var t=e.target,a="checkbox"===t.type?t.checked:t.value,n=t.name;this.setState(_defineProperty({},n,a));this.setState(_defineProperty({},n,a))}},{key:"handleButton",value:function(e,t){if(10===e)(a=document.createElement("a")).href=base_url+"api/ReportD1/pdf_col_horario/"+this.state.idpromotor,a.target="_blank",document.body.appendChild(a),a.click(),document.body.removeChild(a);else if(20===e){(a=document.createElement("a")).href=base_url+"api/ReportD1/pdf_col_horariog/"+this.state.idpromotor,a.target="_blank",document.body.appendChild(a),a.click(),document.body.removeChild(a)}else if(30===e){var a;(a=document.createElement("a")).href=base_url+"api/ReportD1/pdf_col_horariog2/"+this.state.idpromotor,a.target="_blank",document.body.appendChild(a),a.click(),document.body.removeChild(a)}}},{key:"handleSubmit",value:function(e){}},{key:"autoReset",value:function(){var e=this;this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},3e3)}},{key:"render",value:function(){var t=this;return React.createElement("div",null,React.createElement("div",{className:"ui segment vertical "},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Horario de colmenas")),React.createElement("div",{className:"ui  basic icon buttons"},React.createElement("button",{className:"ui compact labeled icon button","data-tooltip":"Horario por sucursal",onClick:this.handleButton.bind(this,10)},React.createElement("i",{className:"file pdf outline icon"}),"Horario por sucursal "),React.createElement("div",{className:"ama."===this.state.esquema?"":"step hidden"},React.createElement("button",{className:"ui compact labeled icon button","data-tooltip":"Horario global por sucursal",onClick:this.handleButton.bind(this,20)},React.createElement("i",{className:"file pdf outline icon"}),"Horario global por sucursal "),React.createElement("button",{className:"ui compact labeled icon button","data-tooltip":"Horario global",onClick:this.handleButton.bind(this,30)},React.createElement("i",{className:"file pdf outline icon"}),"Horario global ")))),React.createElement(Mensaje,null),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(e){window.clearTimeout(t.timeout),t.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{className:"get colhora"},React.createElement("form",{className:"ui form formgen",ref:"form",onSubmit:this.handleSubmit,method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"field"},React.createElement(SelectDropDown,{id:"idpromotor",label:"Seleccione al promotor",valor:this.state.idpromotor,valores:this.state.catpromotores,onChange:this.handleInputChange.bind(this)})))))}}]),a}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));