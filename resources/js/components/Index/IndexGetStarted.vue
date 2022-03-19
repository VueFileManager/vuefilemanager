<template>
    <div class="page-wrapper large get-started" v-if="index.section_get_started === '1'">
        <PageTitle
            class="page-title"
            type="center"
            :title="index.get_started_title"
            :description="index.get_started_description"
        ></PageTitle>

        <router-link
            tag="button"
            class="get-started-button bg-theme-800 hover-bg-theme shadow-theme"
            :to="{ name: 'SignUp' }"
        >
            <span class="content">{{ $t('sign_up_now') }}</span>
            <chevron-right-icon size="22"></chevron-right-icon>
        </router-link>

        <cloud-icon size="790" class="cloud-bg svg-color-theme" />

        <div class="icons">
            <hard-drive-icon size="42" class="icon"></hard-drive-icon>
            <settings-icon size="22" class="icon"></settings-icon>
            <image-icon size="50" class="icon"></image-icon>
            <link-icon size="24" class="icon"></link-icon>
            <trash2-icon size="40" class="icon"></trash2-icon>
            <search-icon size="18" class="icon"></search-icon>
            <eye-icon size="36" class="icon"></eye-icon>
            <star-icon size="34" class="icon"></star-icon>
            <folder-plus-icon size="20" class="icon"></folder-plus-icon>
            <grid-icon size="28" class="icon"></grid-icon>
            <share-icon size="32" class="icon"></share-icon>
            <folder-plus-icon size="48" class="icon"></folder-plus-icon>
            <search-icon size="34" class="icon"></search-icon>
            <star-icon size="22" class="icon"></star-icon>
            <upload-cloud-icon size="42" class="icon"></upload-cloud-icon>
            <grid-icon size="18" class="icon"></grid-icon>
            <settings-icon size="32" class="icon"></settings-icon>
            <link-icon size="36" class="icon"></link-icon>
            <hard-drive-icon size="22" class="icon"></hard-drive-icon>
            <info-icon size="36" class="icon"></info-icon>
        </div>
    </div>
</template>

<script>
import PageTitle from './Components/PageTitle'
import {
    ChevronRightIcon,
    UploadCloudIcon,
    FolderPlusIcon,
    HardDriveIcon,
    SettingsIcon,
    Trash2Icon,
    SearchIcon,
    ShareIcon,
    CloudIcon,
    ImageIcon,
    InfoIcon,
    GridIcon,
    LinkIcon,
    StarIcon,
    EyeIcon,
} from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'IndexGetStarted',
    components: {
        InfoIcon,
        UploadCloudIcon,
        ShareIcon,
        ChevronRightIcon,
        FolderPlusIcon,
        HardDriveIcon,
        SettingsIcon,
        Trash2Icon,
        SearchIcon,
        CloudIcon,
        PageTitle,
        ImageIcon,
        GridIcon,
        LinkIcon,
        StarIcon,
        EyeIcon,
    },
    computed: {
        ...mapGetters(['index']),
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/landing-page';
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.icons {
    .icon {
        position: absolute;

        &:nth-child(20) {
            bottom: -37%;
            left: 37%;
            transform: rotate(0deg);

            circle,
            line {
                stroke: $yellow;
            }
        }

        &:nth-child(19) {
            bottom: -21%;
            left: 23.5%;
            transform: rotate(-20deg);

            path,
            line {
                stroke: $purple;
            }
        }

        &:nth-child(18) {
            bottom: -4%;
            left: 26.5%;
            transform: rotate(0deg);

            path {
                stroke: $theme;
            }
        }

        &:nth-child(17) {
            bottom: -5%;
            left: 8.5%;
            transform: rotate(0deg);
        }

        &:nth-child(16) {
            top: 86%;
            left: 17%;
            transform: rotate(18deg);
        }

        &:nth-child(15) {
            top: 64%;
            left: 17%;
            transform: rotate(0deg);

            polyline,
            line,
            path {
                stroke: $red;
            }
        }

        &:nth-child(14) {
            top: 44%;
            left: 28%;
            transform: rotate(0deg);

            polygon {
                stroke: $purple;
            }
        }

        &:nth-child(13) {
            top: 33%;
            left: 16%;
            transform: rotate(0deg);
        }

        &:nth-child(12) {
            top: 23%;
            left: 32%;
            transform: rotate(13deg);

            line,
            path {
                stroke: $yellow;
            }
        }

        &:nth-child(1) {
            top: 35%;
            right: 49%;
            transform: rotate(-11deg);

            line,
            path {
                stroke: $theme;
            }
        }

        &:nth-child(2) {
            top: 12%;
            right: 45%;
            transform: rotate(0);

            circle,
            path {
                stroke: $red;
            }
        }

        &:nth-child(3) {
            top: 30%;
            right: 30%;
            transform: rotate(20deg);
        }

        &:nth-child(4) {
            top: 14%;
            right: 14.5%;
            transform: rotate(-1deg);
        }

        &:nth-child(5) {
            top: 62%;
            right: 15.5%;
            transform: rotate(21deg);

            polyline,
            path,
            line {
                stroke: $red;
            }
        }

        &:nth-child(6) {
            top: 66%;
            right: 26.5%;
            transform: rotate(0deg);
        }

        &:nth-child(7) {
            bottom: 3%;
            right: 21.5%;
            transform: rotate(16deg);
        }

        &:nth-child(8) {
            bottom: -13%;
            right: 16.5%;
            transform: rotate(0deg);

            polygon {
                stroke: $yellow;
            }
        }

        &:nth-child(9) {
            bottom: -32%;
            right: 27%;
            transform: rotate(-20deg);
        }

        &:nth-child(10) {
            bottom: -5%;
            right: 34%;
            transform: rotate(16deg);

            rect {
                stroke: $purple;
            }
        }

        &:nth-child(11) {
            bottom: -28%;
            right: 44%;
            transform: rotate(-12deg);

            polyline,
            line,
            path {
                stroke: $red;
            }
        }
    }
}

.cloud-bg {
    z-index: 0;
    position: absolute;
    top: 70px;
    right: 60px;
    transform: scale(-1, 1) rotate(13deg);
    opacity: 0.1;

    path {
        stroke: none;
    }
}

.page-title {
    padding-top: 340px;
}

.get-started-button {
    display: flex;
    align-items: center;
    outline: none;
    border: none;
    margin-left: auto;
    margin-right: auto;
    cursor: pointer;
    padding: 20px 36px;
    border-radius: 6px;
    //box-shadow: 0 5px 10px 2px rgba($theme, 0.34);
    margin-bottom: 395px;
    @include transition(150ms);
    position: relative;
    z-index: 1;

    .content {
        @include font-size(19);
        font-weight: 700;
        margin-right: 8px;
        color: white;
    }

    polyline {
        stroke: white;
    }
}

@media only screen and (max-width: 1190px) {
    .get-started-button {
        margin-bottom: 280px;
    }
}

@media only screen and (max-width: 960px) {
    .page-title {
        padding-top: 20px;
    }

    .get-started-button {
        margin-bottom: 30px;
    }

    .cloud-bg,
    .icons {
        display: none;
    }
}
</style>
