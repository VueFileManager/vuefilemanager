 <template>
     <div class="cell-image-thumbnail">
         <div class="image" :class="imageSize" v-if="image">
             <img :src="image" :alt="title">
         </div>
         <div class="info">
             <b class="name" v-if="title">{{ title }}</b>
             <span class="description" v-if="description">{{ description }}</span>
         </div>
     </div>
</template>

<script>
    export default {
        name:'DatatableCellImage',
        props: ['image', 'title', 'description', 'image-size'],
    }
</script>

<style lang="scss" scoped>
    @import '@assets/vue-file-manager/_variables';
    @import '@assets/vue-file-manager/_mixins';

    .cell-image-thumbnail {
        display: flex;
        align-items: center;
        cursor: pointer;

        .image {
            margin-right: 20px;
            line-height: 0;

            img {
                line-height: 0;
                width: 48px;
                height: 48px;
                border-radius: 8px;
            }

            &.small {
                img {
                    width: 32px;
                    height: 32px;
                }
            }
        }

        .info {

            .name, .description {
                max-width: 150px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                display: block;
            }

            .name {
                @include font-size(15);
                line-height: 1;
                color: $text;
            }

            .description {
                color: $text-muted;
                @include font-size(12);
            }
        }
    }

    @media (prefers-color-scheme: dark) {

        .cell-image-thumbnail {

            .info {

                .description {
                    color: $dark_mode_text_secondary;
                }
            }
        }
    }
</style>
