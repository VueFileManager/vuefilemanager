import { h, nextTick, vModelCheckbox, vModelDynamic, vModelRadio, vModelSelect, vModelText } from 'vue'
import { validateValue } from './rules.js'

/**
 * Minimal Vue 3 replacement for vee-validate 3 <ValidationProvider>.
 *
 * Supported API (exactly what this app uses):
 *  - props: name, rules ('required|email|min:3' or object), vid, tag, mode ('passive' | 'aggressive')
 *  - slot props: errors, valid, invalid, validate, reset
 *  - methods (via ref / parent observer): validate(), setErrors(), reset()
 *
 * Like vee-validate 3, the validated value is taken from the first `v-model` found inside
 * the provider's rendered content (native v-model directive or a component `modelValue`).
 */
const modelDirectives = [vModelText, vModelCheckbox, vModelRadio, vModelSelect, vModelDynamic]

const NOT_FOUND = Symbol('not-found')

function findModelValue(vnode) {
    if (!vnode || typeof vnode !== 'object') return NOT_FOUND

    if (Array.isArray(vnode)) {
        for (const child of vnode) {
            const value = findModelValue(child)
            if (value !== NOT_FOUND) return value
        }
        return NOT_FOUND
    }

    if (vnode.dirs) {
        const directive = vnode.dirs.find((binding) => modelDirectives.includes(binding.dir))

        if (directive) return directive.value
    }

    if (vnode.component) {
        // Nested providers own their own model
        if (vnode.type === ValidationProvider) return NOT_FOUND

        if (vnode.props && 'onUpdate:modelValue' in vnode.props) {
            return vnode.props.modelValue
        }

        return findModelValue(vnode.component.subTree)
    }

    if (Array.isArray(vnode.children)) {
        return findModelValue(vnode.children)
    }

    return NOT_FOUND
}

let providerCounter = 0

const ValidationProvider = {
    name: 'ValidationProvider',
    inject: {
        veeObserver: {
            from: '$_veeObserver',
            default: null,
        },
    },
    props: {
        name: { type: String, default: null },
        rules: { type: [String, Object], default: null },
        vid: { type: String, default: null },
        tag: { type: String, default: 'span' },
        mode: { type: String, default: 'aggressive' },
        disabled: { type: Boolean, default: false },
    },
    data() {
        return {
            errors: [],
            validated: false,
        }
    },
    computed: {
        id() {
            if (this.vid) return this.vid
            if (this.name) return this.name

            if (!this._fallbackId) {
                providerCounter++
                this._fallbackId = '_vee_' + providerCounter
            }

            return this._fallbackId
        },
        invalid() {
            return this.errors.length > 0
        },
    },
    watch: {
        id(newId, oldId) {
            if (this.veeObserver) {
                this.veeObserver.unobserve(oldId, this)
                this.veeObserver.observe(this)
            }
        },
    },
    created() {
        if (this.veeObserver) {
            this.veeObserver.observe(this)
        }
    },
    beforeUnmount() {
        if (this.veeObserver) {
            this.veeObserver.unobserve(this.id, this)
        }
    },
    methods: {
        getValue() {
            const value = findModelValue(this.$.subTree)

            return value === NOT_FOUND ? undefined : value
        },
        async validate() {
            // Make sure the rendered tree reflects latest model values
            await nextTick()

            const errors = validateValue(this.getValue(), this.rules, {
                name: this.name || this.id,
                resolveTarget: (target) => {
                    const provider = this.veeObserver && this.veeObserver.getProvider(target)

                    return provider ? provider.getValue() : undefined
                },
            })

            this.errors = errors
            this.validated = true

            return { valid: errors.length === 0, errors }
        },
        setErrors(errors) {
            this.errors = typeof errors === 'string' ? [errors] : [...(errors || [])]
        },
        reset() {
            this.errors = []
            this.validated = false
        },
        onInteraction() {
            if (this.mode === 'passive') return

            this.validate()
        },
    },
    render() {
        const slotProps = {
            errors: this.errors,
            valid: !this.invalid,
            invalid: this.invalid,
            validated: this.validated,
            validate: this.validate,
            reset: this.reset,
        }

        const children = this.$slots.default ? this.$slots.default(slotProps) : []

        const listeners = this.mode === 'passive' ? {} : { onInput: this.onInteraction, onChange: this.onInteraction }

        return h(this.tag, listeners, children)
    },
}

export default ValidationProvider
