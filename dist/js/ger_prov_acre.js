"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Steps = function (_React$Component) {
    _inherits(Steps, _React$Component);

    function Steps(props) {
        _classCallCheck(this, Steps);

        return _possibleConstructorReturn(this, (Steps.__proto__ || Object.getPrototypeOf(Steps)).call(this, props));
    }

    _createClass(Steps, [{
        key: "render",
        value: function render() {
            var _this2 = this;

            return React.createElement(
                "a",
                { className: this.props.valor == this.props.value ? "active step" : "step", value: this.props.value, onClick: function onClick(e, value) {
                        return _this2.props.onClick(e, value);
                    } },
                React.createElement("i", { className: this.props.icon }),
                React.createElement(
                    "div",
                    { className: "content" },
                    React.createElement(
                        "div",
                        { className: "title" },
                        this.props.titulo
                    ),
                    React.createElement("div", { className: "description" })
                )
            );
        }
    }]);

    return Steps;
}(React.Component);

var InputField = function (_React$Component2) {
    _inherits(InputField, _React$Component2);

    function InputField(props) {
        _classCallCheck(this, InputField);

        return _possibleConstructorReturn(this, (InputField.__proto__ || Object.getPrototypeOf(InputField)).call(this, props));
    }

    _createClass(InputField, [{
        key: "render",
        value: function render() {
            var _this4 = this;

            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            var tipo = this.props.id == "password" ? "password" : "text";
            var may = this.props.mayuscula == "true" ? 'mayuscula' : '';
            return React.createElement(
                "div",
                { className: cols },
                React.createElement(
                    "label",
                    null,
                    this.props.label
                ),
                React.createElement(
                    "div",
                    { className: "ui icon input" },
                    React.createElement("input", { className: may, id: this.props.id, name: this.props.id, type: tipo, readOnly: this.props.readOnly, value: this.props.valor, placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this4.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputField;
}(React.Component);

var InputFieldFind = function (_React$Component3) {
    _inherits(InputFieldFind, _React$Component3);

    function InputFieldFind(props) {
        _classCallCheck(this, InputFieldFind);

        var _this5 = _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));

        _this5.state = {
            value: ''
        };
        return _this5;
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this6 = this;

            //                        <input id={this.props.id} name={this.props.id} type="text" placeholder={this.props.placeholder} onChange={event => this.setState({ value: event.target.value})}/>
            //                         <i className={this.props.icons} onClick={event => this.props.onClick(event, this.state.value)}></i>

            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            return React.createElement(
                "div",
                { className: cols },
                React.createElement(
                    "label",
                    null,
                    this.props.label
                ),
                React.createElement(
                    "div",
                    { className: "ui icon input" },
                    React.createElement("input", { id: this.props.id, name: this.props.id, value: this.props.valor, type: "text", placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this6.props.onChange(event);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this6.props.onClick(event, _this6.props.valor, _this6.props.id);
                        } })
                )
            );
        }
    }]);

    return InputFieldFind;
}(React.Component);

var InputFieldNumber = function (_React$Component4) {
    _inherits(InputFieldNumber, _React$Component4);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: "render",
        value: function render() {
            var _this8 = this;

            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field";
            return React.createElement(
                "div",
                { className: cols },
                React.createElement(
                    "label",
                    null,
                    this.props.label
                ),
                React.createElement(
                    "div",
                    { className: "ui labeled input" },
                    React.createElement(
                        "div",
                        { className: "ui label" },
                        "$"
                    ),
                    React.createElement("input", { type: "text", id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, value: this.props.valor, onChange: function onChange(event) {
                            return _this8.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var Mensaje = function (_React$Component5) {
    _inherits(Mensaje, _React$Component5);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this9 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this9.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this9;
    }

    _createClass(Mensaje, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { className: "ui mini test modal scrolling transition hidden" },
                React.createElement(
                    "div",
                    { className: "ui icon header" },
                    React.createElement("i", { className: this.state.icon }),
                    this.state.titulo
                ),
                React.createElement(
                    "div",
                    { className: "center aligned content " },
                    React.createElement(
                        "p",
                        null,
                        this.state.pregunta
                    )
                ),
                React.createElement(
                    "div",
                    { className: "actions" },
                    React.createElement(
                        "div",
                        { className: "ui red cancel basic button" },
                        React.createElement("i", { className: "remove icon" }),
                        " No "
                    ),
                    React.createElement(
                        "div",
                        { className: "ui green ok basic button" },
                        React.createElement("i", { className: "checkmark icon" }),
                        " Si "
                    )
                )
            );
        }
    }]);

    return Mensaje;
}(React.Component);

/*
function Calendar(props){
    return (
        <div className="ui calendar" id={props.name}>
            <div className="field">
            <label>{props.label}</label>
            <div className="ui input left icon">
                <i className="calendar icon"></i>
                <input type="text" name={props.name} id={props.name} value={props.valor} placeholder="Fecha"/>
            </div>
            </div>
        </div>
    );
}
*/

var Calendar = function (_React$Component6) {
    _inherits(Calendar, _React$Component6);

    function Calendar(props) {
        _classCallCheck(this, Calendar);

        return _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));
    }

    _createClass(Calendar, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { className: "ui calendar", id: this.props.name },
                React.createElement(
                    "div",
                    { className: "field" },
                    React.createElement(
                        "label",
                        null,
                        this.props.label
                    ),
                    React.createElement(
                        "div",
                        { className: "ui input left icon" },
                        React.createElement("i", { className: "calendar icon" }),
                        React.createElement("input", { ref: "myCalen", type: "text", name: this.props.name, id: this.props.name, placeholder: "Fecha" })
                    )
                )
            );
        }
    }]);

    return Calendar;
}(React.Component);

