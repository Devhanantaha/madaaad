<template>
  <div class="my-2 text-center">
    <p class="alert alert-warning rounded-0">{{ $t(title) }}</p>
  </div>
</template>
<script>
export default {
  name: "NotFound",
  props: {
    title: {
      type: String,
      default: "Item not found",
      required: false,
    },
    redirect: {
      type: Boolean,
      default: false,
      required: false,
    },
    redirectBtnLabel: {
      type: String,
      default: "Go to home",
      required: false,
    },
    redirectUrl: {
      type: String,
      required: false,
      default: "/",
    },
    image: {
      type: Boolean,
      required: false,
      default: "/",
    },
  },
};
</script>
