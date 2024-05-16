"use strict";var _createClass=function(){function n(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(e,t,a){return t&&n(e.prototype,t),a&&n(e,a),e}}();function _toConsumableArray(e){if(Array.isArray(e)){for(var t=0,a=Array(e.length);t<e.length;t++)a[t]=e[t];return a}return Array.from(e)}function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ",a="true"==this.props.mayuscula?"mayuscula":"";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:a,id:this.props.id,readOnly:this.props.readOnly,name:this.props.id,type:"text",value:this.props.valor,placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}})))}}]),t}(),Calendar=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.handleChange=t.handleChange.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleChange",value:function(e){}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myCalen)).on("onChange",this.handleChange)}},{key:"render",value:function(){var e="ui calendar "+(!1===this.props.visible?" hidden ":"");return React.createElement("div",{className:e,id:this.props.name},React.createElement("div",{className:"field"},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui input left icon"},React.createElement("i",{className:"calendar icon"}),React.createElement("input",{ref:"myCalen",type:"text",name:this.props.name,id:this.props.name,placeholder:"Fecha",onChange:this.handleChange}))))}}]),a}(),SelectOption=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" inline field ",t=this.props.valores.map(function(e){return React.createElement("option",{value:e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{value:this.props.valor},"Seleccione"),t))}}]),a}(),Mensaje=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={icon:"send icon",titulo:"Guardar",pregunta:"¿Desea enviar el registro?"},t}return _inherits(a,React.Component),_createClass(a,[{key:"render",value:function(){return React.createElement("div",{className:"ui mini test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok basic button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),a}();function Lista(e){var t=e.enca,a=0,n=t.map(function(e){return React.createElement("th",{className:"two wide",key:a++},e)});return React.createElement("tr",null,n)}var RecordDetalle=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"handleChange",value:function(e){}},{key:"handleClick",value:function(e){this.props.onClick(this.props.registro)}},{key:"render",value:function(){var e="0"==this.props.registro.estatus?"Importar":"Importado",t=null;return"1"==this.props.registro.estatus&&null!=this.props.registro.vale&&(t=React.createElement("button",{className:"ui button","data-tooltip":"Edit",onClick:this.handleClick.bind(this)},React.createElement("i",{className:"edit outline icon",onClick:this.handleClick.bind(this)}))),React.createElement("tr",null,React.createElement("td",null,this.props.registro.hora),React.createElement("td",null,this.props.registro.fecha),React.createElement("td",null,this.props.registro.autorizacion),React.createElement("td",null,this.props.registro.concepto),React.createElement("td",null,this.props.registro.deposito),React.createElement("td",null,this.props.registro.retiro),React.createElement("td",null,this.props.registro.saldo),React.createElement("td",null,this.props.registro.vale),React.createElement("td",null,this.props.registro.semana),React.createElement("td",null,this.props.registro.colmena),React.createElement("td",null,this.props.registro.grupo),React.createElement("td",null,this.props.registro.nomcolmena),React.createElement("td",null,this.props.registro.caja),React.createElement("td",null,e," ",t))}}]),t}(),Table=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.handleChange=t.handleChange.bind(t),t.handleClick=t.handleClick.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"componentDidUpdate",value:function(e,t){}},{key:"handleChange",value:function(e,t){}},{key:"handleClick",value:function(e){this.props.onClick(e)}},{key:"render",value:function(){var t=this,a=[];this.props.datos.map(function(e){return a.push(React.createElement(RecordDetalle,{registro:e,onChange:t.handleChange,onClick:t.handleClick}))});return React.createElement("div",{className:"ui grid"},React.createElement("div",{className:"column"},React.createElement("table",{className:"ui selectable celled blue table"},React.createElement("thead",null,React.createElement(Lista,{enca:["Hora","Fecha","Autorización","Concepto","Deposito","retiro","saldo","Vale","Semana","Colmena","Grupo","Nombre Colmena","Caja","Estatus"]})),React.createElement("tbody",null,a))))}}]),a}(),Captura=function(e){function n(e){var t;_classCallCheck(this,n);var a=_possibleConstructorReturn(this,(n.__proto__||Object.getPrototypeOf(n)).call(this,e));return a.state=(_defineProperty(t={catMov:[],catBancos:[],idbanco:0,message:"",statusmessage:"ui floating hidden message",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon",fecini:"",fecfin:"",ocultar:!0,lblRegistro:"Registro 0 de 0",fecha:"",autorizacion:"",concepto:"",deposito:"",id:0,hora:"",vale:"",semana:"",caja:"",verRecord:!1},"fecha",""),_defineProperty(t,"autorizacion",""),_defineProperty(t,"concepto",""),_defineProperty(t,"importe",""),t),a}return _inherits(n,React.Component),_createClass(n,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e});var t={url:base_url+"api/generalv1/bancosall",type:"GET",dataType:"json"};this.setState({catBancos:[]});var a=this;ajax(t).then(function(e){a.setState({catBancos:e.result,idbanco:0})},function(e){validaError(e)})}},{key:"handleInputChange",value:function(e){var t=e.target,a="checkbox"===t.type?t.checked:t.value,n=t.name;if(this.setState(_defineProperty({},n,a)),"userfile"==n){var f=this,i=e.target.files[0],s=new FileReader,v=0;s.onload=function(e){e.target.result.split(String.fromCharCode(13)).forEach(function(e){if(15<++v){if(0<e.length){var t={hora:"",fecha:"",autorizacion:"",concepto:"",deposito:"",retiro:"",saldo:"",vale:"",semana:"",colmena:"",grupo:"",nomcolmena:"",caja:"",estatus:"0"},a=["hora","fecha","autorizacion","concepto","deposito","retiro","saldo","vale","semana","colmena","grupo","momcolmena","caja","estatus"],n=1,i=e.substr(1,1),s=e.indexOf('"');if(0<s&&'"'==i)for(;;){var r,o="",c="",l=0;if(-1==s)break;if(r=e.indexOf('"',s+1),o=e.substr(s+1,r-s-1),s=e.indexOf('"',r+1),r+1,(1<o.length||0==n)&&2==n?0!=(l=o.indexOf("Autorizaci"))&&(c=o.substr(l+14),t[a[n]]=c,n++,l=o.indexOf("Referencia N"),t[a[n]]=0!=l?o.substr(0,l)+o.substr(l+52):o,n++):(t[a[n]]=o,n++),n-2>=e.length)break}else{var u="";n=1;s=e.indexOf(",",1),u=e.substr(1,s-1),t[a[n]]=u,n++;for(var d=1;;){var h=s;if(d='"'===e.substr(s+1,1)?(s=e.indexOf('"',s+2),1):(s=e.indexOf(",",s+1),0),u=e.substr(h+d+1,s-h-(d+1)),2==n){var m=u.indexOf("Autorizaci");if(0!=m){var p=u.substr(m+14);t[a[n]]=p,n++,m=u.indexOf("Referencia N"),t[a[n]]=0!=m?u.substr(1,m-1)+u.substr(m+52):u,n++}}else t[a[n]]=u,n++;if(s+=d,8<=n)break}}}""==t.autorizacion&&"Autorizacion"==t.autorizacion||"0.00"==t.deposito||(t.estatus="0",f.setState(function(e){return{catMov:[].concat(_toConsumableArray(e.catMov),[t])}}))}})},s.readAsText(i)}}},{key:"handleButton",value:function(e,t){if(2==e){if(0!=this.state.idbanco){var a=1!=this.state.ocultar;this.setState({ocultar:a})}}else if(3==e&&0!=this.state.idbanco){var n="0",i="0";if(""!=$("#fechaini").val()){var s=$("#fechaini").val().split("/"),r=new Date(s[2],s[1]-1,s[0]);n=moment(r).format("DDMMYYYY")}if(""!=$("#fechafin").val()){var o=$("#fechafin").val().split("/"),c=new Date(o[2],o[1]-1,o[0]);i=moment(c).format("DDMMYYYY")}this.setState({verRecord:!1});var l="bancosv1/getEdoCta/"+this.state.idbanco+"/"+n+"/"+i,u={url:base_url+"api/"+l,type:"GET",dataType:"json"};this.setState({catMov:[]});var d=this;ajax(u).then(function(e){d.setState({catMov:e.catmov})},function(e){validaError(e)})}}},{key:"handleImportar",value:function(){var e=this;$(".mini.modal").modal({closable:!1,onApprove:function(){e.import(e)}}).modal("show")}},{key:"handleCancelar",value:function(){this.setState({verRecord:!1})}},{key:"handleActualizar",value:function(){var e=this;$(".mini.modal").modal({closable:!1,onApprove:function(){e.updateRecord(e)}}).modal("show")}},{key:"updateRecord",value:function(a){event.preventDefault(),$(".ui.form.formopen").form({inline:!0,on:"blur",fields:{hora:{identifier:"hora",rules:[{type:"empty",prompt:"Seleccione la hora"}]},vale:{identifier:"vale",rules:[{type:"empty",prompt:"Requiere un vale"}]},semaana:{identifier:"semana",rules:[{type:"empty",prompt:"Requiere un valor"}]},caja:{identifier:"caja",rules:[{type:"empty",prompt:"Requiere un valor"}]}}}),$(".ui.form.formopen").form("validate form");var e=$(".ui.form.formopen").form("is valid");if(a.setState({message:"",statusmessage:"ui hidden message"}),1==e){var t=$("#csrf_bancomunidad_token").val(),n=[{fecha:a.state.fecha,hora:a.state.hora,vale:a.state.vale,semana:a.state.semana,caja:a.state.caja,id:a.state.id,csrf_bancomunidad_token:t}],i={url:base_url+"api/BancosV1/update_edocta/"+a.state.id,type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:t,data:n}};ajax(i).then(function(e){a.setState({csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message ",verRecord:!1});var t=a.state.catMov.find(function(e){return e.id===a.state.id});t.hora=a.state.hora,t.vale=a.state.vale,t.semana=a.state.semana,t.caja=a.state.caja,a.setState(function(e){return{catMov:[].concat(_toConsumableArray(e.catMov),[t])}})},function(e){var t=validaError(e);a.setState({csrf:t.newtoken})})}else a.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),a.autoReset()}},{key:"import",value:function(s){for(var r=s.state.catMov.length,e=function(t){var a=s.state.catMov[t];if("0"==a.estatus){var e=$("#csrf_bancomunidad_token").val(),n=[{fecha:a.fecha,autorizacion:a.autorizacion,concepto:a.concepto,deposito:a.deposito,retiro:a.retiro,saldo:a.saldo,consecutivo:t,csrf_bancomunidad_token:e}],i={url:base_url+"api/BancosV1/add_edocta/"+s.state.idbanco,type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:e,data:n}};ajax(i).then(function(e){s.setState({csrf:e.newtoken,lblRegistro:"Registro "+(t+1)+" de "+r}),a.estatus="1",s.setState(function(e){return{catMov:[].concat(_toConsumableArray(e.catMov),[a])}})},function(e){var t=validaError(e);s.setState({csrf:t.newtoken})})}},t=0;t<s.state.catMov.length;t++)e(t)}},{key:"handleClickRecord",value:function(e){this.setState({verRecord:!0}),this.setState({recordEdit:e,fecha:e.fecha,autorizacion:e.autorizacion,concepto:e.concepto,deposito:e.deposito,id:e.id,hora:e.hora,vale:e.vale,semana:e.semana,caja:e.caja}),console.log(e)}},{key:"autoReset",value:function(e){var t=this;this.timeout=window.setTimeout(function(){t.setState({message:"",statusmessage:"ui message hidden"})},e)}},{key:"render",value:function(){var t=this,e=this.state.lblRegistro;return React.createElement("div",null,React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Estado de Cuenta Bancaria")),React.createElement("div",{className:"ui secondary menu"},React.createElement("div",{className:"ui  basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Nuevo Registro"},React.createElement("i",{className:"plus square outline icon",onClick:this.handleButton.bind(this,0)})),React.createElement("button",{className:"ui button","data-tooltip":"Formato PDF"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,1)})),React.createElement("button",{className:"ui button","data-tooltip":"Cargar Archivo",onClick:this.handleButton.bind(this,2)},React.createElement("i",{className:"upload icon",onClick:this.handleButton.bind(this,2)}))),React.createElement("div",{className:"right menu"},React.createElement("div",{className:"item ui fluid category search"},React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:"prompt mayuscula",type:"text",placeholder:"Buscar Nombre"}),React.createElement("i",{className:"search link icon"})),React.createElement("div",{className:"results"})))),React.createElement(Mensaje,null),React.createElement("div",{className:"ui form formopen"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:"fields"},React.createElement(SelectOption,{cols:"four wide",name:"idbanco",id:"idbanco",label:"Banco:",valor:this.state.idbanco,valores:this.state.catBancos,onChange:this.handleInputChange.bind(this)}),React.createElement("div",{className:"two wide field"}),React.createElement(Calendar,{name:"fechaini",label:"Fecha Inicial",valor:this.state.fechini,onChange:this.handleInputChange.bind(this)}),React.createElement(Calendar,{name:"fechafin",label:"Fecha Final",valor:this.state.fechafin,onChange:this.handleInputChange.bind(this)}),React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"button",name:"action",onClick:this.handleButton.bind(this,3)}," Filtrar "))),React.createElement("div",{className:1==this.state.ocultar?"hidden":""},React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:"ui bottom primary basic button",type:"file",accept:".xlsx, .csv",name:"userfile",size:"20",onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui label"},e),React.createElement("div",{className:"ui vertical segment wide field right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"button",name:"importar",onClick:this.handleImportar.bind(this)},React.createElement("i",{className:"send icon"}),"Importar")))),React.createElement("div",{className:1==this.state.verRecord?"row box-fixed-emergente":"hidden"},React.createElement("div",{className:"row"},React.createElement("div",{className:"fields"},React.createElement(InputField,{id:"fecha",label:"Fecha",cols:"two wide",valor:this.state.fecha,onChange:this.handleInputChange.bind(this),readOnly:"readOnly"}),React.createElement(InputField,{id:"autorizacion",label:"Autorización",cols:"two wide",valor:this.state.autorizacion,onChange:this.handleInputChange.bind(this),readOnly:"readOnly"}),React.createElement(InputField,{id:"concepto",label:"Concepto",cols:"seven wide",valor:this.state.concepto,onChange:this.handleInputChange.bind(this),readOnly:"readOnly"}),React.createElement(InputField,{id:"deposito",label:"deposito",cols:"two wide",valor:this.state.deposito,onChange:this.handleInputChange.bind(this),readOnly:"readOnly"}),React.createElement(InputField,{id:"id",label:"ID",cols:"two wide",valor:this.state.id,onChange:this.handleInputChange.bind(this)}))),React.createElement("div",{className:"fields"},React.createElement(InputField,{id:"hora",label:"Hora",cols:"two wide",valor:this.state.hora,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"vale",label:"Vale",cols:"two wide",valor:this.state.vale,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"semana",label:"Semana",cols:"two wide",valor:this.state.semana,onChange:this.handleInputChange.bind(this)}),React.createElement(InputField,{id:"caja",label:"Caja",cols:"two wide",valor:this.state.caja,onChange:this.handleInputChange.bind(this)})),React.createElement("div",{className:"ui vertical segment wide field right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom red basic button  ",type:"button",name:"cancelar",onClick:this.handleCancelar.bind(this)},React.createElement("i",{className:"x icon"}),"Cancelar"),React.createElement("button",{className:"ui bottom primary basic button",type:"button",name:"actualizar",onClick:this.handleActualizar.bind(this)},React.createElement("i",{className:"send icon"}),"Actualizar")))),React.createElement(Table,{name:"catMov",datos:this.state.catMov,onClick:this.handleClickRecord.bind(this)})),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(e){window.clearTimeout(t.timeout),t.setState({message:"",statusmessage:"ui message hidden"})}})))}}]),n}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root")),$(".ui.search").search({type:"category",minCharacters:8,apiSettings:{url:base_url+"api/CarteraD1/find_acreditados?q={query}",onResponse:function(e){var n={results:{}};if(e&&e.result)return $.each(e.result,function(e,t){var a=t.idacreditado||"Sin asignar";if(8<=e)return!1;void 0===n.results[a]&&(n.results[a]={name:a,results:[]}),n.results[a].results.push({title:t.nombre,description:t.idcredito+" : "+t.idpagare})}),n}}});