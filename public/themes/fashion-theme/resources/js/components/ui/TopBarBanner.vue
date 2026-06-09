<template>
  <div class="top-bar-banner">
    <a :href="properties.topbar_banner_link" class="top-bar-banner_link" v-if="!hideBanner">
      <img :src="properties.topbar_banner_image" class="w-100" />
      <button class="top-bar-banner_close_btn btn-circle size-35" @click.prevent="closeBanner">
        <base-icon-svg name="close" :width="10" :height="10" />
      </button>
    </a>
  </div>
</template>

<script setup>
defineOptions({
  name: 'TopBarBanner'
});
import { ref } from "vue";
import { useTheme } from "@/composables/useTheme";

const { closeTopBanner } = useTheme();
const hideBanner = ref(false);

function closeBanner() {
  closeTopBanner();
  hideBanner.value = true;
}
const props = defineProps({
  properties: {
    type: Object,
    default: null,
  },
});
</script>

<style scoped>
.top-bar-banner {
  top: 0;
  width: 100%;
}

.top-bar-banner img {
  min-height: 36px;
  width: 100%;
}

.top-bar-banner_link {
  display: inline-block;
  position: relative;
  width: 100%;
}

.top-bar-banner_close_btn {
  position: absolute;
  border: none;
  top: 50%;
  right: 20px;
  transform: translateY(-50%);
}
</style>
