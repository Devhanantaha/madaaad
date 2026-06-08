<template>
  <div class="productDetails">
    <page-header :items="bItems" />

    <!-- Product details Hash Menu -->
    <div class="product-details-hash-menu d-lg-none" ref="hashMenu">
      <div class="custom-container2">
        <ul>
          <li @click.prevent="goSec('overview')">{{ $t("Overview") }}</li>
          <li @click.prevent="goSec('quickConnect')">
            {{ $t("Quick Connect") }}
          </li>
          <li @click.prevent="goSec('productDetails')">
            {{ $t("Product Details") }}
          </li>
          <li @click.prevent="goSec('recommendations')">
            {{ $t("Recommendations") }}
          </li>
        </ul>
      </div>
    </div>
    <!-- End Product details Hash Menu -->

    <div class="pt-4 pb-4 light-bg" ref="overview">
      <div class="custom-container2">
        <!--Product Details-->
        <div class="product-details" v-if="!productLoading">
          <div class="row">
            <template v-if="product != null">
              <div class="col-lg-4">
                <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                  :product-name="product.name" :url="product.url" :summary="product.summary"
                  :networks="product.shareOptions" :key="galleryKey" />
              </div>
              <div class="col-lg-5">
                <details-content :product="product" @goto-section="goSec" @color-variant-images="colorVariantImages" />
              </div>
              <div class="col-lg-3">
                <details-right :product="product" />
              </div>
            </template>

            <div class="row" v-else>
              <the-not-found title="Sorry! Product not found"></the-not-found>
            </div>
          </div>
        </div>
        <!--End Product Details-->
        <!--Preloader-->
        <div class="product-details1" v-if="productLoading">
          <div class="row">
            <div class="col-lg-4">
              <skeleton class="w-100 mb-10" height="400px"></skeleton>
              <div class="d-flex">
                <skeleton class="w-25 mb-10 mr-10" height="60px"></skeleton>
                <skeleton class="w-25 mb-10 mr-10" height="60px"></skeleton>
                <skeleton class="w-25 mb-10 mr-10" height="60px"></skeleton>
                <skeleton class="w-25 mb-10" height="60px"></skeleton>
              </div>
            </div>
            <div class="col-lg-5">
              <skeleton class="w-100 mb-1" height="20px"></skeleton>
              <skeleton class="w-50 mb-20" height="20px"></skeleton>
              <skeleton class="w-75 mb-20" height="15px"></skeleton>
              <skeleton class="w-100 mb-1" height="20px"></skeleton>
              <skeleton class="w-75 mb-20" height="15px"></skeleton>
              <skeleton class="w-100 mb-1" height="20px"></skeleton>
              <skeleton class="w-75 mb-30" height="20px"></skeleton>
              <div class="d-flex">
                <skeleton class="w-25 mb-10 mt-20 mr-10" height="10px"></skeleton>
                <skeleton class="w-25 mb-30 mr-10" height="45px"></skeleton>
              </div>
              <!--Action Buttons-->
              <div class="d-flex">
                <skeleton class="w-25 mb-10 mr-10" height="50px"></skeleton>
                <skeleton class="w-25 mb-10 mr-10" height="50px"></skeleton>
                <skeleton class="w-25 mb-10" height="50px"></skeleton>
              </div>
              <!--End Action Button-->
            </div>
            <div class="col-lg-3">
              <skeleton class="w-100 border-bottom border-bottom-light" height="90px"></skeleton>
              <skeleton class="w-100 border-bottom border-bottom-light" height="90px"></skeleton>
              <skeleton class="w-100 border-bottom border-bottom-light" height="90px"></skeleton>
              <skeleton class="w-100 border-bottom border-bottom-light" height="90px"></skeleton>
              <skeleton class="w-100 border-bottom border-bottom-light" height="90px"></skeleton>
            </div>
          </div>
        </div>
        <!--End Preloader-->

        <!--Tab Content-->
        <div class="row mt-30" v-if="!productLoading && product != null">
          <!--Product details tabs-->
          <div :class="{ 'col-lg-9': product.shopInfo, 'col-lg-12': !product.shopInfo }">
            <div class="overview-wrap" ref="productDetails">
              <CNav variant="tabs" role="tablist" class="product-description-tabs bg-light">
                <CNavItem>
                  <CNavLink href="javascript:void(0);" :active="tabPaneActiveKey === 1" @click="
                    () => {
                      tabPaneActiveKey = 1;
                    }
                  ">
                    {{ $t("Product Overview") }}
                  </CNavLink>
                </CNavItem>
                <CNavItem v-if="
                  site_config.enable_product_reviews == enums.status.ACTIVE
                ">
                  <CNavLink href="javascript:void(0);" :active="tabPaneActiveKey === 2" @click="
                    () => {
                      tabPaneActiveKey = 2;
                    }
                  ">
                    {{ $t("Buyer Review") }}
                  </CNavLink>
                </CNavItem>
              </CNav>
              <CTabContent>
                <CTabPane role="tabpanel" aria-labelledby="home-tab" click="product-overview"
                  :visible="tabPaneActiveKey === 1">
                  <div class="product-details-tab" v-if="product.description">
                    <div class="product-description ellipsis-para " :class="{ 'expanded': expandedDetails }"
                      v-html="product.description">
                    </div>
                    <div class="buttons mt-3">
                      <button class="bg-transparent border-0 btn-link" v-if="!expandedDetails"
                        @click="expandedDetails = true">
                        {{ $t("Read More") }}
                      </button>
                      <button class="bg-transparent border-0 btn-link" v-if="expandedDetails"
                        @click="expandedDetails = false">
                        {{ $t("Read Less") }}
                      </button>
                    </div>

                  </div>
                  <div class="no-description-wrapper text-center py-5" v-else>
                    <h2>{{ $t("No Description Found") }}</h2>
                    <p>
                      {{ $t("There are no description of this product") }}
                    </p>
                  </div>

                  <div v-if="product.pdf_specifications">
                    <a :href="product.pdf_specifications" target="_blank">
                      {{ $t("Download Pdf Specifications") }}
                    </a>
                  </div>
                </CTabPane>
                <CTabPane role="tabpanel" click="buyer-review " aria-labelledby="profile-tab"
                  :visible="tabPaneActiveKey === 2" v-if="
                    site_config.enable_product_reviews == enums.status.ACTIVE
                  ">
                  <ProductReview :product-id="product.id" :config="site_config" :key="product.id"
                    :review-summary="product.review_summary">
                  </ProductReview>
                </CTabPane>
              </CTabContent>
            </div>
          </div>
          <!--End product details tabs-->
          <!--Product widgets-->
          <div class="col-lg-3 d-none d-lg-block" v-if="product.shopInfo">
            <div class="widget_wrap">
              <single-shop :shop="product.shopInfo" widgetTitle v-if="product.shopInfo != null"></single-shop>
            </div>
          </div>
          <!--End Product widgets-->
        </div>
        <!--End Tab Content-->
      </div>
    </div>

    <!-- Related Product -->
    <section class="pb-50" ref="recommendations" v-if="!productLoading && product != null && relatedProducts.length">
      <div class="custom-container2">
        <div class="row align-items-center mb-20">
          <div class="col-md-6">
            <section-title :title="sectionTitle" />
          </div>
        </div>

        <swiper v-if="relatedProducts.length" :slidesPerView="1" :modules="modules" :spaceBetween="24" :autoplay="{
          delay: 2500,
          disableOnInteraction: false,
          pauseOnMouseEnter: true,
        }" :loop="true" :pagination="pagination" class="product-grid-slider theme-slider-dots" :breakpoints="{
          '0': {
            slidesPerView: 1,
          },
          '480': {
            slidesPerView: 2,
          },
          '768': {
            slidesPerView: 3,
          },
          '1024': {
            slidesPerView: 6,
          },
        }">
          <swiper-slide v-for="(item, index) in relatedProducts" :key="`slide-${index}`">
            <single-product :item="item" />
          </swiper-slide>
        </swiper>
      </div>
    </section>
    <!-- End Related Product -->
  </div>
