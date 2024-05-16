'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _toConsumableArray(arr) { if (Array.isArray(arr)) { for (var i = 0, arr2 = Array(arr.length); i < arr.length; i++) { arr2[i] = arr[i]; } return arr2; } else { return Array.from(arr); } }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

var CheckBox = function (_React$Component) {
    _inherits(CheckBox, _React$Component);

    function CheckBox(props) {
        _classCallCheck(this, CheckBox);

        return _possibleConstructorReturn(this, (CheckBox.__proto__ || Object.getPrototypeOf(CheckBox)).call(this, props));
    }

    _createClass(CheckBox, [{
        key: 'render',
        value: function render() {
            var _this2 = this;

            var checked = this.props.valor == '1' ? 'ui checkbox checked' : 'ui checkbox';
            if (this.props.disabled) {
                checked += " read-only";
            }
            return React.createElement(
                'div',
                { className: 'field' },
                React.createElement(
                    'label',
                    null,
                    'Seleccione'
                ),
                React.createElement(
                    'div',
                    { className: 'four fields' },
                    React.createElement(
                        'div',
                        { className: 'ten wide inline field' },
                        React.createElement(
                            'div',
                            { className: checked, onClick: function onClick(event) {
                                    return _this2.props.onClickCheck(event, _this2.props.name, _this2.props.valor);
                                } },
                            React.createElement('input', { type: 'checkbox', value: this.props.valor == 1 ? 'on' : 'off', id: this.props.name, name: this.props.name, tabindex: '0', 'class': 'hidden' }),
                            React.createElement(
                                'label',
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

var InputField = function (_React$Component2) {
    _inherits(InputField, _React$Component2);

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

var InputFieldNumber = function (_React$Component3) {
    _inherits(InputFieldNumber, _React$Component3);

    function InputFieldNumber(props) {
        _classCallCheck(this, InputFieldNumber);

        return _possibleConstructorReturn(this, (InputFieldNumber.__proto__ || Object.getPrototypeOf(InputFieldNumber)).call(this, props));
    }

    _createClass(InputFieldNumber, [{
        key: 'render',
        value: function render() {
            var _this6 = this;

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
                    React.createElement('input', { className: 'text-right', type: 'text', id: this.props.id, name: this.props.id, readOnly: this.props.readOnly, value: this.props.valor, onChange: function onChange(event) {
                            return _this6.props.onChange(event);
                        }, onBlur: function onBlur(event) {
                            return _this6.props.onBlur(event);
                        } })
                )
            );
        }
    }]);

    return InputFieldNumber;
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
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'ui mini test modal scrolling transition hidden' },
                React.createElement(
                    'div',
                    { className: 'ui icon header' },
                    React.createElement('i', { className: this.state.icon }),
                    this.state.titulo
                ),
                React.createElement(
                    'div',
                    { className: 'center aligned content ' },
                    React.createElement(
                        'p',
                        null,
                        this.state.pregunta
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

var RecordDetalle = function (_React$Component5) {
    _inherits(RecordDetalle, _React$Component5);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        return _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));
    }

    _createClass(RecordDetalle, [{
        key: 'handleClick',
        value: function handleClick(e) {
            //    console.log(this.props.registro);
            this.props.onClick(e, this.props.registro);
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'tr',
                null,
                React.createElement(
                    'td',
                    null,
                    this.props.registro.idproducto
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.nombre
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.tipo
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.activo
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.top
                ),
                React.createElement(
                    'td',
                    { className: ' center aligned' },
                    React.createElement(
                        'button',
                        { className: 'ui button', 'data-tooltip': 'Edit', onClick: this.handleClick.bind(this) },
                        React.createElement('i', { className: 'edit outline icon', onClick: this.handleClick.bind(this) })
                    )
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

function Lista(props) {
    var cadenas = props.enca;
    var contador = 0;
    var listItems = cadenas.map(function (encabezado) {
        return React.createElement(
            'th',
            { key: contador++ },
            encabezado
        );
    });
    return React.createElement(
        'tr',
        null,
        listItems
    );
}

var Table = function (_React$Component6) {
    _inherits(Table, _React$Component6);

    function Table(props) {
        _classCallCheck(this, Table);

        var _this9 = _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));

        _this9.handleClick = _this9.handleClick.bind(_this9);
        return _this9;
    }

    _createClass(Table, [{
        key: 'handleClick',
        value: function handleClick(e, valor) {
            this.props.onClick(e, valor);
        }
    }, {
        key: 'render',
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            datos.forEach(function (record) {
                rows.push(React.createElement(RecordDetalle, { registro: record, onClick: this.handleClick }));
            }.bind(this));

            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                'div',
                null,
                React.createElement(
                    'table',
                    { className: 'ui selectable celled red table' },
                    React.createElement(
                        'thead',
                        null,
                        React.createElement(Lista, { enca: ['Clave', 'Nombre', 'Tipo', 'Activo', 'Top', 'Opciones'] })
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

var SelectDropDown = function (_React$Component7) {
    _inherits(SelectDropDown, _React$Component7);

    function SelectDropDown(props) {
        _classCallCheck(this, SelectDropDown);

        var _this10 = _possibleConstructorReturn(this, (SelectDropDown.__proto__ || Object.getPrototypeOf(SelectDropDown)).call(this, props));

        _this10.state = {
            value: ""
        };
        _this10.handleSelectChange = _this10.handleSelectChange.bind(_this10);
        return _this10;
    }

    _createClass(SelectDropDown, [{
        key: 'handleSelectChange',
        value: function handleSelectChange(event) {
            this.props.onChange(event);
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
            if (this.props.disabled) {
                cols += " disabled";
            }
            var listItems = void 0;
            if (this.props.valores != false) {
                listItems = this.props.valores.map(function (valor) {
                    return React.createElement(
                        'div',
                        { className: 'item', 'data-value': valor.value },
                        valor.name
                    );
                });
            }
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
                    React.createElement('input', { type: 'hidden', ref: 'myDrop', value: this.props.valor, name: this.props.id, onChange: this.handleSelectChange }),
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

var Captura = function (_React$Component8) {
    _inherits(Captura, _React$Component8);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this11 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this11.state = { activo: 0,
            catINE: [],
            idproducto: 0,
            nombre: '',
            tipo: '',
            minini: 0,
            maxini: 0,
            movmin: 0,
            movmax: 0,
            monudi: false,
            saldomin: 0,
            saldomax: 0,
            idper: 0,
            csrf: "", message: "",
            statusmessage: 'ui floating hidden message',
            icons1: 'inverted circular search link icon',
            disabledboton2: 'disabled', disabledboton3: 'disabled',
            enabledCatprua: false,
            boton: 'Actualizar'
        };

        _this11.handleonBlur = _this11.handleonBlur.bind(_this11);
        return _this11;
    }

    _createClass(Captura, [{
        key: 'componentWillMount',
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
            var object = {
                url: base_url + 'api/CatalogV1/productos',
                type: 'GET',
                dataType: 'json'
            };
            var forma = this;
            ajax(object).then(function resolve(response) {
                forma.setState({ catINE: response.result
                });
            }, function reject(reason) {
                var response = validaError(reason);
                forma.setState({
                    csrf: response.newtoken,
                    message: response.message,
                    statusmessage: 'ui negative floating message', icons1: 'inverted circular search link icon'
                });
            });
        }
    }, {
        key: 'handleInputChange',
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));
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
        key: 'autoReset',
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
        key: 'handleonClickCheck',
        value: function handleonClickCheck(e, name, valor) {
            this.setState(_defineProperty({}, name, valor == '1' ? '0' : '1'));
        }
    }, {
        key: 'handleButton',
        value: function handleButton(e, value) {}
    }, {
        key: 'handleChangeRecord',
        value: function handleChangeRecord() {}
    }, {
        key: 'handleClickRecord',
        value: function handleClickRecord(e, rec) {
            this.setState({ idproducto: rec.idproducto,
                nombre: rec.nombre,
                tipo: rec.tipo,
                minini: rec.minini,
                maxini: rec.maxini,
                movmin: rec.movmin,
                movmax: rec.movmax,
                monudi: rec.monudi,
                enabledCatprua: true,
                saldomin: rec.saldomin,
                saldomax: rec.saldomax });

            var monudi = rec.monudi == '1' ? true : false;

            var $form = $('.get.formgen form'),
                Folio = $form.form('set values', { monudi: monudi });
        }
    }, {
        key: 'handleSubmitBen',
        value: function handleSubmitBen(event) {
            event.preventDefault();
            $('.ui.form.formgen').form({
                inline: true,
                on: 'blur',
                fields: {
                    idproducto: {
                        identifier: 'idproducto',
                        rules: [{
                            type: 'empty',
                            prompt: 'IdProducto incompleto'
                        }]
                    },
                    nombre: {
                        identifier: 'nombre',
                        rules: [{
                            type: 'empty',
                            prompt: 'Nombre incompleto'
                        }]
                    },

                    tipo: {
                        identifier: 'tipo',
                        rules: [{
                            type: 'empty',
                            prompt: 'Tipo incompleto'
                        }]
                    },

                    minini: {
                        identifier: 'minini',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee el monto mínimo inicial'
                        }]
                    },
                    maxini: {
                        identifier: 'maxini',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee el monto máximo inicial'
                        }]
                    },
                    movmin: {
                        identifier: 'movmin',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee el monto mínimo '
                        }]
                    },
                    movmax: {
                        identifier: 'movmax',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee el monto máximo '
                        }]
                    },
                    saldomin: {
                        identifier: 'saldomin',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee el Saldo mínimo '
                        }]
                    },
                    saldomax: {
                        identifier: 'saldomax',
                        rules: [{
                            type: 'empty',
                            prompt: 'Teclee el Saldo máximo '
                        }]
                    }

                }
            });

            $('.ui.form.formgen').form('validate form');
            var valida = $('.ui.form.formgen').form('is valid');
            this.setState({ message: '', statusmessage: 'ui message hidden' });
            var datosErroneos = 'Datos incompletos!';
            if (parseFloat(numeral(this.state.saldomax).format('00.00')) < parseFloat(numeral(this.state.saldomin).format('00.00'))) {
                datosErroneos = 'Saldo máximo es menor al saldo mínimo!';
                valida = false;
            }
            if (parseFloat(numeral(this.state.maxini).format('00.00')) < parseFloat(numeral(this.state.minini).format('00.00'))) {
                datosErroneos = 'Monto máximo inicial es menor al monto mínimo inicial!';
                valida = false;
            }
            if (parseFloat(numeral(this.state.movmax).format('00.00')) < parseFloat(numeral(this.state.movmin).format('00.00'))) {
                datosErroneos = 'Monto máximo es menor al monto mínimo!';
                valida = false;
            }
            if (valida == true) {
                var $form = $('.get.formgen form'),
                    allFields = $form.form('get values'),
                    token = $form.form('get value', 'csrf_bancomunidad_token');
                var tipo = this.state.boton === 'Enviar' ? 'POST' : 'PUT';
                var forma = this;
                $('.mini.modal').modal({
                    closable: false,
                    onApprove: function onApprove() {
                        var object = {
                            url: base_url + 'api/CatalogV1/productos/',
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
                                boton: 'Actualizar'
                            });
                            forma.autoReset();

                            var recUpdate = forma.state.catINE.filter(function (e) {
                                return e.idproducto == forma.state.idproducto;
                            });
                            recUpdate[0]['minini'] = forma.state.minini;
                            recUpdate[0]['maxini'] = forma.state.maxini;
                            recUpdate[0]['movmin'] = forma.state.movmin;
                            recUpdate[0]['movmax'] = forma.state.movmax;
                            recUpdate[0]['monudi'] = forma.state.monudi;
                            recUpdate[0]['saldomin'] = forma.state.saldomin;
                            recUpdate[0]['saldomax'] = forma.state.saldomax;

                            var newArray = [].concat(_toConsumableArray(forma.state.catINE), [recUpdate]);
                        }, function reject(reason) {
                            var response = validaError(reason);
                            forma.setState({
                                csrf: response.newtoken,
                                message: response.message,
                                statusmessage: 'ui negative floating message'
                            });

                            console.log(response.message);
                            forma.autoReset();
                        });
                    }
                }).modal('show');
            } else {
                this.setState({
                    message: datosErroneos,
                    statusmessage: 'ui negative floating message'
                });
                this.autoReset();
            }
        }
    }, {
        key: 'render',
        value: function render() {
            var _this12 = this;

            return React.createElement(
                'div',
                null,
                React.createElement(
                    'div',
                    { className: 'ui segment vertical ' },
                    React.createElement(
                        'div',
                        { className: 'row' },
                        React.createElement(
                            'h3',
                            { className: 'ui rojo header' },
                            'Cat\xE1logo de Productos'
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
                                { className: 'ui button', 'data-tooltip': 'Formato PDF' },
                                React.createElement('i', { className: 'file pdf outline icon', onClick: this.handleButton.bind(this, 1) })
                            )
                        )
                    )
                ),
                React.createElement(Mensaje, null),
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
                            window.clearTimeout(_this12.timeout);
                            _this12.setState({ message: '', statusmessage: 'ui message hidden' });
                        } })
                ),
                React.createElement(
                    'div',
                    { className: 'get formgen' },
                    React.createElement(
                        'form',
                        { className: 'ui form small formgen', ref: 'form' },
                        React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                        React.createElement(
                            'div',
                            { className: this.state.enabledCatprua === true ? "ui blue segment" : "ui blue segment step hidden" },
                            React.createElement(
                                'div',
                                { className: 'fields' },
                                React.createElement(InputField, { id: 'idproducto', label: 'Producto', readOnly: 'readOnly', cols: 'two wide', valor: this.state.idproducto }),
                                React.createElement(InputField, { id: 'nombre', label: 'Nombre', readOnly: 'readOnly', cols: 'six  wide', valor: this.state.nombre }),
                                React.createElement(InputField, { id: 'tipo', label: 'tipo', readOnly: 'readOnly', cols: 'two wide', valor: this.state.tipo })
                            ),
                            React.createElement(
                                'div',
                                { className: 'four fields' },
                                React.createElement(InputFieldNumber, { id: 'saldomin', label: 'Saldo minimo', valor: this.state.saldomin, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                React.createElement(InputFieldNumber, { id: 'saldomax', label: 'Saldo m\xE1ximo', valor: this.state.saldomax, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                React.createElement(InputFieldNumber, { id: 'minini', label: 'Monto m\xEDnimo Inicial', valor: this.state.minini, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                React.createElement(InputFieldNumber, { id: 'maxini', label: 'Monto m\xE1ximo Inicial', valor: this.state.maxini, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur })
                            ),
                            React.createElement(
                                'div',
                                { className: 'four fields' },
                                React.createElement(CheckBox, { titulo: 'Valida vs Udi', name: 'monudi', cols: 'three wide', valor: this.state.monudi, onClickCheck: this.handleonClickCheck.bind(this) }),
                                React.createElement(InputFieldNumber, { id: 'movmin', label: 'Monto m\xEDnimo', valor: this.state.movmin, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur }),
                                React.createElement(InputFieldNumber, { id: 'movmax', label: 'Monto m\xE1ximo', valor: this.state.movmax, onChange: this.handleInputChange.bind(this), onBlur: this.handleonBlur })
                            ),
                            React.createElement(
                                'div',
                                { className: 'ui vertical segment right aligned' },
                                React.createElement(
                                    'div',
                                    { className: 'field' },
                                    React.createElement(
                                        'button',
                                        { className: 'ui bottom primary basic button', type: 'button', name: 'action', onClick: this.handleSubmitBen.bind(this) },
                                        React.createElement('i', { className: 'send icon' }),
                                        ' Actualizar'
                                    )
                                )
                            )
                        ),
                        React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf })
                    )
                ),
                React.createElement('form', null),
                React.createElement(
                    'div',
                    null,
                    React.createElement(Table, { name: 'catMov', datos: this.state.catINE, onClick: this.handleClickRecord.bind(this) })
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

ReactDOM.render(React.createElement(Captura, null), document.getElementById('root'));