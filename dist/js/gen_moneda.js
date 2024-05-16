'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

function _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError("this hasn't been initialised - super() hasn't been called"); } return call && (typeof call === "object" || typeof call === "function") ? call : self; }

function _inherits(subClass, superClass) { if (typeof superClass !== "function" && superClass !== null) { throw new TypeError("Super expression must either be null or a function, not " + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }

/*
* Imprime los encabezados de una tabla
*/
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

/*
 *
 *
*/

var RecordDetalle = function (_React$Component) {
    _inherits(RecordDetalle, _React$Component);

    function RecordDetalle(props) {
        _classCallCheck(this, RecordDetalle);

        var _this = _possibleConstructorReturn(this, (RecordDetalle.__proto__ || Object.getPrototypeOf(RecordDetalle)).call(this, props));

        _this.handleDeleteRecord = _this.handleDeleteRecord.bind(_this);
        return _this;
    }

    _createClass(RecordDetalle, [{
        key: 'handleDeleteRecord',
        value: function handleDeleteRecord() {
            var idbanco = this.props.registro.idbanco;
            var forma = this.props;
            $('.test.modal').modal({
                closable: false,
                onApprove: function onApprove() {
                    $.ajax({
                        url: base_url + '/api/GeneralV1/registro',
                        type: 'DELETE',
                        dataType: 'json',
                        data: {
                            from: 'bancos',
                            idbanco: idbanco
                        },
                        success: function (response) {
                            if (response.status === "OK") {
                                forma.onClickDelete();
                                //                        this.props.onClickDelete();
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
        key: 'render',
        value: function render() {
            var checked = this.props.registro.activo ? 'checked' : '';
            return React.createElement(
                'tr',
                null,
                React.createElement(
                    'td',
                    null,
                    this.props.registro.idbanco
                ),
                React.createElement(
                    'td',
                    null,
                    this.props.registro.nombre
                ),
                React.createElement(
                    'td',
                    null,
                    '   ',
                    React.createElement(
                        'p',
                        null,
                        React.createElement('input', { type: 'checkbox', name: 'Status', 'class': 'filled-in', id: 'filled-in-box', checked: checked }),
                        React.createElement('label', { 'for': 'filled-in-box' })
                    )
                ),
                React.createElement(
                    'td',
                    { className: ' center aligned' },
                    React.createElement(
                        'a',
                        null,
                        React.createElement('i', { className: 'edit icon circular green' })
                    )
                ),
                React.createElement(
                    'td',
                    { className: ' center aligned' },
                    React.createElement(
                        'a',
                        null,
                        React.createElement('i', { className: 'trash outline icon circular red', onClick: this.handleDeleteRecord })
                    )
                )
            );
        }
    }]);

    return RecordDetalle;
}(React.Component);

/*
*
*
*/


var Captura = function (_React$Component2) {
    _inherits(Captura, _React$Component2);

    function Captura(props) {
        _classCallCheck(this, Captura);

        var _this2 = _possibleConstructorReturn(this, (Captura.__proto__ || Object.getPrototypeOf(Captura)).call(this, props));

        _this2.state = { idbanco: '',
            nombre: '',
            activo: true,
            csrf: '',
            message: '',
            statusmessage: 'ui floating message hidden',
            boton: 'Guardar'
        };
        _this2.handleInputChange = _this2.handleInputChange.bind(_this2);
        _this2.handleSubmit = _this2.handleSubmit.bind(_this2);

        return _this2;
    }

    _createClass(Captura, [{
        key: 'componentWillMount',
        value: function componentWillMount() {
            var csrf_token = $("#csrf").val();
            this.setState({ csrf: csrf_token });
        }
    }, {
        key: 'handleInputChange',
        value: function handleInputChange(event) {
            var target = event.target;
            var value = target.type === 'checkbox' ? target.checked : target.value;
            var name = target.name;
            this.setState(_defineProperty({}, name, value));
            if (name === "idbanco") {
                var busca = target.value;
                if (busca === '') {
                    busca = '-';
                }
                $.ajax({
                    url: base_url + '/api/GeneralV1/obtener',
                    type: 'GET',
                    dataType: 'json',
                    data: {
                        from: 'bancos',
                        idbanco: busca
                    },
                    success: function (response) {
                        if (response.result === false) {
                            this.setState({ nombre: '',
                                activo: true });
                            this.setState({ boton: 'Guardar' });
                        } else {
                            this.setState({ nombre: response.result[0].nombre,
                                activo: response.result[0].activo });
                            this.setState({ boton: 'Modificar' });
                        }
                    }.bind(this),
                    error: function (xhr, status, err) {
                        this.setState({ boton: 'Guardar' });
                        console.log('error');
                    }.bind(this)
                });
            }
        }
    }, {
        key: 'handleSubmit',
        value: function handleSubmit(event) {
            event.preventDefault();
            var datos = $(".form").serializeArray();
            var tipo = this.state.boton === 'Guardar' ? 'POST' : 'PUT';
            $.ajax({
                url: base_url + 'api/GeneralV1/bancos',
                type: tipo,
                dataType: 'json',
                data: {
                    csrf_bancomunidad_token: datos[0]['value'],
                    data: datos
                },
                success: function (response) {
                    if (response.status === 'OK') {
                        this.setState({ idbanco: '',
                            nombre: '',
                            activo: true });
                        this.setState({ csrf: response.newtoken });
                        this.setState({ message: response.message });
                        this.setState({ statusmessage: 'ui positive floating message ' });
                        this.props.onClickAdd();
                        this.setState({ boton: 'Guardar' });
                    } else if (response.status === 'ERROR') {
                        this.setState({ csrf: response.newtoken });
                        this.setState({ message: response.message });
                        this.setState({ statusmessage: 'ui negative floating message' });
                    }
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log(status);
                }.bind(this)
            });
        }
    }, {
        key: 'handleCloseIcon',
        value: function handleCloseIcon(e) {
            this.setState({ statusmessage: 'ui floating message hidden' });
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { 'class': 'ui container' },
                React.createElement(
                    'div',
                    { className: 'ui styled fluid accordion' },
                    React.createElement(
                        'div',
                        { className: 'title active' },
                        React.createElement('i', { className: 'dropdown icon' }),
                        ' Captura '
                    ),
                    React.createElement(
                        'div',
                        { className: 'content active' },
                        React.createElement(
                            'form',
                            { className: 'ui form', ref: 'form', onSubmit: this.handleSubmit, method: 'post' },
                            React.createElement('input', { type: 'hidden', name: 'csrf_bancomunidad_token', value: this.state.csrf }),
                            React.createElement(
                                'div',
                                { className: 'fields' },
                                React.createElement(
                                    'div',
                                    { className: 'two wide field' },
                                    React.createElement(
                                        'label',
                                        { 'for': 'idbancolabel' },
                                        'Clave'
                                    ),
                                    React.createElement('input', { id: 'idbanco', name: 'idbanco', type: 'text', value: this.state.idbanco, onChange: this.handleInputChange })
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'ten wide field' },
                                    React.createElement(
                                        'label',
                                        { 'for': 'nombrelabel' },
                                        'Nombre'
                                    ),
                                    React.createElement('input', { id: 'nombre', name: 'nombre', type: 'text', value: this.state.nombre, onChange: this.handleInputChange })
                                ),
                                React.createElement(
                                    'div',
                                    { className: 'six wide field' },
                                    React.createElement(
                                        'label',
                                        { 'for': 'activolabel' },
                                        'Activo'
                                    ),
                                    React.createElement(
                                        'div',
                                        { className: 'two fields' },
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'div',
                                                { 'class': 'ui toggle checkbox' },
                                                React.createElement('input', { type: 'checkbox', tabindex: '0', 'class': 'hidden', name: 'activo', checked: this.state.activo, onChange: this.handleInputChange })
                                            )
                                        ),
                                        React.createElement(
                                            'div',
                                            { className: 'field' },
                                            React.createElement(
                                                'button',
                                                { className: 'ui bottom primary button', type: 'submit', name: 'action' },
                                                React.createElement('i', { className: 'send icon' }),
                                                ' ',
                                                this.state.boton,
                                                ' '
                                            )
                                        )
                                    )
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
                            React.createElement('i', { className: 'close icon', onClick: this.handleCloseIcon.bind(this) })
                        )
                    )
                )
            );
        }
    }]);

    return Captura;
}(React.Component);

/*
*
*
*/


var Filtros = function (_React$Component3) {
    _inherits(Filtros, _React$Component3);

    function Filtros(props) {
        _classCallCheck(this, Filtros);

        var _this3 = _possibleConstructorReturn(this, (Filtros.__proto__ || Object.getPrototypeOf(Filtros)).call(this, props));

        _this3.handleFilterTextInputChange = _this3.handleFilterTextInputChange.bind(_this3);
        _this3.handleSelect = _this3.handleSelect.bind(_this3);
        return _this3;
    }

    _createClass(Filtros, [{
        key: 'handleFilterTextInputChange',
        value: function handleFilterTextInputChange(e) {
            this.props.onFilterTextInput(e.target.value);
        }
    }, {
        key: 'handleSelect',
        value: function handleSelect(e) {
            this.props.onSelect(e.target.value);
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                { className: 'row' },
                React.createElement(
                    'div',
                    { className: 'ui form' },
                    React.createElement(
                        'div',
                        { className: 'ui vertical segment' },
                        React.createElement(
                            'div',
                            { className: 'two fields' },
                            React.createElement(
                                'div',
                                { className: 'two wide field' },
                                React.createElement(
                                    'label',
                                    null,
                                    'Presenta Registros'
                                ),
                                React.createElement(
                                    'select',
                                    { name: 'registro', value: this.props.registro, onChange: this.handleSelect },
                                    React.createElement(
                                        'option',
                                        { value: '15', selected: true },
                                        '15'
                                    ),
                                    React.createElement(
                                        'option',
                                        { value: '25' },
                                        '25'
                                    ),
                                    React.createElement(
                                        'option',
                                        { value: '50' },
                                        '50'
                                    )
                                )
                            ),
                            React.createElement(
                                'div',
                                { className: 'ten wide field column right floated' },
                                React.createElement(
                                    'label',
                                    { 'for': 'filtro' },
                                    'Buscar'
                                ),
                                React.createElement('input', { name: 'filtro', id: 'filtro', type: 'text', value: this.props.filterText, onChange: this.handleFilterTextInputChange })
                            )
                        )
                    )
                )
            );
        }
    }]);

    return Filtros;
}(React.Component);

/*
*
*
*/


var Table = function (_React$Component4) {
    _inherits(Table, _React$Component4);

    function Table(props) {
        _classCallCheck(this, Table);

        return _possibleConstructorReturn(this, (Table.__proto__ || Object.getPrototypeOf(Table)).call(this, props));
    }

    _createClass(Table, [{
        key: 'render',
        value: function render() {
            var rows = [];
            var datos = this.props.datos;
            var click = this.props.onClickDelete;
            var pageclick = this.props.onClickPage;
            var configpage = {
                total_paginas: this.props.total_paginas,
                pag_actual: this.props.pag_actual
            };
            if (datos instanceof Array === false) {
                datos.datos.forEach(function (record) {
                    rows.push(React.createElement(RecordDetalle, { registro: record, onClickDelete: click }));
                });
            }
            var estilo = "display: block !important; top: 1814px;";
            return React.createElement(
                'div',
                null,
                React.createElement(
                    'div',
                    { className: 'ui standard test modal scrolling transition hidden' },
                    React.createElement(
                        'div',
                        { className: 'ui icon header' },
                        React.createElement('i', { className: 'trash outline icon' }),
                        ' Eliminar'
                    ),
                    React.createElement(
                        'div',
                        { className: 'center aligned content ' },
                        React.createElement(
                            'p',
                            null,
                            'El registro seleccionado?'
                        )
                    ),
                    React.createElement(
                        'div',
                        { className: 'actions' },
                        React.createElement(
                            'div',
                            { className: 'ui red cancel button' },
                            React.createElement('i', { className: 'remove icon' }),
                            ' No '
                        ),
                        React.createElement(
                            'div',
                            { className: 'ui green ok  button' },
                            React.createElement('i', { className: 'checkmark icon' }),
                            ' Si '
                        )
                    )
                ),
                React.createElement(
                    'table',
                    { className: 'ui selectable celled table' },
                    React.createElement(
                        'thead',
                        null,
                        React.createElement(Lista, { enca: ['Banco', 'Nombre', 'activo', 'Editar', 'Eliminar'] })
                    ),
                    React.createElement(
                        'tbody',
                        null,
                        rows
                    )
                ),
                React.createElement(Paginacion, { configpage: configpage, onClickPage: pageclick })
            );
        }
    }]);

    return Table;
}(React.Component);

/*
*
*
*/


var Paginacion = function (_React$Component5) {
    _inherits(Paginacion, _React$Component5);

    function Paginacion() {
        _classCallCheck(this, Paginacion);

        return _possibleConstructorReturn(this, (Paginacion.__proto__ || Object.getPrototypeOf(Paginacion)).apply(this, arguments));
    }

    _createClass(Paginacion, [{
        key: 'render',
        value: function render() {
            var rows = [];
            for (var i = 1; i <= this.props.configpage.total_paginas; i++) {
                var activo = "waves-effect";
                if (i === this.props.configpage.pag_actual) {
                    activo = "active waves-effect";
                }
                rows.push(React.createElement(
                    'li',
                    { className: activo },
                    React.createElement(
                        'a',
                        { href: '#!' },
                        i
                    )
                ));
            }

            return React.createElement(
                'ul',
                { className: 'pagination right' },
                React.createElement(
                    'li',
                    { className: 'disabled' },
                    React.createElement(
                        'a',
                        { href: '#!' },
                        React.createElement(
                            'i',
                            { className: 'material-icons' },
                            'chevron_left'
                        )
                    )
                ),
                rows,
                React.createElement(
                    'li',
                    { className: 'waves-effect' },
                    React.createElement(
                        'a',
                        { href: '#!' },
                        React.createElement(
                            'i',
                            { className: 'material-icons' },
                            'chevron_right'
                        )
                    )
                )
            );
        }
    }]);

    return Paginacion;
}(React.Component);

/*
*
*
*/


var Inicio = function (_React$Component6) {
    _inherits(Inicio, _React$Component6);

    function Inicio(props) {
        _classCallCheck(this, Inicio);

        var _this6 = _possibleConstructorReturn(this, (Inicio.__proto__ || Object.getPrototypeOf(Inicio)).call(this, props));

        _this6.state = {
            registro: '15',
            filterText: "",
            data: [],
            page: 0
        };
        _this6.handleFilterTextInput = _this6.handleFilterTextInput.bind(_this6);
        return _this6;
    }

    _createClass(Inicio, [{
        key: 'cargarDatos',
        value: function cargarDatos() {
            var orderby = [];
            orderby.push({ campo: "idbanco",
                direccion: 'ASC' });
            $.ajax({
                url: base_url + '/api/CarteraV1/pagina/' + this.state.page + '/' + this.state.registro,
                type: 'GET',
                dataType: 'json',
                data: {
                    from: 'bancos',
                    orderby: orderby
                },
                success: function (response) {
                    this.setState({ data: response });
                }.bind(this),
                error: function (xhr, status, err) {
                    console.log('error');
                }.bind(this)
            });
        }
    }, {
        key: 'componentDidMount',
        value: function componentDidMount() {
            {
                this.cargarDatos.bind(this)();
            };
        }
    }, {
        key: 'handleFilterTextInput',
        value: function handleFilterTextInput(filterText) {
            this.setState({
                filterText: filterText
            });
        }
    }, {
        key: 'handleSelect',
        value: function handleSelect(registro) {
            this.setState({
                registro: registro
            });
            {
                this.cargarDatos.bind(this)();
            };
        }
    }, {
        key: 'handleUpdateRecord',
        value: function handleUpdateRecord() {
            {
                this.cargarDatos.bind(this)();
            };
        }
    }, {
        key: 'handleUpdatePage',
        value: function handleUpdatePage(page) {
            this.setState({
                page: page
            });
            {
                this.cargarDatos.bind(this)();
            };
        }
    }, {
        key: 'render',
        value: function render() {
            return React.createElement(
                'div',
                null,
                React.createElement(Captura, { onClickAdd: this.handleUpdateRecord.bind(this) }),
                React.createElement(Filtros, {
                    filterText: this.state.filterText,
                    onFilterTextInput: this.handleFilterTextInput,
                    registro: this.state.registro,
                    onSelect: this.handleSelect.bind(this)
                }),
                React.createElement(Table, { datos: this.state.data, onClickDelete: this.handleUpdateRecord.bind(this), onClickPage: this.handleUpdatePage })
            );
        }
    }]);

    return Inicio;
}(React.Component);

ReactDOM.render(React.createElement(Inicio, null), document.getElementById('root'));