(() => {
    "use strict";
    const e = window.React, 
    t = window.wc.wcBlocksRegistry, 
    o = window.wc.wcSettings, 
    a = window.wp.element,
    c = window.wp.htmlEntities,
    n = "epayco_blocks_update_cart";
    var r;
    const m = "mp_checkout_blocks", d = "woo-epayco-ticket",
        i = (0, o.getSetting)("woo-epayco-ticket_data", {}),
        p = (0, c.decodeEntities)(i.title) || "Checkout Ticket", u = t => {
            (e => {
                const {extensionCartUpdate: t} = wc.blocksCheckout, {
                    eventRegistration: o,
                    emitResponse: c
                } = e, {onPaymentSetup: r, onCheckoutSuccess: i, onCheckoutFail: p} = o;
                (0, a.useEffect)((() => {
                    ((e, t) => {
                        e({namespace: n, data: {action: "add", gateway: t}})
                    })(t, d);
                    const e = r((() => ({type: c.responseTypes.SUCCESS})));
                    return () => (((e, t) => {
                        e({namespace: n, data: {action: "remove", gateway: t}})
                    })(t, d), e())
                }), [r]), (0, a.useEffect)((() => {
                    const e = i((async e => {
                        const t = e.processingResponse;
                        return {
                            type: c.responseTypes.SUCCESS,
                            messageContext: c.noticeContexts.PAYMENTS,
                            message: t.paymentDetails.message
                        }
                    }));
                    return () => e()
                }), [i]), (0, a.useEffect)((() => {
                    const e = p((e => {
                        const t = e.processingResponse;
                        return {
                            type: c.responseTypes.FAIL,
                            messageContext: c.noticeContexts.PAYMENTS,
                            message: t.paymentDetails.message
                        }
                    }));
                    return () => e()
                }), [p])
            })(t);
            const M = (0, a.useRef)(null),
            {eventRegistration: o, emitResponse: c} = t,
            {onPaymentSetup: r} = o,
            p = ["address_city", "address_federal_unit", "address_zip_code", "address_street_name", "address_street_number", "address_neighborhood", "address_complement"];
            return (0, a.useEffect)((() => {
                const e = r((async () => {
                    var e;
                    const ticketName = document.getElementsByName('epayco_ticket[name]')[0]??document.getElementsByName('epayco_ticket[nameError]')[0];
                    const ticketContentName = ticketName.value;
                    const nameHelpers =  document.querySelector('input-helper').querySelector("div");
                    const verifyName = (nameElement) => {
                        if (nameElement === '') {
                            document.querySelector('input-name').querySelector(".mp-input").classList.add("mp-error");
                            nameHelpers.style.display = 'flex';
                        }
                    }
                    const ticketContentAddress = document.getElementsByName('epayco_ticket[address]')[0].value;
                    const addressHelpers =  document.querySelector('input-address').querySelector("input-helper").querySelector("div");
                    const verifyAddress = (addressElement) => {
                    if (addressElement === '') {
                        document.querySelector('input-address').querySelector(".mp-input").classList.add("mp-error");
                        addressHelpers.style.display = 'flex';
                    }
                }
                    const ticketContentEmail = document.getElementsByName('epayco_ticket[email]')[0].value; 
                    const emailHelpers =  document.querySelector('input-email').querySelector("input-helper").querySelector("div");
                    const verifyEmail = (emailElement) => {
                        if (emailElement === '') {
                            document.querySelector('input-email').querySelector(".mp-input").classList.add("mp-error");
                            emailHelpers.style.display = 'flex';
                        }
                    }

                    const cellphoneType = document.getElementsByName('epayco_ticket[cellphone]')[0].value; 
                    const ticketContentCellphone = document.getElementsByName('epayco_ticket[cellphoneType]')[0].value; 
                    const cellphoneHelpers =  document.querySelector('input-cellphone').querySelector("input-helper").querySelector("div");
                    const verifyCellphone = (cellphone) => {
                        if (cellphone === '') {
                            document.querySelector('input-cellphone').querySelector(".mp-input").classList.add("mp-error");
                            document.querySelector('input-cellphone').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                            cellphoneHelpers.style.display = 'flex';
                        }
                    }
                   
                    const person_type_value = document.getElementsByName('epayco_ticket[person_type]')[1].value;
                    const doc_type = document.getElementsByName('epayco_ticket[documentType]')[0].value;
                    const documentHelpers =  document.querySelector('input-document').querySelector("input-helper").querySelector("div");
                    const verifyDocument = (ticketContentDocument) => {
                        if (ticketContentDocument === '') {
                            document.querySelector('input-document').querySelector(".mp-input").classList.add("mp-error");
                            document.querySelector('input-document').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                            documentHelpers.style.display = 'flex';
                        }
                    }
                    const doc_number = document.getElementsByName('epayco_ticket[document]').length>0?document.getElementsByName('epayco_ticket[document]'):document.getElementsByName('documentTypeError');
                    const doc_number_value = doc_number[0].value;
                    const countryType = document.getElementsByName('epayco_ticket[countryType]')[0].value;
                    const ticketContentCountry = document.getElementsByName('epayco_ticket[country]')[0].value;
                    const countryHelpers =  document.querySelector('input-country').querySelector("input-helper").querySelector("div");
                    const verifyCountry = (ticketContentCountry) => {
                        if (ticketContentCountry === '') {
                            document.querySelector('input-country').querySelector(".mp-input").classList.add("mp-error");
                            document.querySelector('input-country').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                            countryHelpers.style.display = 'flex';
                        }
                    }
                    var paymentOptionSelected;

                    document.querySelector(".mp-checkout-ticket-container").querySelectorAll(".mp-input-radio-radio").forEach((e => { 
                        if (e.checked) {
                            paymentOptionSelected = e.value;
                        } 
                    }))
                    const termanAndContictionContent = document.querySelector('terms-and-conditions').querySelector('input');
                    const termanAndContictionHelpers = document.querySelector('terms-and-conditions').querySelector(".mp-terms-and-conditions-container");
                    termanAndContictionContent.addEventListener('click', function() {
                        if (termanAndContictionContent.checked) {
                            termanAndContictionHelpers.classList.remove("mp-error")
                        }
                    });

                    
                    const nn = {
                            "epayco_ticket[name]": ticketContentName,
                            "epayco_ticket[address]": ticketContentAddress,
                            "epayco_ticket[email]": ticketContentEmail,
                            "epayco_ticket[cellphoneType]": cellphoneType,
                            "epayco_ticket[cellphone]": ticketContentCellphone,
                            "epayco_ticket[person_type]": person_type_value,
                            "epayco_ticket[identificationtype]": doc_type,
                            "epayco_ticket[doc_number]": doc_number_value,
                            //"epayco_ticket[payment_method_id]": paymentOptionSelected,
                            "epayco_ticket[countryType]": countryType,
                            "epayco_ticket[country]": ticketContentCountry
                        };
                    document.querySelector(".mp-checkout-ticket-container").querySelectorAll(".mp-input-radio-radio").forEach((e => {
                        let paymentselpers = e.parentElement.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.querySelector('input-helper');
                        let child = paymentselpers.querySelector('div');
                        if(e.checked && (nn["epayco_ticket[payment_method_id]"] = e.value)){
                            child.style.display = 'none';
                        }else{
                            child.style.display = 'flex';
                        }
                    }));
                    "" === ticketContentName && verifyName(ticketContentName);
                    "" === ticketContentEmail && verifyEmail(ticketContentEmail);
                    "" === ticketContentAddress && verifyAddress(ticketContentAddress);
                    "" === cellphoneType && verifyCellphone(cellphoneType);
                    "Type"||"Tipo" === doc_type && verifyDocument(doc_number_value);
                    "" === doc_number_value && verifyDocument(doc_number_value);
                    "" === ticketContentCountry && verifyCountry(ticketContentCountry);
                    nn["epayco_ticket[payment_method_id]"] || m(a, "flex");
                    !termanAndContictionContent.checked && termanAndContictionHelpers.classList.add("mp-error");

                    function m(e, t) {
                        e && e.style && (e.style.display = t)
                    }

                    function d(e) {

                        return e && "flex" === e.style.display
                    }

                    return "" !== ticketContentName &&
                    "" !== ticketContentAddress &&
                    "" !==  ticketContentEmail &&
                    "" !== ticketContentCellphone &&
                    "" !== doc_number_value &&
                    "" !== ticketContentCountry &&
                    "Type"||"Tipo" !== doc_type,{
                        type: d(nameHelpers)|| d(addressHelpers)|| d(emailHelpers)|| d(cellphoneHelpers)||d(documentHelpers)||d(countryHelpers)||d(termanAndContictionContent)|| !termanAndContictionContent.checked  ? c.responseTypes.ERROR : c.responseTypes.SUCCESS,
                        meta: {paymentMethodData: nn}
                    }
                }));
                return () => e()
            }), [c.responseTypes.ERROR, c.responseTypes.SUCCESS, r]), (0, e.createElement)("div", {dangerouslySetInnerHTML: {__html: i.params.content}})
        }, l = {
            name: d,
            label: (0, e.createElement)((t => {
                const {PaymentMethodLabel: o} = t.components, a = (0, c.decodeEntities)(i?.params?.fee_title || ""),
                    n = `${p} ${a}`;
                return (0, e.createElement)(o, {text: n})
            }), null),
            content: (0, e.createElement)(u, null),
            edit: (0, e.createElement)(u, null),
            canMakePayment: () => !0,
            ariaLabel: p,
            supports: {features: null !== (r = i?.supports) && void 0 !== r ? r : []}
        };
    (0, t.registerPaymentMethod)(l)
})();