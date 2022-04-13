const ValidatorHelpers = {
    install(Vue) {
        Vue.prototype.$generatePaystackReference = function () {
            let text = ''

            let possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'

            for (let i = 0; i < 10; i++) text += possible.charAt(Math.floor(Math.random() * possible.length))

            return text
        }
    },
}

export default ValidatorHelpers
