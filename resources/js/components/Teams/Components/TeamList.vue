<template>
    <ul>
        <li
            v-if="Object.values(members).length > 0 && entry.id !== user.data.id"
            v-for="(entry, i) in members"
            :key="i"
            class="flex items-center py-2"
        >
            <!--Remove Member-->
            <div @click="deleteMember(entry)" class="-ml-1.5 cursor-pointer py-2 px-1 leading-none">
                <x-icon size="14" class="vue-feather dark:text-gray-600" />
            </div>

            <!--Member Preview-->
            <div class="flex items-center">
                <!--Avatar-->
                <MemberAvatar class="mr-3 ml-2" :is-border="false" :size="44" :member="$mapIntoMemberResource(entry)" />

                <!--Member-->
                <div v-if="entry.type === 'member'" class="info">
                    <b
                        class="max-w-1 block overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold"
                        style="max-width: 155px"
                    >
                        {{ entry.name }}
                    </b>
                    <span class="block text-xs text-gray-600 dark:text-gray-500">
                        {{ entry.email }}
                    </span>
                </div>

                <!--Invitation-->
                <div v-if="entry.type === 'invitation'" class="info">
                    <b
                        class="block max-w-xs overflow-hidden text-ellipsis whitespace-nowrap text-sm font-bold"
                        style="max-width: 155px"
                    >
                        {{ entry.email }}
                    </b>
                    <span v-if="entry.id" class="block text-xs text-gray-600 dark:text-gray-500">
                        {{ $t('waiting_for_accept_invitation') }}
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
import PermissionToggleButton from './PermissionToggleButton'
import MemberAvatar from '../../UI/Others/MemberAvatar'
import { XIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'TeamList',
    props: ['value'],
    computed: {
        ...mapGetters(['user']),
    },
    components: {
        PermissionToggleButton,
        MemberAvatar,
        XIcon,
    },
    data() {
        return {
            members: undefined,
        }
    },
    methods: {
        updateMemberPermission(member, value) {
            this.members.map((e) => (e === member ? (e.permission = value) : e))

            this.emitMembers()
        },
        deleteMember(member) {
            this.members = this.members.filter((m) => m !== member)

            this.emitMembers()
        },
        emitMembers() {
            this.$emit('input', this.members)
        },
    },
    created() {
        this.members = this.value
    },
}
</script>
