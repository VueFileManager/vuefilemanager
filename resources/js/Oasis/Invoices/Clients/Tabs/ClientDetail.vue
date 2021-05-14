<template>
	<PageTab class="form-fixed-width">
		<PageTabGroup class="form block-form">
			<FormLabel>{{ $t('in.form.company_and_logo') }}</FormLabel>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.client_logo') }} ({{ $t('global.optional') }}):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="avatar" v-slot="{ errors }">
					<ImageInput @input="$updateImage(`/v1/invoicing/clients/${client.id}`, 'avatar', client.avatar)" v-model="client.avatar" :image="client.avatar" :error="errors[0]" />
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.company_name') }}:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="name" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'name', client.name)" v-model="client.name" :placeholder="$t('in_editor.plac.client_company')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
		</PageTabGroup>
		<PageTabGroup class="form block-form">
			<FormLabel>{{ $t('in.form.company_details') }}</FormLabel>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.ico') }}:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ico" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'ico', client.ico)" v-model="client.ico" :placeholder="$t('in_editor.plac.client_ico')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.dic') }} ({{ $t('global.optional') }}):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="dic" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'dic', client.dic)" v-model="client.dic" :placeholder="$t('in_editor.plac.client_dic')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.ic_dph') }} ({{ $t('global.optional') }}):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ic_dph" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'ic_dph', client.ic_dph, true)" v-model="client.ic_dph" :placeholder="$t('in_editor.plac.client_ic_dph')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
		</PageTabGroup>
		<PageTabGroup class="form block-form">
			<FormLabel>{{ $t('in.form.company_address') }}</FormLabel>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.client_address') }}:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="address" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'address', client.address)" v-model="client.address" :placeholder="$t('in_editor.plac.client_address')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.client_city') }}:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="city" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'city', client.city)" v-model="client.city" :placeholder="$t('in_editor.plac.client_city')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.client_postal_code') }}:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="postal_code" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'postal_code', client.postal_code)" v-model="client.postal_code" :placeholder="$t('in_editor.plac.client_postal_code')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.client_country') }}:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="country" rules="required" v-slot="{ errors }">
					<SelectInput @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'country', client.country)" v-model="client.country" :default="client.country" :options="countries" :placeholder="$t('in_editor.plac.client_country')" :isError="errors[0]" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
		</PageTabGroup>
		<PageTabGroup class="form block-form">
			<FormLabel>{{ $t('in.form.contact_info') }}</FormLabel>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.client_phone') }} ({{ $t('global.optional') }}):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="phone_number" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'phone_number', client.phone_number, true)" v-model="client.phone_number" :placeholder="$t('in_editor.plac.client_phone')" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>{{ $t('in_editor.client_email') }} ({{ $t('global.optional') }}):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" v-slot="{ errors }">
					<input @input="$updateText(`/v1/invoicing/clients/${client.id}`, 'email', client.email, true)" v-model="client.email" :placeholder="$t('in_editor.plac.client_email')" type="email" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
		</PageTabGroup>
	</PageTab>
</template>

<script>
	import ImageInput from '@/components/Others/Forms/ImageInput'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import SelectInput from '@/components/Others/Forms/SelectInput'
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
	import {mapGetters} from "vuex";

    export default {
        name: 'ClientDetail',
        props: [
            'client'
        ],
		computed: {
			...mapGetters([
				'countries'
			]),
		},
        components: {
			ImageInput,
            PageTabGroup,
            PageTab,
            InfoBox,
            FormLabel,
            ValidationProvider,
            ValidationObserver,
            StorageItemDetail,
            SelectInput,
            ButtonBase,
            SetupBox,
            required,
        },
        data() {
            return {
                isLoading: false,
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';
    @import '@assets/vuefilemanager/_forms';

    .block-form {
        max-width: 100%;
    }

</style>
