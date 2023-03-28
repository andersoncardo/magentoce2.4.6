define([
        'jquery',
        'uiComponent',
        'mage/validation',
        'ko',
        'Cardoso_CustomerProducts/js/product/request_list',
    ], function ($, Component, validation, ko, saveAction) {
        'use strict';
        const totalCustomer = ko.observableArray([]);

        return Component.extend({
            defaults: {
                template: 'Cardoso_CustomerProducts/product_list/item',
                lowRange: '',
                highRange: '',
                sortByPrice: 'ascending',
                highRangeErrorMessage: ko.observable(),
            },

            initialize: function () {
                this._super();
            },
            save: function (saveForm) {
                const self = this;
                const saveData = {},
                    formDataArray = $(saveForm).serializeArray();

                formDataArray.forEach(function (entry) {
                    saveData[entry.name] = entry.value;
                });

                if ($(saveForm).validation()
                    && $(saveForm).validation('isValid')
                ) {
                    // saveAction(saveData, totalCustomer).always(function() {
                    //     console.log(totalCustomer());
                    // });
                }
            },
            validateHighRange: function(value) {
                var low = parseFloat(this.lowRange);
                if (isNaN(value) || value <= low || value > low * 5) {
                    return false;
                }
                return true;
            },
            clearHighRange: function() {

            },
            getError: function () {
                return totalCustomer;
            },
            sortByPriceClick: function() {
                const low = parseFloat(this.lowRange);
                const high = parseFloat(this.highRange);
                if (!isNaN(low) && !isNaN(high) && this.validateHighRange(high)) {
                    this.highRangeErrorMessage('');
                } else {
                    this.highRangeErrorMessage('Enter the High Range value (maximum ' + low * 5 +'):')
                }
            }
        });
    }
);