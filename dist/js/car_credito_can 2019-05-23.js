"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

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

var Calendar = function (_React$Component2) {
    _inherits(Calendar, _React$Component2);

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

var InputField = function (_React$Component3) {
    _inherits(InputField, _React$Component3);

    function InputField(props) {
        _classCallCheck(this, InputField);

        return _possibleConstructorReturn(this, (InputField.__proto__ || Object.getPrototypeOf(InputField)).call(this, props));
    }

    _createClass(InputField, [{
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
                    React.createElement("input", { id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, type: "text", value: this.props.valor, placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this5.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputField;
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
                    React.createElement("input", { type: "text", id: this.props.id, name: this.props.id, value: this.props.valor, onChange: function onChange(event) {
                            return _this7.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var InputFieldFind = function (_React$Component5) {
    _inherits(InputFieldFind, _React$Component5);

    function InputFieldFind(props) {
        _classCallCheck(this, InputFieldFind);

        return _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this9 = this;

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
                    React.createElement("input", { id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, value: this.props.valor, type: "text", placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this9.props.onChange(event);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this9.props.onClick(event, _this9.props.valor, _this9.props.name);
                        } })
                )
            );
        }
    }]);

    return InputFieldFind;
}(React.Component);

var SelectDropDown = function (_React$Component6) {
    _inherits(SelectDropDown, _React$Component6);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this10 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this10.state = {
            value: ""
        };
        _this10.handleSelectChange = _this10.handleSelectChange.bind(_this10);
        return _this10;
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

var Mensaje = function (_React$Component7) {
    _inherits(Mensaje, _React$Component7);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this11 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this11.state = {
            icon: "delete icon",
            titulo: "Eliminar",
            pregunta: "¿Desea eliminar el registro?"
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

var Captura = function (_React$Component8) {
    _inherits(Captura, _React$Component8);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this12 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this12.state = {
            activo: 0,
            idcredito: '',
            catCreditos: [],
            idsucursal: '',
            dias: '',
            idpagare: '',
            nombre: '',
            fecha: '',
            fecha_entrega: '',
            importe: '',

            csrf: "", message: "",
            statusmessage: 'ui floating hidden message', stepno: 1, boton: 'Eliminar', disabledboton: 'disabled', readOnly: '',
            icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
        };
        return _this12;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));
            if (value != "" && name === "idcredito") {
                var link = "";
                var forma = this;
                link = "getcreditos_eliminar";
                link = link + "/" + event.target.value;
                var object = {
                    url: base_url + 'api/CarteraD1/' + link,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {

                    var fechaTemp = new Date(response.catCredito[0].fecha_entrega_col);
                    fechaTemp = fechaTemp.getDate() + '/' + (fechaTemp.getMonth() + 1) + '/' + fechaTemp.getFullYear();
                    var fechaTemp2 = new Date(response.catCredito[0].fecha);
                    fechaTemp2 = fechaTemp2.getDate() + '/' + (fechaTemp2.getMonth() + 1) + '/' + fechaTemp2.getFullYear();
                    forma.setState({
                        idpagare: response.catCredito[0].idpagare,
                        nombre: response.catCredito[0].acreditado,
                        importe: response.catCredito[0].monto,
                        //direccion: response.catCredito[0].direccion,
                        fecha: fechaTemp,
                        fecha_entrega: fechaTemp2
                    });
                    var $form = $('.get.soling form'),
                        Folio = $form.form('set values', {
                        idpagare: response.catCredito[0].idpagare,
                        nombre: response.catCredito[0].acreditado,
                        importe: response.catCredito[0].monto,
                        //direccion: response.catCredito[0].direccion,
                        fecha: fechaTemp,
                        fecha_entrega: fechaTemp2
                    });
                    forma.setState({
                        disabledboton: '',
                        message: response.message,
                        statusmessage: 'ui positive floating message ',
                        boton: 'Eliminar', icons1: 'inverted circular search link icon'

                    });
                }, function reject(reason) {
                    forma.setState({
                        message: validaError(reason).message,
                        statusmessage: 'ui negative floating message'
                    });
                });
            } else if (name === "idcolmena" && value == "") {
                this.setState({ catGrupos: [] });
            }
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    idcredito: {
                        identifier: 'idcredito',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el crédito '
                        }, {
                            type: 'integer[1..9999]',
                            prompt: 'Requiere un valor'
                        }]
                    }
                }
            });

            $('.ui.form.formgen').form('validate form');
            var valida = $('.ui.form.formgen').form('is valid');
            this.setState({ message: '', statusmessage: 'ui message hidden' });

            if (valida == true) {
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'POST';
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/CarteraD1/credito_eliminar',
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
                                activo: 0,
                                boton: 'Eliminar', disabledboton: 'disabled'
                            });
                            forma.clearData();
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
        key: "handleFind",
        value: function handleFind(event, value, name) {
            if (name == "dias") {
                this.setState({ numero: value, icons2: 'spinner circular inverted blue loading icon' });
                var numero = value;
            }
            var forma = this;
            var object = {
                url: base_url + 'api/CarteraD1/getcreditos_sin_entregar/' + numero,
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                forma.setState({
                    idcredito: '',
                    catCreditos: response.catCreditos
                });
                var $form = $('.get.soling form'),
                    Folio = $form.form('set values', {
                    idcredito: '',
                    catCreditos: response.catCreditos
                });
                forma.setState({
                    message: response.message,
                    statusmessage: 'ui positive floating message ',
                    boton: 'Eliminar', icons1: 'inverted circular search link icon'

                });
                forma.autoReset();
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
                    boton: 'Enviar',
                    message: response.message,
                    statusmessage: 'ui negative floating message',
                    icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
                });
                console.log('error' + reason);
                forma.clearData();
                forma.autoReset();
            });
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            var forma = this;
            if (e == 1) {
                this.clearData();
            }
        }
    }, {
        key: "clearData",
        value: function clearData() {
            this.setState({
                dias: '',
                idcredito: '',
                idpagare: '',
                nombre: '',
                importe: '',
                fecha: '',
                fecha_entrega: '',
                disabledboton: 'disabled'
            });
            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                dias: '',
                idcredito: '',
                idpagare: '',
                nombre: '',
                importe: '',
                fecha: '',
                fecha_entrega: ''
            });
            $('.get.soling .ui.dropdown').dropdown('clear');
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
        key: "handleState",
        value: function handleState(value, e) {
            this.setState({
                stepno: value
            });
        }
    }, {
        key: "render",
        value: function render() {
            var _this13 = this;

            var today = new Date();
            var cgrupo = "";
            var tgrupo = "";
            if (this.state.disabledboton == "disabled") {
                cgrupo = " hidden ";
            } else {
                tgrupo = " hidden ";
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
                            "Cambio de grupo del acreditado"
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
                                { className: "ui button", "data-tooltip": "Nuevo" },
                                React.createElement("i", { className: "plus square outline icon", onClick: this.handleButton.bind(this, 1) })
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
                            window.clearTimeout(_this13.timeout);
                            _this13.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get soling" },
                    React.createElement(
                        "form",
                        { className: "ui form formgen", ref: "form", onSubmit: this.handleSubmit.bind(this), method: "post" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "two fields" },
                            React.createElement(InputFieldFind, { cols: "two wide", icons: this.state.icons1, id: "dias", name: "dias", valor: this.state.dias, label: "Dias transcurridos", placeholder: "Buscar", onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: "field" },
                            React.createElement(SelectDropDown, { name: "idcredito", id: "idcredito", label: "Credito", valor: this.state.idcredito, valores: this.state.catCreditos, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: this.state.idcredito == '' ? 'step hidden' : '' },
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputField, { cols: "ten wide", readOnly: "readOnly", id: "idpagare", label: "Pagare", name: "idpagare", valor: this.state.idpagare, onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                React.createElement(InputField, { readOnly: "readOnly", id: "nombre", label: "Nombre", name: "nombre", valor: this.state.nombre, onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "three fields" },
                                React.createElement(InputFieldNumber, { readOnly: "readOnly", id: "importe", label: "Importe", name: "importe", valor: this.state.importe, onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                React.createElement(InputField, { readOnly: "readOnly", id: "fecha", label: "Fecha alta", name: "fecha", valor: this.state.fecha, onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                React.createElement(InputField, { readOnly: "readOnly", id: "fecha_entrega", label: "Fecha entrega", name: "fecha_entrega", valor: this.state.fecha_alta, onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) })
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "field" },
                            React.createElement(
                                "button",
                                { className: "ui submit bottom primary basic button", type: "submit", name: "action", disabled: this.state.disabledboton },
                                React.createElement("i", { className: "send icon" }),
                                " ",
                                this.state.boton,
                                " "
                            )
                        ),
                        React.createElement("div", { className: "row" })
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
    minCharacters: 4,
    apiSettings: {
        url: base_url + 'api/CarteraD1/colmena_query?q={query}',
        onResponse: function onResponse(Response) {
            var response = {
                results: {}
            };
            if (!Response || !Response.result) {
                return;
            }
            $.each(Response.result, function (index, item) {
                var colmena = item.numero || 'Sin asignar',
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
                    description: item.empresa + ' - ' + item.promotor
                });
            });
            return response;
        }
    }
});