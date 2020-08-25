<template>
    <div class="select-box">
        <div class="box-item"
             :class="{'selected': item.value === value}"
             @click="getSelectedValue(item)"
             v-for="(item, i) in data" :key="i"
        >
            <span class="box-value">{{ item.label }}</span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'SelectBoxInput',
        props: ['data'],
        data() {
            return {
                value: undefined,
            }
        },
        methods: {
            getSelectedValue(item) {
                if (! this.value || this.value !== item.value)
                    this.value = item.value
                else
                    this.value = undefined

                this.$emit('input', this.value)
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';
    @import "@assets/vue-file-manager/_inapp-forms.scss";
    @import "@assets/vue-file-manager/_forms.scss";

    .select-box {
        display: flex;
        justify-content: space-between;

        .box-item {
            padding: 12px 14px;
            background: $light_background;
            border-radius: 8px;
            font-weight: 700;
            border: 2px solid $light_background;
            cursor: pointer;

            .box-value {
                @include font-size(15);
            }

            &.selected {
                background: rgba($theme, .1);
                border-color: $theme;

                .box-value {
                    color: $theme;
                }
            }
        }
    }


    @media (prefers-color-scheme: dark) {

    }
</style>
