<template>
  <!-- <div class="navigation-wrapper"> -->
  <div class="navigation-panel" v-if="fileInfoDetail">
    <div class="name-wrapper">
      <x-icon @click="closeFullPreview" size="17" class="icon-close"></x-icon>
      <div class="name-count-wrapper">
        <p class="title">{{ formatedName }}</p>

        <span class="file-count">
          ({{
            showImageIndex +
            " " +
            $t("pronouns.of") +
            " " +
            filteredFiles.length
          }})
        </span>
      </div>
      <span
        id="fast-preview-menu"
        class="fast-menu-icon"
        @click="menuOpen"
        v-if="$checkPermission('master', 'editor')"
      >
        <more-horizontal-icon class="more-icon" size="14">
        </more-horizontal-icon>
      </span>
    </div>

    <!-- </div> -->
    <div class="created_at-wrapper">
      <p>{{ fileInfoDetail.filesize }}, {{ fileInfoDetail.created_at }}</p>
    </div>
    <div class="navigation-icons">
      <div class="navigation-tool-wrapper">
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
  </div>
  <!-- </div> -->
</template>

<script>
import { events } from "@/bus";
import { mapGetters } from "vuex";
import { XIcon, MoreHorizontalIcon } from "vue-feather-icons";

import ToolbarButton from "@/components/FilesView/ToolbarButton";

export default {
  components: { ToolbarButton, XIcon, MoreHorizontalIcon },
  data() {
    return {
      showContextMenu: false,
    };
  },
  computed: {
    ...mapGetters(["fileInfoDetail", "data"]),
    filteredFiles() {
      let files = [];
      this.data.filter((element) => {
        if (element.type == this.fileInfoDetail.type) {
          files.push(element);
        }
      });

      return files;
    },
    showImageIndex() {
      let activeIndex = "";
      this.filteredFiles.filter((element, index) => {
        if (element.unique_id == this.fileInfoDetail.unique_id) {
          activeIndex = index + 1;
        }
      });
      return activeIndex;
    },

    formatedName() {
      let name = this.fileInfoDetail.name;
      if (name.lastIndexOf(".") > -1) {
        return _.truncate(name.substring(0, name.lastIndexOf(".")), {
          length: 20,
        });
      } else {
        return _.truncate(name, {
          length: 18,
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

  methods: {
    printMethod() {
      var tab = document.getElementById("image");
      var win = window.open("", "", "height=700,width=700");
      win.document.write(tab.outerHTML);
      win.document.close();
      win.print();
    },
    downloadItem() {
      // Download file
      this.$downloadFile(
        this.fileInfoDetail.file_url,
        this.fileInfoDetail.name + "." + this.fileInfoDetail.mimetype
      );
    },
    shareItem() {
      if (this.fileInfoDetail.shared) {
        events.$emit("popup:open", {
          name: "share-edit",
          item: this.fileInfoDetail,
        });
      } else {
        events.$emit("popup:open", {
          name: "share-create",
          item: this.fileInfoDetail,
        });
      }
    },
    menuOpen() {
      if (this.$isMobile()) {
        events.$emit("mobileMenu:show", "showFromMediaPreview");
      } else {
        events.$emit("showContextMenuPreview:show", this.fileInfoDetail);
      }
    },
    closeFullPreview() {
      events.$emit("fileFullPreview:hide");
      events.$emit("showContextMenuPreview:hide");
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.name-wrapper {
  width: 33%;
  height: 22px;
  display: flex;
  position: relative;
  align-items: center;
  .name-count-wrapper {
    display: flex;
    .file-count {
      @include font-size(15);
      margin-left: 6px;
      line-height: 1;
      font-weight: 700;
      overflow: hidden;
      text-overflow: ellipsis;
      display: inline-block;
      vertical-align: middle;
      color: $text;
    }
    .title {
      @include font-size(15);
      line-height: 1;
      font-weight: 700;
      overflow: hidden;
      text-overflow: ellipsis;
      display: inline-block;
      vertical-align: middle;
      color: $text;
    }
  }
  .icon-close {
    vertical-align: middle;
    cursor: pointer;
    margin-right: 6px;
    color: $text;
    @include transition(150ms);
  }
  .fast-menu-icon {
    height: 24px;
    display: flex;
    align-items: center;
    vertical-align: middle;
    margin-left: 6px;
    padding: 1px 4px;
    line-height: 0;
    border-radius: 3px;
    cursor: pointer;
    @include transition(150ms);

    svg circle {
      @include transition(150ms);
    }
    &:hover {
      background: white;

      svg circle {
        stroke: $theme;
      }
    }
    .more-icon {
      vertical-align: middle;
      cursor: pointer;
    }
  }
}
.context-menu {
  min-width: 250px;
  position: absolute;
  z-index: 99;
  box-shadow: $shadow;
  background: white;
  border-radius: 8px;
  overflow: hidden;
  top: 29px;

  &.showed {
    display: block;
  }
}

@media (min-width: 420px) and (max-width: 890px) {
  .name-wrapper {
    width: 67%;
  }
}
@media (max-width: 523px) {
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
  @media (max-width: 890px) {
    display: none;
  }
}
.navigation-icons {
  width: 33%;
  text-align: right;

  .navigation-tool-wrapper {
    margin-left: 28px;
    display: inline-block;
    vertical-align: middle;
  }
  .button {
    margin-left: 5px;
    &:hover {
      background: white;
    }
  }
  @media (max-width: 523px) {
    display: none;
  }
}
.navigation-panel {
  height: 6.7%;
  min-height: 63px;
  padding: 10px 15px;
  display: flex;
  position: relative;
  background-color: $light-background;
  color: $text;
  > div {
    flex-grow: 1;
    align-self: center;
    white-space: nowrap;
  }
}
@media (prefers-color-scheme: dark) {
  .navigation-panel {
    background-color: $dark_mode_foreground;
    color: $light-text;
    .icon-close {
      color: $light-text;
    }
    .fast-menu-icon:hover {
      background: $dark_mode_background;
    }
  }
  .name-wrapper {
    .title,
    .file-count {
      color: $light-text !important;
    }
  }
  .navigation-icons {
    .button:hover {
      background: $dark_mode_background;
    }
  }
}
</style>