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

var InputField = function (_React$Component2) {
    _inherits(InputField, _React$Component2);

    function InputField(props) {
        _classCallCheck(this, InputField);

        return _possibleConstructorReturn(this, (InputField.__proto__ || Object.getPrototypeOf(InputField)).call(this, props));
    }

    _createClass(InputField, [{
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
                    React.createElement("input", { id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, type: "text", value: this.props.valor, placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this4.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputField;
}(React.Component);

var InputFieldFind = function (_React$Component3) {
    _inherits(InputFieldFind, _React$Component3);

    function InputFieldFind(props) {
        _classCallCheck(this, InputFieldFind);

        return _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this6 = this;

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
                            return _this6.props.onChange(event);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this6.props.onClick(event, _this6.props.valor, _this6.props.name);
                        } })
                )
            );
        }
    }]);

    return InputFieldFind;
}(React.Component);

var SelectDropDown = function (_React$Component4) {
    _inherits(SelectDropDown, _React$Component4);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this7 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this7.state = {
            value: ""
        };
        _this7.handleSelectChange = _this7.handleSelectChange.bind(_this7);
        return _this7;
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

var Captura = function (_React$Component6) {
    _inherits(Captura, _React$Component6);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this9 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this9.state = { activo: 0,
            csrf: "", message: "",
            statusmessage: 'ui floating hidden message', stepno: 1, boton: 'Enviar', disabledboton: '', readOnly: '',
            icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon',
            idcolmena: 0, catColmenas: [],
            idpromotor: 0, catPromotor: [],
            idempresa: "", catEmpresa: []
        };
        return _this9;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });

            $.ajax({
                url: base_url + '/api/CarteraD1/get_colmenas',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catColmenas: response.catcolmenas,
                        catPromotor: response.catpromotor,
                        catEmpresa: response.catempresa
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error');
                }.bind(this)
            });
        }
    }, {
        key: "asignaColmena",
        value: function asignaColmena(data) {
            this.setState({
                idpromotor: data.idpromotor,
                idempresa: data.idempresa
            });
            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                idpromotor: data.idpromotor,
                idempresa: data.idempresa
            });
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));

            if (name === "idcolmena" && value != "") {
                var forma = this;
                var idcolmena = value;
                var object = {
                    url: base_url + ("/api/CarteraD1/get_colmena_promotor/" + idcolmena),
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    forma.asignaColmena(response.result[0]);
                    forma.setState({
                        disabledboton: ''
                    });
                    var $form = $('.get.soling form'),
                        Folio = $form.form('set values', {
                        idgrupo: idgrupo
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
            var rulesColmena = [{
                type: 'empty',
                prompt: 'Seleccione la colmena '
            }];
            if (this.state.idcolmena == "0") {
                rulesColmena = [{
                    type: 'integer[1]',
                    prompt: 'Seleccione la colmena '
                }];
            }

            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    idpersona: {
                        identifier: 'idpromotor',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione al promotor '
                        }, {
                            type: 'integer[1..9999]',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    idcolmena: {
                        identifier: 'idempresa',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la empresa '
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
                var tipo = 'PUT';
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/CarteraD1/set_colmena_promotor',
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
                                //acreditado_nombre:'',
                                //colmena_grupo:'',
                                //colmena_nombre:'',
                                //idcolmena:'',
                                //idgrupo:'',
                                //grupo_orden:'',
                                boton: 'Enviar', disabledboton: 'disabled'
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
            if (name == "idacreditado") {
                this.setState({ idacreditado: value, icons2: 'spinner circular inverted blue loading icon' });
                var idacreditado = value;
            }

            var forma = this;
            var object = {
                url: base_url + 'api/CarteraD1/get_acreditado_colmena_grupo/' + idacreditado,
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                forma.setState({
                    idacreditado: response.acreditado[0].idacreditado,
                    acreditado_nombre: response.acreditado[0].nombre_socio,
                    colmena_nombre: response.acreditado[0].colmena_nombre,
                    colmena_grupo: response.acreditado[0].grupo_nombre,
                    colmena_grupo_orden: response.acreditado[0].orden,
                    cargo_colmena: response.acreditado[0].cargo_colmena,
                    cargo_grupo: response.acreditado[0].cargo_grupo,

                    idcolmena: response.acreditado[0].idcolmena,
                    idgrupo: response.acreditado[0].idgrupo,
                    grupo_orden: response.acreditado[0].orden,
                    activo: 2, disabledboton: 'disabled'
                });
                forma.setState({
                    icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
                });

                forma.autoReset();
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
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

                forma.setState({
                    idcolmena: '',
                    idpromotor: '',
                    idempresa: '',

                    activo: 0,
                    boton: 'Enviar', disabledboton: 'disabled'
                });

                $('.get.soling .ui.dropdown').dropdown('clear');
            }
        }
    }, {
        key: "clearData",
        value: function clearData() {
            this.setState({
                idacreditado: '',
                acreditado_nombre: '',
                colmena_nombre: '',
                colmena_grupo: '',
                colmena_grupo_orden: '',
                cargo_colmena: '',
                cargo_grupo: '',

                idcolmena: '',
                idgrupo: '',
                grupo_orden: ''

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
        key: "handleState",
        value: function handleState(value, e) {
            this.setState({
                stepno: value
            });
        }
    }, {
        key: "render",
        value: function render() {
            var _this10 = this;

            var today = new Date();
            var pf = null;
            var pm = null;
            if (this.state.tipo == "F") {
                pf = React.createElement(InputField, { id: "rfc", readOnly: "readOnly", mayuscula: "true", label: "RFC", valor: this.state.rfc, onChange: this.handleInputChange.bind(this) });
            } else {
                pm = React.createElement(InputField, { id: "rfc", readOnly: "readOnly", mayuscula: "true", label: "RFC", valor: this.state.rfc, onChange: this.handleInputChange.bind(this) });
            }
            var cgrupo = "";
            var tgrupo = "";

            if (this.state.disabledboton == "disabled") {
                cgrupo = " hidden ";
            } else {
                tgrupo = " hidden ";
            }
            //                            <InputFieldFind icons={this.state.icons2} id="idacreditado" readOnly={'readOnly'} name="idacreditado" valor={this.state.idacreditado} cols="three wide" label="Socio" placeholder="Buscar" onChange={this.handleInputChange.bind(this)} onClick={this.handleFind.bind(this)}/>

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
                        ),
                        React.createElement(
                            "div",
                            { className: "right menu" },
                            React.createElement(
                                "div",
                                { className: "item ui fluid category search" },
                                React.createElement(
                                    "div",
                                    { className: "ui icon input" },
                                    React.createElement("input", { className: "prompt", type: "text", placeholder: "Buscar Nombre" }),
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
                            window.clearTimeout(_this10.timeout);
                            _this10.setState({ message: '', statusmessage: 'ui message hidden' });
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
                            { className: "three fields" },
                            React.createElement(SelectDropDown, { name: "idcolmena", id: "idcolmena", label: "Colmena", valor: this.state.idcolmena, valores: this.state.catColmenas, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(SelectDropDown, { name: "idpromotor", id: "idpromotor", label: "Promotor", valor: this.state.idpromotor, valores: this.state.catPromotor, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(SelectDropDown, { name: "idempresa", id: "idempresa", label: "Empresa", valor: this.state.idempresa, valores: this.state.catEmpresa, onChange: this.handleInputChange.bind(this) })
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
    minCharacters: 10,
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
                    description: item.idpersona + ' - ' + item.idacreditado + ' - ' + item.grupo_nombre
                });
            });
            return response;
        }
    }
});