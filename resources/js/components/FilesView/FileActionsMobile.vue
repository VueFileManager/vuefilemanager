<template>
    <div id="mobile-actions-wrapper">

        <!--Show Buttons-->
        <div v-if="! isSelectMode" class="mobile-actions">
			<slot></slot>
        </div>

		<!-- Multi select mode -->
        <div v-if="isSelectMode" class="mobile-actions">
            <MobileActionButton @click.native="selectAll" icon="check-square">
                {{ $t('mobile_selecting.select_all') }}
            </MobileActionButton>
            <MobileActionButton @click.native="deselectAll" icon="x-square">
                {{ $t('mobile_selecting.deselect_all') }}
            </MobileActionButton>
            <MobileActionButton @click.native="disableMultiSelectMode" icon="check">
                {{ $t('mobile_selecting.done') }}
            </MobileActionButton>
        </div>

        <!--Upload Progressbar-->
        <UploadProgress />
    </div>
</template>

<script>
    import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
    import UploadProgress from '/resources/js/components/FilesView/UploadProgress'
    import {events} from '/resources/js/bus'

    export default {
        name: 'FileActionsMobile',
        components: {
            MobileActionButton,
            UploadProgress,
        },
        data() {
            return {
                isSelectMode: false,
            }
        },
        methods: {
            selectAll() {
                this.$store.commit('ADD_ALL_ITEMS_TO_CLIPBOARD')
            },
            deselectAll() {
                this.$store.commit('CLIPBOARD_CLEAR')
            },
            disableMultiSelectMode() {
                this.isSelectMode = false

                events.$emit('mobileSelecting:stop')
            },
        },
        mounted() {
            events.$on('mobileSelecting:stop', () => this.isSelectMode = false)
            events.$on('mobileSelecting:start', () => this.isSelectMode = true)
        }
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .button-enter-active,
    .button-leave-active {
        transition: all 250ms;
    }

    .button-enter {
        opacity: 0;
        transform: translateY(-50%);
    }

    .button-leave-to {
        opacity: 0;
        transform: translateY(50%);
    }

    .button-leave-active {
        position: absolute;
    }

    #mobile-actions-wrapper {
        display: none;
        background: white;
        position: sticky;
        top: 35px;
        z-index: 3;
    }

    .mobile-actions {
        white-space: nowrap;
        overflow-x: auto;
        margin: 0 -15px;
        padding: 10px 0 10px 15px;
    }

    @media only screen and (max-width: 960px) {

        #mobile-actions-wrapper {
            display: block;
        }
    }

    .dark-mode {
        #mobile-actions-wrapper {
            background: $dark_mode_background;
        }
    }
</style>