/*
class Calendar extends React.Component{
    constructor(props){
        super(props);
        this.handleChange = this.handleChange.bind(this);
    }


    handleChange(e){
        //console.log(e);
        //this.props.onChange(e);

    }

    componentDidMount() {
        $(ReactDOM.findDOMNode(this.refs.myCalen)).on('onChange',this.handleChange);
    }

    render(){
        return(
            <div className="ui calendar" id={this.props.name}>
                <div className="field">
                    <label>{this.props.label}</label>
                    <div className="ui input left icon">
                        <i className="calendar icon"></i>
                        <input ref="myCalen" type="text" name={this.props.name} id={this.props.name} value={this.props.valor} placeholder="Fecha" onChange={this.handleChange}/>
                    </div>
                </div>
            </div>
        );
    }

}
*/

var SelectDropDown = function (_React$Component7) {
    _inherits(SelectDropDown, _React$Component7);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this11 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this11.state = {
            value: ""
        };
        _this11.handleSelectChange = _this11.handleSelectChange.bind(_this11);
        return _this11;
    }

    _createClass(SelectDropDown, [{
        key: "handleSelectChange",
        value: function handleSelectChange(event) {
            this.props.onChange(event);
        }
    }, {
        key: "componentDidMount",
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myDrop)).on('change', this.handleSelectChange);
        }
    }, {
        key: "render",
        value: function render() {
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            var listItems = this.props.valores.map(function (valor) {
                return React.createElement(
                    "div",
                    { className: "item", "data-value": valor.value },
                    valor.name
                );
            });
            return React.createElement(
                "div",
                { className: cols },
                React.createElement(
                    "label",
                    null,
                    this.props.label
                ),
                React.createElement(
                    "div",
                    { className: "ui fluid search selection dropdown" },
                    React.createElement("input", { type: "hidden", ref: "myDrop", value: this.props.valor, name: this.props.id, onChange: this.handleSelectChange }),
                    React.createElement("i", { className: "dropdown icon" }),
                    React.createElement(
                        "div",
                        { className: "default text" },
                        "Seleccione"
                    ),
                    React.createElement(
                        "div",
                        { className: "menu" },
                        listItems
                    )
                )
            );
        }
    }]);

    return SelectDropDown;
}(React.Component);

