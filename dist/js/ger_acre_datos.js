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

var Mensaje = function (_React$Component2) {
    _inherits(Mensaje, _React$Component2);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this3 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this3.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this3;
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

var RecordDetalle = function (_React$Component3) {
    _inherits(RecordDetalle, _React$Component3);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        return _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));
    }

    _createClass(RecordDetalle, [{
        key: "render",
        value: function render() {
            //let checked = this.props.registro.activo ? <i className="green checkmark icon"></i> : '' ;
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idacreditado
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.id_isis
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idsucursal
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.persona
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.estado_civil
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_nac
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.edad
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.sexo
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.cp
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.localidad
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.municipio
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.aportacion_social
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.actividad
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.dependientes
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.tipovivienda
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.aguapot
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.enerelec
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.drenaje
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.colmena
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.grupo
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.telefono
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

        return _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));
    }

    _createClass(Table, [{
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            var nombre = this.name;

            datos.forEach(function (record) {
                rows.push(React.createElement(RecordDetalle, { registro: record }));
            });
            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                "div",
                null,
                React.createElement(
                    "table",
                    { "class": "ui very compact table" },
                    React.createElement(
                        "thead",
                        null,
                        React.createElement(Lista, { enca: ['No', 'Isis', 'Suc', 'Nombre', 'Persona', 'Edo.Civil', 'Fec.Nac.', 'Edad', 'Sexo', 'CP', 'Localidad', 'Municipio', 'Ap.Soc.', 'Actividad', 'Dep.', 'Vivienda', 'Agua', 'Elec.', 'Drenaje', 'Colmena', 'Grupo', 'Teléfono'] })
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

var Captura = function (_React$Component5) {
    _inherits(Captura, _React$Component5);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this6 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this6.state = {
            blnActivar: true,
            csrf: "", message: "",
            stepno: 1,
            idfecha: "",
            acre_datos: [],
            statusmessage: 'ui floating hidden message',
            boton: 'Enviar', btnAutoriza: 'Autorizar', icons1: 'inverted circular search link icon'
        };
        return _this6;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            $.ajax({
                url: base_url + '/api/GeneralD1/get_provisiones_fecha',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catfechas: response.catfechas
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error');
                    console.log('error', xhr);
                    console.log('error', status);
                    console.log('error', err);
                }.bind(this)
            });
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
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
        key: "handleClickNext",
        value: function handleClickNext(e) {
            if (this.state.stepno < 2) {
                this.setState(function (prevState, props) {
                    return {
                        stepno: prevState.stepno + 1
                    };
                });
            }
        }
    }, {
        key: "handleClickPrevious",
        value: function handleClickPrevious(e) {
            if (this.state.stepno > 1) {
                this.setState(function (prevState, props) {
                    return {
                        stepno: prevState.stepno - 1
                    };
                });
            }
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value) {}
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            if (e === 1) {
                var forma = this;
                $.ajax({
                    url: base_url + '/api/CarteraD1/get_acreditados_data',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {

                        forma.setState({
                            acre_datos: response.acre_datos
                        });
                    }.bind(this),
                    error: function (xhr, status, err) {
                        console.log('error');
                    }.bind(this)
                });
            } else {
                var link = "";
                if (e === 2) {
                    link = 'html_datos_acre';
                } else {
                    link = 'html_sin_datos_acre';
                }
                link = link + "/1";

                var a = document.createElement('a');
                a.href = base_url + 'api/ReportD1/' + link;
                a.target = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
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
            var _this7 = this;

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
                            "Datos de socias"
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
                                { className: "ui button", "data-tooltip": "Mostrar" },
                                React.createElement("i", { className: "search icon", onClick: this.handleButton.bind(this, 1) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Datos de la socia" },
                                React.createElement("i", { className: "search icon", onClick: this.handleButton.bind(this, 2) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Sin datos" },
                                React.createElement("i", { className: "search icon", onClick: this.handleButton.bind(this, 3) })
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
                            return _this7.setState({ message: '', statusmessage: 'ui message hidden' });
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
                            null,
                            React.createElement(Table, { datos: this.state.acre_datos })
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
                    description: item.idcredito + ' : ' + item.idpagare
                });
            });
            return response;
        }
    }
});