(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        a = window.wc.wcSettings,
        c = window.wp.element,
        n = window.wp.htmlEntities,
        o = "epayco_blocks_update_cart";
    const m = ({
                   labelMessage: t,
                   helperMessage: a,
                   inputName: c,
                   hiddenId: n,
                   inputDataCheckout: o,
                   selectId: m,
                   selectName: s,
                   selectDataCheckout: r,
                   flagError: l,
                   documents: i,
                   validate: d
               }) =>
            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"}, (0, e.createElement)("input-document", {
                "label-message": t,
                "helper-message": a,
                "input-name": c,
                "hidden-id": n,
                "input-data-checkout": o,
                "select-id": m,
                "select-name": s,
                "select-data-checkout": r,
                "flag-error": l,
                documents: i,
                validate: d
            })),
        mm = ({
                  labelMessage: t,
                  helperMessage: a,
                  inputName: c,
                  hiddenId: n,
                  inputDataCheckout: o,
                  selectId: mm,
                  selectName: s,
                  selectDataCheckout: r,
                  flagError: l,
                  documents: i,
                  validate: d
              }) =>
            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-cellphone"}, (0, e.createElement)("input-cellphone", {
                "label-message": t,
                "helper-message": a,
                "input-name": c,
                "hidden-id": n,
                "input-data-checkout": o,
                "select-id": mm,
                "select-name": s,
                "select-data-checkout": r,
                "flag-error": l,
                documents: i,
                validate: d
            })),
        s = ({
                 isVisible: t,
                 message: a,
                 inputId: c,
                 id: n,
                 dataMain: o
             }) => (0, e.createElement)("input-helper", {
            isVisible: t,
            message: a,
            "input-id": c,
            id: n,
            "data-main": o
        }),
        r = ({isOptinal: t, message: a, forId: c}) =>
            (0, e.createElement)("input-label", {
                isOptinal: t,
                message: a,
                for: c
            }),
        l = ({methods: t}) =>
            (0, e.createElement)("payment-methods", {methods: t}),
        i = ({
                 description: t,
                 linkText: a,
                 linkSrc: c,
                 checkoutClass: n = "pro"
             }) =>
            (0, e.createElement)("div", {className: `mp-checkout-${n}-terms-and-conditions`},
                (0, e.createElement)("terms-and-conditions", {
                    description: t,
                    "link-text": a,
                    "link-src": c
                })),
        d = ({
                 title: t,
                 description: a,
                 linkText: c,
                 linkSrc: n
             }) => (0, e.createElement)("div", {className: "mp-checkout-pro-test-mode"}, (0, e.createElement)("test-mode", {
            title: t,
            description: a,
            "link-text": c,
            "link-src": n
        })),
        ph = ({
                  labelMessage: t,
                  helperMessage: n,
                  inputName: a,
                  hiddenId: c,
                  inputDataCheckout: o,
                  selectId: ph,
                  selectName: r,
                  selectDataCheckout: i,
                  flagError: l,
                  documents: pp,
                  validate: m
              }) =>
            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-cellphone"},
                (0, e.createElement)("input-cellphone", {
                    "label-message": t,
                    "helper-message": n,
                    "input-name": a,
                    "hidden-id": c,
                    "input-data-checkout": o,
                    "select-id": ph,
                    "select-name": r,
                    "select-data-checkout": i,
                    "flag-error": l,
                    documents: pp,
                    validate: m
                })
            )

    ;
    var u;
    const _ = "mp_checkout_blocks",
        h = "woo-epayco-custom",
        k = (0, a.getSetting)("woo-epayco-custom_data", {}),
        E = (0, n.decodeEntities)(k.title) || "Checkout Custom", y = t => {
            (e => {
                const {extensionCartUpdate: t} = wc.blocksCheckout,
                    {eventRegistration: a, emitResponse: n} = e,
                    {onPaymentSetup: m} = a;
                (0, c.useEffect)((() => {
                    ((e, t) => {
                        e({namespace: o, data: {action: "add", gateway: t}})
                    })(t, h);
                    const e = m((() => ({type: n.responseTypes.SUCCESS})));
                    return () => (((e, t) => {
                        e({namespace: o, data: {action: "remove", gateway: t}})
                    })(t, h), e())
                }), [m])
            })(t);
            const {
                test_mode: a,
                test_mode_title: n,
                test_mode_description: u,
                test_mode_link_text: E,
                test_mode_link_src: y,
                card_number_input_label: F,
                card_number_input_helper: O,
                card_holder_name_input_label: P,
                card_holder_name_input_helper: U,
                card_holder_email_input_label: EE,
                card_holder_email_input_helper: HH,
                card_holder_email_input_invalid: cheii,
                card_holder_address_input_label: PP,
                card_holder_address_input_helper: UU,
                card_expiration_input_label: D,
                card_expiration_input_helper: L,
                card_expiration_input_invalid_length: exerr,
                card_security_code_input_label: V,
                card_security_code_input_helper: B,
                card_security_code_input_invalid_length: csciil,
                card_document_input_label: $,
                card_document_input_helper: q,
                card_issuer_input_label: Y,
                card_holder_cellphone_input_label:chcil,
                card_holder_cellphone_input_helper:chcih,
                amount: J,
                message_error_amount: G
            } = k.params;
            if (null == J) return (0, e.createElement)(e.Fragment, null, (0, e.createElement)("p", {className: "alert-message"}, G));
            const W = (0, c.useRef)(null), [X, Z] = (0, c.useState)("custom"), {
                eventRegistration: ee,
                emitResponse: te,
                onSubmit: ae
            } = t, {onPaymentSetup: ce, onCheckoutSuccess: ne, onCheckoutFail: oe} = ee;
            return window.mpFormId = "blocks_checkout_form",
                window.mpCheckoutForm = document.querySelector(".wc-block-components-form.wc-block-checkout__form"),
                jQuery(window.mpCheckoutForm).prop("id", mpFormId),
                (0, c.useEffect)(( () => {
                    var e, a;
                    e = t.billing.cartTotal.value,
                        a = t.billing.currency, cardFormMounted && cardForm.unmount()
                    /*initCardForm(function (e, t) {
                        if (!Number.isInteger(e) || "object" != typeof t) throw new Error("Invalid input");
                        const a = (e / Math.pow(10, t.minorUnit)).toFixed(t.minorUnit).split(".");
                        return `${a[0]}.${a[1]}`
                    }(e, a))*/

                }),[t.billing.cartTotal.value]),
                (0, c.useEffect)((() => {
                    const card = document.querySelector("#form-checkout__cardNumber-container");
                    card.addEventListener('input', function (e) {
                        const input = event.target;
                        input.value = input.value.replace(/[^0-9]/g, '');
                        let cardHelper = card.parentElement.parentElement.querySelector("input-helper > div");

                        if(input.value.length > 0){
                            cardHelper.style.display = "none";
                        }else{
                            cardHelper.style.display = "flex";
                        }
                    })
                    const name = document.querySelector("#form-checkout__cardholderName");
                    let nameHelper = name.parentElement.querySelector("input-helper > div");
                    name.addEventListener('input', function (e) {
                        const input = event.target;
                        this.value = this.value.replace(/[0-9]/g, '');
                        if(input.value.length > 0){
                            nameHelper.style.display = "none";
                        }else{
                            nameHelper.style.display = "flex";
                        }
                    })
                    const email = document.querySelector("#form-checkout__cardholderEmail");
                    let emailHelper = email.parentElement.querySelector("input-helper > div");
                    email.addEventListener('input', function (e) {
                        const input = event.target;
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if(emailPattern.test(input.value)){
                            emailHelper.style.display = "none";
                        }else{
                            emailHelper.lastChild.innerText=cheii;
                            emailHelper.style.display = "flex";
                        }
                    })
                    const scode  = document.querySelector("#form-checkout__securityCode-container");
                    let securycode = scode.parentElement.querySelector("input-helper > div");
                    scode.addEventListener('input', function (e) {
                        const input = event.target;
                        input.value = input.value.replace(/[^0-9]/g, '');
                        if(input.value.length > 0){
                            if(input.value.length<3){
                                securycode.lastChild.innerText=csciil
                                securycode.style.display = "flex";
                            }else{
                                securycode.style.display = "none";
                            }
                        }else{
                            securycode.style.display = "flex";
                        }
                    })
                    const expitation  = document.getElementById('form-checkout__expirationDate-container');
                    expitation.addEventListener('input', function (e) {
                        var input = e.target.value;
                        var regex = /^(\d{2})\/(\d{2})$/;
                        var partes = input.match(regex);
                        input = input.replace(/\D/g, '');
                        if (input.length > 2) {
                            input = input.slice(0, 2) + '/' + input.slice(2);
                        }
                        if (input.length > 5) {
                            input = input.slice(0, 5) + '/' + input.slice(5);
                        }
                        e.target.value = input.slice(0, 5);
                        if (partes) {
                            var mes = parseInt(partes[1], 10);
                            var ano = parseInt(partes[2], 10);
                            var fechaActual = new Date();
                            var anoActual = fechaActual.getFullYear();
                            var mesActual = fechaActual.getMonth() + 1;
                            let a = document.querySelector(".mp-checkout-custom-card-column").querySelector("input-helper > div");
                            if (2000+ano < anoActual) {
                                a.style.display = "flex";
                                a.lastChild.innerText=exerr
                            }else{
                                if( 2000+ano === anoActual ){
                                    if(mes < mesActual){
                                        a.style.display = "flex";
                                        a.lastChild.innerText=exerr
                                    }else{
                                        a.style.display = "none";
                                    }
                                }else{
                                    a.style.display = "none";
                                }
                            }
                        }
                    });
                    const cardholderAdress  = document.querySelector("#form-checkout__cardholderAddress-container");
                    cardholderAdress.addEventListener('input', function (e) {
                        const input = cardholderAdress.value;
                        let cardholderAdressHelper = cardholderAdress.parentElement.querySelector("input-helper > div");
                        if(input.length > 0){
                            cardholderAdressHelper.style.display = "none";
                        }else{
                            cardholderAdressHelper.style.display = "flex";
                        }
                    })
                    const cardholderCellphone  = document.querySelector("#form-checkout__identificationCellphone-container");
                    cardholderCellphone.addEventListener('input', function (e) {
                        const this_ = this;
                        let cardholderCellphone = this_.querySelector("input");
                        const input = cardholderCellphone.value;
                        let cardholderCellphoneHelper = cardholderCellphone.parentElement.parentElement.querySelector("input-helper > div");
                        if(input.length > 0){
                            cardholderCellphoneHelper.style.display = "none";
                        }else{
                            cardholderCellphoneHelper.style.display = "flex";
                        }
                    })
                    //cardFormMounted && cardForm.unmount(), initCardForm();
                    const e = ce((async () => {

                        const cellphoneType = W.current.querySelector("#type_cellphone")?.value.split("+")[1];
                        const cellphone = document.getElementById('form-checkout__identificationCellphone-container').querySelector("input")?.value;
                        const e = document.querySelector("#form-checkout__cardholderName"),
                            t = document.querySelector("#mp-card-holder-name-helper"),
                            em = document.querySelector("#form-checkout__cardholderEmail"),
                            eh = document.querySelector("#mp-card-holder-email-helper"),
                            ca = document.querySelector("#form-checkout__cardholderAddress-container"),
                            cah = document.querySelector("#mp-card-holder-address-helper"),
                            //phone = document.getElementById('form-checkout__identificationTypeAdress'),
                            phone = cellphone,
                            //phoneCode = document.getElementById('form-checkout__identificationTypeAdress'),
                            phoneCode = cellphoneType,
                            dues = document.getElementById('form-checkout__dues')
                        ;

                        const card = document.querySelector("#form-checkout__cardNumber-container");
                        let cardHelper = card.parentElement.parentElement.querySelector("input-helper > div");
                        const scode  = document.querySelector("#form-checkout__securityCode-container");
                        let securycode = scode.parentElement.querySelector("input-helper > div");
                        const expitation  = document.querySelector("#expirationDate");
                        let expirationHelper = expitation.parentElement.querySelector("input-helper > div");
                        const identificationNumber  = document.querySelector("#form-checkout__identificationNumber-container");
                        const identificacionNumberValue = identificationNumber.parentElement.querySelector("input");
                        let identificationNumberHelper = identificationNumber.parentElement.querySelector("input-helper > div");
                        const cardholderAdress  = document.querySelector("#form-checkout__cardholderAddress-container");
                        let cardholderAdressHelper = cardholderAdress.parentElement.querySelector("input-helper > div");
                        const cardholderCellphone  = document.querySelector("#form-checkout__identificationCellphone-container");
                        let cardholderCellphoneVelue = cardholderAdress.parentElement.parentElement.querySelector("input");
                        let cardholderCellphoneHelper = cardholderCellphone.parentElement.parentElement.querySelector("input-helper > div");
                        var a,b,cac, sc,scc, token, cardV,indentiNumber,holderAdress, holderCellphone;
                        //const phoneNUmber = phone.parentElement;
                        if ("" == e.value && ("flex", (a = t) && a.style && (a.style.display = "flex")))
                            if ("" == em.value && ("flex", (b = eh) && b.style && (b.style.display = "flex")))
                                if ("" == ca.value && ("flex", (cac = cah) && cac.style && (cac.style.display = "flex")))
                                    if ("" == scode.firstChild.value && ("flex", (sc = securycode) && sc.style && (sc.style.display = "flex")))
                                        if ("" == expitation.firstChild.value && ("flex", (scc = expirationHelper) && scc.style && (scc.style.display = "flex")))
                                            if ("" == card.value && ("flex", (cardV = cardHelper) && cardV.style && (cardV.style.display = "flex")))
                                                if ("" == identificacionNumberValue.value && ("flex", (indentiNumber = identificationNumberHelper) && indentiNumber.style && (indentiNumber.style.display = "flex")))
                                                    if ("" == cardholderAdress.value && ("flex", (holderAdress = cardholderAdressHelper) && holderAdress.style && (holderAdress.style.display = "flex")))
                                                        if ("" == cardholderCellphoneVelue.value && ("flex", (holderCellphone = cardholderCellphoneHelper) && holderCellphone.style && (holderCellphone.style.display = "flex")));
                        try {
                            /*if (!CheckoutPage.validateInputsCreateToken()) return {type: te.responseTypes.ERROR};
                            {
                                 //token = await cardForm.createCardToken();
                                 token = "await cardForm.createCardToken()";
                            }*/
                        debugger
                            let ticketHelpers = document.querySelectorAll('input-helper');
                            document.querySelector("form.checkout")
                            /*verifyName(CustomContent)
                            verifyEmail(CustomContent)
                            verifyAddress(CustomContent)
                            cellphoneDocument(CustomContent)
                            verifyDocument(CustomContent);*/
                            if (!checkForErrors(ticketHelpers)) {
                                debugger
                                var publicKey = wc_epayco_custom_checkout_params.public_key_epayco;
                                var token;
                                ePayco.setPublicKey(publicKey);
                                ePayco.setLanguage("es");
                                token = await new Promise(function(resolve, reject) {
                                    ePayco.token.create(document, function (data) {
                                        debugger
                                            if(data.status==='error'){
                                                reject(false)
                                            }else{
                                                resolve(data.data.token)
                                            }
                                        })
                                })
                                //token = "await cardForm.createCardToken()";
                            }else{
                                return {type: te.responseTypes.ERROR};
                            }
                        } catch (e) {
                            console.warn("Token creation error: ", e)
                        }
                        const c = W.current, n = {};

                        const values = {
                            "epayco_custom[cardTokenId]": token,
                            "epayco_custom[name]": e.value,
                            "epayco_custom[address]": ca.value,
                            //"epayco_custom[phoneCode]": phoneCode,
                            "epayco_custom[cellphone]":cellphone,
                            "epayco_custom[documenttype]":document.getElementById("form-checkout__identificationType").value,
                            "epayco_custom[doc_number]":identificacionNumberValue.value,
                            "epayco_custom[email]":em.value,
                            "epayco_custom[dues]":dues.value

                        };
                        return c.childNodes.forEach((e => {
                            "INPUT" === e.tagName && e.name && (n[e.name] = e.value)
                        })), Z("custom"),
                            {
                                type: te.responseTypes.SUCCESS,
                                meta: {paymentMethodData: values
                                }
                            }
                    }));
                    return () => e()
                }), [ce, te.responseTypes.ERROR, te.responseTypes.SUCCESS]),
                (0, c.useEffect)((() => {
                    const e = ne((async e => {
                        const t = e.processingResponse,
                            a = e.processingResponse.paymentDetails;
                        return  {type: te.responseTypes.SUCCESS}
                    }));
                    return () => e()
                }), [ne]),
                (0, c.useEffect)((() => {
                    const e = oe((e => {
                        const t = e.processingResponse;
                        return {
                            type: te.responseTypes.FAIL,
                            messageContext: te.noticeContexts.PAYMENTS,
                            message: t.paymentDetails.message
                        }
                    }));
                    return () => e()
                }), [oe]),
                (0, e.createElement)("div", null,
                    (0, e.createElement)("div", {className: "mp-checkout-custom-load"},
                        (0, e.createElement)("div", {className: "spinner-card-form"})),
                    (0, e.createElement)("div", {className: "mp-checkout-container"},
                        (0, e.createElement)("div", {className: "mp-checkout-custom-container"}, a ?
                                (0, e.createElement)("div", {className: "mp-checkout-pro-test-mode"},
                                    (0, e.createElement)(d, {
                                        title: n,
                                        description: u,
                                        linkText: E,
                                        linkSrc: y
                                    })
                                ) : null,
                            (0, e.createElement)("div", {id: "mp-custom-checkout-form-container"},
                                (0, e.createElement)("div", {className: "mp-checkout-custom-card-form"},
                                    (0, e.createElement)("div", {className: "mp-checkout-custom-card-row"},
                                        (0, e.createElement)(r, {
                                            isOptinal: !1,
                                            message: F,
                                            forId: "mp-card-number"
                                        }),
                                        (0, e.createElement)("div", {
                                                className: "mp-checkout-custom-card-input",
                                                id: "cardNumber"
                                            },
                                            (0, e.createElement)("input", {
                                                className: "mp-checkout-custom-card-input cardInput",
                                                maxlength: "25",
                                                inputMode: "numeric",
                                                autoComplete: "off",
                                                placeholder: "0000 0000 0000 0000",
                                                id: "form-checkout__cardNumber-container",
                                                name: "cardNumber",
                                                "data-checkout": "cardNumber",
                                                "data-epayco":"card[number]"
                                            }),
                                        ),
                                        (0, e.createElement)(s, {
                                            isVisible: !1,
                                            message: O,
                                            inputId: "mp-card-number-helper"
                                        })
                                    ),
                                    (0, e.createElement)("div", {className: "mp-checkout-custom-card-row mp-checkout-custom-dual-column-row"},
                                        (0, e.createElement)("div", {className: "mp-checkout-custom-card-column"},
                                            (0, e.createElement)(r, {
                                                message: D,
                                                isOptinal: !1
                                            }),
                                            (0, e.createElement)("div", {
                                                    id: "expirationDate",
                                                    className: "mp-checkout-custom-card-input mp-checkout-custom-left-card-input"
                                                },
                                                (0, e.createElement)("input", {
                                                    className: "mp-checkout-custom-card-input mp-checkout-custom-left-card-input cardInput",
                                                    maxlength: "25",
                                                    inputMode: "numeric",
                                                    autoComplete: "off",
                                                    placeholder: "mm/yy",
                                                    id: "form-checkout__expirationDate-container",
                                                    name: "expirationDate",
                                                    "data-checkout": "expirationDate",
                                                    "data-epayco":"card[date_exp]"
                                                }),
                                            ),
                                            (0, e.createElement)(s, {
                                                isVisible: !1,
                                                message: L,
                                                inputId: "mp-expiration-date-helper"
                                            })
                                        ),
                                        (0, e.createElement)("div", {className: "mp-checkout-custom-card-column"},
                                            (0, e.createElement)(r, {
                                                message: V,
                                                isOptinal: !1
                                            }),
                                            (0, e.createElement)("div", {
                                                    id: "form-checkout__securityCode-container",
                                                    className: "mp-checkout-custom-card-input"
                                                },
                                                (0, e.createElement)("input", {
                                                    className: "mp-checkout-custom-card-input cardInput",
                                                    maxlength: "4",
                                                    inputMode: "numeric",
                                                    autoComplete: "off",
                                                    placeholder: "",
                                                    id: "securityCode",
                                                    name: "securityCode",
                                                    "data-checkout": "securityCode",
                                                    "data-epayco":"card[cvc]"
                                                }),
                                            ),
                                            (0, e.createElement)("p", {
                                                id: "mp-security-code-info",
                                                className: "mp-checkout-custom-info-text"
                                            }),
                                            (0, e.createElement)(s, {
                                                isVisible: !1,
                                                message: B,
                                                inputId: "mp-security-code-helper"
                                            })
                                        )
                                    ),
                                    (0, e.createElement)("div", {className: "mp-checkout-pse-input-cellphone", id: "mp-card-holder-div"},
                                        (0, e.createElement)(r, {
                                            message: P,
                                            isOptinal: !1
                                        }),
                                        (0, e.createElement)("input", {
                                            className: "mp-checkout-pse-card-input mp-card-holder-name ",
                                            placeholder: "Jonh Doe",
                                            id: "form-checkout__cardholderName",
                                            name: "mp-card-holder-name",
                                            "data-checkout": "cardholderName",
                                            "data-epayco":"card[name]"
                                        }),
                                        (0, e.createElement)(s, {
                                            isVisible: !1,
                                            message: U,
                                            inputId: "mp-card-holder-name-helper",
                                            dataMain: "mp-card-holder-name"
                                        })
                                    ),
                                    (0, e.createElement)("div", {className: "mp-checkout-pse-input-cellphone", id: "mp-card-holder-div"},
                                        (0, e.createElement)(r, {
                                            message: EE,
                                            isOptinal: !1
                                        }),
                                        (0, e.createElement)("input", {
                                            className: "mp-checkout-pse-card-input mp-card-holder-name ",
                                            placeholder: "example@email.com",
                                            id: "form-checkout__cardholderEmail",
                                            name: "mp-card-holder-email",
                                            "data-checkout": "cardholderEmail",
                                            "data-epayco":"card[email]"
                                        }),
                                        (0, e.createElement)(s, {
                                            isVisible: !1,
                                            message: HH,
                                            inputId: "mp-card-holder-email-helper",
                                            dataMain: "mp-card-holder-email"
                                        })
                                    ),
                                    (0, e.createElement)("div", {className: "mp-checkout-custom-card-row", id: "mp-card-holder-div"},
                                        (0, e.createElement)(r, {
                                            message: PP,
                                            isOptinal: !1
                                        }),
                                        (0, e.createElement)("input", {
                                            className: "mp-checkout-custom-card-input mp-card-holder-adress",
                                            placeholder: "Street 123",
                                            id: "form-checkout__cardholderAddress-container",
                                            name: "mp-card-holder-address",
                                            "data-checkout": "cardholderAddress"
                                        }),
                                        (0, e.createElement)(s, {
                                            isVisible: !1,
                                            message: UU,
                                            inputId: "mp-card-holder-address-helper",
                                            dataMain: "mp-card-holder-address"
                                        })
                                    ),
                                    (0, e.createElement)("div", {id: "mp-doc-div", className: "mp-checkout-custom-input-document", style: {display: "block"}},
                                        (0, e.createElement)(m, {
                                            labelMessage: $,
                                            helperMessage: q,
                                            inputName: "identificationNumber",
                                            hiddenId: "form-checkout__identificationNumber-container",
                                            inputDataCheckout: "docNumber",
                                            selectId: "form-checkout__identificationType",
                                            selectName: "identificationType",
                                            selectDataCheckout: "docType",
                                            flagError: "docNumberError"
                                        })
                                    ),
                                    (0, e.createElement)("div", {className: "mp-checkout-pse-input-cellphone", id: "mp-pse-holder-div"},
                                        (0, e.createElement)(ph, {
                                            labelMessage: chcil,
                                            helperMessage: chcih,
                                            validate: "true",
                                            selectId: "type_cellphone",
                                            flagError: "epayco_custom[numberCellphoneError]",
                                            inputName: "epayco_custom[cellphone]",
                                            selectName: "epayco_custom[cellphoneType]",
                                            documents: '["+57","+1"]'
                                        })
                                    ),
                                    (0, e.createElement)("div", {id: "mp-dues-div", className: "mp-checkout-custom-input-document", style: {display: "block"}},
                                        (0, e.createElement)(r, {isOptinal: !1, message: "Installments", forId: "mp-issuer"}),
                                        (0, e.createElement)("div", {className: "mp-input-select-input"},
                                            (0, e.createElement)("select", {name: "issuer", id: "form-checkout__dues", className: "mp-input-select-select", children: [
                                                    (0, e.createElement)("option", { value: "1" }, "1"),
                                                    (0, e.createElement)("option", { value: "2" }, "2"),
                                                    (0, e.createElement)("option", { value: "3" }, "3"),
                                                    (0, e.createElement)("option", { value: "4" }, "4"),
                                                    (0, e.createElement)("option", { value: "5" }, "5"),
                                                    (0, e.createElement)("option", { value: "6" }, "6"),
                                                    (0, e.createElement)("option", { value: "7" }, "7"),
                                                    (0, e.createElement)("option", { value: "8" }, "8"),
                                                    (0, e.createElement)("option", { value: "9" }, "9"),
                                                    (0, e.createElement)("option", { value: "10" }, "10"),
                                                    (0, e.createElement)("option", { value: "11" }, "11"),
                                                    (0, e.createElement)("option", { value: "12" }, "12"),
                                                    (0, e.createElement)("option", { value: "13" }, "13"),
                                                    (0, e.createElement)("option", { value: "14" }, "14"),
                                                    (0, e.createElement)("option", { value: "15" }, "15"),
                                                    (0, e.createElement)("option", { value: "16" }, "16"),
                                                    (0, e.createElement)("option", { value: "17" }, "17"),
                                                    (0, e.createElement)("option", { value: "18" }, "18"),
                                                    (0, e.createElement)("option", { value: "19" }, "19"),
                                                    (0, e.createElement)("option", { value: "20" }, "20"),
                                                    (0, e.createElement)("option", { value: "21" }, "21"),
                                                    (0, e.createElement)("option", { value: "22" }, "22"),
                                                    (0, e.createElement)("option", { value: "23" }, "23"),
                                                    (0, e.createElement)("option", { value: "24" }, "24"),
                                                    (0, e.createElement)("option", { value: "10" }, "25"),
                                                    (0, e.createElement)("option", { value: "11" }, "26"),
                                                    (0, e.createElement)("option", { value: "12" }, "27"),
                                                    (0, e.createElement)("option", { value: "13" }, "28"),
                                                    (0, e.createElement)("option", { value: "14" }, "29"),
                                                    (0, e.createElement)("option", { value: "15" }, "30"),
                                                    (0, e.createElement)("option", { value: "16" }, "31"),
                                                    (0, e.createElement)("option", { value: "17" }, "32"),
                                                    (0, e.createElement)("option", { value: "18" }, "33"),
                                                    (0, e.createElement)("option", { value: "19" }, "34"),
                                                    (0, e.createElement)("option", { value: "20" }, "35"),
                                                    (0, e.createElement)("option", { value: "21" }, "36"),
                                                    (0, e.createElement)("option", { value: "22" }, "37"),
                                                    (0, e.createElement)("option", { value: "23" }, "38"),
                                                    (0, e.createElement)("option", { value: "24" }, "39"),
                                                    (0, e.createElement)("option", { value: "24" }, "40"),
                                                    (0, e.createElement)("option", { value: "24" }, "41"),
                                                    (0, e.createElement)("option", { value: "24" }, "42"),
                                                    (0, e.createElement)("option", { value: "21" }, "43"),
                                                    (0, e.createElement)("option", { value: "22" }, "44"),
                                                    (0, e.createElement)("option", { value: "23" }, "45"),
                                                    (0, e.createElement)("option", { value: "24" }, "46"),
                                                    (0, e.createElement)("option", { value: "24" }, "47"),
                                                    (0, e.createElement)("option", { value: "24" }, "48")
                                                ]})
                                        )
                                    )
                                ),
                                (0, e.createElement)("div", {id: "mp-checkout-custom-installments", className: "mp-checkout-custom-installments-display-none", style: {display: "none"}},
                                    (0, e.createElement)("div", {id: "mp-checkout-custom-issuers-container", className: "mp-checkout-custom-issuers-container"},
                                        (0, e.createElement)("div", {className: "mp-checkout-custom-card-row"},
                                            (0, e.createElement)(r, {isOptinal: !1, message: Y, forId: "mp-issuer"})
                                        ),
                                        (0, e.createElement)("div", {className: "mp-input-select-input"},
                                            (0, e.createElement)("select", {name: "issuer", id: "form-checkout__issuer", className: "mp-input-select-select", children: [], style: {display: "none"}})
                                        )
                                    ),
                                    (0, e.createElement)("div", {id: "mp-checkout-custom-installments-container", className: "mp-checkout-custom-installments-container"}),
                                    (0, e.createElement)("select", {style: {display: "none"}, "data-checkout": "installments", name: "installments", id: "form-checkout__installments", className: "mp-input-select-select"}),
                                ),
                            )
                        )
                    ),
                    (0, e.createElement)("div", {
                            ref: W,
                            id: "epayco-utilities",
                            style: {display: "none"}
                        }, (0, e.createElement)("input", {
                            type: "hidden",
                            id: "cardTokenId",
                            name: "epayco_custom[token]"
                        })
                    )
                )
        },
        g = {
            name: h,
            label: (0, e.createElement)(
                (
                    t => {const {PaymentMethodLabel: a} = t.components,
                        c = (0, n.decodeEntities)(k?.params?.fee_title || ""),
                        o = `${E} ${c}`;
                        return (0, e.createElement)(a, {text: o})
                    }), null),
            content: (0, e.createElement)(y, null),
            edit: (0, e.createElement)(y, null),
            canMakePayment: () => !0,
            ariaLabel: E,
            supports: {features: null !== (u = k?.supports) && void 0 !== u ? u : []}
        };
    (0, t.registerPaymentMethod)(g)
})();


