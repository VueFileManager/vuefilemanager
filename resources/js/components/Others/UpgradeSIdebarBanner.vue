<template>
    <div class="upgrade-banner">
        <div class="header-title">
            <hard-drive-icon size="19" class="icon"></hard-drive-icon>
            <span class="title">{{ storage.used }}% From {{ storage.capacity_formatted }}</span>
        </div>
        <div class="content">
            <p v-if="storage.used > 95" class="reach-capacity">{{ $t('upgrade_banner.title') }}</p>
            <p v-else class="reach-capacity">{{ $t('upgrade_banner.description') }}</p>
        </div>
        <div v-if="config.app_payments_active" class="footer">
            <router-link :to="{name: 'UpgradePlan'}" class="button">
                {{ $t('upgrade_banner.button') }}
            </router-link>
        </div>
    </div>
</template>

<script>
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import { HardDriveIcon } from 'vue-feather-icons'
    import { mapGetters } from 'vuex'

    export default {
        name: 'UpgradeSidebarBanner',
        components: {
            HardDriveIcon,
            ButtonBase,
        },
        computed: {
            ...mapGetters(['config']),
            storage() {
                return this.$store.getters.user.relationships.storage.data.attributes
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .upgrade-banner {
        background: rgba($danger, 0.1);
        padding: 10px;
        border-radius: 6px;
        margin: 0 16px;
    }

    .header-title {
        margin-bottom: 12px;
        display: flex;
        align-items: center;

        .icon {
            margin-right: 10px;

            line, path {
                stroke: $danger;
            }
        }

        .title {
            @include font-size(13);
            font-weight: 800;
            color: $danger;
        }
    }

    .content {
        margin-bottom: 12px;

        p {
            @include font-size(12);
            color: $danger;
            font-weight: 700;
        }
    }

    .button {
        background: $danger;
        border-radius: 50px;
        padding: 6px 0;
        width: 100%;
        color: white;
        text-align: center;
        @include font-size(12);
        font-weight: 700;
        display: block;
        box-shadow: 0 4px 10px rgba($danger, 0.35);
    }

    @media only screen and (max-width: 1024px) {

    }

    @media (prefers-color-scheme: dark) {

    }
</style>
