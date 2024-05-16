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

        return _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));
    }

    _createClass(Calendar, [{
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
                        React.createElement("input", { ref: "myCalen", type: "text", name: this.props.name, id: this.props.name, placeholder: "Fecha" })
                    )
                )
            );
        }
    }]);

    return Calendar;
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

var InputFieldNumber = function (_React$Component3) {
    _inherits(InputFieldNumber, _React$Component3);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: "render",
        value: function render() {
            var _this5 = this;

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
                            return _this5.props.onChange(event);
                        }, onBlur: function onBlur(event) {
                            return _this5.props.onBlur(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var Mensaje = function (_React$Component4) {
    _inherits(Mensaje, _React$Component4);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this6 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this6.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this6;
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

var RecordDetalle = function (_React$Component5) {
    _inherits(RecordDetalle, _React$Component5);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        return _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));
    }

    _createClass(RecordDetalle, [{
        key: "handleClick",
        value: function handleClick(e) {
            //    console.log(this.props.registro);
            this.props.onClick(e, this.props.registro);
        }
    }, {
        key: "render",
        value: function render() {
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    this.props.registro.id
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.valor
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

var Table = function (_React$Component6) {
    _inherits(Table, _React$Component6);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this8 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this8.handleClick = _this8.handleClick.bind(_this8);
        return _this8;
    }

    _createClass(Table, [{
        key: "handleClick",
        value: function handleClick(e, valor) {
            this.props.onClick(e, valor);
        }
    }, {
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            datos.forEach(function (record) {
                rows.push(React.createElement(RecordDetalle, { registro: record, onClick: this.handleClick }));
            }.bind(this));

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
                        React.createElement(Lista, { enca: ['Id', 'Fecha', 'Valor'] })
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

var SelectDropDown = function (_React$Component7) {
    _inherits(SelectDropDown, _React$Component7);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this9 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this9.state = {
            value: ""
        };
        _this9.handleSelectChange = _this9.handleSelectChange.bind(_this9);
        return _this9;
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

var Captura = function (_React$Component8) {
    _inherits(Captura, _React$Component8);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this10 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this10.state = { activo: 0,
            catUdis: [],
            fecha: '',
            valor: 0,
            csrf: "", message: "",
            statusmessage: 'ui floating hidden message',
            icons1: 'inverted circular search link icon',
            disabledboton2: 'disabled', disabledboton3: 'disabled',
            enabledCatprua: false,
            boton: 'Enviar'
        };

        _this10.handleonBlur = _this10.handleonBlur.bind(_this10);
        return _this10;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            this.willMount();
        }
    }, {
        key: "willMount",
        value: function willMount() {
            var object = {
                url: base_url + 'api/CatalogV1/udis',
                type: 'GET',
                dataType: 'json'
            };
            var forma = this;
            ajax(object).then(function resolve(response) {
                forma.setState({ catUdis: response.result
                });
            }, function reject(reason) {
                var response = validaError(reason);
                /*            
                            forma.setState({
                                csrf: response.newtoken, 
                                message: response.message,
                                statusmessage: 'ui negative floating message', icons1:'inverted circular search link icon'  
                            });
                */
            });
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));
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
        key: "handleonClickCheck",
        value: function handleonClickCheck(e, name, valor) {
            this.setState(_defineProperty({}, name, valor == '1' ? '0' : '1'));
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {}
    }, {
        key: "handleChangeRecord",
        value: function handleChangeRecord() {}
    }, {
        key: "handleClickRecord",
        value: function handleClickRecord(e, rec) {
            this.setState({ id: rec.id,
                fecha: rec.fecha,
                valor: rec.valor });

            var monudi = rec.monudi == '1' ? true : false;

            var $form = $('.get.formgen form'),
                Folio = $form.form('set values', { monudi: monudi });
        }
    }, {
        key: "handleSubmitBen",
        value: function handleSubmitBen(event) {
            event.preventDefault();
            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    fecha: {
                        identifier: 'fecha',
                        rules: [{
                            type: 'empty',
                            prompt: 'fecha incorrecta'
                        }]
                    },
                    valor: {
                        identifier: 'movmax',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee el valor de la udi '
                        }, {
                            type: 'number',
                            prompt: 'Requiere un valor'
                        }]
                    }

                }
            });

            $('.ui.form.formgen').form('validate form');
            var valida = $('.ui.form.formgen').form('is valid');
            this.setState({ message: '', statusmessage: 'ui message hidden' });
            if (valida == true) {
                var $form = $('.get.formgen form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = this.state.boton === 'Enviar' ? 'POST' : 'PUT';
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/CatalogV1/udis/',
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
                                boton: 'Enviar'
                            });
                            forma.autoReset();
                            forma.willMount();
                        }, function reject(reason) {
                            var response = validaError(reason);
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });

                            console.log(response.message);
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
        key: "render",
        value: function render() {
            var _this11 = this;

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
                            "Captura de Udis"
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
                            window.clearTimeout(_this11.timeout);
                            _this11.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get formgen" },
                    React.createElement(
                        "form",
                        { className: "ui form small formgen", ref: "form" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "ui blue segment" },
                            React.createElement(
                                "div",
                                { className: "one fields" },
                                React.createElement(
                                    "div",
                                    { className: "three wide field" },
                                    React.createElement(Calendar, { name: "fecha", label: "Fecha alta", valor: this.state.fecha, onChange: this.handleInputChange.bind(this) })
                                ),
                                React.createElement(InputFieldNumber, { id: "valor", label: "Valor de Udi", valor: this.state.valor, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur })
                            ),
                            React.createElement(
                                "div",
                                { className: "ui vertical segment right aligned" },
                                React.createElement(
                                    "div",
                                    { className: "field" },
                                    React.createElement(
                                        "button",
                                        { className: "ui bottom primary basic button", type: "button", name: "action", onClick: this.handleSubmitBen.bind(this) },
                                        React.createElement("i", { className: "send icon" }),
                                        " Enviar"
                                    )
                                )
                            )
                        ),
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf })
                    )
                ),
                React.createElement("form", null),
                React.createElement(
                    "div",
                    null,
                    React.createElement(Table, { name: "catMov", datos: this.state.catUdis, onClick: this.handleClickRecord.bind(this) })
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

ReactDOM.render(React.createElement(Captura, null), document.getElementById('root'));