/*global define,alert*/
define(
    [
        'ko',
        'jquery',
        'mage/storage',
        'mage/translate',
    ],
    function (
        ko,
        $,
        storage,
        $t
    ) {
        'use strict';
        return function (payload, products) {
            return storage.post(
                'customer/product/search',
                JSON.stringify(payload),
                false
            ).done(
                function (response) {
                    if (response) {
                        products([]);
                        $.each(response, function (i, v) {
                            products.push(v);
                        });
                    }
                }
            ).fail(
                function (response) {
                }
            );
        };
    }
);