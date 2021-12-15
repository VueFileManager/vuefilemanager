<template>
    <PageTab>
		<div v-if="distribution" class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Storage Usage') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ storage.data.attributes.used }}
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ $t('Total of') }} {{ storage.data.attributes.capacity }} {{ $t('Used') }}
			</b>

			<ProgressLine :data="distribution" />
		</div>
		<div v-if="distribution" class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Upload') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ storage.data.meta.traffic.upload }}
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ $t('In last 45 days') }}
			</b>

			<BarChart :data="storage.data.meta.traffic.chart.upload" color="#FFBD2D" />
		</div>
		<div v-if="distribution" class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Download') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				{{ storage.data.meta.traffic.download }}
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ $t('In last 45 days') }}
			</b>

			<BarChart :data="storage.data.meta.traffic.chart.download" color="#9d66fe" />
		</div>
    </PageTab>
</template>

<script>
	import ProgressLine from "../../components/Admin/ProgressLine";
	import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
	import PageTab from '/resources/js/components/Others/Layout/PageTab'
	import axios from 'axios'
	import BarChart from "../../components/UI/BarChart";

	export default {
        name: 'Storage',
        components: {
			BarChart,
			ProgressLine,
            FormLabel,
            PageTab,

        },
        data() {
            return {
                isLoading: true,
				distribution: undefined,
				storage: undefined
            }
        },
        created() {
            axios.get('/api/user/storage')
                .then(response => {
					this.distribution = this.$mapStorageUsage(response.data)

					this.storage = response.data

                    this.isLoading = false
                })
        }
    }
</script>
