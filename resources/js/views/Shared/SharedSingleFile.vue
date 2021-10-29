<template>
    <div id="single-file">
        <div class="single-file-wrapper">
<!--			TODO: fix-->
<!--            <FileItemGrid v-if="sharedFile" :item="sharedFile.data.attributes" :context-menu="false"/>-->

            <ButtonBase @click.native="download" class="download-button" button-style="theme">
                {{ $t('page_shared.download_file') }}
            </ButtonBase>
        </div>
    </div>
</template>

<script>
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import {mapGetters} from "vuex";

    export default {
        name: 'SharedSingleItem',
        components: {
            ButtonBase,
        },
        computed: {
            ...mapGetters([
                'sharedDetail',
                'sharedFile',
            ]),
        },
        methods: {
            download() {
                this.$downloadFile(this.sharedFile.data.attributes.file_url, this.sharedFile.data.attributes.name + '.' + this.sharedFile.data.attributes.mimetype)
            },
        },
        mounted() {
            if (!this.sharedDetail) {
                this.$store.dispatch('getShareDetail', this.$route.params.token).then(() => {
                    this.$store.dispatch('getSingleFile')
                })
            } else {
                this.$store.dispatch('getSingleFile')
            }
        }
    }
</script>

<style lang="scss">
    #single-file {
        position: absolute;
        bottom: 0;
        right: 0;
        left: 0;
        top: 0;
        display: grid;
        height: 100%;

        .single-file-wrapper {
            margin: auto;
            text-align: center;

            .download-button {
                margin-top: 15px;
                margin-left: auto;
                margin-right: auto;
            }
        }

        /deep/ .file-wrapper {

            .file-item {
                width: 290px;

                &:hover, &.is-clicked {
                    background: transparent;
                }

                .item-shared {
                    display: none;
                }
            }
        }
    }
</style>