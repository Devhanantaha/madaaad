<template>
  <!-- Mobile Header -->
  <header :class="this.headerStyle.custom_header == 1
    ? 'custom-mobile-header mobile_header__two d-lg-none border-bottom'
    : 'mobile_header__two d-lg-none border-bottom'
    " @scroll="scrollHandler" ref="mobileHeader">
    <div class="custom-container2">
      <div class="row align-items-center justify-content-between">

        <div class="col-6" v-if="!isSticky">
          <template v-if="mode == 'dark'">
            <the-logo :logo="siteProperties.mobile_dark_logo" :title="siteProperties.site_title"
              :header-logo-style="headerLogoStyle" v-if="siteProperties.mobile_dark_logo" />
            <h4 class="site-title" v-else>
              {{ siteProperties.site_title }}
            </h4>
          </template>
          <template v-else>
            <the-logo :logo="siteProperties.mobile_logo" :title="siteProperties.site_title"
              :header-logo-style="headerLogoStyle" v-if="siteProperties.mobile_logo" />
            <h4 class="site-title" v-else>
              <router-link to="/">
                {{ siteProperties.site_title }}
              </router-link>
            </h4>
          </template>
        </div>
        <div class="col-6" v-if="isSticky">
          <template v-if="mode == 'dark'">
            <the-logo :logo="siteProperties.sticky_black_mobile_logo" :title="siteProperties.site_title"
              :header-logo-style="headerLogoStyle" v-if="siteProperties.sticky_black_mobile_logo" />
            <h4 class="site-title text-white" v-else>
              {{ siteProperties.site_title }}
            </h4>
          </template>
          <template v-else>
            <the-logo :logo="siteProperties.sticky_mobile_logo" :title="siteProperties.site_title"
              :header-logo-style="headerLogoStyle" v-if="siteProperties.sticky_mobile_logo" />
            <h4 class="site-title" v-else>
              {{ siteProperties.site_title }}
            </h4>
          </template>
        </div>

        <div class="col-6 d-flex align-items-center justify-content-end position-static gap-4">
          <!-- Offcanvas -->
          <the-offcanvas :user-info="customerInfo" :menu-items="menuItems" :header-menu-style="headerMenuStyle"
            :megaCategories="megaCategories" :siteProperties="siteProperties" :mode="mode"
            :header-style="headerStyle" />
          <!-- End The Offcanvas -->
          <!-- Search Form -->
          <search-form style-two mobile-style @search-suggestions="getSearchProducts" />
          <!--Search Suggestions-->
          <div class="search-suggestion box-shadow bg-white" v-if="suggestionsOpen">
            <div v-if="
              tag_suggestions.length ||
              category_suggestions ||
              products_suggestions
            ">
              <!--Tags suggestions List-->
              <div v-if="tag_suggestions.length">
                <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">
                  {{ $t("Popular Suggestions") }}
                </div>
                <ul class="list-unstyled mb-0">
                  <li class="d-block text-left suggestion_list" v-for="(tag, index) in tag_suggestions" :key="index">
                    <router-link :to="`/product/search?tag=${tag.permalink}`">{{
                      tag.name
                    }}</router-link>
                  </li>
                </ul>
              </div>
              <!--End Tags suggestion List--->

              <!--Categories suggestions List-->
              <div v-if="category_suggestions">
                <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">
                  {{ $t("Category Suggestions") }}
                </div>
                <ul class="list-unstyled mb-0">
                  <li class="d-block text-left suggestion_list" v-for="(category, index) in category_suggestions"
                    :key="index">
                    <router-link :to="`/products/category/${category.slug}`">{{
                      category.name
                    }}</router-link>
                  </li>
                </ul>
              </div>
              <!--End Categories suggestion List--->
              <div v-if="products_suggestions">
                <div ref="searchSuggestion"
                  class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">
                  {{ $t("Products Suggestions") }}
                </div>
                <!--Product List-->
                <ul class="list-unstyled mb-0">
                  <li class="d-block text-left suggestion_list" v-for="(product, index) in products_suggestions"
                    :key="index">
                    <single-product :item="product" small />
                  </li>
                </ul>
                <!--End Product List--->
              </div>
            </div>

            <div class="p-3 text-center mt-1" v-else>
              {{ $t("Sorry, nothing found for") }}
              <strong>{{ search_key }}</strong>
            </div>
          </div>
          <!--End Search Suggestions-->

          <!-- End Search Form -->
          <!-- Cart Button -->
          <router-link to="/cart" class="btn-circle custom-icon-btn">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 20 20" class="svg replaced-svg"
              style="">
              <path id="Cart"
                d="M1783.345,37.526h-3.636l-4.545-6.364c-.343-.493-.326-.909-.909-.909h0a2.618,2.618,0,0,0-1.818.909l-4.546,6.364h-2.727a.9.9,0,0,0-.909.909h0a12.409,12.409,0,0,0,2.727,8.182,9.091,9.091,0,0,0,14.545,0,12.412,12.412,0,0,0,2.727-8.182h0A.9.9,0,0,0,1783.345,37.526Zm-9.091-5.455c.021-.029-.009.006,0,0s-.021-.029,0,0l3.636,5.455h-8.182Zm0,16.364c-4.317,0-7.922-4.229-8.182-9.091h16.364C1782.176,44.206,1778.572,48.435,1774.254,48.435Z"
                transform="translate(-1764.254 -30.253)"></path>
            </svg>
            <span class="count position-absolute d-flex align-items-center justify-content-center">{{ cartItem }}</span>
          </router-link>
          <!-- End Cart Button -->
        </div>
      </div>
    </div>
  </header>
  <!-- End Mobile Header -->
