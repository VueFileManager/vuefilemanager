const ValidatorHelpers = {
    install(Vue) {
        Vue.prototype.$isValidEmail = function (email) {
            return email.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/) !== null
        }

        Vue.prototype.$reCaptchaToken = async function (action) {
            await this.$recaptchaLoaded()

            return await this.$recaptcha(action)
        }
    },
}

export default ValidatorHelpers
