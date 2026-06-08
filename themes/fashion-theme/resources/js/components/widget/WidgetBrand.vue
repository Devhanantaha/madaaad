<template>
  <!-- Widget -->
  <div class="widget widget-style-1 widget_brand mb-4" v-if="!brandLoading">
    <h5 class="bg-light">
      <span>{{ $t("Brand") }}</span>
      <span @click="showBrandWidget = !showBrandWidget" class="widget-collapse-toggle"><span class="material-icons">
          {{ showBrandWidget ? "expand_less" : "expand_more" }}
        </span></span>
    </h5>
    <ul class="list-unstyled mb-0" v-if="showBrandWidget">
      <template v-for="(brand, index) in brands">
        <li :key="index" v-if="index <= max_brand">
          <input type="radio" name="brand_group" class="brand-filter" :id="brand.name + index" :value="brand.name"
            :checked="brand == selectedBrand" @change="filter(brand)" />
          <label :for="brand.name + index">{{ brand.name }} </label>
        </li>
      </template>

      <li v-if="brands.length > max_brand">
        <button class="btn_underline" @click.prevent="ViewMore('brand')">
          {{ $t("View More") }}
        </button>
      </li>
      <li v-if="max_brand > 6">
        <button class="btn_underline" @click.prevent="ViewLess('brand')">
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
  emits: ["filter", "loading", "brands"],
  props: {
    selectedBrand: {
      type: Object,
      required: false,
    },
    selectedBrandId: {
      type: Number,
      required: false,
    },
  },
  data() {
    return {
      showBrandWidget: true,
      max_brand: 3,
      brands: [],
      brandLoading: true,
    };
  },
  mounted() {
    this.getBrands();
  },
  methods: {
    /**
     * Get Top brands
     */
    getBrands() {
      axios
        .get("/api/v1/ecommerce-core/brands")
        .then((response) => {
          if (response.status === 200) {
            this.brands = response.data.data;
            this.$emit("brands", this.brands);
            const selectedItem = this.brands.find(
              (item) => item.id == this.selectedBrandId
            );

            if (selectedItem) {
              this.$emit("filter", selectedItem);
            }
            this.brandLoading = false;
            this.$emit("loading");
          }
        })
        .catch((error) => {
          this.brandLoading = false;
          this.$emit("loading");
        });
    },

    ViewMore(item) {
      if (item === "brand") {
        this.max_brand = this.brands.length;
      }
    },
    ViewLess(item) {
      if (item === "brand") {
        this.max_brand = 3;
      }
    },
    filter(brand) {
      this.$emit("filter", brand);
    },
  },
};
</script>
