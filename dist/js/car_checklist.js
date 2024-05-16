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

var CheckListaRow = function (_React$Component2) {
    _inherits(CheckListaRow, _React$Component2);

    function CheckListaRow() {
        _classCallCheck(this, CheckListaRow);

        return _possibleConstructorReturn(this, (CheckListaRow.__proto__ || Object.getPrototypeOf(CheckListaRow)).apply(this, arguments));
    }

    _createClass(CheckListaRow, [{
        key: "render",
        value: function render() {
            var divStyle = {
                padding: '1em 1em 0.3em'
            };
            return React.createElement(
                "div",
                { className: "ui vertical segment aligned", style: divStyle },
                React.createElement(
                    "div",
                    { className: "field" },
                    this.props.categoria
                )
            );
        }
    }]);

    return CheckListaRow;
}(React.Component);

var DocumentoRow = function (_React$Component3) {
    _inherits(DocumentoRow, _React$Component3);

    function DocumentoRow(props) {
        var _this4$state;

        _classCallCheck(this, DocumentoRow);

        var _this4 = _possibleConstructorReturn(this, (DocumentoRow.__proto__ || Object.getPrototypeOf(DocumentoRow)).call(this, props));

        var nombre = "iddoc_" + _this4.props.valor.iddocumento;
        var valor = false;
        if (_this4.props.valor.fecha != null) {
            valor = true;
        }
        _this4.state = (_this4$state = {}, _defineProperty(_this4$state, nombre, valor), _defineProperty(_this4$state, "activo", _this4.props.valor.checked), _this4$state);

        return _this4;
    }

    _createClass(DocumentoRow, [{
        key: "componentDidMount",
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myCheck)).on('onChange', this.handleClick);
            var nombre = "iddoc_" + this.props.valor.iddocumento;
            var valor = false;
            if (this.props.valor.fecha != null) {
                valor = true;
            }

            /*
                    $('.get.soling form')
                        .form('set values', { [nombre]: valor })
                    ;
            */
        }
    }, {
        key: "handleClick",
        value: function handleClick(e) {
            if (this.state.activo == 1) {
                this.setState({ activo: 0 });
            } else {
                this.setState({ activo: 1 });
            }
        }
    }, {
        key: "render",
        value: function render() {
            var nombre = "iddoc_" + this.props.valor.iddocumento;
            var valor = false;
            if (this.state.activo == 1) {
                valor = true;
            }
            var checked = valor == true ? 'ui checkbox checked' : 'ui checkbox';
            var inputc = null;
            if (valor == true) {
                inputc = React.createElement("input", { type: "checkbox", name: nombre, id: nombre, checked: "checked", onChange: this.handleClick.bind(this) });
            } else {
                inputc = React.createElement("input", { type: "checkbox", name: nombre, id: nombre, onChange: this.handleClick.bind(this) });
            }

            return React.createElement(
                "div",
                { className: "row" },
                React.createElement(
                    "div",
                    { className: checked },
                    inputc,
                    React.createElement(
                        "label",
                        null,
                        this.props.valor.descripciond
                    )
                )
            );
        }
    }]);

    return DocumentoRow;
}(React.Component);

