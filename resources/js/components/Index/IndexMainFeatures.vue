<template>
    <section class="main-features page-wrapper medium">
        <PageTitle
                type="center"
                title="The Fastest Growing <span style='color: #41B883'>File Manager</span><br> on the CodeCanyon Market"
                description="Your private cloud storage software build on Laravel & Vue.js. No limits & no monthly fees. Trully freedom."
        ></PageTitle>
        <div class="content">
            <div class="hero">
                <img src="/assets/images/hero-Illustration.svg" alt="Hero">
            </div>
            <div class="features">
                <div class="feature">
                    <div class="icon">
                        <cloud-icon size="24"></cloud-icon>
                    </div>
                    <h3 class="title">
                        Truly Freedom
                    </h3>
                    <p class="description">
                        You have full control over VueFileManager, no third authorities will control your service or usage, only you.
                    </p>
                </div>
                <div class="feature">
                    <div class="icon">
                        <user-icon size="24"></user-icon>
                    </div>
                    <h3 class="title">
                        The Sky is the Limit
                    </h3>
                    <p class="description">
                        VueFileManager is cloud storage software. You have to install and running application on your own server hosting.
                    </p>
                </div>
                <div class="feature">
                    <div class="icon">
                        <hard-drive-icon size="24"></hard-drive-icon>
                    </div>
                    <h3 class="title">
                        No Monthly Fees
                    </h3>
                    <p class="description">
                        When you running VueFileManager on your own server hosting, anybody can't control your content or resell your user data. Your data is safe.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import PageTitle from '@/components/Index/Components/PageTitle'
    import { UserIcon, CloudIcon, HardDriveIcon } from 'vue-feather-icons'

    export default {
        name: 'IndexMainFeatures',
        components: {
            PageTitle,
            HardDriveIcon,
            CloudIcon,
            UserIcon,
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_landing-page';
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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

                path, line, polyline, rect, circle {
                    stroke: $yellow;
                }
            }

            &:nth-child(2) .icon {
                background: rgba($theme, 0.1);

                path, line, polyline, rect, circle {
                    stroke: $theme;
                }
            }

            &:nth-child(3) .icon {
                background: rgba($purple, 0.1);

                path, line, polyline, rect, circle {
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

        .content {
            display: block;
        }

        .hero {
            img {
                width: 100%;
            }
        }

        .features {
            padding-left: 0;
            margin-top: 50px;
        }
    }
</style>
