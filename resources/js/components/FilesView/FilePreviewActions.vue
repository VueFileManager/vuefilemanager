<template>
  <div>
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
import { ChevronLeftIcon, ChevronRightIcon } from 'vue-feather-icons';

export default {
  components: {
    ChevronLeftIcon,
    ChevronRightIcon,
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
  top: 52%;
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
}
.prev {
  left: 0;
}

@media (prefers-color-scheme: dark) {
  .prev,
  .next {
    color: $light-text;
    background-color: $dark_mode_background !important;
  }
}
</style>
