"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var MultiSelect = function (_React$Component) {
    _inherits(MultiSelect, _React$Component);

    function MultiSelect(props) {
        _classCallCheck(this, MultiSelect);

        return _possibleConstructorReturn(this, (MultiSelect.__proto__ || Object.getPrototypeOf(MultiSelect)).call(this, props));
    }

    _createClass(MultiSelect, [{
        key: "render",
        value: function render() {
            var listItems = this.props.valores.map(function (valor) {
                return React.createElement(
                    "option",
                    { value: valor.value },
                    valor.name
                );
            });

            return React.createElement(
                "div",
                { className: "ten wide field" },
                React.createElement(
                    "label",
                    null,
                    this.props.label
                ),
                React.createElement(
                    "select",
                    { name: this.props.name, className: "ui fluid search dropdown selection multiple", multiple: "" },
                    React.createElement(
                        "option",
                        { value: "" },
                        "Seleccione"
                    ),
                    listItems
                )
            );
        }
    }]);

    return MultiSelect;
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
            var _this3 = this;

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
                            return _this3.props.onChange(event);
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

        var _this4 = _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));

        _this4.state = {
            value: ''
        };
        return _this4;
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this5 = this;

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
                            return _this5.props.onChange(event);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this5.props.onClick(event, _this5.props.valor, _this5.props.id);
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
            var _this7 = this;

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
                            return _this7.props.onChange(event);
                        }, onBlur: function onBlur(event) {
                            return _this7.props.onBlur(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var InputFieldNum = function (_React$Component5) {
    _inherits(InputFieldNum, _React$Component5);

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

var Mensaje = function (_React$Component6) {
    _inherits(Mensaje, _React$Component6);

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

var Calendar = function (_React$Component7) {
    _inherits(Calendar, _React$Component7);

    function Calendar(props) {
        _classCallCheck(this, Calendar);

        var _this10 = _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));

        _this10.handleChange = _this10.handleChange.bind(_this10);
        return _this10;
    }

    _createClass(Calendar, [{
        key: "handleChange",
        value: function handleChange(e) {
            //        console.log(e);
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

var SelectDropDown = function (_React$Component8) {
    _inherits(SelectDropDown, _React$Component8);

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

var SelectOption = function (_React$Component9) {
    _inherits(SelectOption, _React$Component9);

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

var CheckBox = function (_React$Component10) {
    _inherits(CheckBox, _React$Component10);

    function CheckBox(props) {
        _classCallCheck(this, CheckBox);

        return _possibleConstructorReturn(this, (CheckBox.__proto__ || Object.getPrototypeOf(CheckBox)).call(this, props));
    }

    _createClass(CheckBox, [{
        key: "render",
        value: function render() {
            var _this14 = this;

            var checked = this.props.valor == '1' ? 'ui checkbox checked' : 'ui checkbox';
            return React.createElement(
                "div",
                { className: "field" },
                React.createElement(
                    "label",
                    null,
                    "Seleccione"
                ),
                React.createElement(
                    "div",
                    { className: "four fields" },
                    React.createElement(
                        "div",
                        { className: "ten wide inline field" },
                        React.createElement(
                            "div",
                            { className: checked, onClick: function onClick(event) {
                                    return _this14.props.onClickCheck(event, _this14.props.name, _this14.props.valor);
                                } },
                            React.createElement("input", { type: "checkbox", value: this.props.valor == 1 ? 'on' : 'off', id: this.props.name, name: this.props.name, tabindex: "0", "class": "hidden" }),
                            React.createElement(
                                "label",
                                null,
                                this.props.titulo
                            )
                        )
                    )
                )
            );
        }
    }]);

    return CheckBox;
}(React.Component);

var Captura = function (_React$Component11) {
    _inherits(Captura, _React$Component11);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this15 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this15.state = { catPagares: [],
            catBancos: [],
            activo: 0,
            idcredito: 0,
            idacreditado: '',
            nombre: '',
            idpagare: '',
            colmena: '',
            grupo: '',
            fecha_entrega: '',
            monto: 0,
            noxpcomprome: '',
            xpcomprometido: '',
            csrf: "",
            message: "",
            importe: 0,
            importeletra: '',
            statusmessage: 'ui floating hidden message', icons1: 'inverted circular search link icon',
            catCuentas: [],
            catproductos: []
        };
        _this15.handleClickMessage = _this15.handleClickMessage.bind(_this15);
        _this15.handleonBlur = _this15.handleonBlur.bind(_this15);
        return _this15;
    }

    _createClass(Captura, [{
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));

            if (name == "idpagare") {
                this.setState({ idpagare: value });
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
            var forma = this;
            var object = {
                url: base_url + "api/CarteraV1/getProductos",
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {

                forma.setState({
                    catproductos: response.catproductos
                });
            }, function reject(reason) {
                var response = validaError(reason);
            });
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value, name) {
            var idacreditado = 0;
            if (name == "idacreditado") {
                this.setState({ idacredita: value, icons1: 'spinner circular inverted blue loading icon' });
                idacreditado = value;
            }
            //getCuentasAcre
            var forma = this;
            var object = {
                url: base_url + ("api/CarteraV1/getcreditos_clist/" + value),
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {

                forma.setState({
                    nombre: response.acre[0].nombre, activo: 1,
                    icons1: 'inverted circular search link icon',
                    catPagares: response.check
                });
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
                    message: 'Acreditado inexistente!', nombre: '',
                    statusmessage: 'ui negative floating message',
                    icons1: 'inverted circular search link icon'
                });
                forma.autoReset();
            });
        }
    }, {
        key: "handleButton",
        value: function handleButton(opc, e) {
            if (opc == 0) {
                this.setState({ activo: 0, idacreditado: '', idPagare: '0', nombre: '', idcredito: 0, catPagares: [] });
            } else {

                var sUrl = "";
                if (opc == 20) {
                    var idcredito = this.state.idpagare;
                    if (idcredito != "0" && idcredito != "") {
                        sUrl = base_url + 'api/ReportV1/edocta/' + idcredito;
                    }
                } else {

                    var $form = $('.get.disper form'),

                    // get one value
                    idahorros = $form.form('get value', 'idAhorros');

                    var opciones = '';
                    idahorros.forEach(function (element) {
                        opciones += element + '-';
                    });

                    var fechaconsulta = "0";
                    var fechafin = "0";
                    if ($('#fechaconsulta').val() != '') {
                        var extraer = $('#fechaconsulta').val().split('/');
                        var fec = new Date(extraer[2], extraer[1] - 1, extraer[0]);
                        fechaconsulta = moment(fec).format('DDMMYYYY');
                    }
                    if ($('#fechafin').val() != '') {
                        var _extraer = $('#fechafin').val().split('/');
                        var _fec = new Date(_extraer[2], _extraer[1] - 1, _extraer[0]);
                        fechafin = moment(_fec).format('DDMMYYYY');
                    }

                    var idacreditado = this.state.idacreditado;
                    if (idacreditado != '') {
                        if (opc == 10) {
                            sUrl = base_url + 'api/ReportV1/cuenta_acre?id=' + idacreditado + '&cuentas=' + opciones + '&fechai=' + fechaconsulta + '&fechaf=' + fechafin;
                        } else if (opc == 11) {
                            sUrl = base_url + 'api/ReportV1/cuenta_acre/p/?id=' + idacreditado + '&cuentas=' + opciones + '&fechai=' + fechaconsulta + '&fechaf=' + fechafin;
                        } else if (opc == 12) {
                            sUrl = base_url + 'api/ReportV1/cuenta_acre_resumen?id=' + idacreditado + '&cuentas=' + opciones;
                        }
                    }
                    if (opc == 14) {
                        sUrl = base_url + 'api/ReportV1/cuenta_acre_saldos?fechaf=' + fechafin;
                    }
                }
                if (sUrl == "") {
                    this.setState({
                        message: 'Seleccione los datos a filtrar!',
                        statusmessage: 'ui negative floating message',
                        icons1: 'inverted circular search link icon'
                    });
                    this.autoReset();
                    return;
                }
                var a = document.createElement('a');
                a.href = sUrl;
                a.target = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
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
        key: "handleonClickCheck",
        value: function handleonClickCheck(e) {}
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
            var _this16 = this;

            //                            <button className="ui button" data-tooltip="Formato PDF"><i className="file pdf outline icon" onClick={this.handleButton.bind(this,1)}></i></button>

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
                            "Reportes de Acreditadas"
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
                            )
                        ),
                        React.createElement(
                            "a",
                            { className: "ui dropdown item" },
                            "Ahorros",
                            React.createElement("i", { className: "dropdown icon" }),
                            React.createElement(
                                "div",
                                { className: "menu" },
                                React.createElement(
                                    "div",
                                    { className: "item" },
                                    React.createElement("i", { className: "dropdown icon" }),
                                    React.createElement(
                                        "span",
                                        { className: "text" },
                                        "Estado de Cuenta"
                                    ),
                                    React.createElement(
                                        "div",
                                        { className: "menu" },
                                        React.createElement(
                                            "div",
                                            { className: "item" },
                                            React.createElement("i", { className: "dropdown icon" }),
                                            React.createElement(
                                                "span",
                                                { className: "text" },
                                                "Detallado "
                                            ),
                                            React.createElement(
                                                "div",
                                                { className: "menu" },
                                                React.createElement(
                                                    "div",
                                                    { className: "item", onClick: this.handleButton.bind(this, 10) },
                                                    "Pdf"
                                                ),
                                                React.createElement(
                                                    "div",
                                                    { className: "item", onClick: this.handleButton.bind(this, 11) },
                                                    "Pantalla"
                                                )
                                            )
                                        ),
                                        React.createElement(
                                            "div",
                                            { className: "item", onClick: this.handleButton.bind(this, 12) },
                                            "Global"
                                        )
                                    )
                                ),
                                React.createElement(
                                    "div",
                                    { className: "item", onClick: this.handleButton.bind(this, 14) },
                                    "Reporte de Saldos"
                                )
                            )
                        ),
                        React.createElement(
                            "a",
                            { className: "ui dropdown item" },
                            "Cr\xE9ditos",
                            React.createElement("i", { className: "dropdown icon" }),
                            React.createElement(
                                "div",
                                { className: "menu" },
                                React.createElement(
                                    "div",
                                    { className: "item", onClick: this.handleButton.bind(this, 20) },
                                    "Estado de Cuenta"
                                )
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
                            return _this16.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get disper" },
                    React.createElement(
                        "form",
                        { className: "ui form formdis", ref: "form" },
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
                            { className: "ui segment" },
                            React.createElement(
                                "div",
                                { className: "fields" },
                                React.createElement(SelectOption, { id: "idpagare", cols: "three wide", label: "Pagar\xE9", valor: this.state.idpagare, valores: this.state.catPagares, onChange: this.handleInputChange.bind(this) })
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "ui segment" },
                            React.createElement(
                                "div",
                                { className: "fields" },
                                React.createElement(Calendar, { name: "fechaconsulta", label: "Fecha inicial", valor: this.state.fechaconsulta, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(Calendar, { name: "fechafin", label: "Fecha Final", valor: this.state.fechafin, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(MultiSelect, { label: "Cuentas de Ahorro", name: "idAhorros", valores: this.state.catproductos })
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
    minCharacters: 6,
    apiSettings: {
        url: base_url + 'api/CarteraV1/solcreacre?q={query}',
        onResponse: function onResponse(Response) {
            var response = {
                results: {}
            };
            if (!Response || !Response.result) {
                return;
            }
            $.each(Response.result, function (index, item) {
                var colmena = item.nombrecol || 'Sin asignar',
                    maxResults = 8;
                if (index >= maxResults) {
                    return false;
                }
                // create new language category
                if (response.results[colmena] === undefined) {
                    response.results[colmena] = {
                        name: colmena,
                        results: []
                    };
                }
                // add result to category
                response.results[colmena].results.push({
                    title: item.nombre,
                    description: 'Sol ' + item.idpersona + ' - Ac ' + item.idacreditado + ' - ' + item.grupo_nombre
                });
            });
            return response;
        }
    }
});