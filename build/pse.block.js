(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        n = window.wc.wcSettings,
        a = window.wp.element,
        o = window.wp.htmlEntities,
        c = "epayco_blocks_update_cart",
        test = ({
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
        name = ({
                    labelMessage:Al,
                    helperMessage:Ah,
                    placeholder:s,
                    inputName:i,
                    flagError:f,
                    validate:v,
                    hiddenId:h
                }) => (0, e.createElement)("input-name", {
            labelMessage:Al,
            helperMessage:Ah,
            placeholder:s,
            inputName:i,
            flagError:f,
            validate:v,
            hiddenId:h
        }),
        email = ({
                     labelMessage:Al,
                     helperMessage:Ah,
                     placeholder:s,
                     inputName:i,
                     flagError:f,
                     validate:v,
                     hiddenId:h
                 }) => (0, e.createElement)("input-email", {
            labelMessage:Al,
            helperMessage:Ah,
            placeholder:s,
            inputName:i,
            flagError:f,
            validate:v,
            hiddenId:h
        }),
        address = ({
                       labelMessage:Al,
                       helperMessage:Ah,
                       placeholder:s,
                       inputName:i,
                       flagError:f,
                       validate:v,
                       hiddenId:h
                   }) => (0, e.createElement)("input-address", {
            labelMessage:Al,
            helperMessage:Ah,
            placeholder:s,
            inputName:i,
            flagError:f,
            validate:v,
            hiddenId:h
        }),
        cellphone = ({
                         labelMessage: t,
                         helperMessage: n,
                         inputId: ii,
                         inputName: a,
                         hiddenId: c,
                         inputDataCheckout: o,
                         selectId: s,
                         selectName: r,
                         selectDataCheckout: i,
                         flagError: m,
                         documents: l,
                         validate: d,
                         placeholder: p
                     }) =>
            (0, e.createElement)("input-cellphone", {
                "label-message": t,
                "helper-message": n,
                "input-id": ii,
                "input-name": a,
                "hidden-id": c,
                "input-data-checkout": o,
                "select-id": s,
                "select-name": r,
                "select-data-checkout": i,
                "flag-error": m,
                documents: l,
                validate: d,
                placeholder: p
            })
        ,
        personType = ({
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
            }),
        document = ({
                        labelMessage: t,
                        helperMessage: n,
                        inputId: ii,
                        inputName: a,
                        hiddenId: c,
                        inputDataCheckout: o,
                        selectId: s,
                        selectName: r,
                        selectDataCheckout: i,
                        flagError: m,
                        documents: l,
                        validate: d,
                        placeholder: p
                    }) =>
            (0, e.createElement)("div", {className: "mp-checkout-pse-input-document"},
                (0, e.createElement)("input-document", {
                    "label-message": t,
                    "helper-message": n,
                    "input-id": ii,
                    "input-name": a,
                    "hidden-id": c,
                    "input-data-checkout": o,
                    "select-id": s,
                    "select-name": r,
                    "select-data-checkout": i,
                    "flag-error": m,
                    documents: l,
                    validate: d,
                    placeholder: p
                })
            ),
        country = ({
               labelMessage: t,
               helperMessage: n,
               inputId: ii,
               inputName: a,
               hiddenId: c,
               inputDataCheckout: o,
               selectId: s,
               selectName: r,
               selectDataCheckout: i,
               flagError: m,
               documents: l,
               validate: d,
               placeholder: p
               }) =>
            (0, e.createElement)("input-country", {
                "label-message": t,
                "helper-message": n,
                "input-id": ii,
                "input-name": a,
                "hidden-id": c,
                "input-data-checkout": o,
                "select-id": s,
                "select-name": r,
                "select-data-checkout": i,
                "flag-error": m,
                documents: l,
                validate: d,
                placeholder: p
            }),
        bankList = ({
                name: t,
                label: n,
                optional: a,
                options: o,
                helperMessage: c,
                hiddenId: s,
                defaultOption: r
            }) =>
            (0, e.createElement)("input-banks", {
                name: t,
                label: n,
                options: o,
                optional: a,
                "helper-message": c,
                "hidden-id": s,
                "default-option": r
            }),
        termscondictions =
            ({
                 label: l,
                 description: t,
                 linkText: n,
                 linkSrc: a,
                 checkoutClass: c = "pro"
             }) => (0, e.createElement)("div", {className: `mp-checkout-ticket-terms-and-conditions`},
                (0, e.createElement)("terms-and-conditions", {
                    label: l,
                    description: t,
                    "link-text": n,
                    "link-src": a
                })
            )
    ;
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
                input_country_label: cl,
                input_country_helper: ch,
                person_type_label: E,
                site_id: f,
                terms_and_conditions_label: ll,
                terms_and_conditions_description: v,
                terms_and_conditions_link_text: b,
                terms_and_conditions_link_src: w,
                test_mode: C,
                financial_institutions: N,
                financial_institutions_label: x,
                financial_institutions_helper: R,
                financial_placeholder: T,
            } = _.params;
            const P = (0, a.useRef)(null), {eventRegistration: q, emitResponse: O} = t, {onPaymentSetup: I} = q;
            let U = {
                labelMessage: k,
                helperMessage: h,
                inputId:"identificationTypeNumber",
                inputName: "epayco_pse[docNumber]",
                hiddenId: "identificationType",
                inputDataCheckout: "doc_number",
                selectId: "identificationType",
                selectName: "identificationType",
                selectDataCheckout: "doc_type",
                flagError: "docNumberError",
                documents: '[{"id":"Type"},{"id":"CC"},{"id":"CE"},{"id":"NIT"},{"id":"TI"},{"id":"PPN"},{"id":"SSN"},{"id":"LIC"},{"id":"DNI"}]',
                validate: "true",
                placeholder: "0000000000"
            };
            return (0, a.useEffect)((() => {
                const ticketContentName = P.current.querySelector('input-name').querySelector('input');
                const nameHelpers =  P.current.querySelector('input-helper').querySelector("div");
                const verifyName = (nameElement) => {
                    if (nameElement.value === '') {
                        P.current.querySelector('input-name').querySelector(".mp-input").classList.add("mp-error");
                        nameHelpers.style.display = 'flex';
                    }
                }

                const ticketContentEmail = P.current.querySelector('input-email').querySelector('input');
                const emailHelpers =  P.current.querySelector('input-email').querySelector("input-helper").querySelector("div");
                const verifyEmail = (emailElement) => {
                    if (emailElement.value === '') {
                        P.current.querySelector('input-email').querySelector(".mp-input").classList.add("mp-error");
                        emailHelpers.style.display = 'flex';
                    }
                }

                const ticketContentAddress = P.current.querySelector('input-address').querySelector('input');
                const addressHelpers =  P.current.querySelector('input-address').querySelector("input-helper").querySelector("div");
                const verifyAddress = (addressElement) => {
                    if (addressElement.value === '') {
                        P.current.querySelector('input-address').querySelector(".mp-input").classList.add("mp-error");
                        addressHelpers.style.display = 'flex';
                    }
                }


                const ticketContentCellphone = P.current.querySelector('input-cellphone').querySelector('#cellphoneTypeNumber').querySelector('input');
                const cellphoneHelpers =  P.current.querySelector('input-cellphone').querySelector("input-helper").querySelector("div");
                const verifyCellphone = (ticketContentCellphone) => {
                    if (ticketContentCellphone.value === '') {
                        P.current.querySelector('input-cellphone').querySelector(".mp-input").classList.add("mp-error");
                        P.current.querySelector('input-cellphone').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                        cellphoneHelpers.style.display = 'flex';
                    }
                }

                const pseContentDocument = P.current.querySelector('input-document').querySelector('input');
                const documentHelpers =  P.current.querySelector('input-document').querySelector("input-helper").querySelector("div");
                const verifyDocument = (pseContentDocument) => {
                    if (pseContentDocument.value === '') {
                        P.current.querySelector('input-document').querySelector(".mp-input").classList.add("mp-error");
                        P.current.querySelector('input-document').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                        documentHelpers.style.display = 'flex';
                    }
                }

                const ticketContentCountry = P.current.querySelector('#form-checkout__identificationCountry-container').lastChild.querySelector('input');
                const countryHelpers =  P.current.querySelector('input-country').querySelector("input-helper").querySelector("div");
                const verifyCountry = (ticketContentCountry) => {
                    if (ticketContentCountry.value === '') {
                        P.current.querySelector('input-country').querySelector(".mp-input").classList.add("mp-error");
                        P.current.querySelector('input-country').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                        countryHelpers.style.display = 'flex';
                    }
                }

                var agree = false;
                const termanAndContictionContent = P.current.parentElement.parentElement.querySelector('terms-and-conditions').querySelector('input');
                const termanAndContictionHelpers =  P.current.parentElement.parentElement.querySelector('terms-and-conditions').querySelector(".mp-terms-and-conditions-container");
                termanAndContictionContent.addEventListener('click', function() {
                    const checkbox = termanAndContictionContent;
                    if (checkbox.checked) {
                        termanAndContictionHelpers.classList.remove("mp-error")
                        agree = true;
                    } else {
                        termanAndContictionHelpers.classList.add("mp-error")
                        agree = false;
                    }
                });


                const e = I((async () => {

                    const doc_type = pseContentDocument.parentElement.parentElement.querySelector("#identificationType").querySelector("select").value;
                    const cellphoneType = ticketContentCellphone.parentElement.parentElement.querySelector(".mp-input-select-select").value;
                    const countryType = ticketContentCountry.parentElement.parentElement.querySelector(".mp-input-select-select").value;
                    const person_type_value = P.current.querySelector("#epayco_pse\\[person_type\\]").value;
                    const doc_number_value = P.current.querySelector("#identificationTypeNumber").querySelector("input").value;
                    const bank = P.current.querySelector("#epayco_pse\\[bank\\]").value;

                    const t = {
                            "epayco_pse[site_id]": f,
                            "epayco_pse[name]": ticketContentName.value,
                            "epayco_pse[address]": ticketContentAddress.value,
                            "epayco_pse[email]": ticketContentEmail.value,
                            "epayco_pse[cellphoneType]": cellphoneType,
                            "epayco_pse[cellphone]": ticketContentCellphone.value,
                            "epayco_pse[person_type]": person_type_value,
                            "epayco_pse[identificationtype]": doc_type,
                            "epayco_pse[doc_number]": doc_number_value,
                            "epayco_pse[countryType]": countryType,
                            "epayco_pse[country]": ticketContentCountry.value,
                            "epayco_pse[bank]": bank
                        };
                    "" === ticketContentName.value && verifyName(ticketContentName);
                    "" === ticketContentEmail.value && verifyEmail(ticketContentEmail);
                    "" === ticketContentAddress.value && verifyAddress(ticketContentAddress);
                    "" === ticketContentCellphone.value && verifyCellphone(ticketContentCellphone);
                    "Type"||"Tipo" === doc_type && verifyDocument(pseContentDocument);
                    "" === pseContentDocument.value && verifyDocument(pseContentDocument);
                    "" === ticketContentCountry.value && verifyCountry(ticketContentCountry);
                    !agree && termanAndContictionHelpers.classList.add("mp-error");

                    const n = P.current.querySelector("#epayco_pse\\[bank\\]");
                    const bankHelper =  P.current.querySelector('.mp-input-select-bank').parentElement.parentElement.querySelector("input-helper").querySelector("div");
                    if("0" === bank){
                        P.current.querySelector('.mp-input-select-bank').parentElement.classList.add("mp-error")
                        c(bankHelper, "flex")
                        bankHelper.lastChild.innerText='invalid bank'
                    }else{
                        P.current.querySelector('.mp-input-select-bank').parentElement.classList.remove("mp-error")
                        c(bankHelper, "none")
                        bankHelper.lastChild.innerText=''
                    }

                    function c(e, t) {
                        e && e.style && (e.style.display = t)
                    }

                    function o(e) {
                        return e && "flex" === e.style.display
                    }

                    return "0" !== n.value  &&
                    "" !== ticketContentName.value &&
                    "" !== ticketContentAddress.value &&
                    "" !==  ticketContentEmail.value &&
                    "" !== ticketContentCellphone.value &&
                    "" !== pseContentDocument.value &&
                    "" !== ticketContentCountry.value &&
                    "Type"||"Tipo" !== doc_type,
                        {
                            type: o(bankHelper) || o(nameHelpers) || o(emailHelpers) || o(addressHelpers) || o(cellphoneHelpers) || o(documentHelpers)  || o(countryHelpers) || !agree ? O.responseTypes.ERROR : O.responseTypes.SUCCESS,
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
                            }, C ? (0, e.createElement)(test, {
                                title: n,
                                description: o,
                                "link-text": m,
                                "link-src": g
                            }) : null,
                                (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                                    (0, e.createElement)(name, {
                                        labelMessage:Nl,
                                        helperMessage:Nh,
                                        placeholder:"jonh doe",
                                        inputName:'epayco_pse[name]',
                                        flagError:'epayco_pse[nameError]',
                                        validate:"true",
                                        hiddenId:"hidden-name-ticket"
                                    }),
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-input-document"},
                                    (0, e.createElement)(email, {
                                        labelMessage:Em,
                                        helperMessage:Eh,
                                        placeholder:"jonh@doe.com",
                                        inputName:'epayco_pse[email]',
                                        flagError:'epayco_pse[emailError]',
                                        validate:"true",
                                        hiddenId:"hidden-email-ticket"
                                    }),
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-input-document"},
                                    (0, e.createElement)(address, {
                                        labelMessage:Am,
                                        helperMessage:Ah,
                                        placeholder:"Street 123",
                                        inputName:'epayco_pse[address]',
                                        flagError:'epayco_pse[addressError]',
                                        validate:"true",
                                        hiddenId:"hidden-address-ticket"
                                    })
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-input-document", id: "mp-pse-holder-div"},
                                    (0, e.createElement)(cellphone, {
                                        labelMessage: kk,
                                        helperMessage: hh,
                                        inputId:"cellphoneTypeNumber",
                                        inputName: "epayco_pse[cellphone]",
                                        hiddenId: "cellphoneType",
                                        inputDataCheckout: "doc_number",
                                        selectId: "cellphoneType",
                                        selectName: "cellphoneType",
                                        selectDataCheckout: "doc_type",
                                        flagError: "cellphoneTypeError",
                                        validate: "true",
                                        placeholder: "0000000000"
                                    })
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-input-document"},
                                    (0, e.createElement)(personType, {
                                        name: "epayco_pse[person_type]",
                                        label: E,
                                        optional: !1,
                                        options: '[{"id":"PN", "description": "Persona natural"},{"id":"PJ", "description": "Persona jurídica"}]'
                                    })
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-input-document"}, U.documents ? (0, e.createElement)(document, {...U}) : null),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-input-document"},
                                    (0, e.createElement)(country, {
                                        labelMessage: cl,
                                        helperMessage: ch,
                                        inputId:"countryType",
                                        inputName: "epayco_pse[country]",
                                        hiddenId: "countryType",
                                        inputDataCheckout: "country_number",
                                        selectId: "countryType",
                                        selectName: "countryType",
                                        selectDataCheckout: "country_type",
                                        flagError: "countryTypeError",
                                        validate: "true",
                                        placeholder: "city"
                                    })
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-pse-bank"},
                                    (0, e.createElement)(bankList, {
                                        name: "epayco_pse[bank]",
                                        label: x,
                                        optional: !1,
                                        options: N,
                                        "hidden-id": "hidden-financial-pse",
                                        "helper-message": R,
                                        "default-option": ""
                                    }),
                                ),

                        ),
                        (0, e.createElement)("div", {id: "mp-box-loading"}),
                        (0, e.createElement)(termscondictions, {
                            label: ll,
                            description: v,
                            linkText: b,
                            linkSrc: w,
                            checkoutClass: "pse"
                        })
                    ),

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