/* globals wc_epayco_custom_checkout_params, epayco, CheckoutPage, MP_DEVICE_SESSION_ID */

var cardForm;
var hasToken = false;
var mercado_pago_submit = false;
var triggeredPaymentMethodSelectedEvent = false;
var cardFormMounted = false;
var threedsTarget = 'mp_custom_checkout_security_fields_client';

var mpCheckoutForm = document.querySelector('form[name=checkout]');
var mpFormId = 'checkout';

if (mpCheckoutForm) {
  mpCheckoutForm.id = mpFormId;
} else {
  mpFormId = 'order_review';
}

function epaycoFormHandler() {
  let formOrderReview = document.querySelector('form[id=order_review]');

  if (formOrderReview) {
    let choCustomContent = document.querySelector('.mp-checkout-custom-container');
    let choCustomHelpers = choCustomContent.querySelectorAll('input-helper');

    choCustomHelpers.forEach((item) => {
      let inputHelper = item.querySelector('div');
      if (inputHelper.style.display !== 'none') {
        removeBlockOverlay();
      }
    });
  }


  if (mercado_pago_submit) {
    return true;
  }

  if (jQuery('#mp_checkout_type').val() === 'wallet_button') {
    return true;
  }

  jQuery('#mp_checkout_type').val('custom');

  if (CheckoutPage.validateInputsCreateToken() && !hasToken) {
    return createToken();
  }

  return false;
}

// Create a new token
function createToken() {
  cardForm
    .createCardToken()
    .then((cardToken) => {
      if (cardToken.token) {
        if (hasToken) {
          return;
        }

        document.querySelector('#cardTokenId').value = cardToken.token;
        mercado_pago_submit = true;
        hasToken = true;

        if (mpFormId === 'order_review') {
          handle3dsPayOrderFormSubmission();
          return false;
        }

        jQuery('form.checkout').submit();
      } else {
        throw new Error('cardToken is empty');
      }
    })
    .catch((error) => {
      console.warn('Token creation error: ', error);
    });

  return false;
}

/**
 * Init cardForm
 */
