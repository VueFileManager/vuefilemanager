import { h } from 'vue'

/**
 * Minimal Vue 3 replacement for vee-validate 3 <ValidationObserver>.
 *
 * Supported API:
 *  - props: tag (default 'span'), disabled
 *  - slot props: invalid, valid, errors, validate, handleSubmit, passes, reset
 *  - methods (via ref): validate() => Promise<boolean>, setErrors({ field: [msg] }), reset()
 *  - listeners like `@submit.prevent` fall through to the root element (e.g. <form>)
 */
const ValidationObserver = {
    name: 'ValidationObserver',
    provide() {
        return {
            $_veeObserver: this,
        }
    },
    inject: {
        $_parentObserver: {
            from: '$_veeObserver',
            default: null,
        },
    },
    props: {
        tag: { type: String, default: 'span' },
        disabled: { type: Boolean, default: false },
    },
    data() {
        return {
            // bumped whenever providers register/unregister, so computed values re-evaluate
            registryVersion: 0,
        }
    },
    computed: {
        errors() {
            this.registryVersion

            return this.providerList().reduce((errors, provider) => {
                errors[provider.id] = provider.errors
                return errors
            }, {})
        },
        invalid() {
            this.registryVersion

            return (
                this.providerList().some((provider) => provider.errors.length > 0) ||
                this.observerList().some((observer) => observer.invalid)
            )
        },
    },
    created() {
        // Non reactive registries (component instances must not be made reactive)
        this._providers = new Map()
        this._observers = new Set()

        if (this.$_parentObserver) {
            this.$_parentObserver.observeObserver(this)
        }
    },
    beforeUnmount() {
        if (this.$_parentObserver) {
            this.$_parentObserver.unobserveObserver(this)
        }
    },
    methods: {
        providerList() {
            return [...this._providers.values()]
        },
        observerList() {
            return [...this._observers]
        },
        observe(provider) {
            this._providers.set(provider.id, provider)
            this.registryVersion++
        },
        unobserve(id, provider) {
            if (this._providers.get(id) === provider) {
                this._providers.delete(id)
                this.registryVersion++
            }
        },
        observeObserver(observer) {
            this._observers.add(observer)
            this.registryVersion++
        },
        unobserveObserver(observer) {
            this._observers.delete(observer)
            this.registryVersion++
        },
        getProvider(id) {
            return this._providers.get(id)
        },
        async validate() {
            const results = await Promise.all([
                ...this.providerList()
                    .filter((provider) => !provider.disabled)
                    .map((provider) => provider.validate().then((result) => result.valid)),
                ...this.observerList()
                    .filter((observer) => !observer.disabled)
                    .map((observer) => observer.validate()),
            ])

            return results.every((valid) => valid)
        },
        async handleSubmit(callback) {
            const isValid = await this.validate()

            if (isValid && callback) {
                return callback()
            }
        },
        setErrors(errors) {
            Object.keys(errors).forEach((key) => {
                const provider = this._providers.get(key)

                if (provider) {
                    provider.setErrors(errors[key] || [])
                }
            })

            this.observerList().forEach((observer) => observer.setErrors(errors))
        },
        reset() {
            this.providerList().forEach((provider) => provider.reset())
            this.observerList().forEach((observer) => observer.reset())
        },
    },
    render() {
        const slotProps = {
            invalid: this.invalid,
            valid: !this.invalid,
            errors: this.errors,
            validate: this.validate,
            handleSubmit: this.handleSubmit,
            passes: this.handleSubmit,
            reset: this.reset,
        }

        return h(this.tag, null, this.$slots.default ? this.$slots.default(slotProps) : [])
    },
}

export default ValidationObserver
