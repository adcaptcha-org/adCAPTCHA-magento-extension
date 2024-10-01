/**
 * Copyright © 2024 adCAPTCHA. All rights reserved.
 */

(() => {
    (function(d, script) {
      script = d.createElement('script');
      script.type = 'text/javascript';
      script.async = true;
      script.onload = function(){
        if (window.adcap) {
            window.adcap.init();
            window.adcap.setupTriggers({
                onComplete: () => {
                    const event = new CustomEvent("adcaptcha_onSuccess", {
                        detail: { successToken: window.adcap.successToken },
                    });
                    document.dispatchEvent(event);
                }
            });
        }


        document.addEventListener("adcaptcha_onSuccess", function(e) {
            var elements = document.querySelectorAll(".adcaptcha_successToken");
            elements.forEach(function(element) {
                element.value = e.detail.successToken;
            });
        });

      };
      script.src = 'https://widget.adcaptcha.com/index.js';
      d.getElementsByTagName('head')[0].appendChild(script);
    }(document));
  })();
