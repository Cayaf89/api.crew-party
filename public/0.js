(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[0],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @babel/runtime/regenerator */ "./node_modules/@babel/runtime/regenerator/index.js");
/* harmony import */ var _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _services_fileService__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../services/fileService */ "./resources/js/services/fileService.js");


function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
  name: "ModalCreateUpdateEvent",
  props: {
    onClose: Function,
    event_id: Number,
    crew_id: Number,
    classic_editor: Function,
    callback: Function
  },
  data: function data() {
    return {
      editorConfig: {},
      cover: null,
      coverSrc: '/storage/event/default-cover.jpg',
      name: '',
      description: '<p>Content of the editor.</p>',
      errors: {},
      submitting: false,
      deletting: false
    };
  },
  mounted: function mounted() {
    var _this = this;

    $(this.$el).modal();
    $(this.$el).on('hidden.bs.modal', function () {
      if (_this.$props.callback) {
        _this.$props.callback();
      }

      _this.$props.onClose();
    });

    if (this.$props.event_id) {
      axios.get('/api/event/' + this.$props.event_id).then(function (res) {
        _this.coverSrc = res.data.data.cover;
        _this.name = res.data.data.name;
        _this.description = res.data.data.description;
      });
    }
  },
  methods: {
    inputCover: function () {
      var _inputCover = _asyncToGenerator( /*#__PURE__*/_babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.mark(function _callee(newFile, oldFile) {
        var _this2 = this;

        var data;
        return _babel_runtime_regenerator__WEBPACK_IMPORTED_MODULE_0___default.a.wrap(function _callee$(_context) {
          while (1) {
            switch (_context.prev = _context.next) {
              case 0:
                this.cover = newFile;
                _context.next = 3;
                return Object(_services_fileService__WEBPACK_IMPORTED_MODULE_1__["imageResize"])(URL.createObjectURL(newFile.file), 2400, 900);

              case 3:
                this.cover.file = _context.sent;
                this.coverSrc = URL.createObjectURL(newFile.file);

                if (this.$props.event_id) {
                  data = new FormData();
                  data.append('cover', newFile.file);
                  axios.post('/api/event/' + this.$props.event_id + '/cover/', data).then(function (res) {
                    _this2.coverSrc = res.data.cover;
                  })["catch"](function (error) {
                    toastr.error(error.response.data);
                  });
                }

              case 6:
              case "end":
                return _context.stop();
            }
          }
        }, _callee, this);
      }));

      function inputCover(_x, _x2) {
        return _inputCover.apply(this, arguments);
      }

      return inputCover;
    }(),
    submit: function submit() {
      var _this3 = this;

      if (this.$props.event_id) {
        axios.post('/api/event/' + this.$props.event_id, {
          name: this.name,
          description: this.description
        }).then(function (res) {
          _this3.errors = {};
          $(_this3.$el).modal('hide');
          toastr.success('Event enregistré');
        })["catch"](function (error) {
          var _error$response, _error$response$data;

          if (error === null || error === void 0 ? void 0 : (_error$response = error.response) === null || _error$response === void 0 ? void 0 : (_error$response$data = _error$response.data) === null || _error$response$data === void 0 ? void 0 : _error$response$data.errors) {
            _this3.errors = error.response.data.errors;
          }
        });
      } else {
        var _this$cover;

        var data = new FormData();
        data.append('cover', ((_this$cover = this.cover) === null || _this$cover === void 0 ? void 0 : _this$cover.file) ? this.cover.file : '');
        data.append('name', this.name);
        data.append('description', this.description);
        axios.post('/api/crew/' + this.$props.crew_id + '/event/', data).then(function (res) {
          _this3.errors = {};
          $(_this3.$el).modal('hide');
          toastr.success('Event enregistré');
        })["catch"](function (error) {
          var _error$response2, _error$response2$data;

          if (error === null || error === void 0 ? void 0 : (_error$response2 = error.response) === null || _error$response2 === void 0 ? void 0 : (_error$response2$data = _error$response2.data) === null || _error$response2$data === void 0 ? void 0 : _error$response2$data.errors) {
            _this3.errors = error.response.data.errors;
          }
        });
      }
    },
    deleteEvent: function deleteEvent() {
      var _this4 = this;

      if (this.$props.event_id) {
        axios["delete"]('/api/event/' + this.$props.event_id).then(function (res) {
          $(_this4.$el).modal('hide');
          toastr.success('Event supprimé');
        })["catch"](function (error) {
          toastr.error('Une erreur est survenue');
        });
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css&":
/*!*************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/css-loader??ref--11-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--11-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css& ***!
  \*************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ "./node_modules/css-loader/lib/css-base.js")(false);
// imports


// module
exports.push([module.i, "\n.event-cover[data-v-30f970d4] {\n    background-size: cover;\n    background-position: center;\n    background-repeat: no-repeat;\n}\n.modal-cover-header[data-v-30f970d4] {\n    display: flex;\n    align-items: center;\n    justify-content: center;\n}\n", ""]);

// exports


/***/ }),

/***/ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css&":
/*!*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/style-loader!./node_modules/css-loader??ref--11-1!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src??ref--11-2!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css& ***!
  \*****************************************************************************************************************************************************************************************************************************************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {


var content = __webpack_require__(/*! !../../../../node_modules/css-loader??ref--11-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--11-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css& */ "./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css&");

