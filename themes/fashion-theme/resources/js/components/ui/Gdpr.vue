<template>
  <div v-if="!hideConsent" :class="[
    properties.gdpr_position_class == 'right'
      ? 'gdpr-content right'
      : 'gdpr-content left',
  ]" :style="styleObject">
    <div class="content" v-html="properties.gdpr_message"></div>
    <button class="btn" @click.prevent="acceptCookies()">
      {{ properties.gdpr_btn_label }}
    </button>
  </div>
</template>
<script setup>
defineOptions({
  name: 'GDPR',
});
import { ref, computed } from "vue";
import { useTheme } from "@/composables/useTheme";

const { agreeGdpr } = useTheme();
const hideConsent = ref(false);

function acceptCookies() {
  agreeGdpr();
  hideConsent.value = true;
}
const props = defineProps({
  properties: {
    type: Object,
    default: null,
  },
});

const styleObject = computed(() => ({
  "--background-color": props.properties.gdpr_bg_color ?? "#222",
}));
</script>
<style scoped>
.gdpr-content {
  position: fixed;
  bottom: 20px;
  color: white;
  background-color: var(--background-color);
  padding: 20px;
  z-index: 9999;
  border-radius: 5px;
  width: 300px;
}

.gdpr-content .content {
  margin-bottom: 15px;
}

.left {
  left: 20px;
}

.right {
  right: 20px;
}
</style>