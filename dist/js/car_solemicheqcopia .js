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
                    this.props.registro.numero
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_vence
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.capital
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.interes
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.iva
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.garantia
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.total
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var Table = function (_React$Component10) {
    _inherits(Table, _React$Component10);

    function Table(props) {
        _classCallCheck(this, Table);

        return _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));
    }

    _createClass(Table, [{
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;

            /*
            let pageclick = this.props.onClickPage;
            let configpage = {
                total_paginas: this.props.datos.total_paginas,
                pag_actual: this.props.datos.pag_actual
            }
            */
            //    if (datos instanceof Array === false){
            datos.forEach(function (record) {
                rows.push(React.createElement(RecordDetalle, { registro: record }));
            });
            //    }
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
                        React.createElement(Lista, { enca: ['Semana', 'Vencimiento', 'Capital', 'Interes', 'IVA', 'Garantia', 'Total'] })
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

var Captura = function (_React$Component11) {
    _inherits(Captura, _React$Component11);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this15 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this15.state = {
            idfecha: '', catfechas: [],
            csrf: "", message: "",
            statusmessage: 'ui floating hidden message',
            stepno: 1,
            boton: 'Enviar', icons1: 'inverted circular search link icon'
        };
        return _this15;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {

            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            $.ajax({
                url: base_url + '/api/GeneralD1/get_solicitud_cheques',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catfechas: response.catfechas
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error');
                }.bind(this)
            });
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));

            if (name === "nivel") {
                this.setState(function (prevState, props) {
                    return {
                        monto: prevState.nivel * 1000
                    };
                });
            }
            //Consultas a cartera
            if (name === "idacreditado") {
                if (event.target.value != "") {
                    var _link = "";
                    if (name === "idacreditado") {
                        _link = "get_acreditado";
                    }
                    _link = _link + "/" + event.target.value;
                    $.ajax({
                        url: base_url + '/api/CarteraD1/' + _link,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if (name === "idacreditado") {
                                this.asignaAcreditado(response.acreditado[0]);
                            }
                        }.bind(this),
                        error: function (xhr, status, err) {
                            console.log('error');
                        }.bind(this)
                    });
                }
            }
            if (name === "idcolmena") {
                var _link2 = "";
                if (name === "idcolmena") {
                    _link2 = "get_colmena_acreditados_get";
                }

                var forma = this;
                _link2 = _link2 + "/" + event.target.value;
                var object = {
                    url: base_url + '/api/GeneralD1/' + _link2,
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
            var valida = true;
            if (valida == true) {
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');

                var tipo = this.state.boton === 'Enviar' ? 'POST' : 'PUT';
                var forma = this;

                link = "pdf_emision_cheques";

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
                                var _idcredito = response.insert_id;
                                forma.setState({
                                    idcredito: _idcredito,
                                    idexiste: _idcredito,
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
        key: "handleSubmitAutoriza2",
        value: function handleSubmitAutoriza2(event) {
            event.preventDefault();
            var valida = true; //$('.ui.form.formaut').form('is valid');

            if (valida == true) {
                var $form = $('.get.solingaut form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var forma = this;
                $('.test.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        $.ajax({
                            url: base_url + 'auth/Autho/validateAutUser/10031',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            },
                            success: function (response) {
                                if (response.status === 'OK') {
                                    forma.setState({
                                        csrf: response.newtoken,
                                        message: response.message,
                                        statusmessage: 'ui positive floating message ',
                                        btnAutoriza: 'Autoriza2'
                                    });

                                    if (valida == true) {
                                        var $form = $('.get.soling form'),
                                            allFields = $form.form('get values'),
                                            token = $form.form('get value', 'csrf_bancomunidad_token');
                                        var tipo = 'PUT';
                                        var _forma = this;
                                        $('.test.modal').modal({
                                            closable: false,
                                            onApprove: function onApprove() {
                                                $.ajax({
                                                    url: base_url + 'api/CarteraD1/aut_credito',
                                                    type: tipo,
                                                    dataType: 'json',
                                                    data: {
                                                        csrf_bancomunidad_token: token,
                                                        data: allFields
                                                    },
                                                    success: function (response) {
                                                        if (response.status === 'OK') {
                                                            _forma.setState({
                                                                csrf: response.newtoken,
                                                                //message: response.message.concat(' ' + response.insert_id.toString()),
                                                                message: response.message,
                                                                statusmessage: 'ui positive floating message ',
                                                                btnAutoriza: 'Autorizar'
                                                            });
                                                        }
                                                    }.bind(this),
                                                    error: function (xhr, status, err) {
                                                        if (xhr.status === 404) {
                                                            _forma.setState({
                                                                csrf: xhr.responseJSON.newtoken,
                                                                message: xhr.responseJSON.message,
                                                                statusmessage: 'ui negative floating message'
                                                            });
                                                        } else if (xhr.status === 409) {
                                                            var cadena = "";
                                                            var pos = xhr.responseText.indexOf('{"status"');
                                                            if (pos !== 0) {
                                                                cadena = xhr.responseText.substring(pos);
                                                            }
                                                            var arreglo = JSON.parse(cadena);
                                                            _forma.setState({
                                                                csrf: arreglo.newtoken,
                                                                message: arreglo.message,
                                                                statusmessage: 'ui negative floating message'
                                                            });
                                                        }
                                                    }.bind(this)
                                                });
                                            }
                                        }).modal('show');
                                    } else {
                                        this.setState({
                                            message: 'Datos incompletos!',
                                            statusmessage: 'ui negative floating message'
                                        });
                                    }
                                }
                            }.bind(this),
                            error: function (xhr, status, err) {
                                if (xhr.status === 404) {
                                    forma.setState({
                                        csrf: xhr.responseJSON.newtoken,
                                        message: xhr.responseJSON.message,
                                        statusmessage: 'ui negative floating message'
                                    });
                                } else if (xhr.status === 409) {
                                    var cadena = "";
                                    var pos = xhr.responseText.indexOf('{"status"');
                                    if (pos !== 0) {
                                        cadena = xhr.responseText.substring(pos);
                                    }
                                    var arreglo = JSON.parse(cadena);
                                    forma.setState({
                                        csrf: arreglo.newtoken,
                                        message: arreglo.message,
                                        statusmessage: 'ui negative floating message'
                                    });
                                }
                            }.bind(this)

                        });
                    }
                }).modal('show');
            } else {
                this.setState({
                    message: 'Datos incompletos!',
                    statusmessage: 'ui negative floating message'
                });
            }
        }
    }, {
        key: "handleSubmitAutoriza",
        value: function handleSubmitAutoriza(event) {
            event.preventDefault();

            var forma = this;
            /*
            var object = {
                url: base_url + 'api/CarteraD1/checklist_completo/' + this.state.idcredito,
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                console(response.checklist);
                if (response.checklist[0] == undefined) {
                    forma.setState({
                        ischecklist: true,
                        checklist: response.checklist,
                        message: 'El CheckList no ha sido terminado, no es posible autorizar el crédito',
                        statusmessage: 'ui positive floating message ',
                    });
                } else {
                    forma.setState({
                        ischecklist: true,
                        checklist: response.checklist,
                        message: "Check List terminado 1",
                        statusmessage: 'ui negative floating message ',
                    });
                }
                forma.autoReset();
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
                    ischecklist: true,
                    message: "El CheckList no se ha encontrado en el credito.",
                    statusmessage: 'ui negative floating message',
                    icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
                });
                forma.clearData();
                forma.autoReset();
            });        
              alert(this.state.ischecklist);
            */
            var valida = true;
            if (valida == true) {
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'PUT';
                var _forma2 = this;
                $('.test.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        $.ajax({
                            url: base_url + 'api/CarteraD1/aut_credito',
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            },
                            success: function (response) {
                                if (response.status === 'OK') {
                                    _forma2.setState({
                                        csrf: response.newtoken,
                                        //message: response.message.concat(' ' + response.insert_id.toString()),
                                        message: response.message,
                                        statusmessage: 'ui positive floating message ',
                                        btnAutoriza: 'Autorizar'
                                    });
                                }
                            }.bind(this),
                            error: function (xhr, status, err) {
                                if (xhr.status === 404) {
                                    _forma2.setState({
                                        csrf: xhr.responseJSON.newtoken,
                                        message: xhr.responseJSON.message,
                                        statusmessage: 'ui negative floating message'
                                    });
                                } else if (xhr.status === 409) {
                                    var cadena = "";
                                    var pos = xhr.responseText.indexOf('{"status"');
                                    if (pos !== 0) {
                                        cadena = xhr.responseText.substring(pos);
                                    }
                                    var arreglo = JSON.parse(cadena);
                                    _forma2.setState({
                                        csrf: arreglo.newtoken,
                                        message: arreglo.message,
                                        statusmessage: 'ui negative floating message'
                                    });
                                }
                            }.bind(this)
                        });
                    }
                }).modal('show');
            } /*else {
                this.setState({
                    message: mensaje,
                    statusmessage: 'ui negative floating message'  
                });
              }
              */
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
            //idacreditado, nombre, idsucursal, edocivil, idactividad, direccion}
            var today = new Date();
            var miDir = this.setState({
                nosocio: data.idacreditado,
                nombre: data.nombre,
                //edocivil: data.edocivil,
                //idactividad: data.idactividad,
                idpagare: 'F' + today.getFullYear() + '' + (today.getMonth() + 1) + '' + today.getDate() + '-' + data.idacreditado,

                colmena_nombre: data.col_nombre,
                colmena_grupo: data.grupo_nombre,

                actividad_nombre: data.actividad_nombre,
                edocivil_nombre: data.edocivil_nombre,

                domicilio: data.direccion == null ? "" : data.direccion,
                idgrupo: data.idgrupo
            });

            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                idgrupo: data.idgrupo

                //actividad_nombre: data.actividad_nombre,
                //edocivil_nombre: data.edocivil_nombre
                //edocivil: data.edocivil,
                //idactividad: data.idactividad 
            });
        }
    }, {
        key: "asignaSolicitudCreditoNew",
        value: function asignaSolicitudCreditoNew() {
            this.setState({
                blnActivar: true,
                idacreditado: "", nosocio: "", domicilio: "",

                fecha: '', idexiste: "",
                edocivil_nombre: "",
                actividad_nombre: "",

                idpagare: "",
                nivel: "", monto: 0,

                proy_nombre: "", proy_descri: "", proy_lugar: "", proy_observa: "",

                idproducto: 1, idchecklist: 1,

                nocolmena: "", //idcolmena: "",
                colmena_nombre: "", grupo_nombre: "", idgrupo: "", blnGrupo: false,

                stepno: 1,
                //nombre: "",
                amortizaciones: [],
                usuario_aprov: null,

                boton: "Enviar"
            });

            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                //idcredito: "",
                nombre: "",
                idcolmena: "",
                nocolmena: "",
                nivel: "",
                idgrupo: "",
                idchecklist: 1
            });
            $('.get.soling .ui.dropdown').dropdown('clear');
        }
    }, {
        key: "asignaSolicitudCredito",
        value: function asignaSolicitudCredito(data) {

            //let today = new Date();
            var fechaAlta = moment(data.fecha).format('DD/MM/YYYY');

            $('#fecha').val(fechaAlta);
            this.setState({

                fecha: fechaAlta,
                //idacreditado: data.idacreditado,
                //nombre: data.nombre, 
                idpagare: data.idpagare,
                proy_nombre: data.proy_nombre,
                proy_descri: data.proy_descri,
                proy_lugar: data.proy_lugar,
                proy_observa: data.proy_observa,
                usuario_aprov: data.usuario_aprov,

                actividad_nombre: data.actividad_nombre,
                edocivil_nombre: data.edocivil_nombre,

                colmena_grupo: data.nomgrupo,
                colmena_nombre: data.nomcolmena,

                domicilio: data.direccion

            });
            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                fecha: fechaAlta,
                //idacreditado: data.idacreditado,
                idacreditado: data.acreditadoid,

                idcolmena: data.idcolmena,
                //edocivil: data.edocivil,
                //idactividad: data.idactividad,
                nivel: data.nivel,
                idgrupo: data.idgrupo,
                idchecklist: data.idchecklist,

                actividad_nombre: data.actividad_nombre,
                edocivil_nombre: data.edocivil_nombre,

                colmena_grupo: data.nomgrupo,
                colmena_nombre: data.nomcolmena,

                domicilio: data.direccion

            });
        }

        /*
            handleFindAcreditado(event,value) {
                this.setState({idcredito: value});
                $.ajax({
                    url: base_url + 'api/CarteraD1/acreditado/'+value,
                    type: 'GET',
                    dataType: 'json',
                    success:function(response) {
                        if (response.status === 'OK' ){
                            this.asignaAcreditado(response.acreditado[0]);
                            this.setState({
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                //boton: 'Actualizar'
                            });
                        }
                    }.bind(this),
                    error: function(xhr, status, err) {
                        if (xhr.status === 404) {
                            this.setState({
                                message: xhr.responseJSON.message,
                                statusmessage: 'ui negative floating message'  
                            });
                        }else if (xhr.status === 409) {
                            let cadena = "";
                            let pos = xhr.responseText.indexOf('{"status"');
                            if (pos !== 0) {
                                cadena = xhr.responseText.substring(pos);
                            }
                            let arreglo = JSON.parse(cadena);
                            this.setState({
                                message: arreglo.message,
                                statusmessage: 'ui negative floating message'  
                            });
                        }
                    }.bind(this)
                })                
            }
        */

    }, {
        key: "handleFind",
        value: function handleFind(event, value) {
            if (value != "") {
                //idcredito: value,
                this.setState({
                    icons1: 'spinner circular inverted blue loading icon' });

                var forma = this;
                var object = {
                    url: base_url + 'api/CarteraD1/get_solicitud_credito/' + value,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {

                    forma.asignaSolicitudCredito(response.solcredito[0]);
                    forma.setState({
                        intCheckListReq: response.checklist[0].total,
                        amortizaciones: response.amortizaciones,
                        message: response.message,
                        statusmessage: 'ui positive floating message ',
                        boton: 'Actualizar',
                        blnActivar: false,
                        icons1: 'inverted circular search link icon'
                    });
                    forma.setState(function (prevState, props) {
                        return {
                            idexiste: idcredito
                        };
                    });
                    forma.autoReset();
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        csrf: response.newtoken,
                        message: response.message,
                        statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                    });
                    forma.asignaSolicitudCreditoNew();
                    forma.autoReset();
                });
            } else {
                var _forma3 = this;
                _forma3.setState({
                    message: "Ingrese el número de la solicitud de crédito",
                    statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                });
                _forma3.autoReset();
            }
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            var today = new Date();
            var $form = $('.get.soling form'),
                allFields = $form.form('get values'),
                token = $form.form('get value', 'csrf_bancomunidad_token');

            if (e === 1) {
                var d = new Date();

                var _link3 = "";
                if (e === 1) {
                    _link3 = "pdf_emision_cheques";
                }
                _link3 = _link3 + "/" + this.state.idfecha;
                var a = document.createElement('a');
                a.href = base_url + 'api/ReportD1/' + _link3;
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
            var _this16 = this;

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
                            "Solicitud de emision de cheques"
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
                                { className: "ui button", "data-tooltip": "Emision de cheques" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
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
                            return _this16.setState({ message: '', statusmessage: 'ui message hidden' });
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
                            { className: this.state.blnActivar === false ? "disablediv" : "" },
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(SelectDropDown, { id: "idfecha", label: "Fecha de pago", valor: this.state.idfecha, valores: this.state.catfechas, onChange: this.handleInputChange.bind(this) })
                                )
                            )
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