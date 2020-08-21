<template>
    <div id="single-page">
        <div id="page-content" v-show="! isLoading">
            <MobileHeader :title="$router.currentRoute.meta.title"/>
            <PageHeader :title="$router.currentRoute.meta.title"/>

            <div class="content-page">
                <DatatableWrapper @init="isLoading = false" api="/api/pages" :paginator="false" :columns="columns" class="table table-users">
                    <template slot-scope="{ row }">
                        <tr>
                            <td class="name" style="min-width: 200px">
                                <router-link :to="{name: 'PageEdit', params: {slug: row.data.attributes.slug}}" class="cell-item" tag="div">
                                    <span>{{ row.data.attributes.title }}</span>
                                </router-link>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.slug }}
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    <SwitchInput @input="changeStatus($event, row.data.attributes.slug)" class="switch" :state="row.data.attributes.visibility"/>
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <router-link :to="{name: 'PageEdit', params: {slug: row.data.attributes.slug}}">
                                        <edit-2-icon size="15" class="icon icon-edit"></edit-2-icon>
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </template>
                </DatatableWrapper>
            </div>
        </div>

        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import DatatableWrapper from '@/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '@/components/FilesView/MobileActionButton'
    import EmptyPageContent from '@/components/Others/EmptyPageContent'
    import SwitchInput from '@/components/Others/Forms/SwitchInput'
    import MobileHeader from '@/components/Mobile/MobileHeader'
    import SectionTitle from '@/components/Others/SectionTitle'
    import ButtonBase from '@/components/FilesView/ButtonBase'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import PageHeader from '@/components/Others/PageHeader'
    import ColorLabel from '@/components/Others/ColorLabel'
    import Spinner from '@/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Pages',
        components: {
            MobileActionButton,
            EmptyPageContent,
            DatatableWrapper,
            SectionTitle,
            MobileHeader,
            SwitchInput,
            Trash2Icon,
            PageHeader,
            ButtonBase,
            ColorLabel,
            Edit2Icon,
            Spinner,
        },
        data() {
            return {
                isLoading: true,
                columns: [
                    {
                        label: this.$t('admin_pages.table.page'),
                        field: 'title',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_pages.table.slug'),
                        field: 'slug',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_pages.table.status'),
                        field: 'visibility',
                        sortable: true
                    },
                    {
                        label: this.$t('admin_page_user.table.action'),
                        sortable: false
                    },
                ],
            }
        },
        methods: {
            changeStatus(val, slug) {
                this.$updateText('/pages/' + slug, 'visibility', val)
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .table-tools {
        background: white;
        display: flex;
        justify-content: space-between;
        padding: 15px 0 10px;
        position: sticky;
        top: 40px;
        z-index: 9;
    }

    .table {

        .cell-item {
            @include font-size(15);
            white-space: nowrap;
        }

        .name {
            font-weight: 700;
            cursor: pointer;
        }
    }

    @media only screen and (max-width: 690px) {
        .table-tools {
            padding: 0 0 5px;
        }
    }

    @media (prefers-color-scheme: dark) {

        .table-tools {
            background: $dark_mode_background;
        }

        .action-icons {

            .icon {
                cursor: pointer;

                circle, path, line, polyline {
                    stroke: $dark_mode_text_primary;
                }
            }
        }

        .user-thumbnail {

            .info {

                .email {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }

</style>
