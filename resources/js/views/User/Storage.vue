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
				154.98MB
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ $t('In last 30 days') }}
			</b>

			<div class="flex items-end justify-between h-28">
				<span class="w-2.5 block rounded-lg lg:mr-2 mr-1.5" :style="{height: Math.random() * 100 + '%', backgroundColor: '#9d66fe'}" v-for="(item, i) in Array(45).fill(0)" :key="i"></span>
			</div>
		</div>
		<div v-if="distribution" class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Download') }}
            </FormLabel>

			<b class="text-3xl font-extrabold -mt-3 block mb-0.5">
				927.12MB
			</b>

			<b class="mb-3 block text-sm text-gray-400 mb-5">
				{{ $t('In last 30 days') }}
			</b>

			<div class="flex items-end justify-between h-28">
				<span class="w-2.5 bg-theme block rounded-lg lg:mr-2 mr-1.5" :style="{height: Math.random() * 100 + '%', backgroundColor: '#ffb822'}" v-for="(item, i) in Array(45).fill(0)" :key="i"></span>
			</div>
		</div>
    </PageTab>
</template>

<script>
	import ProgressLine from "../../components/Admin/ProgressLine";
    import FormLabel from '/resources/js/components/Others/Forms/FormLabel'
    import PageTab from '/resources/js/components/Others/Layout/PageTab'
    import Spinner from '/resources/js/components/FilesView/Spinner'
    import axios from 'axios'

    export default {
        name: 'Storage',
        components: {
			ProgressLine,
            FormLabel,
            PageTab,
            Spinner,
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
