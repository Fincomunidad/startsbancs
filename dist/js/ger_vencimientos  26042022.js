"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

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

var SelectOption = function (_React$Component2) {
    _inherits(SelectOption, _React$Component2);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this3 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this3.state = {
            value: ""
        };
        return _this3;
    }

    _createClass(SelectOption, [{
        key: "handleSelectChange",
        value: function handleSelectChange(event) {
            this.props.onChange(event);
        }
    }, {
        key: "componentDidMount",
        value: function componentDidMount() {
            //        $(ReactDOM.findDOMNode(this.refs.myCombo)).on('change',this.handleSelectChange.bind(this));
        }
    }, {
        key: "render",
        value: function render() {
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
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

var Mensaje = function (_React$Component3) {
    _inherits(Mensaje, _React$Component3);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this4 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this4.state = {
            icon: "check circle outline icon",
            titulo: "Autorizar",
            pregunta: "¿Desea autorizar la reversa del registro?"
        };
        return _this4;
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

var RecordDetalle = function (_React$Component4) {
    _inherits(RecordDetalle, _React$Component4);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        return _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));
    }

    _createClass(RecordDetalle, [{
        key: "render",
        value: function render() {
            var autoriza = this.props.registro.autoriza == 'f' || this.props.registro.autoriza == false ? 'En espera' : 'Autorizado';
            var style = {};
            if (this.props.registro.estatus == 'Por ingresar') {
                style = { color: 'red' };
            }

            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idinversion
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nosocio
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.numero
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fechafin
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.tasa
                ),
                React.createElement(
                    "td",
                    { className: "ui center aligned" },
                    this.props.registro.dias
                ),
                React.createElement(
                    "td",
                    { className: "ui right aligned" },
                    numeral(this.props.registro.total).format('0,0.00')
                ),
                React.createElement(
                    "td",
                    { style: style },
                    this.props.registro.estatus
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var RecordCredito = function (_React$Component5) {
    _inherits(RecordCredito, _React$Component5);

    function RecordCredito(props) {
        _classCallCheck(this, RecordCredito);

        return _possibleConstructorReturn(this, (RecordCredito.__proto__ || Object.getPrototypeOf(RecordCredito)).call(this, props));
    }

    _createClass(RecordCredito, [{
        key: "render",
        value: function render() {
            var autoriza = this.props.registro.autoriza == 'f' || this.props.registro.autoriza == false ? 'En espera' : 'Autorizado';
            var style = { color: 'red' };

            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idcredito
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idacreditado
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idpagare
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.acreditado
                ),
                React.createElement(
                    "td",
                    { className: "ui right aligned" },
                    numeral(this.props.registro.monto).format('0,0.00')
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_primerpago
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_aprov
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_dispersa
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_entrega
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_vence1
                ),
                React.createElement(
                    "td",
                    { style: style },
                    this.props.registro.estatus
                )
            );
        }
    }]);

    return RecordCredito;
}(React.Component);

var Table = function (_React$Component6) {
    _inherits(Table, _React$Component6);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this7 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this7.state = { grantotal: _this7.props.totalxpagar };
        return _this7;
    }

    _createClass(Table, [{
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;

            var rect = null;
            if (datos instanceof Array === true) {
                datos.forEach(function (record) {
                    if (this.props.name == "inversion") {
                        rows.push(React.createElement(RecordDetalle, { registro: record }));
                    } else {
                        rows.push(React.createElement(RecordCredito, { registro: record }));
                    }
                }.bind(this));
            }

            var estilo = "display: block !important; top: 1814px;";
            var enca = [];
            if (this.props.name == "inversion") {
                enca = ['Id', 'Acreditada', 'Nombre', 'No.', 'Apertura', 'Vencimiento', 'Tasa', 'Dias x Vencer', 'Total', 'Estatus'];
            } else {
                enca = ['Id', 'Acreditada', 'Pagaré', 'Nombre', 'Monto', 'Primer Pago', 'Fec. Aprobación', 'Fec. Dispersa', 'Fec. Entrega', 'Fec. Vence', 'Estatus'];
            }
            return React.createElement(
                "div",
                { className: "ui grid" },
                React.createElement(
                    "div",
                    { className: "wide column" },
                    React.createElement(
                        "table",
                        { className: "ui selectable celled blue table" },
                        React.createElement(
                            "thead",
                            null,
                            React.createElement(Lista, { enca: enca })
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

    return Table;
}(React.Component);

var Captura = function (_React$Component7) {
    _inherits(Captura, _React$Component7);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this8 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this8.state = { csrf: '',
            message: '',
            statusmessage: 'hidden',
            catCreditos: [],
            catInversion: [],
            filtro: 0,
            stepno: 1
        };

        return _this8;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            this.obtenerData(0);
        }
    }, {
        key: "obtenerData",
        value: function obtenerData(f) {
            $.ajax({
                url: base_url + '/api/CarteraV1/getVencimientos/' + f,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({ catInversion: response.inver, catCreditos: response.credito
                    });
                }.bind(this),
                error: function (xhr, status, err) {}.bind(this)
            });
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(e) {
            this.setState({
                filtro: e.target.value
            });
            var f = e.target.value;

            this.obtenerData(f);
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, opc) {
            if (e == 4) {
                this.findMov();
            }
        }
    }, {
        key: "findMov",
        value: function findMov() {
            this.obtenerData();
        }
    }, {
        key: "handleState",
        value: function handleState(e) {
            this.setState({ stepno: e });
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
            var _this9 = this;

            //        const habilitar = this.state.boton =="Apertura"?"hidden":"";
            var statusicon = this.state.statusmessage + ' close icon';
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
                            "Vencimientos"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui  basic icon buttons" },
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Formato PDF" },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Actualizar" },
                            React.createElement("i", { className: "refresh icon", onClick: this.handleButton.bind(this, 4) })
                        )
                    )
                ),
                React.createElement(Mensaje, null),
                React.createElement(
                    "div",
                    { className: "thirteen wide column" },
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
                                return _this9.setState({ message: '', statusmessage: 'ui message hidden' });
                            } })
                    )
                ),
                React.createElement(
                    "div",
                    { className: "ui tiny steps" },
                    React.createElement(Steps, { valor: this.state.stepno, value: "1", icon: "folder outline icon", titulo: "Cr\xE9ditos", onClick: this.handleState.bind(this, 1) }),
                    React.createElement(Steps, { valor: this.state.stepno, value: "2", icon: "dollar icon", titulo: "Inversiones", onClick: this.handleState.bind(this, 2) })
                ),
                React.createElement(
                    "div",
                    { className: this.state.stepno === 1 ? "ui blue segment" : "ui blue segment step hidden" },
                    React.createElement(
                        "h4",
                        null,
                        "Cr\xE9ditos"
                    ),
                    React.createElement(
                        "form",
                        { className: "ui form", action: "" },
                        React.createElement(
                            "div",
                            { className: "fields" },
                            React.createElement(SelectOption, { id: "filter", cols: "three wide", label: "Filtro cr\xE9ditos:", valor: this.state.filtro, valores: [{ name: "Por Aprobar", value: "0" }, { name: "Por Dispersar", value: "1" }, { name: "Por Entregar", value: "2" }, { name: "Todos los Anteriores", value: "3" }, { name: "Por Pagar", value: "4" }, { name: "Amortización Pagada", value: "5" }, { name: "Las dos anteriores", value: "6" }, { name: "Crédito Liquidado", value: "7" },, { name: "Crédito Vencido", value: "8" }], onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            null,
                            React.createElement(Table, { name: "creditos", datos: this.state.catCreditos })
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: this.state.stepno === 2 ? "ui blue segment" : "ui blue segment step hidden" },
                    React.createElement(
                        "h4",
                        null,
                        "Inversiones"
                    ),
                    React.createElement(
                        "div",
                        null,
                        React.createElement(Table, { name: "inversion", datos: this.state.catInversion })
                    )
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

ReactDOM.render(React.createElement(Captura, null), document.getElementById('root'));