<template>
    <transition appear name="fade">
        <li class="toastr-item" :class="item.type" v-show="isActive">
            <div class="toastr-content-wrapper">
				<span class="toastr-icon">
					<check-icon v-if="item.type == 'success'" size="22"></check-icon>
					<x-icon v-if="item.type == 'danger'" size="22"></x-icon>
				</span>
                <div class="toastr-content">
                    <p class="toastr-description">{{ item.message }}</p>
                </div>
            </div>
            <div class="progressbar">
                <span></span>
            </div>
        </li>
    </transition>
</template>

<script>
    import {XIcon, CheckIcon} from 'vue-feather-icons'

    export default {
        components: {
            XIcon,
            CheckIcon
        },
        props: ['item'],
        data() {
            return {
                isActive: 0
            }
        },
        created() {
            this.isActive = 1

            setTimeout(() => (this.isActive = 0), 5000)
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .fade-enter-active,
    .fade-leave-active {
        transition: 0.3s ease;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
        @include transform(translateX(100%));
    }

    .toastr-content-wrapper {
        display: flex;
        align-items: center;
        padding: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
    }

    .progressbar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        opacity: 0.35;

        span {
            width: 0;
            height: 3px;
            display: block;
            background: $theme;
            animation: progressbar 5s linear;
        }
    }

    @keyframes progressbar {
        0% {
            width: 0;
        }
        100% {
            width: 100%;
        }
    }

    .toastr-item {
        max-width: 320px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        display: block;
        border-radius: 8px;

        .toastr-description {
            @include font-size(15);
            font-weight: bold;
        }

        .toastr-icon {
            height: 42px;
            width: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-size: 20px;
            margin-right: 10px;
        }

        &.success {
            background: $theme_light;

            polyline {
                stroke: $theme;
            }

            .toastr-description {
                color: $theme;
            }
        }

        &.danger {
            background: rgba($danger, 0.1);

            polyline {
                stroke: $danger;
            }

            .toastr-description {
                color: $danger;
            }
        }
    }

    @media only screen and (max-width: 690px) {

        .toastr-item {
            margin-bottom: 0;
            margin-top: 20px;
            max-width: 100%;
        }

        .fade-enter,
        .fade-leave-to {
            opacity: 0;
            @include transform(translateY(100%));
        }
    }

    @media (prefers-color-scheme: dark) {
        .toastr-item {

            &.success, &.danger {
                background: $dark_mode_foreground;
            }
        }
    }
</style>