</template>
<script>
import offcanvas from "@/fakeDB/offcanvas.json";
import SearchForm from "@/components/ui/SearchForm.vue";
import TheOffcanvas from "@/components/menu/TheOffcanvas.vue";
import SingleProduct from "@/components/product/SingleProduct.vue";
import { mapState } from "vuex";
import axios from "axios";
export default {
  name: "MobileHeader",
  components: {
    SearchForm,
    TheOffcanvas,
    SingleProduct
  },
  props: {
    siteProperties: {
      type: Object,
      required: false,
    },
    megaCategories: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    menuItems: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    mode: {
      type: String,
      required: false,
    },
    cartItem: {
      type: Number,
      required: false,
      default: 0,
    },
    compareItem: {
      type: Number,
      required: false,
      default: 2,
    },
    headerStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    headerMenuStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    headerLogoStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      offcanvas,
      wishlistItem: 0,
      compareItem: 0,
      isSticky: false,
      suggestionsOpen: false,
      products_suggestions: [],
      category_suggestions: [],
      tag_suggestions: [],
    };
  },
  computed: mapState({
    customerInfo: (state) => state.customerInfo,
  }),
  mounted() {
    window.addEventListener("scroll", this.scrollHandler);
    document.addEventListener("click", this.closeDropdown);
  },
  methods: {
    scrollHandler() {
      const mobileHeader = this.$refs.mobileHeader;
      if (window.pageYOffset > 100) {
        this.isSticky = true;
        mobileHeader.classList.add("sticky", "fadeInDowns");
      } else {
        this.isSticky = false;
        mobileHeader.classList.remove("sticky", "fadeInDowns");
      }
    },
    closeDropdown(e) {
      let target = e.target;
      let searchSuggestionDropdown = this.$refs.searchSuggestion;
      if (searchSuggestionDropdown !== target && !searchSuggestionDropdown?.contains(target)) {
        this.suggestionsOpen = false;
      }
    },
    /**
    * Get suggestions products
    *
    */
    getSearchProducts(search_key) {
      this.search_key = search_key;
      this.loading = true;
      if (search_key) {
        axios
          .post("/api/v1/ecommerce-core/search-suggestions", {
            search_key: search_key,
          })
          .then((response) => {
            this.loading = false;
            if (response.data.success) {
              this.suggestionsOpen = true;
              this.category_suggestions = response.data.categories.data;
              this.products_suggestions = response.data.products.data;
              this.tag_suggestions = response.data.tags;
            }
          })
          .catch((error) => { });
      } else {
        this.suggestionsOpen = false;
      }
    },
  },
};
</script>
<style scoped lang="scss">
.search-suggestion {
  z-index: 99;
  position: absolute;
  top: 113px;
  width: 100%;
  left: 50%;
  max-height: 400px;
  min-height: 100px;
  overflow-y: scroll;
  overflow-x: hidden;
  transform: translateX(-50%);
  border-radius: 0px;
  scrollbar-width: none !important;
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 10%), 0 4px 6px -2px rgb(0 0 0 / 5%) !important;
}

.search-suggestion::-webkit-scrollbar {
  display: none;
}

.color-gray {
  color: #0a0e33b8;
}

.font-weight-custom {
  font-weight: 600;
}

.suggestion_list {
  padding: 5px 5px;
}

.suggestion_list:not(:last-child) {
  border-bottom: 1px solid rgba(51, 51, 51, 0.1);
}

.suggestion_list:hover {
  background-color: #dcdcdc36;
}

.bg-soft-secondary {
  background-color: rgb(238 240 242) !important;
}

.fs-10 {
  font-size: 0.625rem !important;
}

.text-muted {
  color: #6c757d !important;
}
</style>
