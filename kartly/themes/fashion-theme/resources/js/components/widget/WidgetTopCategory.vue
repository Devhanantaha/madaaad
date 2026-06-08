<template>
  <!-- Widget -->
  <div class="widget widget-style-1 widget_top_category mb-4" v-if="!categoryLoading">
    <h5 class="bg-light">
      <span>{{ $t("Top Categories") }}</span>
      <span @click.prevent="showCategoryWidget = !showCategoryWidget" class="widget-collapse-toggle"><span
          class="material-icons">
          {{ showCategoryWidget ? "expand_less" : "expand_more" }}
        </span></span>
    </h5>
    <ul class="list-unstyled mb-0" v-if="showCategoryWidget">
      <template v-for="(cat, index) in categories">
        <li :key="index" v-if="index <= max_cat">
          <div v-if="link">
            <router-link :to="`/products/category/${cat.slug}`">{{ cat.name }}</router-link>
          </div>
          <div v-else>
            <input type="radio" name="category_group" :id="cat.name" :value="cat.name" :checked="cat == selectedCat"
              @change="filter(cat)" />
            <label :for="cat.name">{{ cat.name }}</label>
          </div>
        </li>
      </template>

      <li v-if="categories.length > max_cat">
        <button class="btn_underline" @click.prevent="ViewMore('cat')">
          {{ $t("View More") }}
        </button>
      </li>
      <li v-if="max_cat > 6">
        <button class="btn_underline" @click.prevent="ViewLess('cat')">
          {{ $t("View Less") }}
        </button>
      </li>
    </ul>
  </div>
  <!-- Widget -->
</template>

<script>
import axios from "axios";
export default {
  emits: ["filter", "loading"],
  props: {
    selectedCat: {
      type: Object,
      required: false,
    },
    selectedCatId: {
      type: Number,
      required: false,
    },
    link: {
      type: Boolean,
      default: false,
      required: false,
    },
  },
  data() {
    return {
      categories: [],
      categoryLoading: true,
      showCategoryWidget: true,
      max_cat: 6,
    };
  },
  mounted() {
    this.getCategories();
  },
  methods: {
    /**
     * Get top categories
     *
     */
    getCategories() {
      axios
        .get("/api/v1/ecommerce-core/parent-categories")
        .then((response) => {
          if (response.status === 200) {
            this.categories = response.data.data;
            const selectedItem = this.categories.find(
              (item) => item.id == this.selectedCatId
            );
            if (selectedItem) {
              this.$emit("filter", selectedItem);
            }
            this.categoryLoading = false;
            this.$emit("loading");
          }
        })
        .catch((error) => {
          this.categoryLoading = false;
          this.$emit("loading");
        });
    },

    ViewMore(item) {
      if (item === "cat") {
        this.max_cat = this.categories.length;
      }
    },
    ViewLess(item) {
      if (item === "cat") {
        this.max_cat = 6;
      }
    },
    filter(category) {
      this.$emit("filter", category);
    },
  },
};
</script>
