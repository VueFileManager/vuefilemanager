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
            <DatatableWrapper @init="isLoading = false" api="/api/admin/users" :paginator="true" :columns="columns" class="overflow-x-auto">
                <template slot-scope="{ row }">
					<!--Not a subscription-->
                    <tr v-if="config.subscriptionType === 'none'" class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
                        <td class="py-3 md:pr-1 pr-3">
							<router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
								<div class="flex items-center">
									<MemberAvatar
										:is-border="false"
										:size="44"
										:member="row.data.relationships.settings"
									/>
									<div class="ml-3 pr-10">
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
                        <td class="md:px-1 px-3">
                            <ColorLabel :color="$getUserRoleColor(row.data.attributes.role)">
                                {{ row.data.attributes.role }}
                            </ColorLabel>
                        </td>
                        <td class="md:px-1 px-3">
                            <span v-if="row.data.attributes.storage.capacity !== 0" class="text-sm font-bold">
                            	{{ row.data.attributes.storage.used_formatted }}
                            </span>
                            <span v-if="row.data.attributes.storage.capacity === 0" class="text-sm font-bold">
                            	-
                            </span>
                        </td>
                        <td class="md:px-1 px-3" v-if="config.storageLimit">
                            <span v-if="row.data.attributes.storage.capacity !== 0" class="text-sm font-bold">
                            	{{ row.data.attributes.storage.capacity_formatted }}
                            </span>
                            <span v-if="row.data.attributes.storage.capacity === 0" class="text-sm font-bold">
                            	-
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.created_at }}
                            </span>
                        </td>
                        <td class="md:pl-1 pl-3 text-right">
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

					<!--Fixed subscription-->
                    <tr v-if="config.subscriptionType === 'fixed'" class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
                        <td class="py-3 md:pr-1 pr-3">
							<router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
								<div class="flex items-center">
									<MemberAvatar
										:is-border="false"
										:size="44"
										:member="row.data.relationships.settings"
									/>
									<div class="ml-3 pr-10">
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
                        <td class="md:px-1 px-3">
                            <ColorLabel :color="$getUserRoleColor(row.data.attributes.role)">
                                {{ row.data.attributes.role }}
                            </ColorLabel>
                        </td>
                        <td class="md:px-1 px-3" v-if="config.isSaaS">
                            <span class="text-sm font-bold">
                            	{{ row.data.relationships.subscription ? $t('global.premium') : $t('global.free') }}
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span v-if="row.data.attributes.storage.capacity !== 0" class="text-sm font-bold">
                            	{{ row.data.attributes.storage.used_formatted }}
                            </span>
                            <span v-if="row.data.attributes.storage.capacity === 0" class="text-sm font-bold">
                            	-
                            </span>
                        </td>
                        <td class="md:px-1 px-3" v-if="config.storageLimit">
                            <span v-if="row.data.attributes.storage.capacity !== 0" class="text-sm font-bold">
                            	{{ row.data.attributes.storage.capacity_formatted }}
                            </span>
                            <span v-if="row.data.attributes.storage.capacity === 0" class="text-sm font-bold">
                            	-
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.created_at }}
                            </span>
                        </td>
                        <td class="md:pl-1 pl-3 text-right">
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

					<!--Metered subscription-->
					<tr v-if="config.subscriptionType === 'metered'" class="border-b dark:border-opacity-5 border-light border-dashed whitespace-nowrap">
                        <td class="py-3 md:pr-1 pr-3">
							<router-link :to="{name: 'UserDetail', params: {id: row.data.id}}">
								<div class="flex items-center">
									<MemberAvatar
										:is-border="false"
										:size="44"
										:member="row.data.relationships.settings"
									/>
									<div class="ml-3 pr-10">
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
                        <td class="md:px-1 px-3">
                            <ColorLabel :color="$getUserRoleColor(row.data.attributes.role)">
                                {{ row.data.attributes.role }}
                            </ColorLabel>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.meta.usages.featureEstimates.storage.usage }}
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.meta.usages.costEstimate }}
                            </span>
                        </td>
                        <td class="md:px-1 px-3">
                            <span class="text-sm font-bold">
                            	{{ row.data.attributes.created_at }}
                            </span>
                        </td>
                        <td class="md:pl-1 pl-3 text-right">
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
        name: 'Users',
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
            ...mapGetters([
				'config'
			]),
			columns() {
				return {
					metered: [
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
							label: this.$t('admin_page_user.table.storage_used'),
							sortable: false
						},
						{
							label: this.$t('Billing Est.'),
							sortable: false,
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
					],
					fixed: [
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
					],
					none: [
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
					],
				}[this.config.subscriptionType]
			}
        },
        data() {
            return {
                isLoading: true,
            }
        },
    }
</script>
