<template>
    <div class="sticky top-0 dark:bg-dark-background bg-white flex text-center py-4 px-4 w-full justify-between items-center z-10 md:hidden block">

        <!-- Go back-->
		<div @click="goBack" class="go-back-button flex text-left items-center">
            <chevron-left-icon size="17" class="icon-back" :class="{'is-visible': isLoadedFolder }" />

			<!--Folder Title-->
			<div class="directory-name">
				{{ $getCurrentLocationName() }}
			</div>
        </div>

        <!--More Actions-->
        <div class="more-actions-button">
            <div v-if="$checkPermission('master')" @click="showMobileNavigation" class="tap-area px-1.5">
                <menu-icon size="17" />
            </div>
        </div>
    </div>
</template>

<script>
    import ToolbarButton from '/resources/js/components/FilesView/ToolbarButton'
    import SearchBar from '/resources/js/components/FilesView/SearchBar'
    import { MenuIcon, ChevronLeftIcon } from 'vue-feather-icons'
    import {mapGetters} from 'vuex'
    import {events} from '/resources/js/bus'

    export default {
        name: 'MobileToolBar',
        components: {
            ChevronLeftIcon,
            ToolbarButton,
            SearchBar,
            MenuIcon,
        },
        computed: {
            ...mapGetters([
                'isVisibleSidebar',
                'itemViewType',
                'currentFolder',
            ]),
			isLoadedFolder() {
				return this.$route.params.id
			},
        },
        methods: {
            showMobileNavigation() {
                events.$emit('mobile-menu:show', 'user-navigation')
                this.$store.commit('DISABLE_MULTISELECT_MODE')
            },
			goBack() {
				if (this.isLoadedFolder) this.$router.back()
			},
        },
        created() {
            events.$on('show:content', () => {
                if (this.isSidebarMenu) this.isSidebarMenu = false
            })
        }
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

	.go-back-button {

		.icon-back {
			pointer-events: none;
			opacity: 0.15;
			vertical-align: middle;
			cursor: pointer;
			margin-top: -2px;
			margin-right: 4px;

			&.is-visible {
				pointer-events: initial;
				visibility: visible;
				opacity: 1;
			}
		}
	}

	.directory-name {
		line-height: 1;
		width: 100%;
		vertical-align: middle;
		@include font-size(16);
		color: $text;
		font-weight: 700;
		max-width: 220px;
		overflow: hidden;
		text-overflow: ellipsis;
		display: inline-block;
	}

	.more-actions-button {
		position: relative;

		.tap-area {

			path, line, polyline, rect, circle {
				stroke: $text;
			}
		}
	}

    .dark {

		.directory-name {
			color: $dark_mode_text_primary;
		}

		.more-actions-button .tap-area {

			path, line, polyline, rect, circle {
				stroke: $dark_mode_text_primary;
			}
		}
    }
</style>