function initCardForm() {
  var mp = new ePayco(wc_epayco_custom_checkout_params.public_key);

  return new Promise((resolve, reject) => {
    cardForm = mp.cardForm({
      amount: "1",
      iframe: true,
      form: {
        id: mpFormId,
        cardNumber: {
          id: 'form-checkout__cardNumber-container',
          placeholder: '0000 0000 0000 0000',
          style: {
            'font-size': '16px',
            height: '40px',
            padding: '14px',
          },
        },
        cardholderName: {
          id: 'form-checkout__cardholderName',
          placeholder: 'Ex.: María López',
        },
        cardholderEmail: {
          id: 'form-checkout__cardholderEmail',
          placeholder: 'example@email.com',
        },
        cardholderAdress: {
          id: 'form-checkout__cardholderAdress',
          placeholder: 'Ex.:Street 123',
        },
        cardExpirationDate: {
          id: 'form-checkout__expirationDate-container',
          placeholder: wc_epayco_custom_checkout_params.placeholders['cardExpirationDate'],
          mode: 'short',
          style: {
            'font-size': '16px',
            height: '40px',
            padding: '14px',
          },
        },
        securityCode: {
          id: 'form-checkout__securityCode-container',
          placeholder: '123',
          style: {
            'font-size': '16px',
            height: '40px',
            padding: '14px',
          },
        },
        identificationType: {
          id: 'form-checkout__identificationType',
        },
        identificationTypeAdress: {
          id: 'form-checkout__identificationTypeAdress',
        },
        identificationNumber: {
          id: 'form-checkout__identificationNumber',
        },
        identificationAdress: {
          id: 'form-checkout__identificationAdress',
        },
        issuer: {
          id: 'form-checkout__issuer',
          placeholder: wc_epayco_custom_checkout_params.placeholders['issuer'],
        },
        installments: {
          id: 'form-checkout__installments',
          placeholder: wc_epayco_custom_checkout_params.placeholders['installments'],
        },
      },
      callbacks: {
        onReady: () => {
          removeLoadSpinner();
          resolve();
        },
        onFormMounted: function (error) {
          cardFormMounted = true;

          if (error) {
            console.log('Callback to handle the error: creating the CardForm', error);
            return;
          }
        },
        onFormUnmounted: function (error) {
          cardFormMounted = false;
          CheckoutPage.clearInputs();

          if (error) {
            console.log('Callback to handle the error: unmounting the CardForm', error);
            return;
          }
        },
        onInstallmentsReceived: (error, installments) => {
          if (error) {
            console.warn('Installments handling error: ', error);
            return;
          }

          CheckoutPage.setChangeEventOnInstallments(CheckoutPage.getCountry(), installments);
        },
        onCardTokenReceived: (error) => {
          if (error) {
            console.warn('Token handling error: ', error);
            return;
          }
        },
        onPaymentMethodsReceived: (error, paymentMethods) => {
          try {
            if (paymentMethods) {
              CheckoutPage.setValue('paymentMethodId', paymentMethods[0].id);
              CheckoutPage.setCvvHint(paymentMethods[0].settings[0].security_code);
              CheckoutPage.changeCvvPlaceHolder(paymentMethods[0].settings[0].security_code.length);
              CheckoutPage.clearInputs();
              CheckoutPage.setDisplayOfError('fcCardNumberContainer', 'remove', 'mp-error');
              CheckoutPage.setDisplayOfInputHelper('mp-card-number', 'none');
              CheckoutPage.setImageCard(paymentMethods[0].secure_thumbnail || paymentMethods[0].thumbnail);
              CheckoutPage.installment_amount(paymentMethods[0].payment_type_id);
              const additionalInfoNeeded = CheckoutPage.loadAdditionalInfo(paymentMethods[0].additional_info_needed);
              CheckoutPage.additionalInfoHandler(additionalInfoNeeded);
            } else {
              CheckoutPage.setDisplayOfError('fcCardNumberContainer', 'add', 'mp-error');
              CheckoutPage.setDisplayOfInputHelper('mp-card-number', 'flex');
            }
          } catch (error) {
            CheckoutPage.setDisplayOfError('fcCardNumberContainer', 'add', 'mp-error');
            CheckoutPage.setDisplayOfInputHelper('mp-card-number', 'flex');
          }
        },
        onSubmit: function (event) {
          event.preventDefault();
        },
        onValidityChange: function (error, field) {
          if (error) {
            let helper_message = CheckoutPage.getHelperMessage(field);
            let message = wc_epayco_custom_checkout_params.input_helper_message[field][error[0].code];

            if (message) {
              helper_message.innerHTML = message;
            } else {
              helper_message.innerHTML =
                wc_epayco_custom_checkout_params.input_helper_message[field]['invalid_length'];
            }

            if (field === 'cardNumber') {
              if (error[0].code !== 'invalid_length') {
                CheckoutPage.setBackground('fcCardNumberContainer', 'no-repeat #fff');
                CheckoutPage.removeAdditionFields();
                CheckoutPage.clearInputs();
              }
            }

            let containerField = CheckoutPage.findContainerField(field);
            CheckoutPage.setDisplayOfError(containerField, 'add', 'mp-error');

            return CheckoutPage.setDisplayOfInputHelper(CheckoutPage.inputHelperName(field), 'flex');
          }

          let containerField = CheckoutPage.findContainerField(field);
          CheckoutPage.setDisplayOfError(containerField, 'removed', 'mp-error');

          return CheckoutPage.setDisplayOfInputHelper(CheckoutPage.inputHelperName(field), 'none');
        },
        onError: function (errors) {
          errors.forEach((error) => {
            removeBlockOverlay();
            if (error.message.includes('timed out')) {
              return reject(error);
            } else if (error.message.includes('cardNumber')) {
              CheckoutPage.setDisplayOfError('fcCardNumberContainer', 'add', 'mp-error');
              return CheckoutPage.setDisplayOfInputHelper('mp-card-number', 'flex');
            } else if (error.message.includes('cardholderName')) {
              CheckoutPage.setDisplayOfError('fcCardholderName', 'add', 'mp-error');
              return CheckoutPage.setDisplayOfInputHelper('mp-card-holder-name', 'flex');
            }else if (error.message.includes('cardholderEmail')) {
              CheckoutPage.setDisplayOfError('fcCardholderEmail', 'add', 'mp-error');
              return CheckoutPage.setDisplayOfInputHelper('mp-card-holder-email', 'flex');
            }else if (error.message.includes('cardholderAdress')) {
              CheckoutPage.setDisplayOfError('fcCardholderAdress', 'add', 'mp-error');
              return CheckoutPage.setDisplayOfInputHelper('mp-card-holder-adress', 'flex');
            } else if (error.message.includes('expirationMonth') || error.message.includes('expirationYear')) {
              CheckoutPage.setDisplayOfError('fcCardExpirationDateContainer', 'add', 'mp-error');
              return CheckoutPage.setDisplayOfInputHelper('mp-expiration-date', 'flex');
            } else if (error.message.includes('securityCode')) {
              CheckoutPage.setDisplayOfError('fcSecurityNumberContainer', 'add', 'mp-error');
              return CheckoutPage.setDisplayOfInputHelper('mp-security-code', 'flex');
            } else if (error.message.includes('identificationNumber')) {
              CheckoutPage.setDisplayOfError('fcIdentificationNumberContainer', 'add', 'mp-error');
              return CheckoutPage.setDisplayOfInputHelper('mp-doc-number', 'flex');
            } else {
              return reject(error);
            }
          });
        },
      },
    });
  });
}




function removeBlockOverlay() {
  if (jQuery('form#order_review').length > 0) {
    jQuery('.blockOverlay').css('display', 'none');
  }
}

