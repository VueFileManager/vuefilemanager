<template>
  <div
    class="file-full-preview-wrapper"
    v-if="showFullPreview"
    id="fileFullPreview"
  >
    <MediaFullPreview v-if="isMedia" />
  </div>
</template>

<script>
import { events } from "@/bus";
import { mapGetters } from "vuex";

import MediaFullPreview from "@/components/FilesView/MediaFullPreview";

export default {
  components: { MediaFullPreview },
  data() {
    return {
      showFullPreview: false,
    };
  },
  computed: {
    ...mapGetters(["fileInfoDetail"]),
    isMedia() {
      return this.fileInfoDetail === "image" || "video";
    },
  },
  mounted() {
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
  background-color: $light-background;
}

@media (prefers-color-scheme: dark) {
  .file-full-preview-wrapper {
    background-color: $dark_mode_background;
  }
}
</style>