<template>
  <div
    :class="[
      styleTwo ? 'section-title--two' : 'section-title',
      { 'line-color-c5': lineColorC5 },
    ]"
  >
    <h3 :style="`color:${titleColor}`">
      <span v-html="$t(title)"></span>
      <span :style="`color:${spTextColor}`">{{ specialText }}</span>
    </h3>

    <p v-if="description">{{ description }}</p>
  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      default: "",
    },
    specialText: {
      type: String,
      default: "",
    },
    description: {
      type: String,
      default: "",
    },
    titleColor: {
      type: String,
      default: "",
    },
    spTextColor: {
      type: String,
      default: "",
    },
    styleTwo: {
      type: Boolean,
      default: false,
    },
    lineColorC5: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
