<template>
  <!-- <div class="navigation-wrapper"> -->
  <div class="navigation-panel" v-if="fileInfoDetail"    
 >
    <div class="name-wrapper">
      <x-icon @click="closeFullPreview" size="17" class="icon-close"></x-icon>
      <div class="name-count-wrapper">
        <p class="title">{{ formatedName }}</p>

        <span class="file-count">
          ({{
            showImageIndex +
              ' ' +
              $t('pronouns.of') +
              ' ' +
              filteredFiles.length
          }})
        </span>
      </div>
      <span
        id="fast-preview-menu"
        class="fast-menu-icon"
        @click="menuOpen"
        v-if="$checkPermission(['master', 'editor'])"
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
           v-if="!this.$isMobile()"
          source="download"
          @click.native="downloadItem"
          :action="$t('actions.download')"
        />
        <ToolbarButton
        v-if="!this.$isMobile()"
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
import { events } from '@/bus';
import { mapGetters } from 'vuex';
import { XIcon, MoreHorizontalIcon } from 'vue-feather-icons';

import ToolbarButton from '@/components/FilesView/ToolbarButton';

export default {
  components: { ToolbarButton, XIcon, MoreHorizontalIcon },
  data() {
    return {
      showContextMenu: false,
    };
  },
  computed: {
    ...mapGetters(['fileInfoDetail', 'data']),
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
      let activeIndex = '';
      this.filteredFiles.filter((element, index) => {
        if (element.unique_id == this.fileInfoDetail.unique_id) {
          activeIndex = index + 1;
        }
      });
      return activeIndex;
    },

    formatedName() {
      let name = this.fileInfoDetail.name;
      if (name.lastIndexOf('.') > -1) {
        return _.truncate(name.substring(0, name.lastIndexOf('.')), {
          length: 27,
        });
      } else {
        return _.truncate(name, {
          length: 27,
        });
      }
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

  methods: {
    printMethod() {
      var tab = document.getElementById('image');
      var win = window.open('', '', 'height=700,width=700');
      win.document.write(tab.outerHTML);
      win.document.close();
      win.print();
    },
    downloadItem() {
      // Download file
      this.$downloadFile(
        this.fileInfoDetail.file_url,
        this.fileInfoDetail.name + '.' + this.fileInfoDetail.mimetype
      );
    },
    shareItem() {
      if (this.fileInfoDetail.shared) {
        events.$emit('popup:open', {
          name: 'share-edit',
          item: this.fileInfoDetail,
        });
      } else {
        events.$emit('popup:open', {
          name: 'share-create',
          item: this.fileInfoDetail,
        });
      }
    },
    menuOpen() {
      if (this.$isMobile()) {
        events.$emit('mobileMenu:show', 'showFromMediaPreview');
      } else {
        events.$emit('showContextMenuPreview:show', this.fileInfoDetail);
      }
    },
    closeFullPreview() {
      events.$emit('fileFullPreview:hide');
      events.$emit('showContextMenuPreview:hide');
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';
@import '@assets/vue-file-manager/_mixins';

.name-wrapper {
  width: 33%;
  height: 22px;
  display: flex;
  position: relative;
  // align-items: center;
  flex-grow: 1;
  align-self: center;
  white-space: nowrap;
  .name-count-wrapper {
    .file-count {
      @include font-size(15);
      margin-left: 6px;
      line-height: 1;
      font-weight: 700;
      overflow: hidden;
      text-overflow: ellipsis;
      display: inline-block;
      vertical-align: middle;
      align-self: center;
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
      align-self: center;
      color: $text;
    }
  }
  .icon-close {
          min-width: 17px;

    vertical-align: middle;
    cursor: pointer;
    margin-right: 6px;
    color: $text;
    align-self: center;
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
      background: $light_background;

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

@media (min-width: 420px) and (max-width: 930px) {
  .name-wrapper {
    width: 67%;
  }
}
@media (max-width: 570px) {
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
  @media (max-width: 930px) {
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
      background: $light_background;
    }
  }
  @media (max-width: 570px) {
    display: none;
  }
}
.navigation-panel {
height: 63px;
  width: 100%;
  padding: 10px 15px;
  display: flex;
  position: absolute;
  z-index: 8;
  align-items: center;
  background-color: white;
  color: $text;
}
@media (prefers-color-scheme: dark) {
  .navigation-panel {
    background-color: $dark_mode_foreground;
    color: $dark_mode_text_primary;
    .icon-close {
      color: $dark_mode_text_primary;
    }
    .fast-menu-icon:hover {
      background: $dark_mode_background;
    }
  }
  .name-wrapper {
    .title,
    .file-count {
      color: $dark_mode_text_primary !important;
    }
  }
  .navigation-icons {
    .button:hover {
      background: $dark_mode_background;
    }
  }
}
</style>
