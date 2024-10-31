jQuery(document).ready(function () {
    const iframe = document.getElementById("currency-iframe");
    const body = document.body;
    const sidebar = document.querySelector('#adminmenuwrap');


    iframe.style.width = body.clientWidth - sidebar.clientWidth - 30 + 'px';
    iframe.style.maxWidth = '1500px';
    iframe.style.maxHeight = '800px';
    iframe.style.minHeight = '800px';

    window.addEventListener('resize', () => {
        iframe.style.width = body.clientWidth - sidebar.clientWidth - 30 + 'px';
    })


    function authen() {
        jQuery.ajax({
            data: {
                action: "mskmc_getCodeAuth", //Tên action, dữ liệu gởi lên cho server
            },
            method: "POST",
            url: ajaxurl,
            success: function (response) {
                iframe.addEventListener("load", function () {
                    iframe.contentWindow.postMessage(
                        {
                            payload: {
                                url: window.MSKMC_GLOBAL.restBase,
                                token: response.data.code,
                                tidioId: window.MSKMC_GLOBAL.tidio || "",
                                clientSite: window.MSKMC_GLOBAL.clientSite || "",
                                email: window.MSKMC_GLOBAL.email || "",
                                purchaseCode: window.MSKMC_GLOBAL.purchaseCode || "",
                                purchaseCodeLink: window.MSKMC_GLOBAL.purchaseCodeLink || "",
                                productName: window.MSKMC_GLOBAL.productName || "",
                                endpointVerification:
                                    window.MSKMC_GLOBAL.endpointVerification || "",
                            },
                            type: "@InitializePage/getWPInfoRequest",
                        },
                        "*"
                    );
                    iframe.classList.remove("hidden");
                });

                // check trường hợp login thành công
                if (iframe) {
                    iframe.contentWindow.postMessage(
                        {
                            payload: {
                                url: window.MSKMC_GLOBAL.restBase,
                                token: response.data.code,
                                tidioId: window.MSKMC_GLOBAL.tidio || "",
                                clientSite: window.MSKMC_GLOBAL.clientSite || "",
                                email: window.MSKMC_GLOBAL.email || "",
                                purchaseCode: window.MSKMC_GLOBAL.purchaseCode || "",
                                purchaseCodeLink: window.MSKMC_GLOBAL.purchaseCodeLink || "",
                                productName: window.MSKMC_GLOBAL.productName || "",
                                endpointVerification:
                                    window.MSKMC_GLOBAL.endpointVerification || "",
                            },
                            type: "@InitializePage/getWPInfoRequest",
                        },
                        "*"
                    );
                }
            },
            error: function (response) {
                iframe.addEventListener("load", function () {
                    iframe.contentWindow.postMessage(
                        {
                            payload: {
                                url: window.MSKMC_GLOBAL.restBase,
                                token: "",
                                tidioId: window.MSKMC_GLOBAL.tidio || "",
                                clientSite: window.MSKMC_GLOBAL.clientSite || "",
                                email: window.MSKMC_GLOBAL.email || "",
                                purchaseCode: window.MSKMC_GLOBAL.purchaseCode || "",
                                purchaseCodeLink: window.MSKMC_GLOBAL.purchaseCodeLink || "",
                                productName: window.MSKMC_GLOBAL.productName || "",
                                endpointVerification:
                                    window.MSKMC_GLOBAL.endpointVerification || "",
                            },
                            type: "@InitializePage/getWPInfoRequest",
                        },
                        "*"
                    );
                    iframe.classList.remove("hidden");
                });
            },
        });
    }

    authen();

    window.addEventListener("message", (event) => {
        if (event.source !== iframe.contentWindow) {
            return;
        }

        const { payload, type } = event.data

        if (type === "@HasPassed") {
            if (payload.hasPassed === true) {
                authen();
            }
        }

        // api get me/settings
        if ('getMeSettings/request') {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "mskmc_getMeSettings",
                    params: payload,
                },
                success: function (response) {
                    iframe.contentWindow.postMessage(
                        {
                            payload: response,
                            type: "getMeSettings/success",
                        },
                        "*"
                    );
                },
                error: function (jqXHR, error, errorThrown) {
                    alert(jqXHR.responseJSON.message);
                },
            });
        }

        // api get registered-menus
        if (type === "getMenuWP/request") {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "mskmc_getMenuWP",
                    params: payload,
                },
                success: function (response) {
                    iframe.contentWindow.postMessage(
                        {
                            payload: response,
                            type: "getMenuWP/success",
                        },
                        "*"
                    );
                },
                error: function (jqXHR, error, errorThrown) {
                    alert(jqXHR.responseJSON.message);
                },
            });
        }

        // api save setting
        if (type === 'saveSettings/request') {
            const { isNew, settings } = payload;
            if (isNew){
                // Nếu isNew = true thì là create
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl+"?action=mskmc_saveSettings",
                    data: JSON.stringify({
                        action: "mskmc_saveSettings",
                        params: payload,
                    }),
                    dataType: "json",
                    contentType: "application/json;charset=utf-8",
                    success: function (response) {
                        iframe.contentWindow.postMessage(
                            {
                                payload: response,
                                type: "saveSettings/success",
                            },
                            "*"
                        );
                    },
                    error: function (jqXHR, error, errorThrown) {
                        alert(jqXHR.responseJSON.message);
                    },
                });
            }else {
                // Nếu isNew = false thì là update
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl+"?action=mskmc_updateSettings",
                    data: JSON.stringify({
                        action: "mskmc_updateSettings",
                        params: payload,
                    }),
                    dataType: "json",
                    contentType: "application/json;charset=utf-8",
                    success: function (response) {
                        iframe.contentWindow.postMessage(
                            {
                                payload: response,
                                type: "saveSettings/success",
                            },
                            "*"
                        );
                    },
                    error: function (jqXHR, error, errorThrown) {
                        alert(jqXHR.responseJSON.message);
                    },
                });
            }
        }



    }, false);

    jQuery("#btn-Revoke-Purchase-Code").on('click', function () {
        let status = confirm("Are you sure you want to revoke the Purchase Code?");
        if (status) {
            jQuery.ajax({
                type: "POST",
                url: ajaxurl,
                data: {
                    action: "mskmc_revokePurchaseCode",
                    purchaseCode: MSKMC_GLOBAL.purchaseCode
                },
                success: function (response) {
                    if (response.status === 'success') {
                        location.reload();
                    } else {
                        alert(response.data.message);
                    }
                },
                error: function (jqXHR, error, errorThrown) {
                    alert(jqXHR.responseJSON.message);
                },
            });
        }
    });
});