(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        n = window.wc.wcSettings,
        a = window.wp.element,
        c = window.wp.htmlEntities,
        o = "epayco_blocks_update_cart",
        s = ({
                 labelMessage: t,
                 helperMessage: n,
                 inputName: a,
                 hiddenId: c,
                 inputDataCheckout: o,
                 selectId: s,
                 selectName: r,
                 selectDataCheckout: i,
                 flagError: m,
                 documents: l,
                 validate: d
             }) =>
            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                (0, e.createElement)("input-document", {
                    "label-message": t,
                    "helper-message": n,
                    "input-name": a,
                    "hidden-id": c,
                    "input-data-checkout": o,
                    "select-id": s,
                    "select-name": r,
                    "select-data-checkout": i,
                    "flag-error": m,
                    documents: l,
                    validate: d
                })
            ),
        r = ({
                 isVisible: t,
                 message: n,
                 inputId: a,
                 id: c,
                 dataMain: o
             }) => (0, e.createElement)("input-helper", {
            isVisible: t,
            message: n,
            "input-id": a,
            id: c,
            "data-main": o
        }),
        i = ({name: t, buttonName: n, columns: a}) =>
            (0, e.createElement)("input-table", {
                name: t,
                "button-name": n,
                columns: a
            }),
        m = ({
                 description: t,
                 linkText: n,
                 linkSrc: a,
                 checkoutClass: c = "pro"
             }) => (0, e.createElement)("div", {className: `mp-checkout-${c}-terms-and-conditions`},
            (0, e.createElement)("terms-and-conditions", {
                description: t,
                "link-text": n,
                "link-src": a
            })
        ),
        l = ({
                 title: t,
                 description: n,
                 linkText: a,
                 linkSrc: c
             }) => (0, e.createElement)("div", {className: "mp-checkout-pro-test-mode"},
            (0, e.createElement)("test-mode", {
                title: t,
                description: n,
                "link-text": a,
                "link-src": c
            })
        ),
        rr = ({isOptinal: t, message: a, forId: o}) =>
            (0, e.createElement)("input-label", {
                isOptinal: t,
                message: a,
                for: o
            }),
        ss = ({
                  isVisible: t,
                  message: a,
                  inputId: o,
                  id: n,
                  dataMain: c
              }) => (0, e.createElement)("input-helper", {
            isVisible: t,
            message: a,
            "input-id": o,
            id: n,
            "data-main": c
        }),
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
            ),
        ps = ({
                  name: t,
                  label: n,
                  optional: a,
                  options: c,
                  helperMessage: o,
                  hiddenId: s,
                  defaultOption: r
              }) =>
            (0, e.createElement)("input-select", {
                name: t,
                label: n,
                options: c,
                optional: a,
                "helper-message": o,
                "hidden-id": s,
                "default-option": r
            })
    ;
    var p;
    const u = "mp_checkout_blocks",
        _ = "woo-epayco-ticket",
        k = (0, n.getSetting)("woo-epayco-ticket_data", {}),
        g = (0, c.decodeEntities)(k.title) || "Checkout Ticket",
        h = t => {
            (e => {
                const {extensionCartUpdate: t} = wc.blocksCheckout, {
                    eventRegistration: n,
                    emitResponse: c
                } = e, {onPaymentSetup: s, onCheckoutSuccess: r, onCheckoutFail: i} = n;
                (0, a.useEffect)((() => {
                    ((e, t) => {
                        e({namespace: o, data: {action: "add", gateway: t}})
                    })(t, _);
                    const e = s((() => ({type: c.responseTypes.SUCCESS})));
                    return () => (((e, t) => {
                        e({namespace: o, data: {action: "remove", gateway: t}})
                    })(t, _), e())
                }), [s]), (0, a.useEffect)((() => {
                    const e = r((async e => {
                        const t = e.processingResponse;
                        return {
                            type: c.responseTypes.SUCCESS,
                            messageContext: c.noticeContexts.PAYMENTS,
                            message: t.paymentDetails.message
                        }
                    }));
                    return () => e()
                }), [r]), (0, a.useEffect)((() => {
                    const e = i((e => {
                        const t = e.processingResponse;
                        return {
                            type: c.responseTypes.FAIL,
                            messageContext: c.noticeContexts.PAYMENTS,
                            message: t.paymentDetails.message
                        }
                    }));
                    return () => e()
                }), [i])
            })(t);
            const {
                test_mode_title: n,
                test_mode_description: c,
                test_mode_link_text: p,
                test_mode_link_src: g,
                input_name_label: Nl,
                input_name_helper: Nh,
                input_address_label: Al,
                input_address_helper: Ah,
                input_email_label: Em,
                input_email_helper: Eh,
                input_ind_phone_label: kk,
                input_ind_phone_helper: hh,
                person_type_label: PT,
                input_document_label: h,
                input_document_helper: y,
                ticket_text_label: E,
                input_table_button: S,
                input_helper_label: f,
                payment_methods: w,
                amount: b,
                site_id: C,
                terms_and_conditions_description: v,
                terms_and_conditions_link_text: N,
                terms_and_conditions_link_src: T,
                test_mode: R,
                message_error_amount: x
            } = k.params;
            if (null == b) return (0, e.createElement)(e.Fragment, null, (0, e.createElement)("p", {className: "alert-message"}, x));
            const M = (0, a.useRef)(null),
                {eventRegistration: I, emitResponse: P} = t,
                {onPaymentSetup: O} = I;

            let B = {
                labelMessage: h,
                helperMessage: y,
                validate: "true",
                selectId: "docType",
                flagError: "epayco_ticket[docNumberError]",
                inputName: "epayco_ticket[docNumber]",
                selectName: "epayco_ticket[docType]",
                documents: '["CC","CE","NIT","TI","PPN","SSN","LIC","DNI"]'
            };
            return (0, a.useEffect)((() => {
                const holderName =  document.getElementById('form-checkout__cardholderName');
                const holderNameHelper = document.getElementById('mp-card-holder-name-helper');
                holderName.addEventListener('input', function (e) {
                    const input = event.target;
                    input.value = input.value.replace(/[0-9]/g, '');
                });
                holderName.addEventListener("focus", function(e)  {
                    holderName.classList.add("mp-focus"),
                        holderName.classList.remove("mp-error")
                    holderNameHelper.style.display = "none"
                });
                holderName.addEventListener("focusout", function(e)  {
                    holderName.classList.remove("mp-focus");
                    const input = event.target;
                    if(input.value !== ""){
                        holderName.classList.remove("mp-error")
                    }else{
                        holderName.classList.add("mp-error"),
                            holderNameHelper.style.display = "flex"
                    }
                });
                const holderAddress =  document.getElementById('form-checkout__cardholderAddress');
                const holderAddressHelper = document.getElementById('mp-card-holder-address-helper');
                holderAddress.addEventListener("focus", function(e)  {
                    holderAddress.classList.add("mp-focus"),
                        holderAddress.classList.remove("mp-error")
                    holderAddressHelper.style.display = "none"
                });
                holderAddress.addEventListener("focusout", function(e)  {
                    holderAddress.classList.remove("mp-focus");
                    const input = event.target;
                    if(input.value !== ""){
                        holderAddress.classList.remove("mp-error")
                    }else{
                        holderAddress.classList.add("mp-error"),
                            holderAddressHelper.style.display = "flex"
                    }
                });

                const holderEmail =  document.getElementById('form-checkout__cardholderEmail');
                const holderEmailHelper = document.getElementById('mp-card-holder-email-helper');
                holderEmail.addEventListener('input', function (e) {
                    const input = event.target;
                    const emailValue = input.value;
                    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                    if (!emailPattern.test(emailValue)) {
                        holderEmail.classList.add("mp-error")
                        holderEmailHelper.style.display = "flex"
                    } else {
                        holderEmail.classList.remove("mp-error")
                        holderEmailHelper.style.display = "none"// Limpia el mensaje de error
                    }
                });
                holderEmail.addEventListener("focus", function(e)  {
                    holderEmail.classList.add("mp-focus"),
                        holderEmail.classList.remove("mp-error")
                    holderEmailHelper.style.display = "none"
                });
                holderEmail.addEventListener("focusout", function(e)  {
                    holderEmail.classList.remove("mp-focus");
                    const input = event.target;
                    if(input.value !== ""){
                        holderEmail.classList.remove("mp-error")
                    }else{
                        holderEmail.classList.add("mp-error"),
                            holderEmailHelper.style.display = "flex"
                    }
                });

                var paymentSelected;
                var selectElement = M.current.querySelector(".mp-input-table-list");
                selectElement.addEventListener("click", function() {
                    var radios = selectElement.querySelectorAll('input[type="radio"]');
                    radios.forEach(function(radio) {
                        if (radio.checked) {
                            paymentSelected = radio;
                        }
                    });
                });

                const e = O((async () => {
                    const person_type = M.current.querySelector("#epayco_ticket\\[person_type\\]");
                    const doc_number = M.current.querySelector("#form-checkout__identificationNumber-container > input");
                    const holder_name = M.current.querySelector("#form-checkout__cardholderName").value;
                    const holder_address = M.current.querySelector("#form-checkout__cardholderAddress").value;
                    const holder_email = M.current.querySelector("#form-checkout__cardholderEmail").value;
                    const cellphoneType = M.current.querySelector("#type_cellphone")?.value.split("+")[1];
                    const cellphone = M.current.querySelector("#form-checkout__identificationCellphone-container > input")?.value;
                    const person_type_value = person_type.value;
                    const doc_type = M.current.querySelector("#docType")?.value;
                    const doc_number_value = doc_number?.value;
                    const e = document.getElementById("mp-doc-number-helper"),
                        t = document.getElementById("mp-payment-method-helper"),
                        n = {
                            "epayco_ticket[site_id]": C,
                            "epayco_ticket[amount]": b.toString(),
                            "epayco_ticket[name]": holder_name,
                            "epayco_ticket[address]": holder_address,
                            "epayco_ticket[email]": holder_email,
                            "epayco_ticket[cellphoneType]": cellphoneType,
                            "epayco_ticket[cellphone]": cellphone,
                            "epayco_ticket[person_type]": person_type_value,
                            "epayco_ticket[doc_type]": doc_type,
                            "epayco_ticket[doc_number]": doc_number_value,
                        },

                        a = paymentSelected;
                    const ne = document.getElementById("mp-card-holder-name-helper");
                    "" === holder_name && c(ne, "flex");
                    const ad = document.getElementById("mp-card-holder-address-helper");
                    "" === holder_address && c(ad, "flex");
                    const em = document.getElementById("mp-card-holder-email-helper")
                    "" === holder_email && c(em, "flex");
                    const ee = document.getElementById("mp-doc-cellphone-helper");
                    "" === cellphone && c(ee, "flex");
                    const dn = doc_number.parentElement.parentElement.querySelector("input-helper > div");
                    "" === doc_number_value && c(dn, "flex");
                    function c(e, t) {
                        e && e.style && (e.style.display = t)
                    }

                    function o(e) {
                        return e && "flex" === e.style.display
                    }

                    return a &&
                    "" !== holder_name &&
                    "" !== holder_address &&
                    "" !== holder_email &&
                    "" !== cellphone &&
                    "" !== doc_number

                    && (n["epayco_ticket[payment_method_id]"] = M.current.querySelector(`#${a.id}`).value, t.style.display = "none"),
                    n["epayco_ticket[payment_method_id]"] || c(t, "flex"),
                        {
                            type: o(e) || o(t) ? P.responseTypes.ERROR : P.responseTypes.SUCCESS,
                            meta: {paymentMethodData: n}
                        }
                }));
                return () => e()
            }), [P.responseTypes.ERROR, P.responseTypes.SUCCESS, O]),
                (0, e.createElement)("div", {className: "mp-checkout-container"},
                    (0, e.createElement)("div", {className: "mp-checkout-ticket-container"},
                        (0, e.createElement)("div", {
                                ref: M,
                                className: "mp-checkout-ticket-content"
                            },
                            R ? (0, e.createElement)(l, {
                                title: n,
                                description: c,
                                "link-text": p,
                                "link-src": g
                            }) : null,
                            (0, e.createElement)("div", {className: "mp-checkout-pse-input-cellphone", id: "mp-card-holder-div"},
                                (0, e.createElement)(rr, {
                                    message: Nl,
                                    isOptinal: !1
                                }),
                                (0, e.createElement)("input", {
                                    className: "mp-checkout-pse-card-input mp-card-holder-name ",
                                    placeholder: "Jonh Doe",
                                    id: "form-checkout__cardholderName",
                                    name: "mp-card-holder-name",
                                    "data-checkout": "cardholderName"
                                }),
                                (0, e.createElement)(ss, {
                                    isVisible: !1,
                                    message: Nh,
                                    inputId: "mp-card-holder-name-helper",
                                    dataMain: "mp-card-holder-name"
                                })
                            ),
                            (0, e.createElement)("div", {className: "mp-checkout-pse-input-cellphone", id: "mp-card-holder-div"},
                                (0, e.createElement)(rr, {
                                    message: Em,
                                    isOptinal: !1
                                }),
                                (0, e.createElement)("input", {
                                    className: "mp-checkout-pse-card-input mp-card-holder-name ",
                                    placeholder: "example@email.com",
                                    id: "form-checkout__cardholderEmail",
                                    name: "mp-card-holder-email",
                                    "data-checkout": "cardholderEmail"
                                }),
                                (0, e.createElement)(ss, {
                                    isVisible: !1,
                                    message: Eh,
                                    inputId: "mp-card-holder-email-helper",
                                    dataMain: "mp-card-holder-email"
                                })
                            ),
                            (0, e.createElement)("div", {className: "mp-checkout-pse-input-cellphone", id: "mp-card-holder-div"},
                                (0, e.createElement)(rr, {
                                    message: Al,
                                    isOptinal: !1
                                }),
                                (0, e.createElement)("input", {
                                    className: "mp-checkout-pse-card-input mp-card-holder-name ",
                                    placeholder: "Street 123",
                                    id: "form-checkout__cardholderAddress",
                                    name: "mp-card-holder-address",
                                    "data-checkout": "cardholderAddress"
                                }),
                                (0, e.createElement)(ss, {
                                    isVisible: !1,
                                    message: Ah,
                                    inputId: "mp-card-holder-address-helper",
                                    dataMain: "mp-card-holder-address"
                                })
                            ),
                            (0, e.createElement)("div", {className: "mp-checkout-pse-input-cellphone", id: "mp-pse-holder-div"},
                                (0, e.createElement)(ph, {
                                    labelMessage: kk,
                                    helperMessage: hh,
                                    validate: "true",
                                    selectId: "type_cellphone",
                                    flagError: "epayco_ticket[numberCellphoneError]",
                                    inputName: "epayco_ticket[cellphone]",
                                    selectName: "epayco_ticket[cellphoneType]",
                                    documents: '["+57","+1"]'
                                })
                            ),
                            (0, e.createElement)("div", {className: "mp-checkout-pse-person"},
                                (0, e.createElement)(ps, {
                                    name: "epayco_ticket[person_type]",
                                    label: PT,
                                    optional: !1,
                                    options: '[{"id":"PN", "description": "Persona natural"},{"id":"PJ", "description": "Persona jurídica"}]'
                                })
                            ),
                            B.documents ? (0, e.createElement)(s, {...B}) : null,
                            (0, e.createElement)("p", {className: "mp-checkout-ticket-text"}, E),
                            (0, e.createElement)(i, {
                                name: "epayco_ticket[payment_method_id]",
                                buttonName: S,
                                columns: JSON.stringify(w)
                            }),
                            (0, e.createElement)(r, {
                                isVisible: "false",
                                message: f,
                                inputId: "mp-payment-method-helper",
                                id: "payment-method-helper"
                            }), (0, e.createElement)("div", {id: "mp-box-loading"})
                        )
                    ),
                    (0, e.createElement)(m, {
                        description: v,
                        linkText: N,
                        linkSrc: T,
                        checkoutClass: "ticket"
                    })
                )
        }, y = {
            name: _,
            label: (0, e.createElement)((t => {
                const {PaymentMethodLabel: n} = t.components, a = (0, c.decodeEntities)(k?.params?.fee_title || ""),
                    o = `${g} ${a}`;
                return (0, e.createElement)(n, {text: o})
            }), null),
            content: (0, e.createElement)(h, null),
            edit: (0, e.createElement)(h, null),
            canMakePayment: () => !0,
            ariaLabel: g,
            supports: {features: null !== (p = k?.supports) && void 0 !== p ? p : []}
        };
    (0, t.registerPaymentMethod)(y)
})();