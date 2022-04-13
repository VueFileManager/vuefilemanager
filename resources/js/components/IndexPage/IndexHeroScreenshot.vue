<template>
    <div class="page-wrapper large hero-screenshot">
        <img class="hero-light" src="/assets/images/vuefilemanager-screenshot-light.png" :alt="config.app_name" />
        <img class="hero-dark" src="/assets/images/vuefilemanager-screenshot-dark.png" :alt="config.app_name" />

        <div class="icons">
            <link-icon size="20" class="icon"></link-icon>
            <image-icon size="38" class="icon"></image-icon>
            <hard-drive-icon size="34" class="icon"></hard-drive-icon>
            <folder-plus-icon size="40" class="icon"></folder-plus-icon>
            <settings-icon size="18" class="icon"></settings-icon>
            <search-icon size="24" class="icon"></search-icon>
            <star-icon size="18" class="icon"></star-icon>
            <trash2-icon size="32" class="icon"></trash2-icon>
            <search-icon size="18" class="icon"></search-icon>
            <eye-icon size="30" class="icon"></eye-icon>
            <star-icon size="30" class="icon"></star-icon>
            <folder-plus-icon size="16" class="icon"></folder-plus-icon>
            <grid-icon size="20" class="icon"></grid-icon>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import {
    FolderPlusIcon,
    HardDriveIcon,
    SettingsIcon,
    Trash2Icon,
    SearchIcon,
    ImageIcon,
    GridIcon,
    LinkIcon,
    StarIcon,
    EyeIcon,
} from 'vue-feather-icons'

export default {
    name: 'IndexHeroScreenshot',
    components: {
        FolderPlusIcon,
        HardDriveIcon,
        SettingsIcon,
        Trash2Icon,
        SearchIcon,
        ImageIcon,
        GridIcon,
        LinkIcon,
        StarIcon,
        EyeIcon,
    },
    computed: {
        ...mapGetters(['config']),
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/landing-page';
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.icons {
    .icon {
        z-index: 0;
        position: absolute;

        &:nth-child(1) {
            top: -14%;
            right: 2%;
        }

        &:nth-child(2) {
            top: -5%;
            right: 14%;
            transform: rotate(19deg);
        }

        &:nth-child(3) {
            top: -6.5%;
            right: 28.5%;
            transform: rotate(-12deg);

            line,
            path {
                stroke: $theme;
            }
        }

        &:nth-child(4) {
            top: -9.5%;
            right: 41.5%;
            transform: rotate(13deg);

            path,
            line {
                stroke: $yellow;
            }
        }

        &:nth-child(5) {
            top: -16%;
            right: 26%;

            circle,
            path {
                stroke: $red;
            }
        }

        &:nth-child(6) {
            top: -13%;
            right: 49%;
        }

        &:nth-child(7) {
            top: 2.5%;
            right: 46%;

            polygon {
                stroke: $purple;
            }
        }

        &:nth-child(8) {
            top: 13%;
            right: 2.5%;
            transform: rotate(22deg);

            polyline,
            path,
            line {
                stroke: $red;
            }
        }

        &:nth-child(9) {
            top: 14%;
            right: 11%;

            circle,
            line {
                stroke: $purple;
            }
        }

        &:nth-child(10) {
            top: 29%;
            right: 7%;
            transform: rotate(19deg);
        }

        &:nth-child(11) {
            top: 38%;
            right: 3%;

            polygon {
                stroke: $yellow;
            }
        }

        &:nth-child(12) {
            top: 50%;
            right: 11.5%;
            transform: rotate(-22deg);
        }

        &:nth-child(13) {
            top: 34%;
            right: 16%;
            transform: rotate(13deg);

            rect {
                stroke: $theme;
            }
        }
    }
}

.hero-screenshot {
    position: relative;
    z-index: 1;
    padding-top: 75px;
    padding-bottom: 130px;

    img {
        border-radius: 8px;
        width: 80%;
        box-shadow: 0 7px 255px rgba(#19363c, 0.1);

        &.hero-dark {
            display: none;
        }
    }
}

@media only screen and (max-width: 890px) {
    .icons {
        display: none;
    }

    .hero-screenshot {
        padding-top: 40px;
        padding-bottom: 90px;

        img {
            width: 100%;
        }
    }
}

.dark {
    .hero-screenshot {
        img {
            &.hero-light {
                display: none;
            }

            &.hero-dark {
                display: block;
                box-shadow: 0 7px 185px rgba(0, 0, 0, 0.8);
            }
        }
    }
}
</style>
