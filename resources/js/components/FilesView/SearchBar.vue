<template>
    <div @click="showSpotlight" class="search-bar">
        <div class="message">
			<span>
				{{ $t('inputs.placeholder_search_files') }}
			</span>
			<span>
				{{ metaKeyIcon }}+K
			</span>
		</div>
    </div>
</template>

<script>
    import {SearchIcon} from 'vue-feather-icons'
	import {events} from '@/bus'

    export default {
        name: 'SearchBar',
        components: {
            SearchIcon,
        },
		computed: {
			metaKeyIcon() {
				return this.$isApple() ? '⌘' : '⊞'
			},
		},
		methods: {
			showSpotlight() {
				events.$emit('spotlight:show')
			}
		}
    }
</script>

<style scoped lang="scss">
    @import '@assets/vuefilemanager/_variables';
    @import '@assets/vuefilemanager/_mixins';

    .search-bar {
        position: relative;
		background: $light_background;
		border-radius: 8px;
		cursor: pointer;

        .message {
            border-radius: 8px;
            padding: 11px 20px;
            min-width: 300px;
			text-align: left;
			display: flex;
			justify-content: space-between;

			span {
				font-weight: 400;
				@include font-size(14);
				color: #B9B9B9;
			}
        }
    }

    @media only screen and (max-width: 1024px) {

        .search-bar .message {
            max-width: 190px;
        }
    }

    @media only screen and (max-width: 690px) {

        .search-bar .message {
			min-width: initial;
			width: 100%;
			max-width: initial;
		}
    }

    .dark-mode {
        .search-bar {
			background: $dark_mode_foreground;

			.message span {
                color: $dark_mode_text_secondary;
            }
        }
    }
</style>
