define([
        'jquery',
        'uiComponent',
        'mage/validation',
        'ko',
        'Cardoso_CustomerProducts/js/product/request_list',
    ], function ($, Component, validation, ko, request) {
        'use strict';
        const products = ko.observableArray([]);
        return Component.extend({
            defaults: {
                template: 'Cardoso_CustomerProducts/product_list/item',
                lowRange: '',
                highRange: '',
                sortByPrice: 'ascending',
                highRangeErrorMessage: ko.observable(),
            },
            getProducts: function () {
                return products();
            },
            initialize: function () {
                this._super();
            },
            search: function (data) {
                const self = this;
                const saveData = {},
                    formDataArray = $(data).serializeArray();

                formDataArray.forEach(function (entry) {
                    saveData[entry.name] = entry.value;
                });

                if ($(data).validation()
                    && $(data).validation('isValid')
                    && this.validateRequisites()
                ) {
                    request(saveData, products).always(function () {
                            return products()
                        }
                    );
                }
            },
            validateHighRange: function (value) {
                const low = parseFloat(this.lowRange);
                if (isNaN(value) || value <= low || value > low * 5) {
                    return false;
                }

                return true;
            },
            validateRequisites: function () {
                const low = parseFloat(this.lowRange);
                const high = parseFloat(this.highRange);
                if (!isNaN(low) && !isNaN(high) && this.validateHighRange(high)) {
                    this.highRangeErrorMessage('');
                    return true;
                } else {
                    this.highRangeErrorMessage('Enter the High Range value (maximum ' + low * 5 + '):')
                    return false;
                }
            }
        });
    }
);