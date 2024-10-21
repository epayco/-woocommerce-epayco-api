(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        n = window.wc.wcSettings,
        a = window.wp.element,
        o = window.wp.htmlEntities,
        c = "epayco_blocks_update_cart",
        s = ({
              labelMessage: t,
              helperMessage: n,
              inputName: a,
              hiddenId: o,
              inputDataCheckout: c,
              selectId: s,
              selectName: r,
              selectDataCheckout: i,
              flagError: l,
              documents: p,
              validate: m
          }) =>
            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                (0, e.createElement)("input-document", {
                    "label-message": t,
                    "helper-message": n,
                    "input-name": a,
                    "hidden-id": o,
                    "input-data-checkout": c,
                    "select-id": s,
                    "select-name": r,
                    "select-data-checkout": i,
                    "flag-error": l,
                    documents: p,
                    validate: m
                })
            ),
        ph = ({
                 labelMessage: t,
                 helperMessage: n,
                 inputName: a,
                 hiddenId: o,
                 inputDataCheckout: c,
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
                    "hidden-id": o,
                    "input-data-checkout": c,
                    "select-id": ph,
                    "select-name": r,
                    "select-data-checkout": i,
                    "flag-error": l,
                    documents: pp,
                    validate: m
                })
            ),
        r = ({
              description: t,
              linkText: n,
              linkSrc: a,
              checkoutClass: o = "pro"
          }) =>
            (0, e.createElement)("div", {className: `mp-checkout-${o}-terms-and-conditions`},
                (0, e.createElement)("terms-and-conditions", {
                    description: t,
                    "link-text": n,
                    "link-src": a
                })
            ),
        i = ({
              title: t,
              description: n,
              linkText: a,
              linkSrc: o
          }) =>
            (0, e.createElement)("div", {className: "mp-checkout-pro-test-mode"},
                (0, e.createElement)("test-mode", {
                    title: t,
                    description: n,
                    "link-text": a,
                    "link-src": o
                })
            ),
        p = ({
                name: t,
                label: n,
                optional: a,
                options: o,
                helperMessage: c,
                hiddenId: s,
                defaultOption: r
            }) =>
            (0, e.createElement)("input-select", {
                name: t,
                label: n,
                options: o,
                optional: a,
                "helper-message": c,
                "hidden-id": s,
                "default-option": r
            }),
        pp = ({
             name: t,
             label: n,
             optional: a,
             options: o,
             helperMessage: c,
             hiddenId: ph,
             defaultOption: r
         }) =>
        (0, e.createElement)("input-select", {
            name: t,
            label: n,
            options: o,
            optional: a,
            "helper-message": c,
            "hidden-id": ph,
            "default-option": r
        }),
        rr = ({isOptinal: t, message: a, forId: c}) =>
            (0, e.createElement)("input-label", {
                isOptinal: t,
                message: a,
                for: c
            }),
        ss = ({
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
        });
    var m;
    const d = "mp_checkout_blocks",
        u = "woo-epayco-pse",
        _ = (0, n.getSetting)("woo-epayco-pse_data", {}),
        g = (0, o.decodeEntities)(_.title) || "Checkout Pse", k = t => {
            (e => {
                const {extensionCartUpdate: t} = wc.blocksCheckout, {
                    eventRegistration: n,
                    emitResponse: o
                } = e, {onPaymentSetup: s, onCheckoutSuccess: r, onCheckoutFail: i} = n;
                (0, a.useEffect)((() => {
                    ((e, t) => {
                        e({namespace: c, data: {action: "add", gateway: t}})
                    })(t, u);
                    const e = s((() => ({type: o.responseTypes.SUCCESS})));
                    return () => (((e, t) => {
                        e({namespace: c, data: {action: "remove", gateway: t}})
                    })(t, u), e())
                }), [s]), (0, a.useEffect)((() => {
                    const e = r((async e => {
                        const t = e.processingResponse;
                        if(t.paymentStatus === 'success'){
                            location.href=t.paymentDetails.redirect;
                        }else{
                            return {
                                type: o.responseTypes.FAIL,
                                messageContext: o.noticeContexts.PAYMENTS,
                                message: t.paymentDetails.message
                            }
                        }
                    }));
                    return () => e()
                }), [r]), (0, a.useEffect)((() => {
                    const e = i((e => {
                        const t = e.processingResponse;
                        if(t.paymentStatus === 'success'){
                            location.href=t.paymentDetails.redirect;
                        }else{
                            return {
                                type: o.responseTypes.FAIL,
                                messageContext: o.noticeContexts.PAYMENTS,
                                message: t.paymentDetails.message
                            }
                        }
                    }));
                    return () => e()
                }), [i])
            })(t);
            const {
                test_mode_title: n,
                test_mode_description: o,
                test_mode_link_text: m,
                test_mode_link_src: g,
                input_document_label: k,
                input_document_helper: h,
                input_ind_phone_label: kk,
                input_ind_phone_helper: hh,
                input_name_label: Nl,
                input_name_helper: Nh,
                input_email_label: Em,
                input_email_helper: Eh,
                input_address_label: Am,
                input_address_helper: Ah,
                person_type_label: E,
                amount: S,
                site_id: f,
                terms_and_conditions_description: v,
                terms_and_conditions_link_text: b,
                terms_and_conditions_link_src: w,
                test_mode: C,
                financial_institutions: N,
                financial_institutions_label: x,
                financial_institutions_helper: R,
                financial_placeholder: T,
                message_error_amount: M
            } = _.params;
            if (null == S) return (0, e.createElement)(e.Fragment, null, (0, e.createElement)("p", {className: "alert-message"}, M));
            const P = (0, a.useRef)(null), {eventRegistration: q, emitResponse: O} = t, {onPaymentSetup: I} = q;
            let U = {
                labelMessage: k,
                helperMessage: h,
                validate: "true",
                selectId: "doc_type",
                flagError: "epayco_pse[docNumberError]",
                inputName: "epayco_pse[docNumber]",
                selectName: "epayco_pse[docType]",
                documents: '["CC","CE","NIT","TI","PPN","SSN","LIC","DNI"]'
            };
            return (0, a.useEffect)((() => {
                const holderName =  document.getElementById('form-checkout__cardholderName');
                const holderNameHelper = document.getElementById('mp-card-holder-name-helper');
                holderName.addEventListener('input', function (e) {
                    debugger
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
                var selectElement = document.getElementById("epayco_pse[bank]");
                selectElement.addEventListener("input", function() {
                    let a = document.querySelector(".mp-checkout-pse-bank").querySelector("input-helper > div");
                    var selectedValue = selectElement.value;
                    if(selectedValue !== '0'){
                        a.style.display = "none";
                        a.lastChild.innerText=''
                    }else{
                        a.style.display = "flex";
                        a.lastChild.innerText='invalid bank'
                    }
                    console.log("Valor seleccionado:", selectedValue);
                });

                const e = I((async () => {
                    const doc_type = P.current.querySelector("#doc_type")?.value;
                    const cellphoneType = P.current.querySelector("#type_cellphone")?.value.split("+")[1];
                    const doc_number = P.current.querySelector("#form-checkout__identificationNumber-container > input")?.value;
                    const cellphone = P.current.querySelector("#form-checkout__identificationCellphone-container > input")?.value;
                    const bank = P.current.querySelector("#epayco_pse\\[bank\\]").value;
                    const person_type = P.current.querySelector("#epayco_pse\\[person_type\\]").value;
                    const holder_name = P.current.querySelector("#form-checkout__cardholderName").value;
                    const holder_email = P.current.querySelector("#form-checkout__cardholderEmail").value;
                    const holder_address = P.current.querySelector("#form-checkout__cardholderAddress").value;
                    const e = document.querySelector(".mp-checkout-pse-input-document").querySelector(".mp-input-document > input-helper > div"),
                        t = {
                            "epayco_pse[site_id]": f,
                            "epayco_pse[amount]": S.toString(),
                            "epayco_pse[doc_type]": doc_type,
                            "epayco_pse[cellphoneType]": cellphoneType,
                            "epayco_pse[doc_number]": doc_number,
                            "epayco_pse[cellphone]": cellphone,
                            "epayco_pse[bank]": bank,
                            "epayco_pse[person_type]": person_type,
                            "epayco_pse[name]": holder_name,
                            "epayco_pse[email]": holder_email,
                            "epayco_pse[address]": holder_address
                        };
                    U.documents && "" === t["epayco_pse[doc_number]"] && o(e, "flex");
                    const ee = document.getElementById("mp-doc-cellphone-helper");
                        "" === t["epayco_pse[cellphone]"] && o(ee, "flex");
                    const ne = document.getElementById("mp-card-holder-name-helper");
                    "" === t["epayco_pse[name]"] && o(ne, "flex");
                    const em = document.getElementById("mp-card-holder-email-helper")
                    "" === t["epayco_pse[email]"] && o(em, "flex");
                    const ad = document.getElementById("mp-card-holder-address-helper");
                    "" === t["epayco_pse[address]"] && o(ad, "flex");

                    let n = document.querySelector("#epayco_pse\\[bank\\]"),
                        a = document.querySelector(".mp-checkout-pse-bank").querySelector("input-helper > div");
                    if("0" === bank){
                        o(a, "flex")
                        a.lastChild.innerText='invalid bank'
                    }else{
                        o(a, "none")
                        a.lastChild.innerText=''
                    }

                    function o(e, t) {
                        e && e.style && (e.style.display = t)
                    }

                    function c(e) {
                        return e && "flex" === e.style.display
                    }

                    return ("0" !== n.value  &&
                        "" !== doc_type &&
                        "" !== cellphoneType &&
                        "" !== doc_number &&
                        "" !== cellphone &&
                        "0" !== bank &&
                        "" !== person_type &&
                        "" !== holder_name &&
                        "" !== holder_email &&
                        "" !== holder_address), {
                        type: c(a) || c(e) || c(ee) || c(ne) || c(em) || c(ad) ? O.responseTypes.ERROR : O.responseTypes.SUCCESS,
                        meta: {paymentMethodData: t}
                    }
                }));
                return () => e()
            }), [O.responseTypes.ERROR, O.responseTypes.SUCCESS, I]),
                (0, e.createElement)("div", {className: "mp-checkout-container"},
                    (0, e.createElement)("div", {className: "mp-checkout-pse-container"},
                        (0, e.createElement)("div", {
                            ref: P,
                            className: "mp-checkout-pse-content"
                            }, C ? (0, e.createElement)(i, {
                                title: n,
                                description: o,
                                "link-text": m,
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
                                        message: Am,
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
                                        flagError: "epayco_pse[numberCellphoneError]",
                                        inputName: "epayco_pse[cellphone]",
                                        selectName: "epayco_pse[cellphoneType]",
                                        documents: '["+57","+1"]'
                                    })
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-person"},
                                    (0, e.createElement)(p, {
                                        name: "epayco_pse[person_type]",
                                        label: E,
                                        optional: !1,
                                        options: '[{"id":"PN", "description": "Persona natural"},{"id":"PJ", "description": "Persona jurídica"}]'
                                    })
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-input-document"}, U.documents ? (0, e.createElement)(s, {...U}) : null),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-bank"},
                                    (0, e.createElement)(p, {
                                        name: "epayco_pse[bank]",
                                        label: x,
                                        optional: !1,
                                        options: N,
                                        "hidden-id": "hidden-financial-pse",
                                        "helper-message": R,
                                        "default-option": T
                                    }),
                                ),
                                (0, e.createElement)("div", {id: "mp-box-loading"})
                        )
                    ),
                    (0, e.createElement)(r, {
                        description: v,
                        linkText: b,
                        linkSrc: w,
                        checkoutClass: "pse"
                    })
                )
        }, h = {
            name: u,
            label: (0, e.createElement)((t => {
                const {PaymentMethodLabel: n} = t.components, a = (0, o.decodeEntities)(_?.params?.fee_title || ""),
                    c = `${g} ${a}`;
                return (0, e.createElement)(n, {text: c})
            }), null),
            content: (0, e.createElement)(k, null),
            edit: (0, e.createElement)(k, null),
            canMakePayment: () => !0,
            ariaLabel: g,
            supports: {features: null !== (m = _?.supports) && void 0 !== m ? m : []}
        };
    (0, t.registerPaymentMethod)(h)
})();