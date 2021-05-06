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
  title: 'LP Pay-per-Post End',
  icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("svg", {
    version: "1.0",
    xmlns: "http://www.w3.org/2000/svg",
    width: "517.000000pt",
    height: "372.000000pt",
    viewBox: "0 0 517.000000 372.000000",
    preserveAspectRatio: "xMidYMid meet"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("g", {
    transform: "translate(0.000000,372.000000) scale(0.100000,-0.100000)",
    fill: "#000000",
    stroke: "none"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 470 0 471 0 -6 29\n-6 29 216 -5 216 -6 0 287 0 287 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2276 3659 l-26 -20 0 -289 0 -288 418 -5 c387 -4 423 -5 500 -25 70\n-19 111 -22 258 -22 97 0 184 4 194 10 10 5 23 24 29 41 14 42 15 525 1 566\n-6 17 -22 35 -36 42 -18 8 -209 11 -668 11 -636 0 -644 0 -670 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M63 2930 c-12 -5 -26 -18 -32 -29 -7 -13 -11 -122 -11 -304 0 -283 0\n-284 23 -305 l23 -22 661 0 662 0 20 26 c20 26 21 39 21 306 0 163 -4 287 -10\n298 -5 10 -24 23 -41 29 -39 13 -1282 14 -1316 1z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1549 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -25 c24 -24 29 -25\n132 -22 l108 3 -3 324 c-1 178 -5 327 -8 332 -7 12 -172 10 -205 -2z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3479 2873 c80 -80 133 -180 152 -287 12 -70 8 -264 -6 -298 -7 -17\n14 -18 363 -18 l371 0 20 26 c20 26 21 39 21 300 0 289 -3 313 -47 333 -16 8\n-168 11 -483 11 l-460 0 69 -67z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4519 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -24 24 -25 289 0\n289 0 21 23 c22 23 22 29 22 309 0 266 -1 287 -19 309 l-19 24 -279 2 c-162 1\n-289 -2 -304 -8z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 2460 l0 -190 305 0 c292 0 306 1 325 20 18 18 20 33 20 184 l0\n163 -52 7 c-29 3 -176 6 -325 6 l-273 0 0 -190z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2990 2465 c0 -142 2 -157 20 -175 26 -26 114 -29 124 -4 12 32 6\n165 -10 202 -17 43 -67 97 -106 118 l-28 15 0 -156z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M40 2170 c-19 -19 -20 -33 -20 -310 0 -277 1 -291 20 -310 19 -19 33\n-20 314 -20 l295 0 20 26 c20 26 21 39 21 304 0 265 -1 278 -21 304 l-20 26\n-295 0 c-281 0 -295 -1 -314 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M781 2164 c-20 -26 -21 -38 -21 -304 0 -266 1 -278 21 -304 l21 -26\n481 2 482 3 0 325 0 325 -482 3 -481 2 -21 -26z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2272 2178 c-7 -7 -12 -29 -12 -51 l0 -38 338 3 c328 3 338 4 389 26\n28 13 61 35 74 48 l22 24 -399 0 c-298 0 -403 -3 -412 -12z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3569 2148 c-37 -73 -134 -160 -228 -206 -83 -40 -84 -41 -57 -52 15\n-5 32 -10 37 -10 6 0 44 -15 85 -34 72 -34 172 -120 231 -201 l22 -30 -2 266\nc-2 247 -3 269 -21 288 -28 31 -43 26 -67 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3750 2168 c-19 -21 -20 -34 -20 -310 0 -275 1 -289 20 -308 20 -20\n33 -20 685 -20 652 0 665 0 685 20 19 19 20 33 20 310 0 277 -1 291 -20 310\n-20 20 -33 20 -685 20 l-664 0 -21 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1617 c0 -36 5 -68 12 -75 14 -14 898 -18 898 -4 0 20 -78 87\n-129 111 l-56 26 -362 3 -363 3 0 -64z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M42 1427 c-22 -23 -22 -29 -22 -309 0 -266 1 -287 19 -309 l19 -24\n667 0 667 0 19 24 c18 22 19 42 19 306 0 270 -1 283 -21 309 l-20 26 -663 0\n-663 0 -21 -23z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1529 1431 l-24 -19 -3 -278 c-2 -188 1 -286 8 -305 16 -39 49 -49\n155 -49 l95 0 1 138 c1 75 2 226 3 334 l1 198 -106 0 c-90 0 -110 -3 -130 -19z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1245 l0 -205 325 0 325 0 0 185 c0 172 -1 186 -20 205 -19 19\n-33 20 -325 20 l-305 0 0 -205z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3012 1430 c-21 -20 -22 -29 -22 -195 0 -128 3 -175 12 -175 29 0\n101 45 136 85 50 57 72 126 72 226 l0 79 -88 0 c-75 0 -92 -3 -110 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3716 1428 c14 -41 7 -250 -10 -320 -22 -96 -79 -202 -144 -270 l-55\n-58 412 0 c444 0 461 2 475 52 3 13 6 146 6 296 l0 273 -25 24 -24 25 -321 0\n-321 0 7 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4495 1425 l-25 -24 0 -275 c0 -183 4 -283 11 -300 20 -44 46 -47\n343 -44 l278 3 19 24 c18 22 19 43 19 310 l0 288 -23 21 c-23 22 -29 22 -311\n22 l-287 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 685 l-25 -24 0 -284 c0 -265 1 -286 19 -308 l19 -24 660 -3\nc655 -2 659 -2 685 19 l27 20 0 271 0 271 -215 -6 -215 -5 6 49 7 49 -472 0\n-472 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3303 679 c-142 -51 -235 -59 -665 -59 l-388 0 0 -267 c0 -148 4\n-273 8 -279 21 -33 50 -34 691 -34 457 0 647 3 665 11 14 7 30 25 36 42 14 41\n13 524 -1 566 -6 17 -19 36 -29 41 -10 6 -67 10 -127 9 -92 -1 -120 -5 -190\n-30z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }))),
  category: 'widgets',
  keywords: ['paywall', 'end-paywall'],
  edit: function edit(props) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("hr", {
      class: "lnpw_pay__gutenberg_block_separator"
    });
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
  title: 'LP Pay-per-View End',
  icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("svg", {
    version: "1.0",
    xmlns: "http://www.w3.org/2000/svg",
    width: "517.000000pt",
    height: "372.000000pt",
    viewBox: "0 0 517.000000 372.000000",
    preserveAspectRatio: "xMidYMid meet"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("g", {
    transform: "translate(0.000000,372.000000) scale(0.100000,-0.100000)",
    fill: "#000000",
    stroke: "none"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 470 0 471 0 -6 29\n-6 29 216 -5 216 -6 0 287 0 287 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2276 3659 l-26 -20 0 -289 0 -288 418 -5 c387 -4 423 -5 500 -25 70\n-19 111 -22 258 -22 97 0 184 4 194 10 10 5 23 24 29 41 14 42 15 525 1 566\n-6 17 -22 35 -36 42 -18 8 -209 11 -668 11 -636 0 -644 0 -670 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M63 2930 c-12 -5 -26 -18 -32 -29 -7 -13 -11 -122 -11 -304 0 -283 0\n-284 23 -305 l23 -22 661 0 662 0 20 26 c20 26 21 39 21 306 0 163 -4 287 -10\n298 -5 10 -24 23 -41 29 -39 13 -1282 14 -1316 1z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1549 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -25 c24 -24 29 -25\n132 -22 l108 3 -3 324 c-1 178 -5 327 -8 332 -7 12 -172 10 -205 -2z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3479 2873 c80 -80 133 -180 152 -287 12 -70 8 -264 -6 -298 -7 -17\n14 -18 363 -18 l371 0 20 26 c20 26 21 39 21 300 0 289 -3 313 -47 333 -16 8\n-168 11 -483 11 l-460 0 69 -67z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4519 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -24 24 -25 289 0\n289 0 21 23 c22 23 22 29 22 309 0 266 -1 287 -19 309 l-19 24 -279 2 c-162 1\n-289 -2 -304 -8z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 2460 l0 -190 305 0 c292 0 306 1 325 20 18 18 20 33 20 184 l0\n163 -52 7 c-29 3 -176 6 -325 6 l-273 0 0 -190z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2990 2465 c0 -142 2 -157 20 -175 26 -26 114 -29 124 -4 12 32 6\n165 -10 202 -17 43 -67 97 -106 118 l-28 15 0 -156z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M40 2170 c-19 -19 -20 -33 -20 -310 0 -277 1 -291 20 -310 19 -19 33\n-20 314 -20 l295 0 20 26 c20 26 21 39 21 304 0 265 -1 278 -21 304 l-20 26\n-295 0 c-281 0 -295 -1 -314 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M781 2164 c-20 -26 -21 -38 -21 -304 0 -266 1 -278 21 -304 l21 -26\n481 2 482 3 0 325 0 325 -482 3 -481 2 -21 -26z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2272 2178 c-7 -7 -12 -29 -12 -51 l0 -38 338 3 c328 3 338 4 389 26\n28 13 61 35 74 48 l22 24 -399 0 c-298 0 -403 -3 -412 -12z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3569 2148 c-37 -73 -134 -160 -228 -206 -83 -40 -84 -41 -57 -52 15\n-5 32 -10 37 -10 6 0 44 -15 85 -34 72 -34 172 -120 231 -201 l22 -30 -2 266\nc-2 247 -3 269 -21 288 -28 31 -43 26 -67 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3750 2168 c-19 -21 -20 -34 -20 -310 0 -275 1 -289 20 -308 20 -20\n33 -20 685 -20 652 0 665 0 685 20 19 19 20 33 20 310 0 277 -1 291 -20 310\n-20 20 -33 20 -685 20 l-664 0 -21 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1617 c0 -36 5 -68 12 -75 14 -14 898 -18 898 -4 0 20 -78 87\n-129 111 l-56 26 -362 3 -363 3 0 -64z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M42 1427 c-22 -23 -22 -29 -22 -309 0 -266 1 -287 19 -309 l19 -24\n667 0 667 0 19 24 c18 22 19 42 19 306 0 270 -1 283 -21 309 l-20 26 -663 0\n-663 0 -21 -23z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1529 1431 l-24 -19 -3 -278 c-2 -188 1 -286 8 -305 16 -39 49 -49\n155 -49 l95 0 1 138 c1 75 2 226 3 334 l1 198 -106 0 c-90 0 -110 -3 -130 -19z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1245 l0 -205 325 0 325 0 0 185 c0 172 -1 186 -20 205 -19 19\n-33 20 -325 20 l-305 0 0 -205z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3012 1430 c-21 -20 -22 -29 -22 -195 0 -128 3 -175 12 -175 29 0\n101 45 136 85 50 57 72 126 72 226 l0 79 -88 0 c-75 0 -92 -3 -110 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3716 1428 c14 -41 7 -250 -10 -320 -22 -96 -79 -202 -144 -270 l-55\n-58 412 0 c444 0 461 2 475 52 3 13 6 146 6 296 l0 273 -25 24 -24 25 -321 0\n-321 0 7 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4495 1425 l-25 -24 0 -275 c0 -183 4 -283 11 -300 20 -44 46 -47\n343 -44 l278 3 19 24 c18 22 19 43 19 310 l0 288 -23 21 c-23 22 -29 22 -311\n22 l-287 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 685 l-25 -24 0 -284 c0 -265 1 -286 19 -308 l19 -24 660 -3\nc655 -2 659 -2 685 19 l27 20 0 271 0 271 -215 -6 -215 -5 6 49 7 49 -472 0\n-472 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3303 679 c-142 -51 -235 -59 -665 -59 l-388 0 0 -267 c0 -148 4\n-273 8 -279 21 -33 50 -34 691 -34 457 0 647 3 665 11 14 7 30 25 36 42 14 41\n13 524 -1 566 -6 17 -19 36 -29 41 -10 6 -67 10 -127 9 -92 -1 -120 -5 -190\n-30z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }))),
  category: 'widgets',
  keywords: ['paywall', 'end-video-paywall'],
  edit: function edit(props) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("hr", {
      class: "lnpw_pay__gutenberg_block_separator"
    });
  },
  save: function save(props) {
    return null;
  }
});

