/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/Block/show-sub-tax-class/Block/block.js":
/*!*****************************************************!*\
  !*** ./src/Block/show-sub-tax-class/Block/block.js ***!
  \*****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Block: () => (/* binding */ Block)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @woocommerce/blocks-checkout */ "@woocommerce/blocks-checkout");
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _portal__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./portal */ "./src/Block/show-sub-tax-class/Block/portal.js");

/**
 * External dependencies
 */





/**
 * Internal dependencies
 */

function TaxClass({
  name
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
    className: "wc-block-components-product-details__tax-class"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "wc-block-components-product-details__name"
  }, "Tax Class:"), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "wc-block-components-product-details__value"
  }, name));
}
function SubTaxClass({
  value,
  options,
  onChange
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("li", {
    className: "wc-block-components-product-details__sub_tax-class"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)('Sub-Tax Class', 'taxdo'),
    value: value,
    options: options,
    onChange: onChange,
    size: "compact"
  }));
}
const Block = ({
  cart,
  validation,
  checkoutExtensionData,
  extensions
}) => {
  const {
    state,
    country
  } = cart.billingAddress;
  const [subTaxClasses, setSubTaxClasses] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)();
  const [cartItems, setCartItems] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)();
  const [itemSubTaxClasses, setItemSubTaxClasses] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(() => {
    return cart.cartItems.map(item => {
      return {
        id: item.id,
        key: item.key,
        value: -1
      };
    });
  });
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (itemSubTaxClasses.length) {
      checkoutExtensionData?.setExtensionData('taxdo', 'itemSubTaxClasses', itemSubTaxClasses);
      (0,_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_2__.extensionCartUpdate)({
        namespace: 'taxdo',
        data: {
          action: 'change-sub-tax-class',
          item_sub_tax_classes: itemSubTaxClasses
        }
      });
    }
  }, [itemSubTaxClasses]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    fetch(`/wp-json/taxdo/v1/states/${state}/sub-tax-classes`).then(res => res.json()).then(res => {
      setSubTaxClasses(res.data);
    }).catch(err => {});
  }, [state]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    const ref = setInterval(() => {
      if (cartItems) {
        clearInterval(ref);
        return;
      }
      const items = document.querySelectorAll('.taxdo-cart-item');
      if (items.length) {
        const newRoots = [...items].map(el => {
          return {
            root: el.parentElement.parentElement.querySelector('.wc-block-components-product-metadata'),
            key: el.id
          };
        });
        setCartItems(newRoots);
        clearInterval(ref);
      }
    }, 100);
    return () => {
      clearInterval(ref);
    };
  }, []);
  if (!subTaxClasses || !cartItems) return null;
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, cartItems.map(cartItem => {
    var _itemSubTaxClasses$fi;
    let item = cart.cartItems.find(item => item.key === cartItem.key);
    if (!item || !item.extensions.taxdo_tax_class?.tc_id) return;
    let name = '';
    let tacClassId = 0;
    switch (country) {
      case 'US':
        name = item.extensions.taxdo_tax_class.tc_us_name;
        tacClassId = item.extensions.taxdo_tax_class.tc_us_id;
        break;
      case 'CA':
        name = item.extensions.taxdo_tax_class.tc_ca_name;
        tacClassId = item.extensions.taxdo_tax_class.tc_ca_id;
        break;
      default:
        return;
    }
    const opt = subTaxClasses?.filter(taxClass => taxClass.tax_class_id === tacClassId)[0]['sub_tax_classes']?.map(subTaxClass => {
      return {
        label: subTaxClass['name'],
        value: subTaxClass['id']
      };
    });
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_portal__WEBPACK_IMPORTED_MODULE_5__["default"], {
      root: cartItem.root,
      key: cartItem.key + tacClassId
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("ul", {
      className: "wc-block-components-product-details"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(TaxClass, {
      name: name
    }), checkoutExtensionData && opt && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(SubTaxClass, {
      value: (_itemSubTaxClasses$fi = itemSubTaxClasses.find(sub => sub.key === item.key)?.value) !== null && _itemSubTaxClasses$fi !== void 0 ? _itemSubTaxClasses$fi : -1,
      options: [{
        label: 'default',
        value: -1
      }, ...opt],
      onChange: value => setItemSubTaxClasses(prev => {
        return [...prev.filter(sub => sub.key != item.key), {
          id: item.id,
          key: item.key,
          value: value
        }];
      })
    })));
  }));
};

/***/ }),

/***/ "./src/Block/show-sub-tax-class/Block/portal.js":
/*!******************************************************!*\
  !*** ./src/Block/show-sub-tax-class/Block/portal.js ***!
  \******************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);

const SubTaxClass = ({
  children,
  root
}) => {
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createPortal)(children, root);
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SubTaxClass);

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@woocommerce/blocks-checkout":
/*!****************************************!*\
  !*** external ["wc","blocksCheckout"] ***!
  \****************************************/
/***/ ((module) => {

module.exports = window["wc"]["blocksCheckout"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ }),

/***/ "./src/Block/show-sub-tax-class/block.json":
/*!*************************************************!*\
  !*** ./src/Block/show-sub-tax-class/block.json ***!
  \*************************************************/
/***/ ((module) => {

module.exports = /*#__PURE__*/JSON.parse('{"apiVersion":2,"name":"taxdo/show-sub-tax-class","version":"1.0.0","title":"Show Sub tax class","category":"woocommerce","description":"Adds a select field to let the shopper choose sub tax class.","supports":{"html":false,"align":false,"multiple":false,"reusable":false},"parent":["woocommerce/checkout-order-summary-block","woocommerce/cart-items-block"],"attributes":{"lock":{"type":"object","default":{"remove":true,"move":true}},"text":{"type":"string","source":"html","selector":".wp-block-taxdo-show-sub-tax-class","default":""}},"textdomain":"taxdo","editorStyle":"file:../style.css"}');

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**************************************************!*\
  !*** ./src/Block/show-sub-tax-class/frontend.js ***!
  \**************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @woocommerce/blocks-checkout */ "@woocommerce/blocks-checkout");
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Block_block__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Block/block */ "./src/Block/show-sub-tax-class/Block/block.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/Block/show-sub-tax-class/block.json");
/**
 * External dependencies
 */

/**
 * Internal dependencies
 */


(0,_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_0__.registerCheckoutBlock)({
  metadata: _block_json__WEBPACK_IMPORTED_MODULE_2__,
  component: _Block_block__WEBPACK_IMPORTED_MODULE_1__.Block
});
})();

/******/ })()
;
//# sourceMappingURL=frontend.js.map