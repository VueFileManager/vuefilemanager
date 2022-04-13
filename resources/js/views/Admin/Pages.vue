<template>
    <div>
        <div class="card shadow-card">
            <DatatableWrapper
                @init="isLoading = false"
                api="/api/admin/pages"
                :paginator="false"
                :columns="columns"
                class="overflow-x-auto"
            >
                <template slot-scope="{ row }">
                    <tr class="whitespace-nowrap border-b border-dashed border-light dark:border-opacity-5">
                        <td class="py-5 pr-3 md:pr-1">
                            <router-link
                                :to="{
                                    name: 'PageEdit',
                                    params: { slug: row.data.attributes.slug },
                                }"
                                class="cursor-pointer text-sm font-bold"
                                tag="div"
                            >
                                {{ row.data.attributes.title }}
                            </router-link>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold">
                                {{ row.data.attributes.slug }}
                            </span>
                        </td>
                        <td class="px-3 md:px-1">
                            <span class="text-sm font-bold">
                                <SwitchInput
                                    @input="
                                        $updateText(
                                            `/admin/pages/${row.data.id}`,
                                            'visibility',
                                            row.data.attributes.visibility
                                        )
                                    "
                                    v-model="row.data.attributes.visibility"
                                    :state="row.data.attributes.visibility"
                                    class="switch"
                                />
                            </span>
                        </td>
                        <td class="pl-3 text-right md:pl-1">
                            <div class="flex w-full justify-end space-x-2">
                                <router-link
                                    class="flex h-8 w-8 items-center justify-center rounded-md bg-light-background transition-colors hover:bg-green-100 dark:bg-2x-dark-foreground"
                                    :to="{
                                        name: 'PageEdit',
                                        params: {
                                            slug: row.data.attributes.slug,
                                        },
                                    }"
                                >
                                    <Edit2Icon size="15" class="opacity-75" />
                                </router-link>
                            </div>
                        </td>
                    </tr>
                </template>
            </DatatableWrapper>
        </div>
    </div>
</template>

<script>
import DatatableWrapper from '../../components/UI/Table/DatatableWrapper'
import MobileActionButton from '../../components/UI/Buttons/MobileActionButton'
import EmptyPageContent from '../../components/Others/EmptyPageContent'
import SwitchInput from '../../components/Inputs/SwitchInput'
import MobileHeader from '../../components/Mobile/MobileHeader'
import SectionTitle from '../../components/UI/Labels/SectionTitle'
import ButtonBase from '../../components/UI/Buttons/ButtonBase'
import { Trash2Icon, Edit2Icon } from 'vue-feather-icons'
import ColorLabel from '../../components/UI/Labels/ColorLabel'
import Spinner from '../../components/UI/Others/Spinner'

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
                    label: this.$t('page'),
                    field: 'title',
                    sortable: true,
                },
                {
                    label: this.$t('slug'),
                    field: 'slug',
                    sortable: true,
                },
                {
                    label: this.$t('status'),
                    field: 'visibility',
                    sortable: true,
                },
                {
                    label: this.$t('action'),
                    sortable: false,
                },
            ],
        }
    },
}
</script>
