<template>
    <div>
        <ul class="meta-data-list">
                    <li v-if="fileInfoDetail.meta_data.Artist"> {{$t('file_detail_meta.author')}}
                         <p> {{fileInfoDetail.meta_data.Artist}} </p> </li>

                    <li v-if="fileInfoDetail.meta_data.DateTimeOriginal">{{$t('file_detail_meta.time_data')}}
                         <p> {{fileInfoDetail.meta_data.DateTimeOriginal}} </p> </li>     

                    <li v-if="fileInfoDetail.meta_data.Make">{{$t('file_detail_meta.make')}} 
                        <p> {{fileInfoDetail.meta_data.Make}} </p> </li>

                    <li v-if="fileInfoDetail.meta_data.Model">{{$t('file_detail_meta.model')}}
                        <p> {{fileInfoDetail.meta_data.Model}} </p> </li>

                    <li v-if="fileInfoDetail.meta_data.COMPUTED.CCDWidth">{{$t('file_detail_meta.camera_lens')}} 
                        <p> {{fileInfoDetail.meta_data.COMPUTED.CCDWidth}} </p> </li>

                    <li v-if="fileInfoDetail.meta_data.COMPUTED.ApertureFNumber">{{$t('file_detail_meta.aperature')}} 
                        <p> {{fileInfoDetail.meta_data.COMPUTED.ApertureFNumber}} </p> </li>

                    <li v-if="fileInfoDetail.meta_data.ISOSpeedRatings">{{$t('file_detail_meta.iso')}}
                        <p> {{fileInfoDetail.meta_data.ISOSpeedRatings}} </p> </li>

                    <li v-if="fileInfoDetail.meta_data.FocalLength">{{$t('file_detail_meta.focal')}} 
                        <p> {{fileInfoDetail.meta_data.FocalLength}} </p> </li>

                    <li v-if="fileInfoDetail.meta_data.ExposureTime">{{$t('file_detail_meta.exposure')}} 
                        <p> {{fileInfoDetail.meta_data.ExposureTime}} </p> </li>

                    <li v-else>{{$t('file_detail_meta.not_avaible')}} </li> 

                </ul>
    </div>
</template>

<script>
    import {mapGetters} from 'vuex'

    export default {
        name: 'ImageMetaData',
        computed: {
            ...mapGetters(['fileInfoDetail']),
        },
    }
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

 .meta-data-list {
            list-style: none;
            padding: 0px;
            margin: 0px;
          li {
            margin-top:10px;
            @include font-size(14);
            font-weight: bold;
            color: $text;
            p {
            @include font-size(13);
            color: $text;
            }
           }
        }
 @media (prefers-color-scheme: dark) {
     li {
         color:$dark_mode_text_primary !important;
         p {
             color: $dark_mode_text_primary !important;
         }
     }
 }
</style>