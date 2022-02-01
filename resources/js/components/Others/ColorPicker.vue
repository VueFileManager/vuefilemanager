<template>
    <div class="color-pick-wrapper">
        <label class="main-label">{{ $t('popup_rename.color_pick_label') }}:</label>
        <ul class="color-wrapper">
            <li v-for="(color, i) in colors" :key="i" @click="setColor(color)" class="single-color">
                <check-icon v-if="color === selectedColor" class="color-icon" size="22" />
                <span :style="{ background: color }" class="color-box"></span>
            </li>
        </ul>
    </div>
</template>

<script>
import { CheckIcon } from 'vue-feather-icons'
import { mapGetters } from 'vuex'

export default {
    name: 'ColorPicker',
    props: ['pickedColor'],
    components: { CheckIcon },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
            selectedColor: this.pickedColor,
            colors: [
                '#FE6F6F',
                '#FE6F91',
                '#FE6FC0',
                '#FE6FF0',
                '#DD6FFE',
                '#AD6FFE',
                '#7D6FFE',
                '#6F90FE',
                '#6FC0FE',
                '#6FF0FE',
                '#6FFEDD',
                '#6FFEAD',
                '#6FFE7D',
                '#90FE6F',
                '#C0FE6F',
                '#F0FE6F',
                '#FEDD6F',
                '#FEAD6F',
                '#FE7D6F',
                '#4c4c4c',
                '#06070B',
            ],
        }
    },
    methods: {
        setColor(value) {
            this.selectedColor = value

            this.$emit('input', value)
        },
    },
    created() {
        this.colors.push(this.config.app_color)
    },
}
</script>

<style lang="scss" scoped>
@import '../../../sass/vuefilemanager/inapp-forms';
@import '../../../sass/vuefilemanager/forms';

.color-pick-wrapper {
    .color-wrapper {
        margin-bottom: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fill, 32px);
        justify-content: space-between;
        gap: 7px;

        .single-color {
            height: 31px;
            list-style: none;
            border-radius: 8px;
            cursor: pointer;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;

            .color-icon {
                z-index: 2;

                polyline {
                    stroke: white;
                }
            }

            .color-box {
                width: 100%;
                height: 100%;
                position: absolute;
                display: block;
            }
        }
    }
}

.main-label {
    @include font-size(14);
    font-weight: 700;
    margin-bottom: 8px;
    display: block;
}

.set-folder-icon {
    position: relative;
}

.dark {
    .color-pick-wrapper {
        .color-wrapper {
            .single-color {
                &.active-color {
                    border: 2px solid;
                }

                &:hover {
                    border: 2px solid $dark_mode_text_primary;
                }
            }
        }
    }
}
</style>
