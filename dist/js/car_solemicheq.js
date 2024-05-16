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

var Calendar = function (_React$Component3) {
    _inherits(Calendar, _React$Component3);

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

var Captura = function (_React$Component5) {
    _inherits(Captura, _React$Component5);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this6 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this6.state = {
            idfecha: '', catfechas: [],
            fecha_cheque: "",
            csrf: "", message: "",
            statusmessage: 'ui floating hidden message',
            stepno: 1,
            boton: 'Enviar', icons1: 'inverted circular search link icon'
        };
        return _this6;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {

            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            /*
             $.ajax({
                url: base_url + '/api/GeneralD1/get_solicitud_cheques',
                type: 'GET',
                dataType: 'json',
                success:function(response) {
                    this.setState({
                        catfechas: response.catfechas
                    });
                  }.bind(this),
                error: function(xhr, status, err) {
                    console.log('error');
                    console.log('error', xhr);
                    console.log('error',status);
                    console.log('error',err);
                }.bind(this)
            });
            */
        }
    }, {
        key: "handleInputChange",
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));

            if (name === "nivel") {
                this.setState(function (prevState, props) {
                    return {
                        monto: prevState.nivel * 1000
                    };
                });
            }
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            var valida = true;

            if (valida == true) {
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');

                /*
                let tipo = this.state.boton ==='Enviar' ? 'POST': 'PUT';
                let forma = this;
                  link="pdf_emision_cheques";
                
                $('.mini.modal')
                .modal({
                    closable  : false,
                    onApprove : function() {
                          let object = {
                            url: base_url + 'api/CarteraD1/add_credito',
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token, 
                                data: allFields
                            }
                        }
                        ajax(object).then(function resolve(response){
                                if (forma.state.boton ==='Enviar'){
                                    let idcredito = response.insert_id;
                                    forma.setState({
                                        idcredito: idcredito,
                                        idexiste: idcredito,
                                        csrf: response.newtoken,
                                        message: response.message.concat(' ' + response.insert_id.toString()),
                                        statusmessage: 'ui positive floating message ',
                                        boton: 'Enviar'
                                    });
                                }else{
                                    forma.setState({
                                        csrf: response.newtoken,
                                        message: response.message,
                                        statusmessage: 'ui positive floating message ',
                                        boton: 'Actualizar'
                                    });
                                }
                                  forma.autoReset();
                        }, function reject( reason ) {
                            let response = validaError(reason);
                            forma.setState({
                                csrf: response.newtoken, 
                                message: response.message,
                                statusmessage: 'ui negative floating message'  
                            });
                            forma.autoReset();
                        });
                    }
                })
                .modal('show');
                
                */
            } else {
                this.setState({
                    message: 'Datos incompletos!',
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset();
            }
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
        key: "handleButton",
        value: function handleButton(e, value) {

            var miFecha = $('#fecha_cheque').val();

            event.preventDefault();
            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {

                    miFecha: {
                        identifier: 'fecha_cheque',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la fecha'
                        }]
                    }

                }
            });

            $('.ui.form.formgen').form('validate form');
            var valida = $('.ui.form.formgen').form('is valid');
            if (valida == true) {

                var today = new Date();
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');

                var _miFecha = $('#fecha_cheque').val();
                var arrFecha = _miFecha.split("/");
                var otraFecha = arrFecha[2] + '-' + arrFecha[1] + '-' + arrFecha[0];
                if (e === 1) {
                    var d = new Date();

                    var link = "";
                    if (e === 1) {
                        link = "pdf_emision_cheques";
                    }
                    //link =`${link}/${this.state.idfecha}`            
                    link = link + "/" + otraFecha;

                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportD1/' + link;

                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } else if (e === 2) {
                    var _d = new Date();

                    var _link = "";
                    _link = "pdf_emision_cheques_year";
                    _link = _link + "/0";
                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportD1/' + _link;
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } else if (e === 3) {
                    var _d2 = new Date();

                    var _link2 = "";
                    _link2 = "pdf_emision_cheques_year";
                    _link2 = _link2 + "/1";
                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportD1/' + _link2;
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                }
                this.autoReset();
            } else {
                this.setState({
                    message: 'Datos incompletos!',
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset();
            }
        }
    }, {
        key: "autoReset",
        value: function autoReset() {
            var self = this;
            window.clearTimeout(self.timeout);
            if (self.state.message != "") {
                this.timeout = window.setTimeout(function () {
                    self.setState({ message: '', statusmessage: 'ui message hidden' });
                }, 3000);
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
                            "Solicitud de emision de cheques"
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
                                { className: "ui button", "data-tooltip": "Emision de cheques" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Ejercicio anterior" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 2) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Ejercicio actual" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 3) })
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
                            { className: this.state.blnActivar === false ? "disablediv" : "" },
                            React.createElement(
                                "div",
                                { className: "four wide field" },
                                React.createElement(Calendar, { name: "fecha_cheque", label: "Seleccione la fecha de pago", valor: this.state.fecha_cheque, onChange: this.handleInputChange.bind(this) })
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