if(typeof content === 'string') content = [[module.i, content, '']];

var transform;
var insertInto;



var options = {"hmr":true}

options.transform = transform
options.insertInto = undefined;

var update = __webpack_require__(/*! ../../../../node_modules/style-loader/lib/addStyles.js */ "./node_modules/style-loader/lib/addStyles.js")(content, options);

if(content.locals) module.exports = content.locals;

if(false) {}

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=template&id=30f970d4&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=template&id=30f970d4&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "modal", attrs: { tabindex: "-1", role: "dialog" } },
    [
      _c(
        "div",
        {
          staticClass: "modal-dialog modal-dialog-centered modal-lg",
          attrs: { role: "document" }
        },
        [
          _c("div", { staticClass: "modal-content" }, [
            _vm._m(0),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "modal-cover-header" },
              [
                _c(
                  "file-upload",
                  {
                    attrs: {
                      "input-id": "event-cover",
                      accept: "image/*",
                      "min-height": 150
                    },
                    on: { "input-file": _vm.inputCover }
                  },
                  [
                    _c("img", {
                      staticClass: "event-cover",
                      attrs: {
                        src: _vm.coverSrc,
                        height: "300",
                        alt: "event cover"
                      }
                    })
                  ]
                )
              ],
              1
            ),
            _vm._v(" "),
            _c("div", { staticClass: "modal-body" }, [
              _c("div", { staticClass: "container" }, [
                _c("div", { staticClass: "row" }, [
                  _c("div", { staticClass: "offset-3 col-6" }, [
                    _c("label", { attrs: { for: "crew-name" } }, [
                      _vm._v("Nom")
                    ]),
                    _vm._v(" "),
                    _c("input", {
                      directives: [
                        {
                          name: "model",
                          rawName: "v-model",
                          value: _vm.name,
                          expression: "name"
                        }
                      ],
                      staticClass: "form-control",
                      attrs: { id: "crew-name", type: "text" },
                      domProps: { value: _vm.name },
                      on: {
                        input: function($event) {
                          if ($event.target.composing) {
                            return
                          }
                          _vm.name = $event.target.value
                        }
                      }
                    })
                  ])
                ]),
                _vm._v(" "),
                _c("div", { staticClass: "row" }, [
                  _c(
                    "div",
                    { staticClass: "col-12" },
                    [
                      _c("label", [_vm._v("Description")]),
                      _vm._v(" "),
                      _c("ckeditor", {
                        attrs: {
                          editor: _vm.classic_editor,
                          config: _vm.editorConfig
                        },
                        model: {
                          value: _vm.description,
                          callback: function($$v) {
                            _vm.description = $$v
                          },
                          expression: "description"
                        }
                      })
                    ],
                    1
                  )
                ])
              ])
            ]),
            _vm._v(" "),
            _c(
              "div",
              { staticClass: "modal-footer" },
              [
                _vm.$props.event_id
                  ? _c(
                      "loading-button",
                      {
                        staticClass: "btn btn-danger",
                        attrs: {
                          type: "button",
                          loading: _vm.deletting,
                          on_click: _vm.deleteEvent
                        }
                      },
                      [
                        _vm._v(
                          "\n                    Supprimer\n                "
                        )
                      ]
                    )
                  : _vm._e(),
                _vm._v(" "),
                _c(
                  "button",
                  {
                    staticClass: "btn btn-secondary",
                    attrs: { type: "button", "data-dismiss": "modal" }
                  },
                  [_vm._v("\n                    Fermer\n                ")]
                ),
                _vm._v(" "),
                _c(
                  "loading-button",
                  {
                    staticClass: "btn btn-primary",
                    attrs: {
                      type: "button",
                      loading: _vm.submitting,
                      on_click: _vm.submit
                    }
                  },
                  [
                    _vm._v(
                      "\n                    Enregistrer\n                "
                    )
                  ]
                )
              ],
              1
            )
          ])
        ]
      )
    ]
  )
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "modal-header" }, [
      _c("h5", { staticClass: "modal-title" }, [_vm._v("Créer un Event")]),
      _vm._v(" "),
      _c(
        "button",
        {
          staticClass: "close",
          attrs: {
            type: "button",
            "data-dismiss": "modal",
            "aria-label": "Close"
          }
        },
        [_c("span", { attrs: { "aria-hidden": "true" } }, [_vm._v("×")])]
      )
    ])
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/js/components/Modals/ModalCreateUpdateEvent.vue":
/*!*******************************************************************!*\
  !*** ./resources/js/components/Modals/ModalCreateUpdateEvent.vue ***!
  \*******************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _ModalCreateUpdateEvent_vue_vue_type_template_id_30f970d4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./ModalCreateUpdateEvent.vue?vue&type=template&id=30f970d4&scoped=true& */ "./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=template&id=30f970d4&scoped=true&");
