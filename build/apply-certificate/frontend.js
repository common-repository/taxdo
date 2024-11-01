/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./src/Block/apply-certificate/Block/CertificateRequestForm.js":
/*!*********************************************************************!*\
  !*** ./src/Block/apply-certificate/Block/CertificateRequestForm.js ***!
  \*********************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   CertificateRequestForm: () => (/* binding */ CertificateRequestForm)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @woocommerce/blocks-components */ "@woocommerce/blocks-components");
/* harmony import */ var _woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/compose */ "@wordpress/compose");
/* harmony import */ var _wordpress_compose__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);

/**
 * External dependencies
 */




const validateAddress = ({
  address_1,
  city,
  country,
  email,
  first_name,
  last_name,
  postcode,
  state
}) => {
  return address_1.length && city.length && country.length && email.length && first_name.length && last_name.length && postcode.length && state.length;
};
const initValue = {
  businessName: '',
  reason: '',
  note: '',
  attachment: null
};
const CertificateRequestForm = ({
  address,
  cancel
}) => {
  const [data, setData] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(initValue);
  const [referenceNo, setReferenceNo] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
  const [error, setError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
  const [isLoading, setIsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_3__.useState)(false);
  const instanceId = (0,_wordpress_compose__WEBPACK_IMPORTED_MODULE_2__.useInstanceId)(CertificateRequestForm);
  const isValidAddress = validateAddress(address);
  const submit = e => {
    e.preventDefault();
    const {
      businessName,
      reason,
      note,
      attachment
    } = data;
    const formData = new FormData();
    formData.append('business_name', businessName);
    formData.append('reason', reason);
    formData.append('note', note);
    formData.append('address_1', address.address_1);
    formData.append('address_2', address.address_2);
    formData.append('city', address.city);
    formData.append('country', address.country);
    formData.append('email', address.email);
    formData.append('first_name', address.first_name);
    formData.append('last_name', address.last_name);
    formData.append('postcode', address.postcode);
    formData.append('state', address.state);
    formData.append('phone', address.phone);
    formData.append('attachment', attachment[0]);
    setIsLoading(true);
    fetch('/wp-json/taxdo/v1/certificate-request', {
      method: 'POST',
      body: formData
    }).then(response => {
      if (response.ok) {
        return response.json();
      } else {
        response.json().then(e => {
          setError(e.message);
        });
      }
    }).then(data => {
      setReferenceNo(data.reference);
    }).catch(error => {
      setError('Error on submit certificate.');
    }).finally(() => setIsLoading(false));
  };
  if (referenceNo) {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.Panel, {
      title: "Submit Sales Tax Certificate",
      titleTag: "div",
      initialOpen: true,
      className: "wc-block-components-totals-wrapper"
    }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.TextHighlight, {
      highlight: referenceNo,
      text: `Your sales tax exemption certificate is received (ref. ${referenceNo}). Our team is reviewing it, and you'll get a confirmation email with a code for checkout.`
    }));
  }
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.Panel, {
    title: "Submit Sales Tax Certificate",
    titleTag: "div",
    initialOpen: true,
    className: "wc-block-components-totals-wrapper"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("form", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.TextInput, {
    id: `taxdo-block-components-apply-certificate-business-${instanceId}`,
    className: "taxdo-block-components-apply-certificate__input",
    label: "Business name (optional)",
    onChange: value => setData({
      ...data,
      businessName: value
    }),
    value: data.businessName
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.TextInput, {
    id: `taxdo-block-components-apply-certificate-reason-${instanceId}`,
    className: "taxdo-block-components-apply-certificate__input",
    label: "Reason (optional)",
    onChange: value => setData({
      ...data,
      reason: value
    }),
    value: data.reason
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      paddingBottom: '.50em'
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.Textarea, {
    id: `taxdo-block-components-apply-certificate-note-${instanceId}`,
    className: "",
    onTextChange: value => setData({
      ...data,
      note: value
    }),
    placeholder: "Note (optional)",
    value: data.note
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.FormFileUpload, {
    accept: "image/*,application/pdf",
    onChange: event => setData({
      ...data,
      attachment: event.currentTarget.files
    }),
    render: ({
      openFileDialog
    }) => {
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(react__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
        type: "button",
        variant: "outlined",
        onClick: openFileDialog
      }, "Select attachment"), data.attachment && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, " ", data.attachment[0].name));
    }
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Flex, {
    direction: "row-reverse"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.FlexItem, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: submit,
    disabled: !isValidAddress || !data.attachment || isLoading,
    showSpinner: isLoading,
    className: "taxdo-block-components-apply-certificate__button",
    type: "submit"
  }, "Submit")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.FlexItem, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.Button, {
    onClick: cancel,
    className: "taxdo-block-components-apply-certificate__button",
    type: "button",
    variant: "outlined",
    disabled: isLoading
  }, "Cancel")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.FlexBlock, null)), !isValidAddress && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.ValidationInputError, {
    errorMessage: "Please enter your address and email."
  }), !data.attachment && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.ValidationInputError, {
    errorMessage: "Please select certificate file."
  }), error && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_1__.ValidationInputError, {
    errorMessage: error
  })));
};

