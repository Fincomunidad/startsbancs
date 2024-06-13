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
                    React.createElement("input", { type: "text", id: this.props.id, name: this.props.id, value: this.props.valor, onChange: function onChange(event) {
                            return _this8.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var SelectDropDown = function (_React$Component5) {
    _inherits(SelectDropDown, _React$Component5);

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

var SelectOption = function (_React$Component6) {
    _inherits(SelectOption, _React$Component6);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this10 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this10.state = {
            value: ""
        };
        return _this10;
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
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            var disabled = this.props.readOnly !== undefined ? this.props.readOnly !== '' ? 'disabled' : '' : "";
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
                    { className: "ui fluid dropdown", ref: "myCombo", disabled: disabled, name: this.props.id, id: this.props.id, onChange: this.handleSelectChange.bind(this) },
                    React.createElement(
                        "option",
                        { value: "0" },
                        "Seleccione"
                    ),
                    listItems
                )
            );
        }
    }]);

    return SelectOption;
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

function Calendar(props) {
    return React.createElement(
        "div",
        { className: "ui calendar", id: props.name },
        React.createElement(
            "div",
            { className: "field" },
            React.createElement(
                "label",
                null,
                props.label
            ),
            React.createElement(
                "div",
                { className: "ui input left icon" },
                React.createElement("i", { className: "calendar icon" }),
                React.createElement("input", { type: "text", name: props.name, id: props.name, value: props.valor, placeholder: "Fecha" })
            )
        )
    );
}

