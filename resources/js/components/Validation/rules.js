/**
 * Validation rules + default English messages compatible with vee-validate 3
 * (`vee-validate/dist/vee-validate.full` registered every rule with its `en` locale).
 * Only the rules the app uses (plus a few trivial ones) are implemented.
 */

const isNullOrUndefined = (value) => value === null || value === undefined

const isEmptyArray = (value) => Array.isArray(value) && value.length === 0

export const isEmpty = (value) => isNullOrUndefined(value) || value === '' || isEmptyArray(value)

const emailRegex =
    /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/

const every = (value, test) => (Array.isArray(value) ? value.every(test) : test(value))

/**
 * Each rule: { validate(value, params, context) => boolean, message, params: [names], computesRequired }
 */
export const rules = {
    required: {
        computesRequired: true,
        message: 'The {_field_} field is required',
        validate(value) {
            if (isNullOrUndefined(value) || isEmptyArray(value)) {
                return false
            }

            // vee-validate 3 default: `false` is a valid value (allowFalse = true)
            return !!String(value).trim().length
        },
    },
    email: {
        message: 'The {_field_} field must be a valid email',
        validate: (value) => every(value, (val) => emailRegex.test(String(val))),
    },
    min: {
        params: ['length'],
        message: 'The {_field_} field must be at least {length} characters',
        validate(value, [length]) {
            if (isNullOrUndefined(value)) return false

            return every(value, (val) => String(val).length >= Number(length))
        },
    },
    max: {
        params: ['length'],
        message: 'The {_field_} field may not be greater than {length} characters',
        validate(value, [length]) {
            if (isNullOrUndefined(value)) return true

            return every(value, (val) => String(val).length <= Number(length))
        },
    },
    min_value: {
        params: ['min'],
        message: 'The {_field_} field must be {min} or more',
        validate(value, [min]) {
            if (isNullOrUndefined(value) || value === '') return false

            if (Array.isArray(value)) {
                return value.length > 0 && value.every((val) => Number(val) >= Number(min))
            }

            return Number(value) >= Number(min)
        },
    },
    max_value: {
        params: ['max'],
        message: 'The {_field_} field must be {max} or less',
        validate(value, [max]) {
            if (isNullOrUndefined(value) || value === '') return false

            return every(value, (val) => Number(val) <= Number(max))
        },
    },
    numeric: {
        message: 'The {_field_} field may only contain numeric characters',
        validate: (value) => every(value, (val) => /^[0-9]+$/.test(String(val)) || /^[٠-٩]+$/.test(String(val))),
    },
    is: {
        params: ['other'],
        // vee-validate 3 has no "is" message in its locale and falls back to this one
        message: '{_field_} is not valid',
        validate: (value, [other]) => value === other,
    },
    is_not: {
        params: ['other'],
        message: '{_field_} is not valid',
        validate: (value, [other]) => value !== other,
    },
    confirmed: {
        params: ['target'],
        isTarget: true,
        message: 'The {_field_} field confirmation does not match',
        validate: (value, params, { targetValue }) => String(value) === String(targetValue),
    },
}

/**
 * Parse a vee-validate rule expression: 'required|min:3|is:foo' or { required: true, min: 3 }
 */
export function parseRules(expression) {
    if (!expression) return []

    if (typeof expression === 'object') {
        return Object.keys(expression)
            .filter((name) => expression[name] !== false)
            .map((name) => {
                const params = expression[name]

                return {
                    name,
                    params: Array.isArray(params) ? params : params === true ? [] : [params],
                }
            })
    }

    return String(expression)
        .split('|')
        .filter(Boolean)
        .map((rule) => {
            const name = rule.split(':')[0]
            const params = rule.includes(':') ? rule.split(':').slice(1).join(':').split(',') : []

            return { name, params }
        })
}

export function formatMessage(rule, ruleName, field, params, value) {
    const values = { _field_: field, _value_: value, _rule_: ruleName }
    ;(rule.params || []).forEach((paramName, index) => (values[paramName] = params[index]))

    return rule.message.replace(/{([^}]+)}/g, (match, key) => (key in values ? values[key] : match))
}

/**
 * Validate a value against parsed rules. Mirrors vee-validate 3 defaults:
 * bails on the first failing rule and skips optional (non-required) empty fields.
 */
export function validateValue(value, expression, { name, resolveTarget } = {}) {
    const parsed = parseRules(expression)
    const isRequired = parsed.some(({ name }) => rules[name] && rules[name].computesRequired)

    if (!isRequired && isEmpty(value)) {
        return []
    }

    for (const { name: ruleName, params } of parsed) {
        const rule = rules[ruleName]

        if (!rule) {
            console.warn(`[validation] No such validator '${ruleName}' exists.`)
            continue
        }

        const context = {}

        if (rule.isTarget && resolveTarget) {
            context.targetValue = resolveTarget(params[0])
        }

        if (!rule.validate(value, params, context)) {
            return [formatMessage(rule, ruleName, name, params, value)]
        }
    }

    return []
}
