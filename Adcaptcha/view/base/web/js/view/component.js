/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

/*global define*/
define(
    [
        'ko',
        'jquery',
        'uiComponent',
        'mage/translate',
        'adcaptcha_widget_script',
    ],
    function (
        ko,
        $,
        Component
    ) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'Adcaptcha_Magento/adcaptcha',
            },
            configSource: 'adcaptchaConfig',
            config: {
                'enabled': false,
                'placementid': '',
            },
            action: 'default',
            element: null,

            /**
             * Initialize
             */
            initialize: function () {
                this._super();

                if (typeof window[this.configSource] !== 'undefined' && window[this.configSource].config) {
                    this.config = window[this.configSource].config;
                }
            },


            canShow: function () {
                return this.config.enabled;
            },


            load: function (element) {
                this.element = element;

                if (!this.config.placementid) {
                    this.element.innerText = $.mage.__('Placement ID is missing, please contact the owner of the site.');
                } else {
                    this.beforeRender();
                    this.render();
                }
            },

            /**
             * Render widget
             */
            render: function () {

                if (this.element) {

                    this.element.setAttribute('data-adcaptcha', this.config.placementid);

                    this.afterRender();
                }
            },


            beforeRender: function () {
                // Do something if we want.
            },


            afterRender: function () {
                window.adcap.init();
            },

            reset: function () {
                if (this.config.placementid) {
                    adcaptcha.reset(this.placementid);
                }
            }
        });
    }
);
