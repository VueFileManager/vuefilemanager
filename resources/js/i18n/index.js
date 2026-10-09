import { createI18n } from 'vue-i18n'

// Legacy API mode keeps `this.$t()` / `this.$i18n` working in Options API components.
// Messages are loaded at runtime from the backend (see store/modules/app.js -> getLanguageTranslations).
const i18n = createI18n({
    legacy: true,
    locale: config.locale,
    silentTranslationWarn: true,
    silentFallbackWarn: true,
    missingWarn: false,
    fallbackWarn: false,
    warnHtmlInMessage: 'off',
    warnHtmlMessage: false,
})

export default i18n
