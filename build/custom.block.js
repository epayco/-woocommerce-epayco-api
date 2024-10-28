(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        a = window.wc.wcSettings,
        c = window.wp.element,
        n = window.wp.htmlEntities,
        o = "epayco_blocks_update_cart";
    const  test = ({
                 title: t,
                 description: a,
                 linkText: c,
                 linkSrc: n
             }) => (0, e.createElement)("div", {className: "mp-checkout-pro-test-mode"},
            (0, e.createElement)("test-mode", {
                title: t,
                description: a,
                "link-text": c,
                "link-src": n
        })),
        svgLogo = ({
                    width: t,
                    height: a,
                    viewBox: c,
                    d: n,
                    m:m
                }) =>
                    (0, e.createElement)("svg-logo", {
                        width: t,
                        height: a,
                        viewBox: c,
                        d: n,
                        m:m
                    }),
        name = ({
                    labelMessage:Al,
                    helperMessage:Ah,
                    placeholder:s,
                    inputName:i,
                    flagError:f,
                    validate:v,
                    hiddenId:h
                }) => (0, e.createElement)("input-card-name", {
            labelMessage:Al,
            helperMessage:Ah,
            placeholder:s,
            inputName:i,
            flagError:f,
            validate:v,
            hiddenId:h
        }),
        cardNumber = ({
                    labelMessage:Al,
                    helperMessage:Ah,
                    placeholder:s,
                    inputName:i,
                    flagError:f,
                    validate:v,
                    hiddenId:h
                }) => (0, e.createElement)("input-card-number", {
            labelMessage:Al,
            helperMessage:Ah,
            placeholder:s,
            inputName:i,
            flagError:f,
            validate:v,
            hiddenId:h
        }),
        expirationDate = ({
                          labelMessage:Al,
                          helperMessage:Ah,
                          placeholder:s,
                          inputName:i,
                          flagError:f,
                          validate:v,
                          hiddenId:h
                      }) => (0, e.createElement)("input-card-expiration-date", {
            labelMessage:Al,
            helperMessage:Ah,
            placeholder:s,
            inputName:i,
            flagError:f,
            validate:v,
            hiddenId:h
        }),
        securityCode = ({
              labelMessage:Al,
              helperMessage:Ah,
              placeholder:s,
              inputName:i,
              flagError:f,
              validate:v,
              hiddenId:h
          }) => (0, e.createElement)("input-card-security-code", {
            labelMessage:Al,
            helperMessage:Ah,
            placeholder:s,
            inputName:i,
            flagError:f,
            validate:v,
            hiddenId:h
        }),
        installments =({
               name:Al,
               label:Ah,
               optional:s,
               options:i
           }) => (0, e.createElement)("input-installment", {
            name:Al,
            label:Ah,
            optional:s,
            options:i
        }),
        documents = ({
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
                 validate: d,
                 placeholder: p
             }) => (0, e.createElement)("input-document", {
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
                validate: d,
                placeholder: p
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
        email = ({
                    labelMessage:Al,
                    helperMessage:Ah,
                    placeholder:s,
                    inputName:i,
                    flagError:f,
                    validate:v,
                    hiddenId:h
                }) => (0, e.createElement)("input-card-email", {
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
                input_country_label: cl,
                input_country_helper: ch,
                terms_and_conditions_label: ll,
                terms_and_conditions_description: v,
                terms_and_conditions_link_text: N,
                terms_and_conditions_link_src: T,
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
                (0, c.useEffect)((() => {
                    const current =  document.querySelector(".mp-checkout-custom-container");
                    const customContentName = current.querySelector('input-card-name').querySelector('input');
                    const nameHelpers =  current.querySelector('input-helper').querySelector("div");
                    const verifyName = (nameElement) => {
                        if (nameElement.value === '') {
                            current.querySelector('input-card-name').querySelector(".mp-input").classList.add("mp-error");
                            nameHelpers.style.display = 'flex';
                        }
                    }
                    const cardNumberContentName = current.querySelector('input-card-number').querySelector('input');
                    const cardNumberHelpers =  current.querySelector('input-card-number').querySelector("input-helper").querySelector("div");
                    const verifyCardNumber = (nameElement) => {
                        if (nameElement.value === '') {
                            current.querySelector('input-card-number').querySelector(".mp-input").classList.add("mp-error");
                            cardNumberHelpers.style.display = 'flex';
                        }
                    }
                    const cardExpirationContentName = current.querySelector('input-card-expiration-date').querySelector('input');
                    const cardExpirationHelpers =  current.querySelector('input-card-expiration-date').querySelector("input-helper").querySelector("div");
                    const verifyCardExpiration = (nameElement) => {
                        if (nameElement.value === '') {
                            current.querySelector('input-card-expiration-date').querySelector(".mp-input").classList.add("mp-error");
                            cardExpirationHelpers.style.display = 'flex';
                        }
                    }
                    const cardSecurityContentName = current.querySelector('input-card-security-code').querySelector('input');
                    const cardSecurityHelpers =  current.querySelector('input-card-security-code').querySelector("input-helper").querySelector("div");
                    const verifyCardSecurity = (nameElement) => {
                        if (nameElement.value === '') {
                            current.querySelector('input-card-security-code').querySelector(".mp-input").classList.add("mp-error");
                            cardSecurityHelpers.style.display = 'flex';
                        }
                    }

                    const cardContentDocument = current.querySelector('input-document').querySelector('input');
                    const documentHelpers =  current.querySelector('input-document').querySelector("input-helper").querySelector("div");
                    const verifyDocument = (cardContentDocument) => {
                        if (cardContentDocument.value === '') {
                            current.querySelector('input-document').querySelector(".mp-input").classList.add("mp-error");
                            current.querySelector('input-document').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                            documentHelpers.style.display = 'flex';
                        }
                    }

                    const customContentAddress = current.querySelector('input-address').querySelector('input');
                    const addressHelpers =  current.querySelector('input-address').querySelector("input-helper").querySelector("div");
                    const verifyAddress = (addressElement) => {
                        if (addressElement.value === '') {
                            current.querySelector('input-address').querySelector(".mp-input").classList.add("mp-error");
                            addressHelpers.style.display = 'flex';
                        }
                    }

                    const customContentEmail = current.querySelector('input-card-email').querySelector('input');
                    const emailHelpers =  current.querySelector('input-card-email').querySelector("input-helper").querySelector("div");
                    const verifyEmail = (emailElement) => {
                        if (emailElement.value === '') {
                            current.querySelector('input-card-email').querySelector(".mp-input").classList.add("mp-error");
                            emailHelpers.style.display = 'flex';
                        }
                    }

                    const customContentCellphone = current.querySelector('input-cellphone').querySelector('#cellphoneTypeNumber').querySelector('input');
                    const cellphoneHelpers =  current.querySelector('input-cellphone').querySelector("input-helper").querySelector("div");
                    const verifyCellphone = (customContentCellphone) => {
                        if (customContentCellphone.value === '') {
                            current.querySelector('input-cellphone').querySelector(".mp-input").classList.add("mp-error");
                            current.querySelector('input-cellphone').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                            cellphoneHelpers.style.display = 'flex';
                        }
                    }

                    const countryContentCountry = current.querySelector('#form-checkout__identificationCountry-container').lastChild.querySelector('input');
                    const countryHelpers =  current.querySelector('input-country').querySelector("input-helper").querySelector("div");
                    const verifyCountry = (countryContentCountry) => {
                        if (countryContentCountry.value === '') {
                            current.querySelector('input-country').querySelector(".mp-input").classList.add("mp-error");
                            current.querySelector('input-country').querySelector(".mp-input").parentElement.lastChild.classList.add("mp-error");
                            countryHelpers.style.display = 'flex';
                        }
                    }


                    const e = ce((async () => {
                        function o(e) {
                            return e && "flex" === e.style.display
                        }

                        const doc_type = cardContentDocument.parentElement.parentElement.querySelector("#epayco_custom\\[identificationType\\]");
                        const cellphoneType = customContentCellphone.parentElement.parentElement.querySelector(".mp-input-select-select").value;
                        const countryType = countryContentCountry.parentElement.parentElement.querySelector(".mp-input-select-select").value;
                        const doc_number_value =cardContentDocument.value;


                        const values = {
                            "epayco_custom[cardTokenId]": token,
                            "epayco_custom[name]": customContentName.value,
                            "epayco_custom[address]": customContentAddress.value,
                            "epayco_custom[email]": customContentEmail.value,
                            "epayco_custom[identificationtype]": doc_type.value,
                            "epayco_custom[doc_number]": doc_number_value,
                            "epayco_custom[countryType]": countryType,
                            "epayco_custom[cellphoneType]": cellphoneType,
                            "epayco_custom[cellphone]": customContentCellphone.value,
                            "epayco_custom[country]": countryContentCountry.value
                        };

                        "" === customContentName.value && verifyName(customContentName);
                        "" === cardNumberContentName.value && verifyCardNumber(cardNumberContentName);
                        "" === cardExpirationContentName.value && verifyCardExpiration(cardExpirationContentName);
                        "" === cardSecurityContentName.value && verifyCardSecurity(cardSecurityContentName);
                        "Type" === doc_type.value && verifyDocument(cardContentDocument);
                        "" === cardContentDocument.value && verifyDocument(cardContentDocument);
                        "" === customContentAddress.value && verifyAddress(customContentAddress);
                        "" === customContentEmail.value && verifyEmail(customContentEmail);
                        "" === customContentCellphone.value && verifyCellphone(customContentCellphone);
                        "" === countryContentCountry.value && verifyCountry(countryContentCountry);
                        let validation = o(nameHelpers) || o(cardNumberHelpers) || o(cardExpirationHelpers) || o(cardSecurityHelpers) || o(documentHelpers) || o(addressHelpers) || o(emailHelpers) || o(cellphoneHelpers) || o(countryHelpers);
                        try {
                            if (validation) {
                                var publicKey = wc_epayco_custom_checkout_params.public_key_epayco;
                                var token;
                                ePayco.setPublicKey(publicKey);
                                ePayco.setLanguage("es");
                                token=publicKey
                                /*token = await new Promise(function(resolve, reject) {
                                    ePayco.token.create(document, function (data) {
                                        debugger
                                            if(data.status==='error'){
                                                reject(false)
                                            }else{
                                                resolve(data.data.token)
                                            }
                                        })
                                })*/
                                //token = "await cardForm.createCardToken()";
                            }else{
                                //return {type: te.responseTypes.ERROR};
                            }
                        } catch (e) {
                            console.warn("Token creation error: ", e)
                        }
                        return  "" !== customContentName.value &&
                                "" !== cardNumberContentName.value &&
                                "" !== cardExpirationContentName.value &&
                                "" !== cardSecurityContentName.value &&
                                "" !== customContentAddress.value &&
                                "" !== customContentEmail.value &&
                                "" !== customContentCellphone.value &&
                                "" !== countryContentCountry.value &&
                                "" !== doc_number_value &&
                                "Type" !== doc_type.value
                            ,
                            Z("custom"),
                            {
                                type:validation ? te.responseTypes.ERROR : te.responseTypes.SUCCESS,
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
                    (0, e.createElement)("div", {className: "mp-checkout-container"},
                        (0, e.createElement)("div", {className: "mp-checkout-custom-container"}, a ?
                                (0, e.createElement)("div", {className: "mp-checkout-pro-test-mode"},
                                    (0, e.createElement)(test, {
                                        title: n,
                                        description: u,
                                        linkText: E,
                                        linkSrc: y
                                    })
                                ) : null,
                            (0, e.createElement)(svgLogo, {
                                width: "18",
                                height: "14",
                                viewBox: "0 0 18 14",
                                d: "M18 1.616V12.385C18 12.845 17.846 13.2293 17.538 13.538C17.23 13.8467 16.8457 14.0007 16.385 14H1.615C1.155 14 0.771 13.846 0.463 13.538C0.155 13.23 0.000666667 12.8453 0 12.384V1.616C0 1.15533 0.154333 0.771 0.463 0.463C0.771667 0.155 1.15567 0.000666667 1.615 0H16.385C16.845 0 17.229 0.154333 17.537 0.463C17.845 0.771667 17.9993 1.156 18 1.616ZM1 3.808H17V1.616C17 1.462 16.936 1.32067 16.808 1.192C16.68 1.06333 16.539 0.999333 16.385 1H1.615C1.46167 1 1.32067 1.064 1.192 1.192C1.06333 1.32 0.999333 1.46133 1 1.616V3.808ZM1 6.192V12.385C1 12.5383 1.064 12.6793 1.192 12.808C1.32 12.9367 1.461 13.0007 1.615 13H16.385C16.5383 13 16.6793 12.936 16.808 12.808C16.9367 12.68 17.0007 12.539 17 12.385V6.192H1Z",
                                m:"Card details"
                            }),
                            (0, e.createElement)("div", {id: "mp-custom-checkout-form-container"},
                                (0, e.createElement)("div", {className: "mp-checkout-custom-card-form"},

                                    (0, e.createElement)("div", {className: "mp-checkout-custom-card-row", id: "mp-card-holder-div"},
                                        (0, e.createElement)(name, {
                                            labelMessage:P,
                                            helperMessage:U,
                                            placeholder:"jonh doe",
                                            inputName:'epayco_custom[name]',
                                            flagError:'epayco_custom[nameError]',
                                            validate:"true",
                                            hiddenId:"hidden-name-custom"
                                        }),
                                    ),
                                    (0, e.createElement)("div", {className: "mp-checkout-custom-card-row"},
                                        (0, e.createElement)(cardNumber, {
                                            labelMessage:F,
                                            helperMessage:O,
                                            placeholder:"0000 0000 0000 0000",
                                            inputName:'epayco_custom[card]',
                                            flagError:'epayco_custom[cardError]',
                                            validate:"true",
                                            hiddenId:"hidden-card-number-custom"
                                        }),
                                    ),
                                    (0, e.createElement)("div", {className: "mp-checkout-custom-card-row mp-checkout-custom-dual-column-row"},
                                        (0, e.createElement)("div", {className: "mp-checkout-custom-card-column"},
                                            (0, e.createElement)(expirationDate, {
                                                labelMessage:D,
                                                helperMessage:L,
                                                placeholder:"mm/yy",
                                                inputName:'epayco_custom[expirationDate]',
                                                flagError:'epayco_custom[expirationDateError]',
                                                validate:"true",
                                                hiddenId:"hidden-expiration-date-helper"
                                            })
                                        ),
                                        (0, e.createElement)("div", {className: "mp-checkout-custom-card-column"},
                                            (0, e.createElement)(securityCode, {
                                                labelMessage:V,
                                                helperMessage:B,
                                                placeholder:"***",
                                                inputName:'epayco_custom[securityCode]',
                                                flagError:'epayco_custom[securityCodeError]',
                                                validate:"true",
                                                hiddenId:"hidden-security-code-helper"
                                            })
                                        ),
                                        (0, e.createElement)("div", {className: "mp-checkout-custom-card-column"},
                                            (0, e.createElement)(installments, {
                                                name:"epayco_custom[installment]",
                                                label:"fees",
                                                optional:"false",
                                                options:'[{"id":"", "description": "fees"},{"id":"1", "description": "1"}]'
                                            })
                                        ),
                                    ),
                                )
                            ),
                            //(0, e.createElement)("hr", null),
                            (0, e.createElement)(svgLogo, {
                                width: "21",
                                height: "16",
                                viewBox: "0 0 21 16",
                                d: "M13.013 8.528H17.7695V7.38514H13.013V8.528ZM13.013 5.36229H17.7695V4.21943H13.013V5.36229ZM3.2305 11.7806H10.9492V11.5909C10.9492 10.9242 10.6069 10.408 9.9225 10.0423C9.23806 9.67657 8.29383 9.49371 7.08983 9.49371C5.88583 9.49371 4.94122 9.67657 4.256 10.0423C3.57078 10.408 3.22894 10.9242 3.2305 11.5909V11.7806ZM7.08983 7.648C7.58217 7.648 7.99672 7.48305 8.3335 7.15314C8.67105 6.82247 8.83983 6.416 8.83983 5.93371C8.83983 5.45143 8.67105 5.04533 8.3335 4.71543C7.99594 4.38552 7.58139 4.22019 7.08983 4.21943C6.59828 4.21867 6.18372 4.384 5.84617 4.71543C5.50861 5.04686 5.33983 5.45295 5.33983 5.93371C5.33983 6.41448 5.50861 6.82095 5.84617 7.15314C6.18372 7.48533 6.59828 7.65028 7.08983 7.648ZM1.88533 16C1.34789 16 0.8995 15.824 0.540167 15.472C0.180833 15.12 0.000777778 14.6804 0 14.1531V1.84686C0 1.32038 0.180056 0.881142 0.540167 0.529143C0.900278 0.177143 1.34828 0.000761905 1.88417 0H19.1158C19.6525 0 20.1005 0.176381 20.4598 0.529143C20.8192 0.881904 20.9992 1.32114 21 1.84686V14.1543C21 14.68 20.8199 15.1192 20.4598 15.472C20.0997 15.8248 19.6517 16.0008 19.1158 16H1.88533ZM1.88533 14.8571H19.1158C19.2947 14.8571 19.4592 14.784 19.6093 14.6377C19.7594 14.4914 19.8341 14.3299 19.8333 14.1531V1.84686C19.8333 1.67086 19.7587 1.50933 19.6093 1.36229C19.46 1.21524 19.2955 1.1421 19.1158 1.14286H1.88417C1.70528 1.14286 1.54078 1.216 1.39067 1.36229C1.24056 1.50857 1.16589 1.6701 1.16667 1.84686V14.1543C1.16667 14.3295 1.24133 14.4907 1.39067 14.6377C1.54 14.7848 1.7045 14.8579 1.88417 14.8571",
                                m:"Customer data"
                            }),
                            (0, e.createElement)("div", {id: "mp-custom-checkout-form-container"},
                                (0, e.createElement)("div", {className: "mp-checkout-custom-input-document", id: "mp-doc-div"},
                                    (0, e.createElement)(documents, {
                                        labelMessage: $,
                                        helperMessage: q,
                                        inputName: 'epayco_custom[doc_number]',
                                        hiddenId: "dentificationType",
                                        inputDataCheckout: "doc_number",
                                        selectId: "dentificationType",
                                        selectName:"epayco_custom[identificationType]",
                                        selectDataCheckout: "doc_type",
                                        flagError: "identificationTypeError",
                                        "documents":'[{"id":"Type"},{"id":"CC"},{"id":"CE"},{"id":"NIT"},{"id":"TI"},{"id":"PPN"},{"id":"SSN"},{"id":"LIC"},{"id":"DNI"}]',
                                        "validate":"true",
                                        "placeholder":"0000000000"
                                    }),
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-custom-card-row", id: "mp-card-holder-div"},
                                    (0, e.createElement)(address, {
                                        labelMessage:PP,
                                        helperMessage:UU,
                                        placeholder:"Street 123",
                                        inputName:'epayco_custom[address]',
                                        flagError:'epayco_custom[addressError]',
                                        validate:"true",
                                        hiddenId:"hidden-address-custom"
                                    }),
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-custom-card-row", id: "mp-card-holder-div"},
                                    (0, e.createElement)(email, {
                                        labelMessage:EE,
                                        helperMessage:HH,
                                        placeholder:"john@example.com",
                                        inputName:'epayco_custom[email]',
                                        flagError:'epayco_custom[emailError]',
                                        validate:"true",
                                        hiddenId:"hidden-email-custom"
                                    }),
                                ),
                                (0, e.createElement)("div", {className: "mp-checkout-custom-card-row", id: "mp-card-holder-div"},
                                    (0, e.createElement)(cellphone, {
                                        labelMessage: chcil,
                                        helperMessage: chcih,
                                        inputId:"cellphoneTypeNumber",
                                        inputName: "epayco_ticket[cellphone]",
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
                                (0, e.createElement)("div", {className: "mp-checkout-custom-card-row", id: "mp-card-holder-div"},
                                    (0, e.createElement)(country, {
                                        labelMessage: cl,
                                        helperMessage: ch,
                                        inputId:"countryType",
                                        inputName: "epayco_ticket[country]",
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
                            ),
                            (0, e.createElement)(termscondictions, {
                                label: ll,
                                description: v,
                                linkText: N,
                                linkSrc: T,
                                checkoutClass: "ticket"
                            })
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


