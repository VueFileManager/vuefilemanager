<template  >
  <div v-if="showMenu && $checkPermission('master')" class="menu-wrapper">
    <div class="item-list">
      <li @click="renameItem">
        <p><edit-2-icon class="icon" size="19" /> Rename</p>
      </li>
      <li @click="moveItem">
        <corner-down-right-icon class="icon" size="19" />
        <p>Move</p>
      </li>
      <li @click="deleteItem">
        <trash-2-icon class="icon" size="19" />
        <p>Delete</p>
      </li>
    </div>
  </div>
</template>

<script>
import { Trash2Icon, Edit2Icon, CornerDownRightIcon } from "vue-feather-icons";
import ToolbarButton from "@/components/FilesView/ToolbarButton";
import { events } from "@/bus";

export default {
  components: {
    ToolbarButton,
    Trash2Icon,
    Edit2Icon,
    CornerDownRightIcon,
  },
  props: ["fileInfoDetail"],
  data() {
    return {
      showMenu: false,
    };
  },
  created() {
    events.$on("desktopMediaMenu:show", () => {
      this.showMenu = !this.showMenu;
    });
  },

  methods: {
    closeMenu() {
      this.showMenu = false;
    },
    deleteItem() {
      this.$store.dispatch("deleteItem", this.fileInfoDetail);
    },
    moveItem() {
      // Open move item popup
      events.$emit("popup:open", { name: "move", item: this.fileInfoDetail });
    },
    renameItem() {
      let itemName = prompt(
        this.$t("popup_rename.title"),
        this.fileInfoDetail.name
      );

      if (itemName && itemName !== "") {
        let item = {
          unique_id: this.fileInfoDetail.unique_id,
          type: this.fileInfoDetail.type,
          name: itemName,
        };

        this.$store.dispatch("renameItem", item);

        // Change item name if is mobile device or prompted
        if (this.$isMobile()) {
          events.$emit("change:name", item);
        }
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.menu-wrapper {
  width: 250px;
  position: absolute;
  top: 7%;
  z-index: 2;
  @include widget-card();
  padding: 0px;
}
.item-list {
  list-style: none;
  color: $text;

  li {
    width: 100%;
    height: 49px;
    margin: auto;
    display: flex;
    align-items: center;
    padding: 15px 20px;
    .icon {
      margin-right: 20px;
    }

    p {
      @include font-size(16);
    }
    &:hover {
      background-color: $light_background;
      color: $theme !important;
      p,
      .icon {
        color: $theme;
        stroke: $theme !important;
      }
    }
  }
}
.item-list {
  &:first-child() {
    border-top-left-radius: 8px !important;
    border-top-right-radius: 8px !important;
  }

  &:last-child {
    border-bottom-left-radius: 8px;
    border-bottom-right-radius: 8px;
  }
}
@media (prefers-color-scheme: dark) {
  .menu-wrapper {
    background-color: $dark_mode_foreground;
  }
  .item-list {
    color: $dark_mode_text_primary;
    li:hover {
      background-color: rgba($theme, 0.1);
    }
  }
}
</style>