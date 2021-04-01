<template>
    <div class="search-bar">
        <div v-if="!query" class="icon">
            <search-icon size="19" />
        </div>
        <div @click="clearInput" v-if="query" class="icon">
            <x-icon class="pointer" size="19"></x-icon>
        </div>
        <input
            v-model="query"
            @input="$emit('input', query)"
            class="query"
            type="text"
            name="searchInput"
            placeholder="Search Language Strings..."
        />
    </div>
</template>

<script>
import {SearchIcon, XIcon} from 'vue-feather-icons'

export default {
    name: 'SearchInput',
    components: {
        SearchIcon,
        XIcon,
    },
    data() {
        return {
            query: undefined,
        }
    },
    methods: {
        clearInput() {
            this.query = undefined

            this.$emit('reset-query')
        },
    },
}
</script>

<style lang="scss" scoped>
@import '@assets/vuefilemanager/_variables';
@import '@assets/vuefilemanager/_mixins';
@import '@assets/vuefilemanager/_forms';

.search-bar {
    padding: 7px 0px;
    position: relative;
    width: 100%;
    border-radius: 8px;

    input {
        background: $light_background;
        border-radius: 8px;
        outline: 0;
        padding: 9px 20px 9px 43px;
        font-weight: 700;
        @include font-size(16);
        width: 100%;
        height: 50px;
        min-width: 175px;
        transition: 0.15s all ease;
        border: 1px solid transparent;
        -webkit-appearance: none;
        box-shadow: none;

        &::placeholder {
            color: $light_text;
            @include font-size(14);
            font-weight: 700;
        }

        &:focus {
            border: 1px solid $theme;
            box-shadow: 0 0 7px rgba($theme, 0.3);
        }

        &:focus + .icon {
            path {
                fill: $theme;
            }
        }
    }

    .icon {
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        padding: 11px 15px;
        display: flex;
        align-items: center;

        circle,
        line {
            color: $light_text;
        }

        .pointer {
            cursor: pointer;
        }
    }
}

</style>
