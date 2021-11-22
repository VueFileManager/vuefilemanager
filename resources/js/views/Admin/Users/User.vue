<template>
    <div>
		<div id="page-content" v-if="! isLoading">
			<!--Page Tab links-->
			<div class="card shadow-card pt-4 pb-0 sticky top-0 z-10">
				<div class="user-thumbnail">
					<div class="avatar">
						<img :src="user.data.relationships.settings.data.attributes.avatar.sm" :alt="user.data.relationships.settings.data.attributes.name">
						<!--<img :src="user.data.attributes.avatar" :alt="user.data.attributes.name" class="blurred">-->
					</div>
					<div class="info">
						<b class="name">
							{{ user.data.relationships.settings.data.attributes.name }}
							<ColorLabel color="purple">
								{{ user.data.attributes.role }}
							</ColorLabel>
						</b>
						<span class="email">{{ user.data.attributes.email }}</span>
					</div>
				</div>

				<router-link
					class="inline-block text-sm font-bold px-4 py-5 border-b-2 border-transparent border-bottom-theme"
					:class="{'text-theme': $router.currentRoute.name === page.route, 'text-gray-600': $router.currentRoute.name !== page.route}"
					v-for="(page, i) in pages"
					:to="{name: page.route}"
					:key="i"
					replace
				>
					{{ page.title }}
				</router-link>
			</div>

			<!--Router Content-->
			<router-view :user="user" @reload-user="fetchUser"/>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import {UserIcon, HardDriveIcon, LockIcon, Trash2Icon, FileTextIcon, CreditCardIcon} from 'vue-feather-icons'
    import StorageItemDetail from '/resources/js/components/Others/StorageItemDetail'
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
    import SectionTitle from '/resources/js/components/Others/SectionTitle'
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import {mapGetters} from 'vuex'
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            StorageItemDetail,
            CreditCardIcon,
            HardDriveIcon,
            SectionTitle,
            FileTextIcon,
            MobileHeader,
            PageHeader,
            ColorLabel,
            Trash2Icon,
            UserIcon,
            LockIcon,
            Spinner,
        },
        computed: {
            ...mapGetters(['config']),
            admin() {
                return this.$store.getters.user ? this.$store.getters.user : undefined
            },
        },
        data() {
            return {
                isLoading: true,
                user: undefined,
				pages: [
					{
						title: this.$t('admin_page_user.tabs.detail'),
						route: 'UserDetail',
					},
					{
						title: this.$t('admin_page_user.tabs.storage'),
						route: 'UserStorage',
					},
					{
						title: this.$t('admin_page_user.tabs.subscription'),
						route: 'UserSubscription',
					},
					{
						title: this.$t('Transactions'),
						route: 'UserInvoices',
					},
					{
						title: this.$t('admin_page_user.tabs.password'),
						route: 'UserPassword',
					},
					{
						title: this.$t('Delete Account'),
						route: 'UserDelete',
					},
				]
            }
        },
        methods: {
            fetchUser() {
                axios.get('/api/admin/users/' + this.$route.params.id)
                    .then(response => {
                        this.user = response.data
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
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

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

    .dark {
        .user-thumbnail {

            .info {

                .email {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }

</style>
