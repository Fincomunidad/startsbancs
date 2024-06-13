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
            var tipo = this.props.id == "password" ? "password" : "text";
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
                    React.createElement("input", { className: may, id: this.props.id, name: this.props.id, type: tipo, readOnly: this.props.readOnly, value: this.props.valor, placeholder: this.props.placeholder, onChange: function onChange(event) {
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

        var _this5 = _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));

        _this5.state = {
            value: ''
        };
        return _this5;
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this6 = this;

            //                        <input id={this.props.id} name={this.props.id} type="text" placeholder={this.props.placeholder} onChange={event => this.setState({ value: event.target.value})}/>
            //                         <i className={this.props.icons} onClick={event => this.props.onClick(event, this.state.value)}></i>

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
                            return _this6.props.onChange(event);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this6.props.onClick(event, _this6.props.valor, _this6.props.id);
                        } })
                )
            );
        }
    }]);

    return InputFieldFind;
}(React.Component);

var Mensaje = function (_React$Component4) {
    _inherits(Mensaje, _React$Component4);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this7 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this7.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this7;
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

var Captura = function (_React$Component5) {
    _inherits(Captura, _React$Component5);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this8 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this8.state = {
            idexiste: "",
            edocivil_nombre: "",
            actividad_nombre: "",
            idacreditado: "", nosocio: "", domicilio: "",
            acreditado_id: "", acreditado_nombre: "",
            nocolmena: "", idcolmena: "",
            colmena_nombre: "", colmena_grupo: "", blnGrupo: false,
            csrf: "", message: "",
            statusmessage: 'ui floating hidden message',
            stepno: 1,
            blnActivar: true,
            fecha_aprov: null, usuario_aprov: null,
            identify: null, password: null,
            //amortizaciones: [],
            boton: 'Enviar', btnAutoriza: 'Autorizar', icons1: 'inverted circular search link icon'
        };
        return _this8;
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
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {}
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
        key: "asignaAcreditado",
        value: function asignaAcreditado(data) {
            var today = new Date();
            var miDir = this.setState({
                blnActivar: false,
                nosocio: data.idacreditado,

                acreditado_nombre: data.nombre,

                colmena_nombre: data.col_nombre,
                colmena_grupo: data.grupo_nombre,

                domicilio: data.direccion == null ? "" : data.direccion
            });

            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {});
        }
    }, {
        key: "asignaSolicitudNew",
        value: function asignaSolicitudNew() {
            this.setState({
                blnActivar: true,
                domicilio: "",

                acreditado_nombre: "",
                idacreditado: "",
                colmena_nombre: "",
                colmena_grupo: "",
                idexiste: "",
                stepno: 1
            });
            /*
            var $form = $('.get.soling form'),
            Folio = $form.form('set values', { 
                //blnActivar:true,
                acreditado_nombre:"",
                domicilio: "",
                colmena_nombre: "", 
                colmena_grupo: ""
            });
            $('.get.soling .ui.dropdown')
            .dropdown('clear')
            ;
            */
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value) {
            if (value != "") {
                //idretiro: value,
                this.setState({
                    icons1: 'spinner circular inverted blue loading icon' });

                var forma = this;
                var object = {
                    url: base_url + 'api/CarteraD1/get_acreditado_gar/' + value,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {

                    forma.asignaAcreditado(response.acreditado[0]);
                    forma.setState({
                        message: response.message,
                        statusmessage: 'ui positive floating message ',
                        icons1: 'inverted circular search link icon'
                    });
                    /*
                    forma.setState((prevState, props) => ({
                        idexiste: idretiro
                    }));
                    */
                    forma.autoReset();
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        boton: "Enviar",
                        csrf: response.newtoken,
                        message: response.message,
                        statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                    });
                    forma.asignaSolicitudNew();
                    forma.autoReset();
                });
            } else {
                var _forma = this;
                _forma.setState({
                    message: "Ingrese el número de la solicitud de crédito",
                    statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                });
                _forma.autoReset();
            }
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            if (e < 2) {
                this.asignaSolicitudNew();
                var $form = $('.get.soling form'),
                    Folio = $form.form('set values', {
                    idacreditado: ""
                });
            } else if (e == 17) {
                if (this.state.blnActivar == false) {
                    var d = new Date();
                    var id = this.state.idretiro;

                    var link = "";
                    link = "pdf_retgarantia_acreditado";
                    link = link + "/" + this.state.idacreditado;
                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportD1/' + link;
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            }
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
            var _this9 = this;

            var today = new Date();
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
                            "Solicitud de retiro de garantia"
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
                                { className: "ui button", "data-tooltip": "Nuevo Registro" },
                                React.createElement("i", { className: "plus square outline icon", onClick: this.handleButton.bind(this, 0) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Retiro de garantia PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 17) })
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
                            return _this9.setState({ message: '', statusmessage: 'ui message hidden' });
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
                            { className: this.state.blnActivar === false ? "disablediv" : "" },
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputFieldFind, { icons: this.state.icons1, id: "idacreditado", cols: "two wide", label: "No. acreditado", placeholder: "Buscar", valor: this.state.idacreditado, onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) })
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "ui mini steps" },
                            React.createElement(Steps, { valor: this.state.stepno, value: "1", icon: "folder outline icon", titulo: "Datos Personales", onClick: this.handleState.bind(this, 1) })
                        ),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 1 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: this.state.blnActivar === false ? "disablediv" : "" },
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(InputField, { id: "acreditado_nombre", label: "Nombre del acreditado:", readOnly: "readOnly", valor: this.state.acreditado_nombre, onChange: this.handleInputChange.bind(this) })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputField, { id: "colmena_nombre", label: "Colmena:", readOnly: "readOnly", valor: this.state.colmena_nombre, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "colmena_grupo", label: "Grupo:", readOnly: "readOnly", valor: this.state.colmena_grupo, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "field" },
                                React.createElement(InputField, { id: "domicilio", mayuscula: "true", label: "Domicilio", valor: this.state.domicilio, onChange: this.handleInputChange.bind(this) })
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

$('.ui.search').search({
    type: 'category',
    minCharacters: 8,
    apiSettings: {
        url: base_url + 'api/CarteraD1/find_acreditados?q={query}',
        onResponse: function onResponse(Response) {
            var response = {
                results: {}
            };
            if (!Response || !Response.result) {
                return;
            }
            $.each(Response.result, function (index, item) {
                var sucursal = item.idacreditado || 'Sin asignar',
                    maxResults = 8;
                if (index >= maxResults) {
                    return false;
                }
                // create new language category
                if (response.results[sucursal] === undefined) {
                    response.results[sucursal] = {
                        name: sucursal,
                        results: []
                    };
                }
                // add result to category
                response.results[sucursal].results.push({
                    title: item.nombre,
                    description: item.idretiro + ' : ' + item.idpagare
                });
            });
            return response;
        }
    }
});