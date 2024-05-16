'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var FindAcreditada = function (_React$Component) {
    _inherits(FindAcreditada, _React$Component);

    function FindAcreditada(props) {
        _classCallCheck(this, FindAcreditada);

        var _this = _possibleConstructorReturn(this, (FindAcreditada.__proto__ || Object.getPrototypeOf(FindAcreditada)).call(this, props));

        _this.state = {
            idacredi: '',
            idapersona: 0,
            nombrea: '',
            icons1: 'inverted circular search link icon'

        };
        return _this;
    }

    _createClass(FindAcreditada, [{
        key: 'handleButton',
        value: function handleButton(e, opc) {
            var link = '';
            var id = this.state.idacredi;
            var idpersona = this.state.idpersona;
            if (id != '' && idpersona != 0) {
                link = 'ReportV1/solcreditopdf/' + idpersona;
            } else {
                return;
            }

            var a = document.createElement('a');
            a.href = base_url + ('api/' + link);
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: 'handleInputChange',
        value: function handleInputChange(e) {
            var evento = e.target;
            var value = evento.value;
            var name = evento.name;
            this.setState(_defineProperty({}, name, value));
        }
    }, {
        key: 'handleFind',
        value: function handleFind(e, value, name) {
            var idacreditado = 0;
            var page = "";
            this.setState(_defineProperty({}, name, value));
            page = 'findAcre/' + value;

            if (page != "") {
                var icons = 'spinner circular inverted blue loading icon';
                var _forma = this;
                var object = {
                    url: base_url + ('api/carteraV1/' + page),
                    type: 'GET',
                    dataType: 'json'
                };
                this.setState({ icons1: icons });
                ajax(object).then(function resolve(response) {
                    var _forma$setState;

                    var named = "nombrea";
                    var idacre = "idpersona";
                    _forma.setState((_forma$setState = {}, _defineProperty(_forma$setState, named, response.result[0].nombre), _defineProperty(_forma$setState, idacre, response.result[0].idpersona), _defineProperty(_forma$setState, 'message', ''), _defineProperty(_forma$setState, 'icons1', 'inverted circular search link icon'), _forma$setState));
                }, function reject(reason) {
                    var response = validaError(reason);
                    _forma.setState({
                        message: response.message,
                        statusmessage: 'ui negative floating message'
                    });
                    _forma.setState({
                        nombrea: ''
                    });
                    _forma.setState({
                        icons1: 'inverted circular search link icon'
                    });
                });
            }
        }
    }, {
        key: 'render',
        value: function render() {

            return React.createElement(
                'div',
                { className: 'ui form box-emergente' },
                React.createElement(
                    'div',
                    { className: 'ui segment vertical' },
                    React.createElement(
                        'div',
                        { className: 'ui secondary menu' },
                        React.createElement(
                            'div',
                            { className: 'ui basic icon buttons' },
                            React.createElement(
                                'button',
                                { className: 'ui button', 'data-tooltip': 'Datos Generales' },
                                React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 0) })
                            )
                        )
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'two fields' },
                    React.createElement(InputFieldFind, { icons: this.state.icons1, id: 'idacredi', name: 'idacredi', valor: this.state.idacredi, cols: 'three wide', label: 'Acreditado', placeholder: 'Buscar', onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                    React.createElement(InputField, { id: 'nombrea', label: 'Nombre', cols: 'eleven wide', readOnly: 'readOnly', valor: this.state.nombrea, onChange: this.handleInputChange.bind(this) })
                )
            );
        }
    }]);

    return FindAcreditada;
}(React.Component);

var MultiSelect = function (_React$Component2) {
    _inherits(MultiSelect, _React$Component2);

    function MultiSelect(props) {
        _classCallCheck(this, MultiSelect);

        return _possibleConstructorReturn(this, (MultiSelect.__proto__ || Object.getPrototypeOf(MultiSelect)).call(this, props));
    }

    _createClass(MultiSelect, [{
        key: 'render',
        value: function render() {
            var listItems = this.props.valores.map(function (valor) {
                return React.createElement(
                    'option',
                    { value: valor.value },
                    valor.name
                );
            });

            return React.createElement(
                'div',
                { className: 'ten wide field' },
                React.createElement(
                    'label',
                    null,
                    this.props.label
                ),
                React.createElement(
                    'select',
                    { name: this.props.name, className: 'ui fluid search dropdown selection multiple', multiple: '' },
                    React.createElement(
                        'option',
                        { value: '' },
                        'Seleccione'
                    ),
                    listItems
                )
            );
        }
    }]);

    return MultiSelect;
}(React.Component);

var InputField = function (_React$Component3) {
    _inherits(InputField, _React$Component3);

    function InputField(props) {
        _classCallCheck(this, InputField);

        return _possibleConstructorReturn(this, (InputField.__proto__ || Object.getPrototypeOf(InputField)).call(this, props));
    }

    _createClass(InputField, [{
        key: 'render',
        value: function render() {
            var _this4 = this;

            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            var may = this.props.mayuscula == "true" ? 'mayuscula' : '';
            return React.createElement(
                'div',
                { className: cols },
                React.createElement(
                    'label',
                    null,
                    this.props.label
                ),
                React.createElement(
                    'div',
                    { className: 'ui icon input' },
                    React.createElement('input', { className: may, id: this.props.id, readOnly: this.props.readOnly, name: this.props.id, type: 'text', value: this.props.valor, placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this4.props.onChange(event);
                        } })
                )
            );
        }
    }]);

    return InputField;
}(React.Component);

var InputCheck = function (_React$Component4) {
    _inherits(InputCheck, _React$Component4);

    function InputCheck(props) {
        _classCallCheck(this, InputCheck);

        var _this5 = _possibleConstructorReturn(this, (InputCheck.__proto__ || Object.getPrototypeOf(InputCheck)).call(this, props));

        _this5.handleClick = _this5.handleClick.bind(_this5);
        return _this5;
    }

    _createClass(InputCheck, [{
        key: 'handleClick',
        value: function handleClick(e) {
            this.props.onClick(this.props.id);
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'ui left floated compact segment' },
                React.createElement(
                    'div',
                    { className: 'ui fitted checkbox' },
                    React.createElement('input', { name: this.props.id, type: 'checkbox', value: this.props.valor }),
                    React.createElement('label', { name: this.props.id, onClick: this.handleClick })
                )
            );
        }
    }]);

    return InputCheck;
}(React.Component);

