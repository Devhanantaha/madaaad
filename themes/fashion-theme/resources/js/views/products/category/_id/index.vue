<template>
  <div class="">
    <page-header :items="bItems" />

    <div class="pt-30 pt-lg-60 pb-60 light-bg">
      <div class="custom-container2">
        <div class="row">
          <div class="col-lg-3">
            <div class="widget_wrap" :class="{ active: wToggle }">
              <button class="close-btn btn-circle d-lg-none" @click.prevent="wToggle = !wToggle">
                <span class="material-icons"> close </span>
              </button>
              <div class="widget_wrap-inner">
                <WidgetBrand :selected-brand="brand_filter" :selected-brand-id="brand_filter_id"
                  @filter="addBrandFilter" @brands="
                    (el) => {
                      this.brands = el;
                    }
                  " @loading="
                    () => {
                      this.brandLoading = false;
                    }
                  " />
                <div v-if="brandLoading" class="widget widget-style-1 widget_top_category mb-4">
                  <skeleton height="300px"></skeleton>
                </div>
                <WidgetRating :selected-item="rating_filter" @filter="addRatingFilter" />
                <WidgetPrice :selected-option="price_filter" :selected-range="selected_price_range"
                  @filter="addPriceFilter" />
              </div>
            </div>
          </div>

          <div class="col-lg-9">
            <div class="row">
              <div class="col-12">
                <div class="mb-40 shadow-card">
                  <div class="row">
                    <div class="col-lg-6 order-1 order-lg-0 col-xl-6">
                      <div class="section-header">
                        <h3 class="product_header" v-if="!categoryLoading">
                          {{ categoryDetails.name }}
                        </h3>
                        <h3 class="product_header" v-if="categoryLoading">
                          <skeleton border-radius="5px" width="200px" height="30px"></skeleton>
                        </h3>
                        <div v-if="categoryLoading">
                          <p class="mt-1">
                            <skeleton border-radius="5px" width="80px" height="10px"></skeleton>
                          </p>
                        </div>
                        <div v-if="!categoryLoading">
                          <p v-if="totalItems > 0">
                            {{ totalItems }} {{ $t("items found") }}
                          </p>
                          <p v-else>{{ $t("No item found") }}</p>
                        </div>
                      </div>
                    </div>
                    <sorting-option class="col-lg-6 order-0 order-lg-1 text-lg-right" :data-loading="categoryLoading"
                      :selected-item="sorting_by" @sorting-items="sortingItems"
                      @filter-toggle="wToggle = !wToggle"></sorting-option>
                  </div>

                  <!-- Brand Collapse Box -->
                  <brand-collapse-box :brands="brands" :brand-loading="brandLoading" @select-brand="addBrandFilter"
                    v-if="!brandLoading && brands.length > 0"></brand-collapse-box>
                  <!-- End Brand Collapse Box -->
                  <!--Filter items-->
                  <div class="filter-tag-wrap" v-if="
                    brand_filter != null ||
                    price_filter != null ||
                    rating_filter != null
                  ">
                    <div class="filter-tags">
                      <h6 class="filtered-by mb-0">{{ $t("Filtered By") }}:</h6>

                      <div class="ant-tag" v-if="brand_filter != null">
                        <span class="ant-tag-text">{{
                          brand_filter.name
                        }}</span>
                        <span class="material-icons" @click.prevent="removeTag('brand')">close</span>
                      </div>

                      <div class="ant-tag" v-if="rating_filter != null">
                        <span class="ant-tag-text">{{ rating_filter }} Star</span>
                        <span class="material-icons" @click.prevent="removeTag('rating')">close</span>
                      </div>
                      <div class="ant-tag" v-if="price_filter != null">
                        <span class="ant-tag-text">
                          <the-currency :amount="price_filter.min"></the-currency>-
                          <the-currency :amount="price_filter.max"></the-currency>
                        </span>
                        <span class="material-icons" @click.prevent="removeTag('price')">close</span>
                      </div>

                      <span class="clear-all" @click.prevent="removeAllTag">{{
                        $t("CLEAR ALL")
                      }}</span>
                    </div>
                  </div>
                  <!--End filter items-->
                </div>
              </div>
            </div>
            <div class="row mobile-gap-10" v-if="productsLoading">
              <div class="col-lg-3 col-6" v-for="(item, index) in productSkeletons" :key="index">
                <skeleton :height="item.height" class="w-100 mb-10"> </skeleton>
              </div>
            </div>
            <div class="row mobile-gap-10" v-else>
              <div v-for="product in paginatedItems" :key="product.id" class="col-lg-3 col-6">
                <single-product :item="product" styleEight />
              </div>
            </div>

            <div class="row align-items-center mt-10" v-if="!productsLoading">
              <div class="col-md-6">
                <!-- Showing Per Page -->
                <ShowingPerPage class="text-center text-md-start" :items-per-page="perPage" :total-items="totalItems"
                  :current-page="currentPage" />
                <!-- Showing Per Page -->
              </div>
              <div class="col-md-6 d-flex justify-content-center justify-content-md-end mt-3 mt-md-0">
                <!-- Pagination -->
                <pagination :options="paginationOptions" v-model="currentPage" :records="totalItems" :per-page="perPage"
                  @paginate="pagination" />
                <!-- End Pagination -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import SingleProduct from "@/components/product/SingleProduct.vue";
