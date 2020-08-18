<template>
  <div class="media-full-preview"  id="mediaPreview">
    <div class="file-wrapper-preview" v-for="i in [currentIndex]" :key="i" >
      <div class="file-wrapper" >
        <audio
          class="file"
          v-if="fileInfoDetail.type == 'audio'"
          :src="currentFile.file_url"
          controlsList="nodownload"
          controls
        ></audio>

        <img
          v-if="fileInfoDetail.type === 'image' && currentFile.thumbnail"
          class="file"
          id="image"
          :src="currentFile.file_url"
          v-on:load="onLoaded"
        />
        <video
          v-if="fileInfoDetail.type === 'video' && currentFile.file_url"
          :src="currentFile.file_url"
          class="file"
          :style="{ width: sizeWidth, height: sizeHeight }"
          controlsList="nodownload"
          disablePictureInPicture
          playsinline
          controls
        />
        <spinner v-if="!loaded && fileInfoDetail.type === 'image'" />
      </div>
    </div>
  </div>
</template>

<script>
import { events } from '@/bus';
import { mapGetters } from 'vuex';
import ToolbarButton from '@/components/FilesView/ToolbarButton';
import Spinner from '@/components/FilesView/Spinner';

export default {
  components: { ToolbarButton, Spinner },
  data() {
    return {
      currentIndex: 1,
      sliderFile: [],
      loaded: false,
      sizeWidth: '',
      sizeHeight: '',
    };
  },
  computed: {
    ...mapGetters(['fileInfoDetail', 'data']),

    currentFile: function() {
      return this.sliderFile[
        Math.abs(this.currentIndex) % this.sliderFile.length
      ];
    },

    canShareInView() {
      return !this.$isThisLocation([
        'base',
        'participant_uploads',
        'latest',
        'shared',
        'public',
      ]);
    },
  },
  mounted() {
    events.$on('filePreviewAction:next', () => {
      this.currentIndex += 1;
      this.slideType = 'next'
      if (this.currentIndex > this.sliderFile.length - 1) {
        this.currentIndex = 0;
      }
    });
    events.$on('filePreviewAction:prev', () => {
      this.slideType = 'prev'
      this.currentIndex -= 1;
      if (this.currentIndex < 0) {
        this.currentIndex = this.sliderFile.length - 1;
      }
    });
  },
  created() {
    this.filteredFiles();
    this.imageSizing();
  },
  watch: {
    currentFile() {
      //HANDLE ACUTAL VIEW IMAGE IN FIELINFODETAIL
      this.$store.commit('GET_FILEINFO_DETAIL', this.currentFile);
      events.$emit('actualShowingImage:ContextMenu', this.currentFile);
    },
    fileInfoDetail() {
      //FILE DELETE HANDLING
      if (!this.fileInfoDetail) {
        if (this.data.length == 0) {
          events.$emit('fileFullPreview:hide');
        } else {
          this.currentIndex = this.currentIndex - 1;
          this.$store.commit('GET_FILEINFO_DETAIL', this.currentFile);
          this.sliderFile = [];
          this.filteredFiles();
        }
      }
    },
    data(newValue, oldValue) {
      //MOVE ITEM HANDLING
      if (newValue != oldValue) {
        this.sliderFile = [];
        this.filteredFiles();
      }
    },
  },
  methods: {
    imageSizing() {
      if (this.$isMobile()) {
        this.sizeWidth = '100%';
        this.sizeHeight = 'auto';
      } else {
        this.sizeWidth = 'auto';
        this.sizeHeight = '100%';
      }
    },
    closeFullPreview() {
      events.$emit('fileFullPreview:hide');
    },


    filteredFiles() {
      this.data.filter((element) => {
        if (element.type == this.fileInfoDetail.type) {
          this.sliderFile.push(element);
        }
      });
      this.choseActiveFile();
    },
    onLoaded() {
      this.loaded = true;
    },
    choseActiveFile() {
      this.sliderFile.forEach((element, index) => {
        if (element.unique_id == this.fileInfoDetail.unique_id) {
          this.currentIndex = index;
        }
      });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

.media-full-preview {
  height: 93.1%;
  position: relative;
}

.navigation-panel {
  width: 100%;
  height: 7%;
  display: flex;
  align-items: center;
  padding: 20px;
  justify-content: space-between;
  background-color: $light-background;
  color: $text;
  .icon-close {
    color: $text;
    @include font-size(21);
    &:hover {
      color: $theme;
    }
  }
}

.file-wrapper-preview {
  width: 100%;
  height: 100%;
  padding: 30px 0px;
  display: flex;
  overflow: hidden;
  justify-content: center;
  align-items: center;
  background-color: white;
  .file-wrapper {
    width: 100%;
    height: 100%;
    display: flex;
    justify-content: center;
    .file {
      max-width: 100%;
      max-height: 100%;
      align-self: center;
    }
  }
}



@media (prefers-color-scheme: dark) {
  .file-wrapper-preview {
    background-color: $dark_mode_background;
  }
}
</style>
