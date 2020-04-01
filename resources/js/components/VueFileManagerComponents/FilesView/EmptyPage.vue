<template>
    <div class="empty-page" v-if="isLoading || isEmpty">
        <div class="empty-state">
            <div class="text-content" v-if="isEmpty && !isLoading">
                <h1 class="title">{{ $t('empty_page.title') }}</h1>
                <p v-if="! isTrash" class="description">
                    {{ $t('empty_page.description') }}
                </p>
                <ButtonUpload
                        v-if="! isTrash"
                        @input.native="$uploadFiles(files)"
                        v-model="files"
                        button-style="theme"
                >{{ $t('empty_page.call_to_action') }}
                </ButtonUpload
                >
            </div>
            <div class="text-content" v-if="isLoading">
                <Spinner/>
            </div>
        </div>
    </div>
</template>

<script>
    import ButtonUpload from '@/components/VueFileManagerComponents/FilesView/ButtonUpload'
    import Spinner from '@/components/VueFileManagerComponents/FilesView/Spinner'
    import {mapGetters} from 'vuex'

    export default {
        name: 'EmptyPage',
        props: ['title', 'description'],
        components: {
            ButtonUpload,
            Spinner
        },
        computed: {
            ...mapGetters(['data', 'isLoading', 'currentFolder']),
            isEmpty() {
                return this.data.length == 0
            },
            isTrash() {
                return this.currentFolder.location === 'trash' || this.currentFolder.location === 'trash-root'
            }
        },
        data() {
            return {
                files: undefined
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "@assets/app.scss";

    .empty-page {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        top: 0;
        margin-top: 85px;
        display: flex;
        align-items: center;

        .empty-state {
            margin: 0 auto;
            padding-left: 15px;
            padding-right: 15px;
        }
    }

    .text-content {
        text-align: center;
        margin: 30px 0;

        .title {
            @include font-size(24);
            color: $text;
            font-weight: 700;
            margin: 0;
        }

        .description {
            @include font-size(15);
            color: $text-muted;
            margin-bottom: 20px;
            display: block;
        }
    }

    @media (prefers-color-scheme: dark) {
        .text-content {

            .title {
                color: $dark_mode_text_primary;
            }

            .description {
                color: $dark_mode_text_secondary;
            }
        }
    }
</style>