/***/ }),

/***/ "./src/blocks/file.js":
/*!****************************!*\
  !*** ./src/blocks/file.js ***!
  \****************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

var registerBlockType = wp.blocks.registerBlockType;
var _wp$editor = wp.editor,
    InspectorControls = _wp$editor.InspectorControls,
    MediaUpload = _wp$editor.MediaUpload,
    MediaPlaceholder = _wp$editor.MediaPlaceholder;
var _wp$components = wp.components,
    ToggleControl = _wp$components.ToggleControl,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    TextareaControl = _wp$components.TextareaControl,
    Button = _wp$components.Button,
    NumberControl = _wp$components.__experimentalNumberControl,
    SelectControl = _wp$components.SelectControl;
registerBlockType("lightning-paywall/gutenberg-file-block", {
  title: 'LP Pay-per-File',
  icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("svg", {
    version: "1.0",
    xmlns: "http://www.w3.org/2000/svg",
    width: "517.000000pt",
    height: "372.000000pt",
    viewBox: "0 0 517.000000 372.000000",
    preserveAspectRatio: "xMidYMid meet"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("g", {
    transform: "translate(0.000000,372.000000) scale(0.100000,-0.100000)",
    fill: "#000000",
    stroke: "none"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 470 0 471 0 -6 29\n-6 29 216 -5 216 -6 0 287 0 287 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2276 3659 l-26 -20 0 -289 0 -288 418 -5 c387 -4 423 -5 500 -25 70\n-19 111 -22 258 -22 97 0 184 4 194 10 10 5 23 24 29 41 14 42 15 525 1 566\n-6 17 -22 35 -36 42 -18 8 -209 11 -668 11 -636 0 -644 0 -670 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M63 2930 c-12 -5 -26 -18 -32 -29 -7 -13 -11 -122 -11 -304 0 -283 0\n-284 23 -305 l23 -22 661 0 662 0 20 26 c20 26 21 39 21 306 0 163 -4 287 -10\n298 -5 10 -24 23 -41 29 -39 13 -1282 14 -1316 1z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1549 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -25 c24 -24 29 -25\n132 -22 l108 3 -3 324 c-1 178 -5 327 -8 332 -7 12 -172 10 -205 -2z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3479 2873 c80 -80 133 -180 152 -287 12 -70 8 -264 -6 -298 -7 -17\n14 -18 363 -18 l371 0 20 26 c20 26 21 39 21 300 0 289 -3 313 -47 333 -16 8\n-168 11 -483 11 l-460 0 69 -67z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4519 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -24 24 -25 289 0\n289 0 21 23 c22 23 22 29 22 309 0 266 -1 287 -19 309 l-19 24 -279 2 c-162 1\n-289 -2 -304 -8z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 2460 l0 -190 305 0 c292 0 306 1 325 20 18 18 20 33 20 184 l0\n163 -52 7 c-29 3 -176 6 -325 6 l-273 0 0 -190z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2990 2465 c0 -142 2 -157 20 -175 26 -26 114 -29 124 -4 12 32 6\n165 -10 202 -17 43 -67 97 -106 118 l-28 15 0 -156z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M40 2170 c-19 -19 -20 -33 -20 -310 0 -277 1 -291 20 -310 19 -19 33\n-20 314 -20 l295 0 20 26 c20 26 21 39 21 304 0 265 -1 278 -21 304 l-20 26\n-295 0 c-281 0 -295 -1 -314 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M781 2164 c-20 -26 -21 -38 -21 -304 0 -266 1 -278 21 -304 l21 -26\n481 2 482 3 0 325 0 325 -482 3 -481 2 -21 -26z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2272 2178 c-7 -7 -12 -29 -12 -51 l0 -38 338 3 c328 3 338 4 389 26\n28 13 61 35 74 48 l22 24 -399 0 c-298 0 -403 -3 -412 -12z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3569 2148 c-37 -73 -134 -160 -228 -206 -83 -40 -84 -41 -57 -52 15\n-5 32 -10 37 -10 6 0 44 -15 85 -34 72 -34 172 -120 231 -201 l22 -30 -2 266\nc-2 247 -3 269 -21 288 -28 31 -43 26 -67 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3750 2168 c-19 -21 -20 -34 -20 -310 0 -275 1 -289 20 -308 20 -20\n33 -20 685 -20 652 0 665 0 685 20 19 19 20 33 20 310 0 277 -1 291 -20 310\n-20 20 -33 20 -685 20 l-664 0 -21 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1617 c0 -36 5 -68 12 -75 14 -14 898 -18 898 -4 0 20 -78 87\n-129 111 l-56 26 -362 3 -363 3 0 -64z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M42 1427 c-22 -23 -22 -29 -22 -309 0 -266 1 -287 19 -309 l19 -24\n667 0 667 0 19 24 c18 22 19 42 19 306 0 270 -1 283 -21 309 l-20 26 -663 0\n-663 0 -21 -23z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1529 1431 l-24 -19 -3 -278 c-2 -188 1 -286 8 -305 16 -39 49 -49\n155 -49 l95 0 1 138 c1 75 2 226 3 334 l1 198 -106 0 c-90 0 -110 -3 -130 -19z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1245 l0 -205 325 0 325 0 0 185 c0 172 -1 186 -20 205 -19 19\n-33 20 -325 20 l-305 0 0 -205z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3012 1430 c-21 -20 -22 -29 -22 -195 0 -128 3 -175 12 -175 29 0\n101 45 136 85 50 57 72 126 72 226 l0 79 -88 0 c-75 0 -92 -3 -110 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3716 1428 c14 -41 7 -250 -10 -320 -22 -96 -79 -202 -144 -270 l-55\n-58 412 0 c444 0 461 2 475 52 3 13 6 146 6 296 l0 273 -25 24 -24 25 -321 0\n-321 0 7 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4495 1425 l-25 -24 0 -275 c0 -183 4 -283 11 -300 20 -44 46 -47\n343 -44 l278 3 19 24 c18 22 19 43 19 310 l0 288 -23 21 c-23 22 -29 22 -311\n22 l-287 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 685 l-25 -24 0 -284 c0 -265 1 -286 19 -308 l19 -24 660 -3\nc655 -2 659 -2 685 19 l27 20 0 271 0 271 -215 -6 -215 -5 6 49 7 49 -472 0\n-472 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3303 679 c-142 -51 -235 -59 -665 -59 l-388 0 0 -267 c0 -148 4\n-273 8 -279 21 -33 50 -34 691 -34 457 0 647 3 665 11 14 7 30 25 36 42 14 41\n13 524 -1 566 -6 17 -19 36 -29 41 -10 6 -67 10 -127 9 -92 -1 -120 -5 -190\n-30z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }))),
  category: 'widgets',
  keywords: ['paywall', 'file-paywall'],
  attributes: {
    pay_file_block: {
      type: 'boolean',
      default: true
    },
    btc_format: {
      type: 'string'
    },
    file: {
      type: 'string',
      default: ''
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
    },
    currency: {
      type: 'string'
    },
    price: {
      type: 'number'
    },
    duration_type: {
      type: 'string'
    },
    duration: {
      type: 'number'
    }
  },
  edit: function edit(props) {
    var _props$attributes = props.attributes,
        pay_file_block = _props$attributes.pay_file_block,
        btc_format = _props$attributes.btc_format,
        file = _props$attributes.file,
        title = _props$attributes.title,
        description = _props$attributes.description,
        preview = _props$attributes.preview,
        currency = _props$attributes.currency,
        duration_type = _props$attributes.duration_type,
        price = _props$attributes.price,
        duration = _props$attributes.duration,
        setAttributes = props.setAttributes;
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", {
      class: "lnpw_pay__gutenberg_block_file"
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(InspectorControls, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelBody, {
      title: "LP Paywall File",
      initialOpen: true
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(ToggleControl, {
      label: "Enable payment block",
      checked: pay_file_block,
      onChange: function onChange(checked) {
        setAttributes({
          pay_file_block: checked
        });
      },
      value: pay_file_block
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(TextareaControl, {
      label: "Title",
      help: "Enter file title",
      onChange: function onChange(content) {
        setAttributes({
          title: content
        });
      },
      value: title
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(TextareaControl, {
      label: "Description",
      help: "Enter file description",
      onChange: function onChange(desc) {
        setAttributes({
          description: desc
        });
      },
      value: description
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(MediaUpload, {
      onSelect: function onSelect(pic) {
        setAttributes({
          preview: pic.sizes.full.url
        });
      },
      render: function render(_ref) {
        var open = _ref.open;
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(Button, {
          onClick: open
        }, "Video preview");
      }
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Currency",
      value: currency,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          currency: selectedItem
        });
      },
      options: [{
        value: '',
        label: 'Default'
      }, {
        value: 'SATS',
        label: 'SATS'
      }, {
        value: 'BTC',
        label: 'BTC'
      }, {
        value: 'EUR',
        label: 'EUR'
      }, {
        value: 'USD',
        label: 'USD'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Btc format",
      value: btc_format,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          btc_format: selectedItem
        });
      },
      options: [{
        value: 'SATS',
        label: 'SATS'
      }, {
        value: 'BTC',
        label: 'BTC'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(NumberControl, {
      label: "Price",
      value: price,
      onChange: function onChange(nextValue) {
        return setAttributes({
          price: Number(nextValue)
        });
      }
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Duration type",
      value: duration_type,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          duration_type: selectedItem
        });
      },
      options: [{
        value: '',
        label: 'Default'
      }, {
        value: 'minute',
        label: 'Minute'
      }, {
        value: 'hour',
        label: 'Hour'
      }, {
        value: 'week',
        label: 'Week'
      }, {
        value: 'month',
        label: 'Month'
      }, {
        value: 'year',
        label: 'Year'
      }, {
        value: 'onetime',
        label: 'Onetime'
      }, {
        value: 'unlimited',
        label: 'Unlimited'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(NumberControl, {
      label: "Duration",
      value: duration,
      onChange: function onChange(nextValue) {
        return setAttributes({
          duration: Number(nextValue)
        });
      }
    })))), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(MediaPlaceholder, {
      labels: {
        title: 'LP Pay-per-File'
      },
      onSelect: function onSelect(el) {
        return setAttributes({
          file: el.url
        });
      },
      multiple: false,
      onSelectURL: function onSelectURL(el) {
        return setAttributes({
          file: el
        });
      }
    }));
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
var _wp$components = wp.components,
    ToggleControl = _wp$components.ToggleControl,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    SelectControl = _wp$components.SelectControl,
    NumberControl = _wp$components.__experimentalNumberControl;
var InspectorControls = wp.editor.InspectorControls;
registerBlockType("lightning-paywall/gutenberg-start-block", {
  title: 'LP Pay-per-Post Start',
  icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("svg", {
    version: "1.0",
    xmlns: "http://www.w3.org/2000/svg",
    width: "517.000000pt",
    height: "372.000000pt",
    viewBox: "0 0 517.000000 372.000000",
    preserveAspectRatio: "xMidYMid meet"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("g", {
    transform: "translate(0.000000,372.000000) scale(0.100000,-0.100000)",
    fill: "#000000",
    stroke: "none"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 470 0 471 0 -6 29\n-6 29 216 -5 216 -6 0 287 0 287 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2276 3659 l-26 -20 0 -289 0 -288 418 -5 c387 -4 423 -5 500 -25 70\n-19 111 -22 258 -22 97 0 184 4 194 10 10 5 23 24 29 41 14 42 15 525 1 566\n-6 17 -22 35 -36 42 -18 8 -209 11 -668 11 -636 0 -644 0 -670 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M63 2930 c-12 -5 -26 -18 -32 -29 -7 -13 -11 -122 -11 -304 0 -283 0\n-284 23 -305 l23 -22 661 0 662 0 20 26 c20 26 21 39 21 306 0 163 -4 287 -10\n298 -5 10 -24 23 -41 29 -39 13 -1282 14 -1316 1z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1549 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -25 c24 -24 29 -25\n132 -22 l108 3 -3 324 c-1 178 -5 327 -8 332 -7 12 -172 10 -205 -2z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3479 2873 c80 -80 133 -180 152 -287 12 -70 8 -264 -6 -298 -7 -17\n14 -18 363 -18 l371 0 20 26 c20 26 21 39 21 300 0 289 -3 313 -47 333 -16 8\n-168 11 -483 11 l-460 0 69 -67z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4519 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -24 24 -25 289 0\n289 0 21 23 c22 23 22 29 22 309 0 266 -1 287 -19 309 l-19 24 -279 2 c-162 1\n-289 -2 -304 -8z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 2460 l0 -190 305 0 c292 0 306 1 325 20 18 18 20 33 20 184 l0\n163 -52 7 c-29 3 -176 6 -325 6 l-273 0 0 -190z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2990 2465 c0 -142 2 -157 20 -175 26 -26 114 -29 124 -4 12 32 6\n165 -10 202 -17 43 -67 97 -106 118 l-28 15 0 -156z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M40 2170 c-19 -19 -20 -33 -20 -310 0 -277 1 -291 20 -310 19 -19 33\n-20 314 -20 l295 0 20 26 c20 26 21 39 21 304 0 265 -1 278 -21 304 l-20 26\n-295 0 c-281 0 -295 -1 -314 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M781 2164 c-20 -26 -21 -38 -21 -304 0 -266 1 -278 21 -304 l21 -26\n481 2 482 3 0 325 0 325 -482 3 -481 2 -21 -26z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2272 2178 c-7 -7 -12 -29 -12 -51 l0 -38 338 3 c328 3 338 4 389 26\n28 13 61 35 74 48 l22 24 -399 0 c-298 0 -403 -3 -412 -12z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3569 2148 c-37 -73 -134 -160 -228 -206 -83 -40 -84 -41 -57 -52 15\n-5 32 -10 37 -10 6 0 44 -15 85 -34 72 -34 172 -120 231 -201 l22 -30 -2 266\nc-2 247 -3 269 -21 288 -28 31 -43 26 -67 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3750 2168 c-19 -21 -20 -34 -20 -310 0 -275 1 -289 20 -308 20 -20\n33 -20 685 -20 652 0 665 0 685 20 19 19 20 33 20 310 0 277 -1 291 -20 310\n-20 20 -33 20 -685 20 l-664 0 -21 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1617 c0 -36 5 -68 12 -75 14 -14 898 -18 898 -4 0 20 -78 87\n-129 111 l-56 26 -362 3 -363 3 0 -64z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M42 1427 c-22 -23 -22 -29 -22 -309 0 -266 1 -287 19 -309 l19 -24\n667 0 667 0 19 24 c18 22 19 42 19 306 0 270 -1 283 -21 309 l-20 26 -663 0\n-663 0 -21 -23z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1529 1431 l-24 -19 -3 -278 c-2 -188 1 -286 8 -305 16 -39 49 -49\n155 -49 l95 0 1 138 c1 75 2 226 3 334 l1 198 -106 0 c-90 0 -110 -3 -130 -19z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1245 l0 -205 325 0 325 0 0 185 c0 172 -1 186 -20 205 -19 19\n-33 20 -325 20 l-305 0 0 -205z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3012 1430 c-21 -20 -22 -29 -22 -195 0 -128 3 -175 12 -175 29 0\n101 45 136 85 50 57 72 126 72 226 l0 79 -88 0 c-75 0 -92 -3 -110 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3716 1428 c14 -41 7 -250 -10 -320 -22 -96 -79 -202 -144 -270 l-55\n-58 412 0 c444 0 461 2 475 52 3 13 6 146 6 296 l0 273 -25 24 -24 25 -321 0\n-321 0 7 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4495 1425 l-25 -24 0 -275 c0 -183 4 -283 11 -300 20 -44 46 -47\n343 -44 l278 3 19 24 c18 22 19 43 19 310 l0 288 -23 21 c-23 22 -29 22 -311\n22 l-287 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 685 l-25 -24 0 -284 c0 -265 1 -286 19 -308 l19 -24 660 -3\nc655 -2 659 -2 685 19 l27 20 0 271 0 271 -215 -6 -215 -5 6 49 7 49 -472 0\n-472 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3303 679 c-142 -51 -235 -59 -665 -59 l-388 0 0 -267 c0 -148 4\n-273 8 -279 21 -33 50 -34 691 -34 457 0 647 3 665 11 14 7 30 25 36 42 14 41\n13 524 -1 566 -6 17 -19 36 -29 41 -10 6 -67 10 -127 9 -92 -1 -120 -5 -190\n-30z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }))),
  category: 'widgets',
  keywords: ['paywall', 'start-paywall'],
  attributes: {
    pay_block: {
      type: 'boolean',
      default: true
    },
    btc_format: {
      type: 'string'
    },
    currency: {
      type: 'string'
    },
    price: {
      type: 'number'
    },
    duration_type: {
      type: 'string'
    },
    duration: {
      type: 'number'
    }
  },
  edit: function edit(props) {
    var _props$attributes = props.attributes,
        pay_block = _props$attributes.pay_block,
        btc_format = _props$attributes.btc_format,
        currency = _props$attributes.currency,
        duration_type = _props$attributes.duration_type,
        price = _props$attributes.price,
        duration = _props$attributes.duration,
        setAttributes = props.setAttributes;
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("hr", {
      class: "lnpw_pay__gutenberg_block_separator"
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(InspectorControls, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelBody, {
      title: "LP Paywall Text",
      initialOpen: true
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(ToggleControl, {
      label: "Enable paywall",
      checked: pay_block,
      onChange: function onChange(checked) {
        setAttributes({
          pay_block: checked
        });
      },
      value: pay_block
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Currency",
      value: currency,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          currency: selectedItem
        });
      },
      options: [{
        value: '',
        label: 'Default'
      }, {
        value: 'SATS',
        label: 'SATS'
      }, {
        value: 'EUR',
        label: 'EUR'
      }, {
        value: 'USD',
        label: 'USD'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Btc format",
      value: btc_format,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          btc_format: selectedItem
        });
      },
      options: [{
        value: 'SATS',
        label: 'SATS'
      }, {
        value: 'BTC',
        label: 'BTC'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(NumberControl, {
      label: "Price",
      value: price,
      onChange: function onChange(nextValue) {
        return setAttributes({
          price: Number(nextValue)
        });
      }
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Duration type",
      value: duration_type,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          duration_type: selectedItem
        });
      },
      options: [{
        value: '',
        label: 'Default'
      }, {
        value: 'minute',
        label: 'Minute'
      }, {
        value: 'hour',
        label: 'Hour'
      }, {
        value: 'week',
        label: 'Week'
      }, {
        value: 'month',
        label: 'Month'
      }, {
        value: 'year',
        label: 'Year'
      }, {
        value: 'onetime',
        label: 'Onetime'
      }, {
        value: 'unlimited',
        label: 'Unlimited'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(NumberControl, {
      label: "Duration",
      value: duration,
      onChange: function onChange(nextValue) {
        return setAttributes({
          duration: Number(nextValue)
        });
      }
    })))));
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
    MediaUpload = _wp$editor.MediaUpload;
var _wp$components = wp.components,
    ToggleControl = _wp$components.ToggleControl,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    TextareaControl = _wp$components.TextareaControl,
    Button = _wp$components.Button,
    NumberControl = _wp$components.__experimentalNumberControl,
    SelectControl = _wp$components.SelectControl;
registerBlockType("lightning-paywall/gutenberg-start-video-block", {
  title: 'LP Pay-per-View Start',
  icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("svg", {
    version: "1.0",
    xmlns: "http://www.w3.org/2000/svg",
    width: "517.000000pt",
    height: "372.000000pt",
    viewBox: "0 0 517.000000 372.000000",
    preserveAspectRatio: "xMidYMid meet"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("g", {
    transform: "translate(0.000000,372.000000) scale(0.100000,-0.100000)",
    fill: "#000000",
    stroke: "none"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 470 0 471 0 -6 29\n-6 29 216 -5 216 -6 0 287 0 287 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2276 3659 l-26 -20 0 -289 0 -288 418 -5 c387 -4 423 -5 500 -25 70\n-19 111 -22 258 -22 97 0 184 4 194 10 10 5 23 24 29 41 14 42 15 525 1 566\n-6 17 -22 35 -36 42 -18 8 -209 11 -668 11 -636 0 -644 0 -670 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M63 2930 c-12 -5 -26 -18 -32 -29 -7 -13 -11 -122 -11 -304 0 -283 0\n-284 23 -305 l23 -22 661 0 662 0 20 26 c20 26 21 39 21 306 0 163 -4 287 -10\n298 -5 10 -24 23 -41 29 -39 13 -1282 14 -1316 1z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1549 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -25 c24 -24 29 -25\n132 -22 l108 3 -3 324 c-1 178 -5 327 -8 332 -7 12 -172 10 -205 -2z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3479 2873 c80 -80 133 -180 152 -287 12 -70 8 -264 -6 -298 -7 -17\n14 -18 363 -18 l371 0 20 26 c20 26 21 39 21 300 0 289 -3 313 -47 333 -16 8\n-168 11 -483 11 l-460 0 69 -67z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4519 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -24 24 -25 289 0\n289 0 21 23 c22 23 22 29 22 309 0 266 -1 287 -19 309 l-19 24 -279 2 c-162 1\n-289 -2 -304 -8z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 2460 l0 -190 305 0 c292 0 306 1 325 20 18 18 20 33 20 184 l0\n163 -52 7 c-29 3 -176 6 -325 6 l-273 0 0 -190z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2990 2465 c0 -142 2 -157 20 -175 26 -26 114 -29 124 -4 12 32 6\n165 -10 202 -17 43 -67 97 -106 118 l-28 15 0 -156z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M40 2170 c-19 -19 -20 -33 -20 -310 0 -277 1 -291 20 -310 19 -19 33\n-20 314 -20 l295 0 20 26 c20 26 21 39 21 304 0 265 -1 278 -21 304 l-20 26\n-295 0 c-281 0 -295 -1 -314 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M781 2164 c-20 -26 -21 -38 -21 -304 0 -266 1 -278 21 -304 l21 -26\n481 2 482 3 0 325 0 325 -482 3 -481 2 -21 -26z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2272 2178 c-7 -7 -12 -29 -12 -51 l0 -38 338 3 c328 3 338 4 389 26\n28 13 61 35 74 48 l22 24 -399 0 c-298 0 -403 -3 -412 -12z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3569 2148 c-37 -73 -134 -160 -228 -206 -83 -40 -84 -41 -57 -52 15\n-5 32 -10 37 -10 6 0 44 -15 85 -34 72 -34 172 -120 231 -201 l22 -30 -2 266\nc-2 247 -3 269 -21 288 -28 31 -43 26 -67 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3750 2168 c-19 -21 -20 -34 -20 -310 0 -275 1 -289 20 -308 20 -20\n33 -20 685 -20 652 0 665 0 685 20 19 19 20 33 20 310 0 277 -1 291 -20 310\n-20 20 -33 20 -685 20 l-664 0 -21 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1617 c0 -36 5 -68 12 -75 14 -14 898 -18 898 -4 0 20 -78 87\n-129 111 l-56 26 -362 3 -363 3 0 -64z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M42 1427 c-22 -23 -22 -29 -22 -309 0 -266 1 -287 19 -309 l19 -24\n667 0 667 0 19 24 c18 22 19 42 19 306 0 270 -1 283 -21 309 l-20 26 -663 0\n-663 0 -21 -23z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1529 1431 l-24 -19 -3 -278 c-2 -188 1 -286 8 -305 16 -39 49 -49\n155 -49 l95 0 1 138 c1 75 2 226 3 334 l1 198 -106 0 c-90 0 -110 -3 -130 -19z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1245 l0 -205 325 0 325 0 0 185 c0 172 -1 186 -20 205 -19 19\n-33 20 -325 20 l-305 0 0 -205z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3012 1430 c-21 -20 -22 -29 -22 -195 0 -128 3 -175 12 -175 29 0\n101 45 136 85 50 57 72 126 72 226 l0 79 -88 0 c-75 0 -92 -3 -110 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3716 1428 c14 -41 7 -250 -10 -320 -22 -96 -79 -202 -144 -270 l-55\n-58 412 0 c444 0 461 2 475 52 3 13 6 146 6 296 l0 273 -25 24 -24 25 -321 0\n-321 0 7 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4495 1425 l-25 -24 0 -275 c0 -183 4 -283 11 -300 20 -44 46 -47\n343 -44 l278 3 19 24 c18 22 19 43 19 310 l0 288 -23 21 c-23 22 -29 22 -311\n22 l-287 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 685 l-25 -24 0 -284 c0 -265 1 -286 19 -308 l19 -24 660 -3\nc655 -2 659 -2 685 19 l27 20 0 271 0 271 -215 -6 -215 -5 6 49 7 49 -472 0\n-472 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3303 679 c-142 -51 -235 -59 -665 -59 l-388 0 0 -267 c0 -148 4\n-273 8 -279 21 -33 50 -34 691 -34 457 0 647 3 665 11 14 7 30 25 36 42 14 41\n13 524 -1 566 -6 17 -19 36 -29 41 -10 6 -67 10 -127 9 -92 -1 -120 -5 -190\n-30z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }))),
  category: 'widgets',
  keywords: ['paywall', 'start-video-paywall'],
  attributes: {
    pay_view_block: {
      type: 'boolean',
      default: true
    },
    btc_format: {
      type: 'string'
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
    },
    currency: {
      type: 'string'
    },
    price: {
      type: 'number'
    },
    duration_type: {
      type: 'string'
    },
    duration: {
      type: 'number'
    }
  },
  edit: function edit(props) {
    var _props$attributes = props.attributes,
        pay_view_block = _props$attributes.pay_view_block,
        btc_format = _props$attributes.btc_format,
        title = _props$attributes.title,
        description = _props$attributes.description,
        preview = _props$attributes.preview,
        currency = _props$attributes.currency,
        duration_type = _props$attributes.duration_type,
        price = _props$attributes.price,
        duration = _props$attributes.duration,
        setAttributes = props.setAttributes;
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("div", null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("hr", {
      class: "lnpw_pay__gutenberg_block_separator"
    }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(InspectorControls, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelBody, {
      title: "LP Paywall Video",
      initialOpen: true
    }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(ToggleControl, {
      label: "Enable payment block",
      checked: pay_view_block,
      onChange: function onChange(checked) {
        setAttributes({
          pay_view_block: checked
        });
      },
      value: pay_view_block
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(TextareaControl, {
      label: "Title",
      help: "Enter video title",
      onChange: function onChange(content) {
        setAttributes({
          title: content
        });
      },
      value: title
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(TextareaControl, {
      label: "Description",
      help: "Enter video description",
      onChange: function onChange(desc) {
        setAttributes({
          description: desc
        });
      },
      value: description
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(MediaUpload, {
      onSelect: function onSelect(pic) {
        setAttributes({
          preview: pic.sizes.full.url
        });
      },
      render: function render(_ref) {
        var open = _ref.open;
        return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(Button, {
          onClick: open
        }, "Video preview");
      }
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Currency",
      value: currency,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          currency: selectedItem
        });
      },
      options: [{
        value: '',
        label: 'Default'
      }, {
        value: 'SATS',
        label: 'SATS'
      }, {
        value: 'BTC',
        label: 'BTC'
      }, {
        value: 'EUR',
        label: 'EUR'
      }, {
        value: 'USD',
        label: 'USD'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Btc format",
      value: btc_format,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          btc_format: selectedItem
        });
      },
      options: [{
        value: 'SATS',
        label: 'SATS'
      }, {
        value: 'BTC',
        label: 'BTC'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(NumberControl, {
      label: "Price",
      value: price,
      onChange: function onChange(nextValue) {
        return setAttributes({
          price: Number(nextValue)
        });
      }
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(SelectControl, {
      label: "Duration type",
      value: duration_type,
      onChange: function onChange(selectedItem) {
        return setAttributes({
          duration_type: selectedItem
        });
      },
      options: [{
        value: '',
        label: 'Default'
      }, {
        value: 'minute',
        label: 'Minute'
      }, {
        value: 'hour',
        label: 'Hour'
      }, {
        value: 'week',
        label: 'Week'
      }, {
        value: 'month',
        label: 'Month'
      }, {
        value: 'year',
        label: 'Year'
      }, {
        value: 'onetime',
        label: 'Onetime'
      }, {
        value: 'unlimited',
        label: 'Unlimited'
      }]
    })), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(PanelRow, null, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])(NumberControl, {
      label: "Duration",
      value: duration,
      onChange: function onChange(nextValue) {
        return setAttributes({
          duration: Number(nextValue)
        });
      }
    })))));
  },
  save: function save(props) {
    return null;
  }
});

/***/ }),