import ShowingPerPage from "@/components/ui/ShowingPerPage.vue";
import WidgetBrand from "@/components/widget/WidgetBrand.vue";
import WidgetRating from "@/components/widget/WidgetRating.vue";
import WidgetPrice from "@/components/widget/WidgetPrice.vue";
import Pagination from "v-pagination-3";
import BrandCollapseBox from "@/components/product/BrandCollapseBox.vue";
import SortingOption from "@/components/product/SortingOption.vue";
const axios = require("axios").default;
export default {
  name: "CategoryProducts",
  components: {
    PageHeader,
    SingleProduct,
    ShowingPerPage,
    WidgetBrand,
    WidgetRating,
    WidgetPrice,
    Pagination,
    BrandCollapseBox,
    SortingOption,
  },
  data() {
    return {
      productsLoading: true,
      brandLoading: true,
      categoryLoading: true,
      wToggle: false,
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Products"),
          active: true,
        },
      ],
      currentPage: this.$route.query.page ?? 1,
      perPage:
        this.$store.state.siteSettings != null
          ? this.$store.state.siteSettings.product_per_page
          : 18,
      paginatedItems: [],
      totalItems: 0,
      sorting_by: "newest",
      brands: [],
      brand_filter: null,
      brand_filter_id: this.$route.query.brand_filter ?? "",
      rating_filter: this.$route.query.rating_filter ?? null,
      price_filter: null,
      selected_price_range: this.$route.query.price_filter,

      categoryDetails: {},
      paginationOptions: {
        chunk: 3,
        theme: "bootstrap4",
        hideCount: true,
      },

      productSkeletons: [
        {
          height: "370px",
        },
        {
          height: "370px",
        },
        {
          height: "370px",
        },
        {
          height: "370px",
        },
      ],
    };
  },
  mounted() {
    this.getCategoryDetails();
  },
  watch: {
    $route: {
      deep: true,
      handler(to, from) {
        this.getCategoryDetails();
      },
    },
  },
  methods: {
    /**
     * Get category details
     */
    getCategoryDetails() {
      axios
        .post("/api/v1/ecommerce-core/category-details", {
          permalink: this.$route.params.id,
        })
        .then((response) => {
          if (response.data.success) {
            this.categoryDetails = response.data.data;
            document.title = response.data.data.name;
            this.getProducts();
          }
          this.categoryLoading = false;
        })
        .catch((error) => {
          this.categoryLoading = false;
        });
    },

    //Filter by brand
    addBrandFilter(el) {
      this.brand_filter = el;
      if (this.$route.query.brand_filter != this.brand_filter.id) {
        this.generateFilterUrl();
      }
    },

    //Filter by price
    addPriceFilter(el) {
      this.price_filter = el;
      if (this.$route.query.price_filter != this.price_filter.value) {
        this.generateFilterUrl();
      }
    },

    //Filter by rating
    addRatingFilter(el) {
      this.rating_filter = el;
      if (this.$route.query.rating_filter != this.rating_filter) {
        this.generateFilterUrl();
      }
    },

    //Generate filter base page url
    generateFilterUrl() {
      const currentQuery = this.$route.query;
      const newQuery = {};

      //Brand Filter
      if (this.brand_filter != null) {
        newQuery.brand_filter = this.brand_filter.id;
      }

      //Brand Filter
      if (this.rating_filter != null) {
        newQuery.rating_filter = this.rating_filter;
      }

      //Price Filter
      if (this.price_filter != null) {
        let value = this.price_filter.min + "-" + this.price_filter.max;
        newQuery.price_filter = value;
      }

      if (currentQuery.page) {
        delete currentQuery.page;
      }

      const mergedQuery = { ...currentQuery, ...newQuery };

      this.$router.push({ query: mergedQuery }).then(() => {
        this.currentPage = 1;
        this.getProducts();
      });
    },

    //Remove specific filter item
    removeTag(item) {
      if (item === "brand") {
        this.brand_filter = null;
        this.removeQueryFromUrl("brand_filter");
      } else if (item === "price") {
        this.price_filter = null;
        this.removeQueryFromUrl("price_filter");
      } else if (item === "rating") {
        this.rating_filter = null;
        this.removeQueryFromUrl("rating_filter");
      }
    },

    //Remove all filter items
    removeAllTag() {
      this.brand_filter = null;
      this.price_filter = null;
      this.rating_filter = null;
      this.$router.push(``).then(() => {
        this.currentPage = 1;
        this.getProducts();
      });
    },

    //Remove query param from url
    removeQueryFromUrl(myQueryParam) {
      const query = { ...this.$route.query };
      delete query[myQueryParam];

      this.$router.replace({ query }).then(() => {
        this.currentPage = 1;
        this.getProducts();
      });
    },

    //Pagination
    pagination() {
      const currentQueryParams = this.$route.query;
      const paginationQueryParam = "page";

      const updatedQueryParams = {
        ...currentQueryParams,
        [paginationQueryParam]: this.currentPage,
      };

      this.$router.push({ query: updatedQueryParams }).then(() => {
        this.getProducts();
      });
    },

    /**
     * Get products
     */
    getProducts() {
      window.scrollTo(0, 0);
      this.productsLoading = true;
      axios
        .post("/api/v1/ecommerce-core/products", {
          category_id: this.categoryDetails.id,
          perPage: this.perPage,
          page: this.currentPage,
          brand_id: this.$route.query.brand_filter ?? "",
          rating: this.$route.query.rating_filter ?? "",
          price_range: this.$route.query.price_filter ?? "",
          sorting: this.sorting_by,
        })
        .then((response) => {
          if (response.status === 200) {
            this.paginatedItems = response.data.data;
            this.totalItems = response.data.meta.total;
          }
          this.productsLoading = false;
        })
        .catch((error) => {
          this.productsLoading = false;
        });
    },

    //Sorting items
    sortingItems(item) {
      this.sorting_by = item;
      this.getProducts();
    },
  }
};
</script>
