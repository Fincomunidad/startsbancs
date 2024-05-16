"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var InputField = function (_React$Component) {
    _inherits(InputField, _React$Component);

    function InputField(props) {
        _classCallCheck(this, InputField);

        return _possibleConstructorReturn(this, (InputField.__proto__ || Object.getPrototypeOf(InputField)).call(this, props));
    }

    _createClass(InputField, [{
        key: "render",
        value: function render() {
            var _this2 = this;

            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
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
                    React.createElement("input", { className: may, id: this.props.id, readOnly: this.props.readOnly, name: this.props.id, type: "text", value: this.props.valor, placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this2.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputField;
}(React.Component);

var InputFieldFind = function (_React$Component2) {
    _inherits(InputFieldFind, _React$Component2);

    function InputFieldFind(props) {
        _classCallCheck(this, InputFieldFind);

        var _this3 = _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));

        _this3.state = {
            value: ''
        };
        return _this3;
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this4 = this;

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
                            return _this4.props.onChange(event);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this4.props.onClick(event, _this4.props.valor, _this4.props.id);
                        } })
                )
            );
        }
    }]);

    return InputFieldFind;
}(React.Component);

var InputFieldNumber = function (_React$Component3) {
    _inherits(InputFieldNumber, _React$Component3);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: "render",
        value: function render() {
            var _this6 = this;

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
                            return _this6.props.onChange(event);
                        }, onBlur: function onBlur(event) {
                            return _this6.props.onBlur(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var InputFieldNum = function (_React$Component4) {
    _inherits(InputFieldNum, _React$Component4);

    function InputFieldNum(props) {
        _classCallCheck(this, InputFieldNum);

        return _possibleConstructorReturn(this, (InputFieldNum.__proto__ || Object.getPrototypeOf(InputFieldNum)).call(this, props));
    }

    _createClass(InputFieldNum, [{
        key: "render",
        value: function render() {
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
                    React.createElement("input", { type: "text", id: this.props.id, name: this.props.id, value: this.props.valor })
                )
            );
        }
    }]);

    return InputFieldNum;
}(React.Component);

var Mensaje = function (_React$Component5) {
    _inherits(Mensaje, _React$Component5);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this8 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this8.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this8;
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

var Calendar = function (_React$Component6) {
    _inherits(Calendar, _React$Component6);

    function Calendar(props) {
        _classCallCheck(this, Calendar);

        var _this9 = _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));

        _this9.handleChange = _this9.handleChange.bind(_this9);
        return _this9;
    }

    _createClass(Calendar, [{
        key: "handleChange",
        value: function handleChange(e) {
            console.log(e);
        }
    }, {
        key: "componentDidMount",
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myCalen)).on('onChange', this.handleChange);
        }
    }, {
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
                        React.createElement("input", { ref: "myCalen", type: "text", name: this.props.name, id: this.props.name, value: this.props.valor, placeholder: "Fecha", onChange: this.handleChange })
                    )
                )
            );
        }
    }]);

    return Calendar;
}(React.Component);