/* harmony import */ var _ModalCreateUpdateEvent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./ModalCreateUpdateEvent.vue?vue&type=script&lang=js& */ "./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _ModalCreateUpdateEvent_vue_vue_type_style_index_0_id_30f970d4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css& */ "./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");






/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_3__["default"])(
  _ModalCreateUpdateEvent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _ModalCreateUpdateEvent_vue_vue_type_template_id_30f970d4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _ModalCreateUpdateEvent_vue_vue_type_template_id_30f970d4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "30f970d4",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/Modals/ModalCreateUpdateEvent.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=script&lang=js&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=script&lang=js& ***!
  \********************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./ModalCreateUpdateEvent.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css&":
/*!****************************************************************************************************************************!*\
  !*** ./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css& ***!
  \****************************************************************************************************************************/
/*! no static exports found */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_11_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_11_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_style_index_0_id_30f970d4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/style-loader!../../../../node_modules/css-loader??ref--11-1!../../../../node_modules/vue-loader/lib/loaders/stylePostLoader.js!../../../../node_modules/postcss-loader/src??ref--11-2!../../../../node_modules/vue-loader/lib??vue-loader-options!./ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css& */ "./node_modules/style-loader/index.js!./node_modules/css-loader/index.js?!./node_modules/vue-loader/lib/loaders/stylePostLoader.js!./node_modules/postcss-loader/src/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=style&index=0&id=30f970d4&scoped=true&lang=css&");
/* harmony import */ var _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_11_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_11_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_style_index_0_id_30f970d4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_11_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_11_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_style_index_0_id_30f970d4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__);
/* harmony reexport (unknown) */ for(var __WEBPACK_IMPORT_KEY__ in _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_11_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_11_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_style_index_0_id_30f970d4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__) if(["default"].indexOf(__WEBPACK_IMPORT_KEY__) < 0) (function(key) { __webpack_require__.d(__webpack_exports__, key, function() { return _node_modules_style_loader_index_js_node_modules_css_loader_index_js_ref_11_1_node_modules_vue_loader_lib_loaders_stylePostLoader_js_node_modules_postcss_loader_src_index_js_ref_11_2_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_style_index_0_id_30f970d4_scoped_true_lang_css___WEBPACK_IMPORTED_MODULE_0__[key]; }) }(__WEBPACK_IMPORT_KEY__));


/***/ }),