var SelectOption = function (_React$Component8) {
    _inherits(SelectOption, _React$Component8);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this12 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this12.state = {
            value: ""
        };
        return _this12;
    }

    _createClass(SelectOption, [{
        key: "handleSelectChange",
        value: function handleSelectChange(event) {
            this.props.onChange(event);
        }
    }, {
        key: "componentDidMount",
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myCombo)).on('change', this.handleSelectChange.bind(this));
        }
    }, {
        key: "render",
        value: function render() {
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " inline field ";

            var listItems = this.props.valores.map(function (valor) {
                return React.createElement(
                    "option",
                    { value: valor.value },
                    valor.name
                );
            });
            return React.createElement(
                "div",
                { className: cols },
                React.createElement(
                    "label",
                    null,
                    this.props.label
                ),
                React.createElement(
                    "select",
                    { className: "ui fluid dropdown", ref: "myCombo", name: this.props.id, id: this.props.id, onChange: this.handleSelectChange.bind(this) },
                    React.createElement(
                        "option",
                        { value: this.props.valor },
                        "Seleccione"
                    ),
                    listItems
                )
            );
        }
    }]);

    return SelectOption;
}(React.Component);

/*
* Imprime los encabezados de una tabla
*/


function Lista(props) {
    var cadenas = props.enca;
    var contador = 0;
    var listItems = cadenas.map(function (encabezado) {
        return React.createElement(
            "th",
            { key: contador++ },
            encabezado
        );
    });
    return React.createElement(
        "tr",
        null,
        listItems
    );
}

var RecordDetalle = function (_React$Component9) {
    _inherits(RecordDetalle, _React$Component9);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        return _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));
    }

    _createClass(RecordDetalle, [{
        key: "render",
        value: function render() {
            //let checked = this.props.registro.activo ? <i className="green checkmark icon"></i> : '' ;
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idacreditado
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.acreditado
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idsucursal
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.numero_cuenta
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.base
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.interes
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idpoliza
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var RecordDetalle2 = function (_React$Component10) {
    _inherits(RecordDetalle2, _React$Component10);

    function RecordDetalle2(props) {
        _classCallCheck(this, RecordDetalle2);

        return _possibleConstructorReturn(this, (RecordDetalle2.__proto__ || Object.getPrototypeOf(RecordDetalle2)).call(this, props));
    }

    _createClass(RecordDetalle2, [{
        key: "render",
        value: function render() {
            //let checked = this.props.registro.activo ? <i className="green checkmark icon"></i> : '' ;
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.dia,
                    " "
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.idsucursal
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.idcredito
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.capital_saldo,
                    " "
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.pago_total
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.pago_capital
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.int_pag
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.iva_pag
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.saldo_actual
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.interes
                ),
                React.createElement(
                    "td",
                    null,
                    " ",
                    this.props.registro.iva
                )
            );
        }
    }]);

    return RecordDetalle2;
}(React.Component);

var Table2 = function (_React$Component11) {
    _inherits(Table2, _React$Component11);

    function Table2(props) {
        _classCallCheck(this, Table2);

        return _possibleConstructorReturn(this, (Table2.__proto__ || Object.getPrototypeOf(Table2)).call(this, props));
    }

    _createClass(Table2, [{
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            var nombre = this.name;

            datos.forEach(function (record) {
                rows.push(React.createElement(RecordDetalle2, { registro: record }));
            });
            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                "div",
                null,
                React.createElement(
                    "table",
                    { className: "ui selectable celled red table" },
                    React.createElement(
                        "thead",
                        null,
                        React.createElement(Lista, { enca: ['Fecha', 'Sucursal', 'Credito', 'Capital vigente', 'Pago total', 'Pago capital', 'Int. pag', 'IVA pag', 'Saldo capital', 'Interes diario', 'IVA diario'] })
                    ),
                    React.createElement(
                        "tbody",
                        null,
                        rows
                    )
                )
            );
        }
    }]);

    return Table2;
}(React.Component);

