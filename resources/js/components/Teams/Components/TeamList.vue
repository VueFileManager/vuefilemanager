<template>
	<ul>
		<li
			v-if="Object.values(members).length > 0 && entry.id !== user.data.id"
			v-for="(entry, i) in members"
			:key="i"
			class="flex items-center py-2"
		>
			<!--Remove Member-->
			<div @click="deleteMember(entry)" class="cursor-pointer leading-none py-2 px-1 -ml-1.5">
				<x-icon size="14" class="vue-feather dark:text-gray-600" />
			</div>

			<!--Member Preview-->
			<div class="flex items-center">

				<!--Avatar-->
				<MemberAvatar
					class="mr-3 ml-2"
					:is-border="false"
					:size="44"
					:member="$mapIntoMemberResource(entry)"
				/>

				<!--Member-->
				<div v-if="entry.type === 'member'" class="info">
					<b class="text-sm font-bold block max-w-1 overflow-hidden overflow-ellipsis whitespace-nowrap" style="max-width: 155px;">
						{{ entry.name }}
					</b>
					<span class="block text-xs dark:text-gray-500 text-gray-600">
						{{ entry.email }}
					</span>
				</div>

				<!--Invitation-->
				<div v-if="entry.type === 'invitation'" class="info">
					<b class="text-sm font-bold block max-w-xs overflow-hidden overflow-ellipsis whitespace-nowrap" style="max-width: 155px;">
						{{ entry.email }}
					</b>
					<span v-if="entry.id" class="block text-xs dark:text-gray-500 text-gray-600">
						{{ $t('Waiting for accept invitation...') }}
					</span>
				</div>
			</div>


			<!--Set member permission-->
			<div class="ml-auto">
				<PermissionToggleButton @input="updateMemberPermission(entry, $event)" :item="entry" />
			</div>
		</li>
	</ul>
</template>

<script>
	import PermissionToggleButton from "./PermissionToggleButton"
	import MemberAvatar from "../../FilesView/MemberAvatar";
	import {XIcon} from 'vue-feather-icons'
	import {mapGetters} from "vuex";

	export default {
		name: "TeamList",
		props: [
			'value',
		],
		computed: {
			...mapGetters([
				'user'
			])
		},
		components: {
			PermissionToggleButton,
			MemberAvatar,
			XIcon,
		},
		data() {
			return {
				members: undefined
			}
		},
		methods: {
			updateMemberPermission(member, value) {
				this.members.map(e => e === member ? e.permission = value : e)

				this.emitMembers()
			},
			deleteMember(member) {
				this.members = this.members.filter(m => m !== member)

				this.emitMembers()
			},
			emitMembers() {
				this.$emit('input', this.members)
			}
		},
		created() {
			this.members = this.value
		}
	}
</script>