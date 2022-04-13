<template>
    <section class="main-features page-wrapper medium">
        <PageTitle
            v-if="index.section_features === '1'"
            type="center"
            :title="index.features_title"
            :description="index.features_description"
        ></PageTitle>
        <div v-if="index.section_feature_boxes === '1'" class="content">
            <div class="hero">
                <img src="/assets/images/hero-Illustration.svg" alt="Hero" />
            </div>
            <div class="features">
                <div class="feature">
                    <div class="icon">
                        <cloud-icon size="24"></cloud-icon>
                    </div>
                    <h3 class="title">
                        {{ index.feature_title_1 }}
                    </h3>
                    <p class="description">
                        {{ index.feature_description_1 }}
                    </p>
                </div>
                <div class="feature">
                    <div class="icon">
                        <user-icon size="24"></user-icon>
                    </div>
                    <h3 class="title">
                        {{ index.feature_title_2 }}
                    </h3>
                    <p class="description">
                        {{ index.feature_description_2 }}
                    </p>
                </div>
                <div class="feature">
                    <div class="icon">
                        <hard-drive-icon size="24"></hard-drive-icon>
                    </div>
                    <h3 class="title">
                        {{ index.feature_title_3 }}
                    </h3>
                    <p class="description">
                        {{ index.feature_description_3 }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import { UserIcon, CloudIcon, HardDriveIcon } from 'vue-feather-icons'
import PageTitle from './Components/PageTitle'
import { mapGetters } from 'vuex'

export default {
    name: 'IndexMainFeatures',
    components: {
        PageTitle,
        HardDriveIcon,
        CloudIcon,
        UserIcon,
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

.features {
    padding-left: 75px;

    .feature {
        margin-bottom: 25px;

        .title {
            @include font-size(26);
            font-weight: 800;
            margin-bottom: 4px;
        }

        .description {
            line-height: 1.5;
            color: $text-muted;
            @include font-size(18);
        }

        .icon {
            border-radius: 12px;
            width: 44px;
            height: 44px;
            display: flex;
            align-items: center;
            margin-bottom: 18px;

            svg {
                margin: 0 auto;
            }
        }

        &:nth-child(1) .icon {
            background: rgba($yellow, 0.1);

            path,
            line,
            polyline,
            rect,
            circle {
                stroke: $yellow;
            }
        }

        &:nth-child(2) .icon {
            background: rgba($theme, 0.1);

            path,
            line,
            polyline,
            rect,
            circle {
                stroke: $theme;
            }
        }

        &:nth-child(3) .icon {
            background: rgba($purple, 0.1);

            path,
            line,
            polyline,
            rect,
            circle {
                stroke: $purple;
            }
        }
    }
}

.content {
    margin-top: 107px;
    display: flex;
}

@media only screen and (max-width: 1190px) {
    .hero {
        flex: 0 0 60%;

        img {
            width: 100%;
        }
    }

    .features {
        padding-left: 25px;
        margin-top: 0px;
    }
}

@media only screen and (max-width: 960px) {
    .content {
        display: block;
        margin-top: 40px;
    }

    .features {
        margin-top: 50px;
        padding-left: 0;

        .feature {
            margin-bottom: 35px;

            .title {
                @include font-size(22);
            }

            .description {
                @include font-size(16);
            }
        }
    }
}

.dark {
    .features {
        .feature {
            .description {
                color: $dark_mode_text_secondary;
            }
        }
    }
}
</style>
