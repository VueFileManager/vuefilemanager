<template>
    <PageTab>
		<div class="card shadow-card">
			<FormLabel icon="hard-drive">
                {{ $t('Storage Usage') }}
            </FormLabel>

			<div v-if="distribution">
				<b class="mb-3 block text-sm text-gray-400">
					{{ $t('Total') }} {{ storage.data.attributes.used }} {{ $t('of') }} {{ storage.data.attributes.capacity }} {{ $t('Used') }}
				</b>
				<ProgressLine :data="distribution" />
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

<style lang="scss" scoped>
    @import '/resources/sass/vuefilemanager/_variables';
    @import '/resources/sass/vuefilemanager/_mixins';

    #single-page {
        overflow: hidden;
        width: 100%;
        height: 100%;
        position: relative;

        .content-page {
            overflow-y: auto;
            height: 100%;
            padding-bottom: 100px;
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
        }
    }

    @media only screen and (max-width: 960px) {

        #single-page {

            .content-page {
                padding-left: 15px;
                padding-right: 15px;
            }
        }
    }

    .dark {


    }

</style>
