<template>
    <nav class="pt-7 select-none dark:bg-dark-foreground bg-light-background flex-none xl:w-20 w-16 lg:grid hidden">

		<!--Navigation-->
		<div v-if="user" class="mb-auto text-center">

			<MemberAvatar
				class="mx-auto inline-block"
				:size="44"
				:is-border="false"
				:member="user"
			/>

			<!--Usage-->
			<div v-if="config.subscriptionType === 'metered'" class="text-center leading-3 mt-1">
				<b class="text-xs font-bold leading-3 block text-theme">
					{{ user.data.meta.usages.costEstimate }}
				</b>
				<span class="text-xs text-gray-500">
					{{ $t('usage') }}
				</span>
			</div>

			<!--Navigation-->
			<div class="mt-7">
				<router-link
					v-for="item in navigation"
					:to="{name: item.route}"
					:title="item.title"
					:class="[{'router-link-active': isSection(item.section)}, item.icon]"
					class="block mb-1.5"
				>
					<div class="button-icon p-3 cursor-pointer inline-block dark:hover:bg-4x-dark-foreground hover:bg-light-300 text-theme rounded-xl">
						<hard-drive-icon v-if="item.icon === 'home'" size="20" />
						<settings-icon v-if="item.icon === 'settings'" size="20" />
						<user-icon v-if="item.icon === 'user'" size="20" />
					</div>
				</router-link>
			</div>

			<!--Toggle Dark/Light mode-->
			<div @click="toggleDarkMode" :title="$t('dark_mode_toggle')" class="block mt-6">
				<div class="button-icon p-3 cursor-pointer inline-block dark:hover:bg-4x-dark-foreground hover:bg-light-300 rounded-xl">
					<sun-icon v-if="isDarkMode" size="20" />
					<moon-icon v-if="! isDarkMode" size="20" />
				</div>
			</div>
        </div>

		<!--Logout-->
        <div class="mt-auto text-center">
			<div @click="$store.dispatch('logOut')" :title="$t('locations.logout')" class="button-icon p-3 cursor-pointer inline-block dark:hover:bg-4x-dark-foreground hover:bg-light-300 rounded-xl">
				<power-icon size="20" />
			</div>
        </div>
    </nav>
</template>

<script>
	import MemberAvatar from "../FilesView/MemberAvatar";
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
			MemberAvatar,
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
				'config',
				'user',
			]),
			navigation() {

				if (this.user.data.attributes.role === 'admin') {
					return [
						{
							route: 'Files',
							section: 'Platform',
							title: this.$t('locations.home'),
							icon: 'home',
						},
						{
							route: 'Profile',
							section: 'User',
							title: this.$t('locations.profile'),
							icon: 'user',
						},
						{
							route: 'Dashboard',
							section: 'Admin',
							title: this.$t('locations.settings'),
							icon: 'settings',
						},
					]
				}

				return [
					{
						route: 'Files',
						section: 'Platform',
						title: this.$t('locations.home'),
						icon: 'home',
					},
					{
						route: 'Profile',
						section: 'User',
						title: this.$t('locations.profile'),
						icon: 'settings',
					},
				]
			}
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

	.router-link-active {

		&.home .button-icon {
			path, line, polyline, rect, circle {
				color: inherit;
			}
		}

		&.trash .button-icon {
			background: rgba($red, 0.1);

			path, line, polyline, rect, circle {
				stroke: $red;
			}
		}

		&.settings .button-icon {
			background: rgba($purple, 0.1);

			path, line, polyline, rect, circle {
				stroke: $purple;
			}
		}

		&.user .button-icon {
			background: rgba($pink, 0.1);

			path, line, polyline, rect, circle {
				stroke: $pink;
			}
		}
	}
</style>
