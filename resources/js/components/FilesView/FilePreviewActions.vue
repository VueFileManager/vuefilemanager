<template>
  <div v-if="filteredFiles.length > 1">
    <chevron-left-icon
      class="prev"
      @click.prevent="prev"
      size="17"
    ></chevron-left-icon>

    <chevron-right-icon
      class="next"
      @click.prevent="next"
      size="17"
    ></chevron-right-icon>
  </div>
</template>

<script>
import { events } from '@/bus';
import { mapGetters } from "vuex";
import { ChevronLeftIcon, ChevronRightIcon } from 'vue-feather-icons';

export default {
  components: {
    ChevronLeftIcon,
    ChevronRightIcon,
  },
  computed: {
        ...mapGetters(["fileInfoDetail", "data"]),

     filteredFiles() {
       let filteredData = []
      this.data.filter((element) => {
        if (element.type == this.fileInfoDetail.type) {
          filteredData.push(element);
        }
      });
        return filteredData
    },
  },

  methods: {
    next: function() {
      events.$emit('filePreviewAction:next');
    },
    prev: function() {
      events.$emit('filePreviewAction:prev');
    },
  
  },
};
</script>

<style lang="scss" scoped>
@import '@assets/vue-file-manager/_variables';

.icon-wrapper {
  width: 40px;
  height: 40px;
}

.prev,
.next {
  cursor: pointer;
  position: absolute;
  top: 53.5%;
  display: flex;
  justify-content: center;
  background-color: white !important;
  color: $text;
  border-radius: 50%;
  text-decoration: none;
  user-select: none;
}
.next {
  right: 0;
  margin-right: 10px;
}
.prev {
  left: 0;
  margin-left: 10px;
}

@media (prefers-color-scheme: dark) {
  .prev,
  .next {
    color: $light-text;
    background-color: $dark_mode_background !important;
  }
}
</style>
