"use strict";

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

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

var RecordDetalle = function (_React$Component) {
    _inherits(RecordDetalle, _React$Component);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this.handleClick = _this.handleClick.bind(_this);
        _this.handleClickDel = _this.handleClickDel.bind(_this);

        return _this;
    }

    _createClass(RecordDetalle, [{
        key: "handleClick",
        value: function handleClick(e) {
            this.props.onClick(e, this.props.registro);
        }
    }, {
        key: "handleClickDel",
        value: function handleClickDel(e) {
            this.props.onClickDel(e, this.props.registro);
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
                    this.props.registro.idbeneficiario
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nombre1
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nombre2
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.apaterno
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.amaterno
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.porcentaje
                ),
                React.createElement(
                    "td",
                    { className: " center aligned" },
                    React.createElement(
                        "a",
                        { className: "ui button", onClick: this.handleClick },
                        React.createElement("i", { className: "edit icon green", onClick: this.handleClick })
                    ),
                    React.createElement(
                        "a",
                        { className: "ui button", onClick: this.handleClickDel },
                        React.createElement("i", { className: "remove icon red", onClick: this.handleClickDel })
                    )
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var Table = function (_React$Component2) {
    _inherits(Table, _React$Component2);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this2 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this2.handleClick = _this2.handleClick.bind(_this2);
        _this2.handleClickDel = _this2.handleClickDel.bind(_this2);
        return _this2;
    }

    _createClass(Table, [{
        key: "handleClick",
        value: function handleClick(e, valor) {
            this.props.onClick(e, valor);
        }
    }, {
        key: "handleClickDel",
        value: function handleClickDel(e, valor) {
            this.props.onClickDel(e, valor);
        }
    }, {
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            datos.forEach(function (record) {
                rows.push(React.createElement(RecordDetalle, { registro: record, onClick: this.handleClick, onClickDel: this.handleClickDel }));
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
                        React.createElement(Lista, { enca: ['Id', 'Primer Nombre', 'Segundo Nombre', 'Apellido Paterno', 'Apellido Materno', '%', ''] })
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

var Steps = function (_React$Component3) {
    _inherits(Steps, _React$Component3);

    function Steps(props) {
        _classCallCheck(this, Steps);

        return _possibleConstructorReturn(this, (Steps.__proto__ || Object.getPrototypeOf(Steps)).call(this, props));
    }

    _createClass(Steps, [{
        key: "render",
        value: function render() {
            var _this4 = this;

            return React.createElement(
                "a",
                { className: this.props.valor == this.props.value ? "active step" : "step", value: this.props.value, onClick: function onClick(e, value) {
                        return _this4.props.onClick(e, value);
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

var InputField = function (_React$Component4) {
    _inherits(InputField, _React$Component4);

    function InputField(props) {
        _classCallCheck(this, InputField);

        return _possibleConstructorReturn(this, (InputField.__proto__ || Object.getPrototypeOf(InputField)).call(this, props));
    }

    _createClass(InputField, [{
        key: "render",
        value: function render() {
            var _this6 = this;

            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            var may = this.props.mayuscula == "true" ? 'mayuscula' : '';

            if (this.props.visibility == false) {
                cols += " hidden";
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
                    { className: "ui icon input" },
                    React.createElement("input", { className: may, id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, type: "text", value: this.props.valor, placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this6.props.onChange(event);
                        }, disabled: this.props.disabled })
                )
            );
        }
    }]);

    return InputField;
}(React.Component);

var InputFieldFind = function (_React$Component5) {
    _inherits(InputFieldFind, _React$Component5);

    function InputFieldFind(props) {
        _classCallCheck(this, InputFieldFind);

        var _this7 = _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));

        _this7.state = {
            value: ''
        };
        return _this7;
    }

    _createClass(InputFieldFind, [{
        key: "render",
        value: function render() {
            var _this8 = this;

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
                    React.createElement("input", { id: this.props.id, name: this.props.id, type: "text", placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this8.setState({ value: event.target.value });
                        }, onKeyPress: function onKeyPress(event) {
                            return _this8.props.onKeyPress(event, _this8.state.value);
                        } }),
                    React.createElement("i", { className: this.props.icons, onClick: function onClick(event) {
                            return _this8.props.onClick(event, _this8.state.value);
                        } })
                )
            );
        }
    }]);

    return InputFieldFind;
}(React.Component);

var InputFieldNumber = function (_React$Component6) {
    _inherits(InputFieldNumber, _React$Component6);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: "render",
        value: function render() {
            var _this10 = this;

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
                            return _this10.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var Mensaje = function (_React$Component7) {
    _inherits(Mensaje, _React$Component7);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this11 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this11.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
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

var Calendar = function (_React$Component8) {
    _inherits(Calendar, _React$Component8);

    function Calendar(props) {
        _classCallCheck(this, Calendar);

        var _this12 = _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));

        _this12.handleChange = _this12.handleChange.bind(_this12);
        return _this12;
    }

    _createClass(Calendar, [{
        key: "handleChange",
        value: function handleChange(e) {}
    }, {
        key: "componentDidMount",
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myCalen)).on('onChange', this.handleChange);
        }
    }, {
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
                        React.createElement("input", { ref: "myCalen", type: "text", name: this.props.name, id: this.props.name, placeholder: "Fecha", onChange: this.handleChange })
                    )
                )
            );
        }
    }]);

    return Calendar;
}(React.Component);

/*
class Calendar extends React.Component{
    constructor(props){
        super(props);
        this.handleChange = this.handleChange.bind(this);
    }


    handleChange(e){
    }

    componentDidMount() {
        $(ReactDOM.findDOMNode(this.refs.myCalen)).on('onChange',this.handleChange);

    }

    render(){
        return(
            <div className="ui calendar" id={this.props.name}>
                <div className="field">
                <label>{this.props.label}</label>
                <div className="ui input left icon">
                    <i className="calendar icon"></i>
                    <input ref="myCalen" type="text" name={this.props.name} id={this.props.name} value={this.props.valor} placeholder="Fecha" onChange={this.handleChange}/>
                </div>
                </div>
            </div>
        );
    }

}

*/

var SelectDropDown = function (_React$Component9) {
    _inherits(SelectDropDown, _React$Component9);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this13 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this13.state = {
            value: ""
        };
        _this13.handleSelectChange = _this13.handleSelectChange.bind(_this13);
        return _this13;
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

            if (this.props.visibility == false) {
                cols += " hidden";
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

var SelectOptionMultiple = function (_React$Component10) {
    _inherits(SelectOptionMultiple, _React$Component10);

    function SelectOptionMultiple(props) {
        _classCallCheck(this, SelectOptionMultiple);

        var _this14 = _possibleConstructorReturn(this, (SelectOptionMultiple.__proto__ || Object.getPrototypeOf(SelectOptionMultiple)).call(this, props));

        _this14.state = {
            value: ""
        };
        return _this14;
    }

    _createClass(SelectOptionMultiple, [{
        key: "handleSelectChange",
        value: function handleSelectChange(event) {
            this.props.onChange(event);
        }
    }, {
        key: "componentDidMount",
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myCombo)).on('change', this.handleSelectChange.bind(this));
        }
    }, {
        key: "render",
        value: function render() {
            var listItems = this.props.valores.map(function (valor) {
                return React.createElement(
                    "div",
                    { className: "itme", "data-value": valor.value },
                    valor.name
                );
            });
            return React.createElement(
                "div",
                null,
                React.createElement(
                    "div",
                    { className: "ui sub header" },
                    this.props.label
                ),
                React.createElement(
                    "div",
                    { className: "ui fluid multiple search selection dropdown" },
                    React.createElement("input", { name: "tags", type: "hidden", value: this.props.valor }),
                    React.createElement("i", { "class": "dropdown icon" }),
                    React.createElement(
                        "div",
                        { className: "default text" },
                        "Seleccione"
                    ),
                    React.createElement(
                        "div",
                        { className: "menu" },
                        ListItems
                    )
                )
            );
        }
    }]);

    return SelectOptionMultiple;
}(React.Component);

var SelectOption = function (_React$Component11) {
    _inherits(SelectOption, _React$Component11);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this15 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this15.state = {
            value: ""
        };
        return _this15;
    }

    _createClass(SelectOption, [{
        key: "handleSelectChange",
        value: function handleSelectChange(event) {
            this.props.onChange(event);
        }
    }, {
        key: "componentDidMount",
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myCombo)).on('change', this.handleSelectChange.bind(this));
        }
    }, {
        key: "render",
        value: function render() {
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            if (this.props.disabled) {
                cols += " disabled";
            }
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

var CheckBox = function (_React$Component12) {
    _inherits(CheckBox, _React$Component12);

    function CheckBox(props) {
        _classCallCheck(this, CheckBox);

        return _possibleConstructorReturn(this, (CheckBox.__proto__ || Object.getPrototypeOf(CheckBox)).call(this, props));
    }

    _createClass(CheckBox, [{
        key: "render",
        value: function render() {
            var _this17 = this;

            var checked = this.props.valor == '1' ? 'ui checkbox checked' : 'ui checkbox';
            if (this.props.disabled) {
                checked += " read-only";
            }
            return React.createElement(
                "div",
                { className: "field" },
                React.createElement(
                    "label",
                    null,
                    "Seleccione"
                ),
                React.createElement(
                    "div",
                    { className: "four fields" },
                    React.createElement(
                        "div",
                        { className: "ten wide inline field" },
                        React.createElement(
                            "div",
                            { className: checked, onClick: function onClick(event) {
                                    return _this17.props.onClickCheck(event, _this17.props.name, _this17.props.valor);
                                } },
                            React.createElement("input", { type: "checkbox", value: this.props.valor == 1 ? 'on' : 'off', id: this.props.name, name: this.props.name, tabindex: "0", "class": "hidden" }),
                            React.createElement(
                                "label",
                                null,
                                this.props.titulo
                            )
                        )
                    )
                )
            );
        }
    }]);

    return CheckBox;
}(React.Component);

var Captura = function (_React$Component13) {
    _inherits(Captura, _React$Component13);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this18 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this18.state = { activo: 0,
            catestado: [], catestadob: [], catmpio: [], catcolonia: [], catcp: [], catedocivil: [], catescolar: [], cattiposociedad: [], catpais: [],
            catparentesco: [], catactividad: [], catpersonarel: [], idpersona: '', idpersonaben: '', fechaalta: null, fechaaltad: null, fechaaltab: null, cia: '', idtiposociedad: '',
            tipo: 'F', nombre1: "", nombre2: "", personarel: '0', idpersonarel: '', apaterno: "", amaterno: "", aliaspf: "", fecha_nac: "",
            sexo: "", paisnac: "", edonac: "", lugnac: "", idnacionalidad: "", edocivil: "", escolaridad: "",
            rfc: "", curp: "", folio_ife: "", vine: 0, otroiden: "", email: "", conyuge: "", celular: "", direccion1: "", nota: "",
            noexterior: "", nointerior: "", direccion2: "", idestado: "", idmunicipio: "", idcolonia: "",
            ciudad: "", cp: "", tiempo: 0, telefono: "", tipovivienda: 0, aguapot: 0, enerelec: 0,
            drenaje: 0, idactividad: "", patrimonio: 0, idparentesco: "", teltrabajo: "", domlaboral: "",
            domlabref: "", ingresomen: 0, ingresomenext: 0, egresomen: 0, egresomenext: 0, dependientes: 0,
            ahorro: 0, nombre1_ben: "", nombre2_ben: "", apaterno_ben: "", amaterno_ben: "", aliaspf_ben: "",
            rfc_ben: "", telefono_ben: "", sexo_ben: "", participacion: 0, csrf: "", message: "",
            statusmessage: 'ui floating hidden message', stepno: 1, boton: 'Enviar', boton2: 'Enviar', boton3: 'Enviar',
            icons1: 'inverted circular search link icon',
            disabledboton2: 'disabled', disabledboton3: 'disabled',
            catbene: [], idBeneActivo: 0,
            direccion1b: "",
            noexteriorb: "", nointeriorb: "", direccion2b: "", idestadob: "", idmunicipiob: "", idcoloniab: "",
            ciudadb: "", cpb: "", tiempob: 0, telefonob: "",
            catestadoben: [], catmpiob: [], catcoloniab: [], catcpb: []
        };
        return _this18;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            $.ajax({
                url: base_url + 'api/GeneralV1/catsolcre',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    var estados = response.estado;
                    var catEstadoB = estados.filter(function (cat) {
                        return cat.value == '20';
                    });
                    this.setState({ catestado: response.estado, catestadoben: catEstadoB, catpais: response.pais, catestadob: catEstadoB, idestado: '20', idestadob: '20', catmpio: response.mpio,
                        catmpiob: response.mpio, catedocivil: response.edocivil,
                        catescolar: response.escolaridad, catparentesco: response.parentesco,
                        catactividad: response.actividad, cattiposociedad: response.tiposociedad
                    });

                    var $form = $('.get.solingdom form'),
                        Folio = $form.form('set values', { idestado: '20'
                    });
                }.bind(this),
                error: function (xhr, status, err) {}.bind(this)
            });
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));
            if (name === "idcolonia") {
                var $form = $('.get.solingdom form'),
                    idmunicipio = $form.form('get value', 'idmunicipio');
            }
            if (name === "idcoloniab") {
                var $form = $('.get.solingben form'),
                    idmunicipiob = $form.form('get value', 'idmunicipiob');
            }

            if (name == "personarel" && value == "0") {
                this.setState({ catpersonarel: [], idpersonarel: ''
                });
                var $form = $('.get.soling form'),
                    person = $form.form('set values', { idpersonarel: '' });
            }
            var findRfcBen = false;
            if (name === "rfc_ben") {
                if (value.length == 10 || value.length == 13) {
                    findRfcBen = true;
                }
            }

            //name === "idestado" ||
            if (findRfcBen == true || name === "idmunicipio" || name === "idcolonia" || name === "idmunicipiob" || name === "idcoloniab" || name == "personarel" && value == "1") {
                var link = "";
                if (name === "idestado") {
                    link = "GeneralV1/catmpio";
                } else if (name === "idmunicipio" || name === "idmunicipiob") {
                    link = "GeneralV1/catcolonia";
                } else if (name === "idcolonia") {
                    link = "GeneralV1/catcp/" + idmunicipio;
                } else if (name === "idcoloniab") {
                    console.log(idmunicipiob);
                    link = "GeneralV1/catcp/" + idmunicipiob;
                } else if (name === "personarel") {
                    link = "GeneralV1/catempleados";
                } else if (name === "rfc_ben") {
                    link = "CarteraV1/findPersonByRfc";
                }
                link = link + "/" + event.target.value;

                var forma = this;
                var object = {
                    url: base_url + 'api/' + link,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    if (name === "idestado") {
                        forma.setState({ catmpio: response.result,
                            catcolonia: [], idcolonia: "", catcp: [], cp: ""
                        });
                    } else if (name === "idestadob") {
                        forma.setState({ catmpiob: response.result,
                            catcoloniab: [], idcoloniab: "", catcpb: [], cpb: ""
                        });
                    } else if (name === "idmunicipio") {
                        forma.setState({ catcolonia: response.result,
                            idcolonia: "", catcp: [], cp: ""
                        });
                    } else if (name === "idmunicipiob") {
                        forma.setState({ catcoloniab: response.result,
                            idcoloniab: "", catcpb: [], cpb: ""
                        });
                    } else if (name === "idcolonia") {
                        forma.setState({ catcp: response.result,
                            cp: ""
                        });
                    } else if (name === "idcoloniab") {
                        forma.setState({ catcpb: response.result,
                            cpb: ""
                        });
                    } else if (name == "personarel") {
                        forma.setState({ catpersonarel: response.result
                        });
                    } else if (name == "rfc_ben") {
                        var datapersona = response.persona[0];
                        forma.asignaBen(datapersona);
                    }
                }, function reject(reason) {
                    var response = validaError(reason);
                });
            }
        }
    }, {
        key: "handleSubmitDom",
        value: function handleSubmitDom(event) {
            event.preventDefault();
            $('.ui.form.formdom').form({
                inline: true,
                on: 'blur',
                fields: {
                    direccion1: {
                        identifier: 'direccion1',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee la Dirección'
                        }, {
                            type: 'maxLength[40]',
                            prompt: 'Longitud máxima de 40 caracteres '
                        }]
                    },
                    direccion2: {
                        identifier: 'direccion1',
                        optional: true,
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee la Referencia'
                        }, {
                            type: 'maxLength[200]',
                            prompt: 'Longitud máxima de 200 caracteres '
                        }]
                    },
                    noexterior: {
                        identifier: 'noexterior',
                        rules: [{
                            type: 'empty',
                            prompt: 'No. Exterior'
                        }, {
                            type: 'maxLength[8]',
                            prompt: 'Longitud máxima de 8 caracteres '
                        }]
                    },
                    idestado: {
                        identifier: 'idestado',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el Estado'
                        }]
                    },
                    idmunicipio: {
                        identifier: 'idmunicipio',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el Municipio'
                        }]
                    },
                    idcolonia: {
                        identifier: 'idcolonia',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la Colonia'
                        }]
                    },
                    cp: {
                        identifier: 'cp',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el CP'
                        }]
                    },
                    ciudad: {
                        identifier: 'ciudad',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee la Ciudad'
                        }, {
                            type: 'maxLength[40]',
                            prompt: 'Longitud máxima de 40 Catacteres'
                        }]
                    },
                    tiempo: {
                        identifier: 'tiempo',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee tiempo de radicar'
                        }, {
                            type: 'integer[1..99]',
                            prompt: 'Requiere un valor numérico'
                        }]
                    },
                    tipovivienda: {
                        identifier: 'tipovivienda',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione Tipo de Vivienda'
                        }]
                    }
                }
            });

            //        $('.ui.form').find('.error').removeClass('error').find('.prompt').remove();
            $('.ui.form.formdom').form('validate form');
            var valida = $('.ui.form.formdom').form('is valid');
            this.setState({ message: '', statusmessage: 'ui message hidden' });
            if (valida == true) {
                var $form = $('.get.solingdom form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = this.state.boton2 === 'Enviar' ? 'POST' : 'PUT';
                var id = this.state.idpersona;
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {

                        var object = {
                            url: base_url + 'api/CarteraV1/solcreditodom/' + id,
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
                                boton2: 'Actualizar'
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
                this.setState({
                    message: 'Datos incompletos!',
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset();
            }
        }
    }, {
        key: "handleSubmitBen",
        value: function handleSubmitBen(event) {
            event.preventDefault();
            var srfc = this.state.rfc_ben;
            this.setState({ rfc_ben: srfc.toUpperCase() });

            $('.ui.form.formben').form({
                inline: true,
                on: 'blur',
                fields: {
                    nombre1_ben: {
                        identifier: 'nombre1_ben',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee Nombre de Beneficiario'
                        }, {
                            type: 'maxLength[75]',
                            prompt: 'Longitud máximo de 75 caracteres'
                        }]
                    },
                    rfc_ben: {
                        identifier: 'rfc_ben',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el RFC'
                        }, {
                            type: 'regExp[/^[A-Za-z]{4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z0-9\d]{0,3})$/]',
                            prompt: 'Capture un AAAAYYMMDD formato correcto'
                        }]
                    },
                    sexo_ben: {
                        identifier: 'sexo_ben',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el Sexo'
                        }]
                    },
                    idparentesco: {
                        identifier: 'idparentesco',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione parentesco'
                        }]
                    },
                    porcentaje: {
                        identifier: 'porcentaje',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee participación'
                        }, {
                            type: 'integer[1..100]',
                            prompt: 'Requiere un valor numérico'
                        }]
                    }
                }
            });

            //        $('.ui.form').find('.error').removeClass('error').find('.prompt').remove();
            $('.ui.form.formben').form('validate form');
            var valida = $('.ui.form.formben').form('is valid');
            this.setState({ message: '', statusmessage: 'ui message hidden' });
            if (valida == true) {
                var $form = $('.get.solingben form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = this.state.boton3 === 'Enviar' ? 'POST' : 'PUT';
                var id = this.state.idpersona;
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/CarteraV1/solcreditoben/' + id,
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
                                boton3: 'Actualizar'
                            });
                            forma.autoReset();

                            if (tipo == "POST") {
                                forma.getBeneficiarios(id, forma);
                            } else {

                                var recUpdate = forma.state.catbene.filter(function (e) {
                                    return e.idpersona == forma.state.idBeneActivo;
                                });
                                recUpdate[0]['celular'] = forma.state.telefono_ben;
                                recUpdate[0]['idparentesco'] = forma.state.idparentesco;
                                recUpdate[0]['sexo'] = forma.state.sexo_ben;
                                recUpdate[0]['porcentaje'] = forma.state.porcentaje;
                                recUpdate[0]['rfc_ben'] = forma.state.rfc_ben;

                                var $form = $('.get.solingben form'),
                                    Folio = $form.form('set values', { idparentesco: forma.state.idparentesco, sexo_ben: forma.state.sexo });

                                var newArray = [].concat(_toConsumableArray(forma.state.catbene), [recUpdate]);
                            }
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
        key: "getBeneficiarios",
        value: function getBeneficiarios(id, forma) {
            var object = {
                url: base_url + 'api/CarteraV1/solcreditoben/' + id,
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                forma.setState({
                    catbene: response.ben
                });
            }, function reject(reason) {
                forma.setState({
                    catbene: [],
                    idbeneficiario: 0,
                    boton3: 'Enviar',
                    disabledboton3: ''
                });
                forma.inicialBen();
            });
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            var rulescia = [];
            var rulestipsoc = [];
            var rulesrfc = [];
            var rulesife = [];
            var rulescurp = [];
            var rulesedonac = [];
            var rulesotraiden = [];

            if (this.state.tipo == "M") {
                rulescia = [{
                    type: 'empty',
                    prompt: 'Capture la compañia'
                }, {
                    type: 'minLength[2]',
                    prompt: 'Minimo 2 caracteres'
                }, {
                    type: 'maxLength[125]',
                    prompt: 'Longitu máxima de 125 caracteres'
                }];
                rulestipsoc = [{
                    type: 'empty',
                    prompt: 'Capture el tipo de sociedad'
                }];
                rulesrfc = [{
                    type: 'empty',
                    prompt: 'Capture el RFC'
                }, {
                    type: 'regExp[/^[A-Za-z]{3}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z0-9\d]{3})$/]',
                    prompt: 'Capture un AAAYYMMDDAAA formato correcto'
                }];
            } else {
                rulesrfc = [{
                    type: 'empty',
                    prompt: 'Capture el RFC'
                }, {
                    type: 'regExp[/^[A-Za-z]{4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z0-9\d]{0,3})$/]',
                    prompt: 'Capture un AAAAYYMMDD formato correcto'
                }];

                if (this.state.paisnac === "MEX") {
                    rulescurp = [{
                        type: 'empty',
                        prompt: 'Teclee la CURP'
                    }, {
                        type: 'exactLength[18]',
                        prompt: 'Longitud de 18 caracteres'

                    }, {
                        type: 'regExp[/^[A-Za-z]{4}([0-9]{2})(1[0-2]|0[1-9])([0-3][0-9])([A-Za-z]{6})([A-Za-z0-9]{1})([0-9]{1})$/]',
                        prompt: 'Formato incorrecto'
                    }];
                    rulesedonac = [{
                        type: 'empty',
                        prompt: 'Seleccione el Estado de nacimiento'
                    }];
                }
                if (this.state.otroiden != "" && this.state.otroiden != null) {
                    rulesotraiden = [{
                        type: 'empty',
                        prompt: 'Capture Otra identificación'
                    }, {
                        type: 'maxLength[45]',
                        prompt: 'Longitu máxima de 45 caracteres'
                    }];
                } else {
                    rulesife = [{
                        type: 'empty',
                        prompt: 'Capture el Folio IFE'
                    }, {
                        type: 'minLength[10]',
                        prompt: 'Longitu máxima de 10 caracteres'
                    }, {
                        type: 'maxLength[14]',
                        prompt: 'Longitu máxima de 14 caracteres'
                    }];
                    rulesotraiden = [];
                }
            }

            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    cia: {
                        identifier: 'cia',
                        rules: rulescia
                    },
                    idtiposociedad: {
                        identifier: 'idtiposociedad',
                        rules: rulestipsoc
                    },
                    fechaalta: {
                        identifier: 'fechaalta',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture la fecha de alta'
                        }]
                    },
                    nombre1: {
                        identifier: 'nombre1',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el Primer Nombre'
                        }, {
                            type: 'minLength[2]',
                            prompt: 'Minimo 2 caracteres'
                        }, {
                            type: 'maxLength[75]',
                            prompt: 'Longitu máxima de 75 caracteres'
                        }]
                    },
                    nombre2: {
                        identifier: 'nombre2',
                        optional: true,
                        rules: [{
                            type: 'minLength[2]',
                            prompt: 'Minimo 2 caracteres'
                        }, {
                            type: 'maxLength[75]',
                            prompt: 'Longitu máxima de 75 caracteres'
                        }]
                    },
                    apaterno: {
                        identifier: 'apaterno',
                        optional: true,
                        rules: [{
                            type: 'minLength[2]',
                            prompt: 'Minimo 2 caracteres'
                        }, {
                            type: 'maxLength[25]',
                            prompt: 'Longitu máxima de 25 caracteres'
                        }]
                    },
                    amaterno: {
                        identifier: 'amaterno',
                        optional: true,
                        rules: [{
                            type: 'minLength[2]',
                            prompt: 'Minimo 2 caracteres'
                        }, {
                            type: 'maxLength[25]',
                            prompt: 'Longitu máxima de 25 caracteres'
                        }]
                    },
                    aliaspf: {
                        identifier: 'aliaspf',
                        optional: true,
                        rules: [{
                            type: 'minLength[5]',
                            prompt: 'Minimo 5 caracteres'
                        }, {
                            type: 'maxLength[120]',
                            prompt: 'Longitu máxima de 120 caracteres'
                        }]
                    },
                    email: {
                        identifier: 'email',
                        rules: [{
                            type: 'email',
                            prompt: 'Capture un Email Válido'
                        }]
                    },
                    rfc: {
                        identifier: 'rfc',
                        rules: rulesrfc
                    },
                    curp: {
                        identifier: 'curp',
                        rules: rulescurp
                    },
                    folio_ife: { identifier: 'folio_ife',
                        rules: rulesife
                    },
                    otroiden: { identifier: 'otroiden',
                        rules: rulesotraiden
                    },
                    fecha_nac: {
                        identifier: 'fecha_nac',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione Fec. Nacimiento'
                        }]
                    },
                    sexo: {
                        identifier: 'sexo',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el Sexo'
                        }]
                    },
                    edonac: {
                        identifier: 'edonac',
                        rules: rulesedonac
                    },
                    edocivil: {
                        identifier: 'edocivil',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el Estado Civil'
                        }]
                    },
                    escolaridad: {
                        identifier: 'escolaridad',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el Escolaridad'
                        }]
                    },
                    conyuge: {
                        identifier: 'conyuge',
                        optional: true,
                        rules: [{
                            type: 'minLength[5]',
                            prompt: 'Minimo 5 caracteres'
                        }, {
                            type: 'maxLength[100]',
                            prompt: 'Longitu máxima de 100 caracteres'
                        }]
                    },
                    personarel: {
                        identifier: 'personarel',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el Vinculo Familiar'
                        }]
                    },

                    idactividad: {
                        identifier: 'idactividad',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la Actividad'
                        }, {
                            type: 'minLength[6]',
                            prompt: 'Seleccione una Actividad válida'
                        }]
                    },
                    patrimonio: {
                        identifier: 'patrimonio',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee el Patrimonio'
                        }, {
                            type: 'number',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    experiencia: {
                        identifier: 'experiencia',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee la experiencia'
                        }, {
                            type: 'integer[0..99]',
                            prompt: 'Requiere un valor numérico'
                        }]
                    },
                    domlaboral: {
                        identifier: 'domlaboral',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee la dirección'
                        }, {
                            type: 'maxLength[100]',
                            prompt: 'Longitu máxima de 100 caracteres'
                        }]
                    },
                    domlabref: {
                        identifier: 'domlabref',
                        optional: true,
                        rules: [{
                            type: 'minLength[5]',
                            prompt: 'Minimo 5 caracteres'
                        }, {
                            type: 'maxLength[200]',
                            prompt: 'Longitu máxima de 200 caracteres'
                        }]
                    },
                    ingresomen: {
                        identifier: 'ingresomen',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee Ingreso Mensual'
                        }, {
                            type: 'number',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    ingresomenext: {
                        identifier: 'ingresomenext',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee Ingreso Extraordinario'
                        }, {
                            type: 'number',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    egresomen: {
                        identifier: 'egresomen',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee Egreso Mensual'
                        }, {
                            type: 'number',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    egresomenext: {
                        identifier: 'egresomenext',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee Egreso Extraordinario'
                        }, {
                            type: 'number',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    dependientes: {
                        identifier: 'dependientes',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee Dependientes'
                        }, {
                            type: 'integer[0..10]',
                            prompt: 'Requiere un valor numérico'
                        }]
                    },
                    ahorro: {
                        identifier: 'ahorro',
                        rules: [{
                            type: 'number',
                            prompt: 'Requiere un valor'
                        }]
                    }
                }
            });

            //        $('.ui.form').find('.error').removeClass('error').find('.prompt').remove();
            $('.ui.form.formgen').form('validate form');
            var valida = $('.ui.form.formgen').form('is valid');
            this.setState({ message: '', statusmessage: 'ui message hidden' });
            if (valida == true) {
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = this.state.boton === 'Enviar' ? 'POST' : 'PUT';
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/CarteraV1/solcreditogen',
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };

                        ajax(object).then(function resolve(response) {
                            if (tipo == "POST") {
                                forma.setState({
                                    idpersona: response.insert_id, boton: 'Actualizar', activo: 1,
                                    disabledboton2: '', disabledboton3: '' });
                                var $form = $('.get.soling form'),
                                    Folio = $form.form('set values', { idpersona: response.insert_id
                                });
                            }

                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message '
                            });

                            forma.autoReset();
                        }, function reject(reason) {

                            if (reason.status == 200) {

                                var response = validaError(reason);

                                forma.setState({
                                    csrf: response.newtoken,
                                    message: response.message,
                                    statusmessage: 'ui positive floating message '
                                });
                            } else {
                                var _response = validaError(reason);

                                forma.setState({
                                    csrf: _response.newtoken,
                                    message: _response.message,
                                    statusmessage: 'ui negative floating message'
                                });
                            }
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
        key: "handleonClickCheck",
        value: function handleonClickCheck(e, name, valor) {
            this.setState(_defineProperty({}, name, valor == '1' ? '0' : '1'));
        }
    }, {
        key: "handleClickNext",
        value: function handleClickNext(e) {
            if (this.state.stepno < 4) {
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
        key: "asignaPersona",
        value: function asignaPersona(data) {
            //        let fecalta = new Date(data.fechaalta);
            //        fecalta= fecalta.getDate() + '/' + ( fecalta.getMonth() + 1) + '/' + fecalta.getFullYear();
            var fecalta = moment(data.fechaalta).format('DD/MM/YYYY');
            //        let fecnac = new Date(data.fecha_nac);
            //        fecnac = fecnac.getDate() + '/' + ( fecnac.getMonth() + 1) + '/' + fecnac.getFullYear();
            var fecnac = moment(data.fecha_nac).format('DD/MM/YYYY');
            $('#fechaalta').val(fecalta);
            $('#fechaaltad').val(fecalta);
            $('#fechaaltab').val(fecalta);

            this.setState({ fechaalta: fecalta, fechaaltad: fecalta, fechaaltab: fecalta, tipo: data.tipo, cia: data.cia, idtiposociedad: data.idtiposociedad, nombre1: data.nombre1, nombre2: data.nombre2,
                apaterno: data.apaterno, amaterno: data.amaterno, aliaspf: data.aliaspf, fecha_nac: fecnac, sexo: data.sexo, paisnac: data.paisnac, edonac: data.edonac, lugnac: data.lugnac, edocivil: data.edocivil, idnacionalidad: data.idnacionalidad,
                escolaridad: data.escolaridad, rfc: data.rfc, curp: data.curp, folio_ife: data.folio_ife, vine: data.vine, otroiden: data.otroiden, email: data.email,
                celular: data.celular, conyuge: data.conyuge, idactividad: data.idactividad, patrimonio: data.patrimonio, experiencia: data.experiencia,
                teltrabajo: data.teltrabajo, domlaboral: data.domlaboral, domlabref: data.domlabref, ingresomen: data.ingresomen, ingresomenext: data.ingresomenext,
                egresomen: data.egresomen, egresomenext: data.egresomenext, dependientes: data.dependientes, ahorro: data.ahorro,
                personalrel: data.personarel, idpersonarel: data.idpersonarel, nota: data.nota
            });
            var $form = $('.get.soling form'),
                Folio = $form.form('set values', { fechaalta: fecalta, fechaaltad: fecalta, fechaaltab: fecalta, fecha_nac: fecnac, tipo: data.tipo, idtiposociedad: data.idtiposociedad, sexo: data.sexo,
                paisnac: data.paisnac, edonac: data.edonac, lugnac: data.lugnac, idnacionalidad: data.idnacionalidad, edocivil: data.edocivil, escolaridad: data.escolaridad, idactividad: data.idactividad, idpersonarel: data.idpersonarel
            });

            var $form = $('.get.solingdom form'),
                Folio = $form.form('set values', { fechaaltad: fecalta });

            var $form = $('.get.solingben form'),
                Folio = $form.form('set values', { fechaaltab: fecalta });
        }
    }, {
        key: "incialPersona",
        value: function incialPersona() {
            var fecha = moment(new Date(), 'DD/MM/YYYY').format('DD/MM/YYYY');
            this.setState({ fechaalta: fecha, tipo: 'F', cia: '', idtiposociedad: '', nombre1: '', nombre2: '',
                apaterno: '', amaterno: '', aliaspf: '',
                rfc: '', curp: '', folio_ife: '', vine: 0, otroiden: '', email: '',
                celular: '', conyuge: '', patrimonio: '', experiencia: '',
                teltrabajo: '', domlaboral: '', domlabref: '', ingresomen: '', ingresomenext: '',
                egresomen: '', egresomenext: '', dependientes: '', ahorro: '', nota: ''
            });
            var $form = $('.get.soling form'),
                Folio = $form.form('set values', { tipo: 'F' });
        }
    }, {
        key: "findClaves",
        value: function findClaves(name, value, idmunicipio, asigna, cp) {
            if (name === "idmunicipio" || name === "idcolonia") {
                var link = "";
                if (name === "idmunicipio") {
                    link = "catcolonia";
                } else if (name === "idcolonia") {
                    link = "catcp/" + idmunicipio;
                }
                link = link + "/" + value;

                var forma = this;
                var object = {
                    url: base_url + 'api/GeneralV1/' + link,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    if (name === "idestado") {
                        forma.setState({ catmpio: response.result,
                            catcolonia: [], idcolonia: "", catcp: [], cp: ""
                        });
                    } else if (name === "idmunicipio") {
                        forma.setState({ catcolonia: response.result,
                            idcolonia: "", catcp: [], cp: ""
                        });
                        forma.setState({ idcolonia: asigna });
                        var $form = $('.get.solingdom form'),
                            Folio = $form.form('set values', { idcolonia: asigna });

                        forma.findClaves("idcolonia", asigna, value, cp, 0);
                    } else if (name === "idcolonia") {
                        forma.setState({ catcp: response.result,
                            cp: ""
                        });

                        forma.setState({ cp: asigna });
                        var $form = $('.get.solingdom form'),
                            Folio = $form.form('set values', { cp: asigna });
                    }
                }, function reject(reason) {
                    var response = validaError(reason);
                });
            }
        }
    }, {
        key: "findClavesB",
        value: function findClavesB(name, value, idmunicipio, asigna, cpb) {
            if (name === "idmunicipiob" || name === "idcoloniab") {
                var link = "";
                if (name === "idmunicipiob") {
                    link = "catcolonia";
                } else if (name === "idcoloniab") {
                    link = "catcp/" + idmunicipio;
                }
                link = link + "/" + value;
                var forma = this;
                var object = {
                    url: base_url + 'api/GeneralV1/' + link,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    if (name === "idestadob") {
                        forma.setState({ catmpiob: response.result,
                            catcoloniab: [], idcoloniab: "", catcpb: [], cpb: ""
                        });
                    } else if (name === "idmunicipiob") {
                        forma.setState({ catcoloniab: response.result,
                            idcoloniab: "", catcpb: [], cpb: ""
                        });
                        forma.setState({ idcoloniab: asigna });
                        var $form = $('.get.solingben form'),
                            folio = $form.form('set values', { idcoloniab: asigna });

                        forma.findClavesB("idcoloniab", asigna, value, cpb, 0);
                    } else if (name === "idcoloniab") {
                        forma.setState({ catcpb: response.result,
                            cpb: ""
                        });

                        forma.setState({ cpb: asigna });
                        var $form = $('.get.solingben form'),
                            Folio = $form.form('set values', { cpb: asigna });
                    }
                }, function reject(reason) {
                    var response = validaError(reason);
                });
            }
        }
    }, {
        key: "asignaDom",
        value: function asignaDom(data) {
            if (data != undefined) {
                this.setState({ direccion1: data.direccion1, noexterior: data.noexterior, nointerior: data.nointerior, direccion2: data.direccion2,
                    ciudad: data.ciudad,
                    tiempo: data.tiempo, telefono: data.telefono, tipovivienda: data.tipovivienda, aguapot: data.aguapot, enerelec: data.enerelec,
                    drenaje: data.drenaje
                });

                var aguapot = data.aguapot == '1' ? true : false;
                var enerelec = data.enerelec == '1' ? true : false;
                var drenaje = data.drenaje == '1' ? true : false;

                var idestado = data.idestado;
                if (data.idestado == null) {
                    this.setState({ idestado: '20' });
                    idestado = '20';
                } else {
                    this.setState({ idestado: data.idestado, idmunicipio: data.idmunicipio });
                }
                var idmunicipio = data.idmunicipio;
                var idcolonia = data.idcolonia;
                var cp = data.cp;
                if (idmunicipio != null) {
                    this.findClaves("idmunicipio", idmunicipio, 0, idcolonia, cp);
                    this.findClaves("idcolonia", idcolonia, idmunicipio, cp, 0);
                }

                var $form = $('.get.solingdom form'),
                    Folio = $form.form('set values', { tipovivienda: data.tipovivienda, idestado: idestado, idmunicipio: data.idmunicipio, aguapot: aguapot, enerelec: enerelec, drenaje: drenaje
                });

                //             idmunicipio: data.idmunicipio,  idcolonia: data.idcolonia, cp: data.cp,             

            } else {
                this.inicialDom();
            }
        }
    }, {
        key: "inicialDom",
        value: function inicialDom() {
            this.setState({ direccion1: '', noexterior: '', nointerior: '', direccion2: '',
                idestado: '', idmunicipio: '', idcolonia: '', cp: '', ciudad: '',
                tiempo: '', telefono: '', tipovivienda: '', aguapot: 0, enerelec: 0,
                drenaje: 0
            });
            var $form = $('.get.solingdom form'),
                Folio = $form.form('set values', { idestado: '', idmunicipio: '', idcolonia: '', cp: '',
                tipovivienda: '', aguapot: false, enerelec: false, drenaje: false
            });
        }
    }, {
        key: "asignaBen",
        value: function asignaBen(data) {
            if (data != undefined) {
                this.setState({ idpersonaben: data.idpersona, nombre1_ben: data.nombre1, nombre2_ben: data.nombre2, apaterno_ben: data.apaterno, amaterno_ben: data.amaterno,
                    aliaspf_ben: data.aliaspf, rfc_ben: data.rfc, sexo_ben: data.sexo, telefono_ben: data.celular, idparentesco: data.idparentesco,
                    porcentaje: data.porcentaje
                });
                var idestado = '20';
                var idmunicipio = 0;
                if (data['direccion'].length == 0) {
                    this.setState({
                        direccion1b: '', noexteriorb: '', nointeriorb: '', direccion2b: '',
                        estadob: '20', municipiob: '', coloniab: 0, cpb: 0, ciudadb: '',
                        tiempob: '', telefonob: ''
                    });
                } else {
                    this.setState({
                        direccion1b: data['direccion'].direccion1, noexteriorb: data['direccion'].noexterior, nointeriorb: data['direccion'].nointerior, direccion2b: data['direccion'].direccion2,
                        estadob: data['direccion'].estado, municipiob: data['direccion'].municipio, coloniab: data['direccion'].colonia, cpb: data['direccion'].cp, ciudadb: data['direccion'].ciudad,
                        tiempob: data['direccion'].tiempo, telefonob: data['direccion'].telefono
                    });

                    idestado = data['direccion'].idestado;
                    if (data['direccion'].idestado == null) {
                        this.setState({ idestadob: '20' });
                        idestado = '20';
                    } else {
                        this.setState({ idestadob: data['direccion'].idestado, idmunicipiob: data['direccion'].idmunicipio });
                    }
                    idmunicipio = data['direccion'].idmunicipio;
                    var idcolonia = data['direccion'].idcolonia;
                    var cp = data['direccion'].cp;

                    if (idmunicipio != null) {
                        this.findClavesB("idmunicipiob", idmunicipio, 0, idcolonia, cp);
                        this.findClavesB("idcoloniab", idcolonia, idmunicipio, cp, 0);
                    }
                }

                var $form = $('.get.solingben form'),
                    Folio = $form.form('set values', { idparentesco: data.idparentesco, sexo_ben: data.sexo, idestadob: idestado, idmunicipiob: idmunicipio });
            } else {
                this.inicialBen();
            }
        }
    }, {
        key: "inicialBen",
        value: function inicialBen() {
            this.setState({ idpersonaben: '', nombre1_ben: '', nombre2_ben: '', apaterno_ben: '', amaterno_ben: '',
                aliaspf_ben: '', rfc_ben: '', telefono_ben: '', idparentesco: '', porcentaje: '',
                direccion1b: '', noexteriorb: '', nointeriorb: '', direccion2b: '',
                estadob: '', municipiob: '', coloniab: '', cpb: '', ciudadb: '',
                tiempob: '', telefonob: ''
            });
            var $form = $('.get.solingben form'),
                Folio = $form.form('set values', { idparentesco: '', idestado: '', idmunicipio: '', idcolonia: '', cp: '' });
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
        key: "hanleKeyPress",
        value: function hanleKeyPress(event, value) {
            if (event.key === 'Enter') {
                this.findData(value);
            }
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value) {
            this.findData(value);
        }
    }, {
        key: "findData",
        value: function findData(value) {
            this.setState({ idpersona: value, icons1: 'spinner circular inverted blue loading icon' });
            var forma = this;
            var object = {
                url: base_url + 'api/CarteraV1/solcredito/' + value,
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                forma.setState({ catcolonia: response.col });
                forma.setState({ catcp: response.cp });

                forma.asignaPersona(response.persona[0]);

                forma.asignaDom(response.dom[0]);
                forma.asignaBen(response.ben[0]);
                if (response.ben[0] != undefined) {
                    forma.setState({
                        catbene: response.ben,
                        idBeneActivo: response.ben[0].idpersona
                    });
                } else {
                    forma.setState({
                        catbene: [],
                        idBeneActivo: 0
                    });
                }

                forma.setState({
                    message: response.message,
                    statusmessage: 'ui positive floating message ',
                    boton: 'Actualizar', activo: 1, icons1: 'inverted circular search link icon',
                    disabledboton2: '', disabledboton3: 'disabled'

                });
                forma.autoReset();
                if (response.dom[0] != undefined) {
                    forma.setState({
                        boton2: 'Actualizar', disabledboton2: ''
                    });
                } else {
                    forma.setState({
                        boton2: 'Enviar', disabledboton2: ''
                    });
                }
                if (response.ben[0] != undefined) {
                    forma.setState({
                        boton3: 'Actualizar', disabledboton3: 'disabled'
                    });
                } else {
                    forma.setState({
                        boton3: 'Enviar', disabledboton3: ''
                    });
                }
                $('.ui.form.formgen').form('validate form');
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
                    csrf: response.newtoken,
                    message: response.message,
                    statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                });
                forma.incialPersona();
                forma.inicialDom();
                forma.inicialBen();
                forma.setState({ boton: 'Enviar', boton2: 'Enviar', disabledboton2: 'disabled', boton3: 'Enviar', disabledboton3: '' });
                forma.autoReset();
            });
        }
    }, {
        key: "handleButton3",
        value: function handleButton3(e, value) {
            //        if (this.state.disabledboton3 == "") {
            this.inicialBen();
            this.setState({ boton3: 'Enviar', disabledboton3: '', idBeneActivo: 0 });
            //      }
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            if (e == 0) {
                this.incialPersona();
                this.inicialDom();
                this.inicialBen();
                this.setState({ activo: 0, boton: 'Enviar', boton2: 'Enviar', disabledboton2: 'disabled', boton3: 'Enviar', disabledboton3: 'disabled', idpersona: '' });

                var $form = $('.get.soling form'),
                    Folio = $form.form('set values', { idpersona: ''
                });
            } else {
                if (this.state.boton == "Actualizar") {
                    var d = new Date();
                    var id = this.state.idpersona;

                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportV1/solcreditopdf/' + id;
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
        key: "handleClickRecord",
        value: function handleClickRecord(e, rec) {
            this.asignaBen(rec);
            this.setState({
                boton3: 'Actualizar', disabledboton3: 'disabled', idBeneActivo: rec.idpersona
            });
        }
    }, {
        key: "handleClickRecordDel",
        value: function handleClickRecordDel(e, rec) {
            var id = this.state.idpersona;
            var idbeneficiario = rec.idpersona;
            var forma = this;
            $('.mini.modal').modal({
                closable: false,
                onApprove: function onApprove() {
                    var object = {
                        url: base_url + 'api/CarteraV1/solcreditoben/' + id,
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            idbeneficiario: idbeneficiario
                        }
                    };

                    ajax(object).then(function resolve(response) {

                        forma.setState({
                            csrf: response.newtoken,
                            message: response.message,
                            statusmessage: 'ui positive floating message '
                        });

                        forma.autoReset();
                        forma.getBeneficiarios(id, forma);
                    }, function reject(reason) {

                        if (reason.status == 200) {

                            var response = validaError(reason);

                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message '
                            });
                        } else {
                            var _response2 = validaError(reason);

                            forma.setState({
                                csrf: _response2.newtoken,
                                message: _response2.message,
                                statusmessage: 'ui negative floating message'
                            });
                        }
                        forma.autoReset();
                    });
                }
            }).modal('show');
        }
    }, {
        key: "render",
        value: function render() {
            var _this19 = this;

            var today = new Date();
            var pf = null;
            var pm = null;
            var edonac = null;
            if (this.state.tipo == "F") {
                pf = React.createElement(InputField, { id: "rfc", mayuscula: "true", label: "RFC", valor: this.state.rfc, onChange: this.handleInputChange.bind(this) });
            } else {
                pm = React.createElement(InputField, { id: "rfc", mayuscula: "true", label: "RFC", valor: this.state.rfc, onChange: this.handleInputChange.bind(this) });
            }

            var bNac1 = false;
            var bNac2 = false;
            if (this.state.paisnac == "MEX") {
                edonac = React.createElement(SelectDropDown, { id: "edonac", label: "Estado de Nacimiento", valor: this.state.edonac, valores: this.state.catestado, onChange: this.handleInputChange.bind(this) });
                bNac1 = true;
            } else {
                edonac = React.createElement(InputField, { id: "lugnac", mayuscula: "true", label: "Lugar de Nacimiento", valor: this.state.lugnac, onChange: this.handleInputChange.bind(this) });
                bNac2 = true;
            }

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
                            "Solicitud de ingreso de socio"
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
                                { className: "ui button", "data-tooltip": "Formato PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
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
                            window.clearTimeout(_this19.timeout);
                            _this19.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get soling" },
                    React.createElement(
                        "form",
                        { className: "ui form small formgen", ref: "form" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: this.state.activo === 1 ? "disablediv" : "" },
                            React.createElement(
                                "div",
                                { className: "three fields" },
                                React.createElement(InputFieldFind, { icons: this.state.icons1, id: "idpersona", name: "idpersona", cols: "two wide", label: "Solicitud", placeholder: "Buscar", onClick: this.handleFind.bind(this), onKeyPress: this.hanleKeyPress.bind(this) }),
                                React.createElement(
                                    "div",
                                    { className: "three wide field" },
                                    React.createElement(InputField, { id: "fechaalta", readOnly: 'readOnly', label: "Fecha alta", name: "fechaalta", valor: this.state.fechaalta, onChange: this.handleInputChange.bind(this) })
                                )
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: "ui steps" },
                            React.createElement(Steps, { valor: this.state.stepno, value: "1", icon: "folder outline icon", titulo: "Datos Personales", onClick: this.handleState.bind(this, 1) }),
                            React.createElement(Steps, { valor: this.state.stepno, value: "2", icon: "dollar icon", titulo: "Actividad econ\xF3mica", onClick: this.handleState.bind(this, 2) }),
                            React.createElement(Steps, { valor: this.state.stepno, value: "3", icon: "map outline icon", titulo: "Domicilio", onClick: this.handleState.bind(this, 3) }),
                            React.createElement(Steps, { valor: this.state.stepno, value: "4", icon: "check circle outline icon", titulo: "Beneficiario", onClick: this.handleState.bind(this, 4) })
                        ),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 1 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectOption, { name: "tipo", id: "tipo", label: "Persona", valor: this.state.tipo, valores: [{ name: "Fisica", value: "F" }, { name: "Moral", value: "M" }], onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: this.state.tipo == 'F' ? 'step hidden' : '' },
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(InputField, { id: "cia", label: "Compa\xF1ia", mayuscula: "true", valor: this.state.cia, onChange: this.handleInputChange.bind(this) }),
                                    React.createElement(
                                        "div",
                                        { className: "two fields" },
                                        React.createElement(SelectOption, { id: "idtiposociedad", label: "Tipo de Sociedad", valor: this.state.idtiposociedad, valores: this.state.cattiposociedad, onChange: this.handleInputChange.bind(this) }),
                                        pm
                                    )
                                ),
                                React.createElement(
                                    "h5",
                                    { className: "ui horizontal divider header blue" },
                                    "Datos del Representante Legal"
                                ),
                                React.createElement("div", { className: "row" })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "nombre1", mayuscula: "true", label: "Primer Nombre", valor: this.state.nombre1, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "nombre2", mayuscula: "true", label: "Segundo Nombre", valor: this.state.nombre2, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "apaterno", mayuscula: "true", label: "Apellido Paterno", valor: this.state.apaterno, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "amaterno", mayuscula: "true", label: "Apellido Materno", valor: this.state.amaterno, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "two  fields" },
                                React.createElement(InputField, { id: "aliaspf", mayuscula: "true", label: "Conocida(o) como", valor: this.state.aliaspf, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(
                                        "div",
                                        { className: "field" },
                                        React.createElement(Calendar, { name: "fecha_nac", label: "Fec. Nacimiento", valor: this.state.fecha_nac, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    React.createElement(SelectOption, { name: "sexo", id: "sexo", label: "Sexo", valor: this.state.sexo, valores: [{ name: "Femenino", value: "F" }, { name: "Masculino", value: "M" }], onChange: this.handleInputChange.bind(this) })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectDropDown, { id: "paisnac", label: "Pais de Nacimiento", valor: this.state.paisnac, valores: this.state.catpais, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(SelectDropDown, { id: "edonac", visibility: bNac1, label: "Estado de Nacimiento", valor: this.state.edonac, valores: this.state.catestado, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "lugnac", visibility: bNac2, mayuscula: "true", label: "Lugar de Nacimiento", valor: this.state.lugnac, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(SelectDropDown, { id: "idnacionalidad", label: "Nacionalidad", valor: this.state.idnacionalidad, valores: this.state.catpais, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(SelectOption, { id: "edocivil", label: "Estado Civil", valor: this.state.edocivil, valores: this.state.catedocivil, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectDropDown, { id: "escolaridad", label: "Escolaridad", valor: this.state.escolaridad, valores: this.state.catescolar, onChange: this.handleInputChange.bind(this) }),
                                pf,
                                React.createElement(InputField, { id: "curp", mayuscula: "true", label: "CURP", valor: this.state.curp, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "folio_ife", mayuscula: "true", label: "IFE/INE", valor: this.state.folio_ife, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "vine", label: "Fecha Vencimiento INE", valor: this.state.vine, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "otroiden", mayuscula: "true", label: "Otra identificaci\xF3n", valor: this.state.otroiden, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(
                                    "div",
                                    { className: "field" },
                                    React.createElement(
                                        "label",
                                        null,
                                        "Email"
                                    ),
                                    React.createElement("input", { name: "email", id: "email", type: "text", value: this.state.email, onChange: this.handleInputChange.bind(this) })
                                ),
                                React.createElement(InputField, { id: "celular", label: "Celular", valor: this.state.celular, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "three  fields" },
                                React.createElement(InputField, { id: "conyuge", mayuscula: "true", label: "Nombre del conyuge", valor: this.state.conyuge, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(SelectOption, { name: "personarel", id: "personarel", label: "Vinculo Familiar con Empleado(s) o Directivos de la empresa", valor: this.state.personarel, valores: [{ name: "No", value: "0" }, { name: "Si", value: "1" }], onChange: this.handleInputChange.bind(this) }),
                                React.createElement(SelectOption, { id: "idpersonarel", label: "Persona Relacionada", valor: this.state.idpersonarel, valores: this.state.catpersonarel, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "field" },
                                React.createElement(InputField, { id: "nota", mayuscula: "true", label: "Nota", valor: this.state.nota, onChange: this.handleInputChange.bind(this) })
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 2 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectDropDown, { id: "idactividad", label: "Actividad econ\xF3mica", valor: this.state.idactividad, valores: this.state.catactividad, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "patrimonio", label: "Monto del patrimonio", valor: this.state.patrimonio, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "experiencia", label: "Experiencia del actividad (a\xF1os)", valor: this.state.experiencia, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "teltrabajo", label: "Tel\xE9fono del domicilio laboral", valor: this.state.teltrabajo, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputField, { id: "domlaboral", mayuscula: "true", label: "Domicilio Laboral", valor: this.state.domlaboral, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "domlabref", mayuscula: "true", label: "Referencias del domicilio Laboral", valor: this.state.domlabref, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputFieldNumber, { id: "ingresomen", label: "Ingresos mensuales", valor: this.state.ingresomen, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "ingresomenext", label: "Ingresos extraordinarios", valor: this.state.ingresomenext, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "egresomen", label: "Egresos mensuales", valor: this.state.egresomen, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "egresomenext", label: "Egresos extraordinarios", valor: this.state.egresomenext, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputField, { id: "dependientes", cols: "four wide", label: "No. de personas que dependen de Usted", valor: this.state.dependientes, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "ahorro", cols: "four wide", label: "Compromiso de ahorro", valor: this.state.ahorro, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "ui vertical segment right aligned" },
                                React.createElement(
                                    "div",
                                    { className: "field" },
                                    React.createElement(
                                        "button",
                                        { className: "ui bottom primary basic button", type: "button", name: "action", onClick: this.handleSubmit.bind(this) },
                                        React.createElement("i", { className: "send icon" }),
                                        " ",
                                        this.state.boton,
                                        " "
                                    )
                                )
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "get solingdom" },
                    React.createElement(
                        "form",
                        { className: "ui form formdom", ref: "formdom" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 3 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(
                                    "div",
                                    { className: "three wide field hidden" },
                                    React.createElement(Calendar, { name: "fechaaltad", label: "Fecha alta", valor: this.state.fechaaltad, onChange: this.handleInputChange.bind(this) })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "direccion1", mayuscula: "true", cols: "ten wide", label: "Direcci\xF3n", valor: this.state.direccion1, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "noexterior", mayuscula: "true", cols: "two wide", label: "No. Ext.", valor: this.state.noexterior, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "nointerior", mayuscula: "true", cols: "two wide", label: "No. Int.", valor: this.state.nointerior, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "direccion2", mayuscula: "true", cols: "five wide", label: "Referencia del domicilio", valor: this.state.direccion2, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectOption, { id: "idestado", label: "Estado", valor: this.state.idestado, valores: this.state.catestadob, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(SelectDropDown, { id: "idmunicipio", label: "Municipio", cols: "five wide", valor: this.state.idmunicipio, valores: this.state.catmpio, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(SelectDropDown, { id: "idcolonia", label: "Colonia", cols: "five wide", valor: this.state.idcolonia, valores: this.state.catcolonia, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(SelectDropDown, { id: "cp", label: "C\xF3digo Postal", cols: "two wide", valor: this.state.cp, valores: this.state.catcp, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 })
                            ),
                            React.createElement(
                                "div",
                                { className: "three fields" },
                                React.createElement(InputField, { id: "ciudad", mayuscula: "true", label: "Ciudad", valor: this.state.ciudad, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "tiempo", label: "Tiempo de radicar en el domicilio actual (a\xF1os)", valor: this.state.tiempo, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "telefono", label: "Tel\xE9fono local", valor: this.state.telefono, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectOption, { name: "tipovivienda", id: "tipovivienda", label: "Tipo de Vivienda", valor: this.state.tipovivienda, valores: [{ name: "Propia", value: "1" }, { name: "Rentada", value: "2" }, { name: "Familiar", value: "3" }, { name: "Prestada", value: "4" }, { name: "Otro", value: "5" }], onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(CheckBox, { titulo: "Agua Potable", name: "aguapot", valor: this.state.aguapot, onClickCheck: this.handleonClickCheck.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(CheckBox, { titulo: "Energia electrica", name: "enerelec", valor: this.state.enerelec, onClickCheck: this.handleonClickCheck.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(CheckBox, { titulo: "Drenaje", name: "drenaje", valor: this.state.drenaje, onClickCheck: this.handleonClickCheck.bind(this), disabled: this.state.disabledboton2 })
                            ),
                            React.createElement(
                                "div",
                                { className: "ui vertical segment right aligned" },
                                React.createElement(
                                    "div",
                                    { className: "field" },
                                    React.createElement(
                                        "button",
                                        { className: "ui bottom primary basic button", type: "button", name: "action", disabled: this.state.disabledboton2, onClick: this.handleSubmitDom.bind(this) },
                                        React.createElement("i", { className: "send icon" }),
                                        " ",
                                        this.state.boton2,
                                        " "
                                    )
                                )
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "get solingben" },
                    React.createElement(
                        "form",
                        { className: "ui form formben", ref: "formben" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 4 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: "two fields hidden" },
                                React.createElement(
                                    "div",
                                    { className: "three wide field" },
                                    React.createElement(Calendar, { name: "fechaaltab", label: "Fecha alta", valor: this.state.fechaaltab, onChange: this.handleInputChange.bind(this) })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "two fields hidden" },
                                React.createElement("input", { type: "text", name: "idpersonaben", value: this.state.idpersonaben })
                            ),
                            React.createElement(
                                "div",
                                { className: "one fields" },
                                React.createElement(InputField, { id: "rfc_ben", mayuscula: "true", cols: "s3", label: "RFC", valor: this.state.rfc_ben, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton3 })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "nombre1_ben", mayuscula: "true", label: "Primer Nombre", valor: this.state.nombre1_ben, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton3 }),
                                React.createElement(InputField, { id: "nombre2_ben", mayuscula: "true", label: "Segundo Nombre", valor: this.state.nombre2_ben, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton3 }),
                                React.createElement(InputField, { id: "apaterno_ben", mayuscula: "true", label: "Apellido Paterno", valor: this.state.apaterno_ben, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton3 }),
                                React.createElement(InputField, { id: "amaterno_ben", mayuscula: "true", label: "Apellido Materno", valor: this.state.amaterno_ben, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton3 })
                            ),
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputField, { id: "aliaspf_ben", mayuscula: "true", label: "Conocida(o) como", valor: this.state.aliaspf_ben, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(SelectOption, { name: "sexo_ben", id: "sexo_ben", label: "Sexo", valor: this.state.sexo_ben, valores: [{ name: "Femenino", value: "F" }, { name: "Masculino", value: "M" }], onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton3 }),
                                    React.createElement(InputField, { id: "telefono_ben", cols: "s3", label: "Celular", valor: this.state.telefono_ben, onChange: this.handleInputChange.bind(this) })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectDropDown, { id: "idparentesco", label: "Parentesco", valor: this.state.parentesco, valores: this.state.catparentesco, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(InputField, { id: "porcentaje", cols: "s3", label: "% participaci\xF3n", valor: this.state.porcentaje, onChange: this.handleInputChange.bind(this) })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "direccion1b", mayuscula: "true", cols: "ten wide", label: "Direcci\xF3n", valor: this.state.direccion1b, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "noexteriorb", mayuscula: "true", cols: "two wide", label: "No. Ext.", valor: this.state.noexteriorb, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "nointeriorb", mayuscula: "true", cols: "two wide", label: "No. Int.", valor: this.state.nointeriorb, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "direccion2b", mayuscula: "true", cols: "five wide", label: "Referencia del domicilio", valor: this.state.direccion2b, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(SelectOption, { id: "idestadob", label: "Estado", valor: this.state.idestadob, valores: this.state.catestadoben, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(SelectDropDown, { id: "idmunicipiob", label: "Municipio", cols: "five wide", valor: this.state.idmunicipiob, valores: this.state.catmpiob, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(SelectDropDown, { id: "idcoloniab", label: "Colonia", cols: "five wide", valor: this.state.idcoloniab, valores: this.state.catcoloniab, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(SelectDropDown, { id: "cpb", label: "C\xF3digo Postal", cols: "two wide", valor: this.state.cpb, valores: this.state.catcpb, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 })
                            ),
                            React.createElement(
                                "div",
                                { className: "three fields" },
                                React.createElement(InputField, { id: "ciudadb", mayuscula: "true", label: "Ciudad", valor: this.state.ciudadb, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "tiempob", label: "Tiempo de radicar en el domicilio actual (a\xF1os)", valor: this.state.tiempob, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 }),
                                React.createElement(InputField, { id: "telefonob", label: "Tel\xE9fono local", valor: this.state.telefonob, onChange: this.handleInputChange.bind(this), disabled: this.state.disabledboton2 })
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
                                        " ",
                                        this.state.boton3,
                                        " "
                                    )
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "ui secondary menu" },
                                React.createElement(
                                    "div",
                                    { className: "right menu" },
                                    React.createElement(
                                        "div",
                                        { className: "ui basic icon buttons" },
                                        React.createElement(
                                            "a",
                                            { className: "ui button", "data-tooltip": "Nuevo Beneficiario" },
                                            React.createElement("i", { className: "plus square outline icon", onClick: this.handleButton3.bind(this) })
                                        )
                                    )
                                )
                            ),
                            React.createElement(
                                "div",
                                null,
                                React.createElement(Table, { name: "catBene", datos: this.state.catbene, onClick: this.handleClickRecord.bind(this), onClickDel: this.handleClickRecordDel.bind(this) })
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "ui vertical segment" },
                    React.createElement(
                        "div",
                        { className: "ui vertical segment right aligned" },
                        React.createElement(
                            "button",
                            { className: "ui labeled icon positive basic button", onClick: this.handleClickPrevious.bind(this) },
                            React.createElement("i", { className: "left chevron icon" }),
                            " Anterior "
                        ),
                        React.createElement(
                            "button",
                            { className: "ui right labeled icon positive basic button", onClick: this.handleClickNext.bind(this) },
                            "Siguiente ",
                            React.createElement("i", { className: "right chevron icon" }),
                            " "
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
    minCharacters: 6,
    apiSettings: {
        url: base_url + 'api/CarteraV1/solcrefind?q={query}',
        onResponse: function onResponse(Response) {
            var response = {
                results: {}
            };
            if (!Response || !Response.result) {
                return;
            }
            $.each(Response.result, function (index, item) {
                var sucursal = item.idsucursal || 'Sin asignar',
                    maxResults = 8;
                if (index >= maxResults) {
                    return false;
                }
                if (response.results[sucursal] === undefined) {
                    response.results[sucursal] = {
                        name: sucursal,
                        results: []
                    };
                }
                // add result to category
                response.results[sucursal].results.push({
                    title: item.nombre,
                    description: item.idpersona + ' - ' + item.rfc
                });
            });
            return response;
        }
    }
});