<template>
    <div class="select-box">
        <div class="box-item"
             :class="{'selected': item.value === input}"
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
        props: [
            'data',
            'value',
        ],
        data() {
            return {
                input: undefined,
            }
        },
        methods: {
            getSelectedValue(item) {
                if (! this.input || this.input !== item.value)
                    this.input = item.value
                else
                    this.input = undefined

                this.$emit('input', this.input)
            }
        },
        created() {
            if (this.value)
                this.input = this.value
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
        flex-wrap: wrap;
        flex-direction: row;
        margin-bottom: 10px;

        .box-item {
            margin-bottom: 10px;
            padding: 12px 4px;
            text-align: center;
            background: $light_background;
            border-radius: 8px;
            font-weight: 700;
            border: 2px solid $light_background;
            cursor: pointer;
            flex-direction: column;
            flex-basis: 55px;

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

    @media only screen and (max-width: 960px) {
        .select-box {

            .box-item {
                flex-basis: calc(34% - 10px);
            }
        }
    }


    @media (prefers-color-scheme: dark) {
        .select-box {

            .box-item {
                border-color: $dark_mode_border_color;
                background: $dark_mode_foreground;
            }
        }
    }
</style>
