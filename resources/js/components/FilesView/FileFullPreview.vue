<template>
  <div
    class="file-full-preview-wrapper"
    v-if="showFullPreview"
    id="fileFullPreview"
    ref="filePreview"
    tabindex="-1"
     @click="closeContextMenu"
    @keydown.esc="(showFullPreview = false), hideMenu()"
    @keydown.right="next"
    @keydown.left="prev"
  >
    <FilePreviewNavigationPanel />
    <MediaFullPreview v-if="isMedia" />
    <FilePreviewActions  />
  </div>
</template>

<script>
import { events } from "@/bus";
import { mapGetters } from "vuex";

import MediaFullPreview from "@/components/FilesView/MediaFullPreview";
import FilePreviewActions from "@/components/FilesView/FilePreviewActions";
import FilePreviewNavigationPanel from "@/components/FilesView/FilePreviewNavigationPanel";

export default {
  components: {
    MediaFullPreview,
    FilePreviewNavigationPanel,
    FilePreviewActions,
  },
  data() {
    return {
      showFullPreview: false,
    };
  },
  methods: {
      closeContextMenu(event) {
        if( (event.target.parentElement.id ||  event.target.id) === "fast-preview-menu") {
          return
      } else {
          events.$emit('showContextMenuPreview:hide')
      }
    },
    next: function () {
      events.$emit("filePreviewAction:next");
    },
    prev: function () {
      events.$emit("filePreviewAction:prev");
    },
    hideMenu() {
      events.$emit("showContextMenuPreview:hide");
    },
  },

  updated() {
    if (this.showFullPreview) {
      this.$refs.filePreview.focus();
    }
  },
  computed: {
    ...mapGetters(["fileInfoDetail" , "data"]),
    isMedia() {
      return this.fileInfoDetail === "image" || "video" || "audio";
    },
    
  },

  mounted() {
    if (this.showFullPreview) {
      let preview = document.getElementById("file-wrapper-preview");
      preview.addEventListener("click", this.hideMenu);
    }
    events.$on("fileFullPreview:show", () => {
      this.showFullPreview = true;
    });
    events.$on("fileFullPreview:hide", () => {
      this.showFullPreview = false;
    });
  },
};
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_variables";

.file-full-preview-wrapper {
  width: 100%;
  height: 100%;
  position: absolute;
  z-index: 7;
  background-color: white;
}

@media (prefers-color-scheme: dark) {
  .file-full-preview-wrapper {
    background-color: $dark_mode_background;
  }
}
</style>
