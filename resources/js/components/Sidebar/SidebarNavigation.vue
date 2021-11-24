<template>
    <nav class="menu-bar dark:bg-dark-foreground bg-light-background flex-none xl:w-20 w-16 lg:grid hidden">
        <div class="icon-navigation menu" v-if="user">

            <router-link :to="{name: 'Profile'}" class="icon-navigation-item user">
                <UserAvatar />
            </router-link>

            <router-link :to="{name: 'Files'}" :class="{'is-active': isSection('Platform')}" :title="$t('locations.home')" class="icon-navigation-item home">
                <div class="button-icon text-theme">
                    <hard-drive-icon size="19" class="text-theme" />
                </div>
            </router-link>

            <router-link :to="{name: 'Profile'}" :class="{'is-active': isSection('User')}" :title="$t('locations.profile')" class="icon-navigation-item settings">
                <div class="button-icon">
                    <user-icon size="19" />
                </div>
            </router-link>

            <router-link :to="{name: 'Dashboard'}" :class="{'is-active': isSection('Admin')}" v-if="user.data.attributes.role === 'admin'" :title="$t('locations.settings')" class="icon-navigation-item users">
                <div class="button-icon">
                    <settings-icon size="19" />
                </div>
            </router-link>

			<a @click="toggleDarkMode" :title="$t('dark_mode_toggle')" class="icon-navigation-item dark-switcher">
				<div class="button-icon">
					<sun-icon v-if="isDarkMode" size="19" />
					<moon-icon v-if="! isDarkMode" size="19" />
				</div>
			</a>
        </div>

        <!--Logout-->
        <div class="icon-navigation logout">
            <div @click="$store.dispatch('logOut')" :title="$t('locations.logout')" class="icon-navigation-item">
                <div class="button-icon">
                    <power-icon size="19" />
                </div>
            </div>
        </div>
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
				'isDarkMode',
				'user',
			])
        },
		methods: {
			toggleDarkMode() {
				this.$store.dispatch('toggleDarkMode', !this.isDarkMode)
			},
			isSection(section) {
				return this.$route.matched[0].name === section
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

	.dark-switcher {
		padding-top: 20px;
		border-top: 1px solid darken($light_mode_border, 7%);
		margin: 20px 15px 0;
	}

    .menu-bar {
        user-select: none;
        padding-top: 25px;
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

        .icon-navigation {

            .icon-navigation-item {
                margin-bottom: 15px;
            }

            .button-icon {
                padding: 8px;
            }
        }
    }

    .dark {

		.dark-switcher {
			border-color:lighten($dark_mode_foreground, 2%);
		}

        .icon-navigation {

            .button-icon {
                &:hover {
                    background: #22262b;
                }
            }
        }
    }

</style>
