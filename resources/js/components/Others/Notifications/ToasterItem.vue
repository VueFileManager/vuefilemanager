<template>
    <transition appear name="fade">
        <li v-show="isActive" class="toaster-item" :class="item.type">
            <div class="toaster-content-wrapper">
				<span class="toaster-icon">
					<check-icon v-if="item.type === 'success'" size="22" />
					<x-icon v-if="item.type === 'danger'" size="22" />
				</span>
                <div class="toaster-content">
                    <p class="toaster-description">{{ item.message }}</p>
                </div>
            </div>
            <div :class="{'success': item.type === 'success', 'danger': item.type === 'danger'}" class="progressbar">
                <span></span>
            </div>
        </li>
    </transition>
</template>

<script>
    import {XIcon, CheckIcon} from 'vue-feather-icons'

    export default {
        components: {
            CheckIcon,
            XIcon,
        },
        props: [
			'item'
		],
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
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    .fade-enter-active,
    .fade-leave-active {
        transition: 0.3s ease;
    }

    .fade-enter,
    .fade-leave-to {
        opacity: 0;
        @include transform(translateX(100%));
    }

    .toaster-content-wrapper {
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
            animation: progressbar 5s linear;
        }

		&.success span {
            background: $theme;
		}

		&.danger span {
            background: $danger;
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

    .toaster-item {
        max-width: 320px;
        margin-top: 20px;
        position: relative;
        overflow: hidden;
        display: block;
        border-radius: 8px;

        .toaster-description {
            @include font-size(15);
            font-weight: bold;
        }

        .toaster-icon {
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

            line, polyline {
                stroke: $theme;
            }

            .toaster-description {
                color: $theme;
            }
        }

        &.danger {
            background: rgba($danger, 0.1);

            line, polyline {
                stroke: $danger;
            }

            .toaster-description {
                color: $danger;
            }
        }
    }

    @media only screen and (max-width: 690px) {

        .toaster-item {
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

    .dark {
        .toaster-item {

            &.success, &.danger {
                background: $dark_mode_foreground;
            }
        }
    }
</style>