/***/ "./src/blocks/video_catalog.js":
/*!*************************************!*\
  !*** ./src/blocks/video_catalog.js ***!
  \*************************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

var registerBlockType = wp.blocks.registerBlockType;
registerBlockType("lightning-paywall/gutenberg-catalog-view", {
  title: 'LP Video Catalog',
  icon: Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("svg", {
    version: "1.0",
    xmlns: "http://www.w3.org/2000/svg",
    width: "517.000000pt",
    height: "372.000000pt",
    viewBox: "0 0 517.000000 372.000000",
    preserveAspectRatio: "xMidYMid meet"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("g", {
    transform: "translate(0.000000,372.000000) scale(0.100000,-0.100000)",
    fill: "#000000",
    stroke: "none"
  }, Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 470 0 471 0 -6 29\n-6 29 216 -5 216 -6 0 287 0 287 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2276 3659 l-26 -20 0 -289 0 -288 418 -5 c387 -4 423 -5 500 -25 70\n-19 111 -22 258 -22 97 0 184 4 194 10 10 5 23 24 29 41 14 42 15 525 1 566\n-6 17 -22 35 -36 42 -18 8 -209 11 -668 11 -636 0 -644 0 -670 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 3655 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M63 2930 c-12 -5 -26 -18 -32 -29 -7 -13 -11 -122 -11 -304 0 -283 0\n-284 23 -305 l23 -22 661 0 662 0 20 26 c20 26 21 39 21 306 0 163 -4 287 -10\n298 -5 10 -24 23 -41 29 -39 13 -1282 14 -1316 1z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1549 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -25 c24 -24 29 -25\n132 -22 l108 3 -3 324 c-1 178 -5 327 -8 332 -7 12 -172 10 -205 -2z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3479 2873 c80 -80 133 -180 152 -287 12 -70 8 -264 -6 -298 -7 -17\n14 -18 363 -18 l371 0 20 26 c20 26 21 39 21 300 0 289 -3 313 -47 333 -16 8\n-168 11 -483 11 l-460 0 69 -67z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4519 2929 c-46 -17 -49 -40 -49 -335 l0 -275 25 -24 24 -25 289 0\n289 0 21 23 c22 23 22 29 22 309 0 266 -1 287 -19 309 l-19 24 -279 2 c-162 1\n-289 -2 -304 -8z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 2460 l0 -190 305 0 c292 0 306 1 325 20 18 18 20 33 20 184 l0\n163 -52 7 c-29 3 -176 6 -325 6 l-273 0 0 -190z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2990 2465 c0 -142 2 -157 20 -175 26 -26 114 -29 124 -4 12 32 6\n165 -10 202 -17 43 -67 97 -106 118 l-28 15 0 -156z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M40 2170 c-19 -19 -20 -33 -20 -310 0 -277 1 -291 20 -310 19 -19 33\n-20 314 -20 l295 0 20 26 c20 26 21 39 21 304 0 265 -1 278 -21 304 l-20 26\n-295 0 c-281 0 -295 -1 -314 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M781 2164 c-20 -26 -21 -38 -21 -304 0 -266 1 -278 21 -304 l21 -26\n481 2 482 3 0 325 0 325 -482 3 -481 2 -21 -26z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2272 2178 c-7 -7 -12 -29 -12 -51 l0 -38 338 3 c328 3 338 4 389 26\n28 13 61 35 74 48 l22 24 -399 0 c-298 0 -403 -3 -412 -12z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3569 2148 c-37 -73 -134 -160 -228 -206 -83 -40 -84 -41 -57 -52 15\n-5 32 -10 37 -10 6 0 44 -15 85 -34 72 -34 172 -120 231 -201 l22 -30 -2 266\nc-2 247 -3 269 -21 288 -28 31 -43 26 -67 -21z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3750 2168 c-19 -21 -20 -34 -20 -310 0 -275 1 -289 20 -308 20 -20\n33 -20 685 -20 652 0 665 0 685 20 19 19 20 33 20 310 0 277 -1 291 -20 310\n-20 20 -33 20 -685 20 l-664 0 -21 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1617 c0 -36 5 -68 12 -75 14 -14 898 -18 898 -4 0 20 -78 87\n-129 111 l-56 26 -362 3 -363 3 0 -64z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M42 1427 c-22 -23 -22 -29 -22 -309 0 -266 1 -287 19 -309 l19 -24\n667 0 667 0 19 24 c18 22 19 42 19 306 0 270 -1 283 -21 309 l-20 26 -663 0\n-663 0 -21 -23z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M1529 1431 l-24 -19 -3 -278 c-2 -188 1 -286 8 -305 16 -39 49 -49\n155 -49 l95 0 1 138 c1 75 2 226 3 334 l1 198 -106 0 c-90 0 -110 -3 -130 -19z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M2260 1245 l0 -205 325 0 325 0 0 185 c0 172 -1 186 -20 205 -19 19\n-33 20 -325 20 l-305 0 0 -205z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3012 1430 c-21 -20 -22 -29 -22 -195 0 -128 3 -175 12 -175 29 0\n101 45 136 85 50 57 72 126 72 226 l0 79 -88 0 c-75 0 -92 -3 -110 -20z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3716 1428 c14 -41 7 -250 -10 -320 -22 -96 -79 -202 -144 -270 l-55\n-58 412 0 c444 0 461 2 475 52 3 13 6 146 6 296 l0 273 -25 24 -24 25 -321 0\n-321 0 7 -22z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M4495 1425 l-25 -24 0 -275 c0 -183 4 -283 11 -300 20 -44 46 -47\n343 -44 l278 3 19 24 c18 22 19 43 19 310 l0 288 -23 21 c-23 22 -29 22 -311\n22 l-287 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M45 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 284 0 c266 0 286 1\n308 19 l24 19 0 297 0 297 -24 19 c-22 18 -42 19 -308 19 l-284 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M785 685 l-25 -24 0 -284 c0 -265 1 -286 19 -308 l19 -24 660 -3\nc655 -2 659 -2 685 19 l27 20 0 271 0 271 -215 -6 -215 -5 6 49 7 49 -472 0\n-472 0 -24 -25z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3303 679 c-142 -51 -235 -59 -665 -59 l-388 0 0 -267 c0 -148 4\n-273 8 -279 21 -33 50 -34 691 -34 457 0 647 3 665 11 14 7 30 25 36 42 14 41\n13 524 -1 566 -6 17 -19 36 -29 41 -10 6 -67 10 -127 9 -92 -1 -120 -5 -190\n-30z"
  }), Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("path", {
    d: "M3755 685 l-25 -24 0 -286 0 -286 25 -24 24 -25 656 0 656 0 24 25\n25 24 0 286 0 286 -25 24 -24 25 -656 0 -656 0 -24 -25z"
  }))),
  category: 'widgets',
  keywords: ['paywall', 'end-video-paywall'],
  edit: function edit(props) {
    return Object(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__["createElement"])("hr", {
      class: "lnpw_pay__gutenberg_block_separator"
    });
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
/* harmony import */ var _blocks_video_catalog__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./blocks/video_catalog */ "./src/blocks/video_catalog.js");
/* harmony import */ var _blocks_file__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./blocks/file */ "./src/blocks/file.js");







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