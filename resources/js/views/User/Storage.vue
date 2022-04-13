<template>
    <PageTab>
        <div v-if="distribution" class="card shadow-card">
            <FormLabel icon="hard-drive">
                {{ $t('storage_usage') }}
            </FormLabel>

            <b class="-mt-3 block text-2xl font-extrabold sm:text-3xl">
                {{ storage.data.attributes.used }}
            </b>

            <b
                v-if="
                    config.subscriptionType === 'fixed' || (config.subscriptionType === 'none' && config.storageLimit)
                "
                class="mt-0.5 block text-sm dark:text-gray-500 text-gray-400"
            >
                {{ $t('total_of', {capacity: storage.data.attributes.capacity}) }}
                {{ $t('used') }}
            </b>

            <ProgressLine v-if="storage.data.attributes.used !== '0B'" :data="distribution" class="mt-5" />
        </div>
        <div v-if="distribution" class="card shadow-card">
            <FormLabel icon="hard-drive">
                {{ $t('upload') }}
            </FormLabel>

            <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                {{ storage.data.meta.traffic.upload }}
            </b>

            <b class="mb-3 mb-5 block text-sm dark:text-gray-500 text-gray-400">
                {{ $t('in_last_x_days') }}
            </b>

            <BarChart :data="storage.data.meta.traffic.chart.upload" color="#FFBD2D" />
        </div>
        <div v-if="distribution" class="card shadow-card">
            <FormLabel icon="hard-drive">
                {{ $t('download') }}
            </FormLabel>

            <b class="-mt-3 mb-0.5 block text-2xl font-extrabold sm:text-3xl">
                {{ storage.data.meta.traffic.download }}
            </b>

            <b class="mb-3 mb-5 block text-sm dark:text-gray-500 text-gray-400">
                {{ $t('in_last_x_days') }}
            </b>

            <BarChart :data="storage.data.meta.traffic.chart.download" color="#9d66fe" />
        </div>
    </PageTab>
</template>

<script>
import ProgressLine from '../../components/UI/ProgressChart/ProgressLine'
import FormLabel from '../../components/UI/Labels/FormLabel'
import PageTab from '../../components/Layout/PageTab'
import axios from 'axios'
import BarChart from '../../components/UI/BarChart/BarChart'
import { mapGetters } from 'vuex'

export default {
    name: 'Storage',
    components: {
        BarChart,
        ProgressLine,
        FormLabel,
        PageTab,
    },
    computed: {
        ...mapGetters(['config']),
    },
    data() {
        return {
            isLoading: true,
            distribution: undefined,
            storage: undefined,
        }
    },
    created() {
        axios.get('/api/user/storage').then((response) => {
            this.distribution = this.$mapStorageUsage(response.data)

            this.storage = response.data

            this.isLoading = false
        })
    },
}
</script>
