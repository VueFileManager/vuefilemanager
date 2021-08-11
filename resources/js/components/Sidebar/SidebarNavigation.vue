<template>
    <nav class="menu-bar">

        <!--Navigation Icons-->
        <div class="icon-navigation menu" v-if="user">

            <router-link :to="{name: 'Profile'}" class="icon-navigation-item user">
                <UserAvatar />
            </router-link>

            <router-link :to="{name: 'Files'}" :title="$t('locations.home')" class="icon-navigation-item home">
                <div class="button-icon text-theme">
                    <hard-drive-icon size="19" class="text-theme" />
                </div>
            </router-link>

            <router-link :to="{name: 'Profile'}" :class="{'is-active': isUserProfileRoute}" :title="$t('locations.profile')" class="icon-navigation-item settings">
                <div class="button-icon">
                    <user-icon size="19" />
                </div>
            </router-link>

            <router-link v-if="user.data.attributes.role === 'admin'" :to="{name: 'Dashboard'}" :class="{'is-active': $isThisRoute($route, adminRoutes)}" :title="$t('locations.settings')" class="icon-navigation-item users">
                <div class="button-icon">
                    <settings-icon size="19" />
                </div>
            </router-link>

			<a @click="toggleDarkMode" :title="$t('dark_mode_toggle')" class="icon-navigation-item dark-mode-switcher">
				<div class="button-icon">
					<sun-icon v-if="isDarkMode" size="19" />
					<moon-icon v-if="! isDarkMode" size="19" />
				</div>
			</a>
        </div>

        <!--Logout-->
        <ul class="icon-navigation logout">
            <li @click="$store.dispatch('logOut')" :title="$t('locations.logout')" class="icon-navigation-item">
                <div class="button-icon">
                    <power-icon size="19" />
                </div>
            </li>
        </ul>
    </nav>
</template>

<script>
    import UserAvatar from '/resources/js/components/Others/UserAvatar'
    import {mapGetters} from 'vuex'
    import {
    	MoonIcon,
    	SunIcon,
        HardDriveIcon,
        SettingsIcon,
        Trash2Icon,
        UserIcon,
        PowerIcon,
        ShareIcon,
    } from 'vue-feather-icons'

    export default {
        name: 'SidebarNavigation',
        components: {
            HardDriveIcon,
            SettingsIcon,
            UserAvatar,
            Trash2Icon,
            PowerIcon,
            ShareIcon,
            UserIcon,
			MoonIcon,
			SunIcon,
        },
        computed: {
            ...mapGetters([
				'user',
				'isDarkMode'
			]),
            isUserProfileRoute() {
                return this.$isThisRoute(this.$route, ['Profile', 'Password', 'Storage', 'Invoice', 'Subscription', 'PaymentMethods'])
            }
        },
        data() {
            return {
                adminRoutes: [
                    'AppSettings',
                    'AppAppearance',
                    'AppBillings',
                    'AppEmail',
                    'AppOthers',
                    'Dashboard',
                    'PlanSubscribers',
                    'PlanCreate',
                    'PlanSettings',
                    'PlanDelete',
                    'UserSubscription',
                    'UserInvoices',
                    'UserPassword',
                    'UserStorage',
                    'UserDelete',
                    'PlanCreate',
                    'UserCreate',
                    'AppPayments',
                    'PageEdit',
                    'Pages',
                    'UserDelete',
                    'UserDetail',
                    'Invoices',
                    'Gateways',
                    'Gateway',
                    'Plans',
                    'Users',
                    'User',
                ],
            }
        },
		methods: {
			toggleDarkMode() {
				this.$store.dispatch('toggleDarkMode', !this.isDarkMode)
			}
		},
        mounted() {
            this.$store.dispatch('getAppData')
        }
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

	.dark-mode-switcher {
		padding-top: 20px;
		border-top: 1px solid darken($light_mode_border, 7%);
		margin: 20px 15px 0;
	}

    .menu-bar {
        background: $light_background;
        user-select: none;
        padding-top: 25px;
        display: grid;
        flex: 0 0 72px;
    }

    .icon-navigation {
        text-align: center;

        &.menu {
            margin-bottom: auto;
        }

        &.logout {
            margin-top: auto;
        }

        .icon-navigation-item {
            display: block;
            margin-bottom: 10px;

            &.user {
                margin-bottom: 20px;
                display: block;
            }
        }

        .button-icon {
            cursor: pointer;
            border-radius: 4px;
            padding: 12px;
            display: inline-block;
            line-height: 0;
            @include transition(150ms);

            &:hover {
                background: darken($light_background, 5%);
            }

            path, line, polyline, rect, circle {
                @include transition(150ms);
            }
        }

        .router-link-active,
        .is-active  {

            &.home {
                .button-icon {

                    path, line, polyline, rect, circle {
                        color: inherit;
                    }
                }
            }

            &.trash {
                .button-icon {
                    background: rgba($red, 0.1);

                    path, line, polyline, rect, circle {
                        stroke: $red;
                    }
                }
            }

            &.settings {
                .button-icon {
                    background: rgba($purple, 0.1);

                    path, line, polyline, rect, circle {
                        stroke: $purple;
                    }
                }
            }

            &.users {
                .button-icon {
                    background: rgba($pink, 0.1);

                    path, line, polyline, rect, circle {
                        stroke: $pink;
                    }
                }
            }
        }
    }

    @media only screen and (max-width: 1024px) {
        .menu-bar {
            flex: 0 0 60px;
        }

        .icon-navigation {

            .icon-navigation-item {
                margin-bottom: 15px;
            }

            .button-icon {
                padding: 8px;
            }
        }
    }

    @media only screen and (max-width: 690px) {
        .menu-bar {
            display: none;
        }
    }

    .dark-mode {

		.dark-mode-switcher {
			border-color:lighten($dark_mode_foreground, 2%);
		}

        .icon-navigation {

            .button-icon {
                &:hover {
                    background: #22262b;
                }
            }
        }

        .menu-bar {
            background: #181a1b;
        }
    }

</style>
