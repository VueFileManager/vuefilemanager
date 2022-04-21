<template>
    <div
        class="sticky top-14 z-[19] whitespace-nowrap bg-white dark:bg-dark-background lg:hidden"
    >
		<div class="flex items-center overflow-x-auto pb-3 pl-4">
			<!--Show Buttons-->
			<slot v-if="!isMultiSelectMode" />

			<!-- Multi select mode -->
			<div v-if="isMultiSelectMode">
				<MobileActionButton @click.native="selectAll" icon="check-square">
					{{ $t('select_all') }}
				</MobileActionButton>
				<MobileActionButton @click.native="deselectAll" icon="x-square">
					{{ $t('deselect_all') }}
				</MobileActionButton>
				<MobileActionButton @click.native="disableMultiSelectMode" icon="check">
					{{ $t('done') }}
				</MobileActionButton>
			</div>
		</div>

        <!--Upload Progressbar-->
        <UploadProgress class="pt-3 pl-4" />
    </div>
</template>

<script>
import MobileActionButton from '../UI/Buttons/MobileActionButton'
import UploadProgress from '../UI/Others/UploadProgress'
import { mapGetters } from 'vuex'

export default {
    name: 'FileActionsMobile',
    components: {
        MobileActionButton,
        UploadProgress,
    },
    computed: {
        ...mapGetters(['isMultiSelectMode']),
    },
    methods: {
        selectAll() {
            this.$store.commit('ADD_ALL_ITEMS_TO_CLIPBOARD')
        },
        deselectAll() {
            this.$store.commit('CLIPBOARD_CLEAR')
        },
        disableMultiSelectMode() {
            this.$store.commit('TOGGLE_MULTISELECT_MODE')
        },
    },
}
</script>

<style scoped lang="scss">
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

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
</style>
