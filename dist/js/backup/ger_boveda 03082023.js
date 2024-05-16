"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var Calendar = function (_React$Component) {
    _inherits(Calendar, _React$Component);

    function Calendar(props) {
        _classCallCheck(this, Calendar);

        var _this = _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));

        _this.handleChange = _this.handleChange.bind(_this);
        return _this;
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
            var cols = "ui calendar " + (this.props.visible == false ? " hidden " : "");

            return React.createElement(
                "div",
                { className: cols, id: this.props.name },
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
                        React.createElement("input", { ref: "myCalen", type: "text", name: this.props.name, id: this.props.name, placeholder: "Fecha", onChange: this.handleChange })
                    )
                )
            );
        }
    }]);

    return Calendar;
}(React.Component);

var Mensaje = function (_React$Component2) {
    _inherits(Mensaje, _React$Component2);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this2 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this2.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this2;
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

var InputFieldNumber = function (_React$Component3) {
    _inherits(InputFieldNumber, _React$Component3);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: "render",
        value: function render() {
            var _this4 = this;

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
                            return _this4.props.onChange(event);
                        }, onBlur: function onBlur(event) {
                            return _this4.props.onBlur(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var SelectDropDown = function (_React$Component4) {
    _inherits(SelectDropDown, _React$Component4);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this5 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this5.state = {
            value: ""
        };
        _this5.handleSelectChange = _this5.handleSelectChange.bind(_this5);
        return _this5;
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

var SelectOption = function (_React$Component5) {
    _inherits(SelectOption, _React$Component5);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this6 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this6.state = {
            value: ""
        };
        return _this6;
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

var RecordDetalle = function (_React$Component6) {
    _inherits(RecordDetalle, _React$Component6);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this7 = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this7.state = {
            cantidad: 0, total: 0
        };
        return _this7;
    }

    _createClass(RecordDetalle, [{
        key: "handleChange",
        value: function handleChange(e) {
            var _this8 = this;

            var name = e.target.name;
            var value = isNaN(e.target.value) || e.target.value == "" ? "0" : e.target.value;
            var saldo = this.props.registro.saldo;

            if (parseFloat(value) <= parseFloat(saldo) || this.props.movimiento == "I") {
                this.setState({ cantidad: value,
                    total: parseFloat(this.props.registro.nombre) * parseFloat(value)
                });
            } else {
                this.setState({ cantidad: 0,
                    total: parseFloat(this.props.registro.nombre) * parseFloat(0)
                });
            }

            this.setState(function (prevState, props) {
                var anterior = numeral(prevState.total).format('0.00');
                var valoradd = numeral(_this8.state.total).format('0.00');
                var valtext = numeral($('#grantotal').val()).format('0.00');
                var final = parseFloat(valtext) - parseFloat(valoradd) + parseFloat(anterior);
                _this8.props.onChange(e, final);
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
            var saldoi = numeral(this.props.registro.saldo).format('0,0');
            var rec0 = React.createElement(
                "td",
                { className: "right aligned" },
                saldoi
            );

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
                    React.createElement("input", { className: "table-input", type: "text", name: "total[]", value: total }),
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
                rec0,
                rec1,
                rec2
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var Table = function (_React$Component7) {
    _inherits(Table, _React$Component7);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this9 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this9.state = { grantotal: _this9.props.totalxpagar };
        _this9.handleChange = _this9.handleChange.bind(_this9);
        return _this9;
    }

    _createClass(Table, [{
        key: "componentDidUpdate",
        value: function componentDidUpdate(prevProps, prevState) {
            var datos = this.props.datos;
            if (datos.length == 0 && this.state.grantotal != 0) {
                this.setState({ grantotal: 0 });
            }
        }
    }, {
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
            var rect = null;

            if (datos instanceof Array === true) {
                var conteo = 0;
                datos.forEach(function (record) {
                    conteo = conteo + 1;
                    rows.push(React.createElement(RecordDetalle, { registro: record, id: conteo, existe: this.props.existe, movimiento: this.props.movimiento, onChange: this.handleChange }));
                }.bind(this));
                rect = React.createElement("input", { className: "totalxpagar", type: "text", id: "grantotal", name: "grantotal", value: grantotal });
            } else {
                this.setState({ grantotal: 0 });
                rect = React.createElement("input", { className: "totalxpagar", type: "text", id: "grantotal", name: "grantotal", value: "0" });
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
                            React.createElement(Lista, { enca: ['Denominación', 'Saldo', 'Cantidad', 'Importe'] })
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
                                        rect
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

var Captura = function (_React$Component8) {
    _inherits(Captura, _React$Component8);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this10 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this10.state = { csrf: '',
            idclave: 0,
            catBoveda: [],
            catBancos: [],
            catDenomina: [],
            catDenoIni: [],
            ocultar: true,
            boton: 'Apertura',
            movimiento: 0,
            labdes_ori: 'Destino',
            labidbanco: 'Caja',
            des_ori: 0,
            idbanco: 0,
            importe: 0,
            totalxpagar: 0,
            message: '',
            statusmessage: 'hidden',
            idmov: 0,
            idmovdet: 0,
            existe: 0,
            idmovecho: 0,
            catMov: [],
            idmovisible: false,
            fecierreant: '',
            ocultacierre: true,
            fechaconsulta: '',
            fechafin: '',
            saldoboveda: 0
        };
        _this10.handleInputChange = _this10.handleInputChange.bind(_this10);
        _this10.handleonBlur = _this10.handleonBlur.bind(_this10);
        return _this10;
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

                    console.log(response);
                    this.setState({ catBoveda: response.boveda, catDenoIni: response.denomina
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error', xhr);
                    console.log('error', err);
                    console.log('error', status);
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

            if (name == "importe") {
                return;
            }

            var mdes_ori = this.state.des_ori;
            var mmovimiento = this.state.movimiento;
            var midbanco = this.state.idbanco;

            //Busca de acuerdo a la selección de combo la boveda a seleccionar 
            if (name == "idclave") {
                var object = {
                    url: base_url + ("api/CarteraV1/getboveda/" + value),
                    type: 'GET',
                    dataType: 'json'
                };
                var forma = this;
                ajax(object).then(function resolve(response) {
                    forma.setState({ ocultacierre: true, fecierreant: '' });

                    if (response.result == false) {

                        if (response.anterior != []) {
                            forma.setState({ boton: "Cierre", ocultar: false, ocultacierre: false, idmov: response.anterior[0]['idmov'], fecierreant: response.anterior[0]['fecinicio'], catMov: response.movimientos });
                        } else {
                            forma.setState({ boton: "Apertura", ocultar: true, ocultacierre: true, catMov: [] });
                        }
                    } else {
                        if (response.result[0]['status'] == "1") {
                            forma.setState({ boton: "Cierre", ocultar: false, ocultacierre: true, idmov: response.result[0]['idmov'], catMov: response.movimientos });
                        } else {
                            var catMovRes = response.movimientos;
                            var fecfinal = response.result[0]['fecfinal'].slice(-8);
                            catMovRes.push({ value: response.result[0]['idmov'], name: 'Cierre boveda Hora: ' + fecfinal });

                            forma.setState({ boton: "Cerrado", ocultar: true, ocultacierre: true, idmov: response.result[0]['idmov'], catMov: catMovRes,
                                idmovdet: response.result[0]['idmov'] });
                        }
                    }
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        message: response.message,
                        statusmessage: 'ui negative floating message message2', catMov: [],
                        ocultacierre: true, fecierreant: ''
                    });
                    forma.autoReset();
                });
                return;
            }

            if (name == "idmovecho") {
                this.setState({ idmovdet: value });
            } else if (name == "movimiento") {
                tit = "Destino";
                if (value == "I") {
                    tit = "Origen";
                }
                this.setState({
                    labdes_ori: tit
                });
                mmovimiento = value;
            } else if (name == "des_ori") {
                mdes_ori = value;
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

                var _object = {
                    url: base_url + ("api/" + link),
                    type: 'GET',
                    dataType: 'json'
                };

                var $form = $('.get.bovmov form'),
                    Folio = $form.form('set values', { idbanco: '0' });
                this.setState({ catBancos: [] });

                var _forma = this;
                ajax(_object).then(function resolve(response) {
                    _forma.setState({ catBancos: response.result, idbanco: 0 });
                    var $form = $('.get.bovmov form'),
                        Folio = $form.form('set values', { idbanco: '0' });
                }, function reject(reason) {
                    var response = validaError(reason);
                });
            } else if (name == "idbanco") {
                midbanco = value;
            }

            //Busca la Nota de credito o Cierre de CAJA
            if (mdes_ori == "C" && mmovimiento == "I" && midbanco != "") {
                //        if(name=="idbanco" && this.state.des_ori =="C" && this.state.movimiento == "I"){
                var idmov = this.state.idmov;
                var _object2 = {
                    url: base_url + ("api/CarteraV1/getCorteCaja/" + idmov + "/" + midbanco),
                    type: 'GET',
                    dataType: 'json'
                };
                var _forma2 = this;
                ajax(_object2).then(function resolve(response) {
                    var importe = numeral(response.mov[0].importe).format('0,0.00');
                    _forma2.setState(_defineProperty({
                        message: response.message, idmovdet: response.mov[0].idmovdet, idmovisible: false,
                        statusmessage: 'ui positive floating message message2', existe: 1,
                        totalxpagar: importe, importe: importe, catDenomina: response.movdet }, "totalxpagar", response.mov[0].importe));
                    _forma2.autoReset();
                }, function reject(reason) {
                    var response = validaError(reason);
                    _forma2.setState({
                        message: response.message, existe: 0,
                        statusmessage: 'ui negative floating message message2', importe: 0, catDenomina: [], totalxpagar: 0
                    });
                    _forma2.autoReset();
                });
                return;
            }

            if (mdes_ori != "" && mdes_ori != "0" && mmovimiento != "" && mmovimiento != "0" && midbanco != "" && midbanco != "0") {
                var _idmov = this.state.idmov;
                var _object3 = {
                    url: base_url + ("api/CarteraV1/getSaldoBoveda/" + _idmov),
                    type: 'GET',
                    dataType: 'json'
                };
                var _forma3 = this;
                ajax(_object3).then(function resolve(response) {
                    _forma3.setState({
                        existe: 0, catDenomina: response.result, importe: 0, totalxpagar: 0, saldoboveda: response.saldo
                    });
                }, function reject(reason) {
                    var response = validaError(reason);
                    _forma3.setState({
                        existe: 0, importe: 0, catDenomina: [], totalxpagar: 0, saldoboveda: 0
                    });
                    _forma3.autoReset();
                });
                return;
            } else {
                if (this.state.catDenomina != []) {
                    this.setState({
                        existe: 0, catDenomina: [], importe: 0, totalxpagar: 0
                    });
                }
                return;
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
        value: function handleButton(e, opc) {
            if (e == 1) {
                this.printReport(0);
            } else if (e == 3) {
                if (this.state.idclave != 0) {
                    var idvis = false;
                    if (this.state.idmovisible == false) {
                        idvis = true;
                    }
                    this.setState({ idmovisible: idvis });
                }
            } else if (e == 4) {
                this.findMov();
            }
        }
    }, {
        key: "findMov",
        value: function findMov() {
            if (this.state.idclave != "") {
                var forma = this;
                var idmov = this.state.idmov;
                var fechaconsulta = '';

                if (idmov == "") {
                    idmov = 0;
                }

                if ($('#fechaconsulta').val() != '') {
                    var extraer = $('#fechaconsulta').val().split('/');
                    var fec = new Date(extraer[2], extraer[1] - 1, extraer[0]);
                    fechaconsulta = moment(fec).format('DDMMYYYY');
                }
                var object = {
                    url: base_url + ("api/CarteraV1/getboveda_mov/" + idmov + "/" + fechaconsulta),
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    forma.setState({
                        catMov: response.result
                    });
                }, function reject(reason) {});
            }
        }
    }, {
        key: "printReport",
        value: function printReport(opc) {
            if (this.state.idmovdet != 0) {
                var id = this.state.idmovdet;
                var idclave = this.state.idclave;
                var surl = surl = 'bovedarep/' + id;
                if (opc == 0 && $('#data-name').text().substring(0, 19) === 'Movimientos del dia') {
                    var fechaconsulta = "0";
                    if ($('#fechaconsulta').val() != '') {
                        var extraer = $('#fechaconsulta').val().split('/');
                        var fec = new Date(extraer[2], extraer[1] - 1, extraer[0]);
                        fechaconsulta = moment(fec).format('DDMMYYYY');
                    }
                    surl = 'cajamov/' + id + '/' + fechaconsulta;
                } else if (opc == 0 && $('#data-name').text().substring(0, 24) === 'Recuperación de Créditos') {
                    var _fechaconsulta = "0";
                    if ($('#fechaconsulta').val() != '') {
                        var _extraer = $('#fechaconsulta').val().split('/');
                        var _fec = new Date(_extraer[2], _extraer[1] - 1, _extraer[0]);
                        _fechaconsulta = moment(_fec).format('DDMMYYYY');
                    }
                    surl = 'creditosmov/' + _fechaconsulta;
                } else if (opc == 0 && ($('#data-name').text().substring(0, 17) === 'Detalle de boveda' || $('#data-name').text().substring(0, 31) === 'Movimientos de boveda operativa' || $('#data-name').text().substring(0, 29) === 'Movimientos de caja operativa')) {
                    var _fechaconsulta2 = "0";
                    var fechafin = "0";
                    if ($('#fechaconsulta').val() != '') {
                        var _extraer2 = $('#fechaconsulta').val().split('/');
                        var _fec2 = new Date(_extraer2[2], _extraer2[1] - 1, _extraer2[0]);
                        _fechaconsulta2 = moment(_fec2).format('DDMMYYYY');
                    }
                    if ($('#fechafin').val() != '') {
                        var _extraer3 = $('#fechafin').val().split('/');
                        var _fec3 = new Date(_extraer3[2], _extraer3[1] - 1, _extraer3[0]);
                        fechafin = moment(_fec3).format('DDMMYYYY');
                    }

                    if ($('#data-name').text().substring(0, 17) === 'Detalle de boveda') {
                        surl = 'bovedamov/' + idclave + '/' + _fechaconsulta2 + '/' + fechafin;
                    } else if ($('#data-name').text().substring(0, 31) === 'Movimientos de boveda operativa') {
                        surl = 'bovedaopera/' + idclave + '/' + _fechaconsulta2 + '/' + fechafin;
                    } else if ($('#data-name').text().substring(0, 29) === 'Movimientos de caja operativa') {
                        surl = 'cajaopera/' + _fechaconsulta2 + '/' + fechafin;
                    }
                } else if (this.state.boton == "Cerrado") {
                    if (this.state.idmovecho == 0) {
                        surl = 'bovedacierep/' + id;
                    }
                } else if (opc == 0 && $('#data-name').text().substring(0, 13) === 'Cierre Boveda') {
                    surl = 'bovedacierep/' + id;
                } else if (opc == 0 && $('#data-name').text().substring(0, 15) === 'Saldo de boveda') {
                    var idmov = this.state.idclave;
                    surl = 'saldoboveda/' + idmov;
                }
                var a = document.createElement('a');
                a.href = base_url + 'api/ReportV1/' + surl;
                a.target = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                /*
                             $.ajax({
                                    url: base_url + 'api/ReportV1/'+surl,
                                    type: 'GET',
                                    dataType: 'text',
                                    success:function(response) {
                                        var blob=new Blob([response], { type: 'application/pdf' });
                                        var link=document.createElement('a');
                                        link.href=window.URL.createObjectURL(blob);
                                            link.target ="_blank";
                                        link.click();
                                    }.bind(this),
                                    error: function(xhr, status, err) {
                                        if (xhr.status === 404) {
                                            
                                        }else if (xhr.status === 409) {
                                            let cadena = "";
                                            let pos = xhr.responseText.indexOf('{"status"');
                                            if (pos !== 0) {
                                                cadena = xhr.responseText.substring(pos);
                                            }
                                            let arreglo = JSON.parse(cadena);
                                        }
                                    }.bind(this)
                                })   
                */
            }
        }
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
                var idant = this.state.idmov;
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
                                statusmessage: 'ui positive floating message message2 ',
                                boton: texto, idmov: response.registros, ocultar: ocultar, ocultacierre: true
                            });
                            forma.autoReset();

                            if (texto == 'Cerrado') {
                                forma.setState({ idmovecho: 0, idmovdet: idant });
                                forma.printReport(1);
                            }
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
            } else {
                if (opc != "Cierre" && opc != "Apertura") {
                    this.setState({
                        message: 'Boveda Cerrada!',
                        statusmessage: 'ui negative floating message message2'
                    });
                } else {
                    this.setState({
                        message: 'Datos incompletos!',
                        statusmessage: 'ui negative floating message message2'
                    });
                }
                this.autoReset();
            }
        }
    }, {
        key: "handleSubmitMov",
        value: function handleSubmitMov() {
            var rulesMatchImporte = void 0;
            if (esquema != 'ban.') {
                rulesMatchImporte = [{
                    type: 'match[grantotal]',
                    prompt: 'Importes diferentes!'
                }];
            }

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
                        rules: rulesMatchImporte
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

            var messageError = 'Datos incompletos!';
            if (this.state.importe == 0 && this.state.totalxpagar == 0 && this.state.movimiento == "I" && this.state.des_ori == "C") {
                //No Es posible realizar un cierre de caja en ceros.
            } else if (this.state.importe == 0 && this.state.totalxpagar == 0 && esquema != 'ban.') {
                valida = false;
            } else if (this.state.ocultacierre == false && this.state.movimiento == "E") {
                messageError = 'Movimiento incorrecto, sólo se permite Ingresos (Notas de crédito y/o Cierre de caja)!';
                valida = false;
            } else if (this.state.ocultacierre == false && this.state.movimiento == "I" && this.state.des_ori == "B") {
                messageError = 'Movimiento incorrecto, sólo se permite Ingresos (Notas de crédito y/o Cierre de caja)!';
                valida = false;
            }

            if (valida == true) {
                var $form = $('.get.bovmov form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'POST';
                var forma = this;
                var idmov = this.state.idmov;
                var fechacierre = this.state.fecierreant;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ("api/CarteraV1/add_boveda/" + idmov),
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields,
                                fechacierre: fechacierre
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            if (response.registros != 0) {
                                forma.setState({ idmovdet: response.registros });
                            }
                            var newVal = forma.state.catDenoIni;
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message message2 ',
                                idgrupo: 0, des_ori: 0, movimiento: 0, importe: 0,
                                catDenomina: newVal, totalxpagar: 0
                            });
                            var $form = $('.get.bovmov form'),
                                Folio = $form.form('set values', { idgrupo: '0', des_ori: '0', movimiento: '0' });

                            forma.autoReset();
                            forma.findMov();
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
            } else {
                this.setState({
                    message: messageError,
                    statusmessage: 'ui negative floating message message2'
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
                            "Control de Boveda"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui  basic icon buttons" },
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Nuevo Registro", onClick: this.handleButton.bind(this, 0) },
                            React.createElement("i", { className: "plus square outline icon", onClick: this.handleButton.bind(this, 0) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Formato PDF", onClick: this.handleButton.bind(this, 1) },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Formato Excel", onClick: this.handleButton.bind(this, 2) },
                            React.createElement("i", { className: "file excel outline icon", onClick: this.handleButton.bind(this, 2) })
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui basic right floated icon buttons" },
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Movimientos", onClick: this.handleButton.bind(this, 3) },
                            React.createElement("i", { className: "list icon", onClick: this.handleButton.bind(this, 3) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Actualizar", onClick: this.handleButton.bind(this, 4) },
                            React.createElement("i", { className: "refresh icon", onClick: this.handleButton.bind(this, 4) })
                        )
                    )
                ),
                React.createElement(Mensaje, null),
                React.createElement(
                    "div",
                    { className: "get bovopen" },
                    React.createElement(
                        "div",
                        { className: "ten wide column" },
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
                            React.createElement("i", { className: statusicon })
                        )
                    ),
                    React.createElement(
                        "form",
                        { className: "ui form formopen", ref: "form", onSubmit: this.handleSubmitOpen.bind(this), method: "post" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "fields" },
                            React.createElement(SelectDropDown, { cols: "four wide", visible: true, name: "idclave", id: "idclave", label: "Boveda", valor: this.state.idclave, valores: this.state.catBoveda, onChange: this.handleInputChange.bind(this) }),
                            React.createElement("div", { className: "two wide field" }),
                            React.createElement(Calendar, { visible: this.state.idmovisible, name: "fechaconsulta", label: "Fecha consulta", valor: this.state.fechaconsulta, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(Calendar, { visible: this.state.idmovisible, name: "fechafin", label: "Fecha Final", valor: this.state.fechafin, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(SelectDropDown, { cols: "seven wide", visible: this.state.idmovisible, name: "idmovecho", id: "idmovecho", label: "Movimientos", valor: this.state.idmovecho, valores: this.state.catMov, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: this.state.ocultacierre == true ? "hidden" : "ui inverted red segment" },
                            React.createElement(
                                "div",
                                { className: "ui header" },
                                "Aviso importante."
                            ),
                            React.createElement(
                                "div",
                                { className: "ui subheader" },
                                "Se ha identificado que no fue cerrada la boveda del d\xEDa ",
                                this.state.fecierreant,
                                ". ",
                                React.createElement(
                                    "b",
                                    null,
                                    "Solo se permite Notas de cr\xE9dito y/o cierre de caja,"
                                ),
                                " en cuanto termine cierre la b\xF3veda."
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "ui vertical segment left aligned" },
                            React.createElement(
                                "div",
                                { className: "ui form" },
                                React.createElement(
                                    "div",
                                    { className: "inline fields" },
                                    React.createElement(
                                        "div",
                                        { className: "four wide field" },
                                        React.createElement(
                                            "button",
                                            { className: "ui submit bottom primary basic button", type: "submit", name: "action" },
                                            React.createElement("i", { className: "send icon" }),
                                            " ",
                                            this.state.boton,
                                            " "
                                        )
                                    ),
                                    React.createElement(
                                        "div",
                                        { className: this.state.idmov != 0 ? "five wide field " : "hidden" },
                                        React.createElement(
                                            "label",
                                            null,
                                            "Saldo de b\xF3veda"
                                        ),
                                        React.createElement(
                                            "div",
                                            { className: "ten wide field" },
                                            React.createElement(
                                                "div",
                                                { className: "ui labeled input" },
                                                React.createElement(
                                                    "div",
                                                    { className: "ui label" },
                                                    "$"
                                                ),
                                                React.createElement("input", { className: "text-right", type: "text", id: "saldoboveda", name: "saldoboveda", readOnly: "readonly", value: numeral(this.state.saldoboveda).format('0,0.00'), onChange: this.handleInputChange.bind(this) })
                                            )
                                        )
                                    )
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
                            React.createElement(Table, { datos: this.state.catDenomina, existe: this.state.existe, totalxpagar: this.state.totalxpagar, movimiento: this.state.movimiento }),
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