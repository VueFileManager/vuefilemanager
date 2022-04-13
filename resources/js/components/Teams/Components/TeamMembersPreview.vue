<template>
    <div class="team-folder">
        <span v-if="limit && membersCount > 3" class="member-count"> +{{ membersCount - 3 }} </span>
        <div class="members">
            <div
                v-for="member in members"
                :key="member.data.id"
                :title="member.data.attributes.email"
                class="member-preview z-10"
            >
                <MemberAvatar :is-border="true" :size="34" :member="member" />
            </div>
        </div>
    </div>
</template>

<script>
import MemberAvatar from '../../UI/Others/MemberAvatar'

export default {
    name: 'TeamMembersPreview',
    props: ['folder', 'limit', 'avatarSize'],
    components: {
        MemberAvatar,
    },
    computed: {
        membersCount() {
            return (
                this.folder.data.relationships.members.data.length +
                this.folder.data.relationships.invitations.data.length
            )
        },
        members() {
            let allMembers = this.folder.data.relationships.members.data.concat(
                this.folder.data.relationships.invitations.data
            )

            if (this.limit) {
                return allMembers.slice(0, 3)
            }

            return allMembers
        },
    },
}
</script>

<style lang="scss" scoped>
@import 'resources/sass/vuefilemanager/_variables';
@import 'resources/sass/vuefilemanager/_mixins';

.team-folder {
    display: flex;
    align-items: center;

    .member-count {
        @include font-size(12);
        color: $text-muted;
        margin-right: 3px;
        opacity: 0.7;
        min-width: 14px;
        text-align: left;
    }

    .members {
        display: flex;

        .member-preview {
            margin-left: -10px;

            &:first-child {
                margin-left: 0;
            }
        }

        .member {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 10px;
            border: 2px solid white;
            vertical-align: middle;
        }
    }
}

.dark {
    .team-folder {
        .member-count {
            color: $dark_mode_text_secondary;
        }

        .members .member {
            border-color: $dark_mode_foreground;
        }
    }
}
</style>