/***/ }),

/***/ "./src/Block/apply-certificate/Block/TotalsCertificate.js":
/*!****************************************************************!*\
  !*** ./src/Block/apply-certificate/Block/TotalsCertificate.js ***!
  \****************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @woocommerce/blocks-components */ "@woocommerce/blocks-components");
/* harmony import */ var _woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @woocommerce/blocks-checkout */ "@woocommerce/blocks-checkout");
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! classnames */ "./node_modules/.pnpm/classnames@2.5.1/node_modules/classnames/index.js");
/* harmony import */ var classnames__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(classnames__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _useApplyCertificate__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./useApplyCertificate */ "./src/Block/apply-certificate/Block/useApplyCertificate.js");

/**
 * External dependencies
 */






const TotalsCertificate = ({
  displayCertificateForm = false,
  onSuccess,
  onRequestCode
}) => {
  const [certificateValue, setCertificateValue] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)('');
  const [isCertificateFormHidden, setIsCertificateFormHidden] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(!displayCertificateForm);
  const {
    submitCertificateCode,
    isLoading,
    error
  } = (0,_useApplyCertificate__WEBPACK_IMPORTED_MODULE_6__.userApplyCertificate)({
    onSuccess: () => {
      setCertificateValue('');
      setIsCertificateFormHidden(true);
      onSuccess();
    }
  });
  const textInputId = 'taxdo-block-components-totals-certificate__input';
  const formWrapperClass = classnames__WEBPACK_IMPORTED_MODULE_5___default()('taxdo-block-components-totals-certificate__content', {
    'screen-reader-text': isCertificateFormHidden
  });
  const handleCertificateAnchorClick = e => {
    e.preventDefault();
    setIsCertificateFormHidden(false);
  };
  const apply = e => {
    e.preventDefault();
    submitCertificateCode(certificateValue);
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_4__.TotalsWrapper, {
    className: "taxdo-block-components-apply-certificate-block"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "taxdo-block-components-totals-certificate"
  }, isCertificateFormHidden ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("a", {
    role: "button",
    href: "#taxdo-block-components-totals-certificate__form",
    className: "taxdo-block-components-totals-certificate-link",
    "aria-label": "Have a Sales Tax Exemption Certificate?",
    onClick: handleCertificateAnchorClick
  }, "Have a Sales Tax Exemption Certificate?") : (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: formWrapperClass
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("form", {
    className: "taxdo-block-components-totals-certificate__form",
    id: "taxdo-block-components-totals-certificate__form"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__.ValidatedTextInput, {
    id: textInputId,
    errorId: "certificate",
    className: "taxdo-block-components-totals-certificate__input",
    label: "Enter exemption code",
    value: certificateValue,
    onChange: newCertificateValue => {
      setCertificateValue(newCertificateValue);
    },
    focusOnMount: true,
    validateOnMount: false,
    showError: false
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__.Button, {
    className: "taxdo-block-components-totals-certificate__button",
    disabled: isLoading || !certificateValue,
    showSpinner: isLoading,
    onClick: apply,
    type: "submit"
  }, "Apply")), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__.ValidationInputError, {
    propertyName: "certificate",
    errorMessage: error
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
    onClick: e => {
      e.preventDefault();
      onRequestCode();
    },
    href: "#taxdo-block-components-apply-certificate__form",
    variant: "link",
    type: "button"
  }, "Don't have an exemption code?"))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TotalsCertificate);

/***/ }),

/***/ "./src/Block/apply-certificate/Block/ValidCertificate.js":
/*!***************************************************************!*\
  !*** ./src/Block/apply-certificate/Block/ValidCertificate.js ***!
  \***************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   ValidCertificate: () => (/* binding */ ValidCertificate)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @woocommerce/blocks-checkout */ "@woocommerce/blocks-checkout");
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @woocommerce/blocks-components */ "@woocommerce/blocks-components");
/* harmony import */ var _woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _useApplyCertificate__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./useApplyCertificate */ "./src/Block/apply-certificate/Block/useApplyCertificate.js");




