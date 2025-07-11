(() => {
    "use strict";
    const e = window.React,
        t = window.wc.wcBlocksRegistry,
        o = window.wc.wcSettings,
        a = window.wp.element,
        c = window.wp.htmlEntities,
        n = "epayco_blocks_update_cart";
    var r;
    const m = "mp_checkout_blocks", d = "woo-epayco-checkout",
        i = (0, o.getSetting)("woo-epayco-checkout_data", {}),
        p = (0, c.decodeEntities)(i.title) || "Checkout ePayco", u = t => {
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
                    const  n = {
                            "epayco_checkout": true,
                        }
                    return {
                        type: c.responseTypes.SUCCESS,
                        meta: {paymentMethodData: n}
                    }
                }));
                return () => e()
            }), [c.responseTypes.ERROR, c.responseTypes.SUCCESS, r]),
                (0, e.createElement)("div", {dangerouslySetInnerHTML: {__html: i.params.content}})
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
                        const hasClass = document.querySelector('.ep-checkout-epayco-container') !== null;
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
                        src: "https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/checkoutEpayco.png",
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
                        ? "Otros métodos de pago."
                        : "Other payment methods."),
                ),
                showLogo && (0, e.createElement)("img", {
                    //pago en bloque 
                    src: "https://multimedia-epayco-preprod.s3.us-east-1.amazonaws.com/plugins-sdks/new/iconosCheckout.png",
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