</template>

<script>
import { defineAsyncComponent } from "vue";
import { mapState } from "vuex";
const axios = require("axios").default;
import enums from "../../../enums/enums";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Autoplay, Pagination } from "swiper/modules";

const PageHeader = defineAsyncComponent(() =>
  import("@/components/pageheader/PageHeader.vue")
);
const DetailsContent = defineAsyncComponent(() =>
  import("@/components/product/DetailsContent.vue")
);

const ProductReview = defineAsyncComponent(() =>
  import("@/components/product/ProductReview.vue")
);

const SingleProduct = defineAsyncComponent(() =>
  import("@/components/product/SingleProduct.vue")
);


const DetailsGallery = defineAsyncComponent(() =>
  import("@/components/product/DetailsGallery.vue")
);

const SingleShop = defineAsyncComponent(() =>
  import("@/components//shop/SingleShop.vue")
);
const DetailsRight = defineAsyncComponent(() =>
  import("@/components/product/DetailsRight.vue")
);

import {
  CTabContent,
  CTabPane,
  CNav,
  CNavItem,
  CNavLink,
} from "@coreui/vue";
export default {
  name: "ProductDetails",
  components: {
    DetailsGallery,
    PageHeader,
    DetailsContent,
    ProductReview,
    SingleProduct,
    Swiper,
    SwiperSlide,
    Pagination,
    CTabContent,
    CTabPane,
    CNav,
    CNavItem,
    CNavLink,
    SingleShop,
    DetailsRight
  },
  data() {
    return {
      enums: enums,
      product: null,
      tabPaneActiveKey: 1,
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Products"),
          href: "/products",
        },
      ],
      relatedProducts: [],
      galleryKey: 1,
      sectionTitle: this.$t("Related Products"),
      productLoading: true,
      expandedDetails: false,
    };
  },
  computed: mapState({
    site_config: (state) => state.siteSettings,
  }),
  setup() {
    return {
      modules: [Autoplay, Pagination],
    };
  },
  mounted() {
    window.addEventListener("scroll", this.scrollHandler);
    this.getProductDetails()
  },
  watch: {
    $route: {
      deep: true,
      handler(to, from) {
        this.getProductDetails();
        this.tabPaneActiveKey = 1;
        window.scrollTo(0, 0);
      },
    },
  },
  methods: {
    /**
     * Get single product details
     */
    getProductDetails() {
      this.$store.dispatch("showPreloader", true);
      axios
        .post("/api/v1/ecommerce-core/product-details", {
          permalink: this.$route.params.id,
          preview:
            typeof this.$route.query.preview != "undefined"
              ? this.$route.query.preview
              : null,
        })
        .then((response) => {
          this.$store.dispatch("showPreloader", false);
          if (response.data.success) {
            this.getRelatedProducts(response.data.data.id);
            document.title = response.data.data.name;
            this.product = response.data.data;
            this.productLoading = false;
          } else {
            this.productLoading = false;
          }
        })
        .catch((error) => {
          this.productLoading = false;
          this.$store.dispatch("showPreloader", false);
        });
    },

    /**
     * Related Products
     */
    getRelatedProducts(id) {
      axios
        .post("/api/v1/ecommerce-core/related-products", {
          id: id,
        })
        .then((response) => {
          this.relatedProducts = response.data.data;
        })
        .catch((error) => {
          this.relatedProducts = [];
        });
    },

    /**
     * Color variant gallery images
     */
    colorVariantImages(color_id) {
      this.$store.dispatch("showPreloader", true);
      this.galleryKey = this.galleryKey + 1;
      axios
        .post("/api/v1/ecommerce-core/color-variant-images", {
          product_id: this.product.id,
          color_id: color_id,
        })
        .then((response) => {
          if (response.data.success) {
            this.product.galleryImages = response.data.images;
            this.$store.dispatch("showPreloader", false);
          } else {
            this.$store.dispatch("showPreloader", false);
          }
        })
        .catch((error) => {
          this.$store.dispatch("showPreloader", false);
          this.product.galleryImages = [];
        });
    },
    goSec(sec) {
      this.$nextTick(() => {
        let element = this.$refs[sec];
        let top = element.offsetTop;
        window.scrollTo(0, top - 110);
      });
    },

    scrollHandler() {
      const hashMenu = this.$refs.hashMenu;
      if (hashMenu) {
        window.pageYOffset > 450
          ? hashMenu.classList.add("active")
          : hashMenu.classList.remove("active");
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../../assets/sass/00-abstracts/01-variables";

.product-details {
  background-color: #fff;
  box-shadow: 3px 3px 30px rgb(0 0 0 / 3%);
  border: 1px solid #f7f8fa;
}

.overview-wrap {
  background-color: #fff;
  box-shadow: 3px 3px 30px rgb(0 0 0 / 3%);
  border: 1px solid #f7f8fa;

  ul {
    li {
      &:not(:last-child) {
        margin-bottom: 5px;
      }
    }
  }

  p {
    &:not(:last-child) {
      margin-bottom: 20px;
    }
  }

  .tab-content {
    padding: 20px;

    @media only screen and (max-width: 575px) {
      padding: 0px 15px;
    }
  }
}

.product-description-tabs {
  border-bottom: none !important;

  @media only screen and (max-width: 575px) {
    flex-direction: column;
  }

  li {
    margin-bottom: 0 !important;

    a {
      color: #666666;
      font-size: 16px;
      font-weight: 700;
      padding: 11px 20px;
      line-height: 1.2;
      font-family: $title-font;
      border: none;
      position: relative;
      border-radius: 0px;

      @media only screen and (max-width: 575px) {
        text-align: center;
      }

      &:after {
        font-family: "Material Icons";
        content: "\e5cf";
        position: relative;
        top: 3px;
        left: 5px;
        font-size: 16px;
      }

      &.active {
        color: #fff;
        background-color: $c1;

        &:after {
          content: "\e5ce";
        }
      }
    }
  }
}


.specification-table {
  h4 {
    font-size: 21px;
    font-weight: 500;
    margin-bottom: 16px;
  }

  .table {
    th {
      background-color: #f7f8fa;
    }

    th,
    td {
      border-color: #f7f8fa;
    }

    tr {
      &:hover {
        background-color: rgba($color: #fafafa, $alpha: 0.5);
      }
    }
  }
}

.product-details-hash-menu {
  position: fixed;
  width: 100%;
  top: 75px;
  visibility: hidden;
  opacity: 0;
  transition: all 0.3s ease;
  left: 0;
  box-shadow: 7px 7px 60px rgb(0 0 0 / 7%);
  padding: 10px 0;
  background-color: #fff;

  ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    align-items: center;
    white-space: nowrap;
    overflow: auto;

    li {
      user-select: none;
      cursor: pointer;

      &:not(:last-child) {
        margin-right: 20px;
      }

      &.active {
        color: $c1;
      }
    }
  }

  &.active {
    top: 65px;
    visibility: visible;
    opacity: 1;
    z-index: 9;
  }
}

.fa {
  width: 27px;
  height: auto;
}

.ellipsis-para {
  max-height: 136px;
  overflow: hidden;
  position: relative;
}

.ellipsis-para:not(.expanded):after {
  content: "";
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 80px;
  background-image: linear-gradient(to bottom,
      rgba(255, 255, 255, 0),
      #fff);
}

.ellipsis-para.expanded {
  max-height: 100%;
}

.no-description-wrapper {
  min-height: 179px;
}
</style>
