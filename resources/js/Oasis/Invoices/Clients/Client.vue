<template>
    <div id="single-page">
        <div id="page-content" v-if="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title" />
            <PageHeader :can-back="true" :title="$router.currentRoute.meta.title" />
            <div class="content-page">

                <!--Client thumbnail-->
                <div class="user-thumbnail">
                    <div class="avatar">
                        <img :src="client.avatar" :alt="client.name">
                    </div>
                    <div class="info">
                        <b class="name">{{ client.name }}</b>
                        <span class="email">{{ client.email }}</span>
                    </div>
                </div>

				<!--Page Tab links-->
                <div class="menu-list-wrapper horizontal">
                    <router-link replace :to="{name: 'ClientDetail'}" class="menu-list-item link border-bottom-theme">
                        <div class="icon text-theme">
                            <user-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_page_user.tabs.detail') }}
                        </div>
                    </router-link>

                    <router-link replace :to="{name: 'ClientInvoices'}" class="menu-list-item link border-bottom-theme">
                        <div class="icon text-theme">
                            <file-text-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            {{ $t('admin_page_user.tabs.invoices') }}
                        </div>
                    </router-link>

					<router-link replace :to="{name: 'UserDelete'}" class="menu-list-item link border-bottom-theme">
                        <div class="icon text-theme">
                            <trash2-icon size="17" />
                        </div>
                        <div class="label text-theme">
                            Delete Client
                        </div>
                    </router-link>
                </div>

				<!--Router Content-->
                <router-view :client="client" @reload-user="fetchUser" />
            </div>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner />
        </div>
    </div>
</template>

<script>
    import {UserIcon, Trash2Icon, FileTextIcon} from 'vue-feather-icons'
	import MobileHeader from '@/components/Mobile/MobileHeader'
	import SectionTitle from '@/components/Others/SectionTitle'
	import PageHeader from '@/components/Others/PageHeader'
	import Spinner from '@/components/FilesView/Spinner'
	import axios from 'axios'

	export default {
		name: 'Client',
		components: {
			SectionTitle,
			FileTextIcon,
			MobileHeader,
			PageHeader,
			Trash2Icon,
			UserIcon,
			Spinner,
		},
		data() {
			return {
				isLoading: true,
				client: undefined,
			}
		},
		methods: {
			fetchUser() {
				axios.get(`/api/oasis/clients/${this.$route.params.id}`)
					.then(response => {
						this.client = response.data
					})
					.finally(() => {
						this.isLoading = false
					})
			}
		},
		created() {
			this.fetchUser()
		}
	}
</script>

<style lang="scss" scoped>
    @import '@assets/vuefilemanager/_variables';
	@import '@assets/vuefilemanager/_mixins';

	.user-thumbnail {
		display: flex;
		align-items: center;
		cursor: pointer;
		padding-bottom: 10px;
		padding-top: 15px;

		.avatar {
			margin-right: 20px;
			position: relative;

			img {
				line-height: 0;
				width: 62px;
				height: 62px;
				border-radius: 12px;
				z-index: 1;
				position: relative;

				&.blurred {
					@include blurred-image;
					top: 0;
				}
			}
		}

		.info {

			.name {
				display: block;
				@include font-size(17);
				line-height: 1;
			}

			.email {
				color: $text-muted;
				@include font-size(14);
			}
		}
	}

	@media only screen and (max-width: 960px) {

	}

	@media (prefers-color-scheme: dark) {
		.user-thumbnail {

			.info {

				.email {
					color: $dark_mode_text_secondary;
				}
			}
		}
	}

</style>
