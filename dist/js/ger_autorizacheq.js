"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

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
            pregunta: "¿Desea autorizar el Cheque ?"
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

var RecordDetalle = function (_React$Component2) {
    _inherits(RecordDetalle, _React$Component2);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this2 = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this2.state = {
            fecha_entrega: _this2.props.registro.fecha_entrega,
            estatus: _this2.props.registro.estatus

        };
        return _this2;
    }

    _createClass(RecordDetalle, [{
        key: "handleClick",
        value: function handleClick(e) {
            //this.props.onClick(e, this.props.registro.idcredito);
            var idmov = this.props.registro.idcredito;
            var forma = this;
            var $form = $('.get .bovmov form'),
                token = $form.form('get value', 'csrf_bancomunidad_token');
            this.props.onClick(e, 0);
            $('.mini.modal').modal({
                closable: false,
                onApprove: function onApprove() {
                    var object = {
                        url: base_url + ("api/CarteraV1/AutorizaCheque/" + idmov),
                        type: 'PUT',
                        dataType: 'json'
                    };
                    ajax(object).then(function resolve(response) {
                        forma.setState({
                            csrf: response.newtoken,
                            message: response.message,
                            statusmessage: 'ui positive floating message message2'
                        });
                    }, function reject(reason) {
                        if (reason.status == "200") {
                            var response = validaError(reason);
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message message2'
                            });
                            var fecha = response.todo.fecha;

                            if (fecha != '') {
                                forma.setState({
                                    fecha_entrega: fecha, estatus: 'Autorizado'
                                });
                            }
                            forma.props.onClick(e, 'Cheque aplicado exitosamente!', 'ui positive floating message message2');
                        } else {
                            var _response = validaError(reason);
                            forma.setState({
                                csrf: _response.newtoken,
                                message: _response.message,
                                statusmessage: 'ui negative floating message message2'
                            });
                        }
                    });
                }
            }).modal('show');
        }
    }, {
        key: "handleClickCancel",
        value: function handleClickCancel(e) {
            //this.props.onClick(e, this.props.registro.idcredito);
            var idmov = this.props.registro.idcredito;
            var forma = this;
            var $form = $('.get .bovmov form'),
                token = $form.form('get value', 'csrf_bancomunidad_token');
            $('.mini.modal').modal({
                closable: false,
                onApprove: function onApprove() {
                    var object = {
                        url: base_url + ("api/CarteraV1/CancelaCheque/" + idmov),
                        type: 'PUT',
                        dataType: 'json'
                    };
                    ajax(object).then(function resolve(response) {
                        forma.setState({
                            csrf: response.newtoken,
                            message: response.message,
                            statusmessage: 'ui positive floating message message2'
                        });
                    }, function reject(reason) {
                        if (reason.status == "200") {
                            var response = validaError(reason);
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message message2'
                            });
                            forma.setState({
                                fecha_entrega: '', estatus: 'Por Autorizar'
                            });

                            forma.props.onClick(e, 'Cheque cancelado exitosamente!', 'ui positive floating message message2');
                        } else {
                            var _response2 = validaError(reason);
                            forma.setState({
                                csrf: _response2.newtoken,
                                message: _response2.message,
                                statusmessage: 'ui negative floating message message2'
                            });

                            forma.props.onClick(e, _response2.message, 'ui negative floating message message2');
                        }
                    });
                }
            }).modal('show');
        }
    }, {
        key: "render",
        value: function render() {
            var boton = null;
            var botonCancel = null;
            if (this.state.estatus == 'Por Autorizar') {
                boton = React.createElement(
                    "a",
                    { "data-tooltip": "Autorizar", onClick: this.handleClick.bind(this) },
                    React.createElement("i", { className: "edit icon circular green" })
                );
            } else {
                botonCancel = React.createElement(
                    "a",
                    { "data-tooltip": "Cancelar", onClick: this.handleClickCancel.bind(this) },
                    React.createElement("i", { className: "cancel icon circular red" })
                );
            }

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
                    this.props.registro.acreditado
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idpagare
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.monto
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
                    this.state.fecha_entrega
                ),
                React.createElement(
                    "td",
                    null,
                    this.state.estatus
                ),
                React.createElement(
                    "td",
                    { className: " center aligned" },
                    boton,
                    botonCancel
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var Table = function (_React$Component3) {
    _inherits(Table, _React$Component3);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this3 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this3.state = { statusmessage: '', message: '' };
        _this3.handleClick = _this3.handleClick.bind(_this3);
        return _this3;
    }

    _createClass(Table, [{
        key: "handleClick",
        value: function handleClick(e, message, statusmessage) {
            this.props.onClick(e, message, statusmessage);
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
                null,
                React.createElement(
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
                                React.createElement(Lista, { enca: ['Crédito', 'Acreditada(o)', 'Pagaré', 'Monto', 'Fec. Aprobación', 'Fec. Dispersión', 'Fec. Autorización', 'Estatus', 'Evento'] })
                            ),
                            React.createElement(
                                "tbody",
                                null,
                                rows
                            )
                        )
                    )
                )
            );
        }
    }]);

    return Table;
}(React.Component);

