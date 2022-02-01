import { events } from '../../bus'

const defaultState = {
    singleChargeAmount: undefined,
}

const actions = {
    callSingleChargeProcess: ({ commit }, amount) => {
        // Open popup with payment methods
        events.$emit('popup:open', { name: 'select-payment-method' })

        // Store charge amount
        commit('SET_SINGLE_CHARGE_AMOUNT', amount)
    },
}

const mutations = {
    SET_SINGLE_CHARGE_AMOUNT(state, amount) {
        state.singleChargeAmount = amount
    },
}

const getters = {
    singleChargeAmount: (state) => state.singleChargeAmount,
}

export default {
    state: defaultState,
    getters,
    actions,
    mutations,
}
