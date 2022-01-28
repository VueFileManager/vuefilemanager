<template>
	<AuthContentWrapper>
		<AuthContent name="password" :visible="true">
			<Headline
				:title="$t('page_shared.title')"
				:description="$t('page_shared.subtitle')"
			/>
			<ValidationObserver @submit.prevent="authenticateProtected" ref="authenticateProtected" v-slot="{ invalid }" tag="form" class="md:flex items-start md:space-x-4 md:space-y-0 space-y-4 mb-12">
				<ValidationProvider tag="div" mode="passive" class="w-full text-left" name="Password" rules="required" v-slot="{ errors }">
					<input v-model="password" :placeholder="$t('page_shared.placeholder_pass')" type="password" class="font-bold px-5 py-3.5 dark:bg-2x-dark-foreground bg-light-background w-full rounded-lg focus-border-theme appearance-none border border-transparent" :class="{'border-red': errors[0]}" />
					<span class="text-red-600 text-xs text-left" v-if="errors[0]">{{ errors[0] }}</span>
				</ValidationProvider>
				<AuthButton class="md:w-min w-full justify-center" icon="chevron-right" :text="$t('page_shared.submit')" :loading="isLoading" :disabled="isLoading" />
			</ValidationObserver>
		</AuthContent>
	</AuthContentWrapper>
</template>

<script>
	import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
	import AuthContentWrapper from "../../components/Auth/AuthContentWrapper"
    import AuthContent from "../../components/Auth/AuthContent";
    import AuthButton from "../../components/Auth/AuthButton";
	import Headline from "../Auth/Headline";
    import {mapGetters} from "vuex";
    import axios from "axios";

    export default {
        name: 'SharedAuthentication',
        components: {
            ValidationObserver,
            ValidationProvider,
			AuthContentWrapper,
            AuthContent,
            AuthButton,
			Headline,
        },
        computed: {
            ...mapGetters([
                'config',
            ]),
        },
        data() {
            return {
                password: '',
                isLoading: false,
            }
        },
        methods: {
            async authenticateProtected() {

                // Validate fields
                const isValid = await this.$refs.authenticateProtected.validate();

                if (!isValid) return;

                // Start loading
                this.isLoading = true

                // Send request to get verify account
                axios
                    .post('/api/browse/authenticate/' + this.$route.params.token, {
                        password: this.password
                    })
                    .then(response => {

                        // Show file browser
                        if (response.data.data.attributes.type === 'folder' && this.$router.currentRoute.name !== 'Public') {
							this.$router.replace({name: 'Public', params: {token: this.$route.params.token, id: response.data.data.attributes.item_id}})
						}

                        // Show single file
                        if (response.data.data.attributes.type !== 'folder' && this.$router.currentRoute.name !== 'SharedSingleFile') {
                            this.$router.push({name: 'SharedSingleFile'})
                        }
                    })
                    .catch(error => {

                        if (error.response.status === 401)
                            this.$refs.authenticateProtected.setErrors({
                                'Password': [error.response.data]
                            });
                    })
                    .finally(() => {
                        this.isLoading = false
                    })
            },
        },
    }
</script>