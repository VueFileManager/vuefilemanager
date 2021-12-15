<template>
    <div class="flex items-center leading-none">
		<MemberAvatar
			:size="52"
			:is-border="false"
			:member="user"
		/>
        <div class="pl-4">
            <b class="block font-bold mb-1 leading-none">
				{{ user.data.relationships.settings.data.attributes.name }}
			</b>
            <span class="text-theme font-semibold text-sm leading-none">
				{{ user.data.attributes.email }}
			</span>
        </div>
    </div>
</template>

<script>
	import MemberAvatar from "../FilesView/MemberAvatar"
    import { mapGetters } from 'vuex'

    export default {
        name: 'UserHeadline',
        components: {
			MemberAvatar,
        },
        computed: {
            ...mapGetters([
                'user'
            ]),
        },
    }
</script>