var SelectDropDown = function (_React$Component7) {
    _inherits(SelectDropDown, _React$Component7);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this10 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this10.state = {
            value: ""
        };
        _this10.handleSelectChange = _this10.handleSelectChange.bind(_this10);
        return _this10;
    }

    _createClass(SelectDropDown, [{
        key: "handleSelectChange",
        value: function handleSelectChange(event) {
            this.props.onChange(event);
            this.setState({ value: event.target.value });
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
                    React.createElement("input", { type: "hidden", ref: "myDrop", value: this.value, name: this.props.id, onChange: this.handleSelectChange }),
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

        var _this11 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this11.state = {
            value: ""
        };
        return _this11;
    }

    _createClass(SelectOption, [{
        key: "handleSelectChange",
        value: function handleSelectChange(event) {
            this.props.onChange(event);
        }
    }, {
        key: "render",
        value: function render() {
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            var listItems = this.props.valores.map(function (valor) {
                return React.createElement(
                    "option",
                    { value: valor.value, "data-cuenta": valor.idcuenta },
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

var Captura = function (_React$Component9) {
    _inherits(Captura, _React$Component9);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this12 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this12.state = { catPagares: [], catBancos: [], activo: 0,
            idcredito: 0, idacreditado: '', nombre: '', idpagare: '', colmena: '', grupo: '', fecha_entrega: '', monto: 0,
            noxpcomprome: '', xpcomprometido: '', csrf: "", message: "", importe: 0, importeletra: '',
            statusmessage: 'ui floating hidden message', icons1: 'inverted circular search link icon'
        };
        _this12.handleClickMessage = _this12.handleClickMessage.bind(_this12);
        _this12.handleonBlur = _this12.handleonBlur.bind(_this12);
        return _this12;
    }

    _createClass(Captura, [{
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));
            if (name == "idpagare") {
                var idacre = this.state.idacreditado;
                var datPag = this.state.catPagares.filter(function (pag) {
                    return pag.value == value && pag.acreditadoid == idacre;
                });
                if (datPag != "") {
                    this.setState({ colmena: datPag[0].nomcolmena, grupo: datPag[0].nomgrupo,
                        fecha_entrega: datPag[0].fecha_entrega, monto: numeral(datPag[0].monto).format('0,0.00'),
                        noxpcomprome: datPag[0].noxpcomprome, xpcomprometido: numeral(datPag[0].xpcomprometido).format('0,0.00')
                    });
                } else {
                    this.setState({ colmena: '', grupo: '',
                        fecha_entrega: '', monto: 0, noxpcomprome: '', xpcomprometido: ''
                    });
                }
            }
        }
    }, {
        key: "handleonBlur",
        value: function handleonBlur(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            var nuevovalor = numeral(value).format('0,0.00');
            this.setState(_defineProperty({}, name, nuevovalor));
        }
    }, {
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value, name) {
            var idacreditado = 0;
            if (name == "idacreditado") {
                this.setState({ idacredita: value, icons1: 'spinner circular inverted blue loading icon' });
                idacreditado = value;
            }
            var forma = this;
            var object = {
                url: base_url + ("api/CarteraV1/getCreditos_vig/" + value),
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                forma.setState({
                    nombre: response.result[0].nombre, afavor: response.result[0].nombre, idcredito: response.result[0].idcredito,
                    catPagares: response.result, message: response.message, statusmessage: 'ui floating positive message', activo: 1,
                    icons1: 'inverted circular search link icon'
                });
                forma.autoReset();
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
                    catPagares: [], idpagare: '', colmena: '', grupo: '', fecha_entrega: '', monto: 0, idcredito: 0, noxpcomprome: '', xpcomprometido: '',
                    message: 'Acreditado sin pagares activos', nombre: '',
                    statusmessage: 'ui negative floating message',
                    icons1: 'inverted circular search link icon'
                });
                var $form = $('.get.disper form'),
                    Folio = $form.form('set values', { idpagare: '' });
                forma.autoReset();
            });
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            $('.ui.form.formdis').form({
                inline: true,
                on: 'blur',
                fields: {
                    idacreditado: {
                        identifier: 'idacreditado',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    idpagare: {
                        identifier: 'idpagare',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    colmena: {
                        identifier: 'colmena',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    grupo: {
                        identifier: 'grupo',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    fecha_entrega: {
                        identifier: 'fecha_entrega',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    noxpcomprome: {
                        identifier: 'noxpcomprome',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    xpcomprometido: {
                        identifier: 'xpcomprometido',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    }
                }
            });

            $('.ui.form.formdis').form('validate form');
            var valida = $('.ui.form.formdis').form('is valid');
            var idacre = this.state.idacreditado;
            var idpag = this.state.idpagare;
            var datPag = this.state.catPagares.filter(function (pag) {
                return pag.value == idpag && pag.acreditadoid == idacre;
            });
            if (datPag == "") {
                this.setState({ colmena: '', grupo: '',
                    fecha_entrega: '', monto: 0, noxpcomprome: '', xpcomprometido: ''
                });
                valida = false;
            }
            if (this.state.monto == 0 && this.state.xpcomprometido == 0) {
                valida = false;
            }
            this.setState({ message: '', statusmessage: 'ui floating hidden message' });
            if (valida == true) {
                var $form = $('.get.disper form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'PUT';
                var forma = this;
                var idcredito = this.state.idpagare;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ("api/CarteraV1/credito_con/" + idcredito),
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {

                            var idpag = forma.state.idpagare;
                            var catNew = forma.state.catPagares.filter(function (pag) {
                                return pag.value != idpag;
                            });
                            forma.setState({
                                idpagare: '',
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                catPagares: catNew, monto: 0, importe: 0, colmena: '', grupo: '', fecha_entrega: '',
                                noxpcomprome: '', xpcomprometido: '', importeletra: ''
                            });
                            var $form = $('.get.soling form'),
                                Folio = $form.form('set values', { idpagare: '' });
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
        key: "handleButton",
        value: function handleButton(opc, e) {
            if (opc == 0) {
                this.setState({ activo: 0 });
            }
        }
    }, {
        key: "handleClickMessage",
        value: function handleClickMessage(e) {
            this.setState(function (prevState) {
                return { message: '', statusmessage: 'ui message hidden' };
            });
        }
    }, {
        key: "autoReset",
        value: function autoReset() {
            var self = this;
            window.clearTimeout(self.timeout);
            this.timeout = window.setTimeout(function () {
                self.setState({ message: '', statusmessage: 'ui message hidden' });
            }, 5000);
        }
    }, {
        key: "render",
        value: function render() {
            var _this13 = this;

            return React.createElement(
                "div",
                null,
                React.createElement(
                    "div",
                    { className: "ui segment vertical" },
                    React.createElement(
                        "div",
                        { className: "row" },
                        React.createElement(
                            "h3",
                            { className: "ui rojo header" },
                            "Ahorro Comprometido (Condonaci\xF3n)"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui secondary menu" },
                        React.createElement(
                            "div",
                            { className: "ui basic icon buttons" },
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Nuevo Registro" },
                                React.createElement("i", { className: "plus square outline icon", onClick: this.handleButton.bind(this, 0) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Formato PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
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
                                    React.createElement("input", { className: "prompt mayuscula", type: "text", placeholder: "Buscar Nombre" }),
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
                            return _this13.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get disper" },
                    React.createElement(
                        "form",
                        { className: "ui form formdis", ref: "form", onSubmit: this.handleSubmit.bind(this), method: "post" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: this.state.activo === 1 ? "disablediv" : "" },
                            React.createElement(
                                "div",
                                { className: "fields" },
                                React.createElement(InputFieldFind, { icons: this.state.icons1, id: "idacreditado", cols: "three wide", mayuscula: "true", name: "idacreditado", valor: this.state.idacreditado, label: "Acreditado", placeholder: "Buscar", onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                React.createElement(InputField, { id: "nombre", cols: "thirteen wide", label: "Nombre", readOnly: "readOnly", valor: this.state.nombre, onChange: this.handleInputChange.bind(this) })
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: this.state.activo === 0 ? "disablediv" : "" },
                            React.createElement(
                                "div",
                                { className: "fields" },
                                React.createElement(SelectOption, { id: "idpagare", cols: "three wide", label: "Pagar\xE9", valor: this.state.idpagare, valores: this.state.catPagares, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "colmena", cols: "five wide", label: "Colmena", readOnly: "readOnly", valor: this.state.colmena, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "grupo", cols: "two wide", label: "Grupo", readOnly: "readOnly", valor: this.state.grupo, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "fecha_entrega", cols: "three wide", label: "Fec. entrega", readOnly: "readOnly", valor: this.state.fecha_entrega, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNum, { id: "monto", label: "Monto", readOnly: "readOnly", valor: this.state.monto, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "h4",
                                { className: "ui dividing header" },
                                "Datos de condonaci\xF3n"
                            ),
                            React.createElement(
                                "div",
                                { className: "fields" },
                                React.createElement(InputField, { id: "noxpcomprome", cols: "five wide", label: "No. Amortizacion(es)", readOnly: "readOnly", valor: this.state.noxpcomprome, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "xpcomprometido", cols: "five wide", label: "Importe", readOnly: "readOnly", valor: this.state.xpcomprometido, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "importeletra", cols: "ten wide", label: "Importe en Letra", readOnly: "readOnly", valor: this.state.importeletra, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "ui vertical segment right aligned" },
                                React.createElement(
                                    "div",
                                    { className: "field" },
                                    React.createElement(
                                        "button",
                                        { className: "ui submit bottom primary basic button", type: "submit", name: "action" },
                                        React.createElement("i", { className: "send icon" }),
                                        " Enviar "
                                    )
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
                    description: item.idretiro + ' : ' + item.idpagare
                });
            });
            return response;
        }
    }
});