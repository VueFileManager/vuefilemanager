<template>
	<div v-if="isVisible" @keyup.esc="exit" id="spotlight" tabindex="-1">
		<div class="spotlight-wrapper">

			<!--Query bar-->
			<div class="spotlight-search">
				<div class="magnify-icon">
					<div v-if="isLoading" class="spinner-icon">
						<Spinner />
					</div>
					<search-icon :class="{'is-hidden': isLoading}" size="22" class="magnify text-theme" />
				</div>
				<input v-model="query" @keydown.enter="showSelected" @keydown.meta="proceedToSelect" @keyup.down="onPageDown" @keyup.up="onPageUp" type="text" placeholder="Spotlight search..." ref="searchInput">
				<div v-if="! $isMobile()" class="input-hint">
					<span class="title keyboard-hint">esc</span>
				</div>
				<div v-if="$isMobile()" @click="exit" class="close-icon">
					<x-icon size="22" class="close"/>
				</div>
			</div>

			<div v-if="isEmptyQuery" class="spotlight-results">

				<!--Show results-->
				<div v-if="results.length !== 0" v-for="(item, i) in results" :key="item.data.id" class="result-item">
					<FileItemList
						:item="item"
						class="file-item"
						:class="{'is-clicked': i === index}"
						:disable-highlight="true"
						@click.native="exit"
					/>
					<div v-if="! $isMobile()" class="input-hint">
						<span class="title">{{ i === 0 ? '↵' : metaKeyIcon + i}}</span>
					</div>
				</div>

				<!--Show Empty message-->
				<div v-if="results.length === 0">
					<span class="message">{{ $t('messages.nothing_was_found') }}</span>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import FileItemList from '/resources/js/components/FilesView/ItemHandler'
import Spinner from '/resources/js/components/FilesView/Spinner'
import {SearchIcon, XIcon} from 'vue-feather-icons'
import {events} from '/resources/js/bus'
import {debounce} from 'lodash';
import axios from "axios";

export default {
	name: 'Spotlight',
	components: {
		FileItemList,
		SearchIcon,
		Spinner,
		XIcon,
	},
	computed: {
		metaKeyIcon() {
			return this.$isApple() ? '⌘' : '⊞'
		},
		isEmptyQuery() {
			return this.query !== ''
		}
	},
	watch: {
		query(val) {
			if (val === '' || typeof val === 'undefined')
				this.results = []

			this.index = 0
			this.searchFiles(val)
		}
	},
	data() {
		return {
			isVisible: false,
			isLoading: false,
			results: [],
			query: '',
			index: 0,
		}
	},
	methods: {
		proceedToSelect(e) {
			// Preserve select and reload native shortcut
			if (! ['a', 'r', 'v'].includes(e.key)) {
				e.preventDefault()
			}

			// Allow only numbers within result range
			if (Number.isInteger(parseInt(e.key)) && parseInt(e.key) < this.results.length) {
				this.index = parseInt(e.key)

				this.showSelected()
			}
		},
		showSelected() {

			let file = this.results[this.index]

			// Show folder
			if (file.data.type === 'folder') {
				this.$router.push({name: 'Files', params: {id: this.results[this.index].data.id}})
			} else {

				// Show file
				if (['video', 'audio', 'image'].includes(file.data.type) || file.data.attributes.mimetype === 'pdf'){
					this.$store.commit('ADD_TO_FAST_PREVIEW', this.results[this.index])

					events.$emit('file-preview:show')
				} else {
					this.$downloadFile(file.data.attributes.file_url, file.data.attributes.name + '.' + file.data.attributes.mimetype)
				}
			}

			this.exit()
		},
		onPageDown() {
			if (this.index < (this.results.length - 1))
				this.index++
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
				let permission = this.$store.getters.sharedDetail.data.attributes.protected
					? 'private'
					: 'public'

				route = `/api/browse/search/${permission}/${this.$router.currentRoute.params.token}`

			} else {
				route = '/api/browse/search'
			}

			axios
				.get(route, {
					params: {query: value}
				})
				.then(response => {
					let files = response.data.files.data
					let folders = response.data.folders.data

					this.results = folders.concat(files)
				})
				.catch(() => this.$isSomethingWrong())
				.finally(() => this.isLoading = false)

		}, 150),
		exit() {
			this.results = []
			this.query = ''
			this.isVisible = false
			events.$emit('popup:close')
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
@import '/resources/sass/vuefilemanager/_variables';
@import '/resources/sass/vuefilemanager/_mixins';

#spotlight {
	position: absolute;
	width: 100%;
	height: 100%;

	.spotlight-wrapper {
		margin: 90px auto 0;
		overflow-y: auto;
		width: 590px;
		background: white;
		position: relative;
		border-radius: 8px;
		z-index: 99;
	}
}

.spotlight-search {
	margin: 0 auto 0;
	padding: 20px 25px;
	display: flex;
	align-items: center;
	position: sticky;
	top: 0;
	z-index: 99;

	.magnify-icon {
		position: relative;
		margin-right: 10px;

		.is-hidden {
			opacity: 0;
		}

		.magnify {
			transform: translateY(3px);

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

	.close-icon {
		cursor: pointer;

		.close {
			transform: translateY(4px);

			line {
				stroke: $text-muted;
			}
		}
	}
}

.spotlight-results {
	margin: -8px auto 0;
	padding: 10px 10px 10px;
	border-top: 1px solid $light_mode_border;
	position: relative;
	z-index: 99;

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

	.message {
		color: $text;
		@include font-size(16);
		font-weight: 600;
		padding-left: 15px;
	}
}

.input-hint .title {
	color: $text-muted;
	@include font-size(13);
}

.dark {

	#spotlight .spotlight-wrapper {
		background: $dark_mode_foreground;
	}

	.spotlight-search {
		input {
			color: $dark_mode_text_primary;

			&::placeholder {
				color: $dark_mode_text_primary;
			}
		}
	}

	.spotlight-results {
		border-color: $dark_mode_border_color;

		.is-clicked {
			background: lighten($dark_mode_foreground, 3%);
		}
	}

	.input-hint .title, .message {
		color: $dark_mode_text_secondary;
	}
}

@media only screen and (max-width: 1024px) {

	#spotlight .spotlight-wrapper {
		margin-top: 30px;
	}

	.spotlight-search {
		padding: 15px;
	}

	.spotlight-results {
		margin-top: -2px;
	}
}

@media only screen and (max-width: 690px) {
	#spotlight {
		background: white;
		z-index: 99;

		.spotlight-wrapper {
			width: 100%;
			border-radius: 0;
			margin: 0;
		}
	}
}

</style>
