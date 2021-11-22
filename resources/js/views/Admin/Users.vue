<template>
    <div>
		<div class="card shadow-card">

			<div class="mb-6">
				<router-link :to="{name: 'UserCreate'}">
					<MobileActionButton icon="user-plus">
						{{ $t('admin_page_user.create_user.submit') }}
					</MobileActionButton>
				</router-link>
			</div>

			<!--Datatable-->
			<DatatableWrapper @init="isLoading = false" api="/api/admin/users" :paginator="true" :columns="columns" class="table table-users">
                    <template slot-scope="{ row }">
                        <tr>
                            <td style="min-width: 320px">
                                <router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
                                    <DatatableCellImage
										:image="row.data.relationships.settings.data.attributes.avatar.sm"
										:title="row.data.relationships.settings.data.attributes.name"
										:description="row.data.attributes.email"
									/>
                                </router-link>
                            </td>
                            <td>
                                <ColorLabel :color="getRoleColor(row.data.attributes.role)">
                                    {{ row.data.attributes.role }}
                                </ColorLabel>
                            </td>
                            <td v-if="config.isSaaS">
                                <span class="cell-item" v-if="row.data.attributes.subscription">
                                    {{ $t('global.premium') }}
                                </span>
                                <span class="cell-item" v-else>
                                    {{ $t('global.free') }}
                                </span>
                            </td>
                            <td>
                                <span v-if="row.data.attributes.storage.capacity !== 0" class="cell-item">
                                    {{ row.data.attributes.storage.used_formatted }}
                                </span>
                                <span v-if="row.data.attributes.storage.capacity == 0" class="cell-item">
                                    -
                                </span>
                            </td>
                            <td v-if="config.storageLimit">
                                <span v-if="row.data.attributes.storage.capacity !== 0" class="cell-item">
                                    {{ row.data.attributes.storage.capacity_formatted }}
                                </span>
                                <span v-if="row.data.attributes.storage.capacity == 0" class="cell-item">
                                    -
                                </span>
                            </td>
                            <td>
                                <span class="cell-item">
                                    {{ row.data.attributes.created_at_formatted }}
                                </span>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
                                        <Edit2Icon size="15" class="icon icon-edit" />
                                    </router-link>
                                    <router-link :to="{name: 'UserDelete', params: {id: row.data.id}}">
                                        <Trash2Icon size="15" class="icon icon-trash" />
                                    </router-link>
                                </div>
                            </td>
                        </tr>
                    </template>
                </DatatableWrapper>
		</div>

<!--        <div id="page-content">

            <div class="content-page">

                &lt;!&ndash;Table tools&ndash;&gt;
                <div class="table-tools">
                    <div class="buttons">
                    </div>
                </div>


            </div>
        </div>-->
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
    import DatatableCellImage from '/resources/js/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
    import SectionTitle from '/resources/js/components/Others/SectionTitle'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import {mapGetters} from "vuex"
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            DatatableCellImage,
            MobileActionButton,
            DatatableWrapper,
            SectionTitle,
            MobileHeader,
            Trash2Icon,
            PageHeader,
            ButtonBase,
            ColorLabel,
            Edit2Icon,
            Spinner,
        },
        computed: {
            ...mapGetters(['config']),
        },
        data() {
            return {
                isLoading: true,
                columns: undefined,
            }
        },
        methods: {
            getRoleColor(role) {
                switch(role) {
                    case 'admin':
                        return 'purple'
                        break;
                    case 'user':
                        return 'yellow'
                        break;
                }
            }
        },
        created() {
            this.columns = [
                {
                    label: this.$t('admin_page_user.table.name'),
                    field: 'email',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.role'),
                    field: 'role',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.plan'),
                    field: 'subscription',
                    sortable: false,
                    hidden: ! this.config.isSaaS,
                },
                {
                    label: this.$t('admin_page_user.table.storage_used'),
                    field: 'used',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.max_storage_amount'),
                    field: 'settings.max_storage_amount',
                    sortable: true,
                    hidden: ! this.config.storageLimit,
                },
                {
                    label: this.$t('admin_page_user.table.created_at'),
                    field: 'created_at',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.action'),
                    field: 'data.action',
                    sortable: false
                },
            ]
        }
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
    }

</style>
