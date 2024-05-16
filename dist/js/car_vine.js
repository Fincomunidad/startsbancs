"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Mensaje = function (_React$Component) {
    _inherits(Mensaje, _React$Component);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this;
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

var RecordDetalle = function (_React$Component2) {
    _inherits(RecordDetalle, _React$Component2);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        return _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));
    }

    _createClass(RecordDetalle, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idpersona
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.acreditadoid
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idsucursal
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.col_nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nompromotor
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.anio
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

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

var Table = function (_React$Component3) {
    _inherits(Table, _React$Component3);

    function Table(props) {
        _classCallCheck(this, Table);

        return _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));
    }

    _createClass(Table, [{
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
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
                        React.createElement(Lista, { enca: ['Persona', 'Mp. Socia', 'Nombre', 'Sucursal', 'Colmena', 'Promotor(a)', 'Año'] })
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

var SelectDropDown = function (_React$Component4) {
    _inherits(SelectDropDown, _React$Component4);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this4 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this4.state = {
            value: ""
        };
        _this4.handleSelectChange = _this4.handleSelectChange.bind(_this4);
        return _this4;
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
            if (this.props.disabled) {
                cols += " disabled";
            }
            var listItems = void 0;
            if (this.props.valores != false) {
                listItems = this.props.valores.map(function (valor) {
                    return React.createElement(
                        "div",
                        { className: "item", "data-value": valor.value },
                        valor.name
                    );
                });
            }
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

var Captura = function (_React$Component5) {
    _inherits(Captura, _React$Component5);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this5 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this5.state = { activo: 0,
            catINE: [],
            catPeriodo: [],
            idper: 0,
            csrf: "", message: "",
            statusmessage: 'ui floating hidden message', stepno: 1, boton: 'Enviar', boton2: 'Enviar', boton3: 'Enviar',
            icons1: 'inverted circular search link icon',
            disabledboton2: 'disabled', disabledboton3: 'disabled'
        };
        return _this5;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            var currentTime = new Date();
            var iAnio = currentTime.getFullYear();
            var catA = [];
            for (var index = 2023; index <= iAnio; index++) {
                catA.push({ value: index, name: index });
            }

            this.state.catPeriodo = catA;
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));

            var id = this.state.idper;
            var object = {
                url: base_url + 'api/CarteraV1/vine/' + id,
                type: 'GET',
                dataType: 'json'
            };
            var forma = this;
            ajax(object).then(function resolve(response) {
                forma.setState({ catINE: response.movine
                });
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
                    csrf: response.newtoken,
                    message: response.message,
                    statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                });
            });
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
        key: "handleButton",
        value: function handleButton(e, value) {

            var id = this.state.idper;

            var a = document.createElement('a');
            a.href = base_url + 'api/ReportV1/vine/' + id;
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
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
            var _this6 = this;

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
                            "Vencimientos  INE"
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
                                { className: "ui button", "data-tooltip": "Formato PDF" },
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
                            window.clearTimeout(_this6.timeout);
                            _this6.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get soling" },
                    React.createElement(
                        "form",
                        { className: "ui form small formgen", ref: "form" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: this.state.activo === 1 ? "disablediv" : "" },
                            React.createElement(
                                "div",
                                { className: "three fields" },
                                React.createElement(
                                    "div",
                                    { className: "three wide field" },
                                    React.createElement(SelectDropDown, { name: "idper", id: "idper", label: "Periodo", valor: this.state.idper, valores: this.state.catPeriodo, onChange: this.handleInputChange.bind(this) })
                                )
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    null,
                    React.createElement(Table, { datos: this.state.catINE })
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

ReactDOM.render(React.createElement(Captura, null), document.getElementById('root'));