var InputFieldFind = function (_React$Component5) {
    _inherits(InputFieldFind, _React$Component5);

    function InputFieldFind(props) {
        _classCallCheck(this, InputFieldFind);

        var _this6 = _possibleConstructorReturn(this, (InputFieldFind.__proto__ || Object.getPrototypeOf(InputFieldFind)).call(this, props));

        _this6.state = {
            value: ''
        };
        return _this6;
    }

    _createClass(InputFieldFind, [{
        key: 'render',
        value: function render() {
            var _this7 = this;

            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            return React.createElement(
                'div',
                { className: cols },
                React.createElement(
                    'label',
                    null,
                    this.props.label
                ),
                React.createElement(
                    'div',
                    { className: 'ui icon input' },
                    React.createElement('input', { id: this.props.id, 'data-idcre': this.props.dataid, name: this.props.id, value: this.props.valor, type: 'text', placeholder: this.props.placeholder, onChange: function onChange(event) {
                            return _this7.props.onChange(event);
                        } }),
                    React.createElement('i', { className: this.props.icons, onClick: function onClick(event) {
                            return _this7.props.onClick(event, _this7.props.valor, _this7.props.id);
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
        key: 'render',
        value: function render() {
            var _this9 = this;

            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field";
            return React.createElement(
                'div',
                { className: cols },
                React.createElement(
                    'label',
                    { htmlFor: this.props.id },
                    this.props.label
                ),
                React.createElement(
                    'div',
                    { className: 'ui labeled input' },
                    React.createElement(
                        'div',
                        { className: 'ui label' },
                        '$'
                    ),
                    React.createElement('input', { className: 'text-right', type: 'text', id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, value: this.props.valor, onChange: function onChange(event) {
                            return _this9.props.onChange(event);
                        }, onBlur: function onBlur(event) {
                            return _this9.props.onBlur(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
}(React.Component);

var InputFieldNum = function (_React$Component7) {
    _inherits(InputFieldNum, _React$Component7);

    function InputFieldNum(props) {
        _classCallCheck(this, InputFieldNum);

        return _possibleConstructorReturn(this, (InputFieldNum.__proto__ || Object.getPrototypeOf(InputFieldNum)).call(this, props));
    }

    _createClass(InputFieldNum, [{
        key: 'render',
        value: function render() {
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field";
            return React.createElement(
                'div',
                { className: cols },
                React.createElement(
                    'label',
                    null,
                    this.props.label
                ),
                React.createElement(
                    'div',
                    { className: 'ui labeled input' },
                    React.createElement(
                        'div',
                        { className: 'ui label' },
                        '$'
                    ),
                    React.createElement('input', { type: 'text', id: this.props.id, name: this.props.id, value: this.props.valor })
                )
            );
        }
    }]);

    return InputFieldNum;
}(React.Component);

var Nota = function (_React$Component8) {
    _inherits(Nota, _React$Component8);

    function Nota(props) {
        _classCallCheck(this, Nota);

        return _possibleConstructorReturn(this, (Nota.__proto__ || Object.getPrototypeOf(Nota)).call(this, props));
    }

    _createClass(Nota, [{
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: this.props.visible === true ? "" : "hidden" },
                React.createElement(
                    'div',
                    { className: 'center aligned content ' },
                    React.createElement(
                        'p',
                        { className: 'ui rojo' },
                        'Existen cr\xE9ditos por Entregar a la  Acreditada: ',
                        this.props.notavis_lista
                    )
                )
            );
        }
    }]);

    return Nota;
}(React.Component);

var Mensaje = function (_React$Component9) {
    _inherits(Mensaje, _React$Component9);

    function Mensaje(props) {
        _classCallCheck(this, Mensaje);

        var _this12 = _possibleConstructorReturn(this, (Mensaje.__proto__ || Object.getPrototypeOf(Mensaje)).call(this, props));

        _this12.state = {
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?"
        };
        return _this12;
    }

    _createClass(Mensaje, [{
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'ui mini test modal scrolling transition hidden' },
                React.createElement(
                    'div',
                    { className: 'ui icon header' },
                    React.createElement('i', { className: this.props.icon }),
                    this.props.titulo
                ),
                React.createElement(
                    'div',
                    { className: 'center aligned content ' },
                    React.createElement(
                        'p',
                        null,
                        this.props.pregunta
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'actions' },
                    React.createElement(
                        'div',
                        { className: 'ui red cancel basic button' },
                        React.createElement('i', { className: 'remove icon' }),
                        ' No '
                    ),
                    React.createElement(
                        'div',
                        { className: 'ui green ok basic button' },
                        React.createElement('i', { className: 'checkmark icon' }),
                        ' Si '
                    )
                )
            );
        }
    }]);

    return Mensaje;
}(React.Component);

var Calendar = function (_React$Component10) {
    _inherits(Calendar, _React$Component10);

    function Calendar(props) {
        _classCallCheck(this, Calendar);

        return _possibleConstructorReturn(this, (Calendar.__proto__ || Object.getPrototypeOf(Calendar)).call(this, props));
    }

    _createClass(Calendar, [{
        key: 'changeData',
        value: function changeData(e) {
            console.log('data calendar');
        }
    }, {
        key: 'render',
        value: function render() {
            var cols = "ui calendar " + (this.props.visible == false ? " hidden " : "");
            return React.createElement(
                'div',
                { className: cols, id: this.props.name },
                React.createElement(
                    'div',
                    { className: 'field' },
                    React.createElement(
                        'label',
                        null,
                        this.props.label
                    ),
                    React.createElement(
                        'div',
                        { className: 'ui input left icon' },
                        React.createElement('i', { className: 'calendar icon' }),
                        React.createElement('input', { ref: 'myCalen', type: 'text', readOnly: this.props.readOnly, name: this.props.name, id: this.props.name, placeholder: 'Fecha', onChange: this.changeData.bind(this) })
                    )
                )
            );
        }
    }]);

    return Calendar;
}(React.Component);

var SelectDropDown = function (_React$Component11) {
    _inherits(SelectDropDown, _React$Component11);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this14 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this14.state = {
            value: ""
        };
        _this14.handleSelectChange = _this14.handleSelectChange.bind(_this14);
        return _this14;
    }

    _createClass(SelectDropDown, [{
        key: 'handleSelectChange',
        value: function handleSelectChange(event) {
            this.props.onChange(event);
            this.setState({ value: event.target.value });
        }
    }, {
        key: 'componentDidMount',
        value: function componentDidMount() {
            $(ReactDOM.findDOMNode(this.refs.myDrop)).on('change', this.handleSelectChange);
        }
    }, {
        key: 'render',
        value: function render() {
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            var listItems = this.props.valores.map(function (valor) {
                return React.createElement(
                    'div',
                    { className: 'item', 'data-value': valor.value },
                    valor.name
                );
            });

            return React.createElement(
                'div',
                { className: cols },
                React.createElement(
                    'label',
                    null,
                    this.props.label
                ),
                React.createElement(
                    'div',
                    { className: 'ui fluid search selection dropdown' },
                    React.createElement('input', { type: 'hidden', ref: 'myDrop', value: this.value, name: this.props.id, onChange: this.handleSelectChange }),
                    React.createElement('i', { className: 'dropdown icon' }),
                    React.createElement(
                        'div',
                        { className: 'default text' },
                        'Seleccione'
                    ),
                    React.createElement(
                        'div',
                        { className: 'menu' },
                        listItems
                    )
                )
            );
        }
    }]);

    return SelectDropDown;
}(React.Component);

var SelectOption = function (_React$Component12) {
    _inherits(SelectOption, _React$Component12);

    function SelectOption(props) {
        _classCallCheck(this, SelectOption);

        var _this15 = _possibleConstructorReturn(this, (SelectOption.__proto__ || Object.getPrototypeOf(SelectOption)).call(this, props));

        _this15.state = {
            value: ""
        };
        return _this15;
    }

    _createClass(SelectOption, [{
        key: 'handleSelectChange',
        value: function handleSelectChange(event) {
            this.props.onChange(event);
        }
    }, {
        key: 'componentDidMount',
        value: function componentDidMount() {
            //        $(ReactDOM.findDOMNode(this.refs.myCombo)).on('change',this.handleSelectChange.bind(this));
        }
    }, {
        key: 'render',
        value: function render() {
            var cols = (this.props.cols !== undefined ? this.props.cols : "") + " field ";
            var listItems = this.props.valores.map(function (valor) {
                return React.createElement(
                    'option',
                    { key: valor.name + valor.value, value: valor.value },
                    valor.name
                );
            });
            return React.createElement(
                'div',
                { className: cols },
                React.createElement(
                    'label',
                    null,
                    this.props.label
                ),
                React.createElement(
                    'select',
                    { className: 'ui fluid dropdown', ref: 'myCombo', name: this.props.id, id: this.props.id, onChange: this.handleSelectChange.bind(this) },
                    React.createElement(
                        'option',
                        { key: '0', value: this.props.valor },
                        'Seleccione'
                    ),
                    listItems
                )
            );
        }
    }]);

    return SelectOption;
}(React.Component);

function Lista(props) {
    var cadenas = props.enca;
    var contador = 0;
    var thStyle = {
        display: 'none'
    };
    var thStyled = {
        display: 'display'
    };

    var listItems = cadenas.map(function (encabezado) {
        return React.createElement(
            'th',
            { key: contador++, style: encabezado == 'id' || encabezado == 'Pagaré' || encabezado == 'ahorroc' || encabezado == 'ahorrov' || encabezado == 'SubTotal' || encabezado == 'idcredito' || encabezado == 'idahorro' ? thStyle : thStyled },
            encabezado
        );
    });
    return React.createElement(
        'tr',
        null,
        listItems
    );
}

var RecordDetalle = function (_React$Component13) {
    _inherits(RecordDetalle, _React$Component13);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this16 = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this16.state = {
            idcredito: _this16.props.registro.idcredito, numero: _this16.props.registro.numero,
            nopagos: _this16.props.registro.pagos_col, ahocorriente: _this16.props.registro.ahorro_vol,
            //nopagos: 1, ahocorriente:0,
            capital: _this16.props.registro.capital,
            interes: _this16.props.registro.interes, iva: _this16.props.registro.iva,
            importepago: _this16.props.registro.importepago,
            ahocomprome: _this16.props.registro.ahocomprome, ajuste: _this16.props.registro.ajuste,
            total: _this16.props.registro.total,
            activo: 1
        };
        return _this16;
    }

    _createClass(RecordDetalle, [{
        key: 'handleInputChange',
        value: function handleInputChange(e) {
            this.setState({
                nopagos: e.target.value
            });
        }
    }, {
        key: 'handleChange',
        value: function handleChange(e) {
            var _this17 = this;

            var name = e.target.name;
            if (name == "ajuste[]") {

                /*             console.log(this.state.numero)    ;
                            console.log(this.state.nopagos);
                 */
                var ajuste = isNaN(e.target.value) || e.target.value == "" ? "0" : e.target.value;
                var importe = this.state.importepago;
                var ahorro = this.state.ahocomprome;
                var corriente = numeral(this.state.ahocorriente).format('0.00');;

                this.setState({
                    ajuste: ajuste,
                    total: parseFloat(importe) + parseFloat(ahorro) + parseFloat(corriente) + parseFloat(ajuste)
                });

                this.setState(function (prevState, props) {
                    var anterior = numeral(prevState.total).format('0.00');
                    var valoradd = numeral(_this17.state.total).format('0.00');
                    var valtext = numeral($('#pagototal').val()).format('0.00');
                    var final = parseFloat(valtext) - parseFloat(valoradd) + parseFloat(anterior);
                    _this17.props.onChange(e, final);
                });
            } else if (name == "ahocorriente[]") {

                var _importe = this.state.importepago;
                var _ahorro = this.state.ahocomprome;
                var _ajuste = this.state.ajuste;

                if (esquema == 'fin.') {
                    if (this.state.nopagos == this.state.nopagos.length) {
                        _importe = parseFloat(this.state.capital) + parseFloat(this.state.interes) + parseFloat(this.state.iva);
                    }
                }

                var _corriente = isNaN(e.target.value) || e.target.value == "" ? "0" : e.target.value;
                this.setState({
                    ahocorriente: _corriente,
                    total: parseFloat(_importe) + parseFloat(_ahorro) + parseFloat(_corriente) + parseFloat(_ajuste)
                });

                this.setState(function (prevState, props) {
                    var anterior = numeral(prevState.total).format('0.00');
                    var valoradd = numeral(_this17.state.total).format('0.00');
                    var valtext = numeral($('#pagototal').val()).format('0.00');
                    var final = parseFloat(valtext) - parseFloat(valoradd) + parseFloat(anterior);
                    _this17.props.onChange(e, final);
                });
            } else {

                var no = e.target.value;
                if (no == 1 || esquema == 'ban.') {
                    var ahorrocorriente = 0;
                    if (this.state.ahocorriente != null) {
                        ahorrocorriente = this.state.ahocorriente;
                    }
                    var multiplica = no;
                    if (esquema != 'ban.') {
                        multiplica = 1;
                    }

                    var capital = parseFloat(this.props.registro.capital) * multiplica;
                    var interes = parseFloat(this.props.registro.interes) * multiplica;
                    var iva = parseFloat(this.props.registro.iva) * multiplica;

                    var abonocomprome = esquema == 'ban.' ? 0 : parseFloat(this.props.registro.ahocomprome) * multiplica;
                    if (esquema == 'fin.') {
                        if (this.state.numero == 1) {
                            capital = parseFloat(this.props.registro.capital) + parseFloat(this.props.registro.interes) + parseFloat(this.props.registro.iva);
                            interes = 0;
                            iva = 0;
                        }
                    }

                    this.setState({
                        nopagos: no, capital: capital,
                        interes: interes, iva: iva,
                        importepago: parseFloat(this.props.registro.importepago) * multiplica,
                        ahocomprome: parseFloat(this.props.registro.ahocomprome) * multiplica,
                        ajuste: this.props.registro.ajuste,
                        total: parseFloat(this.props.registro.importepago) * multiplica + abonocomprome + parseFloat(this.props.registro.ajuste) + parseFloat(ahorrocorriente)
                    });
                    this.setState(function (prevState, props) {
                        var anterior = numeral(prevState.total).format('0.00');
                        var valoradd = numeral(_this17.state.total).format('0.00');
                        var valtext = numeral($('#pagototal').val()).format('0.00');
                        var final = parseFloat(valtext) - parseFloat(valoradd) + parseFloat(anterior);
                        _this17.props.onChange(e, final);
                    });
                } else {
                    var _forma2 = this;
                    var idcredito = this.state.idcredito;
                    var tot = e.target.length;

                    var ultimo = false;

                    var primero = false;
                    if (parseFloat(this.state.numero) + parseFloat(no) == parseFloat(this.state.numero) + tot) {
                        ultimo = true;
                    }
                    if (this.state.numero == 1) {
                        primero = true;
                    }
                    var data = { 'primero': primero, 'ultimo': ultimo };

                    var numero = parseFloat(this.state.numero) + parseFloat(no);
                    var object = {
                        url: base_url + ('api/CarteraV1/amortizaMas/' + idcredito + '/' + numero),
                        type: 'GET',
                        dataType: 'json',
                        data: data
                    };
                    ajax(object).then(function resolve(response) {
                        var anterior = _forma2.state.total;
                        _forma2.setState({
                            nopagos: no, capital: response.result[0].capital,
                            interes: response.result[0].interes, iva: response.result[0].iva,
                            importepago: response.result[0].importepago,
                            ahocomprome: response.result[0].ahocomprome, ajuste: response.result[0].ajuste,
                            total: parseFloat(response.result[0].importepago) + parseFloat(response.result[0].ahocomprome) + parseFloat(response.result[0].ajuste) + parseFloat(_forma2.state.ahocorriente === null ? 0 : _forma2.state.ahocorriente)
                        });
                        _forma2.setState(function (prevState, props) {
                            //                    let anterior = numeral(prevState.total).format('0.00');
                            var valoradd = numeral(_forma2.state.total).format('0.00');
                            var valtext = numeral($('#pagototal').val()).format('0.00');
                            var final = parseFloat(valtext) - parseFloat(anterior) + parseFloat(valoradd);
                            _forma2.props.onChange(e, final);
                        });
                    }, function reject(reason) {
                        var response = validaError(reason);
                        _forma2.setState({
                            csrf: response.newtoken,
                            message: response.message,
                            statusmessage: 'ui negative floating message'
                        });
                        _forma2.autoReset();
                    });
                }
            }
        }
    }, {
        key: 'handleClickCheck',
        value: function handleClickCheck(e) {
            var _this18 = this;

            if (this.state.activo == 1) {
                this.setState({ activo: 0 });
            } else {
                this.setState({ activo: 1 });
            }

            this.setState(function (prevState, props) {
                var valoradd = numeral(_this18.state.total).format('0.00');
                var valtext = numeral($('#pagototal').val()).format('0.00');
                var final = parseFloat(valtext) - (_this18.state.activo == 1 ? parseFloat(valoradd) : parseFloat(valoradd * -1));
                _this18.props.onChange(e, final);
            });
        }
    }, {
        key: 'render',
        value: function render() {
            var total = numeral(this.state.total).format('0,0.00');
            var capital = numeral(this.state.capital).format('0,0.00');
            var interes = numeral(this.state.interes).format('0,0.00');
            var iva = numeral(this.state.iva).format('0,0.00');
            var importepago = numeral(this.state.importepago).format('0,0.00');
            var ahocomprome = numeral(this.state.ahocomprome).format('0,0.00');
            //        const ajuste = numeral(this.state.ajuste).format('0,0.00');
            var ajuste1 = numeral(this.state.ajuste).format('0.00');
            var ajuste = this.state.ajuste;
            var nopagos = [];
            var datos = this.props.registro.nopagos;
            datos.forEach(function (record) {
                nopagos.push(React.createElement(
                    'option',
                    { value: record.value },
                    record.name
                ));
            });

            var thStyle = {
                display: 'none'
            };
            var ajustetd = null;
            var ahorrotd = null;
            var ivatd = null;
            if (esquema != 'ban.') {
                ivatd = React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', type: 'text', readOnly: 'readOnly', id: 'iva[]', name: 'iva[]', value: iva }),
                    ' '
                );
                ahorrotd = React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', type: 'text', id: 'ahocorriente[]', name: 'ahocorriente[]', value: this.state.ahocorriente, onChange: this.handleChange.bind(this) }),
                    ' '
                );
                ajustetd = React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', type: 'text', id: 'ajuste[]', name: 'ajuste[]', value: ajuste, onChange: this.handleChange.bind(this) }),
                    ' '
                );
            }
            var valor = false;
            if (this.state.activo == 1) {
                valor = true;
            }

            var checked = valor == true ? 'ui checkbox checked' : 'ui checkbox';
            var checkPago = null;
            if (valor == true) {
                checkPago = React.createElement('input', { type: 'checkbox', name: 'chkpago[]', id: 'chkpago[]', checked: 'checked', onChange: this.handleClickCheck.bind(this) });
            } else {
                checkPago = React.createElement('input', { type: 'checkbox', name: 'chkpago[]', id: 'chkpago[]', onChange: this.handleClickCheck.bind(this) });
            }

            return React.createElement(
                'tr',
                null,
                React.createElement(
                    'td',
                    null,
                    checkPago,
                    ' '
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement('input', { type: 'text', className: 'styleNo table-input', id: 'idacreditado[]', name: 'idacreditado[]', value: this.props.registro.idacreditado }),
                    ' '
                ),
                React.createElement(
                    'td',
                    { style: thStyle },
                    React.createElement('input', { type: 'text', className: 'styleNo', id: 'numero_cuentac[]', name: 'numero_cuentac[]', value: this.props.registro.numero_cuentac }),
                    ' '
                ),
                React.createElement(
                    'td',
                    { style: thStyle },
                    React.createElement('input', { type: 'text', className: 'styleNo', id: 'numero_cuentav[]', name: 'numero_cuentav[]', value: this.props.registro.numero_cuentav }),
                    ' '
                ),
                React.createElement(
                    'td',
                    { style: thStyle },
                    React.createElement('input', { type: 'text', className: 'styleNo', id: 'idcredito[]', name: 'idcredito[]', value: this.props.registro.idcredito }),
                    ' '
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement('input', { type: 'text', className: 'styleNo table-input', id: 'numero[]', name: 'numero[]', value: this.props.registro.numero })
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.acreditado,
                    ' '
                ),
                React.createElement(
                    'td',
                    { style: thStyle },
                    React.createElement('input', { type: 'text', className: 'styleNo', id: 'idpagare[]', name: 'idpagare[]', value: this.props.registro.idpagare }),
                    ' '
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement(
                        'select',
                        { className: 'ui dropdown', value: this.state.nopagos, name: 'nopagos[]', onChange: this.handleChange.bind(this) },
                        nopagos
                    )
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', type: 'text', readOnly: 'readOnly', id: 'capital[]', name: 'capital[]', value: capital }),
                    ' '
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', type: 'text', readOnly: 'readOnly', id: 'interes[]', name: 'interes[]', value: interes }),
                    ' '
                ),
                ivatd,
                React.createElement(
                    'td',
                    { style: thStyle },
                    React.createElement('input', { className: 'table-input', readOnly: 'readOnly', type: 'text', id: 'importepago[]', name: 'importepago[]', value: importepago }),
                    ' '
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', type: 'text', readOnly: 'readOnly', id: 'ahocomprome[]', name: 'ahocomprome[]', value: ahocomprome }),
                    ' '
                ),
                ahorrotd,
                React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', type: 'text', readOnly: 'readOnly', id: 'total[]', name: 'total[]', value: total }),
                    ' '
                ),
                ajustetd
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

/*
*
*
*/


var Table = function (_React$Component14) {
    _inherits(Table, _React$Component14);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this19 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this19.handleChange = _this19.handleChange.bind(_this19);
        return _this19;
    }

    _createClass(Table, [{
        key: 'handleChange',
        value: function handleChange(e, total) {
            this.props.onChange(e, total);
        }
    }, {
        key: 'render',
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            var encabezado = ['', 'No. Socia', 'ahorroc', 'ahorrov', 'id', 'No', 'Nombre', 'Pagaré', 'No.Pagos', 'Capital', 'Interes', 'IVA', 'SubTotal', 'Ahorro comprometido', 'Ahorro', 'Total', 'Ajuste'];

            if (esquema == 'ban.') {
                encabezado = ['', 'No. Socia', 'ahorroc', 'ahorrov', 'id', 'No', 'Nombre', 'Pagaré', 'No.Pagos', 'Capital', 'Aporte Solidario', 'SubTotal', 'Garantia', 'Total'];
            }
            if (datos instanceof Array === true) {
                /*        
                        if (this.props.tipo == "temporal"){
                            encabezado = ['No','No. Socia','Nombre','Nivel', 'Pago','Ahorro Voluntario', 'Total'];
                            datos.forEach(function(record) {
                                  rows.push(<RecordDetalleTemp registro={record} onChange={this.handleChange}  />);
                            }.bind(this));
                        }else {
                */
                datos.forEach(function (record) {
                    rows.push(React.createElement(RecordDetalle, { registro: record, onChange: this.handleChange }));
                }.bind(this));

                //      }    
            }
            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                'div',
                null,
                React.createElement(
                    'table',
                    { className: 'ui selectable celled blue table' },
                    React.createElement(
                        'thead',
                        null,
                        React.createElement(Lista, { enca: encabezado })
                    ),
                    React.createElement(
                        'tbody',
                        null,
                        rows
                    )
                )
            );
        }
    }]);

    return Table;
}(React.Component);

var RecordDetalleC = function (_React$Component15) {
    _inherits(RecordDetalleC, _React$Component15);

    function RecordDetalleC(props) {
        _classCallCheck(this, RecordDetalleC);

        var _this20 = _possibleConstructorReturn(this, (RecordDetalleC.__proto__ || Object.getPrototypeOf(RecordDetalleC)).call(this, props));

        _this20.state = {
            cantidad: 0, total: 0
        };
        return _this20;
    }

    _createClass(RecordDetalleC, [{
        key: 'handleChange',
        value: function handleChange(e) {
            var _this21 = this;

            var name = e.target.name;
            var value = isNaN(e.target.value) || e.target.value == "" ? "0" : e.target.value;

            this.setState({
                cantidad: value,
                total: parseFloat(this.props.registro.nombre) * parseFloat(value)
            });

            this.setState(function (prevState, props) {
                var anterior = numeral(prevState.total).format('0.00');
                var valoradd = numeral(_this21.state.total).format('0.00');
                var valtext = numeral($('#grantotalcorte').val()).format('0.00');
                var final = parseFloat(valtext) - parseFloat(valoradd) + parseFloat(anterior);
                _this21.props.onChange(e, final);
            });
        }
    }, {
        key: 'render',
        value: function render() {
            var cantidad = numeral(this.state.cantidad).format('0,0.00');
            var total = numeral(this.state.total).format('0,0.00');
            var thStyle = {
                display: 'none'
            };
            return React.createElement(
                'tr',
                null,
                React.createElement(
                    'td',
                    { style: thStyle },
                    React.createElement('input', { className: 'table-input', type: 'text', id: 'iddenomina[]', name: 'iddenomina[]', value: this.props.registro.iddenomina }),
                    ' '
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.nombre
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', type: 'text', id: 'cantidad[]', name: 'cantidad[]', value: this.state.cantidad, onChange: this.handleChange.bind(this) }),
                    ' '
                ),
                React.createElement(
                    'td',
                    null,
                    React.createElement('input', { className: 'table-input', readOnly: 'readOnly', type: 'text', id: 'total[]', name: 'total[]', value: total }),
                    ' '
                )
            );
        }
    }]);

    return RecordDetalleC;
}(React.Component);

var TableC = function (_React$Component16) {
    _inherits(TableC, _React$Component16);

    function TableC(props) {
        _classCallCheck(this, TableC);

        var _this22 = _possibleConstructorReturn(this, (TableC.__proto__ || Object.getPrototypeOf(TableC)).call(this, props));

        _this22.state = { grantotal: _this22.props.totalxpagarc };
        _this22.handleChange = _this22.handleChange.bind(_this22);
        return _this22;
    }

    _createClass(TableC, [{
        key: 'handleChange',
        value: function handleChange(e, total) {
            this.setState({ grantotal: total });
            this.props.onChange(total);
        }
    }, {
        key: 'render',
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            var grantotal = this.state.grantotal != 0 ? numeral(this.state.grantotal).format('0,0.00') : numeral(this.props.totalxpagar).format('0,0.00');
            if (datos instanceof Array === true) {
                datos.forEach(function (record) {
                    rows.push(React.createElement(RecordDetalleC, { registro: record, onChange: this.handleChange }));
                }.bind(this));
            }
            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                'div',
                { className: 'ui grid' },
                React.createElement(
                    'div',
                    { className: 'eight wide column' },
                    React.createElement(
                        'table',
                        { className: 'ui selectable celled blue table' },
                        React.createElement(
                            'thead',
                            null,
                            React.createElement(Lista, { enca: ['Denominación', 'Cantidad', 'Importe'] })
                        ),
                        React.createElement(
                            'tbody',
                            null,
                            rows
                        ),
                        React.createElement(
                            'tfoot',
                            { className: 'full-width' },
                            React.createElement(
                                'tr',
                                null,
                                React.createElement(
                                    'th',
                                    { colSpan: 3 },
                                    React.createElement(
                                        'div',
                                        { className: 'ui right floated  tiny orange statistic' },
                                        React.createElement('input', { className: 'totalxpagar', type: 'text', id: 'grantotalcorte', name: 'grantotalcorte', value: grantotal })
                                    )
                                )
                            )
                        )
                    )
                )
            );
        }
    }]);

    return TableC;
}(React.Component);

var RecordDetSoc = function (_React$Component17) {
    _inherits(RecordDetSoc, _React$Component17);

    function RecordDetSoc(props) {
        _classCallCheck(this, RecordDetSoc);

        var _this23 = _possibleConstructorReturn(this, (RecordDetSoc.__proto__ || Object.getPrototypeOf(RecordDetSoc)).call(this, props));

        _this23.handleEdoCta = _this23.handleEdoCta.bind(_this23);
        _this23.handleRetiro = _this23.handleRetiro.bind(_this23);
        _this23.handlePago = _this23.handlePago.bind(_this23);
        return _this23;
    }

    _createClass(RecordDetSoc, [{
        key: 'handleEdoCta',
        value: function handleEdoCta(e) {
            var d = new Date();
            var id = this.props.registro.idcredito;
            var surl = 'edocta/' + id;

            var a = document.createElement('a');
            a.href = base_url + ('api/ReportV1/' + surl);
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: 'handleRetiro',
        value: function handleRetiro(e) {
            var reg = this.props.registro;
            this.props.onClick(e, reg.idcredito, reg.idahorro, reg.numero_cuenta, reg.idpagare, reg.comprometido);
        }
    }, {
        key: 'handlePago',
        value: function handlePago(e) {
            var reg = this.props.registro;
            this.props.onClick(e, reg.idcredito, 0, '', reg.idpagare);
        }
    }, {
        key: 'render',
        value: function render() {
            var sComprometido = null;
            var creditoIndividual = null;
            if (this.props.registro.comprometido != 0 && this.props.registro.monto == this.props.registro.pagos) {
                sComprometido = React.createElement(
                    'a',
                    { 'data-tooltip': 'Retiro de ahorro' },
                    React.createElement('i', { className: 'minus circle icon circular green link', onClick: this.handleRetiro })
                );
            }
            if (this.props.registro.idproducto == 10) {
                if (this.props.registro.saldo > 0) {
                    creditoIndividual = React.createElement(
                        'a',
                        { 'data-tooltip': 'Cr\xE9dito Individual' },
                        React.createElement('i', { className: 'minus circle icon circular green calendar plus', onClick: this.handlePago })
                    );
                }
            }
            var thStyle = {
                display: 'none'
            };
            return React.createElement(
                'tr',
                null,
                React.createElement(
                    'td',
                    { style: thStyle },
                    this.props.registro.idcredito
                ),
                React.createElement(
                    'td',
                    { style: thStyle },
                    this.props.registro.idahorro
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.idpagare
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.monto
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.pagos
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.saldo
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.numero_cuenta
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.comprometido
                ),
                React.createElement(
                    'td',
                    { className: ' center aligned' },
                    sComprometido,
                    creditoIndividual,
                    React.createElement(
                        'a',
                        { 'data-tooltip': 'Estado de cuenta' },
                        React.createElement('i', { className: 'file text outline icon circular orange link', onClick: this.handleEdoCta })
                    )
                )
            );
        }
    }]);

    return RecordDetSoc;
}(React.Component);

var RecordDetAho = function (_React$Component18) {
    _inherits(RecordDetAho, _React$Component18);

    function RecordDetAho(props) {
        _classCallCheck(this, RecordDetAho);

        var _this24 = _possibleConstructorReturn(this, (RecordDetAho.__proto__ || Object.getPrototypeOf(RecordDetAho)).call(this, props));

        _this24.handleAddAhorro = _this24.handleAddAhorro.bind(_this24);
        return _this24;
    }

    _createClass(RecordDetAho, [{
        key: 'handleAddAhorro',
        value: function handleAddAhorro(e) {
            var reg = this.props.registro;
            this.props.onClick(e, reg.idahorro, reg.numero_cuenta);
        }
    }, {
        key: 'render',
        value: function render() {
            var thStyle = {
                display: 'none'
            };

            return React.createElement(
                'tr',
                null,
                React.createElement(
                    'td',
                    { style: thStyle },
                    this.props.registro.idahorro
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.numero_cuenta
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.menor
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.fecdeposito
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.fecretiro
                ),
                React.createElement(
                    'td',
                    { className: ' center aligned' },
                    React.createElement(
                        'a',
                        { 'data-tooltip': 'Agregar registro' },
                        React.createElement('i', { className: 'add circle icon circular green link', onClick: this.handleAddAhorro })
                    )
                )
            );
        }
    }]);

    return RecordDetAho;
}(React.Component);

var RecordDetInv = function (_React$Component19) {
    _inherits(RecordDetInv, _React$Component19);

    function RecordDetInv(props) {
        _classCallCheck(this, RecordDetInv);

        var _this25 = _possibleConstructorReturn(this, (RecordDetInv.__proto__ || Object.getPrototypeOf(RecordDetInv)).call(this, props));

        _this25.handleEdoCta = _this25.handleEdoCta.bind(_this25);
        _this25.handleReinversion = _this25.handleReinversion.bind(_this25);
        _this25.handleCancelar = _this25.handleCancelar.bind(_this25);
        return _this25;
    }

    _createClass(RecordDetInv, [{
        key: 'handleEdoCta',
        value: function handleEdoCta() {
            var surl = "contratoinver/" + this.props.registro.numero;
            var a = document.createElement('a');
            a.href = base_url + 'api/ReportV1/' + surl;
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: 'handleReinversion',
        value: function handleReinversion(e) {
            var reg = this.props.registro;
            this.props.onClick(e, reg.numero);
        }
    }, {
        key: 'handleCancelar',
        value: function handleCancelar(e) {
            this.props.onClick(e, this.props.registro.numero);
        }
    }, {
        key: 'render',
        value: function render() {
            var reinventir = void 0;
            if (this.props.registro.dias == 0) {
                reinventir = React.createElement(
                    'a',
                    { 'data-tooltip': 'Reinvertir ' },
                    React.createElement('i', { className: 'exchange icon circular green link', onClick: this.handleReinversion })
                );
            }

            var thStyle = {
                display: 'none'
            };

            return React.createElement(
                'tr',
                null,
                React.createElement(
                    'td',
                    null,
                    this.props.registro.numero
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.fecha
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.fechafin
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.dias
                ),
                React.createElement(
                    'td',
                    { className: ' center aligned' },
                    reinventir,
                    React.createElement(
                        'a',
                        { 'data-tooltip': 'Contrato' },
                        React.createElement('i', { className: 'file text outline icon circular orange link', onClick: this.handleEdoCta })
                    )
                )
            );
        }
    }]);

    return RecordDetInv;
}(React.Component);

var RecordCredxDis = function (_React$Component20) {
    _inherits(RecordCredxDis, _React$Component20);

    function RecordCredxDis(props) {
        _classCallCheck(this, RecordCredxDis);

        return _possibleConstructorReturn(this, (RecordCredxDis.__proto__ || Object.getPrototypeOf(RecordCredxDis)).call(this, props));
    }

    _createClass(RecordCredxDis, [{
        key: 'render',
        value: function render() {

            return React.createElement(
                'tr',
                null,
                React.createElement(
                    'td',
                    null,
                    this.props.registro.idcredito
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.idacreditado
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.idpagare
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.acreditado
                ),
                React.createElement(
                    'td',
                    { className: 'ui right aligned' },
                    numeral(this.props.registro.monto).format('0,0.00')
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.fecha_primerpago
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.fecha_aprov
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.fecha_entrega
                )
            );
        }
    }]);

    return RecordCredxDis;
}(React.Component);

var TableGlob = function (_React$Component21) {
    _inherits(TableGlob, _React$Component21);

    function TableGlob(props) {
        _classCallCheck(this, TableGlob);

        var _this27 = _possibleConstructorReturn(this, (TableGlob.__proto__ || Object.getPrototypeOf(TableGlob)).call(this, props));

        _this27.state = {
            ver: false, verr: false, verp: false, veri: false, idacreditado: 0, numero_cuenta: '', idpagare: '', movimiento: 0, importe: 0,
            saldoini: 0, saldofin: 0, saldoreq: 0, iddestino: 0,
            idahorrov: 0, idahorrop: 0, idcredito: 0,
            incrementocheck: 'off', retiroccheck: 'off', retiroicheck: 'off', cretiroc: 0, cretiroi: 0, idinversion: '',

            montocredito: 0,
            capitalactual: 0,
            adeudototal: 0,
            intnorvig: 0,
            capitalvig: 0,
            intnorven: 0,
            intmor: 0,
            iva: 0,
            gIVA: '0',
            totalpagos: 0,
            condonacion: 0,
            gastos: 0,
            efectivo: 0,
            importepagar: 0,
            nomovahorro: 0,
            totalamortiza: 0,
            totalliquida: 0,
            int_n_pago: 0,
            int_m_pago: 0,
            iva_pago: 0,
            cap_pago: 0,
            pago_alafecha: 0,
            pago_talafecha: 0,
            capital_porvencer: 0,
            int_n_pagof: 0,
            int_m_pagof: 0,
            iva_pagof: 0,
            cap_pagof: 0,
            editar: true

        };
        _this27.handleAddAhorro = _this27.handleAddAhorro.bind(_this27);
        _this27.handleRetiro = _this27.handleRetiro.bind(_this27);
        _this27.handleInversion = _this27.handleInversion.bind(_this27);
        _this27.handleCloseW = _this27.handleCloseW.bind(_this27);
        _this27.handleInputChange = _this27.handleInputChange.bind(_this27);
        _this27.handleonBlur = _this27.handleonBlur.bind(_this27);
        _this27.handleClickCheck = _this27.handleClickCheck.bind(_this27);

        return _this27;
    }

    _createClass(TableGlob, [{
        key: 'handleAddAhorro',
        value: function handleAddAhorro(e, idahorrov, numero_cuenta) {
            this.setState({ ver: true, idahorrov: idahorrov, numero_cuenta: numero_cuenta });
            this.props.onChange(e, 1);
        }
    }, {
        key: 'handleRetiro',
        value: function handleRetiro(e, idcredito, idahorrop, numero_cuenta, idpagare, importe) {
            console.log(idahorrop);
            if (idahorrop == 0) {
                this.setState({ verp: true, idcredito: idcredito, idahorrop: 0, numero_cuenta: '', idpagare: idpagare, importe: 0, verr: false });
            } else {
                this.setState({ verr: true, idcredito: idcredito, idahorrop: idahorrop, numero_cuenta: numero_cuenta, idpagare: idpagare, importe: importe, verp: false });
            }
            this.props.onChange(e, 3);
        }
    }, {
        key: 'GeneraTotales',
        value: function GeneraTotales() {
            var fecha = $('#fecha').val();
            var importe = this.state.importe;
            var dias = this.state.dias;
            var tasa = this.state.tasa;
            if (name == "importe") {
                importe = value;
            } else if (name == "dias") {
                dias = value;
            } else if (name == "tasa") {
                tasa = value;
            }
            if (fecha != '' && dias != 0 || dias != 0 && fecha != "") {
                var nWeek = moment(fecha, 'DD/MM/YYYY').add(dias, 'day').format('e');
                if (nWeek == 6) {
                    dias = parseFloat(dias) + 2;
                } else if (nWeek == 0) {
                    dias = parseFloat(dias) + 1;
                }
                var fec = moment(fecha, 'DD/MM/YYYY').add(dias, 'day').format('DD/MM/YYYY');

                this.setState({ fechafin: fec });
            }
            if (importe != 0 && tasa != 0) {
                var interes = numeral(parseFloat(importe.replace(',', '')) * dias * (tasa / 100) / 360).format('0,0.00');
                var sumTotal = numeral(parseFloat(importe.replace(',', '')) + parseFloat(interes.replace(',', ''))).format('0.00');
                this.setState({ interes: interes, total: sumTotal });
            }
        }
    }, {
        key: 'handleInversion',
        value: function handleInversion(e, numero) {
            this.setState({ veri: true });
            this.props.onChange(e, 2);
            var forma = this;
            var object = {
                url: base_url + ('api/CarteraV1/getInversion/' + numero),
                type: 'GET',
                dataType: 'json'
            };
            ajax(object).then(function resolve(response) {
                var fecha = moment(response.result[0].fechafin).format('DD/MM/YYYY');
                forma.setState({
                    csrf: response.newtoken, idinversion: response.result[0].idinversion,
                    numeroi: numero, importei: response.result[0].importe,
                    interesi: response.result[0].interes, totali: response.result[0].total, fecha: fecha,
                    importe: response.result[0].total, interes: '', fechafin: '', total: '', numero: '', activo: 0,
                    incrementocheck: 'off', incremento: '', retiroccheck: 'off', retiroc: '', cretiroc: 0, retiroicheck: 'off', retiroi: '', cretiroi: 0,
                    dias: response.result[0].dias, tasa: response.result[0].tasa
                });
                var $form = $('.get.reinver form'),
                    Folio = $form.form('set values', { cretiroi: '0', cretiroc: '0', incrementocheck: 'off', retiroccheck: 'off', retiroicheck: 'off', dias: response.result[0].dias });
                forma.GeneraTotales();
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
    }, {
        key: 'handleCloseW',
        value: function handleCloseW(e) {
            this.props.onChange(e, 0);
            this.setState({ ver: false, verr: false, veri: false, verp: false });
        }
    }, {
        key: 'handleClickCheck',
        value: function handleClickCheck(name) {
            var valor = void 0;
            var retiroc = 0;
            var retiroi = 0;
            var incremento = 0;

            if (name == "incrementocheck") {
                valor = this.state.incrementocheck;
            } else if (name == "retiroccheck") {
                valor = this.state.retiroccheck;
            } else if (name == "retiroicheck") {
                valor = this.state.retiroicheck;
            }
            if (valor == "off") {
                valor = "on";
            } else {
                valor = "off";
            }

            if (name == "incrementocheck" && valor == "on") {
                retiroc = 0;
                if (this.state.retiroicheck == "on") {
                    retiroi = parseFloat(this.state.interesi);
                }
                incremento = 0;
                this.setState({
                    retiroccheck: 'off', retiroc: '', incremento: ''
                });
                $('.get.reinver form').form('set values', {
                    retiroccheck: 'off'
                });
            } else if (name == "incrementocheck" && valor == "off") {
                incremento: 0;
                if (this.state.retiroicheck == "on") {
                    retiroi = parseFloat(this.state.interesi);
                }
                this.setState({
                    incremento: ''
                });
            } else if (name == "retiroccheck" && valor == "on") {
                var importei = this.state.importei;
                retiroc = parseFloat(importei);
                this.setState({
                    incrementocheck: 'off', incremento: '', retiroc: importei
                });

                $('.get.reinver form').form('set values', {
                    incrementocheck: 'off'
                });
            } else if (name == "retiroccheck" && valor == "off") {
                if (this.state.retiroicheck == "on") {
                    retiroi = parseFloat(this.state.interesi);
                }
                retiroc = 0;
                this.setState({
                    retiroc: ''
                });
            } else if (name == "retiroicheck" && valor == "on") {
                var interesi = this.state.interesi;
                if (this.state.incrementocheck == "on") {
                    if (this.state.incremento != "") {
                        incremento = parseFloat(this.state.incremento);
                    }
                }
                if (this.state.retiroccheck == "on") {
                    retiroc = parseFloat(this.state.importei);
                }
                retiroi = parseFloat(interesi);
                this.setState({
                    retiroi: interesi
                });
            } else if (name == "retiroicheck" && valor == "off") {
                if (this.state.retiroc != "") {
                    retiroc = parseFloat(this.state.retiroc);
                }
                if (this.state.incremento != "") {
                    incremento = parseFloat(this.state.incremento);
                }
                retiroi = 0;
                this.setState({
                    retiroi: ''
                });
            } else {}

            var total = parseFloat(this.state.totali);
            var importe = numeral(total + incremento - retiroc - retiroi).format('0,0.00');
            this.setState({ importe: importe });

            var tasa = this.state.tasa;
            var dias = this.state.dias;
            if (importe != 0 && tasa != 0 && dias != 0) {
                var interes = numeral(parseFloat(importe.replace(',', '')) * dias * (tasa / 100) / 360).format('0,0.00');
                var sumtotal = numeral(parseFloat(importe.replace(',', '')) + parseFloat(interes.replace(',', ''))).format('0.00');
                this.setState({ interes: interes, total: sumtotal });
            }

            this.setState(_defineProperty({}, name, valor));
        }
    }, {
        key: 'handleInputChange',
        value: function handleInputChange(e) {
            var _this28 = this;

            var evento = e.target;
            var value = evento.value;
            var name = evento.name;
            this.setState(_defineProperty({}, name, value));

            var tasa = 0;
            if (name == "dias" || name == "importe") {
                var campo = "";
                var imp = this.state.importe;
                if (name == "dias") {
                    campo = value;
                } else {
                    campo = this.state.dias;
                    if (name == "importe") {
                        imp = value;
                    }
                }
                imp = parseFloat(imp.replace(',', ''));
                if (imp != 0) {
                    var tasafind = this.props.catInteres.filter(function (cat) {
                        return imp > cat.monto;
                    });
                    if (tasafind.length > 0) {
                        tasafind = tasafind[tasafind.length - 1];
                        var tasavalor = tasafind[campo];
                        this.setState({ tasa: tasavalor });

                        tasa = tasavalor;
                    } else {
                        this.setState({ tasa: 0 });
                    }
                } else {
                    this.setState({ tasa: 0 });
                }
            }

            if (name == "importe" || name == "movimiento") {
                var sf = 0;
                var anterior = 0;
                this.setState(function (prevState, props) {
                    anterior = prevState.importe;
                    if (_this28.state.movimiento == "D") {
                        sf = parseFloat(_this28.state.saldoini) + parseFloat(anterior);
                    } else if (_this28.state.movimiento == "R") {
                        sf = parseFloat(_this28.state.saldoini) - parseFloat(anterior);
                    }
                    saldofin: sf;
                });
            } else if (name == "incremento") {
                var retiroc = 0;
                var total = parseFloat(this.state.totali);
                if (this.state.retiroc != "") {
                    retiroc = parseFloat(this.state.retiroc);
                }
                var retiroi = 0;
                if (this.state.retiroicheck == "on") {
                    retiroi = parseFloat(this.state.retiroi);
                }
                var importe = numeral(total - retiroc - retiroi + parseFloat(value)).format('0,0.00');
                this.setState({ importe: importe });

                var _tasa = this.state.tasa;
                var dias = this.state.dias;
                if (importe != 0 && _tasa != 0 && dias != 0) {
                    var interes = numeral(parseFloat(importe.replace(',', '')) * dias * (_tasa / 100) / 360).format('0,0.00');
                    var sumtotal = numeral(parseFloat(importe.replace(',', '')) + parseFloat(interes.replace(',', ''))).format('0.00');
                    this.setState({ interes: interes, total: sumtotal });
                }
            } else if (name == "dias" || name == "tasa") {
                var fecha = $('#fecha').val();
                var _importe2 = this.state.importe;
                var _dias = this.state.dias;
                var _tasa2 = this.state.tasa;
                if (name == "importe") {
                    _importe2 = value;
                } else if (name == "dias") {
                    _dias = value;
                } else if (name == "tasa") {
                    _tasa2 = value;
                }

                if (fecha != '' && _dias != 0 || _dias != 0 && fecha != "") {
                    var nWeek = moment(fecha, 'DD/MM/YYYY').add(_dias, 'day').format('e');
                    if (nWeek == 6) {
                        _dias = parseFloat(_dias) + 2;
                    } else if (nWeek == 0) {
                        _dias = parseFloat(_dias) + 1;
                    }

                    var fec = moment(fecha, 'DD/MM/YYYY').add(_dias, 'day').format('DD/MM/YYYY');
                    this.setState({ fechafin: fec });
                }
                if (_importe2 != 0 && _tasa2 != 0) {
                    var _interes = numeral(parseFloat(_importe2.replace(',', '')) * _dias * (_tasa2 / 100) / 360).format('0,0.00');
                    var sumTotal = numeral(parseFloat(_importe2.replace(',', '')) + parseFloat(_interes.replace(',', ''))).format('0.00');
                    this.setState({ interes: _interes, total: sumTotal });
                }
                return;
            } else if (name == 'condonacion') {

                var intNor = numeral(parseFloat(this.state.intnorvig.replace(',', ''))).format('0.00');
                var intMor = numeral(parseFloat(this.state.intmor.replace(',', ''))).format('0.00');
                var saldoIntNor = void 0;
                var saldoIntMor = void 0;

                if (parseFloat(value) > parseFloat(intNor) + parseFloat(intMor)) {
                    console.log('sobre pasa');
                } else if (parseFloat(value) === 0) {
                    this.setState({
                        int_m_pago: intMor,
                        int_n_pago: intNor
                    });
                    saldoIntMor = intMor;
                    saldoIntNor = intNor;
                } else {

                    if (parseFloat(value) >= parseFloat(intMor) && parseFloat(intMor) > 0) {
                        var saldoInt = numeral(parseFloat(value) - parseFloat(intMor)).format('0.00');
                        saldoInt = numeral(intNor - saldoInt).format('0.00');
                        this.setState({
                            int_m_pago: 0,
                            int_n_pago: saldoInt
                        });
                        saldoIntMor = 0;
                        saldoIntNor = saldoInt;
                    } else if (parseFloat(value) < parseFloat(intMor) && parseFloat(intMor) > 0) {
                        var saldoMor = numeral(parseFloat(intMor) - value).format('0.00');
                        this.setState({
                            int_m_pago: saldoMor
                        });
                        saldoIntMor = saldoMor;
                        saldoIntNor = intNor;
                    } else if (intMor === 0) {
                        var _saldoInt = numeral(parseFloat(intNor) - parseFloat(value)).format('0.00');
                        this.setState({
                            int_n_pago: _saldoInt
                        });
                        saldoIntMor = intMor;
                        saldoIntNor = _saldoInt;
                    }
                }
                var capitalactual = parseFloat(numeral(this.state.capitalactual).format('0.00'));
                var iva_pagoc = (parseFloat(saldoIntMor) + parseFloat(saldoIntNor)) * (this.state.gIVA == '1' ? 0.16 : 0);
                var capital_porvencer = parseFloat(numeral(this.state.capital_porvencer).format('0.00'));
                this.setState({
                    iva_pago: numeral(iva_pagoc).format('0,0.00'),
                    pago_alafecha: numeral(parseFloat(capitalactual) + parseFloat(saldoIntMor) + parseFloat(saldoIntNor) + iva_pagoc).format('0,0.00'),
                    pago_talafecha: numeral(parseFloat(capitalactual) + parseFloat(capital_porvencer) + parseFloat(saldoIntMor) + parseFloat(saldoIntNor) + iva_pagoc).format('0,0.00')

                });
                var importepagar = parseFloat(this.state.importepagar);
                /* 
                            console.log(saldoIntNor)
                            console.log(saldoIntMor)
                            
                 */

                this.updatePay(importepagar, saldoIntNor, saldoIntMor, iva_pagoc);
            } else if (name == 'importepagar') {

                var _importepagar = parseFloat(value);
                this.updatePay(_importepagar, this.state.int_n_pago, this.state.int_m_pago, this.state.iva_pago);
            }
        }
    }, {
        key: 'updatePay',
        value: function updatePay(importepagar, int_normal, int_mora, int_iva) {
            var int_n_pago = parseFloat(numeral(int_normal).format('0.00'));
            var int_m_pago = parseFloat(numeral(int_mora).format('0.00'));
            var iva_pago = parseFloat(numeral(int_iva).format('0.00'));
            var sumaintereses = parseFloat(int_n_pago) + parseFloat(int_m_pago) + parseFloat(iva_pago);

            var restamora = 0;
            var restainteres = 0;
            var iva = 0;
            var capital = 0;

            if (importepagar === 0 || importepagar === '') {
                this.setState({
                    cap_pago: this.state.capitalactual
                });
            } else {
                if (importepagar > sumaintereses) {
                    restamora = int_m_pago;
                    restainteres = int_n_pago;
                    iva = iva_pago;
                    capital = numeral(importepagar - sumaintereses).format('0.00');
                    var validaImp = parseFloat(numeral(this.state.capitalactual).format('0.00')) + parseFloat(numeral(this.state.capital_porvencer).format('0.00'));
                    if (capital > validaImp) {
                        capital = validaImp;
                    }
                } else if (importepagar === sumaintereses) {
                    restamora = int_m_pago;
                    restainteres = int_n_pago;
                    iva = iva_pago;
                    capital = 0;
                } else if (sumaintereses === 0) {
                    this.setState({
                        cap_pago: numeral(importepagar).format('0,0.00')
                    });
                } else {
                    var montosiniva = numeral(importepagar / (this.state.gIVA == '1' ? 1.16 : 1)).format('0.00');
                    if (parseFloat(iva_pago) == 0) {
                        montosiniva = numeral(importepagar).format('0.00');
                    }
                    var saldo = montosiniva;
                    iva = numeral(importepagar - montosiniva).format('0.00');
                    restamora = 0;
                    restainteres = 0;
                    if (saldo < int_m_pago) {
                        restamora = montosiniva;
                    } else {
                        restamora = int_m_pago;
                    }

                    saldo = numeral(saldo - restamora).format('0.00');
                    if (int_n_pago > 0) {
                        if (saldo < int_n_pago) {
                            restainteres = saldo;
                        } else {
                            restainteres = int_n_pago;
                        }
                    }
                }

                this.setState({
                    iva_pagof: numeral(iva).format('0,0.00'),
                    int_m_pagof: numeral(restamora).format('0,0.00'),
                    int_n_pagof: numeral(restainteres).format('0,0.00'),
                    cap_pagof: numeral(capital).format('0,0.00')
                });
            }
        }
    }, {
        key: 'handleSubmitAho',
        value: function handleSubmitAho(event) {
            event.preventDefault();
            $('.ui.form.formaho').form({
                inline: true,
                on: 'blur',
                fields: {
                    numero_cuenta: {
                        identifier: 'numero_cuenta',
                        rules: [{
                            type: 'empty',
                            prompt: 'Numero de Cuenta incorrecto '
                        }]
                    },
                    movimiento: {
                        identifier: 'movimiento',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el tipo de movimiento'
                        }]
                    },
                    importe: {
                        identifier: 'importe',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el importe'
                        }, {
                            type: 'number',
                            prompt: 'Cantidad incorrecta'
                        }]
                    }
                }
            });

            $('.ui.form.formaho').form('validate form');
            var valida = $('.ui.form.formaho').form('is valid');
            if (this.state.movimiento == "0" || this.state.importe == 0) {
                valida = false;
            }

            if (valida == true) {
                var $form = $('.get.ahoing form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'POST';
                var _forma3 = this;

                var idacredita = $('#idacredita').data("idcre");
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/altahorro/' + idacredita),
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            _forma3.setState({
                                importe: 0, movimiento: 0
                            });
                            var nomov = response.nomov;
                            _forma3.setState({ nomovahorro: nomov });

                            _forma3.props.onClick(2, response.message, 'ui positive floating message', response.newtoken);
                            var $form = $('.get.ahoing form'),
                                Folio = $form.form('set values', { movimiento: '0' });

                            _forma3.PrintMov(nomov);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma3.props.onClick(0, response.message, 'ui negative floating message', response.newtoken);
                        });
                    }
                }).modal('show');
            } else {
                forma.props.onClick(0, 'Datos incompletos', 'ui negative floating message');
            }
        }
    }, {
        key: 'PrintMov',
        value: function PrintMov(nomov) {
            var link = 'ReportV1/PrintMov/' + nomov;
            var a = document.createElement('a');
            a.href = base_url + ('api/' + link);
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: 'handleSubmitInv',
        value: function handleSubmitInv(event) {
            event.preventDefault();
            $('.ui.form.forminv').form({
                inline: true,
                on: 'blur',
                fields: {
                    fecha: {
                        identifier: 'fecha',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la fecha'
                        }]
                    },
                    importe: {
                        identifier: 'importe',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    dias: {
                        identifier: 'dias',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    tasa: {
                        identifier: 'tasa',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    interes: {
                        identifier: 'interes',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    fechafin: {
                        identifier: 'fechafin',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    total: {
                        identifier: 'total',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    }
                }
            });

            $('.ui.form.forminv').form('validate form');
            var valida = $('.ui.form.forminv').form('is valid');
            this.setState({ message: '', statusmessage: 'ui hidden message' });

            if (valida == true) {
                var $form = $('.get.solinver form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var _forma4 = this;
                var idacredita = this.state.acreditainv;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/update_inversion/' + idacredita),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            _forma4.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ', fecha: '', importe: '',
                                dias: '', tasa: '', interes: '', fechafin: '', total: '', numero: '', activo: 0, idinversion: ''
                            });

                            _forma4.autoReset();
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma4.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            _forma4.autoReset();
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
        key: 'handleSubmitRet',
        value: function handleSubmitRet(event) {
            event.preventDefault();
            $('.ui.form.formret').form({
                inline: true,
                on: 'blur',
                fields: {
                    numero_cuenta: {
                        identifier: 'numero_cuenta',
                        rules: [{
                            type: 'empty',
                            prompt: 'Numero de Cuenta incorrecto '
                        }]
                    },
                    idpagare: {
                        identifier: 'idpagare',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el pagaré'
                        }]
                    },
                    iddestino: {
                        identifier: 'iddestino',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione el destino'
                        }]
                    },
                    importe: {
                        identifier: 'importe',
                        rules: [{
                            type: 'empty',
                            prompt: 'Capture el importe'
                        }]
                    }

                }
            });

            $('.ui.form.formret').form('validate form');
            var valida = $('.ui.form.formret').form('is valid');
            if (this.state.iddestino == "0" || this.state.importe == 0) {
                valida = false;
            }
            var forma = this;
            if (valida == true) {
                var $form = $('.get.ahoret form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var idacredita = $('#idacredita').data("idcre");
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/retahorro/' + idacredita),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            forma.setState({
                                idpagare: '', numero_cuenta: '', importe: '', iddestino: 0
                            });
                            forma.props.onClick(1, response.message, 'ui positive floating message', response.newtoken);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            forma.setState({
                                csrf1: response.newtoken, csrf2: response.newtoken
                            });
                            forma.props.onClick(0, response.message, 'ui negative floating message', response.newtoken);
                        });
                    }
                }).modal('show');
            } else {
                forma.props.onClick(0, 'Datos incompletos!', 'ui negative floating message');
            }
        }
    }, {
        key: 'handleSubmitReInv',
        value: function handleSubmitReInv() {
            event.preventDefault();
            var rulesIncremento = void 0;
            var rulesretiroc = void 0;
            var rulesretiroi = void 0;
            var rulescretiroc = void 0;
            var rulescretiroi = void 0;
            if (this.state.incrementocheck == "on") {
                rulesIncremento = [{
                    type: 'empty',
                    prompt: 'Incremento requerido'
                }];
            }
            if (this.state.retiroccheck == "on") {
                rulesretiroc = [{
                    type: 'empty',
                    prompt: 'Retiro Capital requerido'
                }];

                if (this.state.cretiroc == "0") {
                    rulescretiroc = [{
                        type: 'integer[1]',
                        prompt: 'Seleccione una opción'
                    }];
                } else {
                    rulescretiroc = [{
                        type: 'empty',
                        prompt: 'Seleccione una opción'
                    }];
                }
            }
            if (this.state.retiroicheck == "on") {
                rulesretiroi = [{
                    type: 'empty',
                    prompt: 'Retiro Interes requerido'
                }];
                if (this.state.cretiroi == "0") {
                    rulescretiroi = [{
                        type: 'integer[1]',
                        prompt: 'Seleccione una opción'
                    }];
                } else {
                    rulescretiroi = [{
                        type: 'empty',
                        prompt: 'Seleccione una opción'
                    }];
                }
            }

            $('.ui.form.formreinv').form({
                inline: true,
                on: 'blur',
                fields: {
                    importei: {
                        identifier: 'importei',
                        rules: [{
                            type: 'empty',
                            prompt: 'Importe requerido'
                        }]
                    },
                    interesi: {
                        identifier: 'interesi',
                        rules: [{
                            type: 'empty',
                            prompt: 'Interes requerido'
                        }]
                    },
                    totali: {
                        identifier: 'totali',
                        rules: [{
                            type: 'empty',
                            prompt: 'Total requerido'
                        }]
                    },
                    incremento: {
                        identifier: 'incremento',
                        rules: rulesIncremento
                    },
                    fecha: {
                        identifier: 'fecha',
                        rules: [{
                            type: 'empty',
                            prompt: 'Fecha requerido'
                        }]
                    },
                    retiroc: {
                        identifier: 'retiroc',
                        rules: rulesretiroc
                    },
                    cretiroc: {
                        identifier: 'cretiroc',
                        rules: rulescretiroc
                    },
                    retiroi: {
                        identifier: 'retiroi',
                        rules: rulesretiroi
                    },
                    cretiroi: {
                        identifier: 'cretiroi',
                        rules: rulescretiroi
                    },
                    importe: {
                        identifier: 'importe',
                        rules: [{
                            type: 'empty',
                            prompt: 'Fecha requerido'
                        }]
                    },
                    dias: {
                        identifier: 'dias',
                        rules: [{
                            type: 'empty',
                            prompt: 'Dias requerido'
                        }]
                    },
                    tasa: {
                        identifier: 'dias',
                        rules: [{
                            type: 'empty',
                            prompt: 'Tasa requerido'
                        }]
                    },
                    interes: {
                        identifier: 'interes',
                        rules: [{
                            type: 'empty',
                            prompt: 'Interes requerido'
                        }]
                    },
                    fechafin: {
                        identifier: 'fechafin',
                        rules: [{
                            type: 'empty',
                            prompt: 'Fecha Vencimiento requerido'
                        }]
                    },
                    total: {
                        identifier: 'total',
                        rules: [{
                            type: 'empty',
                            prompt: 'Total requerido'
                        }]
                    }
                }
            });

            $('.ui.form.formreinv').form('validate form');
            var valida = $('.ui.form.formreinv').form('is valid');
            if (this.state.total == 0 && this.state.retiroccheck != "on" && this.state.retiroicheck != "on") {
                valida = false;
            }
            var forma = this;
            if (valida == true) {
                var $form = $('.get.reinver form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var idacredita = $('#idacredita').data("idcre");
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/reinversion/' + idacredita),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {

                            var numero = response.registros;

                            forma.handleCloseW();
                            forma.props.onClick(3, response.message, 'ui positive floating message', response.newtoken);

                            forma.printInversion(numero);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            forma.setState({
                                csrf1: response.newtoken, csrf2: response.newtoken
                            });
                            forma.props.onClick(0, response.message, 'ui negative floating message', response.newtoken);
                        });
                    }
                }).modal('show');
            } else {
                forma.props.onClick(0, 'Datos incompletos!', 'ui negative floating message');
            }
        }
    }, {
        key: 'handleSubmitPago',
        value: function handleSubmitPago() {
            event.preventDefault();
            $('.ui.form.formpag').form({
                inline: true,
                on: 'blur',
                fields: {
                    condonacion: {
                        identifier: 'condonacion',
                        rules: [{
                            type: 'empty',
                            prompt: 'Condonación requerido'
                        }, {
                            type: 'decimal',
                            prompt: 'Condonación requerido'
                        }]
                    },
                    importepagar: {
                        identifier: 'importepagar',
                        rules: [{
                            type: 'empty',
                            prompt: 'Importe requerido'
                        }, {
                            type: 'decimal',
                            prompt: 'Importe requerido'
                        }]
                    },
                    fecha_pagoi: {
                        identifier: 'fecha_pagoi',
                        rules: [{
                            type: 'empty',
                            prompt: 'Fecha requerido'
                        }]
                    }

                }
            });

            $('.ui.form.formpag').form('validate form');
            var valida = $('.ui.form.formpag').form('is valid');
            var message = 'Datos incompletos';
            if (numeral(this.state.importepagar).format('0.00') == 0.00) {
                valida = false;
            } else {
                if (parseFloat(numeral(this.state.importepagar).format('0.00')) > parseFloat(numeral(this.state.pago_talafecha).format('0.00')) + 1) {
                    valida = false;
                    message = 'imoporte de pago es mayor al permitido';
                }
            }

            var forma = this;
            if (valida == true) {
                var $form = $('.get.pagind form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var idacredita = $('#idacredita').data("idcre");
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/add_pagind/' + idacredita),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            var _forma$setState2;

                            var numero = response.registros;

                            forma.handleCloseW();
                            forma.props.onClick(1, response.message, 'ui positive floating message', response.newtoken);

                            forma.setState((_forma$setState2 = {
                                capitalactual: 0,
                                capital_porvencer: 0,
                                intnorvig: 0,
                                intmor: 0,
                                iva: 0,
                                totalamortiza: 0,
                                totalliquida: 0,
                                condonacion: 0
                            }, _defineProperty(_forma$setState2, 'condonacion', 0), _defineProperty(_forma$setState2, 'importepagar', 0), _defineProperty(_forma$setState2, 'int_n_pagof', 0), _defineProperty(_forma$setState2, 'int_m_pagof', 0), _defineProperty(_forma$setState2, 'iva_pagof', 0), _defineProperty(_forma$setState2, 'cap_pagof', 0), _defineProperty(_forma$setState2, 'pago_alafecha', 0), _defineProperty(_forma$setState2, 'pago_talafecha', 0), _defineProperty(_forma$setState2, 'editar', true), _forma$setState2));

                            //                        forma.printInversion(numero);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            forma.setState({
                                csrf1: response.newtoken, csrf2: response.newtoken
                            });
                            forma.props.onClick(0, response.message, 'ui negative floating message', response.newtoken);
                        });
                    }
                }).modal('show');
            } else {
                forma.props.onClick(0, message, 'ui negative floating message');
            }
        }
    }, {
        key: 'printInversion',
        value: function printInversion(numero, e) {
            var surl = "contratoinver/" + numero;
            var a = document.createElement('a');
            a.href = base_url + 'api/ReportV1/' + surl;
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: 'handleClickFecha',
        value: function handleClickFecha(e) {
            var fechaconsulta = "";
            var id = 0;
            if ($('#fecha_pagoi').val() != '') {
                var _forma5 = this;
                var extraer = $('#fecha_pagoi').val().split('/');
                var fec = new Date(extraer[2], extraer[1] - 1, extraer[0]);
                fechaconsulta = moment(fec).format('DDMMYYYY');
                if (fechaconsulta != '') {
                    var idpago = this.state.idcredito;
                    var object = {
                        url: base_url + ('api/CarteraV1/getPagoInd/' + fechaconsulta + '/' + idpago),
                        type: 'GET',
                        dataType: 'json'
                    };
                    ajax(object).then(function resolve(response) {
                        var data = response.saldos;
                        console.log(response);
                        _forma5.setState({
                            capitalactual: numeral(data.capital).format('0,0.00'),
                            intnorvig: numeral(data.interes).format('0,0.00'),
                            intmor: numeral(data.interes_mora).format('0,0.00'),
                            iva: numeral(data.iva).format('0,0.00'),
                            gIVA: data.gIva,
                            totalamortiza: numeral(data.total).format('0,0.00'),
                            totalliquida: numeral(data.liquidar).format('0,0.00'),
                            int_n_pago: numeral(data.interes).format('0,0.00'),
                            int_m_pago: numeral(data.interes_mora).format('0,0.00'),
                            iva_pago: numeral(data.iva).format('0,0.00'),
                            pago_alafecha: numeral(data.total).format('0,0.00'),
                            pago_talafecha: numeral(data.liquidar).format('0,0.00'),
                            condonacion: 0,
                            cap_pago: numeral(data.capital).format('0,0.00'),
                            capital_porvencer: numeral(data.capital_porvencer).format('0,0.00'),
                            editar: data.editar
                        });
                    }, function reject(reason) {

                        var response = validaError(reason);
                        console.log(response);
                    });
                }
            }
        }
    }, {
        key: 'handleonBlur',
        value: function handleonBlur(event) {}
    }, {
        key: 'autoReset',
        value: function autoReset() {
            var self = this;
            window.clearTimeout(self.timeout);
            this.timeout = window.setTimeout(function () {
                self.setState({ message: '', statusmessage: 'ui message hidden' });
            }, 5000);
        }
    }, {
        key: 'render',
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            var addAhorro = null;
            var retAhorro = null;
            var addInversion = null;
            var pagoInd = null;
            if (datos instanceof Array === true) {
                datos.forEach(function (record) {
                    if (this.props.name == "pagares") {
                        rows.push(React.createElement(RecordDetSoc, { registro: record, onClick: this.handleRetiro }));
                    } else if (this.props.name == "voluntario") {
                        rows.push(React.createElement(RecordDetAho, { registro: record, onClick: this.handleAddAhorro }));
                    } else if (this.props.name == "inversiones") {
                        rows.push(React.createElement(RecordDetInv, { registro: record, onClick: this.handleInversion }));
                    } else if (this.props.name == "credxdis") {
                        rows.push(React.createElement(RecordCredxDis, { registro: record, onClick: this.handleRetiro }));
                    }
                }.bind(this));

                if (this.props.name == "pagares") {
                    retAhorro = React.createElement(
                        'div',
                        null,
                        React.createElement(
                            'h4',
                            { className: this.state.verr === true ? "ui dividing header right aligned" : "hidden" },
                            React.createElement('i', { className: 'window close icon red link', onClick: this.handleCloseW })
                        ),
                        React.createElement(
                            'div',
                            { className: this.state.verr === true ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                'div',
                                { className: 'get ahoret' },
                                React.createElement(
                                    'form',
                                    { className: 'ui form formret', ref: 'form', onSubmit: this.handleSubmitRet.bind(this), method: 'post' },
                                    React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.props.csrf }),
                                    React.createElement(
                                        'div',
                                        { className: 'four fields' },
                                        React.createElement('input', { id: 'idahorrop', readOnly: 'readOnly', name: 'idahorrop', type: 'hidden', value: this.state.idahorrop }),
                                        React.createElement('input', { id: 'idcredito', readOnly: 'readOnly', name: 'idcredito', type: 'hidden', value: this.state.idcredito }),
                                        React.createElement(InputField, { id: 'numero_cuenta', label: 'Cuenta', readOnly: 'readOnly', valor: this.state.numero_cuenta }),
                                        React.createElement(SelectOption, { name: 'iddestino', id: 'iddestino', label: 'Destino', valor: this.state.iddestino, valores: [{ name: "Acreditado", value: "A" }, { name: "Ahorro Voluntario", value: "V" }], onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'idpagare', label: 'Pagar\xE9', readOnly: 'readOnly', valor: this.state.idpagare }),
                                        React.createElement(InputFieldNumber, { id: 'importe', label: 'Importe', readOnly: 'readOnly', valor: this.state.importe, onChange: this.handleInputChange, onBlur: this.handleonBlur })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'ui vertical segment right aligned' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                ' Enviar'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    );

                    pagoInd = React.createElement(
                        'div',
                        null,
                        React.createElement(
                            'h4',
                            { className: this.state.verp === true ? "ui dividing header right aligned" : "hidden" },
                            React.createElement('i', { className: 'window close icon red link', onClick: this.handleCloseW })
                        ),
                        React.createElement(
                            'div',
                            { className: this.state.verp === true ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                'div',
                                { className: 'get pagind' },
                                React.createElement(
                                    'form',
                                    { className: 'ui form formpag', ref: 'form', onSubmit: this.handleSubmitPago.bind(this), method: 'post' },
                                    React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.props.csrf }),
                                    React.createElement(
                                        'div',
                                        { className: ' fields' },
                                        React.createElement('input', { id: 'idcredito', readOnly: 'readOnly', name: 'idcredito', type: 'hidden', value: this.state.idcredito }),
                                        React.createElement(InputField, { cols: 'three wide', id: 'idpagare', label: 'Pagar\xE9', readOnly: 'readOnly', valor: this.state.idpagare }),
                                        React.createElement(Calendar, { cols: 'three wide', id: 'fecha_pagoi', name: 'fecha_pagoi', label: 'Fecha', valor: this.state.fecha_pagoi, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(
                                            'div',
                                            { className: 'ui vertical segment right aligned' },
                                            React.createElement(
                                                'div',
                                                { className: 'field two wide' },
                                                React.createElement(
                                                    'button',
                                                    { className: 'ui bottom primary basic button', type: 'button', name: 'action', onClick: this.handleClickFecha.bind(this) },
                                                    React.createElement('i', { className: 'search icon link', onClick: this.handleClickFecha.bind(this) }),
                                                    ' '
                                                )
                                            )
                                        ),
                                        '                                '
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'five fields' },
                                        '                                    ',
                                        React.createElement(InputFieldNumber, { id: 'capitalactual', label: 'Capital Actual', readOnly: 'readOnly', valor: this.state.capitalactual, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'capital_porvencer', label: 'Capital por vencer', readOnly: 'readOnly', valor: this.state.capital_porvencer, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'intnorvig', label: 'Interes Normales', readOnly: 'readOnly', valor: this.state.intnorvig, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'intmor', label: 'Interes Moratorio', readOnly: 'readOnly', valor: this.state.intmor, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'iva', label: 'IVA', readOnly: 'readOnly', valor: this.state.iva, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'totalamortiza', label: 'Pago a la fecha', readOnly: 'readOnly', valor: this.state.totalamortiza, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'totalliquida', label: 'Pago para liquidar', readOnly: 'readOnly', valor: this.state.totalliquida, onChange: this.handleInputChange })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'four fields' },
                                        React.createElement(InputFieldNumber, { id: 'condonacion', label: 'Condonacion', valor: this.state.condonacion, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'gastos', label: 'Gastos', valor: this.state.gastos, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'efectivo', label: 'Importe Efectivo', readOnly: 'readOnly', valor: this.state.efectivo, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'importepagar', label: 'Monto a pagar', valor: this.state.importepagar, onChange: this.handleInputChange })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'four fields  hidden' },
                                        React.createElement(
                                            'p',
                                            null,
                                            ' ',
                                            React.createElement(
                                                'b',
                                                null,
                                                'Desglose de Pago '
                                            ),
                                            ' '
                                        ),
                                        React.createElement(InputFieldNumber, { id: 'int_n_pago', readOnly: 'readOnly', label: 'Interes Normal', valor: this.state.int_n_pago, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'int_m_pago', readOnly: 'readOnly', label: 'Interes Moratorio', valor: this.state.int_m_pago, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'iva_pago', readOnly: 'readOnly', label: 'IVA', valor: this.state.iva_pago, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'cap_pago', readOnly: 'readOnly', label: 'Capital', valor: this.state.cap_pago, onChange: this.handleInputChange })
                                    ),
                                    React.createElement(
                                        'p',
                                        null,
                                        ' ',
                                        React.createElement(
                                            'b',
                                            null,
                                            'Aplicacion de pago final '
                                        ),
                                        ' '
                                    ),
                                    React.createElement('hr', null),
                                    React.createElement(
                                        'div',
                                        { className: 'four fields' },
                                        React.createElement(InputFieldNumber, { id: 'int_n_pagof', readOnly: 'readOnly', label: 'Interes Normal', valor: this.state.int_n_pagof, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'int_m_pagof', readOnly: 'readOnly', label: 'Interes Moratorio', valor: this.state.int_m_pagof, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'iva_pagof', readOnly: 'readOnly', label: 'IVA', valor: this.state.iva_pagof, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'cap_pagof', readOnly: 'readOnly', label: 'Capital', valor: this.state.cap_pagof, onChange: this.handleInputChange })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'four fields' },
                                        React.createElement(InputFieldNumber, { id: 'pago_alafecha', readOnly: 'readOnly', label: 'Pago a la fecha', valor: this.state.pago_alafecha, onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'pago_talafecha', readOnly: 'readOnly', label: 'Pago total a la fecha', valor: this.state.pago_talafecha, onChange: this.handleInputChange })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: this.state.editar == true ? "ui vertical segment right aligned" : "" },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                ' Enviar'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    );
                } else if (this.props.name == "voluntario") {
                    addAhorro = React.createElement(
                        'div',
                        null,
                        React.createElement(
                            'h4',
                            { className: this.state.ver === true ? "ui dividing header right aligned" : "hidden" },
                            React.createElement('i', { className: 'window close icon red link', onClick: this.handleCloseW })
                        ),
                        React.createElement(
                            'div',
                            { className: this.state.ver === true ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                'div',
                                { className: 'get ahoing' },
                                React.createElement(
                                    'form',
                                    { className: 'ui form formaho', ref: 'form', onSubmit: this.handleSubmitAho.bind(this), method: 'post' },
                                    React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.props.csrf }),
                                    React.createElement(
                                        'div',
                                        { className: 'three fields' },
                                        React.createElement('input', { id: 'idahorrov', readOnly: 'readOnly', name: 'idahorrov', type: 'hidden', value: this.state.idahorrov }),
                                        React.createElement(InputField, { id: 'numero_cuenta', label: 'Cuenta', readOnly: 'readOnly', valor: this.state.numero_cuenta }),
                                        React.createElement(SelectOption, { name: 'movimiento', id: 'movimiento', label: 'Tipo de Movimiento', valor: this.state.movimiento, valores: [{ name: "Deposito", value: "D" }, { name: "Retiro", value: "R" }], onChange: this.handleInputChange }),
                                        React.createElement(InputFieldNumber, { id: 'importe', label: 'Importe', valor: this.state.importe, onChange: this.handleInputChange, onBlur: this.handleonBlur })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'ui vertical segment right aligned' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                ' Enviar'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    );
                } else if (this.props.name == "inversiones") {
                    var nocancelar = null;
                    if (this.state.retiroccheck == "off" || this.state.retiroicheck == "off") {
                        nocancelar = React.createElement(
                            'div',
                            { className: '' },
                            React.createElement(
                                'div',
                                { className: 'fields' },
                                React.createElement(InputField, { id: 'fecha', label: 'Fecha', cols: 'four', readOnly: 'readOnly', valor: this.state.fecha, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: 'importe', cols: 'four', readOnly: 'readOnly', label: 'Importe', valor: this.state.importe, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                React.createElement(SelectOption, { id: 'dias', cols: 'two wide', label: 'Plazo', valor: this.state.dias, valores: this.props.catPlazos, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: 'tasa', readOnly: 'readOnly', label: 'Tasa', cols: 'two', valor: this.state.tasa, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputField, { id: 'interes', cols: 'four', readOnly: 'readOnly', label: 'Interes', valor: this.state.interes, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                'div',
                                { className: 'fields' },
                                React.createElement(InputField, { id: 'fechafin', cols: 'three', readOnly: 'readOnly', label: 'Fecha vencimiento', valor: this.state.fechafin, onChange: this.handleInputChange.bind(this) }),
                                React.createElement(InputFieldNumber, { id: 'total', cols: 'three', readOnly: 'readOnly', label: 'Total', valor: this.state.total, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur })
                            )
                        );
                    }
                    var readOnly = "";
                    if (this.state.incrementocheck == "off") {
                        readOnly = "readOnly";
                    }
                    addInversion = React.createElement(
                        'div',
                        null,
                        React.createElement(
                            'h4',
                            { className: this.state.veri === true ? "ui dividing header right aligned" : "hidden" },
                            React.createElement('i', { className: 'window close icon red link', onClick: this.handleCloseW })
                        ),
                        React.createElement(
                            'div',
                            { className: this.state.veri === true ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                'div',
                                { className: 'get reinver' },
                                React.createElement(
                                    'form',
                                    { className: 'ui form formreinv', ref: 'form', onSubmit: this.handleSubmitReInv.bind(this), method: 'post' },
                                    React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.props.csrf }),
                                    React.createElement('input', { type: 'hidden', name: 'idinversion', value: this.state.idinversion }),
                                    React.createElement(
                                        'div',
                                        { className: 'three fields' },
                                        React.createElement(InputField, { id: 'numeroi', label: 'N\xFAmero', readOnly: 'readOnly', valor: this.state.numeroi }),
                                        React.createElement(InputFieldNumber, { id: 'importei', cols: 'four', readOnly: 'readOnly', label: 'Importe', valor: this.state.importei, onBlur: this.handleonBlur }),
                                        React.createElement(InputFieldNumber, { id: 'interesi', cols: 'four', readOnly: 'readOnly', label: 'Interes', valor: this.state.interesi, onBlur: this.handleonBlur }),
                                        React.createElement(InputFieldNumber, { id: 'totali', cols: 'four', readOnly: 'readOnly', label: 'Total', valor: this.state.totali, onBlur: this.handleonBlur })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'four fields' },
                                        React.createElement(InputCheck, { id: 'incrementocheck', valor: this.state.incrementocheck, onClick: this.handleClickCheck }),
                                        React.createElement(InputFieldNumber, { id: 'incremento', readOnly: readOnly, label: 'Incremento Capital', valor: this.state.incremento, onChange: this.handleInputChange, onBlur: this.handleonBlur })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'three fields' },
                                        React.createElement(InputCheck, { id: 'retiroccheck', valor: this.state.retiroccheck, onClick: this.handleClickCheck }),
                                        React.createElement(InputFieldNumber, { id: 'retiroc', readOnly: 'readOnly', label: 'Retiro Capital', valor: this.state.retiroc, onChange: this.handleInputChange, onBlur: this.handleonBlur }),
                                        React.createElement(SelectOption, { name: 'cretiroc', id: 'cretiroc', label: 'Destino', valor: this.state.cretiroc, valores: [{ name: "Efectivo", value: "E" }, { name: "Ahorro Voluntario", value: "V" }], onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputCheck, { id: 'retiroicheck', valor: this.state.retiroicheck, onClick: this.handleClickCheck }),
                                        React.createElement(InputFieldNumber, { id: 'retiroi', readOnly: 'readOnly', label: 'Retiro Interes', valor: this.state.retiroi, onChange: this.handleInputChange, onBlur: this.handleonBlur }),
                                        React.createElement(SelectOption, { name: 'cretiroi', id: 'cretiroi', label: 'Destino', valor: this.state.cretiroi, valores: [{ name: "Efectivo", value: "E" }, { name: "Ahorro Voluntario", value: "V" }], onChange: this.handleInputChange.bind(this) })
                                    ),
                                    nocancelar,
                                    React.createElement(
                                        'div',
                                        { className: 'ui vertical segment right aligned' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                ' Enviar'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    );
                }
            }
            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                'div',
                null,
                React.createElement(
                    'table',
                    { className: 'ui selectable celled violet table' },
                    React.createElement(
                        'thead',
                        null,
                        React.createElement(Lista, { enca: this.props.enca })
                    ),
                    React.createElement(
                        'tbody',
                        null,
                        rows
                    )
                ),
                addAhorro,
                retAhorro,
                pagoInd,
                addInversion
            );
        }
    }]);

    return TableGlob;
}(React.Component);

var Captura = function (_React$Component22) {
    _inherits(Captura, _React$Component22);

    function Captura(props) {
        var _this29$state;

        _classCallCheck(this, Captura);

        var _this29 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this29.state = (_this29$state = {
            idcredito: 0,
            idcolmena: 0,
            fecha_pago: '',
            catColmenas: [],
            catGrupos: [],
            catPagares: [],
            catPagDis: [],
            catAporta: [],
            catPlazos: [],
            catInteres: [],
            catInstrumento: [],
            instrumento: '',
            disabledaut: '',
            activoap: 0,
            movapor: '',
            tipoapor: '',
            imporapor: 0,
            idbancodetapor: '',
            cheque_refapor: '',
            afavorapor: '',
            idgrupo: 0,
            idpagare: '',
            totalcompara: 0,
            tipo: '',
            nombrei: '',
            nombreg: '',
            nombred: '',
            csrf: "",
            message: "",
            statusmessage: 'ui floating hidden message',
            capital: 0,
            sumatotal: 0,
            totalxpagar: 0,
            stepno: 0,
            stepOpc: 0,
            activoc: 0,
            idahorrodis: 0,
            idcreditodis: 0,
            idacredis: '',
            idpagdis: '',
            colmena: '',
            grupo: '',
            fecha_aprov: '',
            fecha_disper: '',
            monto: 0,
            importeletra: '',

            idacreseg: '',
            nomseg: '',
            idpagseg: '',
            catPagSeg: [],
            colmena_s: '',
            grupo_s: '',
            fecha_aprov_s: '',
            fecha_disper_s: '',
            monto_s: 0,
            importeletra_s: '',
            esquema: '',

            catPagSoc: [],
            idacredita: '',
            idacredi: '',
            acreditadoid: 0,
            acreditaind: 0,
            acreditadis: 0,
            activoin: 0,
            catAhoVol: [],
            catInver: [],
            catCreditos: [],
            activodis: 0,
            idmov: 0,
            saldoini: 0,
            ingresos: 0,
            egresos: 0,
            saldofin: 0,
            grantotalcorte: 0,
            pagototal: 0,
            catDenomina: [],
            movcaja: 0,
            icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon', icons3: 'inverted circular search link icon', icons4: 'inverted circular search link icon',
            catInverNum: [],
            numero: '',
            idcreditoinv: '',
            activoi: 0,
            fecha: '',
            importe: '',
            dias: '',
            tasa: '',
            fechafin: '',
            interes: '',
            total: '',
            idinversion: '',
            veri: false,
            actproy: 0,
            importeproy: '',
            tasaproy: '',
            interesproy: '',
            totalproy: '',
            notavis: false,
            notavis_lista: '',
            idmovdet: 0,

            semana: '',
            promotor: '',
            catPagos: [],
            idmovisible: false,
            fechaconsulta: '',
            btnreversa: false,
            nomovreversa: 0,
            nomovaport: 0,
            nomovgrupal: 0,
            nomovahorro: 0,
            icon: "send icon",
            titulo: "Guardar",
            pregunta: "¿Desea enviar el registro?",
            cierrecajant: false,
            fechacierre: '',

            fecha_pagoi: '',
            catproductos: [],
            fecaini: '',
            fecafin: '',
            visibleahorro: 0,
            visibleFindAcredita: false,
            idpersona: 0,

            fecha_aplica: '',
            hora: '',
            vale: ''
        }, _defineProperty(_this29$state, 'semana', ''), _defineProperty(_this29$state, 'caja', ''), _defineProperty(_this29$state, 'idautoriza', 0), _defineProperty(_this29$state, 'catAutoriza', []), _defineProperty(_this29$state, 'totalautoriza', 0), _defineProperty(_this29$state, 'deposito', 0), _defineProperty(_this29$state, 'status', ''), _this29$state);
        _this29.handleonBlur = _this29.handleonBlur.bind(_this29);
        _this29.handleChangeOpc = _this29.handleChangeOpc.bind(_this29);
        _this29.handleClickUpdate = _this29.handleClickUpdate.bind(_this29);
        _this29.handleChangeTot = _this29.handleChangeTot.bind(_this29);
        _this29.handleCloseW = _this29.handleCloseW.bind(_this29);

        _this29.handleChangeTotal = _this29.handleChangeTotal.bind(_this29);

        return _this29;
    }

    _createClass(Captura, [{
        key: 'handleInputChange',
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));
            if (name === "idcolmena") {
                var _forma6 = this;
                var idcolmena = value;
                var object = {
                    url: base_url + ('/api/CarteraV1/colmenas_grupos/' + idcolmena),
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(object).then(function resolve(response) {
                    var _forma6$setState;

                    _forma6.setState((_forma6$setState = {
                        catGrupos: response.result,

                        idgrupo: 0, catPagares: [],
                        totalxpagar: 9 }, _defineProperty(_forma6$setState, 'totalxpagar', 0), _defineProperty(_forma6$setState, 'totalcompara', 0), _defineProperty(_forma6$setState, 'btnreversa', false), _defineProperty(_forma6$setState, 'nomovreversa', 0), _forma6$setState));
                }, function reject(reason) {
                    var _forma6$setState2;

                    _forma6.setState((_forma6$setState2 = {
                        message: validaError(reason).message,
                        statusmessage: 'ui negative floating message',

                        idgrupo: 0, catPagares: [],
                        totalxpagar: 9 }, _defineProperty(_forma6$setState2, 'totalxpagar', 0), _defineProperty(_forma6$setState2, 'totalcompara', 0), _defineProperty(_forma6$setState2, 'btnreversa', false), _defineProperty(_forma6$setState2, 'nomovreversa', 0), _forma6$setState2));
                });
            } else if (name == "idgrupo") {
                var grupo = value;
                if (grupo == "0" || grupo == "") {
                    this.setState({
                        catPagares: [], totalxpagar: 0, totalcompara: 0,
                        btnreversa: false,
                        nomovreversa: 0
                    });
                } else {
                    this.setState({
                        catPagares: [],
                        grantotal: 0,
                        totalxpagar: 0,
                        totalcompara: 0,
                        btnreversa: false,
                        nomovreversa: 0
                    });
                    var _forma7 = this;
                    var _object = {
                        url: base_url + ('/api/CarteraV1/amortiza/' + grupo),
                        type: 'GET',
                        dataType: 'json'
                    };
                    ajax(_object).then(function resolve(response) {
                        var pagototal = numeral(response.totalxpagar).format('0,0.00');
                        var pagrev = response.pago;
                        _forma7.setState({
                            catPagares: response.result,
                            totalxpagar: response.totalxpagar, totalcompara: 0,
                            pagototal: pagototal
                            //                           message: response.message,
                            //                           statusmessage: 'ui positive floating message '
                        });

                        _forma7.setState({ notavis: response.falta });
                        if (response.falta == true) {
                            _forma7.setState({ notavis_lista: response.falta_lista });
                        }
                        _forma7.autoReset();
                        _forma7.saldoCaja();
                        if (pagrev.length != 0) {
                            _forma7.setState({
                                btnreversa: true,
                                nomovreversa: pagrev[0]['nomov']
                            });
                        }
                    }, function reject(reason) {
                        _forma7.setState({
                            catPagares: [], grantotal: 0, totalcompara: 0, pagototal: 0,
                            message: validaError(reason).message,
                            statusmessage: 'ui negative floating message',
                            notavis: false,
                            notavis_lista: '',
                            btnreversa: false,
                            nomovreversa: 0
                        });
                        _forma7.autoReset();
                    });
                }
            } else if (name == "idpagdis") {
                var idacre = this.state.idacredis;
                var datPag = this.state.catPagDis.filter(function (pag) {
                    return pag.value == value && pag.idacreditado == idacre;
                });
                if (datPag != "") {
                    this.setState({
                        colmena: datPag[0].nomcolmena, grupo: datPag[0].nomgrupo,
                        fecha_aprov: datPag[0].fecha_aprov, fecha_disper: datPag[0].fecha_dispersa, monto: numeral(datPag[0].monto).format('0,0.00'),
                        idahorrodis: datPag[0].idahorro, numero_cuenta: datPag[0].numero_cuenta
                    });
                } else {
                    this.setState({
                        colmena: '', grupo: '',
                        fecha_aprov: '', monto: 0, idahorrodis: 0, numero_cuenta: ''
                    });
                }
            } else if (name == "idpagseg") {
                var _idacre = this.state.idacreseg;
                var _datPag = this.state.catPagSeg.filter(function (pag) {
                    return pag.value == value && pag.idacreditado == _idacre;
                });
                if (_datPag != "") {
                    this.setState({
                        colmena_s: _datPag[0].nomcolmena, grupo_s: _datPag[0].nomgrupo,
                        fecha_aprov_s: _datPag[0].fecha_aprov, fecha_disper_s: _datPag[0].fecha_dispersa, monto_s: numeral(_datPag[0].seguro).format('0,0.00')
                    });
                } else {
                    this.setState({
                        colmena_s: '', grupo_s: '',
                        fecha_aprov_s: '', monto_s: 0
                    });
                }
            } else if (name == "tipoapor" && value == "10") {
                var _forma8 = this;
                var _object2 = {
                    url: base_url + 'api/generalv1/bancosall',
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(_object2).then(function resolve(response) {
                    _forma8.setState({ catBancos: response.result, idbancodetapor: '' });
                    var $form = $('.get.disper form'),
                        Folio = $form.form('set values', { idbancodetapor: '' });
                }, function reject(reason) {
                    var response = validaError(reason);
                });
            } else if (name == "numero") {
                var _idacre2 = this.state.idcreditoinv;
                var datInv = this.state.catInverNum.filter(function (pag) {
                    return pag.value == value && pag.idacreditado == _idacre2;
                });
                if (datInv != "") {
                    var fecha = moment(datInv[0].fecha).format('DD/MM/YYYY');
                    var fecfin = moment(datInv[0].fechafin).format('DD/MM/YYYY');
                    this.setState({
                        fecha: fecha, importe: datInv[0].importe, dias: datInv[0].dias,
                        tasa: datInv[0].tasa, interes: datInv[0].interes, fechafin: fecfin,
                        total: datInv[0].total, idinversion: datInv[0].idinversion
                    });
                } else {
                    this.setState({
                        colmena: '', grupo: '',
                        fecha_aprov: '', monto: 0, idahorrodis: 0, numero_cuenta: ''
                    });
                }
            } else if (name == "movcaja") {
                var _forma9 = this;
                var _object3 = {
                    url: base_url + 'api/carteraV1/getDenomina',
                    type: 'GET',
                    dataType: 'json'
                };
                ajax(_object3).then(function resolve(response) {
                    _forma9.setState({ catDenomina: response.result });
                    var $form = $('.get.disper form'),
                        Folio = $form.form('set values', { idbancodetapor: '' });
                }, function reject(reason) {
                    var response = validaError(reason);
                });
            }

            var tasa = 0;
            if (name == "diasproy" || name == "importeproy") {
                var campo = "";
                var imp = this.state.importeproy;
                if (name == "diasproy") {
                    campo = value;
                } else {
                    campo = this.state.diasproy;
                    if (name == "importeproy") {
                        imp = value;
                    }
                }
                imp = parseFloat(imp.replace(',', ''));
                if (imp != 0) {
                    var tasafind = this.state.catInteres.filter(function (cat) {
                        return imp > cat.monto;
                    });
                    if (tasafind.length > 0) {
                        tasafind = tasafind[tasafind.length - 1];
                        var tasavalor = tasafind[campo];
                        this.setState({ tasaproy: tasavalor });

                        tasa = tasavalor;
                    } else {
                        this.setState({ tasaproy: 0 });
                    }
                } else {
                    this.setState({ tasaproy: 0 });
                }
            }

            if (name == "importeproy" || name == "diasproy" || name == "tasaproy") {
                //            let fecha = $('#fecha').val();
                var importe = this.state.importeproy;
                var dias = this.state.diasproy;

                if (name == "importeproy") {
                    importe = value;
                } else if (name == "diasproy") {
                    dias = value;
                } else if (name == "tasaproy") {
                    tasa = value;
                }
                /*            
                            if (fecha !='' && dias != 0){
                                let fec = moment(fecha, 'DD/MM/YYYY').add(dias, 'day').format('DD/MM/YYYY');
                                this.setState({fechafin: fec});
                            }
                */
                if (importe != 0 && tasa != 0) {
                    var interes = numeral(parseFloat(importe.replace(',', '')) * dias * (tasa / 100) / 360).format('0,0.00');
                    this.setState({ interesproy: interes, totalproy: parseFloat(importe.replace(',', '')) + parseFloat(interes.replace(',', '')) });
                }
            }

            if (name == 'autorizacion') {
                var cat = this.state.catAutoriza;
                var found = cat.find(function (element) {
                    return element['value'] == value;
                });

                if (found['vale'] == null) {
                    this.setState({ new: true });
                } else {
                    this.setState({ new: false });
                }
                this.setState({ fecha_aplica: found['fechao'], deposito: numeral(found['deposito']).format('0,0.00'), vale: found['vale'], semana: found['semana'], caja: found['caja'] });
                if (found['hora'] != '12:12:00') {
                    this.setState({ hora: found['hora'] });
                }
            } else if (name == 'instrumento') {

                var $form = $('.get.disper form'),
                    Folio = $form.form('set values', { instrumento: value });
                this.setState({ hora: '', caja: '', vale: '', deposito: 0.00 });
            }
        }
    }, {
        key: 'handleChangeTotal',
        value: function handleChangeTotal(e, total) {
            var pago = numeral(total).format('0,0.00');
            this.setState({ pagototal: pago });
        }
    }, {
        key: 'handleonBlur',
        value: function handleonBlur(event) {
            var target = event.target;
            var value = target.value;
            var name = target.name;
            var nuevovalor = numeral(value).format('0,0.00');
            this.setState(_defineProperty({}, name, nuevovalor));
        }
    }, {
        key: 'componentWillMount',
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            var iSemana = getWeekNo();
            this.setState({ semana: iSemana });
            $.ajax({
                url: base_url + '/api/CarteraV1/colmenas',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catColmenas: response.catcolmenas
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error');
                }.bind(this)
            });

            $.ajax({
                url: base_url + '/api/GeneralV1/getPlazos',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catPlazos: response.cat_plazos, catInteres: response.cat_interes
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error');
                }.bind(this)
            });

            $.ajax({
                url: base_url + '/api/CarteraV1/getinstrumento',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catInstrumento: response.catinstrumento
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error');
                }.bind(this)
            });

            this.saldoCaja();
            if (esquema == 'ban.') {
                this.getAutoriza();
            }
        }
    }, {
        key: 'getAutoriza',
        value: function getAutoriza() {
            $.ajax({
                url: base_url + '/api/BancosV1/autorizacion',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catAutoriza: response.result
                    });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error');
                }.bind(this)
            });
        }
    }, {
        key: 'saldoCaja',
        value: function saldoCaja() {
            var object = {
                url: base_url + 'api/CarteraV1/getSaldoCaja',
                type: 'GET',
                dataType: 'json'
            };
            var forma = this;
            ajax(object).then(function resolve(response) {
                forma.setState({ cierrecajant: false, fechacierre: '' });
                if (response.result != false) {
                    if (response.result[0].fecfinal == null) {
                        //&&  response.result[0].saldo > 0 gulp 
                        if (forma.state.stepno == -1) {
                            forma.setState({ stepno: 0 });
                        } else {
                            forma.setState({ idmovdet: response.result[0].idmovdet });
                        }

                        if (response.cierrecajant == true) {
                            forma.setState({ cierrecajant: true, fechacierre: response.fechacierre, stepno: 99 });
                        } else if (response.result[0].depositos == null) {
                            // cuando la caja no fue abierta 
                            forma.setState({ stepno: -1, idmovdet: 0 });
                        }
                    } else {
                        forma.setState({ stepno: -1, idmovdet: response.result[0].idmovdet });
                    }

                    forma.setState({
                        idmov: response.result[0].idmov, saldoini: response.result[0].depositos, ingresos: response.result[0].ingresos,
                        egresos: parseFloat(response.result[0].egresos) + parseFloat(response.result[0].retiros), saldofin: response.result[0].saldocaja
                    });

                    //Cambio
                    //                  forma.setState({ stepno: 0 });

                } else {
                    forma.setState({ stepno: -1 });

                    //Cambio                
                    //             forma.setState({ stepno: 0 });

                }
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({ stepno: -1, fechacierre: '', cierrecajant: false });
            });
        }

        /*
                        fecha_pago: {
                            identifier: 'fecha_pago',
                            rules: [
                            {
                                type   : 'empty',
                                prompt : 'Seleccione la fecha'
                            }                           
                            ]
                        },
        
        */

    }, {
        key: 'handleSubmit',
        value: function handleSubmit(event) {
            var rules = [];
            var rulesi = [];
            if (esquema == 'ban.') {
                if (this.state.instrumento != '01') {
                    rules = [{
                        type: 'empty',
                        prompt: 'Requiere un valor'
                    }];
                } else {
                    rulesi = [{
                        type: 'empty',
                        prompt: 'Requiere un valor'
                    }];
                }
            }

            event.preventDefault();
            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    totalcompara: {
                        identifier: 'totalcompara',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    pagototal: {
                        identifier: 'pagototal',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    match: {
                        identifier: 'totalcompara',
                        rules: [{
                            type: 'match[pagototal]',
                            prompt: 'Importes diferentes!'
                        }]
                    },
                    instrumento: {
                        identifier: 'instrumento',
                        rules: rulesi
                    },
                    autorizacion: {
                        identifier: 'autorizacion',
                        rules: rules
                    },

                    fecha_aplica: {
                        identifier: 'fecha',
                        rules: rules
                    },

                    hora: {
                        identifier: 'hora',
                        rules: rules
                    },

                    vale: {
                        identifier: 'vale',
                        rules: rules
                    },
                    semana: {
                        identifier: 'semana',
                        rules: rules
                    },

                    caja: {
                        identifier: 'caja',
                        rules: rules
                    }

                }
            });

            $('.ui.form.formgen').form('validate form');
            var valida = $('.ui.form.formgen').form('is valid');

            //        if (this.state.totalcompara==0 && this.state.totalxpagar==0){
            if (this.state.totalcompara == 0 && this.state.pagototal == 0) {
                valida = false;
            }
            this.setState({ message: '', statusmessage: 'ui message hidden' });
            if (valida == true) {
                var $form = $('.get.soling form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = 'PUT';
                var _forma10 = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/ColmenasV1/add_aplica',
                            type: tipo,
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            _forma10.setState({
                                catPagares: [], idgrupo: 0,
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                totalxpagar: 0, totalcompara: 0, pagototal: 0
                            });
                            _forma10.autoReset();
                            _forma10.saldoCaja();
                            var $form = $('.get.soling form'),
                                Folio = $form.form('set values', { idgrupo: '0' });

                            var nomov = response.nomov;
                            _forma10.setState({ nomovgrupal: nomov });
                            _forma10.PrintMov(nomov);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma10.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            _forma10.autoReset();
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
        key: 'handleSubmitSol',
        value: function handleSubmitSol() {
            event.preventDefault();
            $('.ui.form.formapo').form({
                inline: true,
                on: 'blur',
                fields: {
                    idacredi: {
                        identifier: 'idacredi',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    movapor: {
                        identifier: 'movapor',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    tipoapor: {
                        identifier: 'tipoapor',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    imporapor: {
                        identifier: 'imporapor',
                        rules: [{
                            type: 'number',
                            prompt: 'Requiere un valor numérico'
                        }, {
                            type: 'integer[1..5000]',
                            prompt: 'Requiere un valor mayor a cero'
                        }]
                    },
                    idbancodetapor: {
                        identifier: 'idbancodetapor',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    cheque_refapor: {
                        identifier: 'cheque_refapor',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    afavorapor: {
                        identifier: 'afavorapor',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]

                    }
                }
            });

            $('.ui.form.formapo').form('validate form');
            var valida = $('.ui.form.formapo').form('is valid');
            this.setState({ message: '', statusmessage: 'ui hidden message' });

            if (this.state.stepno == -1) {
                valida = false;
            }

            if (valida == true) {
                var $form = $('.get.aposol form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var _forma11 = this;
                var acreditadoid = this.state.acreditadoid;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/aportasoc/' + acreditadoid),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            _forma11.setState({
                                nombrea: '', cheque_refapor: '', afavorapor: '',
                                imporapor: 0, idacredi: '', acreditadoid: 0,
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ', activoap: 0
                            });
                            var $form = $('.get.soling form'),
                                Folio = $form.form('set values', { idpagdis: '' });
                            _forma11.autoReset();
                            _forma11.saldoCaja();

                            var nomov = response.nomov;
                            _forma11.setState({ nomovaport: nomov });
                            _forma11.PrintMov(nomov);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma11.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            _forma11.autoReset();
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
        key: 'handleSubmitDis',
        value: function handleSubmitDis(event) {
            event.preventDefault();
            $('.ui.form.formdis').form({
                inline: true,
                on: 'blur',
                fields: {
                    idacredis: {
                        identifier: 'idacredis',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    idpagdis: {
                        identifier: 'idpagdis',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    colmena: {
                        identifier: 'colmena',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    grupo: {
                        identifier: 'grupo',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    fecha_aprov: {
                        identifier: 'fecha_aprov',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    fecha_disper: {
                        identifier: 'fecha_disper',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    numero_cuenta: {
                        identifier: 'numero_cuenta',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    monto: {
                        identifier: 'monto',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    }
                }
            });
            $('.ui.form.formdis').form('validate form');
            var valida = $('.ui.form.formdis').form('is valid');
            var idacre = this.state.idacredis;
            var idpag = this.state.idpagdis;
            var datPag = this.state.catPagDis.filter(function (pag) {
                return pag.value == idpag && pag.idacreditado == idacre;
            });
            if (datPag == "") {
                this.setState({
                    colmena: '', grupo: '',
                    fecha_aprov: '', fecha_disper: '', monto: 0, numero_cuenta: ''
                });
                valida = false;
            }

            if (this.state.monto == 0) {
                valida = false;
            }

            var saldofin = $('#saldofin').val();

            if (parseFloat(this.state.monto) > parseFloat(saldofin)) {
                this.setState({
                    message: 'Importe del crédito es mayor al saldo en Caja!',
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset();
                return;
            }

            this.setState({ message: '', statusmessage: 'ui hidden message' });

            if (valida == true) {
                var $form = $('.get.disper form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var _forma12 = this;
                var idcredito = this.state.idcreditodis;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/cred_entrega/' + idcredito),
                            type: 'PUT',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {

                            var idpag = _forma12.state.idpagdis;
                            var catNew = _forma12.state.catPagDis.filter(function (pag) {
                                return pag.value != idpag;
                            });

                            _forma12.setState({
                                idpagare: '',
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                catPagDis: catNew, monto: 0, colmena: '', grupo: '', fecha_aprov: '', fecha_disper: '',
                                idahorrodis: 0, numero_cuenta: ''
                            });
                            var $form = $('.get.soling form'),
                                Folio = $form.form('set values', { idpagdis: '' });
                            _forma12.autoReset();
                            _forma12.saldoCaja();
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma12.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            _forma12.autoReset();
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
        key: 'handleSubmitSeg',
        value: function handleSubmitSeg(event) {
            event.preventDefault();
            $('.ui.form.formseg').form({
                inline: true,
                on: 'blur',
                fields: {
                    idacredis: {
                        identifier: 'idacreseg',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    idpagdis: {
                        identifier: 'idpagseg',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    colmena: {
                        identifier: 'colmena_s',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    grupo: {
                        identifier: 'grupo_s',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    fecha_aprov: {
                        identifier: 'fecha_aprov_s',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    fecha_disper: {
                        identifier: 'fecha_disper_s',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    monto: {
                        identifier: 'monto_s',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    }
                }
            });
            $('.ui.form.formseg').form('validate form');
            var valida = $('.ui.form.formseg').form('is valid');
            var idacre = this.state.idacreseg;
            var idpag = this.state.idpagseg;
            var datPag = this.state.catPagSeg.filter(function (pag) {
                return pag.value == idpag && pag.idacreditado == idacre;
            });
            if (datPag == "") {
                this.setState({
                    colmena_s: '', grupo_s: '',
                    fecha_aprov_s: '', fecha_disper_s: '', monto_s: 0
                });
                valida = false;
            }

            if (this.state.monto_s == 0) {
                valida = false;
            }

            this.setState({ message: '', statusmessage: 'ui hidden message' });
            if (valida == true) {
                var $form = $('.get.disseg form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var _forma13 = this;
                var idcredito = this.state.idpagseg;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/seguros/' + idcredito),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {

                            var idpag = _forma13.state.idpagSeg;
                            var catNew = _forma13.state.catPagSeg.filter(function (pag) {
                                return pag.value != idpag;
                            });

                            _forma13.setState({
                                idpagare: '',
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                catPagSeg: catNew, monto_s: 0, colmena_s: '', grupo_s: '', fecha_aprov_s: '', fecha_disper_s: ''
                            });
                            var $form = $('.get.solseg form'),
                                Folio = $form.form('set values', { idpagseg: '' });
                            _forma13.autoReset();
                            _forma13.saldoCaja();

                            var nomov = response.nomov;
                            _forma13.PrintMov(nomov);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma13.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            _forma13.autoReset();
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
        key: 'PrintMov',
        value: function PrintMov(nomov) {
            var link = 'ReportV1/PrintMov/' + nomov;
            var a = document.createElement('a');
            a.href = base_url + ('api/' + link);
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: 'handleSubmitInv',
        value: function handleSubmitInv(event) {
            event.preventDefault();
            $('.ui.form.forminv').form({
                inline: true,
                on: 'blur',
                fields: {
                    fecha: {
                        identifier: 'fecha',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione la fecha'
                        }]
                    },
                    importe: {
                        identifier: 'importe',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    dias: {
                        identifier: 'dias',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    tasa: {
                        identifier: 'tasa',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    interes: {
                        identifier: 'interes',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    fechafin: {
                        identifier: 'fechafin',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    total: {
                        identifier: 'total',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    }
                }
            });

            $('.ui.form.forminv').form('validate form');
            var valida = $('.ui.form.forminv').form('is valid');
            this.setState({ message: '', statusmessage: 'ui hidden message' });

            if (valida == true) {
                var $form = $('.get.solinver form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var _forma14 = this;
                var idacredita = this.state.idcreditoinv;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {

                        var object = {
                            url: base_url + ('api/CarteraV1/update_inversion/' + idacredita),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            var num = _forma14.state.numero;
                            var catNew = _forma14.state.catInverNum.filter(function (pag) {
                                return pag.value != num;
                            });
                            _forma14.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ', fecha: '', importe: '',
                                dias: '', tasa: '', interes: '', fechafin: '', total: '', numero: '', idinversion: '', activo: 0,
                                catInverNum: catNew, activoi: 0
                            });
                            var $form = $('.get.solinver form'),
                                Folio = $form.form('set values', { numero: '' });
                            _forma14.autoReset();
                            //antes valiar si exta la busqueda del idsoico 
                            //                    forma.obtenerDatos(3);
                            _forma14.saldoCaja();
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma14.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            _forma14.autoReset();
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
        key: 'handleSubmitCorte',
        value: function handleSubmitCorte() {
            event.preventDefault();
            var rulesCierre = [];
            if (this.state.movcaja == "C") {
                rulesCierre = {
                    identifier: 'saldofin',
                    rules: [{
                        type: 'match[grantotalcorte]',
                        prompt: 'Importes diferentes!'
                    }]
                };
            }
            $('.ui.form.formcaj').form({
                inline: true,
                on: 'blur',
                fields: {
                    movcaja: {
                        identifier: 'movcaja',
                        rules: [{
                            type: 'empty',
                            prompt: 'Seleccione un movimiento'
                        }]
                    },
                    saldofin: {
                        identifier: 'saldofin',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    grantotalcorte: {
                        identifier: 'grantotalcorte',
                        rules: [{
                            type: 'empty',
                            prompt: 'Requiere un valor'
                        }]
                    },
                    match: rulesCierre
                }
            });

            $('.ui.form.formcaj').form('validate form');
            var valida = $('.ui.form.formcaj').form('is valid');
            var mensajeCorte = "Datos incompletos";

            // Cierre de caja en ceros 
            if (this.state.movcaja == "C" && this.state.saldofin == 0 && this.state.grantotalcorte == 0) {} else if (this.state.saldofin == 0 || this.state.grantotalcorte == 0 || this.state.movcaja == "0") {
                valida = false;
            }

            if (this.state.cierrecajant == true) {
                if (this.state.movcaja == "N") {
                    valida = false;
                    mensajeCorte = "Movimiento incorrecto, genere el Cierre de Caja!";
                }
            } else {
                if (this.state.movcaja == "N" && this.state.saldofin == this.state.grantotalcorte) {
                    valida = false;
                    mensajeCorte = "Importes iguales, movimiento que aplica es un Cierre de Caja!";
                }
            }
            this.setState({ message: '', statusmessage: 'ui hidden message' });
            if (valida == true) {
                var $form = $('.get.discaja form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var _forma15 = this;
                var idmov = this.state.idmov;
                var fechacierre = this.state.fechacierre;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/CarteraV1/add_caja/' + idmov),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token,
                                data: allFields,
                                fechacierre: fechacierre
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            _forma15.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ', movcaja: '0', idmovdet: response.registros
                            });
                            if (_forma15.state.movcaja == "C") {
                                _forma15.setState({
                                    message: response.message, step: -1
                                });
                            } else {
                                _forma15.setState({
                                    message: 'Nota de Cierre realizado exitosamente!'
                                });
                            }
                            _forma15.setState({
                                csrf: response.newtoken,
                                statusmessage: 'ui positive floating message ', movcaja: '0'
                            });
                            var $form = $('.get.discaja form'),
                                Folio = $form.form('set values', { movcaja: '0' });
                            _forma15.autoReset();
                            _forma15.saldoCaja();
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma15.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });
                            _forma15.autoReset();
                        });
                    }
                }).modal('show');
            } else {
                this.setState({
                    message: mensajeCorte,
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset();
            }
        }
    }, {
        key: 'handleClickItem',
        value: function handleClickItem(val) {
            if (this.state.stepno >= 0) {
                if (this.state.cierrecajant == true) {
                    this.setState({
                        stepno: 99
                    });

                    //Cambio

                    /*
                                                    this.setState({
                                                    stepno: val
                                                })
                    
                    */
                } else {
                    this.setState({
                        stepno: val
                    });
                }
                this.setState({
                    message: '', statusmessage: 'ui floating hidden message'
                });

                if (val == 4) {
                    if (this.state.catCreditos.length == 0) {
                        this.findCredxDis(2);
                    }
                }
            }
        }
    }, {
        key: 'findCredxDis',
        value: function findCredxDis(e) {
            console.log('aqui');
            $.ajax({
                url: base_url + '/api/CarteraV1/getVencimientos/' + e,
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    this.setState({
                        catCreditos: response.credito
                    });
                }.bind(this),
                error: function (xhr, status, err) {}.bind(this)
            });
        }
    }, {
        key: 'handleCloseW',
        value: function handleCloseW() {
            this.setState({ veri: false });
        }
    }, {
        key: 'PrintMov',
        value: function PrintMov(nomov) {
            var link = 'ReportV1/PrintMov/' + nomov;
            var a = document.createElement('a');
            a.href = base_url + ('api/' + link);
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: 'obtenerDatos',
        value: function obtenerDatos(val) {
            var idacreditado = this.state.acreditaind;

            var page = "getCreditos/";
            if (val == 2) {
                this.setState({ visibleahorro: 0 });
                page = "getCtaAhorros/";
            } else if (val == 3) {
                page = "getInversiones/";
            }

            var object = {
                url: base_url + ('api/CarteraV1/' + page + idacreditado),
                type: 'GET',
                dataType: 'json'
            };
            var forma = this;
            ajax(object).then(function resolve(response) {
                if (val == 1) {
                    forma.setState({ catPagSoc: response.result });
                } else if (val == 2) {
                    forma.setState({ catAhoVol: response.result });
                } else if (val == 3) {
                    forma.setState({ catInver: response.result });
                }
            }, function reject(reason) {
                if (val == 1) {
                    forma.setState({ catPagSoc: [] });
                } else if (val == 2) {
                    forma.setState({ catAhoVol: [] });
                } else if (val == 3) {
                    forma.setState({ catInver: [] });
                }
                forma.setState({
                    message: validaError(reason).message,
                    statusmessage: 'ui negative floating message'
                });
            });
        }
    }, {
        key: 'handleClickUpdate',
        value: function handleClickUpdate(val, message, statusmessage, newtoken) {
            if (newtoken != undefined) {
                this.setState({
                    csrf: newtoken
                });
            }
            this.setState({
                message: message, statusmessage: statusmessage
            });
            this.autoReset();
            if (val != 0) {
                this.obtenerDatos(val);
                this.saldoCaja();
            }
        }
    }, {
        key: 'handleClickOpc',
        value: function handleClickOpc(val) {
            if (this.state.idacredita != "") {
                this.obtenerDatos(val);
            }
        }
    }, {
        key: 'handleSubmitind',
        value: function handleSubmitind() {}
    }, {
        key: 'handleReversa',
        value: function handleReversa() {
            if (this.state.btnreversa == true && this.state.nomovreversa != 0) {
                var idgrupo = this.state.idgrupo;
                var nomovreversa = this.state.nomovreversa;
                var $form = $('.get.discaja form'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                this.setState({
                    icon: "undo icon",
                    titulo: "Reversa",
                    pregunta: "¿Desea dar reversa al registro (" + nomovreversa + ")?"
                });
                var _forma16 = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + ('api/ColmenasV1/reversa_aplica/' + idgrupo + '/' + nomovreversa),
                            type: 'POST',
                            dataType: 'json',
                            data: {
                                csrf_bancomunidad_token: token
                            }
                        };
                        ajax(object).then(function resolve(response) {
                            _forma16.setState({
                                catPagares: [], idgrupo: 0,
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui positive floating message ',
                                totalxpagar: 0, totalcompara: 0, pagototal: 0,
                                btnreversa: false,
                                nomovreversa: 0,
                                icon: "send icon",
                                titulo: "Guardar",
                                pregunta: "¿Desea enviar el registro?"
                            });
                            _forma16.autoReset();
                            _forma16.saldoCaja();
                            var $form = $('.get.soling form'),
                                Folio = $form.form('set values', { idgrupo: '0' });
                        }, function reject(reason) {
                            var response = validaError(reason);
                            _forma16.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message',
                                icon: "send icon",
                                titulo: "Guardar",
                                pregunta: "¿Desea enviar el registro?"

                            });
                            _forma16.autoReset();
                        });
                    },
                    onDeny: function onDeny() {
                        _forma16.setState({
                            icon: "send icon",
                            titulo: "Guardar",
                            pregunta: "¿Desea enviar el registro?"
                        });
                    }
                }).modal('show');
            }
        }
    }, {
        key: 'handleChangeTot',
        value: function handleChangeTot(grantotalcorte) {
            this.setState({ grantotalcorte: grantotalcorte });
        }
    }, {
        key: 'handleChangeOpc',
        value: function handleChangeOpc(e, stepOpc) {
            this.setState({ stepOpc: stepOpc });
        }
    }, {
        key: 'handleFind',
        value: function handleFind(e, value, name) {
            var idacreditado = 0;
            var page = "";
            this.setState(_defineProperty({}, name, value));
            if (name == "idacredi" || name == "idacredita") {
                page = 'findAcre/' + value;
            } else if (name == "idacredis") {
                page = 'getCreditosxDis/' + value;
            } else if (name == "idacreseg") {
                page = 'getCreditosxSeg/' + value;
            } else if (name == "idacreinv") {
                page = 'getInversionxDis/' + value;
            }
            if (page != "") {
                var icons = 'spinner circular inverted blue loading icon';
                var _forma17 = this;
                var object = {
                    url: base_url + ('api/carteraV1/' + page),
                    type: 'GET',
                    dataType: 'json'
                };
                if (name == "idacredi") {
                    this.setState({ icons1: icons });
                } else if (name == "idacredita") {
                    this.setState({ icons2: icons });
                } else if (name == "idacredis") {
                    this.setState({ icons3: icons, idpagdis: '', catPagDis: [] });
                } else if (name == "idacreseg") {
                    this.setState({ icons31: icons, idpagseg: '', catPagSeg: [] });
                } else if (name == "idacreinv") {
                    this.setState({ icons4: icons });
                }
                ajax(object).then(function resolve(response) {
                    var _forma17$setState;

                    var named = "nombred";
                    var idacre = "acreditadis";
                    if (name == "idacredita") {
                        named = "nombre";
                        idacre = "acreditaind";
                    } else if (name == "idacredi") {
                        named = "nombrea";
                        idacre = "acreditadoid";
                    } else if (name == "idacreseg") {
                        named = "nomseg";
                        idacre = "acreditadoid";
                    } else if (name == "idacreinv") {
                        named = "nombrei";
                        idacre = "acreditainv";
                    }
                    _forma17.setState((_forma17$setState = {}, _defineProperty(_forma17$setState, named, response.result[0].nombre), _defineProperty(_forma17$setState, idacre, response.result[0].acreditadoid), _defineProperty(_forma17$setState, 'message', ''), _defineProperty(_forma17$setState, 'statusmessage', 'ui message hidden'), _defineProperty(_forma17$setState, 'icons1', 'inverted circular search link icon'), _defineProperty(_forma17$setState, 'icons2', 'inverted circular search link icon'), _defineProperty(_forma17$setState, 'icons3', 'inverted circular search link icon'), _defineProperty(_forma17$setState, 'icons4', 'inverted circular search link icon'), _forma17$setState));
                    if (name == "idacredi") {
                        _forma17.setState({ activoap: 1 });
                    }

                    if (name == "idacredis") {
                        _forma17.setState({ catPagDis: response.result, idcreditodis: response.result[0].idcredito, activoc: 1 });
                    }
                    if (name == "idacreseg") {
                        //idcreseg: response.result[0].idcredito,
                        _forma17.setState({ catPagSeg: response.result, activoc: 1, esquema: response.result[0].esquema });
                    }
                    if (name == "idacredita") {
                        _forma17.setState({
                            activoin: 1,
                            idcredito: response.result[0].idcredito,
                            idpersona: response.result[0].idpersona,
                            status: response.result[0].lock_cuenta
                        });
                    }

                    if (name == "idacreinv") {
                        _forma17.setState({ catInverNum: response.result, idcreditoinv: response.result[0].idacreditado, activoi: 1 });
                        var $form = $('.get.solinver form'),
                            Folio = $form.form('set values', { numero: '' });
                    }
                }, function reject(reason) {
                    var response = validaError(reason);
                    if (name == "idacredis") {
                        _forma17.setState({
                            catPagDis: [],
                            colmena: '', grupo: '', fecha_aprov: '', fecha_disper: '', monto: 0, numero_cuenta: '', importeletra: '',
                            nombred: '', message: 'Acreditado sin pagares por dispersar',
                            statusmessage: 'ui negative floating message'
                        });
                        var $form = $('.get.disper form'),
                            Folio = $form.form('set values', { idpagare: '' });
                    } else if (name == "idacreinv") {
                        _forma17.setState({
                            catInverNum: [],
                            nombrei: '', message: 'Acreditado sin pagares por dispersar',
                            statusmessage: 'ui negative floating message',
                            fecha: '', importe: '', dias: '', tasa: '', fechafin: '', interes: '', total: '', idinversion: ''
                        });
                        var $form = $('.get.solinver form'),
                            Folio = $form.form('set values', { numero: '' });
                    } else {
                        _forma17.setState({
                            message: response.message,
                            statusmessage: 'ui negative floating message'
                        });
                        if (name == "idacredi") {
                            _forma17.setState({
                                nombrea: ''
                            });
                        }
                    }
                    _forma17.setState({
                        icons1: 'inverted circular search link icon', icons2: 'inverted circular search link icon', icons3: 'inverted circular search link icon',
                        icons4: 'inverted circular search link icon'
                    });
                    _forma17.autoReset();
                });
            }
        }
    }, {
        key: 'autoReset',
        value: function autoReset() {
            var self = this;
            window.clearTimeout(self.timeout);
            this.timeout = window.setTimeout(function () {
                self.setState({ message: '', statusmessage: 'ui message hidden' });
            }, 5000);
        }
    }, {
        key: 'handleButton',
        value: function handleButton(opc) {
            var link = void 0;

            if (opc == 50) {
                this.saldoCaja();
                return;
            }

            if (opc == -10) {
                this.setState({ activoap: 0, imporapor: '', cheque_refapor: '', afavorapor: '' });
                return;
            } else if (opc == -11) {
                var id = this.state.idacredi;
                if (id != '') {
                    link = 'ReportV1/aportacertif/' + id;
                } else {
                    return;
                }
            } else if (opc == -12) {
                var nomov = this.state.nomovaport;
                if (nomov !== 0) {
                    this.PrintMov(nomov);
                }
                return;
            } else if (opc == 200) {
                this.setState({ visibleahorro: 0 });
                return;
            } else if (opc == 201 || opc == 202) {

                var $form = $('.get.ind form'),

                // get one value
                idahorros = $form.form('get value', 'idAhorros');

                var opciones = '';
                idahorros.forEach(function (element) {
                    if (element != undefined) {
                        opciones += element + '-';
                    }
                });

                var fechaconsulta = "0";
                var fechafin = "0";
                if ($('#fecaini').val() != '') {
                    var extraer = $('#fecaini').val().split('/');
                    var fec = new Date(extraer[2], extraer[1] - 1, extraer[0]);
                    fechaconsulta = moment(fec).format('DDMMYYYY');
                }
                if ($('#fecafin').val() != '') {
                    var _extraer = $('#fecafin').val().split('/');
                    var _fec = new Date(_extraer[2], _extraer[1] - 1, _extraer[0]);
                    fechafin = moment(_fec).format('DDMMYYYY');
                }

                var _id = $('#idacredita').val();
                var pantalla = opc == 202 ? "/p/" : "";
                //            let opciones = [];
                link = 'ReportV1/cuenta_acre' + pantalla + '?id=' + _id + '&cuentas=' + opciones + '&fechai=' + fechaconsulta + '&fechaf=' + fechafin;
            } else if (opc == 20) {
                var _nomov = this.state.nomovgrupal;
                if (_nomov != 0) {
                    this.PrintMov(_nomov);
                }
                return;
            } else if (opc == 24) {
                var _nomov2 = this.state.nomovahorro;
                if (_nomov2 != 0) {
                    this.PrintMov(_nomov2);
                }
                return;
            } else if (opc == 21) {

                var _id2 = this.state.idacredita; //idcredito;
                link = 'ReportD1/pdf_ahorro/' + _id2 + '/a';
            } else if (opc == 22) {
                this.setState({ visibleahorro: 1 });
                if (this.state.catproductos.length == 0) {
                    var _forma18 = this;
                    var object = {
                        url: base_url + 'api/CarteraV1/getProductos',
                        type: 'GET',
                        dataType: 'json'
                    };
                    ajax(object).then(function resolve(response) {

                        _forma18.setState({
                            catproductos: response.catproductos
                        });
                    }, function reject(reason) {
                        var response = validaError(reason);
                    });
                }
                return;
            } else if (opc == 23) {
                var _id3 = $('#idacredita').val();
                var _opciones = [];
                link = 'ReportV1/cuenta_acre_resumen?id=' + _id3 + '&cuentas=' + _opciones;

                //            return;
            }
            if (opc == 30) {
                this.setState({ activoin: 0, catAhoVol: [], catPagSoc: [], catInver: [] });
                return;
            } else if (opc == 31) {
                var _id4 = this.state.idpersona;
                if (_id4 != 0) {
                    link = 'ReportV1/solcreditopdf/' + _id4;
                } else {
                    return;
                }
            }

            if (opc == 40) {
                this.setState({ catPagDis: [], nombred: '', activoc: 0, colmena: '', grupo: '', fecha_aprov: '', fecha_disper: '', monto: '', importeletra: '', numero_cuenta: '' });
                var $form = $('.get.disper form'),
                    Folio = $form.form('set values', { idpagdis: '' });

                return;
            } else if (opc == 43) {
                if (this.state.activodis == 1) {
                    this.setState({ activodis: 0 });
                } else {
                    this.setState({ activodis: 1 });
                }
                return;
            } else if (opc == 44) {
                this.findCredxDis(2);
                return;
            }

            if (opc == 410) {
                this.setState({ catInverNum: [], activoi: 0, fecha: '', importe: '', dias: '', tasa: '', fechafin: '', interes: '', total: '', idinversion: '' });
                var $form = $('.get.solinver form'),
                    Folio = $form.form('set values', { numero: '' });

                return;
            }
            // Proyeccion de intereses
            if (opc == 414) {
                if (this.state.actproy == 0) {
                    this.setState({ actproy: 1 });
                } else {
                    this.setState({ actproy: 0 });
                }
                return;
            }

            if (opc == 51) {
                if (this.state.idmovdet != 0) {
                    var _id5 = this.state.idmovdet;
                    link = 'ReportV1/bovedarep/' + _id5;
                } else {
                    return;
                }
            } else if (opc == 52) {
                link = 'ReportV1/cajamov';

                if (this.state.idmovisible == true && $('#fechaconsulta').val() != '') {
                    var _fechaconsulta = "0";
                    var _id6 = 0;
                    if ($('#fechaconsulta').val() != '') {
                        var _extraer2 = $('#fechaconsulta').val().split('/');
                        var _fec2 = new Date(_extraer2[2], _extraer2[1] - 1, _extraer2[0]);
                        _fechaconsulta = moment(_fec2).format('DDMMYYYY');
                    }
                    link = 'ReportV1/cajamov/' + _id6 + '/' + _fechaconsulta;
                }
            } else if (opc == 53) {

                var idvis = false;
                if (this.state.idmovisible == false) {
                    idvis = true;
                }
                this.setState({ idmovisible: idvis });
                return;
            } else if (opc == 54) {
                var visibleFindAcredita = this.state.visibleFindAcredita;
                this.setState({
                    visibleFindAcredita: !visibleFindAcredita
                });

                return;
            }
            console.log(link);
            var a = document.createElement('a');
            a.href = base_url + ('api/' + link);
            a.target = '_blank';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }
    }, {
        key: 'render',
        value: function render() {
            var _this30 = this;

            var divStyle = {
                position: 'fixed',
                top: '80px',
                left: 'auto'
            };
            var saldoinic = numeral(this.state.saldoini).format('0,0.00');
            var egresosc = numeral(this.state.egresos).format('0,0.00');
            var ingresosc = numeral(this.state.ingresos).format('0,0.00');
            var saldofinc = numeral(this.state.saldofin).format('0,0.00');

            var complemento = null;
            if (this.state.movapor == "R" && this.state.tipoapor == "10") {
                //Cheque
                complemento = React.createElement(
                    'div',
                    { className: 'fields' },
                    React.createElement(SelectOption, { id: 'idbancodetapor', cols: 'three wide', label: 'Banco', valor: this.state.idbancodetapor, valores: this.state.catBancos, onChange: this.handleInputChange.bind(this) }),
                    React.createElement(InputField, { id: 'cheque_refapor', cols: 'two wide', label: 'No de Cheque', valor: this.state.cheque_refapor, onChange: this.handleInputChange.bind(this) }),
                    React.createElement(InputField, { id: 'afavorapor', cols: 'eleven wide', label: 'A favor de', valor: this.state.afavorapor, onChange: this.handleInputChange.bind(this) })
                );
            } else if (this.state.movapor == "D" && this.state.tipoapor == "10") {
                //Cheque
                complemento = React.createElement(
                    'div',
                    { className: 'fields' },
                    React.createElement(InputField, { id: 'cheque_refapor', cols: 'three wide', label: 'Referencia', valor: this.state.cheque_refapor, onChange: this.handleInputChange.bind(this) }),
                    React.createElement(InputField, { id: 'afavorapor', cols: 'six wide', label: 'Banco', valor: this.state.afavorapor, onChange: this.handleInputChange.bind(this) })
                );
            }
            var corteOk = null;
            if (this.state.saldoini != 0 && this.state.saldofin == 0) {
                corteOk = React.createElement(
                    'div',
                    null,
                    'Cierre realizado!'
                );
            }

            var classaction = "";
            if (this.state.activodis == 0) {
                classaction = "ui hidden";
            } else {
                classaction = "ui visible autumn leaf transition ";
            }

            var vales = null;
            var data0 = null;
            var data1 = null;
            var data2 = null;
            var data3 = null;
            var data4 = null;
            var data5 = null;
            var data6 = null;
            var data7 = null;
            var status = '';
            if (this.state.status == true || this.state.status == 't') {
                status = " (Cuentas Bloqueadas) ";
            }

            var disabledaut = '';
            /*
                    if (esquema == 'ban.' || esquema == 'BAN.') {
                        if (this.state.instrumento == '01') {
                            disabledaut = 'readOnly'
                        }
                        data0 = <SelectDropDown name="instrumento" cols="three wide" id="instrumento" label="Movimiento" valor={this.state.instrumento} valores={this.state.catInstrumento} onChange={this.handleInputChange.bind(this)} />
            
                        data1 = <SelectDropDown name="autorizacion" readOnly={disabledaut} cols="three wide" id="autorizacion" label="Autorización" valor={this.state.idautoriza} valores={this.state.catAutoriza} onChange={this.handleInputChange.bind(this)} />
                        data2 = <InputField id="fecha_aplica" label="Fecha" readOnly="readOnly" cols="two wide" valor={this.state.fecha_aplica} onChange={this.handleInputChange.bind(this)} />
                        data3 = <InputField id="hora" label="Hora" readOnly={disabledaut} cols="two wide" valor={this.state.hora} onChange={this.handleInputChange.bind(this)} />
                        data4 = <InputField id="semana" readOnly={disabledaut} label="Semana" cols="two wide" valor={this.state.semana} onChange={this.handleInputChange.bind(this)} />
                        data5 = <InputField id="caja" readOnly={disabledaut} label="Caja" cols="two wide" valor={this.state.caja} onChange={this.handleInputChange.bind(this)} />
                        data6 = <InputFieldNumber readOnly="readOnly" id="deposito" label="Deposito" valor={this.state.deposito} onChange={this.handleInputChange.bind(this)} onBlur={this.handleonBlur} />
                        data7 = <InputFieldNumber readOnly="readOnly" id="totalautoriza" label="Total Autorizacion" valor={this.state.totalautoriza} onChange={this.handleInputChange.bind(this)} onBlur={this.handleonBlur} />
                        vales = <InputField readOnly={disabledaut} id="vale" label="Vale" cols="two wide" valor={this.state.vale} onChange={this.handleInputChange.bind(this)} />
                    }
            */
            return React.createElement(
                'div',
                { className: 'ui grid' },
                React.createElement(
                    'div',
                    { className: 'two wide column' },
                    React.createElement(
                        'div',
                        { className: 'overlay fixed', style: divStyle },
                        React.createElement(
                            'div',
                            { className: 'ui labeled icon vertical menu letra' },
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 1) },
                                React.createElement('i', { className: 'check mark box icon grey color' }),
                                ' A. Social'
                            ),
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 2) },
                                React.createElement('i', { className: 'cubes icon grey color' }),
                                ' Colmenas'
                            ),
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 3) },
                                React.createElement('i', { className: 'cube icon grey color' }),
                                ' Individual'
                            ),
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 4) },
                                React.createElement('i', { className: 'user icon grey color' }),
                                ' Cr\xE9ditos'
                            ),
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 41) },
                                React.createElement('i', { className: 'suitcase icon grey color' }),
                                ' Inversiones'
                            ),
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 42) },
                                React.createElement('i', { className: 'world icon grey color' }),
                                ' Remesas'
                            ),
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 43) },
                                React.createElement('i', { className: 'umbrella icon grey color' }),
                                ' Seguros'
                            ),
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 44) },
                                React.createElement('i', { className: 'talk outline icon grey color' }),
                                ' Servicios'
                            ),
                            React.createElement(
                                'a',
                                { className: 'item', onClick: this.handleClickItem.bind(this, 5) },
                                React.createElement('i', { className: 'calculator icon grey color' }),
                                ' Corte Caja'
                            )
                        )
                    )
                ),
                React.createElement(
                    'div',
                    { className: 'thirteen wide column' },
                    React.createElement(
                        'div',
                        { className: this.state.stepno == -1 ? "main ui intro top" : "main ui intro hidden" },
                        React.createElement(
                            'div',
                            { className: 'ui segment vertical' },
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'div',
                                    { className: 'ui basic icon buttons' },
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Devoluci\xF3n a Caja' },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 51) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Movimiento del d\xEDa' },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 52) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Movimientos' },
                                        React.createElement('i', { className: 'list icon', onClick: this.handleButton.bind(this, 53) })
                                    )
                                )
                            ),
                            React.createElement('br', null),
                            React.createElement(
                                'div',
                                { className: 'fields' },
                                React.createElement(Calendar, { visible: this.state.idmovisible, name: 'fechaconsulta', label: 'Fecha consulta: ', valor: this.state.fechaconsulta, onChange: this.handleInputChange.bind(this) })
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui inverted red segment' },
                                React.createElement(
                                    'div',
                                    { className: 'ui header' },
                                    'Caja cerrada.'
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'ui subheader' },
                                    'Es necesario abrirla para realizar operaciones. Error CX001'
                                ),
                                corteOk
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: this.state.statusmessage },
                        React.createElement(
                            'p',
                            null,
                            React.createElement(
                                'b',
                                null,
                                this.state.message
                            )
                        ),
                        React.createElement('i', { className: 'close icon', onClick: function onClick(event) {
                                return _this30.setState({ message: '', statusmessage: 'ui message hidden' });
                            } })
                    ),
                    React.createElement(
                        'div',
                        { className: this.state.stepno === 1 ? "main ui intro  top" : "main ui intro  hidden" },
                        React.createElement(
                            'div',
                            { className: 'ui segment vertical' },
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'h3',
                                    { className: 'ui rojo header' },
                                    'Aportaci\xF3n Social'
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui secondary menu' },
                                React.createElement(
                                    'div',
                                    { className: 'ui basic icon buttons' },
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Nuevo Registro' },
                                        React.createElement('i', { className: 'plus square outline icon', onClick: this.handleButton.bind(this, -10) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Certificado de aportaci\xF3n' },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, -11) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': '\xDAltimo movimiento', onClick: this.handleButton.bind(this, -12) },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, -12) })
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'right menu' },
                                    React.createElement(
                                        'div',
                                        { className: 'item ui fluid category search' },
                                        React.createElement(
                                            'div',
                                            { className: 'ui icon input' },
                                            React.createElement('input', { className: 'prompt mayuscula', type: 'text', placeholder: 'Buscar Nombre' }),
                                            React.createElement('i', { className: 'search link icon' })
                                        ),
                                        React.createElement('div', { className: 'results' })
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'get aposol' },
                            React.createElement(
                                'form',
                                { className: 'ui form formapo', ref: 'form', onSubmit: this.handleSubmitSol.bind(this), method: 'post' },
                                React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                                React.createElement(
                                    'div',
                                    { className: this.state.activoap === 1 ? "disablediv" : "" },
                                    React.createElement(
                                        'div',
                                        { className: 'two fields' },
                                        React.createElement(InputFieldFind, { icons: this.state.icons1, id: 'idacredi', name: 'idacredi', valor: this.state.idacredi, cols: 'three wide', label: 'Acreditado', placeholder: 'Buscar', onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                        React.createElement(InputField, { id: 'nombrea', label: 'Nombre', cols: 'eleven wide', readOnly: 'readOnly', valor: this.state.nombrea, onChange: this.handleInputChange.bind(this) })
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: this.state.activoap === 0 ? "disablediv" : "" },
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        React.createElement(SelectOption, { id: 'movapor', cols: 'three wide', label: 'Movimiento', valor: this.state.movapor, valores: [{ name: 'Ingreso', value: 'D' }, { name: 'Egreso', value: 'R' }], onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(SelectOption, { id: 'tipoapor', cols: 'three wide', label: 'Tipo', valor: this.state.tipoapor, valores: [{ name: 'Efectivo', value: '01' }, { name: 'Cheque', value: '10' }], onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputFieldNumber, { id: 'imporapor', cols: 'three wide', label: 'Importe', valor: this.state.imporapor, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    complemento,
                                    React.createElement(
                                        'div',
                                        { className: 'ui vertical segment right aligned' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                'Enviar'
                                            )
                                        )
                                    )
                                )
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: this.state.stepno === 2 ? "main ui intro  top" : "main ui intro  hidden" },
                        React.createElement(
                            'div',
                            { className: 'ui segment vertical' },
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'h3',
                                    { className: 'ui rojo header' },
                                    'Aplicaci\xF3n de pagos Grupal'
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'ui secondary menu' },
                            React.createElement(
                                'div',
                                { className: 'ui basic icon buttons' },
                                React.createElement(
                                    'button',
                                    { className: 'ui button', 'data-tooltip': '\xDAltimo movimiento', onClick: this.handleButton.bind(this, 20) },
                                    React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 20) })
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'get soling' },
                            React.createElement(
                                'form',
                                { className: 'ui form formgen', ref: 'form', onSubmit: this.handleSubmit.bind(this), method: 'post' },
                                React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                                React.createElement(
                                    'div',
                                    { className: 'fields' },
                                    React.createElement(SelectDropDown, { name: 'idcolmena', cols: 'five wide', id: 'idcolmena', label: 'Colmena', valor: this.state.idcolmena, valores: this.state.catColmenas, onChange: this.handleInputChange.bind(this) }),
                                    React.createElement(SelectOption, { name: 'idgrupo', cols: 'three wide', id: 'idgrupo', label: 'Grupo', valor: this.state.idgrupo, valores: this.state.catGrupos, onChange: this.handleInputChange.bind(this) }),
                                    React.createElement(
                                        'div',
                                        { className: esquema === 'ban.' ? '' : 'hidden' },
                                        React.createElement(Calendar, { name: 'fecha_pago', cols: 'three wide', label: 'Fecha', valor: this.state.fecha_pago, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'ui vertical segment eight wide field right aligned' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: this.state.btnreversa == true ? "ui bottom primary basic button" : "ui bottom primary basic button disabled", type: 'button', name: 'reversa', onClick: this.handleReversa.bind(this) },
                                                React.createElement('i', { className: 'send icon' }),
                                                'Reversa'
                                            )
                                        )
                                    )
                                ),
                                React.createElement(Nota, { visible: this.state.notavis, notavis_lista: this.state.notavis_lista }),
                                React.createElement(Mensaje, { icon: this.state.icon, titulo: this.state.titulo, pregunta: this.state.pregunta }),
                                React.createElement(
                                    'div',
                                    { id: 'pagares', className: 'row' },
                                    React.createElement(
                                        'div',
                                        { className: 'row' },
                                        React.createElement(
                                            'div',
                                            { className: 'four fields' },
                                            React.createElement(InputFieldNumber, { id: 'totalcompara', label: 'Total Pago', valor: this.state.totalcompara, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur })
                                        ),
                                        React.createElement(Table, { datos: this.state.catPagares, totalxpagar: this.state.totalxpagar, onChange: this.handleChangeTotal.bind(this) }),
                                        React.createElement('div', { className: 'clear' }),
                                        React.createElement(
                                            'div',
                                            { className: 'ui right floated  tiny orange statistic' },
                                            React.createElement('input', { className: 'totalxpagar', type: 'text', id: 'pagototal', name: 'grantotal', value: this.state.pagototal, onChange: this.handleInputChange.bind(this) })
                                        ),
                                        React.createElement(
                                            'div',
                                            { className: 'ui vertical segment right aligned' },
                                            React.createElement(
                                                'div',
                                                { className: 'field' },
                                                React.createElement(
                                                    'button',
                                                    { className: this.state.notavis === true ? "ui submit bottom primary basic button disabled" : "ui submit bottom primary basic button", type: 'submit', name: 'action' },
                                                    React.createElement('i', { className: 'send icon' }),
                                                    'Enviar'
                                                )
                                            )
                                        )
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        data0
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        data1,
                                        data2,
                                        data3,
                                        vales,
                                        data4,
                                        data5
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        data6,
                                        data7
                                    )
                                )
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: this.state.stepno === 3 ? "main ui intro  top" : "main ui intro  hidden" },
                        React.createElement(
                            'div',
                            { className: 'ui segment vertical' },
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'h3',
                                    { className: 'ui rojo header' },
                                    'Movimientos de acreditado'
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui secondary menu' },
                                React.createElement(
                                    'div',
                                    { className: 'ui basic icon buttons' },
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Nuevo Registro' },
                                        React.createElement('i', { className: 'plus square outline icon', onClick: this.handleButton.bind(this, 30) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Solicitud de Ingreso' },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 31) })
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'right menu' },
                                    React.createElement(
                                        'div',
                                        { className: 'item ui fluid category search' },
                                        React.createElement(
                                            'div',
                                            { className: 'ui icon input' },
                                            React.createElement('input', { className: 'prompt mayuscula', type: 'text', placeholder: 'Buscar Nombre' }),
                                            React.createElement('i', { className: 'search link icon' })
                                        ),
                                        React.createElement('div', { className: 'results' })
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'get ind' },
                            React.createElement(
                                'form',
                                { className: 'ui form formind', ref: 'form', onSubmit: this.handleSubmitind.bind(this), method: 'post' },
                                React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                                React.createElement(
                                    'div',
                                    { className: this.state.activoin === 1 ? "disablediv" : "" },
                                    React.createElement(
                                        'div',
                                        { className: 'two fields' },
                                        React.createElement(InputFieldFind, { icons: this.state.icons2, dataid: this.state.acreditaind, id: 'idacredita', name: 'idacredita', valor: this.state.idacredita, cols: 'three wide', label: 'Acreditado', placeholder: 'Buscar', onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                        React.createElement(InputField, { id: 'nombre', label: 'Nombre', cols: 'eleven wide', readOnly: 'readOnly', valor: this.state.nombre, onChange: this.handleInputChange.bind(this) })
                                    )
                                ),
                                React.createElement('br', null),
                                React.createElement(
                                    'div',
                                    { className: this.state.activoin === 0 ? "disablediv" : "" },
                                    React.createElement(
                                        'div',
                                        { className: this.state.stepOpc == 0 || this.state.stepOpc == 3 ? "ui row " : "ui row hidden" },
                                        React.createElement(
                                            'h5',
                                            { className: 'ui horizontal divider header' },
                                            React.createElement('i', { className: 'search mini icon link', onClick: this.handleClickOpc.bind(this, 1) }),
                                            ' Cr\xE9ditos '
                                        ),
                                        React.createElement(
                                            'div',
                                            { className: 'row' },
                                            React.createElement(TableGlob, { name: 'pagares', csrf: this.state.csrf, datos: this.state.catPagSoc, enca: ['idcredito', 'idahorro', 'No. Pagaré', 'Importe', 'Pagos', 'Saldo', 'Cta. Ahorro', 'Monto', 'Acciones'], onChange: this.handleChangeOpc, onClick: this.handleClickUpdate })
                                        )
                                    ),
                                    React.createElement('br', null),
                                    React.createElement(
                                        'div',
                                        { className: this.state.stepOpc == 0 || this.state.stepOpc == 1 ? "ui row" : "ui row hidden" },
                                        React.createElement(
                                            'h5',
                                            { className: 'ui horizontal divider header' },
                                            React.createElement('i', { className: 'search icon link', onClick: this.handleClickOpc.bind(this, 2) }),
                                            ' Ahorros\u2009\u2009 ',
                                            status,
                                            React.createElement(
                                                'a',
                                                { 'data-tooltip': 'Ticket' },
                                                React.createElement('i', { className: 'file text outline icon orange link', onClick: this.handleButton.bind(this, 24) })
                                            ),
                                            React.createElement(
                                                'a',
                                                { 'data-tooltip': 'Contrato' },
                                                React.createElement('i', { className: 'file text outline icon orange link', onClick: this.handleButton.bind(this, 21) })
                                            ),
                                            ' ',
                                            React.createElement(
                                                'a',
                                                { 'data-tooltip': 'Estado de cuenta detallado' },
                                                React.createElement('i', { className: 'file text outline icon orange link', onClick: this.handleButton.bind(this, 22) })
                                            ),
                                            ' ',
                                            React.createElement(
                                                'a',
                                                { 'data-tooltip': 'Estado de cuenta global' },
                                                React.createElement('i', { className: 'file text outline icon orange link', onClick: this.handleButton.bind(this, 23) })
                                            )
                                        ),
                                        React.createElement(
                                            'div',
                                            { className: 'row' },
                                            React.createElement(TableGlob, { name: 'voluntario', csrf: this.state.csrf, datos: this.state.catAhoVol, enca: ['idahorro', 'Cta. Ahorro', 'Menor', 'Último Depósito', 'Último Retiro', 'Acciones'], onChange: this.handleChangeOpc, onClick: this.handleClickUpdate })
                                        ),
                                        React.createElement(
                                            'div',
                                            { className: this.state.visibleahorro == 1 ? "ui row div-ahorros" : "ui row hidden div-ahorros" },
                                            React.createElement(
                                                'div',
                                                { className: 'ui secondary menu' },
                                                React.createElement(
                                                    'div',
                                                    { className: 'ui basic icon buttons' },
                                                    React.createElement(
                                                        'button',
                                                        { type: 'button', className: 'ui button', 'data-tooltip': 'Cerrar' },
                                                        React.createElement('i', { className: 'window close icon', onClick: this.handleButton.bind(this, 200) })
                                                    ),
                                                    React.createElement(
                                                        'button',
                                                        { type: 'button', className: 'ui button', 'data-tooltip': 'Formato PDF' },
                                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 201) })
                                                    ),
                                                    React.createElement(
                                                        'button',
                                                        { type: 'button', className: 'ui button', 'data-tooltip': 'Pantalla' },
                                                        React.createElement('i', { className: 'file text outline icon', onClick: this.handleButton.bind(this, 202) })
                                                    )
                                                )
                                            ),
                                            React.createElement(
                                                'div',
                                                { className: 'fields' },
                                                React.createElement(Calendar, { name: 'fecaini', label: 'Fecha inicial', valor: this.state.fecaini, onChange: this.handleInputChange.bind(this) }),
                                                React.createElement(Calendar, { name: 'fecafin', label: 'Fecha Final', valor: this.state.fecafin, onChange: this.handleInputChange.bind(this) })
                                            ),
                                            React.createElement(
                                                'div',
                                                { className: 'four fields' },
                                                React.createElement(MultiSelect, { label: 'Cuentas de Ahorro', name: 'idAhorros', valores: this.state.catproductos })
                                            )
                                        )
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: this.state.stepOpc == 0 || this.state.stepOpc == 2 ? "ui row" : "ui row hidden" },
                                        React.createElement(
                                            'h5',
                                            { className: 'ui horizontal divider header' },
                                            ' ',
                                            React.createElement('i', { className: 'search icon link', onClick: this.handleClickOpc.bind(this, 3) }),
                                            ' Inversiones '
                                        ),
                                        React.createElement(
                                            'div',
                                            { className: 'row' },
                                            React.createElement(TableGlob, { name: 'inversiones', csrf: this.state.csrf, catPlazos: this.state.catPlazos, catInteres: this.state.catInteres, datos: this.state.catInver, enca: ['Número', 'Fecha Apertura', 'Fecha vencimiento', 'Dias x vencer', 'Acciones'], onChange: this.handleChangeOpc, onClick: this.handleClickUpdate })
                                        )
                                    ),
                                    React.createElement('br', null)
                                )
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: this.state.stepno === 4 ? "main ui intro  top" : "main ui intro  hidden" },
                        React.createElement(
                            'div',
                            { className: 'ui segment vertical' },
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'h3',
                                    { className: 'ui rojo header' },
                                    'Entrega de cr\xE9ditos'
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui secondary menu' },
                                React.createElement(
                                    'div',
                                    { className: 'ui basic icon buttons' },
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Nuevo Registro' },
                                        React.createElement('i', { className: 'plus square outline icon', onClick: this.handleButton.bind(this, 40) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Formato PDF' },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 41) })
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'right menu' },
                                    React.createElement(
                                        'div',
                                        { className: 'item ui fluid category search' },
                                        React.createElement(
                                            'div',
                                            { className: 'ui icon input' },
                                            React.createElement('input', { className: 'prompt mayuscula', type: 'text', placeholder: 'Buscar Nombre' }),
                                            React.createElement('i', { className: 'search link icon' })
                                        ),
                                        React.createElement('div', { className: 'results' })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'ui basic right floated icon buttons' },
                                        React.createElement(
                                            'button',
                                            { className: 'ui button', 'data-tooltip': 'Vista', onClick: this.handleButton.bind(this, 43) },
                                            React.createElement('i', { className: 'list icon', onClick: this.handleButton.bind(this, 43) })
                                        ),
                                        React.createElement(
                                            'button',
                                            { className: 'ui button', 'data-tooltip': 'Actualizar', onClick: this.handleButton.bind(this, 44) },
                                            React.createElement('i', { className: 'refresh icon', onClick: this.handleButton.bind(this, 44) })
                                        )
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'get disper' },
                            React.createElement(
                                'form',
                                { className: 'ui form formdis', ref: 'form', onSubmit: this.handleSubmitDis.bind(this), method: 'post' },
                                React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                                React.createElement(
                                    'div',
                                    { className: 'fields' },
                                    React.createElement(InputFieldFind, { icons: this.state.icons3, id: 'idacredis', cols: 'three wide', mayuscula: 'true', name: 'idacredis', valor: this.state.idacredis, label: 'Acreditado', placeholder: 'Buscar', onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                    React.createElement(InputField, { id: 'nombred', cols: 'thirteen wide', label: 'Nombre', readOnly: 'readOnly', valor: this.state.nombred, onChange: this.handleInputChange.bind(this) })
                                ),
                                React.createElement(
                                    'div',
                                    { className: this.state.activoc === 0 ? "disablediv" : "" },
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        React.createElement(SelectOption, { id: 'idpagdis', cols: 'three wide', label: 'Pagar\xE9', valor: this.state.idpagdis, valores: this.state.catPagDis, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'colmena', cols: 'five wide', label: 'Colmena', readOnly: 'readOnly', valor: this.state.colmena, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'grupo', cols: 'two wide', label: 'Grupo', readOnly: 'readOnly', valor: this.state.grupo, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'fecha_aprov', cols: 'three wide', label: 'Fec. aprobaci\xF3n', readOnly: 'readOnly', valor: this.state.fecha_aprov, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'fecha_disper', cols: 'three wide', label: 'Fec. dispersi\xF3n', readOnly: 'readOnly', valor: this.state.fecha_disper, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        React.createElement(InputFieldNumber, { id: 'monto', label: 'Monto', readOnly: 'readOnly', valor: this.state.monto, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                        React.createElement(InputField, { id: 'importeletra', cols: 'ten wide', label: 'Importe en Letra', readOnly: 'readOnly', valor: this.state.importeletra, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement('input', { id: 'idahorrodis', readOnly: 'readOnly', name: 'idahorrodis', type: 'hidden', value: this.state.idahorrodis }),
                                        React.createElement(InputField, { id: 'numero_cuenta', readOnly: 'readOnly', cols: 'three wide', label: 'Cuenta ', valor: this.state.numero_cuenta, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'ui vertical segment right aligned' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                ' Enviar '
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: classaction },
                            React.createElement(
                                'h5',
                                { className: 'ui horizontal divider header' },
                                'Cr\xE9ditos por Entregar '
                            ),
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(TableGlob, { name: 'credxdis', datos: this.state.catCreditos, enca: ['Id', 'Acreditada', 'Pagaré ', 'Nombre', 'Monto', 'Primer Pago', 'Fec. Aprobación', 'Fec. Entrega'], onChange: this.handleChangeOpc, onClick: this.handleClickUpdate })
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: this.state.stepno === 41 ? "main ui intro  top" : "main ui intro  hidden" },
                        React.createElement(
                            'div',
                            { className: 'ui segment vertical' },
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'h3',
                                    { className: 'ui rojo header' },
                                    'Recepci\xF3n de inversiones'
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui basic icon buttons' },
                                React.createElement(
                                    'button',
                                    { className: 'ui button', 'data-tooltip': 'Nuevo Registro' },
                                    React.createElement('i', { className: 'plus square outline icon', onClick: this.handleButton.bind(this, 410) })
                                ),
                                React.createElement(
                                    'button',
                                    { className: 'ui button', 'data-tooltip': 'Formato PDF' },
                                    React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 411) })
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui basic right floated icon buttons' },
                                React.createElement(
                                    'button',
                                    { className: 'ui button', 'data-tooltip': 'Proyecci\xF3n' },
                                    React.createElement('i', { className: 'list icon', onClick: this.handleButton.bind(this, 414) })
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'get solinver' },
                            React.createElement(
                                'form',
                                { className: 'ui form forminv', ref: 'form', onSubmit: this.handleSubmitInv.bind(this), method: 'post' },
                                React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                                React.createElement(
                                    'div',
                                    { className: this.state.activoi === 1 ? "disablediv" : "" },
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        React.createElement(InputFieldFind, { icons: this.state.icons4, id: 'idacreinv', cols: 'three wide', mayuscula: 'true', name: 'idacreinv', valor: this.state.idacreinv, label: 'Acreditado', placeholder: 'Buscar', onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                        React.createElement(InputField, { id: 'nombrei', cols: 'thirteen wide', label: 'Nombre', readOnly: 'readOnly', valor: this.state.nombrei, onChange: this.handleInputChange.bind(this) })
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: this.state.activoi === 0 ? "disablediv" : "" },
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        React.createElement(SelectOption, { id: 'numero', cols: 'three', label: 'N\xFAmero', valor: this.state.numero, valores: this.state.catInverNum, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'fecha', label: 'Fecha', cols: 'four', readOnly: 'readOnly', valor: this.state.fecha, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputFieldNumber, { id: 'importe', cols: 'four', readOnly: 'readOnly', label: 'Importe', valor: this.state.importe, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                        React.createElement(InputField, { id: 'dias', label: 'Dias', cols: 'two', readOnly: 'readOnly', valor: this.state.dias, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'tasa', label: 'Tasa', cols: 'two', readOnly: 'readOnly', valor: this.state.tasa, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'interes', cols: 'four', readOnly: 'readOnly', label: 'Interes', valor: this.state.interes, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        React.createElement(InputField, { id: 'fechafin', cols: 'three', readOnly: 'readOnly', label: 'Fecha vencimiento', valor: this.state.fechafin, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputFieldNumber, { id: 'total', cols: 'three', readOnly: 'readOnly', label: 'Total', valor: this.state.total, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                        React.createElement('input', { type: 'hidden', name: 'idinversion', value: this.state.idinversion, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'ui vertical segment right aligned' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                ' Enviar '
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: this.state.actproy === 1 ? "" : "hidden" },
                            React.createElement(
                                'form',
                                { className: 'ui form proyeccion' },
                                React.createElement(
                                    'div',
                                    { className: 'fields' },
                                    React.createElement(InputFieldNumber, { id: 'importeproy', cols: 'four wide', label: 'Importe', valor: this.state.importeproy, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                    React.createElement(SelectOption, { id: 'diasproy', cols: 'two wide', label: 'Plazo', valor: this.state.diasproy, valores: this.state.catPlazos, onChange: this.handleInputChange.bind(this) }),
                                    React.createElement(InputField, { id: 'tasaproy', label: 'Tasa', cols: 'two wide', readOnly: 'readOnly', valor: this.state.tasaproy, onChange: this.handleInputChange.bind(this) }),
                                    React.createElement(InputField, { id: 'interesproy', cols: 'four wide', readOnly: 'readOnly', label: 'Interes', valor: this.state.interesproy, onChange: this.handleInputChange.bind(this) }),
                                    React.createElement(InputFieldNumber, { id: 'totalproy', cols: 'three', readOnly: 'readOnly', label: 'Total', valor: this.state.totalproy, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur })
                                )
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: this.state.stepno === 43 ? "main ui intro  top" : "main ui intro  hidden" },
                        React.createElement(
                            'div',
                            { className: 'ui segment vertical' },
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'h3',
                                    { className: 'ui rojo header' },
                                    'Seguro de cr\xE9dito'
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui secondary menu' },
                                React.createElement(
                                    'div',
                                    { className: 'ui basic icon buttons' },
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Nuevo Registro' },
                                        React.createElement('i', { className: 'plus square outline icon', onClick: this.handleButton.bind(this, 430) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Formato PDF' },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 431) })
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'right menu' },
                                    React.createElement(
                                        'div',
                                        { className: 'item ui fluid category search' },
                                        React.createElement(
                                            'div',
                                            { className: 'ui icon input' },
                                            React.createElement('input', { className: 'prompt mayuscula', type: 'text', placeholder: 'Buscar Nombre' }),
                                            React.createElement('i', { className: 'search link icon' })
                                        ),
                                        React.createElement('div', { className: 'results' })
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'get disseg' },
                            React.createElement(
                                'form',
                                { className: 'ui form formseg', ref: 'form', onSubmit: this.handleSubmitSeg.bind(this), method: 'post' },
                                React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                                React.createElement(
                                    'div',
                                    { className: 'fields' },
                                    React.createElement(InputFieldFind, { icons: this.state.icons3, id: 'idacreseg', cols: 'three wide', mayuscula: 'true', name: 'idacreseg', valor: this.state.idacreseg, label: 'Acreditado', placeholder: 'Buscar', onChange: this.handleInputChange.bind(this), onClick: this.handleFind.bind(this) }),
                                    React.createElement(InputField, { id: 'nombseg', cols: 'thirteen wide', label: 'Nombre', readOnly: 'readOnly', valor: this.state.nomseg, onChange: this.handleInputChange.bind(this) })
                                ),
                                React.createElement(
                                    'div',
                                    { className: this.state.activoc === 0 ? "disablediv" : "" },
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        React.createElement(SelectOption, { id: 'idpagseg', cols: 'three wide', label: 'Pagar\xE9', valor: this.state.idpagseg, valores: this.state.catPagSeg, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'colmena_s', cols: 'five wide', label: 'Colmenas', readOnly: 'readOnly', valor: this.state.colmena_s, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'grupo_s', cols: 'two wide', label: 'Grupo', readOnly: 'readOnly', valor: this.state.grupo_s, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'fecha_aprov_s', cols: 'three wide', label: 'Fec. aprobaci\xF3n', readOnly: 'readOnly', valor: this.state.fecha_aprov_s, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'fecha_disper_s', cols: 'three wide', label: 'Fec. dispersi\xF3n', readOnly: 'readOnly', valor: this.state.fecha_disper_s, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'fields' },
                                        React.createElement(InputFieldNumber, { id: 'monto_s', label: 'Monto', valor: this.state.monto_s, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                        React.createElement(InputField, { id: 'importeletra_s', cols: 'ten wide', label: 'Importe en Letra', readOnly: 'readOnly', valor: this.state.importeletra_s, onChange: this.handleInputChange.bind(this) }),
                                        React.createElement(InputField, { id: 'esquema', cols: 'three wide', label: 'esquema', readOnly: 'readOnly', valor: this.state.esquema, onChange: this.handleInputChange.bind(this) })
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'ui vertical segment right aligned' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                ' Enviar '
                                            )
                                        )
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: classaction },
                            React.createElement(
                                'h5',
                                { className: 'ui horizontal divider header' },
                                'Cr\xE9ditos por Entregar '
                            ),
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(TableGlob, { name: 'credxdis', datos: this.state.catCreditos, enca: ['Id', 'Acreditada', 'Pagaré ', 'Nombre', 'Monto', 'Primer Pago', 'Fec. Aprobación', 'Fec. Entrega'], onChange: this.handleChangeOpc, onClick: this.handleClickUpdate })
                            )
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: this.state.stepno === 5 || this.state.stepno === 99 ? "main ui intro  top" : "main ui intro  hidden" },
                        React.createElement(
                            'div',
                            { className: 'ui segment vertical' },
                            React.createElement(
                                'div',
                                { className: 'row' },
                                React.createElement(
                                    'h3',
                                    { className: 'ui rojo header' },
                                    'Corte de Caja'
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui secondary menu' },
                                React.createElement(
                                    'div',
                                    { className: 'ui basic icon buttons' },
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Actualiza saldos' },
                                        React.createElement('i', { className: 'refresh icon', onClick: this.handleButton.bind(this, 50) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Nota cr\xE9dito' },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 51) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Movimientos del d\xEDa' },
                                        React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 52) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Movimientos' },
                                        React.createElement('i', { className: 'list icon', onClick: this.handleButton.bind(this, 53) })
                                    ),
                                    React.createElement(
                                        'button',
                                        { className: 'ui button', 'data-tooltip': 'Acreditada(o)', onClick: this.handleButton.bind(this, 54) },
                                        React.createElement('i', { className: 'address card outline icon', onClick: this.handleButton.bind(this, 54) })
                                    )
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'right menu' },
                                    React.createElement(
                                        'div',
                                        { className: 'item ui fluid category search' },
                                        React.createElement(
                                            'div',
                                            { className: 'ui icon input' },
                                            React.createElement('input', { className: 'prompt mayuscula', type: 'text', placeholder: 'Buscar Nombre' }),
                                            React.createElement('i', { className: 'search link icon' })
                                        ),
                                        React.createElement('div', { className: 'results' })
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'fields' },
                            React.createElement(Calendar, { visible: this.state.idmovisible, name: 'fechaconsulta', label: 'Fecha consulta: ', valor: this.state.fechaconsulta, onChange: this.handleInputChange.bind(this) })
                        ),
                        React.createElement(
                            'div',
                            { className: this.state.visibleFindAcredita === true ? '' : 'hidden' },
                            React.createElement(FindAcreditada, null)
                        ),
                        React.createElement(
                            'div',
                            { className: this.state.stepno == 99 ? "main ui intro top" : "main ui intro hidden" },
                            React.createElement(
                                'div',
                                { className: 'ui inverted red segment' },
                                React.createElement(
                                    'div',
                                    { className: 'ui header' },
                                    'Aviso importante.'
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'ui subheader' },
                                    'Se ha identificado que no fue cerrada la caja del d\xEDa ',
                                    this.state.fechacierre,
                                    '. ',
                                    React.createElement(
                                        'b',
                                        null,
                                        'Solo se permite el cierre de caja.'
                                    )
                                )
                            )
                        ),
                        React.createElement(
                            'div',
                            { className: 'get discaja' },
                            React.createElement(
                                'form',
                                { className: 'ui form formcaj', ref: 'form', onSubmit: this.handleSubmitCorte.bind(this), method: 'post' },
                                React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                                React.createElement(
                                    'div',
                                    { className: 'four fields' },
                                    React.createElement(InputFieldNum, { id: 'saldoini', label: 'Saldo Inicial', valor: saldoinic }),
                                    React.createElement(InputFieldNum, { id: 'ingresos', label: 'Ingresos', valor: ingresosc }),
                                    React.createElement(InputFieldNum, { id: 'egresos', label: 'Egresos', valor: egresosc }),
                                    React.createElement(InputFieldNum, { id: 'saldofin', label: 'Saldo Final', valor: saldofinc })
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'four fields' },
                                    React.createElement(SelectOption, { id: 'movcaja', cols: 'three wide', label: 'Movimiento', valor: this.state.movcaja, valores: [{ name: 'Devolución parcial', value: 'N' }, { name: 'Cierre', value: 'C' }], onChange: this.handleInputChange.bind(this) })
                                ),
                                React.createElement(TableC, { datos: this.state.catDenomina, totalxpagar: this.state.totalxpagarc, onChange: this.handleChangeTot }),
                                React.createElement(
                                    'div',
                                    { className: 'ui vertical segment right aligned' },
                                    React.createElement(
                                        'div',
                                        { className: 'field' },
                                        React.createElement(
                                            'button',
                                            { className: 'ui submit bottom primary basic button', type: 'submit', name: 'action' },
                                            React.createElement('i', { className: 'send icon' }),
                                            ' Enviar '
                                        )
                                    )
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