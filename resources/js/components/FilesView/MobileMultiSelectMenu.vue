<template>
   <div  class='mobile-selected-menu-wrapper' >
    <transition name="context-menu">
      <div
       v-if="mobileMultiSelect"
        ref="contextmenu"
        class="options"
      >
  
        <div class="menu-wrapper">

          <div class="mobile-selected-menu">
              <ToolbarButton
               v-if="
              !$isThisLocation(['trash', 'trash-root']) &&
              $checkPermission('master') || $checkPermission('editor')
            "
              source="move"
              :action="$t('actions.move')"
              :class="{'is-inactive' : fileInfoDetail.length < 1}"
              @click.native="moveItem"/>

            <ToolbarButton
            v-if="$checkPermission('master') || $checkPermission('editor')"
              source="trash"
              :class="{'is-inactive' : fileInfoDetail.length < 1}"
              :action="$t('actions.delete')"
              @click.native="deleteItem"/>

                <ToolbarButton
              source="download"
              :class="{'is-inactive' : fileInfoDetail.length < 1}"
              :action="$t('actions.delete')"
              @click.native="downloadItem"/>
              
              <ToolbarButton
              source="close"
              :action="$t('actions.close')"
              class="close-icon"
              @click.native="closeSelecting"/>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import ToolbarButton from "@/components/FilesView/ToolbarButton";
import { events } from "@/bus";
import { mapGetters } from "vuex";

export default {
    name:"MobileMultiSelectMenu",
    components: {ToolbarButton},
    computed: {
        ...mapGetters(['fileInfoDetail'])
    },
    data () {
        return {
            mobileMultiSelect:false
        }
    },
    methods: {
        closeSelecting() {
            events.$emit('mobileSelecting-stop')
        },
        downloadItem() {
            this.fileInfoDetail.forEach(item => {
                this.$downloadFile(
                item.file_url,
                item.name + '.' + item.mimetype
            )
            })   
        },
        moveItem() {
        // Open move item popup 
            events.$emit('popup:open', { name: 'move', item: [this.fileInfoDetail[0]] })
        },
        deleteItem() {
        //Delete items
            this.$store.dispatch("deleteItem");
        },
    },
    created () {
        events.$on('mobileSelecting-start' , () => {
            this.mobileMultiSelect = true
            
    })

    events.$on('mobileSelecting-stop' , () => {
      this.mobileMultiSelect = false
      
    })
    }
    
}
</script>

<style scoped lang="scss">
@import "@assets/vue-file-manager/_variables";
@import "@assets/vue-file-manager/_mixins";

.mobile-selected-menu-wrapper {
  z-index: 1;
    .options {
      z-index: 1;
    }
}

.is-inactive {
    opacity: 0.25 !important;
    pointer-events: none !important;
  }

.mobile-selected-menu {
  display: flex;
  margin-left: 15px;
  margin-right: 15px;
  .close-icon {
    margin-left: auto !important;
  }
}

.menu-option {
  display: flex;
  align-items: center;
}

.options {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 99;
  overflow: hidden;
  background: white;
  border-top-left-radius: 12px;
  border-top-right-radius: 12px;
  &.is-active {
    opacity: 1 !important;
    pointer-events: initial !important;
  }

}

@media (prefers-color-scheme: dark) {

  .options {
    background: $dark_mode_background;
  }
}

// Transition
.context-menu-enter-active,
.fade-enter-active {
  transition: all 200ms;
}

.context-menu-leave-active,
.fade-leave-active {
  transition: all 200ms;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}

.context-menu-enter,
.context-menu-leave-to {
  opacity: 0;
  transform: translateY(100%);
}

.context-menu-leave-active {
  position: absolute;
}

</style>