const ValidCertificate = ({
  certificateCode,
  state,
  onRemove
}) => {
  const {
    removeCertificate,
    isLoading,
    error
  } = (0,_useApplyCertificate__WEBPACK_IMPORTED_MODULE_3__.userApplyCertificate)({
    onSuccess: () => {
      onRemove();
    }
  });
  const apply = e => {
    e.preventDefault();
    removeCertificate();
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_1__.TotalsWrapper, {
    className: "taxdo-block-components-apply-certificate-block"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "taxdo-block-components-totals-certificate"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "taxdo-block-components-totals-certificate__valid-code"
  }, "The Certificate is valid for ", state), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__.RemovableChip, {
    element: "li",
    onRemove: apply,
    screenReaderText: certificateCode,
    text: certificateCode
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_woocommerce_blocks_components__WEBPACK_IMPORTED_MODULE_2__.ValidationInputError, {
    errorMessage: error
  })));
};

/***/ }),

/***/ "./src/Block/apply-certificate/Block/block.js":
/*!****************************************************!*\
  !*** ./src/Block/apply-certificate/Block/block.js ***!
  \****************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   Block: () => (/* binding */ Block)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _CertificateRequestForm__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./CertificateRequestForm */ "./src/Block/apply-certificate/Block/CertificateRequestForm.js");
/* harmony import */ var _ValidCertificate__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./ValidCertificate */ "./src/Block/apply-certificate/Block/ValidCertificate.js");
/* harmony import */ var _TotalsCertificate__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./TotalsCertificate */ "./src/Block/apply-certificate/Block/TotalsCertificate.js");

/**
 * External dependencies
 */


/**
 * Internal dependencies
 */



const STATUS = {
  INIT: 1,
  ENTER_CODE: 2,
  VALID_CERTIFICATE: 3,
  CERTIFICATE_REQUEST: 4
};
const Block = ({
  cart,
  validation,
  checkoutExtensionData,
  extensions
}) => {
  const {
    taxdo_certificate
  } = extensions;
  const [status, setStatus] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(() => {
    if (!taxdo_certificate?.certificate) {
      return STATUS.INIT;
    }
    return STATUS.VALID_CERTIFICATE;
  });
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    if (taxdo_certificate?.certificate) {
      setStatus(STATUS.VALID_CERTIFICATE);
    }
  }, [taxdo_certificate?.certificate]);
  switch (status) {
    case STATUS.INIT:
    case STATUS.ENTER_CODE:
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_TotalsCertificate__WEBPACK_IMPORTED_MODULE_4__["default"], {
        displayCertificateForm: STATUS.ENTER_CODE === status,
        onRequestCode: () => setStatus(STATUS.CERTIFICATE_REQUEST),
        onSuccess: () => setStatus(STATUS.VALID_CERTIFICATE)
      });
    case STATUS.VALID_CERTIFICATE:
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_ValidCertificate__WEBPACK_IMPORTED_MODULE_3__.ValidCertificate, {
        onRemove: () => setStatus(STATUS.ENTER_CODE),
        state: taxdo_certificate?.valid_state,
        certificateCode: taxdo_certificate?.certificate
      });
    case STATUS.CERTIFICATE_REQUEST:
      return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_CertificateRequestForm__WEBPACK_IMPORTED_MODULE_2__.CertificateRequestForm, {
        cancel: () => setStatus(STATUS.INIT),
        address: cart.billingAddress
      });
    default:
      return null;
  }
};

/***/ }),

/***/ "./src/Block/apply-certificate/Block/useApplyCertificate.js":
/*!******************************************************************!*\
  !*** ./src/Block/apply-certificate/Block/useApplyCertificate.js ***!
  \******************************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   userApplyCertificate: () => (/* binding */ userApplyCertificate)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @woocommerce/blocks-checkout */ "@woocommerce/blocks-checkout");
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_1__);
/**
 * External dependencies
 */


const userApplyCertificate = ({
  onSuccess = () => {}
}) => {
  const [error, setError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)();
  const [isLoading, setIsLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.useState)(false);
  const handleRequest = request => {
    setIsLoading(true);
    (0,_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_1__.extensionCartUpdate)(request).then(result => {
      if (result) {
        onSuccess();
      }
    }).catch(e => setError(e.message)).finally(() => setIsLoading(false));
  };
  const submitCertificateCode = certificateValue => {
    handleRequest({
      namespace: 'taxdo',
      data: {
        action: 'apply-certificate',
        certificate: certificateValue
      }
    });
  };
  const removeCertificate = () => {
    handleRequest({
      namespace: 'taxdo',
      data: {
        action: 'remove-certificate'
      }
    });
  };
  return {
    submitCertificateCode,
    removeCertificate,
    isLoading,
    error
  };
};

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

