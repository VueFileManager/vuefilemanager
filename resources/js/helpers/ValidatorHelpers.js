import store from '../store/index'

const ValidatorHelpers = {
    install(Vue) {
        Vue.prototype.$cantInviteMember = function (email, invitations) {
            if (['metered', 'none'].includes(store.getters.config.subscriptionType)) {
                return false
            }

            // Get max team members limitations
            let limit = store.getters.user.data.meta.limitations.max_team_members

            // Unlimited option
            if (limit.total === -1) {
                return false
            }

            // Get emails from invitations and currently active members
            let newInvitationEmails = invitations.map((item) => item['email'])
            let allowedMemberEmails = limit.meta.allowed_emails

            // Get unique list of member emails
            let totalUniqueEmails = [...new Set(newInvitationEmails.concat(Object.values(allowedMemberEmails)))]

            // If there is more unique emails than can be in limit, disable ability to add member
            return totalUniqueEmails.length >= limit.total && !totalUniqueEmails.includes(email)
        }

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
