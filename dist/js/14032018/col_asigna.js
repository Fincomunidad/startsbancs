"use strict";var _createClass=function(){function e(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(t,a,n){return a&&e(t.prototype,a),n&&e(t,n),t}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this,t=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:t},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,type:"text",readOnly:this.props.readOnly,value:this.props.valor,placeholder:this.props.placeholder,onChange:function(t){return e.props.onChange(t)}})))}}]),t}(),ListaRow=function(e){function t(){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:"ui vertical segment aligned"},React.createElement("div",{className:"field"},this.props.categoria))}}]),t}(),DocumentoRow=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e)),n="iddoc_"+a.props.valor.idacreditado;return a.state=_defineProperty({},n,!1),a}return _inherits(t,React.Component),_createClass(t,[{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myCheck)).on("onChange",this.handleClick);var e="iddoc_"+this.props.valor.idacreditado;$(".get.soling form").form("set values",_defineProperty({},e,!1))}},{key:"handleClick",value:function(e){}},{key:"render",value:function(){this.props.valor.idacreditado;return React.createElement("tr",null,React.createElement("td",null,this.props.valor.idacreditado),React.createElement("td",null," - "),React.createElement("td",null,this.props.valor.nombre),React.createElement("td",null," - "),React.createElement("td",null,this.props.valor.idanterior))}}]),t}(),SelectDropDown=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={value:""},a.handleSelectChange=a.handleSelectChange.bind(a),a}return _inherits(t,React.Component),_createClass(t,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.props.valor,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),t}(),Catalogo=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=[],t=null;return this.props.valores.forEach(function(a){a.grupo_nombre!==t&&e.push(React.createElement(ListaRow,{categoria:a.grupo_nombre,key:a.grupo_nombre})),e.push(React.createElement(DocumentoRow,{valor:a,key:a.nombre})),t=a.grupo_nombre}),React.createElement("div",{className:"ui segment"},e)}}]),t}(),InputFieldFind=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=this,t=(void 0!==this.props.cols?this.props.cols:"")+" field ",a="true"==this.props.mayuscula?"mayuscula":"";return React.createElement("div",{className:t},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:a,id:this.props.id,name:this.props.id,value:this.props.valor,type:"text",placeholder:this.props.placeholder,onChange:function(t){return e.props.onChange(t)}}),React.createElement("i",{className:this.props.icons,onClick:function(t){return e.props.onClick(t,e.props.valor,e.props.name)}})))}}]),t}();function Lista(e){var t=e.enca,a=0,n=t.map(function(e){return React.createElement("th",{key:a++},e)});return React.createElement("tr",null,n)}var RecordDetalle=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.handleDeleteRecord=a.handleDeleteRecord.bind(a),a}return _inherits(t,React.Component),_createClass(t,[{key:"cargarGrupoAcreditados",value:function(e){var t="";t=(t="get_onlyacreditadosbygrupo")+"/"+e;var a=this;$.ajax({url:base_url+"/api/CarteraD1/"+t,type:"GET",dataType:"json",success:function(e){a.setState({grupo_acreditados:e.grupo_acreditados})}.bind(a),error:function(e,t,a){console.log("error")}.bind(a)})}},{key:"handleDeleteRecord",value:function(){var e=this.props.registro.acreditadoid,t=this.props.registro.idgrupo,a=this;$(".test.modal").modal({closable:!1,onApprove:function(){$.ajax({url:base_url+"/api/CarteraD1/delete_acreditado_grupo/"+e,type:"PUT",dataType:"json",success:function(e){"OK"===e.status&&a.cargarGrupoAcreditados(t)}.bind(this),error:function(e,t,n){console.log("error"),a.setState({message:"No se ha encontrado información de la colmena",statusmessage:"ui negative floating message",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),a.autoReset()}.bind(this)})}}).modal("show")}},{key:"render",value:function(){return React.createElement("tr",null,React.createElement("td",null,this.props.registro.acreditadoid),React.createElement("td",null,this.props.registro.idacreditado),React.createElement("td",null,this.props.registro.nombre),React.createElement("td",null,this.props.registro.cargo_colmena),React.createElement("td",null,this.props.registro.cargo_grupo),React.createElement("td",null,this.props.registro.orden),React.createElement("td",{className:" center aligned"},React.createElement("a",{"data-tooltip":"Eliminar asignación de acreditada"},React.createElement("i",{className:"trash outline icon circular red",onClick:this.handleDeleteRecord}))))}}]),t}(),Table=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var e=[],t=this.props.datos;this.props.onClickPage;t.forEach(function(t){e.push(React.createElement(RecordDetalle,{registro:t}))});return React.createElement("div",null,React.createElement("div",{className:"ui standard test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:"trash outline icon"})," Eliminar"),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,"La asignación seleccionada?")),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok  basic button"},React.createElement("i",{className:"checkmark icon"})," Si "))),React.createElement("table",{className:"ui selectable celled red table"},React.createElement("thead",null,React.createElement(Lista,{enca:["Id","IdAcreditado","Nombre","Colmena","Grupo","Posición"]})),React.createElement("tbody",null,e)))}}]),t}(),Captura=function(e){function t(e){_classCallCheck(this,t);var a=_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e));return a.state={idcolmena:"",catcolmenas:[],idcolcargo:"",cat_col_cargos:[],idgrupocargo:"",cat_grupo_cargos:[],idgrupoorden:"",cat_grupo_orden:[],idcolpresidenta:"",idcolsecretaria:"",idcoltesorera:"",idgrupo:"",cat_grupos:[],cat_asigna:[],grupo_acreditados:[],idacreditado:"",cat_noasigna:[],botonUpdate:"Actualizar",boton:"Enviar",message:"",statusmessage:"ui floating hidden message",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"},a.handleSubmit=a.handleSubmit.bind(a),a}return _inherits(t,React.Component),_createClass(t,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),$.ajax({url:base_url+"/api/GeneralD1/get_colmenas_asigna",type:"GET",dataType:"json",success:function(e){this.setState({catcolmenas:e.catcolmenas,cat_noasigna:e.cat_noasigna,cat_col_cargos:e.cat_col_cargos,cat_grupo_cargos:e.cat_grupo_cargos,cat_grupo_orden:e.cat_grupo_orden})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleInputChange",value:function(e){var t=e.target,a="checkbox"===t.type?t.checked:t.value,n=t.name;this.setState(_defineProperty({},n,a));var r=this;if("idcolmena"===n&&""!=e.target.value){var o="";o=(o="get_grupo_acreditados")+"/"+e.target.value,$.ajax({url:base_url+"/api/CarteraD1/"+o,type:"GET",dataType:"json",success:function(e){r.setState({message:e.message,statusmessage:"ui positive floating message ",cat_grupos:e.cat_grupos,cat_asigna:e.cat_asigna,icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),r.autoReset()}.bind(this),error:function(e,t,a){console.log("error"),r.setState({message:"No se ha encontrado información de la colmena",statusmessage:"ui negative floating message",cat_grupos:[],icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),r.autoReset()}.bind(this)})}if("idgrupo"===n&&""!=e.target.value){var s="";s=(s="get_acreditadosbygrupo")+"/"+e.target.value,$.ajax({url:base_url+"/api/CarteraD1/"+s,type:"GET",dataType:"json",success:function(e){r.setState({message:e.message,statusmessage:"ui positive floating message ",grupo_acreditados:e.grupo_acreditados,icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),r.autoReset()}.bind(this),error:function(e,t,a){console.log("error"),r.setState({message:"No se ha encontrado información de la colmena",statusmessage:"ui negative floating message",grupo_acreditados:[],icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),r.autoReset()}.bind(this)})}if("idacreditado"===n)$(".get.soling form").form("set values",{idgrupocargo:"0",idcolcargo:"0",idgrupoorden:"1"});this.setState(_defineProperty({},n,a))}},{key:"asignacionNew",value:function(){this.setState({blnActivar:!0,idcolmena:"",idgrupo:"",cat_grupos:[],idacreditado:"",grupo_acreditados:[],boton:"Enviar"});$(".get.soling form").form("set values",{idcolmena:""});$(".get.soling .ui.dropdown").dropdown("clear")}},{key:"handleButton",value:function(e,t){if(e<2){this.asignacionNew();$(".get.soling form").form("set values",{idcolmena:""})}}},{key:"cargarGrupoAcreditados",value:function(e){var t="";t=(t="get_onlyacreditadosbygrupo")+"/"+e;var a=this;$.ajax({url:base_url+"/api/CarteraD1/"+t,type:"GET",dataType:"json",success:function(e){a.setState({grupo_acreditados:e.grupo_acreditados})}.bind(a),error:function(e,t,a){console.log("error")}.bind(a)})}},{key:"handleSubmitUpdate",value:function(e){e.preventDefault();cargarGrupoAcreditados(this.state.idgrupo)}},{key:"handleSubmit",value:function(e){e.preventDefault();var t=$(".get.soling form"),a=t.form("get values"),n=t.form("get value","csrf_bancomunidad_token"),r=this;$.ajax({url:base_url+"api/CarteraD1/update_acreditado_grupo2",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:n,data:a},success:function(e){if("OK"===e.status){r.setState({message:e.message,statusmessage:"ui positive floating message ",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon",csrf:e.newtoken}),this.cargarGrupoAcreditados(this.state.idgrupo),r.autoReset();$(".get.soling form").form("set values",{idgrupocargo:"0",idcolcargo:"0",idgrupoorden:"1"})}}.bind(this),error:function(e,t,a){if(404===e.status)r.setState({csrf:e.responseJSON.newtoken,message:e.responseJSON.message,statusmessage:"ui negative floating message"});else if(409===e.status){var n="",o=e.responseText.indexOf('{"status"');0!==o&&(n=e.responseText.substring(o));var s=JSON.parse(n);r.setState({csrf:s.newtoken,message:s.message,statusmessage:"ui negative floating message"})}}.bind(this)})}},{key:"autoReset",value:function(){var e=this;this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},3e3)}},{key:"render",value:function(){var e=this;return React.createElement("div",null,React.createElement("div",{className:"ui segment vertical "},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Colmenas")),React.createElement("div",{className:"ui  basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Nueva asignacion"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,1)})),React.createElement("button",{className:"ui button","data-tooltip":"Formato PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,2)})))),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(t){window.clearTimeout(e.timeout),e.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{className:"get soling"},React.createElement("form",{className:"ui form formgen",ref:"form",onSubmit:this.handleSubmit,method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{id:"idcolmena",label:"Colmena",valor:this.state.idcolmena,valores:this.state.catcolmenas,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectDropDown,{id:"idgrupo",label:"Grupo",valor:this.state.idgrupo,valores:this.state.cat_grupos,onChange:this.handleInputChange.bind(this)}),React.createElement("br",null),React.createElement("br",null)),React.createElement("div",{className:""==this.state.idgrupo?"step hidden":""},React.createElement("div",{className:"field"},React.createElement(SelectDropDown,{id:"idacreditado",label:"Acreditado",valor:this.state.idacreditado,valores:this.state.cat_noasigna,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"three fields"},React.createElement(SelectDropDown,{id:"idcolcargo",label:"Cargo en colmena",valor:this.state.idcolcargo,valores:this.state.cat_col_cargos,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectDropDown,{id:"idgrupocargo",label:"Cargo en grupo",valor:this.state.idgrupocargo,valores:this.state.cat_grupo_cargos,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectDropDown,{id:"idgrupoorden",label:"Posición en grupo",valor:this.state.idgrupoorden,valores:this.state.cat_grupo_orden,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"submit",name:"action"},React.createElement("i",{className:"send icon"})," ",this.state.boton," ")))),React.createElement(Table,{datos:this.state.grupo_acreditados})),React.createElement("form",{className:"ui form formtabla",ref:"form",onSubmit:this.handleSubmitUpdate,method:"post"},React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"submit",name:"action",onSubmit:this.handleSubmitUpdate},React.createElement("i",{className:"send icon"})," ",this.state.botonUpdate," "))))))}}]),t}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));