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
                    React.createElement("input", { className: "text-right", type: "text", id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, value: this.props.valor, onChange: function onChange(event) {
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

        return _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));
    }

    _createClass(Calendar, [{
        key: "render",
        value: function render() {
            var _this10 = this;

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
                        React.createElement("input", { ref: "myCalen", type: "text", name: this.props.name, id: this.props.name, placeholder: "Fecha", onBlur: function onBlur(event) {
                                return _this10.props.onBlur(event);
                            } })
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

/*
 *
 *
*/

var RecordDetalle = function (_React$Component9) {
    _inherits(RecordDetalle, _React$Component9);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this13 = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this13.handlePrintRecord = _this13.handlePrintRecord.bind(_this13);
        return _this13;
    }

    _createClass(RecordDetalle, [{
        key: "handlePrintRecord",
        value: function handlePrintRecord(e) {
            var surl = "contratoinver/" + this.props.registro.numero;
            var a = document.createElement('a');
            a.href = base_url + 'api/ReportV1/' + surl;
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: "render",
        value: function render() {
            var checked = this.props.registro.activo ? React.createElement("i", { className: "green checkmark icon" }) : '';
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
                    numeral(this.props.registro.importe).format('0,0.00')
                ),
                React.createElement(
                    "td",
                    null,
                    numeral(this.props.registro.interes).format('0,0.00')
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.estatus
                ),
                React.createElement(
                    "td",
                    { className: "center aligned" },
                    React.createElement(
                        "a",
                        { "data-tooltip": "Contrato" },
                        React.createElement("i", { className: "print outline icon circular blue", onClick: this.handlePrintRecord })
                    )
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

/*
*
*
*/


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
            if (datos instanceof Array === true) {
                if (datos.length != 0) {
                    datos.forEach(function (record) {
                        rows.push(React.createElement(RecordDetalle, { registro: record }));
                    });
                }
            }
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
                        React.createElement(Lista, { enca: ['Numero', 'Fecha Apertura', 'Fecha Vencimiento', 'Importe', 'Interes', 'Estatus', 'Acción'] })
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

var Captura = function (_React$Component11) {
    _inherits(Captura, _React$Component11);

    function Captura(props) {
        var _this15$state;

        _classCallCheck(this, Captura);

        var _this15 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this15.state = (_this15$state = { activo: 0,
            catPlazos: [],
            catInteres: [],
            catInversion: [],
            catFestivos: [],
            idacreditado: '',
            nombre: '',
            csrf: "",
            message: "",
            importe: 0,
            importeletra: '',
            visible: false,
            statusmessage: 'ui floating hidden message',
            fecha: ''
        }, _defineProperty(_this15$state, "importe", ''), _defineProperty(_this15$state, "dias", 0), _defineProperty(_this15$state, "tasa", ''), _defineProperty(_this15$state, "fechafin", ''), _defineProperty(_this15$state, "interes", ''), _defineProperty(_this15$state, "total", ''), _defineProperty(_this15$state, "veri", false), _defineProperty(_this15$state, "icons1", 'inverted circular search link icon'), _defineProperty(_this15$state, "retiroc", ''), _defineProperty(_this15$state, "retirosaldo", 0), _defineProperty(_this15$state, "numero_cuenta", ''), _defineProperty(_this15$state, "idahorro", 0), _defineProperty(_this15$state, "diasuma", 0), _this15$state);
        _this15.handleClickMessage = _this15.handleClickMessage.bind(_this15);
        _this15.handleonBlur = _this15.handleonBlur.bind(_this15);
        return _this15;
    }

    _createClass(Captura, [{
        key: "handleClickTable",
        value: function handleClickTable(e) {}
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));

            var tasa = 0;
            if (name == "dias" || name == "fecha" || name == "importe") {
                var campo = "";
                var imp = this.state.importe;
                if (name == "dias") {
                    campo = value;
                } else {
                    campo = this.state.dias;
                    if (name == "importe") {
                        imp = value;
                    }
                }
                imp = parseFloat(imp.replace(',', ''));
                if (imp != 0) {
                    var tasafind = this.state.catInteres.filter(function (cat) {
                        return imp > cat.monto;
                    });
                    if (tasafind.length > 0) {
                        tasafind = tasafind[tasafind.length - 1];
                        var tasavalor = tasafind[campo];
                        this.setState({ tasa: tasavalor });

                        tasa = tasavalor;
                    } else {
                        this.setState({ tasa: 0 });
                    }
                } else {
                    this.setState({ tasa: 0 });
                }
            }

            if (name == "fecha" || name == "importe" || name == "dias" || name == "tasa") {
                var fecha = $('#fecha').val();
                var importe = this.state.importe;
                var dias = this.state.dias;
                //            let tasa = this.state.tasa;

                if (name == "importe") {
                    importe = value;
                } else if (name == "dias") {
                    dias = value;
                } else if (name == "tasa") {
                    tasa = value;
                }
                if (fecha != '' && dias != 0) {
                    var fec = moment(fecha, 'DD/MM/YYYY').add(dias, 'day').format('DD/MM/YYYY');

                    var fechahab = this.dia_habil(fec);
                    var a = moment(fecha, 'DD/MM/YYYY');
                    var b = moment(fechahab, 'DD/MM/YYYY');
                    var diasfinal = b.diff(a, 'days');

                    this.setState({ fechafin: fechahab });
                    //calcular interes en base a la fecha 

                    this.calculaInteres(importe, diasfinal, tasa);
                }
                if (importe != 0 && tasa != 0) {

                    this.calculaInteres(importe, dias, tasa);
                } else {
                    this.setState({ importeletra: '' });
                }
                return;
            }
            if (name == "retiroc") {
                if (value != 'V') {
                    this.setState({
                        retirosaldo: '',
                        numero_cuenta: '',
                        idahorro: 0
                    });
                    return;
                }
                var idcredito = this.state.idcredito;
                var forma = this;
                var object = {
                    url: base_url + ("api/CarteraV1/saldoCtaAhorros/" + idcredito),
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    var data = response.result[0];
                    var retirosaldo = data['saldo'];
                    //                let retirosaldo = numeral(data['saldo']).format('0,0.00');
                    forma.setState({
                        retirosaldo: retirosaldo,
                        numero_cuenta: data['numero_cuenta'],
                        idahorro: data['idahorro']

                    });
                }, function reject(reason) {
                    var response = validaError(reason);
                });

                return;
            }
        }
    }, {
        key: "dia_habil",
        value: function dia_habil(fecha) {
            var fechaReturn = fecha;
            var conteo = 0;
            while (true) {
                var nWeek = moment(fechaReturn, 'DD/MM/YYYY').format('e');
                var diamas = 0;
                //Determina si el dia de la semana es Sabado o Domingo                
                if (nWeek == 6 || nWeek == 0) {
                    diamas = 1;
                    if (nWeek == 6) {
                        diamas = 2;
                    }
                    fechaReturn = moment(fechaReturn, 'DD/MM/YYYY').add(diamas, 'day').format('DD/MM/YYYY');
                }

                var festivo = this.state.catFestivos.filter(function (fes) {
                    return fechaReturn == fes.fecha;
                });
                if (festivo.length == 0) {
                    break;
                }
                fechaReturn = moment(fechaReturn, 'DD/MM/YYYY').add(1, 'day').format('DD/MM/YYYY');

                conteo++;
                if (conteo == 3) {
                    break;
                }
            }
            return fechaReturn;
        }
    }, {
        key: "calculaInteres",
        value: function calculaInteres(importe, dias, tasa) {
            var interes = numeral(parseFloat(importe.replace(',', '')) * dias * (tasa / 100) / 360).format('0,0.00');
            var total = parseFloat(importe.replace(',', '')) + parseFloat(interes.replace(',', ''));
            this.setState({ interes: interes, total: total });

            var importeletras = covertirNumLetras(total);
            this.setState({ importeletra: importeletras });
        }
    }, {
        key: "handleonBlur",
        value: function handleonBlur(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            if (name == "fecha") {
                var fecha = $('#fecha').val();
                var dias = this.state.dias;
                var importe = this.state.importe;
                var tasa = this.state.tasa;

                if (fecha != '' && dias != 0) {
                    var fec = moment(fecha, 'DD/MM/YYYY').add(dias, 'day').format('DD/MM/YYYY');
                    var fechahab = this.dia_habil(fec);
                    var a = moment(fecha, 'DD/MM/YYYY');
                    var b = moment(fechahab, 'DD/MM/YYYY');
                    var diasfinal = b.diff(a, 'days');

                    this.setState({ fechafin: fechahab });

                    this.calculaInteres(importe, diasfinal, tasa);
                }
                return;
            }
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
                url: base_url + "/api/GeneralV1/getPlazos",
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                forma.setState({
                    catPlazos: response.cat_plazos, catInteres: response.cat_interes, catFestivos: response.cat_festivos
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
            var forma = this;
            var object = {
                url: base_url + ("/api/CarteraV1/findAcre/" + value),
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                forma.setState({
                    nombre: response.result[0].nombre, idcredito: response.result[0].acreditadoid,
                    message: response.message, statusmessage: 'ui floating positive message', activo: 1,
                    icons1: 'inverted circular search link icon'
                });
                forma.autoReset();
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
                    idcredito: 0,
                    message: 'Acreditado inexistente', nombre: '',
                    statusmessage: 'ui negative floating message',
                    icons1: 'inverted circular search link icon'
                });
                forma.autoReset();
            });
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            var rulesAhorro = void 0;
            var rulesCuenta = void 0;
            if (this.state.retiroc == "V") {
                rulesAhorro = [{ type: 'empty',
                    prompt: 'Ahorro requerido'
                }, {
                    type: 'number',
                    prompt: 'Cantidad incorrecta'
                }];

                rulesCuenta = [{ type: 'empty',
                    prompt: 'Cuenta requerido'
                }];
            }

            $('.ui.form.forminv').form({
                inline: true,
                on: 'blur',
                fields: {
                    fecha: {
                        identifier: 'fecha',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la fecha'
                        }]
                    },
                    importe: {
                        identifier: 'importe',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    dias: {
                        identifier: 'dias',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    tasa: {
                        identifier: 'tasa',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    interes: {
                        identifier: 'interes',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    fechafin: {
                        identifier: 'fechafin',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    total: {
                        identifier: 'total',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    retiroc: {
                        identifier: 'retiroc',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    retirosaldo: {
                        identifier: 'retirosaldo',
                        rules: rulesAhorro
                    },
                    numero_cuenta: {
                        identifier: 'numero_cuenta',
                        rules: rulesCuenta
                    }

                }
            });

            $('.ui.form.forminv').form('validate form');
            var valida = $('.ui.form.forminv').form('is valid');
            var fecha = $("#fecha").val();
            fecha = moment(fecha, 'DD/MM/YYYY').format('DD/MM/YYYY');
            $("#fecha").val(fecha);
            var $form = $('.get.solinver form'),
                Folio = $form.form('set values', { fecha: fecha });
            this.setState({ message: 'Datos incompletos!', statusmessage: 'ui hidden message' });

            if (this.state.retiroc == "V") {
                var compa1 = numeral(this.state.retirosaldo).format('0.00');
                var compa2 = numeral(this.state.importe).format('0.00');

                if (parseFloat(compa2) > parseFloat(compa1)) {
                    this.setState({ message: 'Saldo de Ahorro insuficiente !' });
                    valida = false;
                }
            }

            if (valida == true) {
                var $form = $('.get.solinver form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var forma = this;
                var idacredita = this.state.idcredito;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {

                        var object = {
                            url: base_url + ("api/CarteraV1/add_inversion/" + idacredita),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            var numero = response.registros;
                            $("#fecha").val('');
                            var $form = $('.get.solinver form'),
                                Folio = $form.form('set values', { dias: '0', fecha: '', retiroc: '' });
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ', fecha: '', importe: '',
                                dias: '0', tasa: '', interes: '', fechafin: '', total: '', numero: '', activo: 0,
                                visible: false, retiroc: '', numero_cuenta: '', retirosaldo: '', idahorro: 0
                            });
                            forma.autoReset();

                            forma.printInversion(numero);
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
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset();
            }
        }
    }, {
        key: "handleButton",
        value: function handleButton(opc, e) {
            if (opc == 0) {
                $("#fecha").val('');
                var $form = $('.get.solinver form'),
                    Folio = $form.form('set values', { dias: '0', fecha: '', retiroc: '' });
                this.setState({ idacreditado: '',
                    activo: 0,
                    dias: 0,
                    nombre: '',
                    fecha: '',
                    importe: '',
                    tasa: '',
                    fechafin: '',
                    interes: '',
                    total: '',
                    catInversion: [],
                    visible: false,
                    numero_cuenta: '',
                    idahorro: 0,
                    retirosaldo: '',
                    retiroc: ''
                });
            } else if (opc == 2) {
                this.findInversion(false);
            } else if (opc == 3) {
                this.findInversion(true);
            }
        }
    }, {
        key: "findInversion",
        value: function findInversion(visualizar) {
            var bgenera = false;
            if (this.state.idacreditado != 0) {
                var visible = this.state.visible;
                if (visualizar == false) {
                    this.setState({ visible: !visible });
                }
                if (visible == false) {
                    if (this.state.catInversion.length == 0 && visualizar == false) {
                        bgenera = true;
                    } else if (this.state.visible == true && visualizar == true) {
                        bgenera = true;
                    }
                } else if (this.state.visible == true && visualizar == true) {
                    bgenera = true;
                }
            }

            if (bgenera) {
                var forma = this;
                var idacredita = this.state.idcredito;
                var object = {
                    url: base_url + ("/api/CarteraV1/getAllInversion/" + idacredita),
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    forma.setState({
                        catInversion: response.result
                    });
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        catInversion: []
                    });
                });
            }
        }
    }, {
        key: "printInversion",
        value: function printInversion(numero, e) {
            var surl = "contratoinver/" + numero;
            var a = document.createElement('a');
            a.href = base_url + 'api/ReportV1/' + surl;
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
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
            var _this16 = this;

            var complemento = null;
            if (this.state.movimiento == "10") {
                //Cheque
                complemento = React.createElement(
                    "div",
                    { className: "fields" },
                    React.createElement(SelectOption, { id: "idbancodet", cols: "three wide", label: "Banco", valor: this.state.idbancodet, valores: this.state.catBancos, onChange: this.handleInputChange.bind(this) }),
                    React.createElement(InputField, { id: "cheque_ref", cols: "two wide", label: "No de Cheque", valor: this.state.cheque_ref, onChange: this.handleInputChange.bind(this) }),
                    React.createElement(InputField, { id: "afavor", cols: "eleven wide", label: "A favor de", valor: this.state.afavor, onChange: this.handleInputChange.bind(this) })
                );
            } else if (this.state.movimiento = "01") {
                //efectivo
                complemento = React.createElement(
                    "div",
                    { className: "fields" },
                    React.createElement("input", { id: "idahorro", readOnly: "readOnly", name: "idahorro", type: "hidden", value: this.state.idahorro }),
                    React.createElement(InputField, { id: "numero_cuenta", readOnly: "readOnly", cols: "three wide", label: "Cuenta ", valor: this.state.numero_cuenta, onChange: this.handleInputChange.bind(this) })
                );
            }
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
                            "Apertura de inversi\xF3n"
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
                                { className: "ui button", "data-tooltip": "Movimientos" },
                                React.createElement("i", { className: "list icon", onClick: this.handleButton.bind(this, 2) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Actualizar" },
                                React.createElement("i", { className: "refresh icon", onClick: this.handleButton.bind(this, 3) })
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "right menu" },
                            React.createElement(
                                "div",
                                { className: "item ui fluid category search searchtext" },
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
                    { className: "get solinver" },
                    React.createElement(
                        "form",
                        { className: "ui form forminv", ref: "form", onSubmit: this.handleSubmit.bind(this), method: "post" },
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
                                React.createElement(SelectDropDown, { cols: "three wide", name: "retiroc", id: "retiroc", label: "Origen", valor: this.state.retiroc, valores: [{ name: "Seleccione", value: "" }, { name: "Efectivo", value: "E" }, { name: "Ahorro Voluntario", value: "V" }], onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "retirosaldo", name: "retirosaldo", readOnly: "readOnly", label: "Saldo Ahorro", valor: this.state.retirosaldo, onChange: this.handleInputChange.bind(this) }),
                                React.createElement("input", { id: "idahorro", readOnly: "readOnly", name: "idahorro", type: "hidden", value: this.state.idahorro, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "numero_cuenta", name: "numero_cuenta", readOnly: "readOnly", cols: "three wide", label: "Cuenta ", valor: this.state.numero_cuenta, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "fields" },
                                React.createElement(Calendar, { name: "fecha", cols: "four wide", label: "Fecha", valor: this.state.fecha, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                React.createElement(InputFieldNumber, { id: "importe", cols: "four wide", label: "Importe", valor: this.state.importe, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                React.createElement(SelectOption, { id: "dias", cols: "two wide", label: "Plazo", valor: this.state.dias, valores: this.state.catPlazos, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "tasa", label: "Tasa", cols: "two wide", readOnly: "readOnly", valor: this.state.tasa, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "interes", cols: "four wide", readOnly: "readOnly", label: "Interes", valor: this.state.interes, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "fields" },
                                React.createElement(InputField, { id: "fechafin", cols: "three", readOnly: "readOnly", label: "Fecha vencimiento", valor: this.state.fechafin, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "total", cols: "three", readOnly: "readOnly", label: "Total", valor: this.state.total, onChange: this.handleInputChange.bind(this) }),
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
                                        " Enviar"
                                    )
                                )
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: this.state.visible === true ? "" : "hidden" },
                    React.createElement(Table, { datos: this.state.catInversion, onClick: this.handleClickTable.bind(this) })
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