var Table = function (_React$Component12) {
    _inherits(Table, _React$Component12);

    function Table(props) {
        _classCallCheck(this, Table);

        return _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));
    }

    _createClass(Table, [{
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            var nombre = this.name;

            datos.forEach(function (record) {
                rows.push(React.createElement(RecordDetalle, { registro: record }));
            });
            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                "div",
                null,
                React.createElement(
                    "table",
                    { className: "ui selectable celled red table" },
                    React.createElement(
                        "thead",
                        null,
                        React.createElement(Lista, { enca: ['Fecha', 'No', 'Acreditada', 'Sucursal', 'Cuenta', 'Imp. base', 'Interes', 'Poliza'] })
                    ),
                    React.createElement(
                        "tbody",
                        null,
                        rows
                    )
                )
            );
        }
    }]);

    return Table;
}(React.Component);

/*
class CheckBox extends React.Component {
    constructor(props){
        super(props);
    }

    render() {
        let checked = this.props.valor === '1' ? 'ui checkbox checked': 'ui checkbox';
        let valchecked = this.props.valor === '1' ? 'true': 'false';
        return(
            <div className="field">
              <label>Seleccione</label>
              <div className="four fields">
             <div className="ten wide inline field">
                <div className={checked} onClick={event => {console.log(event.target.name); this.props.onChange(event)}}>
                    <input type="checkbox" name={this.props.name} tabindex="0" value={valchecked} class="hidden" onClick={event => {console.log(event.target.name); this.props.onChange(event)}}/>
                    <label>{this.props.titulo}</label>
                </div>
            </div>      
            </div>      
            </div>  
        );
    }
}
*/

