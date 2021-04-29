<template>
	<PageTab class="form-fixed-width">
		<PageTabGroup class="form block-form">
			<FormLabel>Company & Logo</FormLabel>
			<div class="block-wrapper">
				<label>Logo (optional):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="avatar" v-slot="{ errors }">
					<ImageInput @input="$updateImage(`/oasis/clients/${client.id}`, 'avatar', client.avatar)" v-model="client.avatar" :image="client.avatar" :error="errors[0]" />
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>Company name:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="name" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'name', client.name)" v-model="client.name" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
		</PageTabGroup>
		<PageTabGroup class="form block-form">
			<FormLabel>Company Details</FormLabel>
			<div class="block-wrapper">
				<label>ICO:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ico" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'ico', client.ico)" v-model="client.ico" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>DIC (optional):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="dic" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'dic', client.dic)" v-model="client.dic" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>IC DPH (optional):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="ic_dph" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'ic_dph', client.ic_dph)" v-model="client.ic_dph" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
		</PageTabGroup>
		<PageTabGroup class="form block-form">
			<FormLabel>Company Address</FormLabel>
			<div class="block-wrapper">
				<label>Address:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="address" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'address', client.address)" v-model="client.address" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>City:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="city" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'city', client.city)" v-model="client.city" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>Postal Code:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="postal_code" rules="required" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'postal_code', client.postal_code)" v-model="client.postal_code" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>Country:</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="country" rules="required" v-slot="{ errors }">
					<SelectInput @input="$updateText(`/oasis/clients/${client.id}`, 'country', client.country)" v-model="client.country" :default="client.country" :options="countries" placeholder="" :isError="errors[0]" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
		</PageTabGroup>
		<PageTabGroup class="form block-form">
			<FormLabel>Contact Informations</FormLabel>
			<div class="block-wrapper">
				<label>Phone (optional):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="phone_number" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'phone_number', client.phone_number)" v-model="client.phone_number" placeholder="" type="text" :class="{'is-error': errors[0]}" class="focus-border-theme" />
					<span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
			</div>
			<div class="block-wrapper">
				<label>Email (optional):</label>
				<ValidationProvider tag="div" mode="passive" class="input-wrapper" name="email" v-slot="{ errors }">
					<input @input="$updateText(`/oasis/clients/${client.id}`, 'email', client.email)" v-model="client.email" placeholder="" type="email" :class="{'is-error': errors[0]}" class="focus-border-theme" />
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
        name: 'UserDetail',
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
        methods: {

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
