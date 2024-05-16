"use strict";var _createClass=function(){function r(e,t){for(var a=0;a<t.length;a++){var r=t[a];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}return function(e,t,a){return t&&r(e.prototype,t),a&&r(e,a),e}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,type:"text",readOnly:this.props.readOnly,value:this.props.valor,placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}})))}}]),t}(),ListaRow=function(e){function t(){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:"ui vertical segment aligned"},React.createElement("div",{className:"field"},this.props.categoria))}}]),t}(),DocumentoRow=function(e){function r(e){_classCallCheck(this,r);var t=_possibleConstructorReturn(this,(r.__proto__||Object.getPrototypeOf(r)).call(this,e)),a="iddoc_"+t.props.valor.idacreditado;return t.state=_defineProperty({},a,!1),t}return _inherits(r,React.Component),_createClass(r,[{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myCheck)).on("onChange",this.handleClick);var e="iddoc_"+this.props.valor.idacreditado;$(".get.soling form").form("set values",_defineProperty({},e,!1))}},{key:"handleClick",value:function(e){}},{key:"render",value:function(){this.props.valor.idacreditado;return React.createElement("tr",null,React.createElement("td",null,this.props.valor.idacreditado),React.createElement("td",null," - "),React.createElement("td",null,this.props.valor.nombre),React.createElement("td",null," - "),React.createElement("td",null,this.props.valor.idanterior))}}]),r}(),SelectDropDown=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t.handleSelectChange=t.handleSelectChange.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.props.valor,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),a}(),Catalogo=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=[],a=null;return this.props.valores.forEach(function(e){e.grupo_nombre!==a&&t.push(React.createElement(ListaRow,{categoria:e.grupo_nombre,key:e.grupo_nombre})),t.push(React.createElement(DocumentoRow,{valor:e,key:e.nombre})),a=e.grupo_nombre}),React.createElement("div",{className:"ui segment"},t)}}]),t}(),InputFieldFind=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ",a="true"==this.props.mayuscula?"mayuscula":"";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:a,id:this.props.id,name:this.props.id,value:this.props.valor,type:"text",placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}}),React.createElement("i",{className:this.props.icons,onClick:function(e){return t.props.onClick(e,t.props.valor,t.props.name)}})))}}]),t}();function Lista(e){var t=e.enca,a=0,r=t.map(function(e){return React.createElement("th",{key:a++},e)});return React.createElement("tr",null,r)}var RecordDetalle=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.handleDeleteRecord=t.handleDeleteRecord.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"cargarGrupoAcreditados",value:function(e){var t="";t=(t="get_onlyacreditadosbygrupo")+"/"+e;var a=this;$.ajax({url:base_url+"/api/CarteraD1/"+t,type:"GET",dataType:"json",success:function(e){a.setState({grupo_acreditados:e.grupo_acreditados,grupo_tesoreros:e.grupo_tesoreros})}.bind(a),error:function(e,t,a){console.log("error")}.bind(a)})}},{key:"handleDeleteRecord",value:function(){var e=this.props.registro.acreditadoid,t=this.props.registro.idgrupo,r=this;$(".test.modal").modal({closable:!1,onApprove:function(){$.ajax({url:base_url+"/api/CarteraD1/delete_acreditado_grupo/"+e,type:"PUT",dataType:"json",success:function(e){"OK"===e.status&&r.cargarGrupoAcreditados(t)}.bind(this),error:function(e,t,a){console.log("error"),r.setState({message:"No se ha encontrado información de la colmena",statusmessage:"ui negative floating message",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),r.autoReset()}.bind(this)})}}).modal("show")}},{key:"render",value:function(){return React.createElement("tr",null,React.createElement("td",null,this.props.registro.acreditadoid),React.createElement("td",null,this.props.registro.idacreditado),React.createElement("td",null,this.props.registro.nombre),React.createElement("td",null,this.props.registro.cargo_colmena),React.createElement("td",null,this.props.registro.cargo_grupo),React.createElement("td",null,this.props.registro.orden),React.createElement("td",{className:" center aligned"},React.createElement("a",{"data-tooltip":"Eliminar asignación de acreditada"},React.createElement("i",{className:"trash outline icon circular red",onClick:this.handleDeleteRecord}))))}}]),a}(),Table=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=[],e=this.props.datos;this.props.onClickPage;e.forEach(function(e){t.push(React.createElement(RecordDetalle,{registro:e}))});return React.createElement("div",null,React.createElement("div",{className:"ui standard test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:"trash outline icon"})," Eliminar"),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,"La asignación seleccionada?")),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok  basic button"},React.createElement("i",{className:"checkmark icon"})," Si "))),React.createElement("table",{className:"ui selectable celled red table"},React.createElement("thead",null,React.createElement(Lista,{enca:["Id","IdAcreditado","Nombre","Colmena","Grupo","Posición"]})),React.createElement("tbody",null,t)))}}]),t}(),Captura=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={idcolmena:"",catcolmenas:[],idcol_presi:"",col_presidenta:"",idcol_secre:"",col_secretaria:"",idcol_mab:"",idgrupo_presi:"",grupo_presi:"",idgrupo_teso:"",grupo_teso:"",idcolcargo:"",cat_col_cargos:[],idgrupocargo:"",cat_grupo_cargos:[],idgrupoorden:"",cat_grupo_orden:[],idgrupo:"",cat_grupos:[],col_cargos:[],cat_asigna:[],cargo_asigna:[],grupo_acreditados:[],grupo_tesoreros:[],pasoFinal:[],idacreditado:"",cat_noasigna:[],botonUpdate:"Actualizar",btnColmena:"Actualizar colmena",btnGrupo:"Actualizar grupo",message:"",statusmessage:"ui floating hidden message",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"},t.handleSubmit=t.handleSubmit.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),$.ajax({url:base_url+"/api/GeneralD1/get_colmenas_asigna",type:"GET",dataType:"json",success:function(e){this.setState({catcolmenas:e.catcolmenas})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleInputChange",value:function(e){var t=e.target,a="checkbox"===t.type?t.checked:t.value,r=t.name;(i=$(".get.soling form")).form("get values"),i.form("get value","csrf_bancomunidad_token");this.setState(_defineProperty({},r,a));var s=this;if("idcolmena"===r&&""!=e.target.value){var n="";n=(n="get_colmena_acreditados")+"/"+e.target.value,$.ajax({url:base_url+"/api/CarteraD1/"+n,type:"GET",dataType:"json",success:function(e){s.setState({message:"colmena cargada",statusmessage:"ui positive floating message ",cat_asigna:e.cat_asigna,cat_grupos:e.cat_grupos,col_cargos:e.col_cargos,idcol_presi:e.col_cargos[0].idpresidente,idcol_secre:e.col_cargos[0].idsecretario,col_presidenta:e.col_cargos[0].presidenta,col_secretaria:e.col_cargos[0].secretaria,icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),s.autoReset()}.bind(this),error:function(e,t,a){console.log("error"),s.setState({message:"No se ha encontrado información de la colmena",statusmessage:"ui negative floating message",cat_grupos:[],icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),s.autoReset()}.bind(this)}),$(".get.soling .ui.dropdown").dropdown("clear"),$(".get.divgrupo .ui.dropdown").dropdown("clear")}if("idgrupo"===r&&""!=e.target.value){var o="";o=(o="get_grupo_acreditados_cargo")+"/"+e.target.value,$.ajax({url:base_url+"/api/CarteraD1/"+o,type:"GET",dataType:"json",success:function(e){s.setState({message:"Grupo obtenido correctamente!",statusmessage:"ui positive floating message ",grupo_acreditados:e.grupo_acreditados,grupo_tesoreros:e.grupo_tesoreros,cargo_asigna:e.cargo_asigna,pasoFinal:e.grupo_acreditados,idgrupo_presi:e.idgrupo_presi,grupo_presi:e.grupo_presi,idgrupo_teso:e.idgrupo_teso,grupo_teso:e.grupo_teso,icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),s.autoReset()}.bind(this),error:function(e,t,a){console.log("error"),s.setState({message:"No se ha encontrado información de la colmena",statusmessage:"ui negative floating message",grupo_acreditados:[],icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),s.autoReset()}.bind(this)}),$(".get.divgrupo .ui.dropdown").dropdown("clear")}if("idacreditado"===r){var i;(i=$(".get.soling form")).form("set values",{idgrupocargo:"0",idcolcargo:"0",idgrupoorden:"1"})}this.setState(_defineProperty({},r,a))}},{key:"asignaCargo",value:function(e){this.setState({idgrupo_presi:e.idcargo1,idgrupo_teso:e.idcargo2})}},{key:"asignacionNew",value:function(){this.setState({blnActivar:!0,idcolmena:"",idgrupo:"",cat_grupos:[],idacreditado:"",grupo_acreditados:[],btnColmena:"Enviar"});$(".get.soling form").form("set values",{idcolmena:""});$(".get.soling .ui.dropdown").dropdown("clear")}},{key:"handleButton",value:function(e,t){if(e<2){this.asignacionNew();$(".get.soling form").form("set values",{idcolmena:""})}}},{key:"cargarGrupoAcreditados",value:function(e){var t="";t=(t="get_onlyacreditadosbygrupo")+"/"+e;var a=this;$.ajax({url:base_url+"/api/CarteraD1/"+t,type:"GET",dataType:"json",success:function(e){a.setState({grupo_acreditados:e.grupo_acreditados})}.bind(a),error:function(e,t,a){console.log("error")}.bind(a)})}},{key:"handleSubmitUpdate",value:function(e){e.preventDefault();cargarGrupoAcreditados(this.state.idgrupo)}},{key:"handleSubmitColmena",value:function(e){e.preventDefault();var t=$(".get.soling form"),a=t.form("get values"),r=t.form("get value","csrf_bancomunidad_token"),o=this;$.ajax({url:base_url+"api/CarteraD1/update_colmena_cargo",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:r,data:a},success:function(e){console.log(e),"OK"===e.status&&(o.setState({message:"Colmena actualizada correctamente.",statusmessage:"ui positive floating message",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon",csrf:e.newtoken}),o.autoReset())}.bind(this),error:function(e,t,a){if(404===e.status)o.setState({csrf:e.responseJSON.newtoken,message:e.responseJSON.message,statusmessage:"ui negative floating message"});else if(409===e.status){var r="",s=e.responseText.indexOf('{"status"');0!==s&&(r=e.responseText.substring(s));var n=JSON.parse(r);o.setState({csrf:n.newtoken,message:n.message,statusmessage:"ui negative floating message"})}}.bind(this)})}},{key:"handleSubmitGrupo",value:function(e){e.preventDefault();var t=$(".get.soling form"),a=t.form("get values"),r=t.form("get value","csrf_bancomunidad_token"),o=this;$.ajax({url:base_url+"api/CarteraD1/update_grupo_cargo",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:r,data:a},success:function(e){"OK"===e.status&&(o.setState({message:"Grupo actualizado correctamente!",statusmessage:"ui positive floating message ",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon",csrf:e.newtoken}),o.autoReset())}.bind(this),error:function(e,t,a){if(404===e.status)o.setState({csrf:e.responseJSON.newtoken,message:e.responseJSON.message,statusmessage:"ui negative floating message"});else if(409===e.status){var r="",s=e.responseText.indexOf('{"status"');0!==s&&(r=e.responseText.substring(s));var n=JSON.parse(r);o.setState({csrf:n.newtoken,message:n.message,statusmessage:"ui negative floating message"})}}.bind(this)})}},{key:"handleSubmit",value:function(e){e.preventDefault(),alert("Hola"),alert("Hola"+e);var t=$(".get.soling form"),a=t.form("get values"),r=t.form("get value","csrf_bancomunidad_token"),o=this;$.ajax({url:base_url+"api/CarteraD1/update_acreditado_grupo2",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:r,data:a},success:function(e){if("OK"===e.status){o.setState({message:e.message,statusmessage:"ui positive floating message ",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon",csrf:e.newtoken}),this.cargarGrupoAcreditados(this.state.idgrupo),o.autoReset();$(".get.soling form").form("set values",{idgrupocargo:"0",idcolcargo:"0",idgrupoorden:"1"})}}.bind(this),error:function(e,t,a){if(404===e.status)o.setState({csrf:e.responseJSON.newtoken,message:e.responseJSON.message,statusmessage:"ui negative floating message"});else if(409===e.status){var r="",s=e.responseText.indexOf('{"status"');0!==s&&(r=e.responseText.substring(s));var n=JSON.parse(r);o.setState({csrf:n.newtoken,message:n.message,statusmessage:"ui negative floating message"})}}.bind(this)})}},{key:"autoReset",value:function(){var e=this;this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},3e3)}},{key:"render",value:function(){var t=this;return React.createElement("div",null,React.createElement("div",{className:"ui segment vertical "},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Cambiar cargo en Colmena o Grupo")),React.createElement("div",{className:"ui  basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Nueva asignacion"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,1)})),React.createElement("button",{className:"ui button","data-tooltip":"Formato PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,2)})))),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(e){window.clearTimeout(t.timeout),t.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{className:"get soling"},React.createElement("form",{className:"ui form formgen",ref:"form",method:"post"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{id:"idcolmena",label:"Colmena",valor:this.state.idcolmena,valores:this.state.catcolmenas,onChange:this.handleInputChange.bind(this)}),React.createElement("br",null),React.createElement("br",null)),React.createElement("div",{className:""==this.state.idcolmena?"step hidden":""},React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"col_presidenta",label:"Presidenta:",readOnly:"readOnly",valor:this.state.col_presidenta,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"col_secretaria",label:"Secretaria:",readOnly:"readOnly",valor:this.state.col_secretaria,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{id:"idcol_presi",label:"Cambiar",valor:this.state.idcol_presi,valores:this.state.cat_asigna,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectDropDown,{id:"idcol_secre",label:"Cambiar",valor:this.state.idcol_secre,valores:this.state.cat_asigna,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"submit",name:"action",onClick:this.handleSubmitColmena.bind(this)},React.createElement("i",{className:"send icon"})," ",this.state.btnColmena," "))),React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{id:"idgrupo",label:"Grupo",valor:this.state.idgrupo,valores:this.state.cat_grupos,onChange:this.handleInputChange.bind(this)}),React.createElement("br",null),React.createElement("br",null))),React.createElement("div",{className:"get divgrupo"},React.createElement("div",{className:""==this.state.idgrupo?"step hidden":""},React.createElement("div",{className:"three fields"},React.createElement(InputField,{id:"grupo_presi",label:"Presidenta:",readOnly:"readOnly",valor:this.state.grupo_presi,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"grupo_teso",label:"Tesorera:",readOnly:"readOnly",valor:this.state.grupo_teso,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"three fields"},React.createElement(SelectDropDown,{id:"idgrupo_presi",label:"Cambiar",valor:this.state.idgrupo_presi,valores:this.state.grupo_acreditados,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectDropDown,{id:"idgrupo_teso",label:"Cambiar",valor:this.state.idgrupo_teso,valores:this.state.grupo_tesoreros,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"submit",name:"action",onClick:this.handleSubmitGrupo.bind(this)},React.createElement("i",{className:"send icon"})," ",this.state.btnGrupo," "))))))))}}]),a}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));