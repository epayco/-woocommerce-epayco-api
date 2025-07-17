(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        o = window.wc.wcSettings,
        a = window.wp.element,
        c = window.wp.htmlEntities,
        n = "epayco_blocks_update_cart";
    var r;
    const m = "mp_checkout_blocks", d = "woo-epayco-creditcard",
        i = (0, o.getSetting)("woo-epayco-creditcard_data", {}),
        p = (() => {
            let title = i && i.title ? (0, c.decodeEntities)(i.title) : "";
            if (!title || title.trim() === "") {
                title = document.documentElement.lang.startsWith('es') 
                    ? "ePayco - Pago Tarjeta de credito y/o debito" 
                    : "ePayco - Credit and/or Debit Cards";
            }
            return title;
        })(),
        u = t => {
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
                        if(t.paymentDetails.threeDs){
                            const threeds = JSON.parse(t.paymentDetails.threeDs);
                            console.log(t.paymentDetails.threeDs);
                        }
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
                {onPaymentSetup: r} = o;
            return (0, a.useEffect)((() => {
                const e = r((async () => {
                    var e;

                    const current =  document.querySelector(".ep-checkout-creditcard-container");
                    const customContentName = current.querySelector('input-card-name').querySelector('input');
                    const nameHelpers =  current.querySelector('input-helper-epayco').querySelector("div");
                    const verifyName = (nameElement) => {
                        if (nameElement.value === '') {
                            current.querySelector('input-card-name').querySelector(".ep-input").classList.add("ep-error");
                            nameHelpers.style.display = 'flex';
                        }
                    }
                    const cardNumberContentName = current.querySelector('input-card-number').querySelector('input');
                    const cardNumberHelpers =  current.querySelector('input-card-number').querySelector("input-helper-epayco").querySelector("div");
                    const verifyCardNumber = (nameElement) => {
                        if (nameElement.value === '') {
                            current.querySelector('input-card-number').querySelector(".ep-input").classList.add("ep-error");
                            cardNumberHelpers.style.display = 'flex';
                        }
                    }
                    const cardExpirationContentName = current.querySelector('input-card-expiration-date').querySelector('input');
                    const cardExpirationHelpers =  current.querySelector('input-card-expiration-date').querySelector("input-helper-epayco").querySelector("div");
                    const verifyCardExpiration = (nameElement) => {
                        if (nameElement.value === '') {
                            current.querySelector('input-card-expiration-date').querySelector(".ep-input").classList.add("ep-error");
                            cardExpirationHelpers.style.display = 'flex';
                        }
                    }
                    const cardSecurityContentName = current.querySelector('input-card-security-code').querySelector('input');
                    const cardSecurityHelpers =  current.querySelector('input-card-security-code').querySelector("input-helper-epayco").querySelector("div");
                    const verifyCardSecurity = (nameElement) => {
                        if (nameElement.value === '') {
                            current.querySelector('input-card-security-code').querySelector(".ep-input").classList.add("ep-error");
                            cardSecurityHelpers.style.display = 'flex';
                        }
                    }


                    const cardContentDocument = current.querySelector('input-document-epayco').querySelector('input');
                    const documentHelpers =  current.querySelector('input-document-epayco').querySelector("input-helper-epayco").querySelector("div");
                    const verifyDocument = (cardContentDocument) => {
                        if (cardContentDocument.value === '') {
                            current.querySelector('input-document-epayco').querySelector(".ep-input").classList.add("ep-error");
                            current.querySelector('input-document-epayco').querySelector(".ep-input").parentElement.lastChild.classList.add("ep-error");
                            documentHelpers.style.display = 'flex';
                        }
                    }

                    const customContentAddress = current.querySelector('input-address-epayco').querySelector('input');
                    const addressHelpers =  current.querySelector('input-address-epayco').querySelector("input-helper-epayco").querySelector("div");
                    const verifyAddress = (addressElement) => {
                        if (addressElement.value === '') {
                            current.querySelector('input-address-epayco').querySelector(".ep-input").classList.add("ep-error");
                            addressHelpers.style.display = 'flex';
                        }
                    }

                    const customContentEmail = current.querySelector('input-card-email').querySelector('input');
                    const emailHelpers =  current.querySelector('input-card-email').querySelector("input-helper-epayco").querySelector("div");
                    const verifyEmail = (emailElement) => {
                        if (emailElement.value === '') {
                            current.querySelector('input-card-email').querySelector(".ep-input").classList.add("ep-error");
                            emailHelpers.style.display = 'flex';
                        }
                    }

                    const customContentCellphone = current.querySelector('input-cellphone-epayco').querySelector('#cellphoneTypeNumber').querySelector('input');
                    const cellphoneHelpers =  current.querySelector('input-cellphone-epayco').querySelector("input-helper-epayco").querySelector("div");
                    const verifyCellphone = (customContentCellphone) => {
                        if (customContentCellphone.value === '') {
                            current.querySelector('input-cellphone-epayco').querySelector(".ep-input").classList.add("ep-error");
                            current.querySelector('input-cellphone-epayco').querySelector(".ep-input").parentElement.lastChild.classList.add("ep-error");
                            cellphoneHelpers.style.display = 'flex';
                        }
                    }

                    const countryContentCountry = current.querySelector('#form-checkout__identificationCountry-container').lastChild.querySelector('input');
                    const countryHelpers =  current.querySelector('input-country-epayco').querySelector("input-helper-epayco").querySelector("div");
                    const verifyCountry = (countryContentCountry) => {
                        if (countryContentCountry.value === '') {
                            current.querySelector('input-country-epayco').querySelector(".ep-input").classList.add("ep-error");
                            current.querySelector('input-country-epayco').querySelector(".ep-input").parentElement.lastChild.classList.add("ep-error");
                            countryHelpers.style.display = 'flex';
                        }
                    }
                    const termanAndContictionContent = document.querySelector('terms-and-conditions').querySelector('input');
                    const termanAndContictionHelpers = document.querySelector('terms-and-conditions').querySelector(".ep-terms-and-conditions-container");
                    termanAndContictionContent.addEventListener('click', function() {
                        if (termanAndContictionContent.checked) {
                            termanAndContictionHelpers.classList.remove("ep-error")
                        }
                    });
                    const customContentInstallments =document.getElementById('epayco_creditcard[installmet]').value;
                    const doc_type =document.getElementById('epayco_creditcard[identificationType]');
                    const cellphoneType = customContentCellphone.parentElement.parentElement.querySelector(".ep-input-select-select").value;
                    const countryType = countryContentCountry.parentElement.parentElement.querySelector(".ep-input-select-select").value;
                    const doc_number_value =cardContentDocument.value;

                    "" === customContentName.value && verifyName(customContentName);
                    "" === cardNumberContentName.value && verifyCardNumber(cardNumberContentName);
                    "" === cardExpirationContentName.value && verifyCardExpiration(cardExpirationContentName);
                    "" === cardSecurityContentName.value && verifyCardSecurity(cardSecurityContentName);
                    "Type"||"Tipo" === doc_type.value && verifyDocument(cardContentDocument);
                    "" === cardContentDocument.value && verifyDocument(cardContentDocument);
                    "" === customContentAddress.value && verifyAddress(customContentAddress);
                    "" === customContentEmail.value && verifyEmail(customContentEmail);
                    "" === customContentCellphone.value && verifyCellphone(customContentCellphone);
                    "" === countryContentCountry.value && verifyCountry(countryContentCountry);
                    !termanAndContictionContent.checked && termanAndContictionHelpers.classList.add("ep-error");
                    let validation = d(nameHelpers) || d(cardNumberHelpers) || d(cardExpirationHelpers) || d(cardSecurityHelpers) || d(documentHelpers) || d(addressHelpers) || d(emailHelpers) || d(cellphoneHelpers) || d(countryHelpers) || !termanAndContictionContent.checked;
                    try {
                        var createTokenEpayco = async function  ($form) {
                            return await new Promise(function(resolve, reject) {
                                ePayco.token.create($form, function(data) {
                                    if(data.status == 'error' || data.error){
                                        reject(false)
                                    }else{
                                        if(data.status == 'success'){
                                            document.querySelector('#cardTokenId').value = data.data.token;
                                            resolve(data.data.token)
                                        }else{
                                            reject(false)
                                        }
                                    }
                                });
                            });
                        }
                        if (!validation) {
                            var publicKey = wc_epayco_creditcard_checkout_params.public_key_epayco;
                            var lang = wc_epayco_creditcard_checkout_params.lang
                            //var token;
                            ePayco.setPublicKey(publicKey);
                            ePayco.setLanguage("es");
                            var token = await createTokenEpayco(current);
                            if(!token){
                                validation = true;
                            }
                        }else{
                            return {
                                type: c.responseTypes.FAIL,
                                messageContext: "PAYMENTS",
                                message: "error"
                            }
                        }
                    } catch (e) {
                        console.warn("Token creation error: ", e)
                        return {
                            type: c.responseTypes.ERROR,
                            messageContext: "PAYMENTS",
                            message: "error"
                        }
                    }
                    const nn = {
                        "epayco_creditcard[cardTokenId]": token,
                        "epayco_creditcard[name]": customContentName.value,
                        "epayco_creditcard[address]": customContentAddress.value,
                        "epayco_creditcard[email]": customContentEmail.value,
                        "epayco_creditcard[identificationtype]": doc_type.value,
                        "epayco_creditcard[doc_number]": doc_number_value,
                        "epayco_creditcard[countryType]": countryType,
                        "epayco_creditcard[cellphoneType]": cellphoneType,
                        "epayco_creditcard[cellphone]": customContentCellphone.value,
                        "epayco_creditcard[country]": countryContentCountry.value,
                        "epayco_creditcard[installmet]": customContentInstallments,
                        };

                    function m(e, t) {
                        e && e.style && (e.style.display = t)
                    }

                    function d(e) {
                        return e && "flex" === e.style.display
                    }


                    return "" !== customContentName.value &&
                    "" !== cardNumberContentName.value &&
                    "" !== cardExpirationContentName.value &&
                    "" !== cardSecurityContentName.value &&
                    "" !== customContentAddress.value &&
                    "" !== customContentEmail.value &&
                    "" !== customContentCellphone.value &&
                    "" !== countryContentCountry.value &&
                    "" !== doc_number_value &&
                    "Type"||"Tipo" !== doc_type.value,{
                        type: validation || !termanAndContictionContent.checked   ? c.responseTypes.ERROR : c.responseTypes.SUCCESS,
                        meta: {paymentMethodData: nn}
                    }
                }));
                return () => e()
            }), [c.responseTypes.ERROR, c.responseTypes.SUCCESS, r]), (0, e.createElement)("div", {dangerouslySetInnerHTML: {__html: i.params.content}})
        }, l = {
            name: d,
            label: (0, e.createElement)((t => {
                const { PaymentMethodLabel: o } = t.components;
                const a = (0, c.decodeEntities)(i?.params?.fee_title || "");
                const n = `${p} ${a}`;
                const [showLogo, setShowLogo] = e.useState(true);

                e.useEffect(() => {
                    let timer = setTimeout(() => setShowLogo(false), 15000);

                    const observer = new MutationObserver(() => {
                        const hasClass = document.querySelector('.ep-checkout-creditcard-container') !== null;
                        setShowLogo(!hasClass);
                    });

                    observer.observe(document.body, { childList: true, subtree: true });

                    return () => {
                        clearTimeout(timer);
                        observer.disconnect();
                    };
                }, []);

            return (0, e.createElement)("div", {
                style: {
                    display: "flex",
                    alignItems: "center",
                    gap: "6px",
                    width: "100%",
                    maxWidth: "100%",
                    margin: "0 20px 0 0",
                    flexWrap: "wrap",
                },
                className: "epayco-payment-label-responsive"
            },
                (0, e.createElement)("img", {
                        src: "https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/tarjetaCreditoDebito.png",
                        alt: "ePayco Efectivo Icono",
                        className: "epayco-icon-mobile-hide",
                        style: {
                            maxWidth: "45px",
                            width: "auto",
                            height: "auto",
                            maxHeight: "45px",
                            marginBottom: "5px",
                            marginRight: "5px",
                        }
                    }), 
                (0, e.createElement)("div", {
                    className: "epayco-payment-label-texts", 
                    style: {
                        display: "flex",
                        flexDirection: "column"
                
                    }
                },
                    (0, e.createElement)("strong", null, n),
                    (0, e.createElement)("span", {
                        style: {
                            fontSize: "12px",
                            color: "#666",
                            marginTop: "2px"
                        }
                    }, document.documentElement.lang.startsWith('es')
                        ? "Visa, MasterCard, Amex, Diners y Codensa."
                        : "Visa, MasterCard, Amex, Diners and Codensa.")
                ),
                showLogo && (0, e.createElement)("img", {
                    src: "https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/iconosTarjetas.png",
                    alt: "ePayco",
                    className: "epayco-logos-final",
                    style: {
                        maxWidth: "280px",
                        width: "auto",
                        marginLeft: "10px",
                        maxHeight: "70px",
                        height: "auto",
                    }
                }),
                (0,  e.createElement)("style", null, `
                    @media (max-width: 430px) {
                        .epayco-logos-final {
                            max-width: 200px !important;
                            height: auto !important;
                        }
                        .epayco-icon-mobile-hide{
                            max-width: 25px;
                            height: 25px;
                        }
                    }
                    `)
            );
            }), null),
            content: (0, e.createElement)(u, null),
            edit: (0, e.createElement)(u, null),
            canMakePayment: () => !0,
            ariaLabel: p,
            supports: {features: null !== (r = i?.supports) && void 0 !== r ? r : []}
        };
    (0, t.registerPaymentMethod)(l)
})();