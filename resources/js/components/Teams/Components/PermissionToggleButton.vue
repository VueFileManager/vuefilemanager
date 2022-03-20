<template>
    <div @click="togglePermission" class="permission-toggle">
        <b class="privilege">{{ $t(teamPermissions[permission]) }}</b>
        <refresh-cw-icon size="14" />
    </div>
</template>

<script>
import { RefreshCwIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'PermissionToggleButton',
    props: ['item'],
    computed: {
        ...mapGetters(['teamPermissions']),
    },
    components: {
        RefreshCwIcon,
    },
    data() {
        return {
            permission: undefined,
        }
    },
    methods: {
        togglePermission() {
            let index = Object.keys(this.teamPermissions)
                .map((i) => i)
                .indexOf(this.permission)

            if (index === Object.keys(this.teamPermissions).length - 1) {
                this.permission = Object.keys(this.teamPermissions)[0]
            } else {
                this.permission = Object.keys(this.teamPermissions)[index + 1]
            }

            this.$emit('input', this.permission)
        },
    },
    created() {
        this.permission = this.item.permission
    },
}
</script>

<style lang="scss" scoped>
@import '../../../../sass/vuefilemanager/inapp-forms';
@import '../../../../sass/vuefilemanager/forms';

.permission-toggle {
    display: flex;
    align-items: center;
    cursor: pointer;
    user-select: none;

    .privilege {
        white-space: nowrap;
        @include font-size(13);
        color: $text-muted;
        margin-right: 10px;
    }

    polyline,
    path {
        color: $light_text;
    }
}

.dark {
    .permission-toggle .privilege {
        color: $dark_mode_text_secondary;
    }
}
</style>
