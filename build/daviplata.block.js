(() => {
    "use strict";
    const e = window.React,
        t = window.wp.element,
        c = window.wc.wcBlocksRegistry,
        n = window.wp.htmlEntities,
        o = window.wc.wcSettings,
        s = "epayco_blocks_update_cart",
        a = ({
                 title: t,
                 description: c
             }) =>
            (0, e.createElement)("div", {className: "mp-checkout-pro-test-mode"},
                (0, e.createElement)("test-mode", {
                    title: t,
                    description: c
                })
            ),
        rr = ({
                  isOptinal: c,
                  message: t,
                  forId: s
            }) =>
                (0, e.createElement)("input-label", {
                    isOptinal: c,
                    message: t,
                    for: s
                }),
        q = ({
                  labelMessage: c,
                  helperMessage: n,
                  inputName: a,
                  hiddenId: o,
                  inputDataCheckout: s,
                  selectId: sss,
                  selectName: r,
                  selectDataCheckout: i,
                  flagError: m,
                  documents: l,
                  validate: d
              }) =>
        (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
            (0, e.createElement)("input-document", {
                "label-message": c,
                "helper-message": n,
                "input-name": a,
                "hidden-id": o,
                "input-data-checkout": s,
                "select-id": sss,
                "select-name": r,
                "select-data-checkout": i,
                "flag-error": m,
                documents: l,
                validate: d
            })
        ),
        ss = ({
                  isVisible: c,
                  message: t,
                  inputId: s,
                  id: o,
                  dataMain: n
              }) =>
            (0, e.createElement)("input-helper", {
                isVisible: c,
                message: t,
                "input-id": s,
                id: o,
                "data-main": n
        }),
        ph = ({
                  labelMessage: c,
                  helperMessage: n,
                  inputName: a,
                  hiddenId: o,
                  inputDataCheckout: s,
                  selectId: ph,
                  selectName: r,
                  selectDataCheckout: i,
                  flagError: l,
                  documents: pp,
                  validate: m
              }) =>
            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-cellphone"},
                (0, e.createElement)("input-cellphone", {
                    "label-message": c,
                    "helper-message": n,
                    "input-name": a,
                    "hidden-id": o,
                    "input-data-checkout": s,
                    "select-id": ph,
                    "select-name": r,
                    "select-data-checkout": i,
                    "flag-error": l,
                    documents: pp,
                    validate: m
                })
            ),
        ps = ({
                  name: c,
                  label: o,
                  optional: a,
                  options: n,
                  helperMessage: s,
                  hiddenId: ss,
                  defaultOption: r
              }) =>
            (0, e.createElement)("input-select", {
                name: c,
                label: o,
                options: n,
                optional: a,
                "helper-message": s,
                "hidden-id": ss,
                "default-option": r
            })
    ;
    var d;
    const p = "mp_checkout_blocks",
        _ = "woo-epayco-daviplata",
        u = (0, o.getSetting)("woo-epayco-daviplata_data", {}),
        k = (0, n.decodeEntities)(u.title) || "Daviplata",
        E = c => {
            (e => {
                const {extensionCartUpdate: c} = wc.blocksCheckout, {
                    eventRegistration: n,
                    emitResponse: o
                } = e, {onPaymentSetup: a, onCheckoutSuccess: i, onCheckoutFail: r} = n;
                (0, t.useEffect)((() => {
                    ((e, t) => {
                        e({namespace: s, data: {action: "add", gateway: t}})
                    })(c, _);
                    const e = a((() => ({type: o.responseTypes.SUCCESS})));
                    return () => (((e, t) => {
                        e({namespace: s, data: {action: "remove", gateway: t}})
                    })(c, _), e())
                }), [a]),
                    (0, t.useEffect)((() => {
                        const e = i((async e => {
                            const t = e.processingResponse;
                            return {
                                type: o.responseTypes.SUCCESS,
                                messageContext: o.noticeContexts.PAYMENTS,
                                message: t.paymentDetails.message
                            }
                        }));
                        return () => e()
                    }), [i]),
                    (0, t.useEffect)((() => {
                        const e = r((e => {
                            const t = e.processingResponse;
                            return {
                                type: o.responseTypes.FAIL,
                                messageContext: o.noticeContexts.PAYMENTS,
                                message: t.paymentDetails.message
                            }
                        }));
                        return () => e()
                    }), [r])
            })(c);
            const {
                test_mode_title: n,
                test_mode_description: o,
                test_mode: x,
                input_name_label: Nl,
                input_name_helper: Nh,
                input_email_label: Em,
                input_email_helper: Eh,
                input_address_label: Al,
                input_address_helper: Ah,
                input_ind_phone_label: Ipl,
                input_ind_phone_helper: Iph,
                person_type_label: PT,
                input_document_label: h,
                input_document_helper: y,
                site_id: C,
                amount: b,
                message_error_amount: R
            } = u.params;
            if (null == b) return (0, e.createElement)(e.Fragment, null, (0, e.createElement)("p", {className: "alert-message"}, R));
            const M = (0, t.useRef)(null),
                {eventRegistration: I, emitResponse: P} = c,
                {onPaymentSetup: O} = I;
            let B = {
                labelMessage: h,
                helperMessage: y,
                validate: "true",
                selectId: "docTypeDaviplata",
                flagError: "epayco_daviplata[docNumberError]",
                inputName: "epayco_daviplata[docNumber]",
                selectName: "epayco_daviplata[docType]",
                documents: '["CC","CE","NIT","TI","PPN","SSN","LIC","DNI"]'
            };
            return (0, t.useEffect)((() => {
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
                const e = O((async () => {
                    const person_type = M.current.querySelector("#epayco_daviplata\\[person_type\\]");
                    const doc_number = M.current.querySelector("#form-checkout__identificationNumber-container > input");
                    const holder_name = M.current.querySelector("#form-checkout__cardholderName").value;
                    const holder_address = M.current.querySelector("#form-checkout__cardholderAddress").value;
                    const holder_email = M.current.querySelector("#form-checkout__cardholderEmail").value;
                    const cellphoneType = M.current.querySelector("#type_cellphone")?.value.split("+")[1];
                    const cellphone = M.current.querySelector("#form-checkout__identificationCellphone-container > input")?.value;
                    const person_type_value = person_type.value;
                    const doc_type = M.current.querySelector("#docTypeDaviplata")?.value;
                    const doc_number_value = doc_number?.value;
                    const n = {
                            "epayco_daviplata[site_id]": C,
                            "epayco_daviplata[amount]": b.toString(),
                            "epayco_daviplata[name]": holder_name,
                            "epayco_daviplata[address]": holder_address,
                            "epayco_daviplata[email]": holder_email,
                            "epayco_daviplata[cellphonetype]": cellphoneType,
                            "epayco_daviplata[cellphone]": cellphone,
                            "epayco_daviplata[person_type]": person_type_value,
                            "epayco_daviplata[doc_type]": doc_type,
                            "epayco_daviplata[doc_number]": doc_number_value,
                        };
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

                    return "" !== holder_name &&
                    "" !== holder_address &&
                    "" !== holder_email &&
                    "" !== cellphone &&
                    "" !== doc_number,
                        {
                            type: o(ne) || o(ad) || o(em) || o(ee) || o(dn) ? P.responseTypes.ERROR : P.responseTypes.SUCCESS,
                            meta: {paymentMethodData: n}
                        }
                }));
                return () => e()
            }), [P.responseTypes.ERROR, P.responseTypes.SUCCESS, O]),
            //return null == b ?(0, e.createElement)(e.Fragment, null, (0, e.createElement)("p", {className: "alert-message"}, R)) :
            (0, e.createElement)("div", {className: "mp-checkout-container"},
                (0, e.createElement)("div", {className: "mp-checkout-pro-container"},
                    (0, e.createElement)("div", {
                            ref: M,
                            className: "mp-checkout-pro-content"
                        },
                        x ? (0, e.createElement)(a, {
                            title: n,
                            description: o
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
                                labelMessage: Ipl,
                                helperMessage: Iph,
                                validate: "true",
                                selectId: "type_cellphone",
                                flagError: "epayco_daviplata[numberCellphoneError]",
                                inputName: "epayco_daviplata[cellphone]",
                                selectName: "epayco_daviplata[cellphoneType]",
                                documents: '["+57","+1"]'
                            })
                        ),
                        (0, e.createElement)("div", {className: "mp-checkout-pse-person"},
                            (0, e.createElement)(ps, {
                                name: "epayco_daviplata[person_type]",
                                label: PT,
                                optional: !1,
                                options: '[{"id":"PN", "description": "Persona natural"},{"id":"PJ", "description": "Persona jurídica"}]'
                            })
                        ),
                        B.documents ? (0, e.createElement)(q, {...B}) : null,
                    )
                ),
            )
        },
        g = {
            name: _,
            label: (0, e.createElement)((t => {
                const {PaymentMethodLabel: c} = t.components, o = (0, n.decodeEntities)(u?.params?.fee_title || ""),
                    s = `${k} ${o}`;
                return (0, e.createElement)(c, {text: s})
            }), null),
            content: (0, e.createElement)(E, null),
            edit: (0, e.createElement)(E, null),
            canMakePayment: () => !0,
            ariaLabel: k,
            supports: {features: null !== (d = u?.supports) && void 0 !== d ? d : []}
        };
    (0, c.registerPaymentMethod)(g)
})();