(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        n = window.wc.wcSettings,
        a = window.wp.element,
        c = window.wp.htmlEntities,
        o = "epayco_blocks_update_cart",
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
            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
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
        paymentsTable = ({name: t, buttonName: n, columns: a}) =>
            (0, e.createElement)("input-table", {
                name: t,
                "button-name": n,
                columns: a
            }),
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
            })
        ,
        inputHelper = ({isVisible: t, message: m, inputId: n, id: a}) =>
            (0, e.createElement)("input-helper", {
                isVisible: t,
                message: m,
                "input-id": n,
                id: a
            }),
        termscondictions =
            ({
                 label: l,
                 description: t,
                 linkText: n,
                 linkSrc: a,
                 checkoutClass: c = "pro"
             }) => (0, e.createElement)("div", {className: `mp-checkout-${c}-terms-and-conditions`},
                (0, e.createElement)("terms-and-conditions", {
                    label: l,
                    description: t,
                    "link-text": n,
                    "link-src": a
                })
            );
    var p;
    const u = "mp_checkout_blocks",
        _ = "woo-epayco-daviplata",
        k = (0, n.getSetting)("woo-epayco-daviplata_data", {}),
        g = (0, c.decodeEntities)(k.title) || "Checkout Daviplata",
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
                input_country_label: cl,
                input_country_helper: ch,
                person_type_label: PT,
                input_document_label: h,
                input_document_helper: y,
                ticket_text_label: E,
                input_table_button: S,
                input_helper_label: f,
                payment_methods: w,
                site_id: C,
                terms_and_conditions_label: ll,
                terms_and_conditions_description: v,
                terms_and_conditions_link_text: N,
                terms_and_conditions_link_src: T,
                test_mode: R,
            } = k.params;
            const M = (0, a.useRef)(null),
                {eventRegistration: I, emitResponse: P} = t,
                {onPaymentSetup: O} = I;

            let B = {
                labelMessage: h,
                helperMessage: y,
                inputId:"identificationTypeNumber",
                inputName: "epayco_daviplata[docNumber]",
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
                const daviplataContentName = M.current.querySelector('input-name').querySelector('input');
                const nameHelpers =  M.current.querySelector('input-helper').querySelector("div");
                const verifyName = (nameElement) => {
                    if (nameElement.value === '') {
                        M.current.querySelector('input-name').querySelector(".mp-input").classList.add("mp-error");
                        nameHelpers.style.display = 'flex';
                    }
                }

                const daviplataContentEmail = M.current.querySelector('input-email').querySelector('input');
                const emailHelpers =  M.current.querySelector('input-email').querySelector("input-helper").querySelector("div");
                const verifyEmail = (emailElement) => {
                    if (emailElement.value === '') {
                        M.current.querySelector('input-email').querySelector(".mp-input").classList.add("mp-error");
                        emailHelpers.style.display = 'flex';
                    }
                }

                const daviplataContentAddress = M.current.querySelector('input-address').querySelector('input');
                const addressHelpers =  M.current.querySelector('input-address').querySelector("input-helper").querySelector("div");
                const verifyAddress = (addressElement) => {
                    if (addressElement.value === '') {
                        M.current.querySelector('input-address').querySelector(".mp-input").classList.add("mp-error");
                        addressHelpers.style.display = 'flex';
                    }
                }


                const daviplataContentCellphone = M.current.querySelector('input-cellphone').querySelector('#cellphoneTypeNumber').querySelector('input');
                const cellphoneHelpers =  M.current.querySelector('input-cellphone').querySelector("input-helper").querySelector("div");
                const verifyCellphone = (daviplataContentCellphone) => {
                    if (daviplataContentCellphone.value === '') {
                        M.current.querySelector('input-cellphone').querySelector(".mp-input").classList.add("mp-error");
                        M.current.querySelector('input-cellphone').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                        cellphoneHelpers.style.display = 'flex';
                    }
                }

                const daviplataContentDocument = M.current.querySelector('input-document').querySelector('input');
                const documentHelpers =  M.current.querySelector('input-document').querySelector("input-helper").querySelector("div");
                const verifyDocument = (daviplataContentDocument) => {
                    if (daviplataContentDocument.value === '') {
                        M.current.querySelector('input-document').querySelector(".mp-input").classList.add("mp-error");
                        M.current.querySelector('input-document').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                        documentHelpers.style.display = 'flex';
                    }
                }

                const daviplataContentCountry = M.current.querySelector('#form-checkout__identificationCountry-container').lastChild.querySelector('input');
                const countryHelpers =  M.current.querySelector('input-country').querySelector("input-helper").querySelector("div");
                const verifyCountry = (daviplataContentCountry) => {
                    if (daviplataContentCountry.value === '') {
                        M.current.querySelector('input-country').querySelector(".mp-input").classList.add("mp-error");
                        M.current.querySelector('input-country').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                        countryHelpers.style.display = 'flex';
                    }
                }

                var agree = false;
                const termanAndContictionContent = M.current.parentElement.parentElement.querySelector('terms-and-conditions').querySelector('input');
                const termanAndContictionHelpers =  M.current.parentElement.parentElement.querySelector('terms-and-conditions').querySelector(".mp-terms-and-conditions-container");
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

                const e = O((async () => {

                    const doc_type = daviplataContentDocument.parentElement.parentElement.querySelector("#identificationType").querySelector("select").value;
                    const cellphoneType = daviplataContentCellphone.parentElement.parentElement.querySelector(".mp-input-select-select").value;
                    const countryType = daviplataContentCountry.parentElement.parentElement.querySelector(".mp-input-select-select").value;

                    const person_type_value = M.current.querySelector("#daviplata\\[person_type\\]").value;
                    const doc_number_value = M.current.querySelector("#identificationTypeNumber").querySelector("input").value;
                    const n = {
                        "epayco_daviplata[site_id]": C,
                        "epayco_daviplata[name]": daviplataContentName.value,
                        "epayco_daviplata[address]": daviplataContentAddress.value,
                        "epayco_daviplata[email]": daviplataContentEmail.value,
                        "epayco_daviplata[cellphoneType]": cellphoneType,
                        "epayco_daviplata[cellphone]": daviplataContentCellphone.value,
                        "epayco_daviplata[person_type]": person_type_value,
                        "epayco_daviplata[identificationtype]": doc_type,
                        "epayco_daviplata[doc_number]": doc_number_value,
                        "epayco_daviplata[countryType]": countryType,
                        "epayco_daviplata[country]": daviplataContentCountry.value
                    };
                    "" === daviplataContentName.value && verifyName(daviplataContentName);
                    "" === daviplataContentEmail.value && verifyEmail(daviplataContentEmail);
                    "" === daviplataContentAddress.value && verifyAddress(daviplataContentAddress);
                    "" === daviplataContentCellphone.value && verifyCellphone(daviplataContentCellphone);
                    "Type"||"Tipo" === doc_type && verifyDocument(daviplataContentDocument);
                    "" === daviplataContentDocument.value && verifyDocument(daviplataContentDocument);
                    "" === daviplataContentCountry.value && verifyCountry(daviplataContentCountry);
                    !agree && termanAndContictionHelpers.classList.add("mp-error");

                    function c(e, t) {
                        e && e.style && (e.style.display = t)
                    }

                    function o(e) {
                        return e && "flex" === e.style.display
                    }

                    return "" !== daviplataContentName.value &&
                    "" !== daviplataContentAddress.value &&
                    "" !==  daviplataContentEmail.value &&
                    "" !== daviplataContentCellphone.value &&
                    "" !== daviplataContentDocument.value &&
                    "" !== daviplataContentCountry.value &&
                    "Type"||"Tipo" !== doc_type,
                        {
                            type: o(nameHelpers) || o(emailHelpers) || o(addressHelpers) || o(cellphoneHelpers) || o(documentHelpers)  || o(countryHelpers) || !agree ? P.responseTypes.ERROR : P.responseTypes.SUCCESS,
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
                            R ? (0, e.createElement)(test, {
                                title: n,
                                description: c,
                                "link-text": p,
                                "link-src": g
                            }) : null,
                            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                                (0, e.createElement)(name, {
                                    labelMessage:Nl,
                                    helperMessage:Nh,
                                    placeholder:"jonh doe",
                                    inputName:'daviplata[name]',
                                    flagError:'daviplata[nameError]',
                                    validate:"true",
                                    hiddenId:"hidden-name-ticket"
                                }),
                            ),
                            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                                (0, e.createElement)(email, {
                                    labelMessage:Em,
                                    helperMessage:Eh,
                                    placeholder:"jonh@doe.com",
                                    inputName:'daviplata[email]',
                                    flagError:'daviplata[emailError]',
                                    validate:"true",
                                    hiddenId:"hidden-email-ticket"
                                }),
                            ),
                            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                                (0, e.createElement)(address, {
                                    labelMessage:Al,
                                    helperMessage:Ah,
                                    placeholder:"Street 123",
                                    inputName:'daviplata[address]',
                                    flagError:'daviplata[addressError]',
                                    validate:"true",
                                    hiddenId:"hidden-address-ticket"
                                })
                            ),
                            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                                (0, e.createElement)(cellphone, {
                                    labelMessage: kk,
                                    helperMessage: hh,
                                    inputId:"cellphoneTypeNumber",
                                    inputName: "daviplata[cellphone]",
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
                            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                                (0, e.createElement)(personType, {
                                    name: "daviplata[person_type]",
                                    label: PT,
                                    optional: !1,
                                    options: '[{"id":"PN", "description": "Persona natural"},{"id":"PJ", "description": "Persona jurídica"}]'
                                })
                            ),
                            B.documents ? (0, e.createElement)(document, {...B}) : null,
                            (0, e.createElement)("div", {className: "mp-checkout-ticket-input-document"},
                                (0, e.createElement)(country, {
                                    labelMessage: cl,
                                    helperMessage: ch,
                                    inputId:"countryType",
                                    inputName: "daviplata[country]",
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
                            (0, e.createElement)("div", {id: "mp-box-loading"})
                        )
                    ),
                    (0, e.createElement)(termscondictions, {
                        label: ll,
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