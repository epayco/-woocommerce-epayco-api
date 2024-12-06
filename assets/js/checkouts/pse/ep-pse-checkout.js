/* jshint es3: false */
/* globals wc_epayco_pse_checkout_params, CheckoutPseElements */
(function ($) {
  'use strict';

  $(function () {

    // Handler form submit
    function epaycoFormHandlerPse() {
      if (!document.getElementById('payment_method_woo-epayco-pse').checked) {
        return true;
      }
      let pseContent = document.querySelector("form.checkout").getElementsByClassName("mp-checkout-pse-content")[0];
      verifyName(pseContent)
      verifyEmail(pseContent)
      verifyAddress(pseContent)
      verifyCellphone(pseContent)
      verifyDocument(pseContent);
      verifyCountry(pseContent)
      verifyFinancial(pseContent);
      if (checkForErrors(pseContent.querySelectorAll('input-helper'))) {
        return  false;
      }
      return true;
    }

    function verifyName(pseContent) {
      let nameElement = pseContent.querySelector('#form-checkout__identificationName-container').querySelector('input');
      if (nameElement.value === '') {
        nameElement.parentElement.classList.add('mp-error');
        let pseHelpers = pseContent.parentElement.querySelector('input-helper');
        let child = pseHelpers.querySelector('div');
        child.style.display = 'flex';
      }
    }

    function verifyEmail(pseContent) {
      let emailElement = pseContent.querySelector('#form-checkout__identificationEmail-container').querySelector('input');
      if (emailElement.value === '') {
        emailElement.parentElement.classList.add('mp-error');
        let pseHelpers = pseContent.querySelector('#form-checkout__identificationEmail-container').parentElement.querySelector('input-helper');
        let child = pseHelpers.querySelector('div');
        child.style.display = 'flex';
      }
    }

    function verifyAddress(pseContent) {
      let addressElement = pseContent.querySelector('#form-checkout__identificationAddress-container').querySelector('input');
      if (addressElement.value === '') {
        addressElement.parentElement.classList.add('mp-error');
        let pseHelpers = pseContent.querySelector('#form-checkout__identificationAddress-container').parentElement.querySelector('input-helper');
        let child = pseHelpers.querySelector('div');
        child.style.display = 'flex';
      }
    }

    function verifyCellphone(pseContent) {
      let addressElement = pseContent.querySelector('#form-checkout__identificationCellphone-container').querySelector('input');
      if (addressElement.value === '') {
        addressElement.parentElement.classList.add("mp-error");
        addressElement.parentElement.parentElement.firstChild.classList.add("mp-error");
        let pseHelpers = pseContent.querySelector('#form-checkout__identificationCellphone-container').parentElement.querySelector('input-helper');
        let child = pseHelpers.querySelector('div');
        child.style.display = 'flex';
      }
    }

    function verifyCountry(pseContent) {
      let addressElement = pseContent.querySelector('#form-checkout__identificationCountry-container').querySelector('input');
      if (addressElement.value === '') {
        addressElement.parentElement.classList.add("mp-error");
        addressElement.parentElement.parentElement.firstChild.classList.add("mp-error");
        let pseHelpers = pseContent.querySelector('#form-checkout__identificationCountry-container').parentElement.querySelector('input-helper');
        let child = pseHelpers.querySelector('div');
        child.style.display = 'flex';
      }
    }

    function verifyDocument(pseContent) {
      let addressElement = pseContent.querySelector('#form-checkout__identificationNumber-container').querySelector('input');
      if (addressElement.value === '') {
        addressElement.parentElement.classList.add("mp-error");
        addressElement.parentElement.parentElement.firstChild.classList.add("mp-error");
        let pseHelpers = pseContent.querySelector('#form-checkout__identificationNumber-container').parentElement.querySelector('input-helper');
        let child = pseHelpers.querySelector('div');
        child.style.display = 'flex';
      }
    }

    function verifyFinancial(pseContent) {
      let documentElement = pseContent.querySelector('#epayco_pse\\[bank\\]');
      let pseHelpers =  pseContent.querySelector('.mp-checkout-pse-bank').querySelector('input-helper');
      if (documentElement.value === '' || wc_epayco_pse_checkout_params.financial_placeholder === documentElement.value ) {
        documentElement.parentElement.classList.add('mp-error');
        let child = pseHelpers.querySelector('div');
        child.style.display = 'flex';
      }
      documentElement.addEventListener('change', () => {
        documentElement.parentElement.classList.remove('mp-error');
        pseHelpers.querySelector('div').style.display = 'none';
      });
    }

    function checkForErrors(pseHelpers) {
      let hasError = false;

      pseHelpers.forEach((item) => {
        let inputHelper = item.querySelector('div');
        if (inputHelper.style.display !== 'none') {
          hasError = true;
        }
      });

      return hasError;
    }

    // Process when submit the checkout form
    $('form.checkout').on('checkout_place_order_woo-epayco-pse', function () {
      return epaycoFormHandlerPse();
    });

    // If payment fail, retry on next checkout page
    $('form#order_review').submit(function () {
      return epaycoFormHandlerPse();
    });


  });
})(jQuery);

