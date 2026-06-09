<template>
  <!-- Categories -->
  <section class="category-section home-page-section">
    <div class="category-wrapper style--two">
      <div class="category-list-wrap">
        <ul class="category-list" v-for="(cat, index) in properties.category_list" :key="index">
          <li>
            <router-link :to="`/products/category/${cat.slug}`" class="gap-2">
              <img :src="`${cat.icon}`" :alt="cat.name" class="cat-icon" />
              <span>
                {{ cat.name }}
              </span>
            </router-link>
          </li>
        </ul>
      </div>
    </div>
  </section>
  <!-- End Categories -->
</template>

<script>
export default {
  name: "CategoryList",
  props: {
    properties: {
      type: Object,
      default: {},
    },
  },
};
</script>

<style lang="scss" scoped>
.category-wrapper {
  position: relative;
}

.category-wrapper.style--two .category-trigger {
  font-weight: 600;
  min-width: 160px;
  padding: 10px 10px;
  line-height: 1px;
  -webkit-box-shadow: 15px 20px 30px rgba(0, 0, 0, 0.03);
  box-shadow: 15px 20px 30px rgba(0, 0, 0, 0.03);
  background-color: #ffffff;
  border-radius: 50px;
  height: 50px;
}

.category-wrapper.style--two .category-trigger svg {
  margin-right: 10px;
}

section.category-section.home-page-section {
  background-color: #fff;
}

.category-list {
  padding: 0;
  margin: 0;
  list-style: none;
}

.category-list li {
  padding: 17px 0;
  font-weight: 500;
  border-bottom: 1px solid #efefef7a;
}

.category-list li a {
  margin-left: 16px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.cat-icon {
  width: 25px;
  height: 25px;
  border-radius: 0px;
  margin-right: 8px;
}
</style>
