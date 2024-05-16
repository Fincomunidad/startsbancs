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
                    React.createElement("input", { type: "text", id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, value: this.props.valor, onChange: function onChange(event) {
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

var Captura = function (_React$Component6) {
    _inherits(Captura, _React$Component6);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this7 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this7.state = { csrf: '',
            idclave: 0,
            ocultar: true,
            movimiento: 0,
            importe: 0,
            message: '',
            statusmessage: 'hidden',
            idmov: 0,
            idmovdet: 0,
            existe: 0,
            idmovecho: 0,
            catMov: [],
            idmovisible: false,
            fechaconsulta: '',
            fechafin: ''
        };
        _this7.handleInputChange = _this7.handleInputChange.bind(_this7);
        _this7.handleonBlur = _this7.handleonBlur.bind(_this7);
        return _this7;
    }

    _createClass(Captura, [{
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            var link = "";
            var tit = "";
            this.setState(_defineProperty({}, name, value));

            if (name == "idmovecho") {
                this.setState({ idmovdet: value });
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
                this.printReport();
            }
        }
    }, {
        key: "printReport",
        value: function printReport() {

            var fechaconsulta = "0";
            var fechafin = "0";
            if ($('#fechaconsulta').val() != '') {
                var extraer = $('#fechaconsulta').val().split('/');
                var fec = new Date(extraer[2], extraer[1] - 1, extraer[0]);
                fechaconsulta = moment(fec).format('DDMMYYYY');
            }
            if ($('#fechafin').val() != '') {
                var _extraer = $('#fechafin').val().split('/');
                var _fec = new Date(_extraer[2], _extraer[1] - 1, _extraer[0]);
                fechafin = moment(_fec).format('DDMMYYYY');
            }

            if (fechaconsulta == '0' || fechafin == '0') {
                return;
            }

            var surl = 'cheques/' + fechaconsulta + '/' + fechafin;

            var a = document.createElement('a');
            a.href = base_url + 'api/ReportV1/' + surl;
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            /*
            
                    $.ajax({
                        url: base_url + 'api/ReportV1/' + surl,
                        type: 'GET',
                        dataType: 'text',
                        success: function (response) { 
                            var binaryData = [];
                            binaryData.push([response]);
            
                            var blob = new Blob(binaryData, { type: 'application/pdf' });
                            var link = document.createElement('a');
                            link.href = window.URL.createObjectURL(blob);
                            link.target = "_blank";
                            link.click();
            
                        }.bind(this),
                        error: function (xhr, status, err) {
                            if (xhr.status === 404) {
            
                            } else if (xhr.status === 409) {
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
            var _this8 = this;

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
                            "Relaci\xF3n Cheques"
                        )
                    ),
                    React.createElement(
                        "div",
                        { className: "ui  basic icon buttons" },
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Formato PDF" },
                            React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
                        ),
                        React.createElement(
                            "button",
                            { className: "ui button", "data-tooltip": "Formato Excel" },
                            React.createElement("i", { className: "file excel outline icon", onClick: this.handleButton.bind(this, 2) })
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
                    React.createElement("i", { className: statusicon, onClick: function onClick(event) {
                            window.clearTimeout(_this8.timeout);
                            _this8.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get bovopen" },
                    React.createElement(
                        "form",
                        { className: "ui form formopen", ref: "form" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "fields" },
                            React.createElement(Calendar, { name: "fechaconsulta", label: "Fecha consulta", valor: this.state.fechaconsulta, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(Calendar, { name: "fechafin", label: "Fecha Final", valor: this.state.fechafin, onChange: this.handleInputChange.bind(this) })
                        )
                    )
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

ReactDOM.render(React.createElement(Captura, null), document.getElementById('root'));