grecaptcha.ready(function () {
    grecaptcha.execute('6LejP90UAAAAAEz9T38b5pRK69-qDCdr6GYw8y-k', { action: 'contact' }).then(function (token) {
        var recaptchaResponse = document.getElementById('recaptchaResponse');
        recaptchaResponse.value = token;
    });
});
