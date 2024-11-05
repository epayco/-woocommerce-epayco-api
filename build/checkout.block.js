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
            }) => (0, e.createElement)("div", {className: "mp-checkout-pro-test-mode"},
                (0, e.createElement)("test-mode", {
                title: t,
                description: c
                })
            );
    var d;
    const p = "mp_checkout_blocks",
        _ = "woo-epayco-checkout",
        u = (0, o.getSetting)("woo-epayco-checkout_data", {}),
        k = (0, n.decodeEntities)(u.title) || "Checkout ePayco",
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
            } = u.params;
            return (0, e.createElement)("div", {className: "mp-checkout-container"},
                    (0, e.createElement)("div", {className: "mp-checkout-pro-container"},
                        (0, e.createElement)("div", {className: "mp-checkout-pro-content",style: {display: x ? "contents":"none"} },
                            x ? (0, e.createElement)(a, {
                                title: n,
                                description: o
                            }) : null,
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