/***/ "./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=template&id=30f970d4&scoped=true&":
/*!**************************************************************************************************************!*\
  !*** ./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=template&id=30f970d4&scoped=true& ***!
  \**************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_template_id_30f970d4_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./ModalCreateUpdateEvent.vue?vue&type=template&id=30f970d4&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/Modals/ModalCreateUpdateEvent.vue?vue&type=template&id=30f970d4&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_template_id_30f970d4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_ModalCreateUpdateEvent_vue_vue_type_template_id_30f970d4_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ }),

/***/ "./resources/js/services/fileService.js":
/*!**********************************************!*\
  !*** ./resources/js/services/fileService.js ***!
  \**********************************************/
/*! exports provided: imageResizeSquare, imageResize, dataURLToBlob */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "imageResizeSquare", function() { return imageResizeSquare; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "imageResize", function() { return imageResize; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "dataURLToBlob", function() { return dataURLToBlob; });
var imageResizeSquare = function imageResizeSquare(imageData, maxSize) {
  var quality = arguments.length > 2 && arguments[2] !== undefined ? arguments[2] : 1;
  var mimeType = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 'image/jpeg';
  return new Promise(function (resolve, reject) {
    var img = document.createElement('img');

    img.onload = function () {
      var canvas = document.createElement("canvas"); // On adapte la taille du canvas

      var size;

      if (this.height > this.width) {
        size = this.width;
      } else {
        size = this.height;
      }

      canvas.width = size;
      canvas.height = size;
      var dx = 0;
      var dy = 0;

      if (this.width > this.height) {
        dx = -((this.width - this.height) / 2);
      } else if (this.width < this.height) {
        dy = -((this.height - this.width) / 2);
      } // Puis on convertit


      var context = canvas.getContext("2d");
      context.drawImage(this, dx, dy);
      var resizedImage = canvas.toDataURL(mimeType, quality);
      canvas.remove();
      img.remove();
      resolve(dataURLToBlob(resizedImage));
    };

    img.onerror = function () {
      canvas.remove();
      img.remove();
      reject(this);
    };

    img.src = imageData;
  });
};
var imageResize = function imageResize(imageData, maxWidth, maxHeight) {
  var quality = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 1;
  var mimeType = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 'image/jpeg';
  return new Promise(function (resolve, reject) {
    var img = document.createElement('img');

    img.onload = function () {
      var canvas = document.createElement("canvas"); // On détermine le ratio de scale

      if (!maxHeight) maxHeight = this.height;
      if (!maxWidth) maxWidth = this.width;
      var scale = Math.max(maxWidth / this.width, maxHeight / this.height); //On adapte la taille du canvas

      canvas.width = maxWidth;
      canvas.height = maxHeight;
      var dx = 0;
      var dy = 0;

      if (maxWidth < maxHeight) {
        dx = -(Math.abs(maxWidth - this.width) / 4);
      } else if (maxWidth > maxHeight) {
        dy = -(Math.abs(maxHeight - this.height) / 4);
      } // Puis on convertit


      var context = canvas.getContext("2d");
      context.scale(scale, scale);
      context.drawImage(this, dx, dy);
      var resizedImage = canvas.toDataURL(mimeType, quality);
      canvas.remove();
      img.remove();
      resolve(dataURLToBlob(resizedImage));
    };

    img.onerror = function () {
      canvas.remove();
      img.remove();
      reject(this);
    };

    img.src = imageData;
  });
};
var dataURLToBlob = function dataURLToBlob(dataURL) {
  var BASE64_MARKER = ';base64,';
  var parts = null;
  var contentType = null;
  var raw = null;

  if (dataURL.indexOf(BASE64_MARKER) === -1) {
    parts = dataURL.split(',');
    contentType = parts[0].split(':')[1];
    raw = parts[1];
    return new Blob([raw], {
      type: contentType
    });
  }

  parts = dataURL.split(BASE64_MARKER);
  contentType = parts[0].split(':')[1];
  raw = window.atob(parts[1]);
  var rawLength = raw.length;
  var uInt8Array = new Uint8Array(rawLength);

  for (var i = 0; i < rawLength; ++i) {
    uInt8Array[i] = raw.charCodeAt(i);
  }

  return new Blob([uInt8Array], {
    type: contentType
  });
};

/***/ })

}]);