(function($) {
    const fieldMaps = {
        'woo-epayco-creditcard': {
            '#shipping-address_1': 'input[name="epayco_creditcard[address]"]',
            '#shipping-phone': 'input[name="epayco_creditcard[cellphone]"]',
            '#shipping-city': 'input[name="epayco_creditcard[country]"]',
        },
        'woo-epayco-pse': {
            '#shipping-address_1': 'input[name="epayco_pse[address]"]',
            '#shipping-phone': 'input[name="epayco_pse[cellphone]"]',
            '#shipping-city': 'input[name="epayco_pse[country]"]',
        },
        'woo-epayco-daviplata': {
            '#shipping-phone': 'input[name="epayco_daviplata[cellphone]"]'
        },
        'woo-epayco-ticket': {
            '#shipping-phone': 'input[name="epayco_ticket[cellphone]"]',
            '#shipping-city': 'input[name="epayco_ticket[country]"]'
        },
        'woo-epayco-subscription': {
            '#shipping-address_1': 'input[name="epayco_subscription[address]"]',
            '#shipping-phone': 'input[name="epayco_subscription[cellphone]"]',
        },
    };

    function autofillName(method) {
        const firstName = $('#shipping-first_name').val() || '';
        const lastName = $('#shipping-last_name').val() || '';
        const fullName = (firstName + ' ' + lastName).trim();
        let selector = '';

        switch (method) {
            case 'woo-epayco-creditcard':
                selector = 'input[name="epayco_creditcard[name]"]'; break;
            case 'woo-epayco-pse':
                selector = 'input[name="epayco_pse[name]"]'; break;
            case 'woo-epayco-daviplata':
                selector = 'input[name="epayco_daviplata[name]"]'; break;
            case 'woo-epayco-ticket':
                selector = 'input[name="epayco_ticket[name]"]'; break;
            case 'woo-epayco-subscription':
                selector = 'input[name="epayco_subscription[name]"]'; break;
        }

        if (selector && $(selector).length) {
            $(selector).val(fullName).trigger('input');
        }
    }

    function autofillEpaycoFields(method) {
        autofillName(method);
        const fieldMap = fieldMaps[method];
        if (!fieldMap) return;

        $.each(fieldMap, function(wcSelector, epaycoSelector) {
            const wcValue = $(wcSelector).val();
            if (wcValue !== undefined && $(epaycoSelector).length) {
                $(epaycoSelector).val(wcValue).trigger('input');
            }
        });
    }

    function getSelectedMethod() {
        return $('input[name="radio-control-wc-payment-method-options"]:checked').val();
    }

    function handleAutofill() {
        const selected = getSelectedMethod();
        if (fieldMaps[selected]) {
            setTimeout(() => autofillEpaycoFields(selected), 300);
        }
    }

    $(document).ready(function() {
        setTimeout(() => {
            handleAutofill();
        }, 500);

        $(document.body).on('change', 'input[name="radio-control-wc-payment-method-options"]', handleAutofill);
        $(document.body).on('updated_checkout', handleAutofill);
    });
})(jQuery);
