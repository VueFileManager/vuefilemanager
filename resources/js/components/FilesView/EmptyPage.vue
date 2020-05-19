<template>
    <div class="empty-page" v-if="isLoading || isEmpty">
        <div class="empty-state">

            <!--Shared empty message-->
            <div class="text-content" v-if="$isThisLocation(['shared']) && ! isLoading">
                <h1 class="title">{{ $t('shared.empty_shared') }}</h1>
            </div>

            <!--Trash empty message-->
            <div class="text-content" v-if="$isThisLocation(['trash', 'trash-root']) && ! isLoading">
                <h1 class="title">{{ $t('empty_page.title') }}</h1>
            </div>

            <!--Trash empty message-->
            <div class="text-content" v-if="$isThisLocation(['participant_uploads']) && ! isLoading">
                <h1 class="title">{{ $t('messages.nothing_from_participants') }}</h1>
            </div>

            <!--Base file browser empty message-->
            <div class="text-content" v-if="$isThisLocation(['base', 'public', 'latest']) && !isLoading">
                <h1 class="title">{{ $t('empty_page.title') }}</h1>
                <p v-if="$checkPermission(['master', 'editor'])" class="description">{{ $t('empty_page.description') }}</p>
                <ButtonUpload
                        v-if="$checkPermission(['master', 'editor'])"
                        button-style="theme"
                >
                    {{ $t('empty_page.call_to_action') }}
                </ButtonUpload>
            </div>

            <!--Spinner-->
            <div class="text-content" v-if="isLoading">
                <Spinner/>
            </div>
        </div>
    </div>
</template>

<script>
    import ButtonUpload from '@/components/FilesView/ButtonUpload'
    import Spinner from '@/components/FilesView/Spinner'
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
                return this.data && this.data.length == 0
            }
        }
    }
</script>

<style scoped lang="scss">
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

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
            @include font-size(20);
            color: $text;
            font-weight: 700;
            margin: 0;
        }

        .description {
            @include font-size(13);
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
