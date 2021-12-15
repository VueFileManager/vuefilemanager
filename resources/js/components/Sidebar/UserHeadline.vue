<template>
    <div class="user-headline">
		<MemberAvatar
			class="inline-block"
			:size="52"
			:is-border="false"
			:member="user"
		/>
        <div class="user-meta">
            <b class="name">{{ user.data.relationships.settings.data.attributes.name }}</b>
            <span class="email text-theme">{{ user.data.attributes.email }}</span>
        </div>
    </div>
</template>

<script>
	import MemberAvatar from "../FilesView/MemberAvatar";
    import { mapGetters } from 'vuex'

    export default {
        name: 'UserHeadline',
        components: {
			MemberAvatar
        },
        computed: {
            ...mapGetters([
                'user'
            ]),
        },
    }
</script>

<style scoped lang="scss">
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .user-headline {
        display: flex;
        align-items: center;
    }

    .user-meta {
        padding-left: 20px;

        .name {
            display: block;
            @include font-size(18);
        }

        .email {
            display: block;
            @include font-size(12);
            font-weight: 600;
        }
    }
</style>
