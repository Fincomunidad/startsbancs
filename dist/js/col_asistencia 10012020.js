"use strict";var _createClass=function(){function n(e,t){for(var a=0;a<t.length;a++){var n=t[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(e,n.key,n)}}return function(e,t,a){return t&&n(e.prototype,t),a&&n(e,a),e}}();function _defineProperty(e,t,a){return t in e?Object.defineProperty(e,t,{value:a,enumerable:!0,configurable:!0,writable:!0}):e[t]=a,e}function _classCallCheck(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function _possibleConstructorReturn(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function _inherits(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}var InputField=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{id:this.props.id,name:this.props.id,type:"text",readOnly:this.props.readOnly,value:this.props.valor,placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}})))}}]),t}(),SelectOption=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:0},t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.setState({value:e.target.value}),this.props.onChange(e)}},{key:"componentDidMount",value:function(){}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("option",{key:e.value,value:e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("select",{className:"ui fluid dropdown",ref:"myCombo",name:this.props.id,id:this.props.id,onChange:this.handleSelectChange.bind(this)},React.createElement("option",{key:"0",value:this.props.valor},"Seleccione"),t))}}]),a}();function Lista(e){var t=e.enca,a=0,n=t.map(function(e){return React.createElement("th",{key:a++},e)});return React.createElement("tr",null,n)}var ListaRow=function(e){function t(){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).apply(this,arguments))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){return React.createElement("div",{className:"ui vertical segment aligned"},React.createElement("div",{className:"field"},this.props.categoria))}}]),t}(),DocumentoRow=function(e){function i(e){var t;_classCallCheck(this,i);var a=_possibleConstructorReturn(this,(i.__proto__||Object.getPrototypeOf(i)).call(this,e)),n="iddoc_"+a.props.valor.idacreditado;a.props.valor.asistencia;return a.state=(_defineProperty(t={},n,!1),_defineProperty(t,"acreditadoid",a.props.valor.acreditadoid),_defineProperty(t,"idgrupo",a.props.valor.idgrupo),_defineProperty(t,"asistencia",a.props.valor.asistencia),_defineProperty(t,"incidencia",a.props.valor.incidencia),_defineProperty(t,"incidencia_det",a.props.incidencia_det),_defineProperty(t,"inci_det",[]),_defineProperty(t,"opcion",a.props.valor.opcion),_defineProperty(t,"verificacion",a.props.valor.verificacion),_defineProperty(t,"niveldesea",a.props.valor.niveldesea),_defineProperty(t,"descrip",a.props.valor.descrip),t),a.handleChange=a.handleChange.bind(a),a.handleClick=a.handleClick.bind(a),a}return _inherits(i,React.Component),_createClass(i,[{key:"handleClick",value:function(e){e.preventDefault(),this.props.handleBoton(this.props.valor,this.state.opcion,0)}},{key:"handleChange",value:function(e){var t=this.state.asistencia,a=this.state.incidencia,n=this.state.opcion;this.state.incidencia_det;"asistencia[]"==e.target.name&&(t=e.target.value,this.setState({asistencia:t}),9==t&&(a=5,n=0,this.setState({incidencia:a,opcion:n}))),"incidencia[]"==e.target.name&&(9==t?(this.setState({incidencia:5}),indicencia=5):(a=e.target.value,this.setState({incidencia:a}))),"opcion[]"==e.target.name&&(9==t?(this.setState({opcion:0}),n=0):(n=e.target.value,this.setState({opcion:n}))),this.props.valor.asistencia=t,this.props.valor.incidencia=a,this.props.handleBoton(this.props.valor,n,1)}},{key:"handleInputChange",value:function(e){}},{key:"render",value:function(){var e,t=this.props.asistencia,a=this.props.incidencia,n=this.state.inci_det,i=this.props.opciones,s=this.props.verificaciones,o=[];t.forEach(function(e){o.push(React.createElement("option",{value:e.value},e.name))});var c=[];a.forEach(function(e){c.push(React.createElement("option",{value:e.value},e.name))});var r=[];n.forEach(function(e){r.push(React.createElement("option",{value:e.value},e.name))});var l=[];i.forEach(function(e){l.push(React.createElement("option",{value:e.value},e.name))});var d=[];s.forEach(function(e){d.push(React.createElement("option",{value:e.value},e.name))});getWeekNo(),this.props.valor.asistencia,this.props.valor.incidencia;return e=React.createElement("td",{className:" center aligned"},React.createElement("a",{onClick:this.handleClick},React.createElement("i",{className:"edit icon circular green",onClick:this.handleClick}))),React.createElement("tr",null,React.createElement("td",{className:"hidden"},React.createElement("input",{className:"hidden",id:"acreditadoid[]",name:"acreditadoid[]",type:"text",readOnly:"readOnly",value:this.state.acreditadoid,onChange:this.handleChange.bind(this)})),React.createElement("td",{className:"hidden"}," ",React.createElement("input",{className:"hidden",id:"idgrupo[]",name:"idgrupo[]",type:"text",readOnly:"readOnly",value:this.state.idgrupo,onChange:this.handleChange.bind(this)})),React.createElement("td",{className:"text-center"},this.props.valor.idacreditado),React.createElement("td",null,this.props.valor.nombre),React.createElement("td",null,this.props.valor.cargo_colmena),React.createElement("td",null,this.props.valor.cargo_grupo),React.createElement("td",{className:"center aligned"},React.createElement("select",{className:"ui dropdown",value:this.state.asistencia,name:"asistencia[]",onChange:this.handleChange},o)),React.createElement("td",null,React.createElement("select",{className:"ui dropdown",value:this.state.incidencia,name:"incidencia[]",onChange:this.handleChange},c)),React.createElement("td",null,React.createElement("select",{className:"ui dropdown",value:this.state.opcion,name:"opcion[]",onChange:this.handleChange},l)),React.createElement("td",{className:"hidden"},React.createElement("select",{className:"ui dropdown",value:this.state.verificacion,name:"verificacion[]",onChange:this.handleChange},d)),React.createElement("td",{className:"hidden"},React.createElement("input",{className:"hidden",id:"niveldesea[]",name:"niveldesea[]",type:"text",readOnly:"readOnly",value:this.state.niveldesea,onChange:this.handleChange.bind(this)})),React.createElement("td",{className:"hidden"},React.createElement("input",{className:"hidden",id:"descrip[]",name:"descrip[]",type:"text",readOnly:"readOnly",value:this.state.descrip,onChange:this.handleChange.bind(this)})),e)}}]),i}(),MultiSelect=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"handleChange",value:function(e){}},{key:"render",value:function(){var e=this.props.valores.map(function(e){return React.createElement("option",{value:e.value},e.name)});return React.createElement("div",{className:"ten wide field"},React.createElement("label",null,this.props.label),React.createElement("select",{onChange:this.handleChange.bind(this),name:this.props.name,className:"ui fluid search dropdown selection multiple",multiple:"",id:this.props.name},React.createElement("option",{value:""},"Seleccione"),e))}}]),t}(),SelectDropDown=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={value:""},t.handleSelectChange=t.handleSelectChange.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleSelectChange",value:function(e){this.props.onChange(e)}},{key:"componentDidMount",value:function(){$(ReactDOM.findDOMNode(this.refs.myDrop)).on("change",this.handleSelectChange)}},{key:"render",value:function(){var e=(void 0!==this.props.cols?this.props.cols:"")+" field ",t=this.props.valores.map(function(e){return React.createElement("div",{className:"item","data-value":e.value},e.name)});return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui fluid search selection dropdown"},React.createElement("input",{type:"hidden",ref:"myDrop",value:this.props.valor,name:this.props.id,onChange:this.handleSelectChange}),React.createElement("i",{className:"dropdown icon"}),React.createElement("div",{className:"default text"},"Seleccione"),React.createElement("div",{className:"menu"},t)))}}]),a}(),Catalogo=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={catAsistencia:[],catIncidencia:[]},t.handleBoton=t.handleBoton.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"handleBoton",value:function(e,t){this.props.handleBoton(e,t,0)}},{key:"render",value:function(){var t=[],a=null,n=this.props.asistencia,i=this.props.incidencia,s=this.props.opcion,o=this.props.verificacion,c=this.props.handleBoton;return this.props.valores.forEach(function(e){e.grupo_nombre!==a&&t.push(React.createElement(ListaRow,{categoria:e.grupo_nombre,key:e.grupo_nombre})),t.push(React.createElement(DocumentoRow,{valor:e,key:e.nombre,asistencia:n,incidencia:i,opciones:s,verificaciones:o,handleBoton:c})),a=e.grupo_nombre}),React.createElement("div",{className:"ui segment"},React.createElement("tbody",null,t))}}]),a}(),InputFieldFind=function(e){function t(e){return _classCallCheck(this,t),_possibleConstructorReturn(this,(t.__proto__||Object.getPrototypeOf(t)).call(this,e))}return _inherits(t,React.Component),_createClass(t,[{key:"render",value:function(){var t=this,e=(void 0!==this.props.cols?this.props.cols:"")+" field ",a="true"==this.props.mayuscula?"mayuscula":"";return React.createElement("div",{className:e},React.createElement("label",null,this.props.label),React.createElement("div",{className:"ui icon input"},React.createElement("input",{className:a,id:this.props.id,name:this.props.id,value:this.props.valor,type:"text",placeholder:this.props.placeholder,onChange:function(e){return t.props.onChange(e)}}),React.createElement("i",{className:this.props.icons,onClick:function(e){return t.props.onClick(e,t.props.valor,t.props.name)}})))}}]),t}(),Mensaje=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={icon:"send icon",titulo:"Guardar",pregunta:"¿Desea enviar el registro?"},t}return _inherits(a,React.Component),_createClass(a,[{key:"render",value:function(){return React.createElement("div",{className:"ui mini test modal scrolling transition hidden"},React.createElement("div",{className:"ui icon header"},React.createElement("i",{className:this.state.icon}),this.state.titulo),React.createElement("div",{className:"center aligned content "},React.createElement("p",null,this.state.pregunta)),React.createElement("div",{className:"actions"},React.createElement("div",{className:"ui red cancel basic button"},React.createElement("i",{className:"remove icon"})," No "),React.createElement("div",{className:"ui green ok basic button"},React.createElement("i",{className:"checkmark icon"})," Si ")))}}]),a}(),Captura=function(e){function a(e){_classCallCheck(this,a);var t=_possibleConstructorReturn(this,(a.__proto__||Object.getPrototypeOf(a)).call(this,e));return t.state={id:0,idcolmena:"",catcolmenas:[],catacreditados:[],boton:"Enviar",message:"",statusmessage:"ui floating hidden message",icons1:"inverted circular search link icon",icons2:"inverted circular search link icon",anio:0,semana:0,catAnios:[],catSemanas:[],catAsistencia:[],catIncidencia:[],visibleUpdate:!1,catSolicitud:[],catOpciones:[{value:0,name:"Ninguno"},{value:1,name:"Verificación"},{value:2,name:"Paralelo"},{value:3,name:"Reinversión"},{value:4,name:"Renuncia"},{value:5,name:"Inactiva"}],mujeres:"",catVerificacion:[{value:0,name:"Seleccione"},{value:1,name:"Inversion Completa"},{value:2,name:"Inversion Parcial"},{value:3,name:"No hubo inversion"}],catInactiva:[{value:0,name:"Seleccione"},{value:1,name:"Ninguna"},{value:2,name:"Mora interna"},{value:3,name:"Mora al programa"}],entiempo:"0",participa:"1",tema:"",imparte:2,mab:0,mab_entrega:0,mab_incidencia:"",mab_pedido:0,opcion:0,verifica:0,inactiva:0,niveldesea1:"",descrip1:"",descrip2:"",numero:0,promotor:"",asistencia:"",incidencia:""},t.handleBoton=t.handleBoton.bind(t),t}return _inherits(a,React.Component),_createClass(a,[{key:"componentWillMount",value:function(){var e=$("#csrf").val();this.setState({csrf:e}),$.ajax({url:base_url+"/api/ColmenasV1/colmenas",type:"GET",dataType:"json",success:function(e){this.setState({catcolmenas:e.catcolmenas})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)});for(var t=[],a=getWeekNo();1<=a;a--){var n={"value:":a,name:a};t.push(n)}this.setState({catSemanas:t});this.setState({catAnios:[{value:2019,name:2019}]}),$.ajax({url:base_url+"/api/ColmenasV1/catAIV",type:"GET",dataType:"json",success:function(e){this.setState({catAsistencia:e.asistencia,catIncidencia:e.incidencia})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)}),$.ajax({url:base_url+"/api/CarteraV1/get_acreditados",type:"GET",dataType:"json",success:function(e){this.setState({catSolicitud:e.record})}.bind(this),error:function(e,t,a){console.log("error")}.bind(this)})}},{key:"handleInputChange",value:function(e){var t=e.target,a="checkbox"===t.type?t.checked:t.value,n=t.name,i=this.state.anio,s=this.state.semana,o=this.state.idcolmena;if("anio"===n&&(i=e.target.value),"semana"===n&&(s=e.target.value),"idcolmena"===n){o=e.target.value;var c=this.state.catcolmenas.find(function(e){return e.value==o});this.setState({dia:c.dia_text,hora:c.horainicio,lugar:c.direccion,promotor:c.nompromotor})}if(""!=o&&"0"!=i&&"0"!=s){this.setState({catacreditados:[]});var r="";if(""==s)return;r=(r="get_acreditadosbycolmena")+"/"+o+"/"+i+"/"+s,$.ajax({url:base_url+"/api/colmenasV1/"+r,type:"GET",dataType:"json",success:function(e){var t=e.colmena[0],a="t"==t.entiempo?1:0,n="t"==t.participa?1:0,i=t.imparte,s=t.mab_entrega,o=t.mab_incidencia,c=t.mab_pedido,r=e.solingre,l=e.nuevoingre,d=e.reingre,m=[];0!=r&&r.forEach(function(e){m.push(e.value)});var u=[];0!=l&&l.forEach(function(e){u.push(e.value)});var h=[];0!=d&&d.forEach(function(e){h.push(e.value)}),this.setState({message:e.message,statusmessage:"ui positive floating message ",catacreditados:e.grupo_acreditados,icons1:"inverted circular search link icon",icons2:"inverted circular search link icon",id:t.id,mujeres:t.mujeres,entiempo:a,tema:t.tema,mab_entrega:s,mab_incidencia:o,mab_pedido:c,notas:t.notas,mab:e.mab}),console.log(e.mab),this.autoReset(),$("#sol_ingreso").dropdown("clear"),$("#ingreso_nuevo").dropdown("clear"),$("#reingreso").dropdown("clear");$(".get.soling form").form("set values",{entiempo:0==a?"0":"1",participa:0==n?"0":"1",imparte:0==i?"0":1==i?"1":"2",mab_entrega:s,sol_ingreso:m,ingreso_nuevo:u,reingreso:h})}.bind(this),error:function(e,t,a){console.log("error"),this.setState({message:"No se ha encontrado información de la colmena",statusmessage:"ui negative floating message",catacreditados:[],icons1:"inverted circular search link icon",icons2:"inverted circular search link icon"}),this.autoReset()}.bind(this)})}this.setState(_defineProperty({},n,a))}},{key:"handleCloseW",value:function(){this.setState({visibleUpdate:!1})}},{key:"handleButton",value:function(e,t){var a=this.state.anio,n=this.state.semana,i=this.state.idcolmena,s="";if(1==e&&""!=i&&"0"!=a&&"0"!=n){if(""==n)return;s=(s="ReportV1/asistenciaFormat")+"/"+i+"/"+a+"/"+n;var o=document.createElement("a");o.href=base_url+"api/"+s,o.target="_blank",document.body.appendChild(o),o.click(),document.body.removeChild(o)}}},{key:"handleInputChangeData",value:function(e){var t=e.target,a=t.value,n=t.name;if(this.setState(_defineProperty({},n,a)),"participa"==n)if("0"==a){this.setState({tema:"",imparte:2});$(".get.soling form").form("set values",{imparte:"2"})}else if("1"==a&&2==this.state.imparte){this.setState({imparte:0});$(".get.soling form").form("set values",{imparte:"1"})}if("imparte"==n)if("0"==this.state.participa&&2!=a){this.setState({imparte:2});$(".get.soling form").form("set values",{imparte:"2"})}else if("1"==this.state.participa&&2==a){this.setState({imparte:0});$(".get.soling form").form("set values",{imparte:"0"})}"tema"==n?"0"==this.state.participa&&""!=a&&this.setState({tema:""}):"opcion"==n?"0"!=a&&"1"!=a||this.setState({renunciaobs:""}):"renunciaobs"==n?"0"!=this.state.opcion&&"1"!=this.state.opcion&&""!=this.state.opcion&&null!=this.state.opcion||""==a||this.setState({renunciaobs:""}):"mab_entrega"==n?"0"!=a&&"2"!=a&&"3"!=a||this.setState({indicendiad:""}):"indicendiad"==n&&("0"!=this.state.mab_entrega&&"2"!=this.state.mab_entrega&&"3"!=this.state.mab_entrega&&""!=this.state.mab_entrega&&null!=this.state.mab_entrega||""==a||this.setState({indicendiad:""}))}},{key:"handleBoton",value:function(e,t,a){if(0!=t&&null!=t){0==a&&this.setState({visibleUpdate:!0});var n="",i="",s=e.idacreditado;2!=t&&3!=t||null==e.descrip?4==t&&null!=e.descrip?n=e.descrip:5==t&&null!=e.descrip&&(n=e.descrip):(i=e.descrip,null!=e.niveldesea&&e.niveldesea);var o=e.verificacion,c=null,r=e.asistencia,l=e.incidencia;5==t&&(c=e.verificacion,o=null),this.setState({asistencia:r,incidencia:l,numero:e.idacreditado,nombre:e.nombre,opcion:t,niveldesea1:e.niveldesea,descrip1:n,descrip2:i,verifica:o,inactiva:c});$(".get.soling form").form("set values",{verifica:o,inactiva:c});if(1==a){var d=this.state.catacreditados.find(function(e){return e.idacreditado==s});d.asistencia=r,d.incidencia=l}}}},{key:"handleUpdateColmena",value:function(e){event.preventDefault(),$(".ui.form.formgen").form({inline:!0,on:"blur",fields:{idcolmena:{identifier:"idcolmena",rules:[{type:"empty",prompt:"Requiere un valor"}]},anio:{identifier:"anio",rules:[{type:"empty",prompt:"Requiere un valor"}]},semana:{identifier:"semana",rules:[{type:"empty",prompt:"Requiere un valor"}]}}}),$(".ui.form.formgen").form("validate form");var t=$(".ui.form.formgen").form("is valid");if(this.setState({message:"",statusmessage:"ui message hidden"}),1==t){var a,n=(a=$(".get.soling form")).form("get values"),i=a.form("get value","csrf_bancomunidad_token"),s=(a=$(".get.soling form")).form("get value","sol_ingreso"),o=a.form("get value","ingreso_nuevo"),c=a.form("get value","reingreso"),r={id:this.state.id,idcolmena:n.idcolmena,anio:n.anio,semana:n.semana,mujeres:n.mujeres,entiempo:n.entiempo,participa:n.participa,tema:n.tema,imparte:n.imparte,mab_entrega:n.mab_entrega,mab_incidencia:n.mab_incidencia,mab_pedido:n.mab_pedido,notas:n.notas,sol_ingreso:s,ingreso_nuevo:o,reingreso:c},l=this;$(".mini.modal").modal({closable:!1,onApprove:function(){var e={url:base_url+"api/ColmenasV1/data_colmena",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:i,data:r}};ajax(e).then(function(e){l.setState({csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message "}),l.autoReset()},function(e){var t=validaError(e);l.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui negative floating message"}),l.autoReset()})}}).modal("show")}else this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset()}},{key:"handleUpdateRecord",value:function(e){e.preventDefault(),$(".ui.form.formgen").form({inline:!0,on:"blur",fields:{idcolmena:{identifier:"idcolmena",rules:[{type:"empty",prompt:"Requiere un valor"}]},anio:{identifier:"anio",rules:[{type:"empty",prompt:"Requiere un valor"}]},semana:{identifier:"semana",rules:[{type:"empty",prompt:"Requiere un valor"}]}}}),$(".ui.form.formgen").form("validate form");var t=$(".ui.form.formgen").form("is valid");if(this.setState({message:"",statusmessage:"ui message hidden"}),1==t){this.setState({visibleUpdate:!1});var a=this.state.numero,n=this.state.verifica,i=this.state.inactiva,s=this.state.niveldesea1,o=this.state.catacreditados.find(function(e){return e.idacreditado==a});o.opcion=this.state.opcion,1==this.state.opcion?(o.verificacion=n,o.niveldesea=null,o.descrip=null):this.state.opcion<=3?(o.verificacion=null,o.niveldesea=s,o.descrip=this.state.descrip2):(5==this.state.opcion?o.verificacion=i:o.verificacion=null,o.niveldesea=null,o.descrip=this.state.descrip1)}else this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset()}},{key:"handleSubmit",value:function(e){e.preventDefault(),$(".ui.form.formgen").form({inline:!0,on:"blur",fields:{idcolmena:{identifier:"idcolmena",rules:[{type:"empty",prompt:"Requiere un valor"}]},anio:{identifier:"anio",rules:[{type:"empty",prompt:"Requiere un valor"}]},semana:{identifier:"semana",rules:[{type:"empty",prompt:"Requiere un valor"}]}}}),$(".ui.form.formgen").form("validate form");var t=$(".ui.form.formgen").form("is valid");if(this.setState({message:"",statusmessage:"ui message hidden"}),1==t){var a=$(".get.soling form"),n=a.form("get values"),i=a.form("get value","csrf_bancomunidad_token"),s=this.state.catacreditados,o={csrf_bancomunidad_token:n.csrf_bancomunidad_token,id:this.state.id,idcolmena:n.idcolmena,anio:n.anio,semana:n.semana,datos:s},c=this;$(".mini.modal").modal({closable:!1,onApprove:function(){var e={url:base_url+"api/ColmenasV1/add_asistencia",type:"PUT",dataType:"json",data:{csrf_bancomunidad_token:i,data:o}};ajax(e).then(function(e){c.setState({semana:0,csrf:e.newtoken,message:e.message,statusmessage:"ui positive floating message ",catacreditados:[]}),c.autoReset();$(".get.soling form").form("set values",{semana:"0"})},function(e){var t=validaError(e);c.setState({csrf:t.newtoken,message:t.message,statusmessage:"ui negative floating message"}),c.autoReset()})}}).modal("show")}else this.setState({message:"Datos incompletos!",statusmessage:"ui negative floating message"}),this.autoReset()}},{key:"autoReset",value:function(){var e=this;this.timeout=window.setTimeout(function(){e.setState({message:"",statusmessage:"ui message hidden"})},3e3)}},{key:"render",value:function(){var t=this;this.state.opcion;console.log(this.state.mab);var e=null,a=null,n=null;return"1"==this.state.mab&&(e=React.createElement(SelectDropDown,{name:"mab_entrega",cols:"three wide",id:"mab_entrega",label:"Opción",valor:this.state.mab_entrega,valores:[{name:"Sin incidencia",value:"0"},{name:"Con incidencia",value:"1"},{name:"Sin pedido",value:"2"},{name:"Compra directa",value:"3"}],onChange:this.handleInputChangeData.bind(this)}),a=React.createElement(InputField,{name:"mab_incidencia",id:"mab_incidencia",label:"Descripción de la incidencia",cols:"eleven wide",valor:this.state.mab_incidencia,onChange:this.handleInputChangeData.bind(this)}),n=React.createElement(InputField,{name:"mab_pedido",id:"mab_pedido",cols:"two wide",label:"Pedido",valor:this.state.mab_pedido,onBlur:this.handleonBlur,onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",null,React.createElement("div",{className:"ui segment vertical "},React.createElement("div",{className:"row"},React.createElement("h3",{className:"ui rojo header"},"Asistencia a colmenas")),React.createElement("div",{className:"ui  basic icon buttons"},React.createElement("button",{className:"ui button","data-tooltip":"Formato en blanco del registro seleccionado"},React.createElement("i",{className:"file pdf outline icon",onClick:this.handleButton.bind(this,1)})))),React.createElement(Mensaje,null),React.createElement("div",{className:this.state.statusmessage},React.createElement("p",null,React.createElement("b",null,this.state.message)),React.createElement("i",{className:"close icon",onClick:function(e){window.clearTimeout(t.timeout),t.setState({message:"",statusmessage:"ui message hidden"})}})),React.createElement("div",{className:"get soling"},React.createElement("form",{className:"ui form formgen",ref:"form"},React.createElement("input",{type:"hidden",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("div",{className:!0===this.state.visibleUpdate?"":"hidden"},React.createElement("div",{className:"box-fixed-emergente "},React.createElement("input",{type:"hidden",id:"csrf_bancomunidad_token",name:"csrf_bancomunidad_token",value:this.state.csrf}),React.createElement("h4",{className:"ui dividing header right aligned"},React.createElement("i",{className:"window close icon red link",onClick:this.handleCloseW.bind(this)})),React.createElement("div",{className:"hidden"},React.createElement(InputField,{id:"idcolmena",label:"Colmena",cols:"two wide",readOnly:"readOnly",valor:this.state.idcolmena,onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"numero",label:"Numero",cols:"two wide",readOnly:"readOnly",valor:this.state.numero,onChange:this.handleInputChangeData.bind(this)}),React.createElement(InputField,{id:"nombre",label:"Nombre",cols:"twelve wide",readOnly:"readOnly",valor:this.state.nombre,onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",{className:1==this.state.opcion?"two fields":"hidden"},React.createElement(SelectDropDown,{name:"verifica",cols:"three wide",id:"verifica",label:"Verificacion",valor:this.state.verifica,valores:this.state.catVerificacion,onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",{className:5==this.state.opcion?"two fields":"hidden"},React.createElement(SelectDropDown,{name:"inactiva",cols:"three wide",id:"inactiva",label:"Inactiva",valor:this.state.inactiva,valores:this.state.catInactiva,onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",{className:4==this.state.opcion||5==this.state.opcion?"two fields":"hidden"},React.createElement(InputField,{id:"descrip1",name:"descrip1",label:"Observación",cols:"eleven wide",valor:this.state.descrip1,onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",{className:2==this.state.opcion||3==this.state.opcion?"":"hidden"},React.createElement("p",null,React.createElement("b",null,"Análisis de crédito"))),React.createElement("div",{className:2==this.state.opcion||3==this.state.opcion?"four fields":"hidden"},React.createElement(InputField,{name:"niveldesea1",id:"niveldesea1",cols:"two wide",label:"Nivel deseado",valor:this.state.niveldesea1,onChange:this.handleInputChangeData.bind(this)}),React.createElement(InputField,{id:"descrip2",name:"descrip2",label:"Para que lo quiere invertir o donde lo va invertir",cols:"eleven wide",valor:this.state.descrip2,onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"button",onClick:this.handleUpdateRecord.bind(this)},React.createElement("i",{className:"send icon",onClick:this.handleUpdateRecord.bind(this)}),"Aceptar"))))),React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{id:"idcolmena",label:"Colmena",valor:this.state.idcolmena,valores:this.state.catcolmenas,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectOption,{name:"anio",cols:"five wide",id:"anio",label:"Año",valor:this.state.anio,valores:this.state.catAnios,onChange:this.handleInputChange.bind(this)}),React.createElement(SelectOption,{name:"semana",cols:"three wide",id:"semana",label:"Semana",valor:this.state.semana,valores:this.state.catSemanas,onChange:this.handleInputChange.bind(this)})),React.createElement("div",null,"Promotora: ",this.state.promotor,React.createElement("div",{className:"three fields"},React.createElement(InputField,{id:"dia",name:"dia",label:"Dia",cols:"four wide",valor:this.state.dia,onChange:this.handleInputChangeData.bind(this)}),React.createElement(InputField,{id:"hora",name:"hora",label:"Hora",cols:"three wide",valor:this.state.hora,onChange:this.handleInputChangeData.bind(this)}),React.createElement(InputField,{id:"lugar",name:"lugar",label:"Lugar",cols:"ten wide",valor:this.state.lugar,onChange:this.handleInputChangeData.bind(this)}))),React.createElement("div",{className:"ui accordion field segment"},React.createElement("div",{className:"title"},React.createElement("i",{className:"dropdown icon"}),"Datos de la colmena"),React.createElement("div",{className:"content field"},React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"mujeres",name:"mujeres",label:"Mujeres",cols:"two wide",valor:this.state.mujeres,onChange:this.handleInputChangeData.bind(this)}),React.createElement(SelectDropDown,{name:"entiempo",cols:"two wide",id:"entiempo",label:"Llegaste en Tiempo ?",valor:this.state.entiempo,valores:[{name:"No",value:"0"},{name:"Si",value:"1"}],onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",null,React.createElement("p",null,React.createElement("b",null,"Participación de Grupo"))),React.createElement("div",{className:"two fields"},React.createElement(SelectDropDown,{name:"participa",cols:"two wide",id:"participa",label:"Si/No",valor:this.state.participa,valores:[{name:"No",value:"0"},{name:"Si",value:"1"}],onChange:this.handleInputChangeData.bind(this)}),React.createElement(InputField,{id:"tema",name:"tema",label:"Tema",cols:"eleven wide",valor:this.state.tema,onChange:this.handleInputChangeData.bind(this)}),React.createElement(SelectDropDown,{name:"imparte",cols:"two wide",id:"imparte",label:"Imparte",valor:this.state.imparte,valores:[{name:"Colmena",value:"0"},{name:"PDSF",value:"1"},{name:"N/A",value:"2"}],onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",{className:"three fields"},React.createElement(MultiSelect,{label:"Solicitud de Ingreso",name:"sol_ingreso",valores:this.state.catSolicitud}),React.createElement(MultiSelect,{label:"Nuevo Ingreso",name:"ingreso_nuevo",valores:this.state.catSolicitud}),React.createElement(MultiSelect,{label:"Reingreso",name:"reingreso",valores:this.state.catSolicitud})),React.createElement("div",null,React.createElement("p",{className:"0"==this.state.mab?"hidden":""},React.createElement("b",null,"MAB"))),React.createElement("div",{className:"0"==this.state.mab?"hidden":""},React.createElement("div",{className:"three fields"},e,a,n)),React.createElement("div",{className:"two fields"},React.createElement(InputField,{id:"notas",name:"notas",label:"Notas",cols:"sixteen wide",valor:this.state.notas,onChange:this.handleInputChangeData.bind(this)})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui bottom primary basic button",type:"button",onClick:this.handleUpdateColmena.bind(this)},React.createElement("i",{className:"send icon",onClick:this.handleUpdateColmena.bind(this)}),"Actualizar"))))),React.createElement("div",{className:"segment"},React.createElement(Catalogo,{valores:this.state.catacreditados,asistencia:this.state.catAsistencia,incidencia:this.state.catIncidencia,opcion:this.state.catOpciones,verificacion:this.state.catVerificacion,handleBoton:this.handleBoton})),React.createElement("div",{className:"ui vertical segment right aligned"},React.createElement("div",{className:"field"},React.createElement("button",{className:"ui button primary basic button",type:"button",onClick:this.handleSubmit.bind(this)},React.createElement("i",{className:"send icon",onClick:this.handleSubmit.bind(this)})," ",this.state.boton," "))))))}}]),a}();ReactDOM.render(React.createElement(Captura,null),document.getElementById("root"));