<template>
  <div class="media-full-preview" @keydown.esc="closeFullPreview">
    <div class="navigation-panel">
      <div class="name-wrapper">
        <div @click="closeFullPreview" class="icon-close">&#215;</div>
        <div class="name">
          <p>{{ formatedName }}</p>

          <p class="file-count">
            ({{
              this.currentIndex +
              1 +
              " " +
              $t("pronouns.of") +
              " " +
              this.sliderFile.length
            }})
          </p>
        </div>

        <div class="fast-menu" @click="menuOpen()">
          <p>...</p>
          <DesktopMediaPreviewMenu :fileInfoDetail="this.fileInfoDetail" />
        </div>
      </div>
      <div class="created_at-wrapper">
        <p>{{ currentFile.filesize }}, {{ currentFile.created_at }}</p>
      </div>
      <div class="navigation-icons">
        <ToolbarButton
          source="download"
          @click.native="downloadItem"
          :action="$t('actions.download')"
        />
        <ToolbarButton
          source="share"
          :class="{ 'is-inactive': canShareInView }"
          :action="$t('actions.share')"
          @click.native="shareItem"
        />
        <ToolbarButton
          v-if="this.fileInfoDetail.type === 'image'"
          source="print"
          :action="$t('actions.print')"
          @click.native="printMethod()"
        />
      </div>
    </div>

    <div class="file-wrapper-preview" v-for="i in [currentIndex]" :key="i">
      <div class="file-wrapper">
        <img
          v-if="fileInfoDetail.type === 'image' && currentFile.thumbnail"
          class="file"
          id="image"
          :src="currentFile.thumbnail"
          :style="{ width: sizeWidth, height: sizeHeight }"
          v-on:load="onLoaded"
          v-show="loaded"
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
    <a class="prev" @click.prevent="prev" href="#">&#10094; </a>
    <a class="next" @click.prevent="next" href="#">&#10095; </a>
  </div>
</template>

<script>
import { events } from "@/bus";
import { mapGetters } from "vuex";
import ToolbarButton from "@/components/FilesView/ToolbarButton";
import DesktopMediaPreviewMenu from "@/components/FilesView/DesktopMediaPreviewMenu";
import Spinner from "@/components/FilesView/Spinner";

export default {
  components: { ToolbarButton, DesktopMediaPreviewMenu, Spinner },
  data() {
    return {
      currentIndex: 1,
      sliderFile: [],
      loaded: false,
      sizeWidth: "",
      sizeHeight: "",
      showingFile: "",
    };
  },
  computed: {
    ...mapGetters(["fileInfoDetail", "data"]),

    currentFile: function () {
      return this.sliderFile[
        Math.abs(this.currentIndex) % this.sliderFile.length
      ];
    },
    formatedName() {
      let name = this.currentFile.name;
      if (name.lastIndexOf(".") > -1) {
        return _.truncate(name.substring(0, name.lastIndexOf(".")), {
          length: 20,
        });
      } else {
        return _.truncate(name, {
          length: 20,
        });
      }
    },

    canShareInView() {
      return !this.$isThisLocation([
        "base",
        "participant_uploads",
        "latest",
        "shared",
        "public",
      ]);
    },
  },
  created() {
    this.filteredFiles();
    this.imageSizing();
  },
  watch: {
    currentFile() {
      this.$store.commit("GET_FILEINFO_DETAIL", this.currentFile);
    },
    fileInfoDetail() {
      if (!this.fileInfoDetail) {
        if (this.data.length == 0) {
          events.$emit("fileFullPreview:hide");
        } else {
          this.currentIndex = this.currentIndex - 1;
          this.$store.commit("GET_FILEINFO_DETAIL", this.currentFile);
          this.sliderFile = [];
          this.filteredFiles();
          this.$forceUpdate();
        }
      }
    },
    data(newValue, oldValue) {
      if (newValue != oldValue) {
        this.sliderFile = [];
        this.filteredFiles();
      }
    },
  },
  methods: {
    imageSizing() {
      if (this.$isMobile()) {
        this.sizeWidth = "100%";
        this.sizeHeight = "auto";
      } else {
        this.sizeWidth = "auto";
        this.sizeHeight = "100%";
      }
    },
    menuOpen() {
      if (this.$isMobile()) {
        events.$emit("mobileMenu:show", "showFromMediaPreview");
      } else {
        events.$emit("desktopMediaMenu:show");
      }
    },
    closeFullPreview() {
      events.$emit("fileFullPreview:hide");
    },
    printMethod() {
      var tab = document.getElementById("image");
      var win = window.open("", "", "height=700,width=700");
      win.document.write(tab.outerHTML);
      win.document.close();
      win.print();
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
    downloadItem() {
      // Download file
      this.$downloadFile(
        this.currentFile.file_url,
        this.currentFile.name + "." + this.currentFile.mimetype
      );
    },

    next: function () {
      this.currentIndex += 1;
      if (this.currentIndex > this.sliderFile.length - 1) {
        this.currentIndex = 0;
      }
    },
    prev: function () {
      this.currentIndex -= 1;
      if (this.currentIndex < 0) {
        this.currentIndex = this.sliderFile.length - 1;
      }
    },

    shareItem() {
      if (this.fileInfoDetail.shared) {
        events.$emit("popup:open", {
          name: "share-edit",
          item: this.currentFile,
        });
      } else {
        events.$emit("popup:open", {
          name: "share-create",
          item: this.currentFile,
        });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.media-full-preview {
  height: 100%;
  position: relative;
}

.name-wrapper {
  width: 33%;
  height: 100%;
  display: flex;
  align-items: center;
  @include font-size(15);
  font-weight: 700;
  .name {
    display: flex;
  }
  .icon-close {
    @include font-size(15);
    font-weight: 700;
    display: flex;
    align-items: center;
    margin-right: 10px;
    color: $text;
    cursor: pointer;
  }
  .fast-menu {
    cursor: pointer;
    margin-left: 10px;
    p {
      margin-top: -10px;
    }
    p:hover {
      color: $theme;
    }
  }
}

@media (min-width: 420px) and (max-width: 750px) {
  .name-wrapper {
    width: 67%;
  }
}
@media (max-width: 430px) {
  .name-wrapper {
    width: 100%;
    justify-content: space-between;
  }
}

.created_at-wrapper {
  width: 33%;
  height: 100%;
  display: flex;
  text-align: center;
  justify-content: center;
  p {
    display: flex;
    align-items: center;
    @include font-size(11);
  }
  @media (max-width: 750px) {
    display: none;
  }
}

.navigation-icons {
  width: 33%;
  height: 100%;
  list-style: none;
  display: flex;
  align-items: center;
  justify-content: flex-end;
  & > * {
    margin-left: 5px;
  }
  @media (max-width: 430px) {
    display: none;
  }
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
  height: 93%;
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
      margin: auto;
    }
  }
}

.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 50%;
  width: 40px;
  height: 40;
  display: flex;
  justify-content: center;
  background-color: white;
  padding: 8px;
  color: $text;
  font-weight: bold;
  font-size: 18px;
  // border-radius: 0 4px 4px 0;
  border-radius: 50%;
  text-decoration: none;
  user-select: none;
}
.next {
  right: 0;
}
.prev {
  left: 0;
}
.prev:hover,
.next:hover {
  color: $theme;
}

@media (prefers-color-scheme: dark) {
  .navigation-panel {
    background-color: $dark_mode_foreground;
    color: $light-text;
    .icon-close {
      color: $light-text;
    }
  }
  .prev,
  .next {
    color: $light-text;
    background-color: $dark_mode_background;
  }
}
</style>