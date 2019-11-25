//declaring our widget
define([
    'jquery',
    'jquery/ui'
], function ($) {
    $.widget('mage.myCustomWidget', {
        options: {
            abcd: 1,
            passvalue: 'test'
        },
        /**
         * Widget initialization
         * @private
         */
        _create: function () {
            alert(this.options.passvalue); //custom message
            alert(this.options.abcd); // 123
        }
    });

    return $.mage.myCustomWidget;
});