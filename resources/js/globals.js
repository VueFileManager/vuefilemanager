/**
 * Registry of global helper methods ($xxx).
 *
 * Vue 2 code registered helpers on `Vue.prototype` and also called them from outside
 * components (store modules, other helpers) via `Vue.prototype.$xxx()`. In Vue 3 the
 * helpers are registered here by the helper plugins and copied to
 * `app.config.globalProperties` by `installGlobals()`, so both styles keep working:
 *
 *  - inside components:   this.$xxx()
 *  - outside components:  globals.$xxx()
 *
 * When a helper is called through `globals`, `this` is the `globals` object, which
 * also exposes `$store`, `$router`, `$route`, `$t` (wired in main.js).
 */
export const globals = {}

const reserved = ['$store', '$router', '$route', '$t', '$i18n']

export function installGlobals(app) {
    Object.keys(globals)
        .filter((key) => !reserved.includes(key))
        .forEach((key) => {
            app.config.globalProperties[key] = globals[key]
        })
}
