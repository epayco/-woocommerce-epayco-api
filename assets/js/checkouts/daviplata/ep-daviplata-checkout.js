/* jshint es3: false */
/* globals wc_epayco_ticket_checkout_params, CheckoutTicketElements, CheckoutTicketPage */
(function ($) {
    'use strict';

    $(function () {
        var epayco_submit_ticket = false;

        // Handler form submit
        function epaycoFormHandlerTicket() {
            if (!document.getElementById('payment_method_woo-epayco-daviplata').checked) {
                return true;
            }
            let DaviplataContent = document.querySelector("form.checkout").getElementsByClassName("mp-checkout-daviplata-content")[0];
            let ticketHelpers = DaviplataContent.querySelectorAll('input-helper');
            verifyName(DaviplataContent)
            verifyEmail(DaviplataContent)
            verifyAddress(DaviplataContent)
            verifyCellphone(DaviplataContent)
            verifyDocument(DaviplataContent);
            verifyCountry(DaviplataContent)
            verifyTermAndCondictions(DaviplataContent)
            let checked =  DaviplataContent.parentElement.querySelector('terms-and-conditions').querySelector('input').checked
            if (checkForErrors(ticketHelpers)|| !checked) {
                removeBlockOverlay();
            } else {
                epayco_submit_ticket = true;
            }

            return epayco_submit_ticket;
        }

        function checkForErrors(ticketHelpers) {
            let hasError = false;

            ticketHelpers.forEach((item) => {
                let inputHelper = item.querySelector('div');
                if (inputHelper.style.display !== 'none') {
                    hasError = true;
                }
            });

            return hasError;
        }

        function verifyName(psedaviplataContent) {
            let nameElement = psedaviplataContent.querySelector('#form-checkout__identificationName-container').querySelector('input');
            if (nameElement.value === '') {
                nameElement.parentElement.classList.add('mp-error');
                let pseHelpers = psedaviplataContent.parentElement.querySelector('input-helper');
                let child = pseHelpers.querySelector('div');
                child.style.display = 'flex';
            }
        }

        function verifyEmail(psedaviplataContent) {
            let emailElement = psedaviplataContent.querySelector('#form-checkout__identificationEmail-container').querySelector('input');
            if (emailElement.value === '') {
                emailElement.parentElement.classList.add('mp-error');
                let pseHelpers = psedaviplataContent.querySelector('#form-checkout__identificationEmail-container').parentElement.querySelector('input-helper');
                let child = pseHelpers.querySelector('div');
                child.style.display = 'flex';
            }
        }

        function verifyAddress(psedaviplataContent) {
            let addressElement = psedaviplataContent.querySelector('#form-checkout__identificationAddress-container').querySelector('input');
            if (addressElement.value === '') {
                addressElement.parentElement.classList.add('mp-error');
                let pseHelpers = psedaviplataContent.querySelector('#form-checkout__identificationAddress-container').parentElement.querySelector('input-helper');
                let child = pseHelpers.querySelector('div');
                child.style.display = 'flex';
            }
        }

        function verifyCellphone(psedaviplataContent) {
            let addressElement = psedaviplataContent.querySelector('#form-checkout__identificationCellphone-container').querySelector('input');
            if (addressElement.value === '') {
                addressElement.parentElement.classList.add("mp-error");
                addressElement.parentElement.parentElement.firstChild.classList.add("mp-error");
                let pseHelpers = psedaviplataContent.querySelector('#form-checkout__identificationCellphone-container').parentElement.querySelector('input-helper');
                let child = pseHelpers.querySelector('div');
                child.style.display = 'flex';
            }
        }

        function verifyDocument(psedaviplataContent) {
            let addressElement = psedaviplataContent.querySelector('#form-checkout__identificationNumber-container').querySelector('input');
            if (addressElement.value === '') {
                addressElement.parentElement.classList.add("mp-error");
                addressElement.parentElement.parentElement.firstChild.classList.add("mp-error");
                let pseHelpers = psedaviplataContent.querySelector('#form-checkout__identificationNumber-container').parentElement.querySelector('input-helper');
                let child = pseHelpers.querySelector('div');
                child.style.display = 'flex';
            }
        }

        function verifyCountry(psedaviplataContent) {
            let addressElement = psedaviplataContent.querySelector('#form-checkout__identificationCountry-container').querySelector('input');
            if (addressElement.value === '') {
                addressElement.parentElement.classList.add("mp-error");
                addressElement.parentElement.parentElement.firstChild.classList.add("mp-error");
                let pseHelpers = psedaviplataContent.querySelector('#form-checkout__identificationCountry-container').parentElement.querySelector('input-helper');
                let child = pseHelpers.querySelector('div');
                child.style.display = 'flex';
            }
        }

        function verifyTermAndCondictions(psedaviplataContent) {
            let addressElement = psedaviplataContent.parentElement.querySelector('terms-and-conditions').querySelector('input');
            if (!addressElement.checked) {
                psedaviplataContent.parentElement.querySelector('terms-and-conditions > div').classList.add('mp-error')
            }
        }



        // Process when submit the checkout form
        $('form.checkout').on('checkout_place_order_woo-epayco-daviplata', function () {
            return epaycoFormHandlerTicket();
        });

        // If payment fail, retry on next checkout page
        $('form#order_review').submit(function () {
            return epaycoFormHandlerTicket();
        });

        // Remove Block Overlay from Order Review page
        function removeBlockOverlay() {
            if ($('form#order_review').length > 0) {
                $('.blockOverlay').css('display', 'none');
            }
        }
    });
})(jQuery);