var CheckBox = function (_React$Component8) {
    _inherits(CheckBox, _React$Component8);

    function CheckBox(props) {
        _classCallCheck(this, CheckBox);

        return _possibleConstructorReturn(this, (CheckBox.__proto__ || Object.getPrototypeOf(CheckBox)).call(this, props));
    }

    _createClass(CheckBox, [{
        key: "render",
        value: function render() {
            var _this13 = this;

            var checked = this.props.valor === '1' ? 'ui checkbox checked' : 'ui checkbox';
            var valchecked = this.props.valor === '1' ? 'true' : 'false';
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
                                    _this13.props.onChange(event);
                                } },
                            React.createElement("input", { type: "checkbox", name: this.props.name, tabindex: "0", value: valchecked, "class": "hidden", onClick: function onClick(event) {
                                    console.log(event.target.name);_this13.props.onChange(event);
                                } }),
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

var Captura = function (_React$Component9) {
    _inherits(Captura, _React$Component9);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this14 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this14.state = { activo: 0,
            idacreditado: '',
            acreditadoid: '',
            idpersona: '',
            fechaalta: null,
            tipo: 'F',
            nombre1: "",
            nombre2: "",
            apaterno: "",
            amaterno: "",
            aliaspf: "", fecha_nac: '', sexo: "", edonac: "", edocivil: "", escolaridad: "",
            rfc: "", curp: "", folio_ife: "", otroiden: "", email: "", conyuge: "", celular: "", direccion1: "",
            noexterior: "", nointerior: "", direccion2: "", estado: "", imunicipio: "", colonia: "",
            ciudad: "", cp: "", tiempo: 0, telefono: "", tipoviviendac: '', aguapot: false, enerelec: false,
            drenaje: false, idactividad: "", patrimonio: 0, idparentesco: "", teltrabajo: "", domlaboral: "",
            domlabref: "", ingresomen: 0, ingresomenext: 0, egresomen: 0, egresomenext: 0, dependientes: 0,
            ahorro: 0, nombre1_ben: "", nombre2_ben: "", apaterno_ben: "", amaterno_ben: "", aliaspf_ben: "",
            rfc_ben: "", telefono_ben: "", participacion: 0, csrf: "", message: "",
            statusmessage: 'ui floating hidden message', stepno: 1, boton: 'Enviar', disabledboton: '', readOnly: '',
            icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon',
            idcolmena: 0, catColmenas: [], idgrupo: 0, catGrupos: [], nomcolmena: '', nomgrupo: '', orden: '', cat_grupo_orden: [],
            idcolcargo: 0, cat_col_cargos: [], idgrupocargo: 0, cat_grupo_cargos: [],
            idgrupoc: 0
        };
        return _this14;
    }

    _createClass(Captura, [{
        key: "componentWillMount",
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });

            $.ajax({
                url: base_url + '/api/CarteraV1/colmenas/1',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({ catColmenas: response.catcolmenas,
                        cat_grupo_orden: response.cat_grupo_orden,
                        cat_col_cargos: response.cat_col_cargos,
                        cat_grupo_cargos: response.cat_grupo_cargos
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
            if (name === "idcolmena" && value != "" && value != "0") {
                var forma = this;
                var idcolmena = value;
                var object = {
                    url: base_url + ("/api/CarteraV1/colmenas_grupos/" + idcolmena),
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {

                    forma.setState({
                        catGrupos: response.result
                    });

                    if (forma.state.idgrupoc != '0' && forma.state.boton == 'Actualizar') {
                        idgrupo = forma.state.idgrupoc;
                    }

                    forma.setState({
                        idgrupo: idgrupo
                    });

                    forma.asignaGrupo();
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
        key: "asignaGrupo",
        value: function asignaGrupo() {
            setTimeout(function () {
                var $form = $('.get.soling form'),
                    Folio = $form.form('set values', { idgrupo: '3985'
                });
            }, 500);
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            var rulesGrupo = [{
                type: 'empty',
                prompt: 'Seleccione el Grupo '
            }];
            if ((this.state.idgrupo == "0" || this.state.idgrupo == "") && this.state.boton != 'Actualizar') {
                rulesGrupo = [{
                    type: 'integer[1..9999]',
                    prompt: 'Seleccione el Grupo '
                }];
            }

            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    idpersona: {
                        identifier: 'idpersona',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture la Solicitud '
                        }, {
                            type: 'integer[1..999999]',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    idcolmena: {
                        identifier: 'idcolmena',
                        rules: [{
                            type: 'integer[1..999999]',
                            prompt: 'Seleccione la Colmena '
                        }]
                    },
                    idcolcargo: {
                        identifier: 'idcolcargo',
                        rules: [{
                            type: 'integer[0..4]',
                            prompt: 'Seleccione 3l cargo colmena '
                        }]
                    },

                    idgrupocargo: {
                        identifier: 'idgrupocargo',
                        rules: [{
                            type: 'integer[0..4]',
                            prompt: 'Seleccione 3l cargo de grupo '
                        }]
                    },

                    idgrupo: {
                        identifier: 'idgrupo',
                        rules: rulesGrupo
                    },
                    orden: {
                        identifier: 'orden',
                        rules: [{
                            type: 'integer[1..9999]',
                            prompt: 'Seleccione el orden'
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

                var tipo = 'POST';
                if (this.state.boton == 'Actualizar') {
                    tipo = 'PUT';
                }
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/CarteraV1/altasocio',
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {

                            /*                        var $form = $('.get.soling form'),
                                                    Folio = $form.form('set values', { idacreditado: response.pagare });
                                                     
                                                    */
                            var nomgrupo = $("#idgrupo option:selected").text();
                            forma.setState({
                                idacreditado: response.pagare,
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                idgrupo: 0, boton: 'Enviar', disabledboton: 'disabled', activo: 1,
                                nomgrupo: nomgrupo

                            });
                            //                        forma.clearData();


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
        key: "asignaPersona",
        value: function asignaPersona(data) {
            this.setState({ tipo: data.tipo, nombre1: data.nombre1, nombre2: data.nombre2, apaterno: data.apaterno, amaterno: data.amaterno,
                aliaspf: data.aliaspf, fecha_nac: data.fecha_nac, sexo: data.sexo, estadonac: data.estadonac, estadocivil: data.estadocivil,
                escolaridadc: data.escolaridadc, rfc: data.rfc, curp: data.curp, folio_ife: data.folio_ife, otroiden: data.otroiden, email: data.email,
                celular: data.celular, conyuge: data.conyuge, idactividad: data.idactividad, patrimonio: data.patrimonio, experiencia: data.experiencia,
                teltrabajo: data.teltrabajo, domlaboral: data.domlaboral, domlabref: data.domlabref, ingresomen: data.ingresomen, ingresomenext: data.ingresomenext,
                egresomen: data.egresomen, egresomenext: data.egresomenext, dependientes: data.dependientes, ahorro: data.ahorro
            });

            /*        var $form = $('.get.soling1 form'),
                    Folio = $form.form('set values', { sexo: data.sexo, edonac: data.edonac, edocivil: data.edocivil, escolaridad: data.escolaridad,
                        idactividad: data.idactividad
                   });
            */
        }
    }, {
        key: "asignaDom",
        value: function asignaDom(data) {
            if (data != undefined) {
                var tipov = '';
                switch (data.tipovivienda) {
                    case '1':
                        tipov = 'Propia';break;
                    case '2':
                        tipov = 'Rentada';break;
                    case '3':
                        tipov = 'Familiar';break;
                    case '4':
                        tipov = 'Prestada';break;
                    case '5':
                        tipov = 'Otro';break;
                }
                this.setState({ direccion1: data.direccion1, noexterior: data.noexterior, nointerior: data.nointerior, direccion2: data.direccion2,
                    estado: data.estado, municipio: data.municipio, colonia: data.colonia, cp: data.cp, ciudad: data.ciudad,
                    tiempo: data.tiempo, telefono: data.telefono, tipoviviendac: tipov, aguapot: data.aguapot, enerelec: data.enerelec,
                    drenaje: data.drenaje
                });
                var $form = $('.get.solingdom form'),
                    Folio = $form.form('set values', { aguapot: data.aguapot == '1' ? true : false, enerelec: data.enerelec == '1' ? true : false,
                    drenaje: data.drenaje == '1' ? true : false
                });
            } else {
                this.setState({ direccion1: '', noexterior: '', nointerior: '', direccion2: '',
                    estado: '', municipio: '', colonia: '', cp: '', ciudad: '',
                    tiempo: '', telefono: '', tipovivienda: '', aguapot: 0, enerelec: 0,
                    drenaje: 0
                });
                var $form = $('.get.solingdom form'),
                    Folio = $form.form('set values', { tipovivienda: '', aguapot: false, enerelec: false, drenaje: false
                });
            }
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value, name) {
            if (name == "idpersona") {
                this.setState({ idpersona: value, icons1: 'spinner circular inverted blue loading icon' });
                var idpersona2 = value;
                var idacreditado = '0';
            } else if (name == "idacreditado") {
                this.setState({ idacreditado: value, icons2: 'spinner circular inverted blue loading icon' });
                var idpersona2 = '0';
                var idacreditado = value;
            }

            var forma = this;
            var object = {
                url: base_url + 'api/CarteraV1/altasocio/' + idpersona2 + '/' + idacreditado,
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                forma.asignaPersona(response.persona[0]);
                forma.asignaDom(response.dom[0]);
                var messagedom = "";
                var sindomicilio = 0;
                if (response.dom[0] != undefined) {
                    forma.setState({
                        message: response.message,
                        statusmessage: 'ui positive floating message ',
                        disabledboton: 'disabled', activo: 1
                    });
                } else {
                    forma.setState({
                        message: 'Solicitud incompleta, faltan datos del domicilio!',
                        statusmessage: 'ui negative floating message ',
                        boton: 'Enviar',
                        disabledboton: ''
                    });
                    sindomicilio = 1;
                }

                if (response.socio[0] != undefined) {
                    var fecha = moment(response.socio[0].fecalta).format('DD/MM/YYYY');
                    var idcolcargo = 0;
                    var idgrupocargo = 0;

                    if (response.socio[0].acreditadoid == response.socio[0].idpresidente) {
                        forma.setState({ idcolcargo: 1 });
                        idcolcargo = 1;
                    } else if (response.socio[0].acreditadoid == response.socio[0].idsecretario) {
                        forma.setState({ idcolcargo: 2 });
                        idcolcargo = 2;
                    } else if (response.socio[0].acreditadoid == response.socio[0].idamaranto) {
                        forma.setState({ idcolcargo: 4 });
                        idcolcargo = 4;
                    } else {
                        forma.setState({ idcolcargo: 0 });
                        idcolcargo = 0;
                    }

                    if (response.socio[0].acreditadoid == response.socio[0].idcargo1) {
                        forma.setState({ idgrupocargo: 1 });
                        idgrupocargo = 1;
                    } else if (response.socio[0].acreditadoid == response.socio[0].idcargo2) {
                        forma.setState({ idgrupocargo: 3 });
                        idgrupocargo = 3;
                    } else {
                        forma.setState({ idgrupocargo: 0 });
                        idgrupocargo = 0;
                    }

                    forma.setState({
                        idacreditado: response.socio[0].idacreditado,
                        acreditadoid: response.socio[0].acreditadoid,
                        idcolmena: response.socio[0].idcolmena,
                        idpersona: response.socio[0].idpersona,
                        activo: 2,
                        nomcolmena: response.socio[0].colmena_numero + ' ' + response.socio[0].colmena_nombre,
                        nomgrupo: response.socio[0].grupo_nombre,
                        fechaalta: fecha,
                        orden: response.socio[0].orden,
                        idgrupoc: response.socio[0].idgrupo
                    });

                    if (forma.state.idgrupoc == null) {
                        forma.setState({ boton: 'Enviar' });
                        if (sindomicilio == 1) {
                            forma.setState({ disabledboton: 'disabled' });
                        } else {
                            forma.setState({ disabledboton: '' });
                        }
                    } else {
                        forma.setState({ boton: 'Actualizar', disabledboton: '' });
                    }

                    var $form = $('.get.soling form'),
                        Folio = $form.form('set values', { idcolmena: response.socio[0].idcolmena,
                        fechaalta: fecha,
                        orden: response.socio[0].orden,
                        idcolcargo: idcolcargo,
                        idgrupocargo: idgrupocargo });

                    //                $('#fechaalta').val(response.socio[0].fechaalta);
                } else {
                    forma.setState({
                        idacreditado: idacreditado,
                        idpersona: idpersona2, disabledboton: '',
                        idcolcargo: 0, idgrupocargo: 0
                    });
                }
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

                forma.clearData();
                forma.autoReset();
            });
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            if (e == 0) {
                this.setState({ activo: 0, idacreditado: '', acreditadoid: '', idpersona: '', disabledboton: '', idcolmena: '0', catGrupos: [], idgrupo: '0', fechaalta: null, orden: '0',
                    idcolcargo: '0',
                    idgrupocargo: '0'
                });

                var $form = $('.get.soling form'),
                    Folio = $form.form('set values', {
                    idcolmena: '0', idgrupo: '0', fechaalta: null, orden: '0', idcolcargo: '0', idgrupocargo: '0'
                });

                this.clearData();
            } else if (e === 1 || e === 2 || e === 3) {
                var d = new Date();
                var id = this.state.idpersona;
                var surl = 'solcreditopdf/' + id;
                if (e === 2 || e === 3) {
                    if (id > 0) {
                        if (e === 2) {
                            surl = 'aportacertif/' + id;
                        } else if (e === 3) {
                            surl = 'pdf_ahorro/' + id + '/p';
                        }
                    } else {
                        return;
                    }
                }

                var a = document.createElement('a');
                if (e === 3) {
                    a.href = base_url + 'api/ReportD1/' + surl;
                } else {
                    a.href = base_url + 'api/ReportV1/' + surl;
                }
                a.target = '_blank';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);

                /*
                             $.ajax({
                                url: base_url + 'api/ReportV1/'+surl,
                                    type: 'GET',
                                    dataType: 'text',
                                    documenttype: "application\pdf",
                                    success:function(response) {
                                        var blob=new Blob([response], {type:"application/pdf;charset=UTF-8"});
                                        var link=document.createElement('a');
                                        link.href=window.URL.createObjectURL(blob);
                                        if (e == 1){
                                            link.target ="_blank";
                                        }else if (e ==2){
                                            link.target ="_blank";
                                        }else if (e ==3){
                                            let hoy = d.getFullYear().toString()+(d.getMonth()+1).toString()+d.getDate().toString();
                                            link.download=id+"_"+hoy+".pdf";
                                        }
                                        link.click();
                                    }.bind(this),
                                    error: function(xhr, status, err) {
                                        if (xhr.status === 404) {
                                            
                                        }else if (xhr.status === 409) {
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
        }
    }, {
        key: "clearData",
        value: function clearData() {
            this.setState({ tipo: '', nombre1: '', nombre2: '', apaterno: '', amaterno: '',
                aliaspf: '', fecha_nac: '', sexo: '', edonac: '', edocivil: '',
                escolaridad: '', rfc: '', curp: '', folio_ife: '', otroiden: '', email: '',
                celular: '', conyuge: '', direccion1: '', noexterior: '', nointerior: '', direccion2: '',
                estado: '', municipio: '', colonia: '', cp: '', ciudad: '',
                tiempo: '', telefono: '', tipoviviendac: '', aguapot: '0', enerelec: '0',
                drenaje: '0', estadonac: '', estadocivil: '', escolaridadc: ''
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
        //        <Steps valor={this.state.stepno} value='2' icon='dollar icon' titulo='Actividad económica' onClick={this.handleState.bind(this,2)} />
        //        <Steps valor={this.state.stepno} value='4' icon='check circle outline icon' titulo='Beneficiario' onClick={this.handleState.bind(this,4)} />

    }, {
        key: "render",
        value: function render() {
            var _this15 = this;

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
            var acreditado = "hidden";

            if (this.state.disabledboton == "disabled") {
                cgrupo = " hidden ";
            } else {
                tgrupo = " hidden ";
            }
            if (this.state.boton == "Actualizar") {
                tgrupo = "  ";
                cgrupo = " hidden ";
            } else {
                tgrupo = " hidden ";
                cgrupo = "  ";
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
                            "Alta de socio"
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
                                { className: "ui button", "data-tooltip": "Formato PDF" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 1) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Certificado de Aportaci\xF3n" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 2) })
                            ),
                            React.createElement(
                                "button",
                                { className: "ui button", "data-tooltip": "Contrato de Ahorro" },
                                React.createElement("i", { className: "file pdf outline icon", onClick: this.handleButton.bind(this, 3) })
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
                            window.clearTimeout(_this15.timeout);
                            _this15.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    "div",
                    { className: "get soling" },
                    React.createElement(
                        "form",
                        { className: "ui form small formgen", ref: "form", onSubmit: this.handleSubmit.bind(this), method: "post" },
                        React.createElement("input", { type: "hidden", name: "csrf_bancomunidad_token", value: this.state.csrf }),
                        React.createElement(
                            "div",
                            { className: "three fields" },
                            React.createElement(InputFieldFind, { icons: this.state.icons1, readOnly: this.state.activo == 1 || this.state.activo == 2 ? 'readOnly' : '', id: "idpersona", name: "idpersona", valor: this.state.idpersona, cols: "three wide", label: "Solicitud", placeholder: "Buscar", onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                            React.createElement(InputField, { id: "idacreditado", readOnly: 'readOnly', label: "Acreditado", name: "idacreditado", valor: this.state.idacreditado, cols: "three wide", onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                            React.createElement(InputField, { id: "acreditadoid", cols: acreditado, readOnly: 'readOnly', label: "Acreditado", name: "acreditadoid", valor: this.state.acreditadoid, onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                            React.createElement(
                                "div",
                                { className: "three wide field" },
                                React.createElement(Calendar, { name: "fechaalta", label: "Fecha alta", valor: this.state.fechaalta })
                            ),
                            React.createElement(SelectOption, { cols: cgrupo, readOnly: '', name: "idcolmena", id: "idcolmena", label: "Colmena", valor: this.state.idcolmena, valores: this.state.catColmenas, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(InputField, { id: "nomcolmena", cols: tgrupo, readOnly: "readOnly", label: "Colmena", valor: this.state.nomcolmena, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(SelectOption, { readOnly: this.state.boton === 'Actualizar' ? 'readOnly' : '', name: "idgrupo", id: "idgrupo", label: "Grupo", valor: this.state.idgrupo, valores: this.state.catGrupos, cols: cgrupo, onChange: this.handleInputChange.bind(this)
                            }),
                            React.createElement(InputField, { id: "nomgrupo", cols: tgrupo, readOnly: "readOnly", label: "Grupo", valor: this.state.nomgrupo, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: "three fields" },
                            React.createElement(SelectOption, { name: "idcolcargo", id: "idcolcargo", label: "Cargo Colmena", valor: this.state.idcolcargo, valores: this.state.cat_col_cargos, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(SelectOption, { name: "idgrupocargo", id: "idgrupocargo", label: "Cargo en grupo", valor: this.state.idgrupocargo, valores: this.state.cat_grupo_cargos, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(SelectOption, { name: "idgrupo", id: "orden", label: "Posici\xF3n en grupo", valor: this.state.orden, valores: this.state.cat_grupo_orden, onChange: this.handleInputChange.bind(this)

                            })
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
                ),
                React.createElement(
                    "div",
                    { className: "get soling1" },
                    React.createElement(
                        "form",
                        { className: "ui form small formgen1", ref: "form" },
                        React.createElement(
                            "div",
                            { className: "ui tiny steps" },
                            React.createElement(Steps, { valor: this.state.stepno, value: "1", icon: "folder outline icon", titulo: "Datos Personales", onClick: this.handleState.bind(this, 1) }),
                            React.createElement(Steps, { valor: this.state.stepno, value: "3", icon: "map outline icon", titulo: "Domicilio", onClick: this.handleState.bind(this, 3) })
                        ),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 1 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "persona", label: "Persona", valor: this.state.tipo == 'M' ? 'Moral' : 'Fisica' })
                            ),
                            React.createElement(
                                "div",
                                { className: this.state.tipo == 'F' ? 'step hidden' : '' },
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(InputField, { id: "cia", label: "Compa\xF1ia", mayuscula: "true", valor: this.state.cia }),
                                    React.createElement(
                                        "div",
                                        { className: "two fields" },
                                        React.createElement(InputField, { readOnly: "readOnly", id: "tiposociedad", label: "Tipo de Sociedad", valor: this.state.tiposociedad }),
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
                                React.createElement(InputField, { id: "nombre1", readOnly: "readOnly", label: "Primer Nombre", valor: this.state.nombre1, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "nombre2", readOnly: "readOnly", label: "Segundo Nombre", valor: this.state.nombre2, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "apaterno", readOnly: "readOnly", label: "Apellido Paterno", valor: this.state.apaterno, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "amaterno", readOnly: "readOnly", label: "Apellido Materno", valor: this.state.amaterno, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "two  fields" },
                                React.createElement(InputField, { id: "aliaspf", readOnly: "readOnly", label: "Conocida(o) como", valor: this.state.aliaspf, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(
                                        "div",
                                        { className: "field" },
                                        React.createElement(InputField, { id: "fecha_nac", readOnly: "readOnly", label: "Fec. Nacimiento", valor: this.state.fecha_nac })
                                    ),
                                    React.createElement(InputField, { id: "sexo", readOnly: "readOnly", label: "Sexo", valor: this.state.sexo == 'F' ? 'Femenino' : this.state.sexo == 'M' ? 'Masculino' : '' })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "estadonac", readOnly: "readOnly", label: "Estado de Nacimiento", valor: this.state.estadonac }),
                                React.createElement(InputField, { id: "estadocivil", readOnly: "readOnly", label: "Estado Civil", valor: this.state.estadocivil }),
                                React.createElement(InputField, { id: "escolaridadc", readOnly: "readOnly", label: "Escolaridad", valor: this.state.escolaridadc }),
                                pf
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "curp", readOnly: "readOnly", label: "CURP", valor: this.state.curp, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "folio_ife", readOnly: "readOnly", label: "IFE/INE", valor: this.state.folio_ife, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "otroiden", readOnly: "readOnly", label: "Otra identificaci\xF3n", valor: this.state.otroiden, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(
                                    "div",
                                    { className: "field" },
                                    React.createElement(
                                        "label",
                                        null,
                                        "Email"
                                    ),
                                    React.createElement("input", { name: "email", readOnly: "readOnly", id: "email", type: "text", value: this.state.email, onChange: this.handleInputChange.bind(this) })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "celular", readOnly: "readOnly", label: "Celular", valor: this.state.celular, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "conyuge", readOnly: "readOnly", label: "Nombre del conyuge", valor: this.state.conyuge, onChange: this.handleInputChange.bind(this) })
                            )
                        ),
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 2 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "actividadc", readOnly: "readOnly", label: "Actividad econ\xF3mica", valor: this.state.actividadc }),
                                React.createElement(InputFieldNumber, { id: "patrimonio", readOnly: "readOnly", label: "Monto del patrimonio", valor: this.state.patrimonio, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "experiencia", readOnly: "readOnly", label: "Experiencia del actividad (a\xF1os)", valor: this.state.experiencia, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "teltrabajo", readOnly: "readOnly", label: "Tel\xE9fono del domicilio laboral", valor: this.state.teltrabajo, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputField, { id: "domlaboral", readOnly: "readOnly", label: "Domicilio Laboral", valor: this.state.domlaboral, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "domlabref", readOnly: "readOnly", label: "Referencias del domicilio Laboral", valor: this.state.domlabref, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputFieldNumber, { id: "ingresomen", readOnly: "readOnly", label: "Ingresos mensuales", valor: this.state.ingresomen, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "ingresomenext", readOnly: "readOnly", label: "Ingresos extraordinarios", valor: this.state.ingresomenext, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "egresomen", readOnly: "readOnly", label: "Egresos mensuales", valor: this.state.egresomen, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "egresomenext", readOnly: "readOnly", label: "Egresos extraordinarios", valor: this.state.egresomenext, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputField, { id: "dependientes", readOnly: "readOnly", cols: "four wide", label: "No. de personas que dependen de Usted", valor: this.state.dependientes, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: "ahorro", readOnly: "readOnly", cols: "four wide", label: "Compromiso de ahorro", valor: this.state.ahorro, onChange: this.handleInputChange.bind(this) })
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "get solingdom" },
                    React.createElement(
                        "form",
                        { className: "ui form small formdom", ref: "formdom" },
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 3 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: "two fields hidden" },
                                React.createElement(
                                    "div",
                                    { className: "three wide field" },
                                    React.createElement(Calendar, { name: "fechaalta1", label: "Fecha alta", valor: today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear() })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "direccion1", readOnly: "readOnly", cols: "ten wide", label: "Direcci\xF3n", valor: this.state.direccion1, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "noexterior", readOnly: "readOnly", cols: "two wide", label: "No. Ext.", valor: this.state.noexterior, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "nointerior", readOnly: "readOnly", cols: "two wide", label: "No. Int.", valor: this.state.nointerior, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "direccion2", readOnly: "readOnly", cols: "five wide", label: "Referencia del domicilio", valor: this.state.direccion2, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "estado", readOnly: "readOnly", cols: "five wide", label: "Estado", valor: this.state.estado }),
                                React.createElement(InputField, { id: "municipio", readOnly: "readOnly", cols: "five wide", label: "Municipio", valor: this.state.municipio }),
                                React.createElement(InputField, { id: "colonia", readOnly: "readOnly", cols: "five wide", label: "Colonia", valor: this.state.colonia }),
                                React.createElement(InputField, { id: "cp", readOnly: "readOnly", cols: "five wide", label: "C\xF3digo Postal", valor: this.state.cp })
                            ),
                            React.createElement(
                                "div",
                                { className: "three fields" },
                                React.createElement(InputField, { id: "ciudad", readOnly: "readOnly", label: "Ciudad", valor: this.state.ciudad, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "tiempo", readOnly: "readOnly", label: "Tiempo de radicar en el domicilio actual (a\xF1os)", valor: this.state.tiempo, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "telefono", readOnly: "readOnly", label: "Tel\xE9fono local", valor: this.state.telefono, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "tipoviviendac", readOnly: "readOnly", label: "Tipo de Vivienda", valor: this.state.tipoviviendac }),
                                React.createElement(CheckBox, { titulo: "Agua Potable", name: "aguapot", valor: this.state.aguapot, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(CheckBox, { titulo: "Energia electrica", name: "enerelec", valor: this.state.enerelec, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(CheckBox, { titulo: "Drenaje", name: "drenaje", valor: this.state.drenaje, onChange: this.handleInputChange.bind(this) })
                            )
                        )
                    )
                ),
                React.createElement(
                    "div",
                    { className: "get solingben" },
                    React.createElement(
                        "form",
                        { className: "ui form small formben", ref: "formben" },
                        React.createElement(
                            "div",
                            { className: this.state.stepno === 4 ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                "div",
                                { className: "two fields hidden" },
                                React.createElement(
                                    "div",
                                    { className: "three wide field" },
                                    React.createElement(Calendar, { name: "fechaalta1", label: "Fecha alta", valor: today.getDate() + '/' + (today.getMonth() + 1) + '/' + today.getFullYear() })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "nombre1_ben", readOnly: "readOnly", label: "Primer Nombre", valor: this.state.nombre1_ben, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "nombre2_ben", readOnly: "readOnly", label: "Segundo Nombre", valor: this.state.nombre2_ben, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "apaterno_ben", readOnly: "readOnly", label: "Apellido Paterno", valor: this.state.apaterno_ben, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: "amaterno_ben", readOnly: "readOnly", label: "Apellido Materno", valor: this.state.amaterno_ben, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                "div",
                                { className: "two fields" },
                                React.createElement(InputField, { id: "aliaspf_ben", readOnly: "readOnly", label: "Conocida(o) como", valor: this.state.aliaspf_ben, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(InputField, { id: "rfc_ben", readOnly: "readOnly", label: "RFC", valor: this.state.rfc_ben, onChange: this.handleInputChange.bind(this) }),
                                    React.createElement(InputField, { id: "telefono_ben", readOnly: "readOnly", cols: "s3", label: "Celular", valor: this.state.telefono_ben, onChange: this.handleInputChange.bind(this) })
                                )
                            ),
                            React.createElement(
                                "div",
                                { className: "four fields" },
                                React.createElement(InputField, { id: "idparentescoc", readOnly: "readOnly", cols: "s3", label: "Parentesco", valor: this.state.parentescoc }),
                                React.createElement(
                                    "div",
                                    { className: "two fields" },
                                    React.createElement(InputField, { id: "porcentaje", readOnly: "readOnly", cols: "s3", label: "% participaci\xF3n", valor: this.state.porcentaje, onChange: this.handleInputChange.bind(this) })
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

$('.ui.search').search({
    type: 'category',
    minCharacters: 6,
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
                    description: 'Sol ' + item.idpersona + ' - Ac ' + item.idacreditado + ' - ' + item.grupo_nombre
                });
            });
            return response;
        }
    }
});