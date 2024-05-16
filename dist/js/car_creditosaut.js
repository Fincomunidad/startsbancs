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
            icon: "check circle outline icon",
            titulo: "Autorizar",
            pregunta: "¿Desea autorizar la reversa del registro?"
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

var SelectDropDown = function (_React$Component2) {
    _inherits(SelectDropDown, _React$Component2);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this2 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this2.state = {
            value: ""
        };
        _this2.handleSelectChange = _this2.handleSelectChange.bind(_this2);
        return _this2;
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
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field " + (this.props.visible == false ? " hidden " : "");
            var listItems = this.props.valores.map(function (valor, index) {
                return React.createElement(
                    "div",
                    { className: "item", "data-value": valor.value },
                    valor.name
                );
            });
            var id = '';
            if (this.props.id == 'idmovecho') {
                id = 'data-name';
            }
            var dropdown = "ui fluid search selection dropdown";
            if (this.props.disabled == true) {
                dropdown = dropdown + " disabled";
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
                    { className: dropdown },
                    React.createElement("input", { type: "hidden", ref: "myDrop", value: this.value, name: this.props.id, onChange: this.handleSelectChange }),
                    React.createElement("i", { className: "dropdown icon" }),
                    React.createElement(
                        "div",
                        { className: "default text", id: id },
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

var RecordDetalle = function (_React$Component3) {
    _inherits(RecordDetalle, _React$Component3);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this3 = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this3.state = {
            activo: 0
        };
        return _this3;
    }

    _createClass(RecordDetalle, [{
        key: "handleClick",
        value: function handleClick(e, val) {
            this.props.onClick(e, val, this.props.registro.idcredito);
        }
    }, {
        key: "handleClickCheck",
        value: function handleClickCheck(e) {
            if (this.state.activo == 1) {
                this.setState({ activo: 0 });
            } else {
                this.setState({ activo: 1 });
            }
        }
    }, {
        key: "render",
        value: function render() {
            var aprobado = null;
            var cancelapro = null;
            var eliminar = null;
            var aprobado = null;
            var valor = false;
            if (this.state.activo == 1) {
                valor = true;
            }
            var checked = valor == true ? 'ui checkbox checked' : 'ui checkbox';

            var checkPago = null;
            if (valor == true) {
                checkPago = React.createElement("input", { type: "checkbox", name: "chkpago[]", id: "chkpago[]", checked: "checked", onChange: this.handleClickCheck.bind(this) });
            } else {
                checkPago = React.createElement("input", { type: "checkbox", name: "chkpago[]", id: "chkpago[]", onChange: this.handleClickCheck.bind(this) });
            }
            var garantia = this.props.registro.garantia > 0 ? this.props.registro.garantia : 0.00;
            var total = parseFloat(this.props.registro.monto) + parseFloat(garantia);
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    checkPago
                ),
                React.createElement(
                    "td",
                    null,
                    React.createElement("input", { type: "text", className: "styleNo", id: "idcredito[]", name: "idcredito[]", value: this.props.registro.idcredito }),
                    " "
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_entrega_col
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idcredito
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nivel
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.monto
                ),
                React.createElement(
                    "td",
                    null,
                    garantia
                ),
                React.createElement(
                    "td",
                    null,
                    total
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.colmena_nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.grupo_nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.promotor
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_apro
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var Table = function (_React$Component4) {
    _inherits(Table, _React$Component4);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this4 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this4.state = { grantotal: _this4.props.totalxpagar };
        _this4.handleClick = _this4.handleClick.bind(_this4);
        return _this4;
    }

    _createClass(Table, [{
        key: "handleClick",
        value: function handleClick(e, val, valor) {
            this.props.onClick(e, val, valor);
        }
    }, {
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;

            var rect = null;
            if (datos instanceof Array === true) {
                var conteo = 0;
                datos.forEach(function (record) {
                    conteo = conteo + 1;
                    rows.push(React.createElement(RecordDetalle, { registro: record, onClick: this.handleClick }));
                }.bind(this));
            }

            var estilo = "display: block !important; top: 1814px;";
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
                            React.createElement(Lista, { enca: ['', 'Id Crédito', 'Fecha entrega', 'Nombre', 'Credito', 'Nivel', 'Monto', 'Garamtia', 'Importe', 'Colmena', 'Grupo', 'Promotor', 'Aprobado', 'Evento'] })
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

var Captura = function (_React$Component5) {
    _inherits(Captura, _React$Component5);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this5 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this5.state = { csrf: '',
            message: '',
            statusmessage: 'hidden',
            catOptions: [{ value: 0, name: 'Por autorizar' }, { value: 1, name: 'Cancela autorizacion' }, { value: 2, name: 'Elimina crédito' }],
            option: 0
        };
        _this5.handleClick = _this5.handleClick.bind(_this5);

        return _this5;
    }

    _createClass(Captura, [{
        key: "handleClick",
        value: function handleClick(e, val, valor) {
            var idmov = valor;
            var forma = this;
            var valOpc = e;
            var $form = $('.get .bovmov form'),
                token = $form.form('get value', 'csrf_bancomunidad_token');

            var link = "apruebaCredito";
            if (valOpc == 1) {
                link = "reversApruebaCredito";
            } else if (valOpc == 2) {
                link = "eliminaCredito";
            }
            $('.mini.modal').modal({
                closable: false,
                onApprove: function onApprove() {
                    var object = {
                        url: base_url + ("api/CarteraV1/" + link + "/" + idmov),
                        type: 'PUT',
                        dataType: 'json'
                    };
                    ajax(object).then(function resolve(response) {
                        forma.setState({
                            csrf: response.newtoken,
                            message: response.message,
                            statusmessage: 'ui positive floating message message2'
                        });
                        forma.autoReset();
                        forma.obtenerData();
                    }, function reject(reason) {
                        var response = validaError(reason);
                        forma.setState({
                            csrf: response.newtoken,
                            message: response.message,
                            statusmessage: 'ui negative floating message message2'
                        });
                        forma.autoReset();
                    });
                }
            }).modal('show');
        }
    }, {
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
        }
    }, {
        key: "obtenerData",
        value: function obtenerData() {
            var valor = this.state.option;
            $.ajax({
                url: base_url + ("/api/CarteraV1/creditosAut/" + valor),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({ catAutoriza: response.result
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    this.setState({ catAutoriza: [] });
                }.bind(this)
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
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            $('.ui.form.formcred').form({
                inline: true,
                on: 'blur',
                fields: {
                    option: {
                        identifier: 'option',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la opción '
                        }]
                    },
                    password: {
                        identifier: 'password',
                        rules: [{
                            type: 'empty',
                            prompt: 'Se requiere la Contraseña '
                        }]
                    }

                }
            });

            $('.ui.form.formcred').form('validate form');
            var valida = $('.ui.form.formcred').form('is valid');

            if (valida == true) {
                var $form = $('.get.creditos form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'POST';
                var forma = this;
                var option = this.state.option;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ("api/CarteraV1/creditosOpt/" + option),
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message '
                            });
                            window.location.replace('');
                            forma.obtenerData();
                        }, function reject(reason) {
                            var response = validaError(reason);

                            if (response.todo.code == "200") {
                                forma.setState({
                                    csrf: response.newtoken,
                                    message: response.message,
                                    statusmessage: 'ui positive floating message '
                                });

                                window.location.replace('');
                                forma.obtenerData();
                            } else {

                                forma.setState({
                                    csrf: response.newtoken,
                                    message: response.message,
                                    statusmessage: 'ui negative floating message'
                                });
                            }
                        });
                    }
                }).modal('show');
            }
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));
            this.obtenerData();
        }
    }, {
        key: "handleButton",
        value: function handleButton(event) {}
    }, {
        key: "render",
        value: function render() {
            var _this6 = this;

            var statusicon = this.state.statusmessage + ' close icon';
            var btnOption = "Autorizar";
            if (this.state.option == 1) {
                btnOption = "Cancelar";
            } else if (this.state.option == 2) {
                btnOption = "Eliminar";
            }

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
                            "Autorizaci\xF3n de cr\xE9ditos"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui  basic icon buttons" },
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "formato PDF" },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 4) })
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
                                return _this6.setState({ message: '', statusmessage: 'ui message hidden' });
                            } })
                    )
                ),
                React.createElement(
                    "div",
                    { className: "get creditos" },
                    React.createElement(
                        "form",
                        { className: "ui form formcred", onSubmit: this.handleSubmit.bind(this), method: "post" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "fields" },
                            React.createElement(SelectDropDown, { cols: "four wide", visible: true, name: "option", id: "option", label: "Filtro", valor: this.state.option, valores: this.state.catOptions, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(
                                "div",
                                { className: "four wide field" },
                                React.createElement(
                                    "label",
                                    null,
                                    "Contrase\xF1a de Autorizacion"
                                ),
                                React.createElement("input", { type: "password", name: "password" })
                            ),
                            React.createElement(
                                "div",
                                { className: "two wide field" },
                                React.createElement(
                                    "button",
                                    { className: "ui submit bottom primary basic button", type: "submit", name: "action" },
                                    React.createElement("i", { className: "send icon" }),
                                    btnOption
                                )
                            )
                        ),
                        React.createElement(Table, { datos: this.state.catAutoriza, onClick: this.handleClick })
                    )
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

ReactDOM.render(React.createElement(Captura, null), document.getElementById('root'));