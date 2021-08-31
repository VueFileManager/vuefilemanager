<template>
    <div class="mobile-toolbar">

        <!-- Go back-->
		<div @click="goBack" class="go-back-button">
            <chevron-left-icon size="17" class="icon-back" :class="{'is-visible': isLoadedFolder }" />

			<!--Folder Title-->
			<div class="directory-name">
				{{ $getCurrentLocationName() }}
			</div>
        </div>

        <!--More Actions-->
        <div class="more-actions-button">
            <div v-if="$checkPermission('master')" @click="showMobileNavigation" class="tap-area">
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
                'FilePreviewType',
                'currentFolder',
            ]),
			isLoadedFolder() {
				return this.$route.params.id
			},
        },
        methods: {
            showMobileNavigation() {
                events.$emit('mobile-menu:show', 'user-navigation')
                events.$emit('mobile-select:stop')
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

    .mobile-toolbar {
        background: white;
        text-align: center;
        display: none;
        padding: 10px 0;
        position: sticky;
        top: 0;
        z-index: 6;

        > div {
            width: 100%;
            flex-grow: 1;
            align-self: center;
            white-space: nowrap;
        }

		.go-back-button {
			text-align: left;
			flex: 1;

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
            flex: 1;
            text-align: right;
            position: relative;

            .tap-area {
                display: inline-block;
                padding: 10px;
                position: absolute;
                right: -10px;
                top: -20px;

                path, line, polyline, rect, circle {
                    stroke: $text;
                }
            }
        }
    }

    @media only screen and (max-width: 960px) {

        .mobile-toolbar {
            display: flex;
        }
    }

    .dark-mode {

        .mobile-toolbar {
            background: $dark_mode_background;

            .directory-name {
                color: $dark_mode_text_primary;
            }

            .more-actions-button .tap-area {

                path, line, polyline, rect, circle {
                    stroke: $dark_mode_text_primary;
                }
            }
        }
    }
</style>