"use strict";
module.exports = window["React"];

/***/ }),

/***/ "@woocommerce/blocks-checkout":
/*!****************************************!*\
  !*** external ["wc","blocksCheckout"] ***!
  \****************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wc"]["blocksCheckout"];

/***/ }),

/***/ "@woocommerce/blocks-components":
/*!******************************************!*\
  !*** external ["wc","blocksComponents"] ***!
  \******************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wc"]["blocksComponents"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/compose":
/*!*********************************!*\
  !*** external ["wp","compose"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["compose"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

"use strict";
module.exports = window["wp"]["element"];

/***/ }),

/***/ "./node_modules/.pnpm/classnames@2.5.1/node_modules/classnames/index.js":
/*!******************************************************************************!*\
  !*** ./node_modules/.pnpm/classnames@2.5.1/node_modules/classnames/index.js ***!
  \******************************************************************************/
/***/ ((module, exports) => {

var __WEBPACK_AMD_DEFINE_ARRAY__, __WEBPACK_AMD_DEFINE_RESULT__;/*!
	Copyright (c) 2018 Jed Watson.
	Licensed under the MIT License (MIT), see
	http://jedwatson.github.io/classnames
*/
/* global define */

(function () {
	'use strict';

	var hasOwn = {}.hasOwnProperty;

	function classNames () {
		var classes = '';

		for (var i = 0; i < arguments.length; i++) {
			var arg = arguments[i];
			if (arg) {
				classes = appendClass(classes, parseValue(arg));
			}
		}

		return classes;
	}

	function parseValue (arg) {
		if (typeof arg === 'string' || typeof arg === 'number') {
			return arg;
		}

		if (typeof arg !== 'object') {
			return '';
		}

		if (Array.isArray(arg)) {
			return classNames.apply(null, arg);
		}

		if (arg.toString !== Object.prototype.toString && !arg.toString.toString().includes('[native code]')) {
			return arg.toString();
		}

		var classes = '';

		for (var key in arg) {
			if (hasOwn.call(arg, key) && arg[key]) {
				classes = appendClass(classes, key);
			}
		}

		return classes;
	}

	function appendClass (value, newClass) {
		if (!newClass) {
			return value;
		}
	
		if (value) {
			return value + ' ' + newClass;
		}
	
		return value + newClass;
	}

	if ( true && module.exports) {
		classNames.default = classNames;
		module.exports = classNames;
	} else if (true) {
		// register as 'classnames', consistent with npm package name
		!(__WEBPACK_AMD_DEFINE_ARRAY__ = [], __WEBPACK_AMD_DEFINE_RESULT__ = (function () {
			return classNames;
		}).apply(exports, __WEBPACK_AMD_DEFINE_ARRAY__),
		__WEBPACK_AMD_DEFINE_RESULT__ !== undefined && (module.exports = __WEBPACK_AMD_DEFINE_RESULT__));
	} else {}
}());


/***/ }),

/***/ "./src/Block/apply-certificate/block.json":
/*!************************************************!*\
  !*** ./src/Block/apply-certificate/block.json ***!
  \************************************************/
/***/ ((module) => {

"use strict";
module.exports = /*#__PURE__*/JSON.parse('{"apiVersion":2,"name":"taxdo/apply-certificate","version":"1.0.0","title":"Apply certificate code","category":"woocommerce","description":"Apply a certificate code","supports":{"html":false,"align":false,"multiple":false,"reusable":true},"parent":["woocommerce/checkout-order-summary-block"],"attributes":{"lock":{"type":"object","default":{"remove":true,"move":true}},"text":{"type":"string","source":"html","selector":".wp-block-taxdo-apply-certificate","default":""}},"textdomain":"taxdo","editorStyle":"file:../style.css"}');

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
// This entry need to be wrapped in an IIFE because it need to be in strict mode.
(() => {
"use strict";
/*!*************************************************!*\
  !*** ./src/Block/apply-certificate/frontend.js ***!
  \*************************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @woocommerce/blocks-checkout */ "@woocommerce/blocks-checkout");
/* harmony import */ var _woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_woocommerce_blocks_checkout__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _Block_block__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./Block/block */ "./src/Block/apply-certificate/Block/block.js");
/* harmony import */ var _block_json__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./block.json */ "./src/Block/apply-certificate/block.json");
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