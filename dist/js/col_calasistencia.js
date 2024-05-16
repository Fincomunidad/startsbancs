"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

function Lista(props) {
    var cadenas = props.enca;
    var contador = 0;
    var listItems = cadenas.map(function (encabezado) {
        return React.createElement(
            "th",
            { className: "two wide", key: contador++ },
            encabezado
        );
    });
    return React.createElement(
        "tr",
        null,
        listItems
    );
}

var DocumentoRow = function (_React$Component) {
    _inherits(DocumentoRow, _React$Component);

    function DocumentoRow(props) {
        var _this$state;

        _classCallCheck(this, DocumentoRow);

        var _this = _possibleConstructorReturn(this, (DocumentoRow.__proto__ || Object.getPrototypeOf(DocumentoRow)).call(this, props));

        _this.state = (_this$state = {}, _defineProperty(_this$state, nombre, false), _defineProperty(_this$state, "asistencia", _this.props.valor.asistencia), _defineProperty(_this$state, "incidencia", _this.props.valor.incidencia), _this$state);
        return _this;
    }

    _createClass(DocumentoRow, [{
        key: "render",
        value: function render() {
            var iSemana = getWeekNo();
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    { style: { width: '10%' }, className: "text-center" },
                    this.props.valor.pagare
                ),
                React.createElement(
                    "td",
                    { style: { width: '10%' }, className: "text-center" },
                    this.props.valor.idcredito
                ),
                React.createElement(
                    "td",
                    { style: { width: '10%' }, className: "text-center" },
                    this.props.valor.fecpago
                ),
                React.createElement(
                    "td",
                    { style: { width: '5%' } },
                    this.props.valor.semana
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.valor.nomasis
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.valor.nominci
                ),
                React.createElement(
                    "td",
                    { style: { width: '60%' } },
                    this.props.valor.sancion
                )
            );
        }
    }]);

    return DocumentoRow;
}(React.Component);

var Catalogo = function (_React$Component2) {
    _inherits(Catalogo, _React$Component2);

    function Catalogo(props) {
        _classCallCheck(this, Catalogo);

        return _possibleConstructorReturn(this, (Catalogo.__proto__ || Object.getPrototypeOf(Catalogo)).call(this, props));
    }

    _createClass(Catalogo, [{
        key: "render",
        value: function render() {
            var rows = [];
            var lastLista = null;

            this.props.valores.forEach(function (item) {
                rows.push(React.createElement(DocumentoRow, { valor: item, key: item.nombre }));
            });

            return React.createElement(
                "div",
                { className: "ui grid" },
                React.createElement(
                    "div",
                    { className: "column" },
                    React.createElement(
                        "table",
                        { className: "ui selectable celled blue table" },
                        React.createElement(
                            "thead",
                            null,
                            React.createElement(Lista, { enca: ['Pagaré', 'Id crédito', 'Fecha', 'Semana', 'Asistencia', 'Incidencia', 'Sancion'] })
                        ),
                        React.createElement(
                            "tbody",
                            null,
                            rows
                        )
                    )
                )
            );
        }
    }]);

    return Catalogo;
}(React.Component);

var MultiSelect = function (_React$Component3) {
    _inherits(MultiSelect, _React$Component3);

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

var InputField = function (_React$Component4) {
    _inherits(InputField, _React$Component4);

    function InputField(props) {
        _classCallCheck(this, InputField);

        return _possibleConstructorReturn(this, (InputField.__proto__ || Object.getPrototypeOf(InputField)).call(this, props));
    }

    _createClass(InputField, [{
        key: "render",
        value: function render() {
            var _this5 = this;

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
                            return _this5.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputField;
}(React.Component);

var InputFieldFind = function (_React$Component5) {
    _inherits(InputFieldFind, _React$Component5);

    function InputFieldFind(props) {
        _classCallCheck(this, InputFieldFind);

        var _this6 = _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));

        _this6.state = {
            value: ''
        };
        return _this6;
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this7 = this;

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
                            return _this7.props.onChange(event);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this7.props.onClick(event, _this7.props.valor, _this7.props.id);
                        } })
                )
            );
        }
    }]);

    return InputFieldFind;
}(React.Component);

var InputFieldNumber = function (_React$Component6) {
    _inherits(InputFieldNumber, _React$Component6);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: "render",
        value: function render() {
            var _this9 = this;

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
                            return _this9.props.onChange(event);
                        }, onBlur: function onBlur(event) {
                            return _this9.props.onBlur(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var InputFieldNum = function (_React$Component7) {
    _inherits(InputFieldNum, _React$Component7);

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

var Mensaje = function (_React$Component8) {
    _inherits(Mensaje, _React$Component8);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this11 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this11.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this11;
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

var Calendar = function (_React$Component9) {
    _inherits(Calendar, _React$Component9);

    function Calendar(props) {
        _classCallCheck(this, Calendar);

        var _this12 = _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));

        _this12.handleChange = _this12.handleChange.bind(_this12);
        return _this12;
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

var SelectDropDown = function (_React$Component10) {
    _inherits(SelectDropDown, _React$Component10);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this13 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this13.state = {
            value: ""
        };
        _this13.handleSelectChange = _this13.handleSelectChange.bind(_this13);
        return _this13;
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

var SelectOption = function (_React$Component11) {
    _inherits(SelectOption, _React$Component11);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this14 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this14.state = {
            value: ""
        };
        return _this14;
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

var CheckBox = function (_React$Component12) {
    _inherits(CheckBox, _React$Component12);

    function CheckBox(props) {
        _classCallCheck(this, CheckBox);

        return _possibleConstructorReturn(this, (CheckBox.__proto__ || Object.getPrototypeOf(CheckBox)).call(this, props));
    }

    _createClass(CheckBox, [{
        key: "render",
        value: function render() {
            var _this16 = this;

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
                                    return _this16.props.onClickCheck(event, _this16.props.name, _this16.props.valor);
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

var Captura = function (_React$Component13) {
    _inherits(Captura, _React$Component13);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this17 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this17.state = {
            catPagares: [],
            catCalifica: [],
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
            catCuentas: []
        };
        _this17.handleClickMessage = _this17.handleClickMessage.bind(_this17);
        _this17.handleonBlur = _this17.handleonBlur.bind(_this17);
        return _this17;
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
                var acreditada = this.state.idacredita;
                //getCuentasAcre
                var forma = this;
                var object = {
                    url: base_url + ("api/ColmenasV1/getcalifica/" + value + "/" + acreditada),
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {

                    forma.setState({
                        icons1: 'inverted circular search link icon',
                        catCalifica: response.califica
                    });
                    console.log(response.califica);
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
            //getCuentasAcre
            var forma = this;
            var object = {
                url: base_url + ("api/CarteraV1/getcreditos_clist/" + value),
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                var catPagares = response.check;
                catPagares.unshift({ 'idacreditado': 0, idcredito: -1, name: 'Todos', value: -1 });
                forma.setState({
                    nombre: response.acre[0].nombre, activo: 1,
                    icons1: 'inverted circular search link icon',
                    catPagares: catPagares
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
                this.setState({ activo: 0, idacreditado: '', nombre: '', idcredito: 0, catPagares: [], catCalifica: [] });
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
            var _this18 = this;

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
                            "Calificacion de asistencia"
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
                            { className: "segment" },
                            React.createElement(Catalogo, { valores: this.state.catCalifica })
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