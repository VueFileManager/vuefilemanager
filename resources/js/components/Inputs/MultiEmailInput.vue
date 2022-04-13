<template>
    <div
		class="focus-border-theme input-dark focus-within-border-theme"
		:class="{'!border-rose-600':isError, '!py-3.5': !emails.length, '!px-2 !pt-[10px] !pb-0.5': emails.length}"
	>
		<div @click="$refs.input.focus()" class="flex flex-wrap cursor-text items-center" style="grid-template-columns: auto minmax(0,1fr);">
			<div
				class="whitespace-nowrap flex items-center rounded-lg bg-theme-100 mr-2 mb-2 py-1 px-2 cursor-pointer w-fit"
				@click="removeEmail(email)"
				v-for="(email, i) in emails"
				:key="i"
			>
				<small class="text-sm text-theme mr-1">
					{{ email }}
				</small>
				<x-icon class="vue-feather text-theme" size="14" />
			</div>
			<input
				@keydown.delete="removeLastEmail($event)"
				@keyup="handleEmail()"
				v-model="email"
				:size="inputSize"
				class="w-auto font-bold text-sm bg-transparent"
				:class="{'mb-2': emails.length}"
				:placeholder="placeHolder"
				autocomplete="new-password"
				ref="input"
			/>
		</div>
	</div>
</template>

<script>
import { XIcon } from 'vue-feather-icons'
import { events } from '../../bus'

export default {
    name: 'MultiEmailInput',
    components: { XIcon },
    props: ['isError', 'label'],
    computed: {
        placeHolder() {
            return !this.emails.length ? this.$t('shared_form.email_placeholder') : ''
        },
        inputSize() {
            return this.email && this.email.length > 14 ? this.email.length : 14
        },
    },
    data() {
        return {
            emails: [],
            email: undefined,
        }
    },
    methods: {
        removeEmail(email) {
            this.emails = this.emails.filter((item) => item !== email)

            // After remove email send new emails list to parent
            events.$emit('emailsInputValues', this.emails)
        },
        removeLastEmail(event) {
            // If is input empty and presse backspace remove last email from array
            if (event.code === 'Backspace' && this.email === '') this.emails.pop()
        },
        handleEmail() {
            if (! this.email.length)
				return;

			// Get index of @ and last dot
			let lastDot = this.email.lastIndexOf('.')
			let at = this.email.indexOf('@')

			// Check if is after @ some dot, if email have @ anf if dont have more like one
			if (lastDot < at || at === -1 || this.email.match(/@/g).length > 1) return

			// First email dont need to be separated by comma or space to be sended
			if (this.emails.length === 0) events.$emit('emailsInputValues', [this.email])

			// After come or backspace push the single email to array or emails
			if (this.email.includes(',') || this.email.includes(' ')) {
				let email = this.email.replace(/[","," "]/, '')

				this.email = ''

				// Push single email to aray of emails
				this.emails.push(email)

				events.$emit('emailsInputValues', this.emails)
			}
        },
    },
    created() {
        this.$nextTick(() => {
            this.$refs.input.focus()
        })
    },
}
</script>
