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
                    React.createElement("input", { id: this.props.id, name: this.props.id, type: "text", readOnly: this.props.readOnly, value: this.props.valor, placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this2.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputField;
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

var ListaRow = function (_React$Component2) {
    _inherits(ListaRow, _React$Component2);

    function ListaRow() {
        _classCallCheck(this, ListaRow);

        return _possibleConstructorReturn(this, (ListaRow.__proto__ || Object.getPrototypeOf(ListaRow)).apply(this, arguments));
    }

    _createClass(ListaRow, [{
        key: "render",
        value: function render() {
            return React.createElement(
                "div",
                { className: "ui vertical segment aligned" },
                React.createElement(
                    "div",
                    { className: "field" },
                    this.props.categoria
                )
            );
        }
    }]);

    return ListaRow;
}(React.Component);

var DocumentoRow = function (_React$Component3) {
    _inherits(DocumentoRow, _React$Component3);

    function DocumentoRow(props) {
        _classCallCheck(this, DocumentoRow);

        var _this4 = _possibleConstructorReturn(this, (DocumentoRow.__proto__ || Object.getPrototypeOf(DocumentoRow)).call(this, props));

        var nombre = "iddoc_" + _this4.props.valor.idacreditado;
        _this4.state = _defineProperty({}, nombre, false);

        return _this4;
    }

    _createClass(DocumentoRow, [{
        key: "componentDidMount",
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myCheck)).on('onChange', this.handleClick);

            var nombre = "iddoc_" + this.props.valor.idacreditado;
            $('.get.soling form').form('set values', _defineProperty({}, nombre, false));
        }
    }, {
        key: "handleClick",
        value: function handleClick(e) {}
    }, {
        key: "render",
        value: function render() {

            var nombre = "iddoc_" + this.props.valor.idacreditado;
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    this.props.valor.idacreditado
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.valor.nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.valor.cargo_colmena
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.valor.cargo_grupo
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.valor.idanterior
                )
            );
        }
    }]);

    return DocumentoRow;
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

var Catalogo = function (_React$Component5) {
    _inherits(Catalogo, _React$Component5);

    function Catalogo(props) {
        _classCallCheck(this, Catalogo);

        return _possibleConstructorReturn(this, (Catalogo.__proto__ || Object.getPrototypeOf(Catalogo)).call(this, props));
    }

    _createClass(Catalogo, [{
        key: "render",
        value: function render() {
            var rows = [];
            var lastLista = null;
            this.props.valores.forEach(function (item) {
                if (item.grupo_nombre !== lastLista) {
                    rows.push(React.createElement(ListaRow, { categoria: item.grupo_nombre, key: item.grupo_nombre }));
                }
                rows.push(React.createElement(DocumentoRow, { valor: item, key: item.nombre }));
                lastLista = item.grupo_nombre;
            });

            return (
                /*
                <div className="ui segment">
                    {rows}
                </div>  
                */
                React.createElement(
                    "div",
                    { className: "ui segment" },
                    React.createElement(
                        "tbody",
                        null,
                        rows
                    )
                )
            );
        }
    }]);

    return Catalogo;
}(React.Component);

var InputFieldFind = function (_React$Component6) {
    _inherits(InputFieldFind, _React$Component6);

    function InputFieldFind(props) {
        _classCallCheck(this, InputFieldFind);

        return _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this8 = this;

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
                    React.createElement("input", { className: may, id: this.props.id, name: this.props.id, value: this.props.valor, type: "text", placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this8.props.onChange(event);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this8.props.onClick(event, _this8.props.valor, _this8.props.name);
                        } })
                )
            );
        }
    }]);

    return InputFieldFind;
}(React.Component);

var Mensaje = function (_React$Component7) {
    _inherits(Mensaje, _React$Component7);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this9 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this9.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this9;
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

        var _this10 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this10.state = {
            idcolmena: '', catcolmenas: [],
            catacreditados: [],
            boton: 'Enviar', message: "", statusmessage: 'ui floating hidden message',
            icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
        };
        return _this10;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            $.ajax({
                url: base_url + '/api/GeneralD1/get_colmenas',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catcolmenas: response.catcolmenas
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
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;

            if (name === "idcolmena") {
                if (event.target.value != "") {
                    var link = "";
                    link = "get_acreditadosbycolmena";
                    link = link + "/" + event.target.value;
                    $.ajax({
                        url: base_url + '/api/CarteraD1/' + link,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            this.setState({
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                catacreditados: response.grupo_acreditados,
                                icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
                            });
                            this.autoReset();
                        }.bind(this),
                        error: function (xhr, status, err) {
                            console.log('error');
                            this.setState({
                                message: "No se ha encontrado información de la colmena",
                                statusmessage: 'ui negative floating message',
                                catacreditados: [],
                                icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
                            });
                            this.autoReset();
                        }.bind(this)
                    });
                }
            }

            this.setState(_defineProperty({}, name, value));
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            if (e === 1) {
                var id = this.state.idcolmena;
                if (id = !"") {
                    var link = "";
                    if (e === 1) {
                        link = "pdf_colmena";
                    }
                    link = link + "/" + this.state.idcolmena;
                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportD1/' + link;
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
            } else {
                var _link = "";
                _link = "pdf_colmenas_dir";
                _link = _link + "/" + e;
                var a = document.createElement('a');
                a.href = base_url + 'api/ReportD1/' + _link;
                a.target = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            }
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {}
    }, {
        key: "autoReset",
        value: function autoReset() {
            var self = this;
            this.timeout = window.setTimeout(function () {
                self.setState({ message: '', statusmessage: 'ui message hidden' });
            }, 3000);
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
                            "Colmenas"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui  basic icon buttons" },
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Colmena activa" },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Directorio de colmenas" },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 2) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Directorio de colmenas ACTIVO" },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 3) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Directorio de colmenas INACTIVO" },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 4) })
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
                    { className: "get soling" },
                    React.createElement(
                        "form",
                        { className: "ui form formgen", ref: "form", onSubmit: this.handleSubmit.bind(this), method: "post" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "two fields" },
                            React.createElement(SelectDropDown, { id: "idcolmena", label: "Colmena", valor: this.state.idcolmena, valores: this.state.catcolmenas, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: "segment" },
                            React.createElement(Catalogo, { valores: this.state.catacreditados })
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
                                    " ",
                                    this.state.boton,
                                    " "
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