var SelectOption = function (_React$Component4) {
    _inherits(SelectOption, _React$Component4);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this5 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this5.state = {
            value: ""
        };
        return _this5;
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

var CheckBox = function (_React$Component5) {
    _inherits(CheckBox, _React$Component5);

    function CheckBox(props) {
        _classCallCheck(this, CheckBox);

        return _possibleConstructorReturn(this, (CheckBox.__proto__ || Object.getPrototypeOf(CheckBox)).call(this, props));
    }

    _createClass(CheckBox, [{
        key: "render",
        value: function render() {
            var rows = [];
            var lastLista = null;
            this.props.valores.forEach(function (valorcheck) {
                if (valorcheck.descripcionc !== lastLista) {
                    rows.push(React.createElement(CheckListaRow, { categoria: valorcheck.descripcionc, key: valorcheck.descripcionc }));
                }
                rows.push(React.createElement(DocumentoRow, { valor: valorcheck, key: valorcheck.descripciond }));
                lastLista = valorcheck.descripcionc;
            });

            return React.createElement(
                "div",
                { className: "ui segment" },
                rows
            );
        }
    }]);

    return CheckBox;
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
                    React.createElement("input", { className: may, id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, value: this.props.valor, type: "text", placeholder: this.props.placeholder, onChange: function onChange(event) {
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
            idacreditado: '', idpagare: 0, catchecklist: [], catPagares: [], idcredito: '', idchecklist: '',
            boton: 'Enviar', message: "", statusmessage: 'ui floating hidden message',
            icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon',
            activo: 0
        };
        return _this10;
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

            if (name == "idpagare" && value != "") {
                var idacreditado = this.state.idacreditado;
                var idpagare = value;
                var forma = this;
                var object = {
                    url: base_url + 'api/CarteraV1/credito_checklist/' + idacreditado + '/' + idpagare,
                    type: 'GET',
                    dataType: 'json'
                    //            window.clearTimeout(this.timeout);            
                };ajax(object).then(function resolve(response) {
                    forma.setState({
                        //                    message: response.message,
                        //                  statusmessage: 'ui positive floating message ',
                        catchecklist: response.checklist,
                        idcredito: response.socio[0].idcredito,
                        idchecklist: response.socio[0].idchecklist,
                        nombre: response.socio[0].nombre,
                        icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
                    });
                    //                forma.autoReset();            
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        message: response.message,
                        statusmessage: 'ui negative floating message',
                        catchecklist: [],
                        icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
                    });
                    forma.autoReset(5000);
                });
            }
        }
    }, {
        key: "handleFind",
        value: function handleFind(event, value, name) {
            if (name == "idacreditado") {
                this.setState({ idacreditado: value,
                    icons1: 'spinner circular inverted loading icon' });
                var idacreditado = value;

                var forma = this;
                var object = {
                    //                url: base_url + 'api/CarteraV1/getcreditos_clist/'+idacreditado,
                    url: base_url + 'api/CarteraV1/getcreditos_clist/' + idacreditado,
                    type: 'GET',
                    dataType: 'json'
                    //            window.clearTimeout(this.timeout);            
                };ajax(object).then(function resolve(response) {
                    forma.setState({
                        //                    message: response.message,
                        //                  statusmessage: 'ui positive floating message ',
                        catPagares: response.check,
                        nombre: response.acre[0].nombre,
                        icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon',
                        activo: 1
                    });
                    //                forma.autoReset();            
                }, function reject(reason) {
                    var response = validaError(reason);
                    forma.setState({
                        message: response.message,
                        statusmessage: 'ui negative floating message',
                        catPagares: [], idpagare: 0, idactivo: 0,
                        icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon'
                    });
                    forma.autoReset(3000);
                });
            }
        }
    }, {
        key: "handleButton",
        value: function handleButton(e, value) {
            if (e == 0) {
                this.setState({
                    activo: 0, idacreditado: '', idpagare: '', catPagares: [], nombre: '',
                    catchecklist: []
                });
                var $form = $('.get.soling form'),
                    Folio = $form.form('set values', { idpagare: '0'
                });
            } else {

                if (this.state.idpagare != 0) {
                    // let d = new Date();
                    var id = this.state.idpagare;
                    var a = document.createElement('a');
                    a.href = base_url + 'api/ReportD1/pdf_checklist/' + id;
                    a.target = '_blank';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);

                    /*
                                 $.ajax({
                                        url: base_url + 'api/ReportV1/solcreditopdf/'+id,
                                        type: 'GET',
                                        dataType: 'text',
                                        success:function(response) {
                                            var blob=new Blob([response], { type: 'application/pdf' });
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
        }
    }, {
        key: "handleSubmit",
        value: function handleSubmit(event) {
            event.preventDefault();
            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    idpagare: {
                        identifier: 'idpagare',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el pagaré'
                        }]
                    },
                    idacreditado: {
                        identifier: 'idacreditado',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el no. de acreditado '
                        }, {
                            type: 'integer[1..99999]',
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
                var tipo = 'PUT';
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/CarteraV1/credito_checklist',
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            var $form = $('.get.soling form'),
                                Folio = $form.form('set values', { idpagare: '0' });

                            forma.setState({
                                idpersona: response.insert_id,
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                boton: 'Enviar', idpagare: 0,
                                catchecklist: [], catPagares: []
                            });
                            forma.autoReset(2000);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            forma.autoReset(5000);
                        });
                    }
                }).modal('show');
            } else {
                this.setState({
                    message: 'Datos incompletos!',
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset(3000);
            }
        }
    }, {
        key: "autoReset",
        value: function autoReset(tiempo) {
            var self = this;
            this.timeout = window.setTimeout(function () {
                self.setState({ message: '', statusmessage: 'ui message hidden' });
            }, tiempo);
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
                    { className: "row" },
                    React.createElement(
                        "h3",
                        { className: "ui rojo header" },
                        "CheckList (Documentaci\xF3n)"
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
                            { className: "fields" },
                            React.createElement(InputFieldFind, { icons: this.state.icons1, id: "idacreditado", readOnly: this.state.activo == 1 || this.state.activo == 2 ? 'readOnly' : '', cols: "three wide", name: "idacreditado", valor: this.state.idacreditado, label: "Acreditado", placeholder: "Buscar", onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                            React.createElement(SelectOption, { name: "idpagare", cols: "three wide", id: "idpagare", label: "No. Pagar\xE9", valor: this.state.idpagare, valores: this.state.catPagares, onChange: this.handleInputChange.bind(this) }),
                            React.createElement(InputField, { id: "nombre", cols: "ten wide", label: "Nombre", readOnly: "readOnly", valor: this.state.nombre, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            "div",
                            { className: "two fields" },
                            React.createElement("input", { type: "hidden", name: "idcredito", value: this.state.idcredito }),
                            React.createElement("input", { type: "hidden", name: "idchecklist", value: this.state.idchecklist })
                        ),
                        React.createElement(CheckBox, { valores: this.state.catchecklist }),
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