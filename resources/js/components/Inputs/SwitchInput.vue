<template>
    <div>
        <div class="switch-content">
            <label class="input-label" v-if="label"> {{ label }}: </label>
            <small class="input-info" v-if="info">
                {{ info }}
            </small>
        </div>

        <div class="switch-content text-right">
            <div class="switch" :class="{ active: state }" @click="changeState">
                <div class="switch-button"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SwitchInput',
    props: ['label', 'name', 'state', 'info', 'input', 'isDisabled'],
    data() {
        return {
            isSwitched: undefined,
        }
    },
    methods: {
        changeState() {
			if (this.isDisabled) return

            this.isSwitched = !this.isSwitched
            this.$emit('input', this.isSwitched)
        },
    },
    mounted() {
        this.isSwitched = this.state
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';

.input-wrapper {
    display: flex;
    width: 100%;

    .input-label {
        color: $text;
    }

    .switch-content {
        width: 100%;

        &:last-child {
            width: 80px;
        }
    }
}

.switch {
    width: 50px;
    height: 28px;
    border-radius: 50px;
    display: block;
    background: #f1f1f5;
    position: relative;
    @include transition;

    .switch-button {
        @include transition;
        width: 22px;
        height: 22px;
        border-radius: 50px;
        display: block;
        background: white;
        position: absolute;
        top: 3px;
        left: 3px;
        box-shadow: 0 2px 4px rgba(37, 38, 94, 0.1);
        cursor: pointer;
    }

    &.active {
        .switch-button {
            left: 25px;
        }
    }
}

.dark {
    .switch {
        background: $dark_mode_foreground;
    }

    .popup-wrapper {
        .switch {
            background: lighten($dark_mode_foreground, 3%);
        }
    }
}
</style>