function cardFormLoad() {
  const checkoutCustomPaymentMethodElement = document.getElementById('payment_method_woo-epayco-custom');

  if (checkoutCustomPaymentMethodElement && checkoutCustomPaymentMethodElement.checked) {
    setTimeout(() => {
      if (!cardFormMounted) {
        createLoadSpinner();
        handleCardFormLoad();
      }
    }, 2500);
  } else {
    if (cardFormMounted) {
      cardForm.unmount();
    }
  }
}

function setCardFormLoadInterval() {
  var cardFormInterval = setInterval(() => {
    const checkoutCustomPaymentMethodElement = document.getElementById('payment_method_woo-epayco-custom');
    const cardInput = document.getElementById('form-checkout__cardNumber-container');

    // Checkout Custom is not selected, so we can stop checking
    if (!checkoutCustomPaymentMethodElement || !checkoutCustomPaymentMethodElement.checked) {
      clearInterval(cardFormInterval);
      return;
    }

    // CardForm iframe is rendered, so we can stop checking
    if (cardInput && cardInput.childElementCount > 0) {
      clearInterval(cardFormInterval);
      return;
    }

    // CardForm is mounted but the iframe is not rendered, so we reload the CardForm
    if (cardFormMounted) {
      cardForm.unmount();
      cardFormLoad();
    }
  }, 1000);
}

function handleCardFormLoad() {
  initCardForm()
    .then(() => {
      sendMetric('MP_CARDFORM_SUCCESS', 'Security fields loaded', threedsTarget);
    })
    .catch((error) => {
      const parsedError = handleCardFormErrors(error);
      sendMetric('MP_CARDFORM_ERROR', parsedError, threedsTarget);
      console.error('ePayco cardForm error: ', parsedError);
    });
}

function handleCardFormErrors(cardFormErrors) {
  if (cardFormErrors.length) {
    const errors = [];
    cardFormErrors.forEach((e) => {
      errors.push(e.description || e.message);
    });

    return errors.join(',');
  }

  return cardFormErrors.description || cardFormErrors.message;
}

jQuery('form.checkout').on('checkout_place_order_woo-epayco-custom', epaycoFormHandler);

jQuery('body').on('payment_method_selected', function () {
  if (!triggeredPaymentMethodSelectedEvent) {
    cardFormLoad();
  }
});

jQuery('form#order_review').submit(function (event) {
  const selectPaymentMethod = document.getElementById('payment_method_woo-epayco-custom');

  if (selectPaymentMethod && selectPaymentMethod.checked) {
    event.preventDefault();
    return epaycoFormHandler();
  } else {
    cardFormLoad();
  }
});

jQuery(document.body).on('checkout_error', () => {
  hasToken = false;
  mercado_pago_submit = false;
});

jQuery(document).on('updated_checkout', function () {
  const checkoutCustomPaymentMethodElement = document.getElementById('payment_method_woo-epayco-custom');

  // Checkout Custom is not selected, so we can stop checking
  if (checkoutCustomPaymentMethodElement && checkoutCustomPaymentMethodElement.checked) {
    if (cardFormMounted) {
      cardForm.unmount();
    }

    handleCardFormLoad();
    return;
  }
});

jQuery(document).ready(() => {
  setCardFormLoadInterval();
});

if (!triggeredPaymentMethodSelectedEvent) {
  jQuery('body').trigger('payment_method_selected');
}

function createLoadSpinner() {
  document.querySelector('.mp-checkout-custom-container').style.display = 'none';
  document.querySelector('.mp-checkout-custom-load').style.display = 'flex';
}

function removeLoadSpinner() {
  document.querySelector('.mp-checkout-custom-container').style.display = 'block';
  document.querySelector('.mp-checkout-custom-load').style.display = 'none';
}



function setDisplayOfErrorCheckout(errorMessage) {
  sendMetric('MP_THREE_DS_ERROR', errorMessage, threedsTarget);

  if (window.mpFormId !== 'blocks_checkout_form') {
    removeElementsByClass('woocommerce-NoticeGroup-checkout');
    var divWooNotice = document.createElement('div');
    divWooNotice.className = 'woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout';
    divWooNotice.innerHTML =
      '<ul class="woocommerce-error" role="alert">' + '<li>'.concat(errorMessage).concat('<li>') + '</ul>';
    mpCheckoutForm.prepend(divWooNotice);
    window.scrollTo(0, 0);
  }
}

function removeElementsByClass(className) {
  const elements = document.getElementsByClassName(className);
  while (elements.length > 0) {
    elements[0].parentNode.removeChild(elements[0]);
  }
}

function sendMetric(name, message, target) {
  const url = 'https://api.epayco.com/v1/plugins/melidata/errors';
  const payload = {
    name,
    message,
    target: target,
    plugin: {
      version: wc_epayco_custom_checkout_params.plugin_version,
    },
    platform: {
      name: 'woocommerce',
      uri: window.location.href,
      version: wc_epayco_custom_checkout_params.platform_version,
      location: `${wc_epayco_custom_checkout_params.location}_${wc_epayco_custom_checkout_params.theme}`,
    },
  };

  navigator.sendBeacon(url, JSON.stringify(payload));
}