var Captura = function (_React$Component13) {
    _inherits(Captura, _React$Component13);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this17 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this17.state = {
            blnActivar: true,
            fecha: "",
            fecha_pago: "",
            csrf: "", message: "",
            stepno: 1,
            idfecha: "",
            catfechas: [],
            idacreditado: "",
            nombre_acreditado: "",
            catsocio: [],
            idcredito: "",
            catpagare: [],
            prov_ahorro: [],
            prov_credito: [],
            statusmessage: 'ui floating hidden message',
            boton: 'Enviar', btnAutoriza: 'Autorizar', icons1: 'inverted circular search link icon'
        };
        return _this17;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            /*
             $.ajax({
                url: base_url + '/api/GeneralD1/get_provisiones_fecha',
                type: 'GET',
                dataType: 'json',
                success:function(response) {
                    this.setState({
                        catfechas: response.catfechas,
                        catsocio: response.catsocio
                    });
                  }.bind(this),
                error: function(xhr, status, err) {
                    console.log('error');
                    console.log('error', xhr);
                    console.log('error',status);
                    console.log('error',err);
                }.bind(this)
            });
            */
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));

            //Consultas a cartera
            if (name === "fecha") {
                alert("Fecha");
            }

            if (name === "idacreditado") {
                if (event.target.value != "") {
                    var link = "";
                    if (name === "idacreditado") {
                        link = "get_acreditado_pagares";
                    }
                    link = link + "/" + event.target.value;
                    $.ajax({
                        url: base_url + '/api/CarteraD1/' + link,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if (name === "idacreditado") {

                                //this.asignaAcreditado(response.acreditado[0]);
                                this.setState({
                                    catpagare: response.catpagare
                                });
                            }
                        }.bind(this),
                        error: function (xhr, status, err) {
                            console.log('error');
                        }.bind(this)
                    });
                }
            }
            if (name === "idcolmena") {
                var _link = "";
                if (name === "idcolmena") {
                    _link = "get_colmena_acreditados_get";
                }

                var forma = this;
                _link = _link + "/" + event.target.value;
                var object = {
                    url: base_url + '/api/GeneralD1/' + _link,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    this.setState({
                        cat_colmena_acreditado: response.result
                        //idgrupo: ""
                    });
                    forma.autoReset();
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        csrf: response.newtoken,
                        message: response.message,
                        statusmessage: 'ui negative floating message'
                    });
                    forma.autoReset();
                });
            }
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();

            $('.ui.form.formgen').form('validate form');
            var valida = $('.ui.form.formgen').form('is valid');
            if (valida == true) {
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = this.state.boton === 'Enviar' ? 'POST' : 'PUT';
                var forma = this;

                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {

                        var object = {
                            url: base_url + 'api/CarteraD1/add_credito',
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            if (forma.state.boton === 'Enviar') {
                                var idcredito = response.insert_id;
                                forma.setState({
                                    idcredito: idcredito,
                                    idexiste: idcredito,
                                    csrf: response.newtoken,
                                    message: response.message.concat(' ' + response.insert_id.toString()),
                                    statusmessage: 'ui positive floating message ',
                                    boton: 'Enviar'
                                });
                            } else {
                                forma.setState({
                                    csrf: response.newtoken,
                                    message: response.message,
                                    statusmessage: 'ui positive floating message ',
                                    boton: 'Actualizar'
                                });
                            }
                            /*
                            console.log(idcredito);
                            console.log(forma.state.idcredito);
                            */

                            forma.autoReset();
                        }, function reject(reason) {
                            var response = validaError(reason);
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            forma.autoReset();
                        });
                    }
                }).modal('show');
            } else {
                this.setState({
                    message: 'Datos incompletos!',
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset();
            }
        }
    }, {
        key: "autoReset",
        value: function autoReset() {
            var self = this;
            window.clearTimeout(self.timeout);
            if (self.state.message != "") {
                this.timeout = window.setTimeout(function () {
                    self.setState({ message: '', statusmessage: 'ui message hidden' });
                }, 5000);
            }
        }
    }, {
        key: "handleClickNext",
        value: function handleClickNext(e) {
            if (this.state.stepno < 2) {
                this.setState(function (prevState, props) {
                    return {
                        stepno: prevState.stepno + 1
                    };
                });
            }
        }
    }, {
        key: "handleClickPrevious",
        value: function handleClickPrevious(e) {
            if (this.state.stepno > 1) {
                this.setState(function (prevState, props) {
                    return {
                        stepno: prevState.stepno - 1
                    };
                });
            }
        }
    }, {
        key: "asignaAcreditado",
        value: function asignaAcreditado(data) {
            var today = new Date();

            this.setState({
                nosocio: data.idacreditado,
                nombre: data.nombre,
                idpagare: 'F' + today.getFullYear() + '' + (today.getMonth() + 1) + '' + today.getDate() + '-' + data.idacreditado
            });

            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                idgrupo: data.idgrupo
            });
        }
    }, {
        key: "asignaSolicitudCreditoNew",
        value: function asignaSolicitudCreditoNew() {
            this.setState({
                blnActivar: true,
                fecha: '', fecha_pago: '',
                prov_ahorro: [],
                prov_credito: [],
                boton: "Enviar"
            });

            $('.get.soling .ui.dropdown').dropdown('clear');
        }
    }, {
        key: "asignaSolicitudCredito",
        value: function asignaSolicitudCredito(data) {

            //let today = new Date();
            var fechaAlta = moment(data.fecha).format('DD/MM/YYYY');
            var fechaPago = moment(data.fecha_pago).format('DD/MM/YYYY');
            var fechaEntregaCol = moment(data.fecha_entrega_col).format('DD/MM/YYYY');

            /*        let fechaAlta = new Date(data.fecha);
                    fechaAlta= fechaAlta.getDate() + '/' + ( fechaAlta.getMonth() + 1) + '/' + fechaAlta.getFullYear();
                    let fechaPago = new Date(data.fecha_pago);
                    fechaPago= fechaPago.getDate() + '/' + ( fechaPago.getMonth() + 1) + '/' + fechaPago.getFullYear();
              */

            $('#fecha').val(fechaAlta);
            $('#fecha_pago').val(fechaPago);
            $('#fecha_entrega_col').val(fechaEntregaCol);
            this.setState({

                fecha: fechaAlta,
                fecha_pago: fechaPago
            });
            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                fecha: fechaAlta,
                fecha_pago: fechaPago
            });
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value) {
            if (value != "") {
                //idcredito: value,
                this.setState({
                    icons1: 'spinner circular inverted blue loading icon' });

                var forma = this;
                var object = {
                    url: base_url + 'api/CarteraD1/get_acreditado_pagares/' + value,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {

                    forma.setState({
                        catpagare: response.catpagare,
                        nombre_acreditado: response.cat_acreditado.nombre_acreditado,
                        message: response.message,
                        statusmessage: 'ui positive floating message ',
                        boton: 'Actualizar',
                        blnActivar: false,
                        icons1: 'inverted circular search link icon'
                    });
                    forma.autoReset();
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        csrf: response.newtoken,
                        message: response.message,
                        nombre_acreditado: "",
                        statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                    });
                    forma.autoReset();
                });
            } else {
                var _forma = this;
                _forma.setState({
                    message: "Ingrese el número de la solicitud de crédito",
                    statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                });
                _forma.autoReset();
            }
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            if (e === 2) {
                var forma = this;
                $.ajax({
                    url: base_url + '/api/CarteraD1/get_provision_ahorro_acre/' + this.state.idacreditado + '/' + this.state.idcredito,
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {

                        forma.setState({
                            prov_ahorro: response.prov_ahorro,
                            prov_credito: response.prov_credito
                        });
                    }.bind(this),
                    error: function (xhr, status, err) {
                        console.log('error');
                    }.bind(this)
                });
            } else if (e > 2 && e < 6) {
                var today = new Date();
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');

                //alert(this.state.);

                var link = "";
                if (e === 3) {
                    link = "pdf_prev_ahorro";
                    link = link + "/" + this.state.idfecha;
                } else if (e === 4) {
                    link = "pdf_prev_ahorro";
                    link = link + "/" + this.state.idfecha;
                } else if (e === 5) {
                    link = "pdf_credito_prov_val";
                    link = link + "/" + this.state.idcredito;
                }
                var a = document.createElement('a');
                a.href = base_url + 'api/ReportD1/' + link;
                a.target = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }
        }
    }, {
        key: "handleState",
        value: function handleState(value, e) {
            this.setState({
                stepno: value
            });
        }
    }, {
        key: "render",
        value: function render() {
            var _this18 = this;

            var today = new Date();
            return React.createElement(
                "div",
                null,
                React.createElement(
                    "div",
                    { className: "ui segment vertical " },
                    React.createElement(
                        "div",
                        { className: "row" },
                        React.createElement(
                            "h3",
                            { className: "ui rojo header" },
                            "Provisi\xF3n del Acreditado"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui secondary menu" },
                        React.createElement(
                            "div",
                            { className: "ui  basic icon buttons" },
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Nuevo Registro" },
                                React.createElement("i", { className: "plus square outline icon", onClick: this.handleButton.bind(this, 1) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Mostrar" },
                                React.createElement("i", { className: "search icon", onClick: this.handleButton.bind(this, 2) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Pdf ahorros" },
                                React.createElement("i", { className: "search icon", onClick: this.handleButton.bind(this, 3) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Pdf creditos" },
                                React.createElement("i", { className: "search icon", onClick: this.handleButton.bind(this, 4) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "VALIDACI\xD3N DEL CREDITO" },
                                React.createElement("i", { className: "search icon", onClick: this.handleButton.bind(this, 5) })
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "right menu" },
                            React.createElement(
                                "div",
                                { className: "item ui fluid category search" },
                                React.createElement(
                                    "div",
                                    { className: "ui icon input" },
                                    React.createElement("input", { className: "prompt", type: "text", placeholder: "Buscar Nombre" }),
                                    React.createElement("i", { className: "search link icon" })
                                ),
                                React.createElement("div", { className: "results" })
                            )
                        )
                    )
                ),
                React.createElement(Mensaje, null),
                React.createElement(
                    "div",
                    { className: this.state.statusmessage },
                    React.createElement(
                        "p",
                        null,
                        React.createElement(
                            "b",
                            null,
                            this.state.message
                        )
                    ),
                    React.createElement("i", { className: "close icon", onClick: function onClick(event) {
                            return _this18.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get soling" },
                    React.createElement(
                        "form",
                        { className: "ui form formgen", ref: "form", onSubmit: this.handleSubmit.bind(this), method: "post" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "two fields" },
                            React.createElement(InputFieldFind, { icons: this.state.icons1, id: "idacreditado", cols: "two wide", label: "No. acreditada", placeholder: "Buscar", valor: this.state.idacreditado, onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                            React.createElement(InputField, { id: "nombre_acreditado", label: "Nombre", readOnly: "readOnly", valor: this.state.nombre_acreditado, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: "two fields" },
                            React.createElement(SelectDropDown, { id: "idcredito", label: "Pagare", valor: this.state.idcredito, valores: this.state.catpagare, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: "ui mini steps" },
                            React.createElement(Steps, { valor: this.state.stepno, value: "1", icon: "check circle outline icon", titulo: "Ahorros", onClick: this.handleState.bind(this, 1) }),
                            React.createElement(Steps, { valor: this.state.stepno, value: "2", icon: "check circle outline icon", titulo: "Creditos", onClick: this.handleState.bind(this, 2) }),
                            React.createElement(Steps, { valor: this.state.stepno, value: "3", icon: "check circle outline icon", titulo: "VALIDACION", onClick: this.handleState.bind(this, 3) })
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "get prov_aho" },
                    React.createElement(
                        "form",
                        { className: "ui form formaut", ref: "formaut" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 1 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                null,
                                React.createElement(Table, { datos: this.state.prov_ahorro, name: "ahorro" })
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "get prov_cred" },
                    React.createElement(
                        "form",
                        { className: "ui form formaut", ref: "formaut" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 2 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                null,
                                React.createElement(Table2, { datos: this.state.prov_credito, name: "credito" })
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "ui vertical segment" },
                    React.createElement(
                        "div",
                        { className: "ui vertical segment right aligned" },
                        React.createElement(
                            "button",
                            { className: "ui labeled icon positive basic button", onClick: this.handleClickPrevious.bind(this) },
                            React.createElement("i", { className: "left chevron icon" }),
                            " Anterior "
                        ),
                        React.createElement(
                            "button",
                            { className: "ui right labeled icon positive basic button", onClick: this.handleClickNext.bind(this) },
                            "Siguiente ",
                            React.createElement("i", { className: "right chevron icon" }),
                            " "
                        )
                    )
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

ReactDOM.render(React.createElement(Captura, null), document.getElementById('root'));

$('.ui.search').search({
    type: 'category',
    minCharacters: 8,
    apiSettings: {
        url: base_url + 'api/CarteraD1/find_acreditados?q={query}',
        onResponse: function onResponse(Response) {
            var response = {
                results: {}
            };
            if (!Response || !Response.result) {
                return;
            }
            $.each(Response.result, function (index, item) {
                var sucursal = item.idacreditado || 'Sin asignar',
                    maxResults = 8;
                if (index >= maxResults) {
                    return false;
                }
                // create new language category
                if (response.results[sucursal] === undefined) {
                    response.results[sucursal] = {
                        name: sucursal,
                        results: []
                    };
                }
                // add result to category
                response.results[sucursal].results.push({
                    title: item.nombre,
                    description: item.idcredito + ' : ' + item.idpagare
                });
            });
            return response;
        }
    }
});