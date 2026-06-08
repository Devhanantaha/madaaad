<template>
  <router-link class="category-card bg-white rounded" :to="`/products/brand/${brand.permalink}`">
    <v-lazy-image :src="`${brand.logo}`" :alt="brand.name" />
    <span>{{ brand.name }}</span>
  </router-link>
</template>

<script>
import VLazyImage from "v-lazy-image";
export default {
  name: "BrandCard",
  components: {
    "v-lazy-image": VLazyImage,
  },
  props: {
    brand: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.category-card {
  padding: 20px;
  background-color: #f7f8fa;
  display: flex;
  align-items: center;
  width: 100%;
  line-height: 1.4;
  border: 1px solid #f7f8fa;

  img {
    border: 1px solid rgba(#707070, 0.3);
    border-radius: 50%;
    margin-right: 15px;
    min-width: 60px;
    height: 60px;
    object-fit: cover;
  }

  span {
    font-size: 16px;
    font-weight: 500;
  }

  @media (max-width: 480px) {
    padding: 10px;

    img {
      margin-right: 10px;
      min-width: 40px;
      height: 40px;
    }

    span {
      font-size: 14px;
    }
  }
}

.category-card:hover {
  border-color: 1px solid var(--mainC);
  animation: pulse 1s infinite;
}
</style>
