<template>
	<div v-if="isVisible" @keyup.esc="exit" class="spotlight-wrapper">
		<div class="spotlight-search">
			<div class="icon">
				<div v-if="isLoading" class="spinner-icon">
					<Spinner />
				</div>
				<search-icon :class="{'is-hidden': isLoading}" size="22" class="text-theme" />
			</div>
			<input v-model="query" @keydown.enter="showSelected" @keydown.meta="proceedToSelect" @keyup.down="onPageDown" @keyup.up="onPageUp" type="text" placeholder="Spotlight search..." ref="searchInput">
			<div class="input-hint">
				<span class="title">esc</span>
			</div>
		</div>
		<div v-if="results.length !== 0" class="spotlight-results">
			<div v-for="(item, i) in results" :key="item.id" class="result-item">
				<FileItemList
					:item="item"
					class="file-item"
					:class="{'is-clicked': i === index}"
					:disable-highlight="true"
				/>
				<div class="input-hint">
					<span class="title">{{ i === 0 ? '↵' : getSystemMetaKeyIcon() + i}}</span>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import FileItemList from '@/components/FilesView/FileItemList'
import Spinner from '@/components/FilesView/Spinner'
import {SearchIcon} from 'vue-feather-icons'
import {mapGetters} from 'vuex'
import {events} from '@/bus'
import {debounce} from 'lodash';
import axios from "axios";

export default {
	name: 'Spotlight',
	components: {
		FileItemList,
		SearchIcon,
		Spinner,
	},
	watch: {
		query(val) {
			if (val === '' || typeof val === 'undefined') {
				this.results = []
			}

			this.index = 0
			this.searchFiles(val)
		}
	},
	data() {
		return {
			index: 0,
			query: '',
			isVisible: false,
			isLoading: false,
			results: [],
		}
	},
	methods: {
		proceedToSelect(e) {
			// Preserve select and reload shortcut
			if (! ['a', 'r'].includes(e.key)) {
				e.preventDefault()
			}

			// Allow only numbers within result range
			if (Number.isInteger(parseInt(e.key)) && parseInt(e.key) < this.results.length) {
				this.index = parseInt(e.key)

				this.showSelected()
			}
		},
		showSelected() {
			// Show folder
			if (this.results[this.index].type === 'folder') {
				this.$store.dispatch('getFolder', [{ folder: this.results[this.index], back: true, init: false }])
			}

			// Show file
			if (this.results[this.index].type !== 'folder'){
				this.$store.commit('CLIPBOARD_CLEAR')
				this.$store.commit('CLIPBOARD_REPLACE', [this.results[this.index]])

				events.$emit('file-preview:show')
			}

			this.exit()
		},
		onPageDown() {
			if (typeof this.index === 'undefined') {
				this.index = 0
			} else {
				if (this.index < (this.results.length - 1)) this.index++
			}
		},
		onPageUp() {

			if (this.index > 0) this.index--
		},
		searchFiles: debounce(function (value) {

			// Prevent empty searching
			if (value === '' || typeof value === 'undefined') return

			this.isLoading = true

			// Get route
			let route = undefined

			if (this.$store.getters.sharedDetail) {
				let permission = this.$store.getters.sharedDetail.is_protected
					? 'private'
					: 'public'

				route = `/api/browse/search/${permission}/${router.currentRoute.params.token}`

			} else {
				route = '/api/browse/search'
			}

			axios
				.get(route, {
					params: {query: value}
				})
				.then(response => {
					this.results = response.data
				})
				.catch(() => this.$isSomethingWrong())
				.finally(() => this.isLoading = false)

		}, 300),
		exit() {
			this.results = []
			this.query = ''
			this.isVisible = false
			events.$emit('popup:close')
		},
		getSystemMetaKeyIcon() {
			return this.$isApple() ? '⌘' : '⊞'
		}
	},
	created() {
		events.$on('spotlight:show', () => {
			this.isVisible = true

			this.$nextTick(() => this.$refs.searchInput.focus())
		})

		events.$on('spotlight:hide', () => this.exit())
	}
}
</script>

<style lang="scss" scoped>
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';

.spotlight-wrapper {
	position: absolute;
	z-index: 99;
	top: 10%;
	left: 0;
	right: 0;
	bottom: 10%;
	overflow-y: auto;
}

.spotlight-results {
	width: 590px;
	margin: -8px auto 0;
	background: white;
	padding: 10px 10px 10px;
	border-bottom-left-radius: 8px;
	border-bottom-right-radius: 8px;
	border-top: 1px solid $light_mode_border;

	.result-item {
		position: relative;

		.is-clicked {
			border-radius: 8px;
			background: $light_background;
		}
	}

	.input-hint {
		position: absolute;
		right: 15px;
		top: 50%;
		@include transform(translateY(-50%));
	}
}

.spotlight-search {
	width: 590px;
	background: white;
	border-radius: 8px;
	margin: 0 auto 0;
	padding: 20px 25px;
	display: flex;
	align-items: center;
	position: sticky;
	top: 0;
	z-index: 99;

	.icon {
		position: relative;
		margin-right: 10px;

		.is-hidden {
			opacity: 0;
		}

		svg {
			vertical-align: middle;

			circle, line {
				color: inherit;
			}
		}

		.spinner-icon {
			@include transform(scale(0.5) translateY(23px));
		}
	}

	input {
		width: 100%;
		border: none;
		color: $text;
		@include font-size(19);
		font-weight: 600;
		background: transparent;

		&::placeholder {
			color: $text;
		}
	}
}

.input-hint .title {
	color: $text-muted;
	@include font-size(13);
}

.dark-mode {
	.spotlight-search {
		background: $dark_mode_foreground;

		input {
			color: $dark_mode_text_primary;

			&::placeholder {
				color: $dark_mode_text_primary;
			}
		}
	}

	.spotlight-results {
		border-color: $dark_mode_border_color;
		background: $dark_mode_foreground;

		.is-clicked {
			background: lighten($dark_mode_foreground, 3%);
		}
	}

	.input-hint .title {
		color: $dark_mode_text_secondary;
	}
}

</style>
