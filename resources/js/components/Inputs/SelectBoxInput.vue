<template>
    <div class="select-box">
        <div
            class="box-item active-bg-theme-100 active-border-theme"
            :class="{ active: item.value === input }"
            @click="getSelectedValue(item)"
            v-for="(item, i) in data"
            :key="i"
        >
            <span class="box-value active-text-theme">{{ item.label }}</span>
        </div>
    </div>
</template>

<script>
export default {
    name: 'SelectBoxInput',
    props: ['data', 'value'],
    data() {
        return {
            input: undefined,
        }
    },
    methods: {
        getSelectedValue(item) {
            if (!this.input || this.input !== item.value) this.input = item.value
            else this.input = undefined

            this.$emit('input', this.input)
        },
    },
    created() {
        if (this.value) this.input = this.value
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/variables';
@import '../../../sass/vuefilemanager/mixins';
@import '../../../sass/vuefilemanager/inapp-forms';
@import '../../../sass/vuefilemanager/forms';

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
    }
}

@media only screen and (max-width: 960px) {
    .select-box {
        .box-item {
            flex-basis: calc(34% - 10px);
        }
    }
}

.dark {
    .select-box {
        .box-item {
            border-color: $dark_mode_border_color;
            background: lighten($dark_mode_foreground, 3%);
        }
    }
}
</style>
