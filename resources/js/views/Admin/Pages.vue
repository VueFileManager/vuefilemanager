<template>
    <div>
        <div id="page-content" v-show="! isLoading">
			<div class="card shadow-card">
				<DatatableWrapper @init="isLoading = false" api="/api/admin/pages" :paginator="false" :columns="columns" class="table table-users">
					<template slot-scope="{ row }">
						<tr class="border-b dark:border-opacity-5 border-light border-dashed">
							<td class="py-4">
								<router-link :to="{name: 'PageEdit', params: {slug: row.data.attributes.slug}}" class="text-sm font-bold cursor-pointer" tag="div">
									{{ row.data.attributes.title }}
								</router-link>
							</td>
							<td>
								<span class="text-sm font-bold">
									{{ row.data.attributes.slug }}
								</span>
							</td>
							<td>
								<span class="text-sm font-bold">
									<SwitchInput @input="changeStatus($event, row.data.attributes.slug)" class="switch" :state="row.data.attributes.visibility"/>
								</span>
							</td>
							<td>
								<div class="flex space-x-2 w-full justify-end">
									<router-link class="flex items-center justify-center w-8 h-8 rounded-md dark:bg-2x-dark-foreground hover:bg-green-100 bg-light-background transition-colors" :to="{name: 'PageEdit', params: {slug: row.data.attributes.slug}}">
										<Edit2Icon size="15" class="opacity-75" />
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
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
    import EmptyPageContent from '/resources/js/components/Others/EmptyPageContent'
    import SwitchInput from '/resources/js/components/Others/Forms/SwitchInput'
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
    import SectionTitle from '/resources/js/components/Others/SectionTitle'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import Spinner from '/resources/js/components/FilesView/Spinner'
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
                this.$updateText('/admin/pages/' + slug, 'visibility', val)
            }
        },
    }
</script>

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

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

    .dark {

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