var Captura = function (_React$Component4) {
    _inherits(Captura, _React$Component4);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this4 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this4.state = {
            csrf: '',
            message: '',
            statusmessage: 'hidden',
            catAutoriza: []
        };
        _this4.handleClick = _this4.handleClick.bind(_this4);

        return _this4;
    }

    _createClass(Captura, [{
        key: "handleClick",
        value: function handleClick(e, message, statusmessage) {

            this.setState({ message: message, statusmessage: statusmessage });
        }
    }, {
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            this.obtenerData();
        }
    }, {
        key: "obtenerData",
        value: function obtenerData() {
            this.setState({
                catAutoriza: []
            });
            $.ajax({
                url: base_url + '/api/CarteraV1/AutorizaCheque',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catAutoriza: response.result
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    this.setState({
                        catAutoriza: []
                    });
                }.bind(this)
            });
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, opc) {
            if (e == 4) {
                this.obtenerData();
            }
        }
    }, {
        key: "findMov",
        value: function findMov() {
            this.obtenerData();
        }
    }, {
        key: "handleAutoriza",
        value: function handleAutoriza() {
            var forma = this;
            $('.mini.modal').modal({
                closable: false,
                onApprove: function onApprove() {
                    forma.import(forma);
                }
            }).modal('show');
        }
    }, {
        key: "import",
        value: function _import(forma) {
            //
            var iConteo = 0;
            var iRegistro = forma.state.catAutoriza.length;

            var _loop = function _loop(index) {
                var element = forma.state.catAutoriza[index];

                var idmov = element['idcredito'];
                if (element['estatus'] == 'Por Autorizar') {
                    var tipo = 'PUT';
                    var token = $('#csrf_bancomunidad_token').val();
                    var object = {
                        url: base_url + ("api/CarteraV1/AutorizaCheque/" + idmov),
                        type: tipo,
                        dataType: 'json',
                        data: {
                            csrf_bancomunidad_token: token
                        }
                    };
                    ajax(object).then(function resolve(response) {}, function reject(reason) {
                        if (reason.status == "200") {
                            var response = validaError(reason);
                            forma.setState({
                                csrf: response.newtoken
                            });

                            var fecha = response.todo.fecha;
                            element['fecha_entrega'] = fecha;
                            element['estatus'] = 'Entregado';
                            forma.setState({
                                catAutoriza: element
                            });
                        } else {
                            var _response3 = validaError(reason);
                            forma.setState({
                                csrf: _response3.newtoken
                            });
                        }
                    });
                }
            };

            for (var index = 0; index < forma.state.catAutoriza.length; index++) {
                _loop(index);
            }

            this.obtenerData();

            //        forma.setState({message: 'Proceso terminado!', statusmessage: 'ui positive floating message'});        
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
            var _this5 = this;

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
                            "Autorizaci\xF3n de Cheques (Cr\xE9ditos)"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui  basic icon buttons" },
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
                                return _this5.setState({ message: '', statusmessage: 'ui message hidden' });
                            } })
                    )
                ),
                React.createElement(
                    "div",
                    { className: "get bovmov" },
                    React.createElement(
                        "form",
                        { className: "ui form", action: "" },
                        React.createElement(
                            "div",
                            { className: "ui vertical segment right aligned" },
                            React.createElement(
                                "div",
                                { className: "field" },
                                React.createElement(
                                    "button",
                                    { className: "ui submit bottom primary basic button", type: "button", name: "action", onClick: this.handleAutoriza.bind(this) },
                                    React.createElement("i", { className: "send icon" }),
                                    " Autoriza todos "
                                )
                            )
                        ),
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(Table, { datos: this.state.catAutoriza, onClick: this.handleClick })
                    )
                ),
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
                            window.clearTimeout(_this5.timeout);
                            _this5.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

ReactDOM.render(React.createElement(Captura, null), document.getElementById('root'));