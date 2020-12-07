<template>
  <button class="button" :title="action">
    <corner-down-right-icon
      v-if="source === 'move'"
      size="19"
    ></corner-down-right-icon>
    <download-cloud-icon
      v-if="source === 'download'"
      size="19"
    ></download-cloud-icon>
    <folder-plus-icon
      v-if="source === 'folder-plus'"
      size="19"
    ></folder-plus-icon>
    <edit-2-icon v-if="source === 'rename'" size="19"></edit-2-icon>
    <printer-icon v-if="source === 'print'" size="19"></printer-icon>
    <trash-2-icon v-if="source === 'trash'" size="19"></trash-2-icon>
    <list-icon v-if="source === 'th-list'" size="19"></list-icon>
    <info-icon v-if="source === 'info'" size="19"></info-icon>
    <grid-icon v-if="source === 'th'" size="19"></grid-icon>
    <link-icon v-if="source === 'share'" size="19"></link-icon>
    <x-icon v-if="source === 'close'" size="19"></x-icon>

    <svg v-if="source === 'preview-sorting'" size="19"
      width="18px" height="18px" viewBox="0 0 18 18" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <g id="VueFileManager" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round">
          <g id="Storage-Alert-Copy" transform="translate(-1092.000000, -28.000000)" stroke="#000000" stroke-width="1.4">
              <g id="Toolbar" transform="translate(331.000000, 19.000000)">
                  <g id="Tools" transform="translate(581.000000, 9.000000)">
                      <g id="sort-icon" transform="translate(181.000000, 1.000000)">
                          <rect id="Rectangle" x="9.77777778" y="0" width="6.22222222" height="6.22222222"></rect>
                          <rect id="Rectangle" x="9.77777778" y="9.77777778" width="6.22222222" height="6.22222222"></rect>
                          <line x1="0" y1="2" x2="6" y2="2" id="Path"></line>
                          <line x1="0" y1="8" x2="6" y2="8" id="Path"></line>
                          <line x1="0" y1="14" x2="6" y2="14" id="Path"></line>
                      </g>
                  </g>
              </g>
          </g>
      </g>
  </svg>
  </button>
</template>

<script>
import {
  FolderPlusIcon,
  Trash2Icon,
  GridIcon,
  ListIcon,
  Edit2Icon,
  InfoIcon,
  CornerDownRightIcon,
  LinkIcon,
  DownloadCloudIcon,
  XIcon,
  PrinterIcon,
} from "vue-feather-icons";

export default {
  name: "ToolbarButton",
  props: ["source", "action"],
  components: {
    CornerDownRightIcon,
    DownloadCloudIcon,
    FolderPlusIcon,
    PrinterIcon,
    Trash2Icon,
    Edit2Icon,
    ListIcon,
    XIcon,
    GridIcon,
    InfoIcon,
    LinkIcon,
  },
};
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.button {
  height: 42px;
  width: 42px;
  border-radius: 8px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  text-align: center;
  cursor: pointer;
  white-space: nowrap;
  outline: none;
  border: none;
  @include transition(150ms);
  background: transparent;

  &:hover {
    background: $light_background;

    path,
    line,
    polyline,
    rect,
    circle {
      @include transition(150ms);
      stroke: $theme;
    }
  }
}

@media (prefers-color-scheme: dark) {
  .button {
    background: transparent;

    &:hover {
      background: $dark_mode_foreground;
    }

    path,
    line,
    polyline,
    rect,
    circle {
      stroke: $dark_mode_text_primary;
    }
  }
}
</style>
