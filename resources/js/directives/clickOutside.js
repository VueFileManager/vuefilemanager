/**
 * v-click-outside directive (Vue 3 replacement for the `v-click-outside` package).
 *
 * Usage: <div v-click-outside="handler"> — `handler(event)` is called on click (or touchstart
 * on touch devices) anywhere outside of the element.
 */
const HANDLER = '__vueClickOutside__'

const events = () =>
    typeof document !== 'undefined' && 'ontouchstart' in document.documentElement ? ['touchstart'] : ['click']

function bind(el, { value }) {
    if (typeof value !== 'function') return

    const state = { handler: value, active: false }

    state.listener = (event) => {
        if (!state.active) return

        const path = event.composedPath ? event.composedPath() : []
        const isInside = path.length ? path.includes(el) : el.contains(event.target)

        if (!isInside) state.handler(event)
    }

    // Defer activation so the click which mounted the element doesn't close it immediately
    state.timeout = setTimeout(() => {
        state.active = true
    }, 0)

    events().forEach((name) => document.documentElement.addEventListener(name, state.listener, false))

    el[HANDLER] = state
}

function unbind(el) {
    const state = el[HANDLER]

    if (!state) return

    clearTimeout(state.timeout)
    events().forEach((name) => document.documentElement.removeEventListener(name, state.listener, false))

    delete el[HANDLER]
}

export default {
    mounted: bind,
    updated(el, binding) {
        if (binding.value === binding.oldValue) return

        if (el[HANDLER] && typeof binding.value === 'function') {
            el[HANDLER].handler = binding.value
        } else {
            unbind(el)
            bind(el, binding)
        }
    },
    beforeUnmount: unbind,
}
