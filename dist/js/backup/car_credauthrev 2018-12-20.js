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

var InputFieldNumber = function (_React$Component4) {
    _inherits(InputFieldNumber, _React$Component4);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: "render",
        value: function render() {
            var _this8 = this;

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
                            return _this8.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var Mensaje = function (_React$Component5) {
    _inherits(Mensaje, _React$Component5);

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

var Calendar = function (_React$Component6) {
    _inherits(Calendar, _React$Component6);

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

var SelectDropDown = function (_React$Component7) {
    _inherits(SelectDropDown, _React$Component7);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this11 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this11.state = {
            value: ""
        };
        _this11.handleSelectChange = _this11.handleSelectChange.bind(_this11);
        return _this11;
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

var SelectOption = function (_React$Component8) {
    _inherits(SelectOption, _React$Component8);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this12 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this12.state = {
            value: ""
        };
        return _this12;
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

var RecordDetalle = function (_React$Component9) {
    _inherits(RecordDetalle, _React$Component9);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this13 = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this13.handleDeleteRecord = _this13.handleDeleteRecord.bind(_this13);
        return _this13;
    }

    _createClass(RecordDetalle, [{
        key: "handleDeleteRecord",
        value: function handleDeleteRecord() {
            var idcredito = this.props.registro.idcredito;
            var forma = this.props;
            alert(idcredito);
            $('.test.modal').modal({
                closable: false,
                onApprove: function onApprove() {
                    $.ajax({
                        url: base_url + ("/api/CarteraD1/creditosrev/" + idcredito),
                        type: 'PUT',
                        dataType: 'json',
                        success: function (response) {
                            if (response.status === "OK") {
                                //                            forma.onClickDelete(1);
                            }
                        }.bind(this),
                        error: function (xhr, status, err) {
                            console.log('error');
                        }.bind(this)
                    });
                }
            }).modal('show');
        }
    }, {
        key: "render",
        value: function render() {
            //let checked = this.props.registro.activo ? <i className="green checkmark icon"></i> : '' ;
            return React.createElement(
                "tr",
                null,
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idcredito
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.nombre
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.idpagare
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.monto
                ),
                React.createElement(
                    "td",
                    null,
                    this.props.registro.fecha_aprov
                ),
                React.createElement(
                    "td",
                    { className: " center aligned" },
                    React.createElement(
                        "a",
                        { "data-tooltip": "Eliminar autorizaci\xF3n" },
                        React.createElement("i", { className: "trash outline icon circular red", onClick: this.handleDeleteRecord })
                    )
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

var Table = function (_React$Component10) {
    _inherits(Table, _React$Component10);

    function Table(props) {
        _classCallCheck(this, Table);

        return _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));
    }

    _createClass(Table, [{
        key: "render",
        value: function render() {
            var rows = [];
            var datos = this.props.datos;

            /*
            let pageclick = this.props.onClickPage;
            let configpage = {
                total_paginas: this.props.datos.total_paginas,
                pag_actual: this.props.datos.pag_actual
            }
            */
            //    if (datos instanceof Array === false){
            datos.forEach(function (record) {
                rows.push(React.createElement(RecordDetalle, { registro: record }));
            });
            //    }
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
                        React.createElement(Lista, { enca: ['Numero', 'Nombre', 'Pagare', 'Monto', 'Fecha aprov'] })
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

var Captura = function (_React$Component11) {
    _inherits(Captura, _React$Component11);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this15 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this15.state = {
            blnActivar: true,
            blnCreditoColmena: false,
            blnCreditoTemporada: false,
            csrf: "", message: "",
            statusmessage: 'ui floating hidden message',
            stepno: 1,
            creditos: [],
            boton: 'Enviar', btnAutoriza: 'Autorizar', icons1: 'inverted circular search link icon'
        };
        return _this15;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            $.ajax({
                url: base_url + '/api/CarteraD1/creditosrev',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        creditos: response.result
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
            this.setState(_defineProperty({}, name, value));

            if (name === "nivel") {
                this.setState(function (prevState, props) {
                    return {
                        monto: prevState.nivel * 1000
                    };
                });
            }
            //Consultas a cartera
            if (name === "idacreditado") {
                if (event.target.value != "") {
                    var link = "";
                    if (name === "idacreditado") {
                        link = "get_acreditado";
                    }
                    link = link + "/" + event.target.value;
                    $.ajax({
                        url: base_url + '/api/CarteraD1/' + link,
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if (name === "idacreditado") {
                                this.asignaAcreditado(response.acreditado[0]);
                                /*
                                this.setState({
                                    cataval1: response.cataval1,
                                    cataval2: response.cataval2
                                });
                                */
                            }
                        }.bind(this),
                        error: function (xhr, status, err) {
                            console.log('error');
                        }.bind(this)
                    });
                }
            }
            if (name === "idcolmena") {
                var _link = "";
                if (name === "idcolmena") {
                    _link = "get_colmena_acreditados_get";
                }

                var forma = this;
                _link = _link + "/" + event.target.value;
                var object = {
                    url: base_url + '/api/GeneralD1/' + _link,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    this.setState({
                        cat_colmena_acreditado: response.result
                        //idgrupo: ""
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
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    idacreditado: {
                        identifier: 'idacreditado',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione al acreditado'
                        }]
                    },
                    idcolmena: {
                        identifier: 'idcolmena',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la colmena'
                        }]
                    },
                    idgrupo: {
                        identifier: 'idgrupo',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el grupo'
                        }]
                    },
                    proy_nombre: {
                        identifier: 'proy_nombre',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el nombre del proyecto'
                        }, {
                            type: 'minLength[5]',
                            prompt: 'Minimo 5 caracteres'
                        }, {
                            type: 'maxLength[80]',
                            prompt: 'Longitu máxima de 80 caracteres'
                        }]
                    },
                    proy_descri: {
                        identifier: 'proy_descri',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture la descripción del proyecto'
                        }, {
                            type: 'minLength[5]',
                            prompt: 'Minimo 5 caracteres'
                        }, {
                            type: 'maxLength[250]',
                            prompt: 'Longitu máxima de 250 caracteres'
                        }]
                    },
                    proy_lugar: {
                        identifier: 'proy_lugar',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el lugar donde se ejecutará el proyecto'
                        }, {
                            type: 'minLength[5]',
                            prompt: 'Minimo 5 caracteres'
                        }, {
                            type: 'maxLength[250]',
                            prompt: 'Longitu máxima de 250 caracteres'
                        }]
                    },
                    proy_observa: {
                        identifier: 'proy_observa',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture las notas y observaciones al proyecto'
                        }, {
                            type: 'minLength[5]',
                            prompt: 'Minimo 5 caracteres'
                        }, {
                            type: 'maxLength[250]',
                            prompt: 'Longitu máxima de 250 caracteres'
                        }]
                    },
                    idchecklist: {
                        identifier: 'idchecklist',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el CheckList'
                        }]
                    },
                    nivel: {
                        identifier: 'nivel',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el nivel'
                        }]
                    }
                }
            });

            $('.ui.form.formgen').form('validate form');
            var valida = $('.ui.form.formgen').form('is valid');
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
                            url: base_url + "api/CarteraD1/add_crediton",
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            //alert("Resolve");
                            if (forma.state.boton === 'Enviar') {
                                //alert("Enviar");
                                var _idcredito = response.insert_id;
                                forma.setState({
                                    idcredito: _idcredito,
                                    idexiste: _idcredito,
                                    csrf: response.newtoken,
                                    message: response.message.concat(' ' + response.insert_id.toString()),
                                    statusmessage: 'ui positive floating message ',
                                    boton: 'Enviar'
                                });
                            } else {
                                //alert("Else");
                                forma.setState({
                                    csrf: response.newtoken,
                                    message: response.message,
                                    statusmessage: 'ui positive floating message ',
                                    boton: 'Actualizar'
                                });
                            }
                            forma.autoReset();
                        }, function reject(reason) {
                            /*
                            let msg = validaError(reason).message;
                            alert(msg);
                            */
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
        key: "handleSubmitAutoriza2",
        value: function handleSubmitAutoriza2(event) {
            event.preventDefault();
            var valida = true; //$('.ui.form.formaut').form('is valid');

            if (valida == true) {
                var $form = $('.get.solingaut form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var forma = this;
                $('.test.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        $.ajax({
                            url: base_url + 'auth/Autho/validateAutUser/10031',
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            },
                            success: function (response) {
                                if (response.status === 'OK') {
                                    forma.setState({
                                        csrf: response.newtoken,
                                        message: response.message,
                                        statusmessage: 'ui positive floating message ',
                                        btnAutoriza: 'Autoriza2'
                                    });

                                    if (valida == true) {
                                        var $form = $('.get.soling form'),
                                            allFields = $form.form('get values'),
                                            token = $form.form('get value', 'csrf_bancomunidad_token');
                                        var tipo = 'PUT';
                                        var _forma = this;
                                        $('.test.modal').modal({
                                            closable: false,
                                            onApprove: function onApprove() {
                                                $.ajax({
                                                    url: base_url + 'api/CarteraD1/aut_credito',
                                                    type: tipo,
                                                    dataType: 'json',
                                                    data: {
                                                        csrf_bancomunidad_token: token,
                                                        data: allFields
                                                    },
                                                    success: function (response) {
                                                        if (response.status === 'OK') {
                                                            _forma.setState({
                                                                csrf: response.newtoken,
                                                                //message: response.message.concat(' ' + response.insert_id.toString()),
                                                                message: response.message,
                                                                statusmessage: 'ui positive floating message ',
                                                                btnAutoriza: 'Autorizar'
                                                            });
                                                        }
                                                    }.bind(this),
                                                    error: function (xhr, status, err) {
                                                        if (xhr.status === 404) {
                                                            _forma.setState({
                                                                csrf: xhr.responseJSON.newtoken,
                                                                message: xhr.responseJSON.message,
                                                                statusmessage: 'ui negative floating message'
                                                            });
                                                        } else if (xhr.status === 409) {
                                                            var cadena = "";
                                                            var pos = xhr.responseText.indexOf('{"status"');
                                                            if (pos !== 0) {
                                                                cadena = xhr.responseText.substring(pos);
                                                            }
                                                            var arreglo = JSON.parse(cadena);
                                                            _forma.setState({
                                                                csrf: arreglo.newtoken,
                                                                message: arreglo.message,
                                                                statusmessage: 'ui negative floating message'
                                                            });
                                                        }
                                                    }.bind(this)
                                                });
                                            }
                                        }).modal('show');
                                    } else {
                                        this.setState({
                                            message: 'Datos incompletos!',
                                            statusmessage: 'ui negative floating message'
                                        });
                                    }
                                }
                            }.bind(this),
                            error: function (xhr, status, err) {
                                if (xhr.status === 404) {
                                    forma.setState({
                                        csrf: xhr.responseJSON.newtoken,
                                        message: xhr.responseJSON.message,
                                        statusmessage: 'ui negative floating message'
                                    });
                                } else if (xhr.status === 409) {
                                    var cadena = "";
                                    var pos = xhr.responseText.indexOf('{"status"');
                                    if (pos !== 0) {
                                        cadena = xhr.responseText.substring(pos);
                                    }
                                    var arreglo = JSON.parse(cadena);
                                    forma.setState({
                                        csrf: arreglo.newtoken,
                                        message: arreglo.message,
                                        statusmessage: 'ui negative floating message'
                                    });
                                }
                            }.bind(this)

                        });
                    }
                }).modal('show');
            } else {
                this.setState({
                    message: 'Datos incompletos!',
                    statusmessage: 'ui negative floating message'
                });
            }
        }
    }, {
        key: "handleSubmitAutoriza",
        value: function handleSubmitAutoriza(event) {
            event.preventDefault();

            var forma = this;
            var valida = true;
            if (valida == true) {
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'PUT';
                var _forma2 = this;
                $('.test.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        $.ajax({
                            url: base_url + 'api/CarteraD1/aut_credito',
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            },
                            success: function (response) {
                                if (response.status === 'OK') {
                                    _forma2.setState({
                                        csrf: response.newtoken,
                                        //message: response.message.concat(' ' + response.insert_id.toString()),
                                        message: response.message,
                                        statusmessage: 'ui positive floating message ',
                                        btnAutoriza: 'Autorizar'
                                    });
                                }
                            }.bind(this),
                            error: function (xhr, status, err) {
                                if (xhr.status === 404) {
                                    _forma2.setState({
                                        csrf: xhr.responseJSON.newtoken,
                                        message: xhr.responseJSON.message,
                                        statusmessage: 'ui negative floating message'
                                    });
                                } else if (xhr.status === 409) {
                                    var cadena = "";
                                    var pos = xhr.responseText.indexOf('{"status"');
                                    if (pos !== 0) {
                                        cadena = xhr.responseText.substring(pos);
                                    }
                                    var arreglo = JSON.parse(cadena);
                                    _forma2.setState({
                                        csrf: arreglo.newtoken,
                                        message: arreglo.message,
                                        statusmessage: 'ui negative floating message'
                                    });
                                }
                            }.bind(this)
                        });
                    }
                }).modal('show');
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
        key: "asignaAcreditado",
        value: function asignaAcreditado(data) {
            //idacreditado, nombre, idsucursal, edocivil, idactividad, direccion}
            var today = new Date();
            //let miDir ="";

            this.setState({
                nosocio: data.idacreditado,
                nombre: data.nombre,
                //edocivil: data.edocivil,
                //idactividad: data.idactividad,
                idpagare: 'F' + today.getFullYear() + '' + (today.getMonth() + 1) + '' + today.getDate() + '-' + data.idacreditado,

                colmena_nombre: data.col_nombre,
                colmena_grupo: data.grupo_nombre,

                actividad_nombre: data.actividad_nombre,
                edocivil_nombre: data.edocivil_nombre,

                domicilio: data.direccion == null ? "" : data.direccion,
                idgrupo: data.idgrupo

                //idAvalTemp1: data.idaval1,
                //idAvalTemp2: data.idaval2,
                //idaval1: data.idaval1,
                //idaval2: data.idaval2
            });

            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                idgrupo: data.idgrupo
                //idaval1: data.idaval1,
                //idaval2: data.idaval2

                //actividad_nombre: data.actividad_nombre,
                //edocivil_nombre: data.edocivil_nombre
                //edocivil: data.edocivil,
                //idactividad: data.idactividad 
            });
        }
    }, {
        key: "asignaSolicitudCreditoNew",
        value: function asignaSolicitudCreditoNew() {
            this.setState({
                blnActivar: true,
                idacreditado: "0", nosocio: "", domicilio: "",

                fecha: '', idexiste: "", fecha_pago: '',
                edocivil_nombre: "",
                actividad_nombre: "",

                idAval1: 0,
                idAval2: 0,
                cataval1: [],
                cataval2: [],

                idpagare: "",
                nivel: "", monto: 0,

                proy_nombre: "", proy_descri: "", proy_lugar: "", proy_observa: "",

                idproducto: 1, idchecklist: 1,

                nocolmena: "", //idcolmena: "",
                colmena_nombre: "", grupo_nombre: "", idgrupo: "", blnGrupo: false,

                stepno: 1,
                //nombre: "",
                creditos: [],
                usuario_aprov: null,
                fecha_aprov: null,

                boton: "Enviar"
            });

            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                //idcredito: "",
                //idacreditado: "0",
                nombre: "",
                idcolmena: "",
                nocolmena: "",
                nivel: "",
                idgrupo: "",
                idaval1: "0",
                idaval2: "0",
                idchecklist: 1,
                fecha_aprov: null
            });
            $('.get.soling .ui.dropdown').dropdown('clear');
        }
    }, {
        key: "asignaSolicitudCredito",
        value: function asignaSolicitudCredito(data) {

            //let today = new Date();
            var fechaAlta = moment(data.fecha).format('DD/MM/YYYY');
            var fechaPago = moment(data.fecha_pago).format('DD/MM/YYYY');
            var fechaPago2 = moment(data.fecha_pago2).format('DD/MM/YYYY');
            var fechaEntregaCol = moment(data.fecha_entrega_col).format('DD/MM/YYYY');

            /*        let fechaAlta = new Date(data.fecha);
                    fechaAlta= fechaAlta.getDate() + '/' + ( fechaAlta.getMonth() + 1) + '/' + fechaAlta.getFullYear();
                    let fechaPago = new Date(data.fecha_pago);
                    fechaPago= fechaPago.getDate() + '/' + ( fechaPago.getMonth() + 1) + '/' + fechaPago.getFullYear();
              */

            $('#fecha').val(fechaAlta);
            $('#fecha_pago').val(fechaPago);
            $('#fecha_pago2').val(fechaPago2);
            $('#fecha_entrega_col').val(fechaEntregaCol);

            this.setState({

                fecha: fechaAlta,
                fecha_pago: fechaPago,
                fecha_pago2: fechaPago2,
                fecha_entrega_col: fechaEntregaCol,

                periodo: data.periodo,
                num_pagos: data.num_pagos,

                //idacreditado: data.idacreditado,
                //nombre: data.nombre, 
                idpagare: data.idpagare,
                proy_nombre: data.proy_nombre,
                proy_descri: data.proy_descri,
                proy_lugar: data.proy_lugar,
                proy_observa: data.proy_observa,
                fecha_aprov: data.fecha_aprov,
                usuario_aprov: data.usuario_aprov,

                idAvalTemp1: data.idaval1,
                idAvalTemp2: data.idaval2,

                idaval1: data.idaval1,
                idaval2: data.idaval2,

                actividad_nombre: data.actividad_nombre,
                edocivil_nombre: data.edocivil_nombre,

                colmena_grupo: data.nomgrupo,
                colmena_nombre: data.nomcolmena,

                domicilio: data.direccion

            });
            var $form = $('.get.soling form'),
                Folio = $form.form('set values', {
                fecha: fechaAlta,
                fecha_pago: fechaPago,
                fecha_pago2: fechaPago2,
                fecha_entrega_col: fechaEntregaCol,
                //idacreditado: data.idacreditado,
                idacreditado: data.acreditadoid,

                idcolmena: data.idcolmena,

                periodo: data.periodo,
                num_pagos: data.num_pagos,

                //edocivil: data.edocivil,
                //idactividad: data.idactividad,
                nivel: data.nivel,
                idgrupo: data.idgrupo,
                idchecklist: data.idchecklist,

                //idAvalTemp1: data.idaval1,
                //idAvalTemp2: data.idaval2,

                actividad_nombre: data.actividad_nombre,
                edocivil_nombre: data.edocivil_nombre,

                colmena_grupo: data.nomgrupo,
                colmena_nombre: data.nomcolmena,

                domicilio: data.direccion
            });
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value) {
            if (value != "") {
                //idcredito: value,
                this.setState({
                    icons1: 'spinner circular inverted blue loading icon' });

                var forma = this;
                var object = {
                    url: base_url + 'api/CarteraD1/get_solicitud_credito_ind/' + value,
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    forma.setState({
                        cataval1: 0, //response.cataval1,
                        cataval2: 0 //response.cataval2
                    });
                    forma.asignaSolicitudCredito(response.solcredito[0]);
                    forma.setState({
                        intCheckListReq: response.checklist[0].total,
                        creditos: response.creditos,
                        message: response.message,
                        statusmessage: 'ui positive floating message ',
                        boton: 'Actualizar',
                        blnActivar: false,
                        icons1: 'inverted circular search link icon'
                    });
                    forma.setState(function (prevState, props) {
                        return {
                            idexiste: idcredito
                        };
                    });
                    forma.autoReset();
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        csrf: response.newtoken,
                        message: response.message,
                        statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                    });
                    forma.asignaSolicitudCreditoNew();
                    forma.autoReset();
                });
            } else {
                var _forma3 = this;
                _forma3.setState({
                    message: "Ingrese el número de la solicitud de crédito",
                    statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                });
                _forma3.autoReset();
            }
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {

            if (e < 2) {
                this.asignaSolicitudCreditoNew();
                var $form = $('.get.soling form'),
                    Folio = $form.form('set values', {
                    idcredito: ""
                });
            } else if (e > 9 && e < 21) {
                if (this.state.boton == "Actualizar") {
                    var d = new Date();
                    var id = this.state.idcredito;

                    var link = "";
                    if (e === 10) {
                        link = "pdf_solicitud_credito";
                    } else if (e === 11) {
                        link = "pdf_pagare";
                    } else if (e === 12) {
                        link = "pdf_tabla_amortizacion";
                    } else if (e === 13) {
                        link = "pdf_checklist";
                    } else if (e === 14) {
                        link = "pdf_contrato";
                    } else if (e === 15) {
                        link = "pdf_ahorro";
                    } else if (e === 16) {
                        link = "pdf_convenio";
                    } else if (e === 17) {
                        link = "pdf_retgarantia";
                    } else if (e === 18) {
                        link = "pdf_retgarantia";
                    } else if (e === 19) {
                        link = "pdf_plan_pago";
                    } else if (e === 20) {
                        link = "pdf_tabla_amortizacion_nueva";
                    }
                    link = link + "/" + this.state.idcredito;

                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportD1/' + link;
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                } else {
                    var _link2 = "";
                    var today = new Date();
                    _link2 = "pdf_creditosfecha";
                    //link =`${link}/${this.state.fechaAlta}`;
                    _link2 = _link2 + "/111";
                    alert(_link2);
                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportD1/' + _link2;
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
            var _this16 = this;

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
                            "Solicitud de credito de socio"
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
                                { className: "ui button", "data-tooltip": "Cancelar captura" },
                                React.createElement("i", { className: "minus square outline icon", onClick: this.handleButton.bind(this, 1) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Solicitud credito PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 10) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Pagare PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 11) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Tabla de amortizaciones PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 12) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Checklist PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 13) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Contrato PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 14) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Ahorro PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 15) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Convenio PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 16) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Retiro garantia PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 17) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Plan de pagos PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 19) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Amortizaciones nueva" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 20) })
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
                            return _this16.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get solingamor" },
                    React.createElement(
                        "form",
                        { className: "ui form formaut", ref: "formaut" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            null,
                            React.createElement(Table, { datos: this.state.creditos })
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
    minCharacters: 8,
    apiSettings: {
        url: base_url + 'api/CarteraD1/find_acreditadosInd?q={query}',
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