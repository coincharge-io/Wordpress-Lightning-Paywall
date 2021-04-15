/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = "./src/index.js");
/******/ })
/************************************************************************/
/******/ ({

/***/ "./src/blocks/end_content.js":
/*!***********************************!*\
  !*** ./src/blocks/end_content.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

var registerBlockType = wp.blocks.registerBlockType;
registerBlockType("lightning-paywall/gutenberg-end-block", {
  title: 'LP End Paid Text Content',
  icon: 'tagcloud',
  category: 'widgets',
  keywords: ['paywall', 'end-paywall'],
  edit: function edit(props) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("h2", null, " ");
  },
  save: function save(props) {
    return null;
  }
});

/***/ }),

/***/ "./src/blocks/end_video.js":
/*!*********************************!*\
  !*** ./src/blocks/end_video.js ***!
  \*********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

var registerBlockType = wp.blocks.registerBlockType;
registerBlockType("lightning-paywall/gutenberg-end-video-block", {
  title: 'LP End Paid Video Content',
  icon: 'tagcloud',
  category: 'widgets',
  keywords: ['paywall', 'end-video-paywall'],
  edit: function edit(props) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("h2", null, " ");
  },
  save: function save(props) {
    return null;
  }
});

/***/ }),

/***/ "./src/blocks/start_content.js":
/*!*************************************!*\
  !*** ./src/blocks/start_content.js ***!
  \*************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

var registerBlockType = wp.blocks.registerBlockType;
var ToggleControl = wp.components.ToggleControl;
registerBlockType("lightning-paywall/gutenberg-start-block", {
  title: 'LP Start Paid Text Content',
  icon: 'tagcloud',
  category: 'widgets',
  keywords: ['paywall', 'start-paywall'],
  attributes: {
    pay_block: {
      type: 'boolean',
      default: false
    }
  },
  edit: function edit(props) {
    var pay_block = props.attributes.pay_block,
        setAttributes = props.setAttributes;
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(ToggleControl, {
      label: "Enable paywall",
      checked: pay_block,
      onChange: function onChange(checked) {
        setAttributes({
          pay_block: checked
        });
      },
      value: pay_block
    }));
  },
  save: function save(props) {
    return null;
  }
});

/***/ }),

/***/ "./src/blocks/start_video.js":
/*!***********************************!*\
  !*** ./src/blocks/start_video.js ***!
  \***********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

var registerBlockType = wp.blocks.registerBlockType;
var _wp$editor = wp.editor,
    InspectorControls = _wp$editor.InspectorControls,
    RichText = _wp$editor.RichText,
    MediaUpload = _wp$editor.MediaUpload;
var _wp$components = wp.components,
    ToggleControl = _wp$components.ToggleControl,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow;
registerBlockType("lightning-paywall/gutenberg-start-video-block", {
  title: 'LP Start Paid Video Content',
  icon: 'tagcloud',
  category: 'widgets',
  keywords: ['paywall', 'start-video-paywall'],
  attributes: {
    pay_view_block: {
      type: 'boolean',
      default: false
    },
    title: {
      type: 'string',
      default: 'Untitled'
    },
    description: {
      type: 'string',
      default: 'No description'
    },
    preview: {
      type: 'string',
      default: ''
    }
  },
  edit: function edit(props) {
    var _props$attributes = props.attributes,
        pay_view_block = _props$attributes.pay_view_block,
        title = _props$attributes.title,
        description = _props$attributes.description,
        preview = _props$attributes.preview,
        setAttributes = props.setAttributes;
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(InspectorControls, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelBody, {
      title: "LP Paywall",
      initialOpen: true
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(ToggleControl, {
      label: "Enable paywall",
      checked: pay_view_block,
      onChange: function onChange(checked) {
        setAttributes({
          pay_view_block: checked
        });
      },
      value: pay_view_block
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(RichText, {
      label: "Title",
      onChange: function onChange(content) {
        setAttributes({
          title: content
        });
      },
      value: title
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(RichText, {
      onChange: function onChange(desc) {
        setAttributes({
          description: desc
        });
      },
      value: description
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("strong", null, "Select a preview image:"), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(MediaUpload, {
      onSelect: function onSelect(pic) {
        setAttributes({
          preview: pic.sizes.full.url
        });
      },
      render: function render(_ref) {
        var open = _ref.open;
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("button", {
          onClick: open
        }, "Upload Image!");
      }
    })))));
  },
  save: function save(props) {
    return null;
  }
});

/***/ }),

/***/ "./src/blocks/store_view.js":
/*!**********************************!*\
  !*** ./src/blocks/store_view.js ***!
  \**********************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

var registerBlockType = wp.blocks.registerBlockType;
registerBlockType("lightning-paywall/gutenberg-store-view", {
  title: 'LP Video Store',
  icon: 'tagcloud',
  category: 'widgets',
  keywords: ['paywall', 'end-video-paywall'],
  edit: function edit(props) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("h2", null, " LP Video Store ");
  },
  save: function save(props) {
    return null;
  }
});

/***/ }),

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _blocks_start_content__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./blocks/start_content */ "./src/blocks/start_content.js");
/* harmony import */ var _blocks_end_content__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./blocks/end_content */ "./src/blocks/end_content.js");
/* harmony import */ var _blocks_start_video__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./blocks/start_video */ "./src/blocks/start_video.js");
/* harmony import */ var _blocks_end_video__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./blocks/end_video */ "./src/blocks/end_video.js");
/* harmony import */ var _blocks_store_view__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./blocks/store_view */ "./src/blocks/store_view.js");






/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/*! no static exports found */
/***/ (function(module, exports) {

(function() { module.exports = window["wp"]["element"]; }());

/***/ })

/******/ });
//# sourceMappingURL=index.js.map