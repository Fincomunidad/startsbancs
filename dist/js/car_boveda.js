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

var InputFieldNumber = function (_React$Component2) {
    _inherits(InputFieldNumber, _React$Component2);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: "render",
        value: function render() {
            var _this3 = this;

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
                            return _this3.props.onChange(event);
                        }, onBlur: function onBlur(event) {
                            return _this3.props.onBlur(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var SelectDropDown = function (_React$Component3) {
    _inherits(SelectDropDown, _React$Component3);

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

var SelectOption = function (_React$Component4) {
    _inherits(SelectOption, _React$Component4);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this5 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this5.state = {
            value: ""
        };
        return _this5;
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

var RecordDetalle = function (_React$Component5) {
    _inherits(RecordDetalle, _React$Component5);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this6 = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this6.state = {
            cantidad: 0, total: 0
        };
        return _this6;
    }

    _createClass(RecordDetalle, [{
        key: "handleChange",
        value: function handleChange(e) {
            var _this7 = this;

            var name = e.target.name;
            var value = isNaN(e.target.value) || e.target.value == "" ? "0" : e.target.value;
            this.setState({ cantidad: value,
                total: parseFloat(this.props.registro.nombre) * parseFloat(value)
            });

            this.setState(function (prevState, props) {
                var anterior = numeral(prevState.total).format('0.00');
                var valoradd = numeral(_this7.state.total).format('0.00');
                var valtext = numeral($('#grantotal').val()).format('0.00');
                var final = parseFloat(valtext) - parseFloat(valoradd) + parseFloat(anterior);
                _this7.props.onChange(e, final);
            });
        }
    }, {
        key: "render",
        value: function render() {
            var cantidad = numeral(this.state.cantidad).format('0,0.00');
            var total = numeral(this.state.total).format('0,0.00');
            var thStyle = {
                display: 'none'
            };
            var rec1 = null;
            if (this.props.existe == 1) {
                var canfor = numeral(this.props.registro.cantidad).format('0,0');
                rec1 = React.createElement(
                    "td",
                    { className: "right aligned" },
                    canfor
                );
            } else {
                rec1 = React.createElement(
                    "td",
                    null,
                    React.createElement("input", { className: "table-input", type: "text", name: "cantidad[]", value: this.state.cantidad, onChange: this.handleChange.bind(this) }),
                    " "
                );
            }
            var rec2 = null;
            if (this.props.existe == 1) {
                var totalfor = numeral(this.props.registro.total).format('0,0.00');
                rec2 = React.createElement(
                    "td",
                    { className: "right aligned" },
                    " ",
                    totalfor
                );
            } else {
                rec2 = React.createElement(
                    "td",
                    null,
                    React.createElement("input", { className: "table-input", readOnly: "readOnly", type: "text", name: "total[]", value: total }),
                    " "
                );
            }

            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    { style: thStyle },
                    React.createElement("input", { className: "table-input", type: "text", id: "iddenomina[]", name: "iddenomina[]", value: this.props.registro.iddenomina }),
                    " "
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nombre
                ),
                rec1,
                rec2
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var Table = function (_React$Component6) {
    _inherits(Table, _React$Component6);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this8 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this8.state = { grantotal: _this8.props.totalxpagar };
        _this8.handleChange = _this8.handleChange.bind(_this8);
        return _this8;
    }

    _createClass(Table, [{
        key: "handleChange",
        value: function handleChange(e, total) {
            this.setState({ grantotal: total });
        }
    }, {
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            var grantotal = this.state.grantotal != 0 ? numeral(this.state.grantotal).format('0,0.00') : numeral(this.props.totalxpagar).format('0,0.00');

            if (datos instanceof Array === true) {
                var conteo = 0;
                datos.forEach(function (record) {
                    conteo = conteo + 1;
                    rows.push(React.createElement(RecordDetalle, { registro: record, id: conteo, existe: this.props.existe, onChange: this.handleChange }));
                }.bind(this));
            }
            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                "div",
                { className: "ui grid" },
                React.createElement(
                    "div",
                    { className: "eight wide column" },
                    React.createElement(
                        "table",
                        { className: "ui selectable celled blue table" },
                        React.createElement(
                            "thead",
                            null,
                            React.createElement(Lista, { enca: ['Denominación', 'Cantidad', 'Importe'] })
                        ),
                        React.createElement(
                            "tbody",
                            null,
                            rows
                        ),
                        React.createElement(
                            "tfoot",
                            { className: "full-width" },
                            React.createElement(
                                "tr",
                                null,
                                React.createElement(
                                    "th",
                                    { colSpan: 4 },
                                    React.createElement(
                                        "div",
                                        { className: "ui right floated  tiny orange statistic" },
                                        React.createElement("input", { className: "totalxpagar", readOnly: "readOnly", type: "text", id: "grantotal", name: "grantotal", value: grantotal })
                                    )
                                )
                            )
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

        var _this9 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this9.state = { csrf: '', idclave: 0, catBoveda: [], catBancos: [], catDenomina: [], catDenoIni: [],
            ocultar: true, boton: 'Apertura', movimiento: 0, labdes_ori: 'Destino', labidbanco: 'Caja', des_ori: 0,
            idbanco: 0, importe: 0, totalxpagar: 0, message: '', statusmessage: '',
            idmov: 0, idmovdet: 0, existe: 0
        };
        _this9.handleInputChange = _this9.handleInputChange.bind(_this9);
        _this9.handleonBlur = _this9.handleonBlur.bind(_this9);
        return _this9;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            $.ajax({
                url: base_url + '/api/CarteraV1/bovedas',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({ catBoveda: response.boveda, catDenoIni: response.denomina
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
            var value = target.value;
            var name = target.name;
            var link = "";
            var tit = "";
            this.setState(_defineProperty({}, name, value));

            if (name == "movimiento") {
                tit = "Destino";
                if (value == "I") {
                    tit = "Origen";
                }
                this.setState({
                    labdes_ori: tit
                });
            } else if (name == "des_ori") {
                tit = "Caja";
                if (value == "B") {
                    tit = "Banco";
                }
                this.setState({
                    labidbanco: tit
                });

                if (tit == "Caja") {
                    link = "generalv1/cajasall";
                } else if (tit == "Banco") {
                    link = "generalv1/bancosall";
                }

                var object = {
                    url: base_url + ("api/" + link),
                    type: 'GET',
                    dataType: 'json'
                };

                var $form = $('.get.bovmov form'),
                    Folio = $form.form('set values', { idbanco: '0' });
                this.setState({ catBancos: [] });

                var forma = this;
                ajax(object).then(function resolve(response) {
                    forma.setState({ catBancos: response.result, idbanco: 0 });
                    var $form = $('.get.bovmov form'),
                        Folio = $form.form('set values', { idbanco: '0' });
                }, function reject(reason) {
                    var response = validaError(reason);
                });
            } else if (name == "idclave") {
                var _object = {
                    url: base_url + ("api/CarteraV1/getboveda/" + value),
                    type: 'GET',
                    dataType: 'json'
                };
                var _forma = this;
                ajax(_object).then(function resolve(response) {
                    if (response.result == false) {
                        _forma.setState({ boton: "Apertura", ocultar: true });
                    } else {
                        if (response.result[0]['status'] == "1") {
                            _forma.setState({ boton: "Cierre", ocultar: false, idmov: response.result[0]['idmov'] });
                        } else {
                            _forma.setState({ boton: "Cerrado", ocultar: true, idmov: response.result[0]['idmov'] });
                        }
                    }
                }, function reject(reason) {
                    var response = validaError(reason);
                    _forma.setState({
                        message: response.message,
                        statusmessage: 'ui negative floating message'
                    });
                    _forma.autoReset();
                });
            } else if (name == "idbanco" && this.state.des_ori == "C" && this.state.movimiento == "I") {
                var idmov = this.state.idmov;
                var _object2 = {
                    url: base_url + ("api/CarteraV1/getCorteCaja/" + idmov + "/" + value),
                    type: 'GET',
                    dataType: 'json'
                };
                var _forma2 = this;
                ajax(_object2).then(function resolve(response) {
                    var importe = numeral(response.mov[0].importe).format('0,0.00');
                    _forma2.setState({
                        message: response.message,
                        statusmessage: 'ui positive floating message', existe: 1,
                        importe: importe, catDenomina: response.movdet, totalxpagar: response.mov[0].importe
                    });
                    _forma2.autoReset();
                }, function reject(reason) {
                    var response = validaError(reason);
                    _forma2.setState({
                        message: response.message, existe: 0,
                        statusmessage: 'ui negative floating message', importe: 0, catDenomina: [], totalxpagar: 0
                    });
                    _forma2.autoReset();
                });
            } else if (name == "idbanco" && this.state.des_ori != "" && this.state.movimiento != "I") {
                var deno = this.state.catDenoIni;
                this.setState({
                    existe: 0, catDenomina: deno, importe: 0, totalxpagar: 0
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
            this.setState({
                importe: nuevovalor
            });
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, opc) {}
    }, {
        key: "handleSubmitOpen",
        value: function handleSubmitOpen() {
            event.preventDefault();
            var opc = this.state.boton;
            $('.ui.form.formopen').form({
                inline: true,
                on: 'blur',
                fields: {
                    idclave: {
                        identifier: 'idclave',
                        rules: [{
                            type: 'empty',
                            prompt: 'Numero de Cuenta incorrecto '
                        }]
                    }
                }
            });

            $('.ui.form.formopen').form('validate form');
            var valida = $('.ui.form.formopen').form('is valid');
            if (opc != "Cierre" && opc != "Apertura") {
                valida = false;
            }
            this.setState({ message: '', statusmessage: 'ui message hidden' });
            if (valida == true) {
                var $form = $('.get.bovopen form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');

                var page = "openboveda";
                if (opc == "Cierre") {
                    page = "closeboveda/" + this.state.idmov;
                }
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ("api/CarteraV1/" + page),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        var texto = "Cierre";
                        var ocultar = false;
                        ajax(object).then(function resolve(response) {
                            if (opc == "Cierre") {
                                texto = "Cerrado";
                                ocultar = true;
                            }
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                boton: texto, idmov: response.registros, ocultar: ocultar
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
                }).modal('show');
            } else {
                if (opc != "Cierre" && opc != "Apertura") {
                    this.setState({
                        message: 'Boveda Cerrada!',
                        statusmessage: 'ui negative floating message'
                    });
                } else {
                    this.setState({
                        message: 'Datos incompletos!',
                        statusmessage: 'ui negative floating message'
                    });
                }
                this.autoReset();
            }
        }
    }, {
        key: "handleSubmitMov",
        value: function handleSubmitMov() {
            event.preventDefault();
            $('.ui.form.formmov').form({
                inline: true,
                on: 'blur',
                fields: {
                    importe: {
                        identifier: 'importe',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    grantotal: {
                        identifier: 'grantotal',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    match: {
                        identifier: 'importe',
                        rules: [{
                            type: 'match[grantotal]',
                            prompt: 'Importes diferentes!'
                        }]
                    },
                    movimiento: {
                        identifier: 'movimiento',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    des_ori: {
                        identifier: 'des_ori',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    idbanco: {
                        identifier: 'idbanco',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    }
                }
            });

            $('.ui.form.formmov').form('validate form');
            var valida = $('.ui.form.formmov').form('is valid');

            if (this.state.importe == 0 && this.state.totalxpagar == 0) {
                valida = false;
            }

            if (valida == true) {
                var $form = $('.get.bovmov form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'POST';
                var forma = this;
                var idmov = this.state.idmov;
                var newVal = this.state.catDenoIni;

                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ("api/CarteraV1/add_boveda/" + idmov),
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
                                statusmessage: 'ui positive floating message ',
                                idgrupo: 0, des_ori: 0, movimiento: 0, importe: 0,
                                catDenomina: newVal, totalxpagar: 0
                            });
                            var $form = $('.get.bovmov form'),
                                Folio = $form.form('set values', { idgrupo: '0', des_ori: '0', movimiento: '0' });
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
            this.timeout = window.setTimeout(function () {
                self.setState({ message: '', statusmessage: 'ui message hidden' });
            }, 5000);
        }
    }, {
        key: "render",
        value: function render() {
            var _this10 = this;

            //        const habilitar = this.state.boton =="Apertura"?"hidden":"";
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
                            "Control de Boveda"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui  basic icon buttons" },
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Nuevo Registro" },
                            React.createElement("i", { className: "plus square outline icon", onClick: this.handleButton.bind(this, 0) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Formato PDF" },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Descargar archivo PDF" },
                            React.createElement("i", { className: "download icon", onClick: this.handleButton.bind(this, 2) })
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
                            window.clearTimeout(_this10.timeout);
                            _this10.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get bovopen" },
                    React.createElement(
                        "form",
                        { className: "ui form formopen", ref: "form", onSubmit: this.handleSubmitOpen.bind(this), method: "post" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "two fields" },
                            React.createElement(SelectDropDown, { name: "idclave", id: "idclave", label: "Boveda", valor: this.state.idclave, valores: this.state.catBoveda, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: "ui vertical segment left aligned" },
                            React.createElement(
                                "div",
                                { className: "field" },
                                React.createElement(
                                    "button",
                                    { className: "ui submit bottom primary basic button", type: "submit", name: "action" },
                                    React.createElement("i", { className: "send icon" }),
                                    " ",
                                    this.state.boton,
                                    " "
                                )
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: this.state.ocultar == true ? "hidden" : "" },
                    React.createElement(
                        "div",
                        { className: "get bovmov" },
                        React.createElement(
                            "form",
                            { className: "ui form formmov", ref: "form", onSubmit: this.handleSubmitMov.bind(this), method: "post" },
                            React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectOption, { name: "movimiento", id: "movimiento", label: "Movimiento", valor: this.state.movimiento, valores: [{ name: "Ingreso a Boveda", value: "I" }, { name: "Egreso de Boveda", value: "E" }], onChange: this.handleInputChange.bind(this) }),
                                React.createElement(SelectOption, { name: "des_ori", id: "des_ori", label: this.state.labdes_ori, valor: this.state.des_ori, valores: [{ name: "Caja", value: "C" }, { name: "Banco", value: "B" }], onChange: this.handleInputChange.bind(this) }),
                                React.createElement(SelectOption, { name: "idbanco", id: "idbanco", label: this.state.labidbanco, valor: this.state.idbanco, valores: this.state.catBancos, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "importe", label: "Importe", valor: this.state.importe, onChange: this.handleInputChange, onBlur: this.handleonBlur })
                            ),
                            React.createElement(Table, { datos: this.state.catDenomina, existe: this.state.existe, totalxpagar: this.state.totalxpagar }),
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