import mitt from 'mitt'

/**
 * Global event bus (Vue 3 replacement for `new Vue()` event hub).
 * Keeps the `$emit / $on / $off / $once` API so existing call sites keep working.
 */
const emitter = mitt()

// mitt passes only one payload argument to handlers. Wrap payloads so multiple
// arguments ($emit('name', a, b)) are forwarded the same way Vue 2 did.
const handlerMap = new Map()

function wrap(event, handler) {
    let handlers = handlerMap.get(event)

    if (!handlers) {
        handlers = new Map()
        handlerMap.set(event, handlers)
    }

    let wrapped = handlers.get(handler)

    if (!wrapped) {
        wrapped = (args) => handler(...args)
        handlers.set(handler, wrapped)
    }

    return wrapped
}

export const events = {
    $on(event, handler) {
        ;[].concat(event).forEach((name) => emitter.on(name, wrap(name, handler)))
        return this
    },
    $once(event, handler) {
        const once = (...args) => {
            this.$off(event, once)
            handler(...args)
        }
        return this.$on(event, once)
    },
    $off(event, handler) {
        if (event === undefined) {
            emitter.all.clear()
            handlerMap.clear()
            return this
        }

        ;[].concat(event).forEach((name) => {
            if (handler === undefined) {
                emitter.off(name)
                handlerMap.delete(name)
                return
            }

            const handlers = handlerMap.get(name)
            const wrapped = handlers && handlers.get(handler)

            if (wrapped) {
                emitter.off(name, wrapped)
                handlers.delete(handler)
            }
        })

        return this
    },
    $emit(event, ...args) {
        emitter.emit(event, args)
        return this
    },
}
