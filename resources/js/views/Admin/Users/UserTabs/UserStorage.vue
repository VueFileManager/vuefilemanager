<template>
    <PageTab :is-loading="isLoading" class="form-fixed-width" v-if="storage">
        <PageTabGroup v-if="config.storageLimit && ! user.data.attributes.subscription">
            <FormLabel>
                {{ $t('user_box_storage.title') }}
            </FormLabel>
            <InfoBox>
                <p>{{ $t('user_box_storage.description') }}</p>
            </InfoBox>
            <ValidationObserver ref="changeStorageCapacity" @submit.prevent="changeStorageCapacity" v-slot="{ invalid }" tag="form" class="form block-form">

                <ValidationProvider tag="div" class="block-wrapper" v-slot="{ errors }" mode="passive" name="Capacity" rules="required">
                    <label>{{ $t('admin_page_user.label_change_capacity') }}:</label>
                    <div class="single-line-form">
                        <input v-model="capacity"
                               :placeholder="$t('admin_page_user.label_change_capacity')"
                               type="number"
                               min="1"
                               max="999999999"
                               :class="{'is-error': errors[0]}"
                        />
                        <ButtonBase :loading="isSendingRequest" :disabled="isSendingRequest" type="submit" button-style="theme" class="submit-button">
                            {{ $t('admin_page_user.change_capacity') }}
                        </ButtonBase>
                    </div>
                    <span class="error-message" v-if="errors[0]">{{ errors[0] }}</span>
                </ValidationProvider>
            </ValidationObserver>
        </PageTabGroup>
        <PageTabGroup>
            <FormLabel>{{ $t('storage.sec_details') }}</FormLabel>
            <StorageItemDetail
                    type="disk"
                    :title="$t('storage.total_used', {used: storage.attributes.used})"
                    :percentage="storage.attributes.percentage"
                    :used="$t('storage.total_capacity', {capacity: storage.attributes.capacity})"
            />
            <StorageItemDetail type="images" :title="$t('storage.images')" :percentage="storage.meta.images.percentage" :used="storage.meta.images.used" />
            <StorageItemDetail type="videos" :title="$t('storage.videos')" :percentage="storage.meta.videos.percentage" :used="storage.meta.videos.used" />
            <StorageItemDetail type="audios" :title="$t('storage.audios')" :percentage="storage.meta.audios.percentage" :used="storage.meta.audios.used" />
            <StorageItemDetail type="documents" :title="$t('storage.documents')" :percentage="storage.meta.documents.percentage" :used="storage.meta.documents.used" />
            <StorageItemDetail type="others" :title="$t('storage.others')" :percentage="storage.meta.others.percentage" :used="storage.meta.others.used" />
        </PageTabGroup>
    </PageTab>
</template>

<script>
    import FormLabel from '@/components/Others/Forms/FormLabel'
    import InfoBox from '@/components/Others/Forms/InfoBox'
    import PageTabGroup from '@/components/Others/Layout/PageTabGroup'
    import PageTab from '@/components/Others/Layout/PageTab'
    import {ValidationProvider, ValidationObserver} from 'vee-validate/dist/vee-validate.full'
    import StorageItemDetail from '@/components/Others/StorageItemDetail'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import SetupBox from '@/components/Others/Forms/SetupBox'
    import {required} from 'vee-validate/dist/rules'
    import {events} from "@/bus"
    import axios from 'axios'
    import {mapGetters} from "vuex";

    export default {
        name: 'UserStorage',
        props: ['user'],
        components: {
            PageTabGroup,
            FormLabel,
            PageTab,
            InfoBox,
            ValidationProvider,
            ValidationObserver,
            StorageItemDetail,
            ButtonBase,
            SetupBox,
            required,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: true,
                isSendingRequest: false,
                capacity: undefined,
                storage: undefined,
            }
        },
        methods: {
            async changeStorageCapacity() {

                // Validate fields
                const isValid = await this.$refs.changeStorageCapacity.validate();

                if (!isValid) return;

                this.isSendingRequest = true

                // Send request to get user reset link
                axios
                    .post(this.$store.getters.api + '/users/' + this.$route.params.id + '/capacity', {
                        attributes: {
                            storage_capacity: this.capacity
                        },
                        _method: 'patch'
                    })
                    .then(() => {

                        // Reset errors
                        this.$refs.changeStorageCapacity.reset()

                        this.isSendingRequest = false

                        this.getStorageDetails()

                        events.$emit('toaster', {
                            type: 'success',
                            message: this.$t('toaster.changed_capacity'),
                        })
                    })
                    .catch(error => {

                        this.isSendingRequest = false

                        if (error.response.status == 422) {

                            // Password validation error
                            if (error.response.data.errors['attributes.storage_capacity']) {

                                this.$refs.changeStorageCapacity.setErrors({
                                    'Capacity': this.$t('errors.capacity_digit')
                                });
                            }
                        } else {

                            events.$emit('alert:open', {
                                title: this.$t('popup_error.title'),
                                message: this.$t('popup_error.message'),
                            })
                        }
                    })
            },
            getStorageDetails() {
                axios.get('/api/users/' + this.$route.params.id + '/storage')
                    .then(response => {
                        this.storage = response.data.data
                        this.isLoading = false
                    })
            }
        },
        created() {
            this.getStorageDetails()
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import '@assets/vue-file-manager/_forms';

    .block-form {
        max-width: 100%;
    }
</style>
