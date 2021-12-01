<template>
    <div>
        <div class="card shadow-card">
            <div class="mb-6">
                <router-link :to="{name: 'UserCreate'}">
                    <MobileActionButton icon="user-plus">
                        {{ $t('admin_page_user.create_user.submit') }}
                    </MobileActionButton>
                </router-link>

				<MobileActionButton @click.native="$openSpotlight('users')" icon="search">
					{{ $t('Search') }}
				</MobileActionButton>
            </div>

			<!--Datatable-->
            <DatatableWrapper @init="isLoading = false" api="/api/admin/users" :paginator="true" :columns="columns" class="table table-users">
                <template slot-scope="{ row }">
                    <tr class="border-b dark:border-opacity-5 border-light border-dashed">
                        <td class="py-3">
							<router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
								<div class="flex items-center">
									<MemberAvatar
										:is-border="false"
										:size="44"
										:member="row.data.relationships.settings"
									/>
									<div class="ml-3">
										<b class="text-sm font-bold block max-w-1 overflow-hidden overflow-ellipsis whitespace-nowrap" style="max-width: 155px;">
											{{ row.data.relationships.settings.data.attributes.name }}
										</b>
										<span class="block text-xs dark:text-gray-500 text-gray-600">
											{{ row.data.attributes.email }}
										</span>
									</div>
								</div>
							</router-link>
                        </td>
                        <td>
                            <ColorLabel :color="getRoleColor(row.data.attributes.role)">
                                {{ row.data.attributes.role }}
                            </ColorLabel>
                        </td>
                        <td v-if="config.isSaaS">
                            <span class="text-sm font-bold">
                            	{{ row.data.relationships.subscription ? $t('global.premium') : $t('global.free') }}
                            </span>
                        </td>
                        <td>
                            <span v-if="row.data.attributes.storage.capacity !== 0" class="text-sm font-bold">
                            	{{ row.data.attributes.storage.used_formatted }}
                            </span>
                            <span v-if="row.data.attributes.storage.capacity === 0" class="text-sm font-bold">
                            	-
                            </span>
                        </td>
                        <td v-if="config.storageLimit">
                            <span v-if="row.data.attributes.storage.capacity !== 0" class="text-sm font-bold">
                            	{{ row.data.attributes.storage.capacity_formatted }}
                            </span>
                            <span v-if="row.data.attributes.storage.capacity === 0" class="text-sm font-bold">
                            	-
                            </span>
                        </td>
                        <td>
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.created_at }}
                            </span>
                        </td>
                        <td>
                            <div class="flex space-x-2 w-full justify-end">
                                <router-link class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-green-100 dark:bg-2x-dark-foreground bg-light-background transition-colors" :to="{name: 'UserDetail', params: {id: row.data.id}}">
                                    <Edit2Icon size="15" class="opacity-75" />
                                </router-link>
                                <router-link class="flex items-center justify-center w-8 h-8 rounded-md hover:bg-red-100 dark:bg-2x-dark-foreground bg-light-background transition-colors" :to="{name: 'UserDelete', params: {id: row.data.id}}">
                                    <Trash2Icon size="15" class="opacity-75" />
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </div>
        <div id="loader" v-if="isLoading">
            <Spinner></Spinner>
        </div>
    </div>
</template>

<script>
	import MemberAvatar from "../../components/FilesView/MemberAvatar";
    import DatatableCellImage from '/resources/js/components/Others/Tables/DatatableCellImage'
    import DatatableWrapper from '/resources/js/components/Others/Tables/DatatableWrapper'
    import MobileActionButton from '/resources/js/components/FilesView/MobileActionButton'
    import MobileHeader from '/resources/js/components/Mobile/MobileHeader'
    import SectionTitle from '/resources/js/components/Others/SectionTitle'
    import ButtonBase from '/resources/js/components/FilesView/ButtonBase'
    import PageHeader from '/resources/js/components/Others/PageHeader'
    import ColorLabel from '/resources/js/components/Others/ColorLabel'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import {Trash2Icon, Edit2Icon} from "vue-feather-icons";
    import {mapGetters} from "vuex"
    import axios from 'axios'

    export default {
        name: 'Profile',
        components: {
            DatatableCellImage,
            MobileActionButton,
            DatatableWrapper,
			MemberAvatar,
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
                        return 'green'
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
                    sortable: false,
                },
                {
                    label: this.$t('admin_page_user.table.storage_used'),
                    sortable: false
                },
                {
                    label: this.$t('Max Storage'),
                    sortable: false,
                    hidden: ! this.config.storageLimit,
                },
                {
                    label: this.$t('admin_page_user.table.created_at'),
                    field: 'created_at',
                    sortable: true
                },
                {
                    label: this.$t('admin_page_user.table.action'),
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
