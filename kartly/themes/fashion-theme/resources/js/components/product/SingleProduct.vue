<template>
  <!--Product Page style-->
  <div v-if="styleEight" class="single-product-item d-inline-block style--eight">
    <div class="position-relative overflow-hidden">
      <!-- Thumb -->
      <router-link :to="`/products/${item.slug}`" class="d-block product-thumbnail">
        <v-lazy-image class="w-100" :src="item.thumbnail_image" :alt="item.name" />
      </router-link>
      <!-- End Thumb -->

      <!-- Action buttons -->
      <div
        class="product-action-buttons align-items-center bg-overlay d-flex fixed-bottom h-auto justify-content-center p-1 position-absolute w-100">
        <!-- Add to Cart -->
        <button v-if="item.quantity > 0" class="btn-circle bg-black" v-bind:title="$t('Add To Cart')" v-on="item.has_variant == 1 || item.attatchment_title != null
          ? { click: () => productQuickView() }
          : { click: () => addToCart() }
          ">
          <base-icon-svg name="cart" :height="17.5" :width="17.5" />
        </button>
        <!-- End Add to Cart -->

        <!-- Add to Wishlist -->
        <button class="btn-circle bg-black" v-bind:title="$t('Add To Wishlist')" @click.prevent="addToWishlist">
          <base-icon-svg name="wishlist" :height="16.5" :width="16.5" />
        </button>
        <!-- End Add to Wishlist -->

        <!-- Quick view -->
        <button class="btn-circle bg-black" v-bind:title="$t('Quick View')" @click.prevent="productQuickView()">
          <base-icon-svg name="quickview" :height="17.688" :width="18.8" />
        </button>
        <!-- End Quick view -->
      </div>
      <!-- End Action buttons -->
    </div>

    <!-- Summary -->
    <div class="d-flex flex-column justify-content-between product-summary">
      <!-- Rating -->
      <div class="star-rating" v-if="
        site_config.enable_product_reviews == 1 &&
        site_config.enable_product_star_rating == 1
      ">
        <div class="product-rating-wrapper">
          <i :data-star="item.avg_rating" :title="item.avg_rating" class="star-rating"></i>
        </div>
      </div>
      <!-- End Rating -->

      <!-- Title -->
      <h4 class="product-title">
        <router-link :to="`/products/${item.slug}`">
          {{ item.name }}
        </router-link>
      </h4>
      <!-- End Title -->

      <!-- Price -->
      <span class="product-price d-flex flex-wrap">
        <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
        <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
        <the-currency :amount="item.base_price" tag="del"
          v-if="!widgetSlider && item.base_price > item.price"></the-currency>
      </span>
      <!-- End Price -->
    </div>
    <!-- End Summary -->
    <!-- Quick View Modal -->
    <teleport to="body">
      <CModal scrollable :visible="visibleQuickView" size="lg" @close="
        () => {
          visibleQuickView = false;
        }
      ">
        <CModalHeader>
          <CModalTitle> {{ $t("Quick View") }}</CModalTitle>
          <button class="btn-circle bg-black size-35" @click="close()">
            <base-icon-svg name="close" :width="10" :height="10" />
          </button>
        </CModalHeader>

        <CModalBody class="p-0">
          <div class="row mx-0">
            <div class="col-lg-6 mb-30 mb-lg-0 p-0">
              <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                :product-name="product.name" :url="product.url" :summary="product.summary"
                :networks="product.shareOptions" />
            </div>

            <div class="col-lg-6 pr-20">
              <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
            </div>
          </div>
        </CModalBody>
      </CModal>
    </teleport>
    <!-- End Quick View Modal -->
  </div>
  <!--End Product Page style-->

  <!--Product search style-->
  <div class="single-product-item search-item d-flex flex-column m-0 rounded-0" v-else-if="small">
    <div class="position-relative overflow-hidden d-flex">
      <!-- Thumb -->
      <router-link :to="`/products/${item.slug}`" class="d-block pr-10">
        <v-lazy-image :src="item.thumbnail_image" :alt="item.name" class="small-image" />
      </router-link>
      <!-- End Thumb -->
      <!-- Summary -->
      <div class="p-0">
        <!-- Title -->
        <h6 class="product-title m-0">
          <router-link :to="`/products/${item.slug}`">
            {{ item.name }}
          </router-link>
        </h6>
        <!-- End Title -->

        <!-- Price -->
        <span class="product-price">
          <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
          <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
          <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
        </span>
        <!-- End Price -->
      </div>
      <!-- End Summary -->
    </div>
  </div>
  <!--End Product search style-->

  <!--Compare Product search style-->
  <div class="single-product-item style--eight d-flex flex-column m-0" v-else-if="compareSearch">
    <div class="position-relative overflow-hidden d-flex">
      <!-- Thumb -->
      <v-lazy-image :src="item.thumbnail_image" :alt="item.name" class="compare-product-image" />
      <!-- End Thumb -->
      <!-- Summary -->
      <div class="p-0 ml-10">
        <!-- Title -->
        <h6 class="product-title m-0">
          {{ item.name }}
        </h6>
        <!-- End Title -->

        <!-- Price -->
        <span class="product-price">
          <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
          <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
          <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
        </span>
        <!-- End Price -->
      </div>
      <!-- End Summary -->
    </div>
  </div>
  <!--End Compare Product search style-->

  <!-- Wishlist style-->
  <CTableRow v-else-if="wishlistStyle">
    <CTableDataCell>
      {{ index + 1 }}
    </CTableDataCell>
    <CTableDataCell>
      <div class="d-flex align-items-center gap-1">
        <div class="product-img">
          <router-link :to="`/products/${item.slug}`">
            <img :src="item.thumbnail_image" :alt="item.name" class="small-image rounded-circle" />
          </router-link>
        </div>
        <div>
          <router-link :to="`/products/${item.slug}`">
            <span class="product-name">{{ item.name }}</span>
          </router-link>
        </div>
      </div>
    </CTableDataCell>
    <CTableDataCell>
      <span class="product-price">
        <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
        <the-currency v-else :amount="item.base_price" tag="span"></the-currency>
        <the-currency v-if="item.base_price > item.price" :amount="item.base_price" tag="del"
          class="ml-10"></the-currency>
      </span>
    </CTableDataCell>
    <CTableDataCell>
      <button class="cart-icon bg-light btn btn-circle" v-bind:title="$t('Add To Cart')" v-if="item.quantity > 0" v-on="item.has_variant == 1 || item.attatchment_title != null
        ? { click: () => productQuickView() }
        : { click: () => addToCart() }
        ">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" class="svg replaced-svg"
          style="">
          <path id="Cart"
            d="M1783.345,37.526h-3.636l-4.545-6.364c-.343-.493-.326-.909-.909-.909h0a2.618,2.618,0,0,0-1.818.909l-4.546,6.364h-2.727a.9.9,0,0,0-.909.909h0a12.409,12.409,0,0,0,2.727,8.182,9.091,9.091,0,0,0,14.545,0,12.412,12.412,0,0,0,2.727-8.182h0A.9.9,0,0,0,1783.345,37.526Zm-9.091-5.455c.021-.029-.009.006,0,0s-.021-.029,0,0l3.636,5.455h-8.182Zm0,16.364c-4.317,0-7.922-4.229-8.182-9.091h16.364C1782.176,44.206,1778.572,48.435,1774.254,48.435Z"
            transform="translate(-1764.254 -30.253)" fill="#333"></path>
        </svg>
      </button>
    </CTableDataCell>
    <CTableDataCell class="text-center">
      <button class="remove-icon bg-light btn btn-circle text-danger" title="remove item"
        @click.prevent="removeItemFromWishlist(item.id)">
        <i class="fa fa-trash-o"></i>
      </button>
    </CTableDataCell>
    <!-- Quick View Modal -->
    <teleport to="body">
      <CModal scrollable :visible="visibleQuickView" size="lg" @close="
        () => {
          visibleQuickView = false;
        }
      ">
        <CModalHeader>
          <button class="btn-circle bg-black size-35" @click="close()">
            <base-icon-svg name="close" :width="10" :height="10" />
          </button>
        </CModalHeader>

        <CModalBody class="p-0">
          <div class="row mx-0">
            <div class="col-lg-6 mb-30 mb-lg-0 p-0">
              <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                :product-name="product.name" :url="product.url" :summary="product.summary"
                :networks="product.shareOptions" />
            </div>

            <div class="col-lg-6 pr-20">
              <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
            </div>
          </div>
        </CModalBody>
      </CModal>
    </teleport>
    <!-- End Quick View Modal -->
  </CTableRow>
  <!-- End Wishlist style-->

  <!--Compare page style-->
  <div class="compare-style-product" v-else-if="compareStyle">
    <button class="btn btn_bordered" v-bind:title="$t('Add To Cart')" :disabled="item.quantity < 1" v-on="item.has_variant == 1 || item.attatchment_title != null
      ? { click: () => productQuickView() }
      : { click: () => addToCart() }
      ">
      {{ $t("Add To Cart") }}
    </button>
    <!-- Quick View Modal -->
    <teleport to="body">
      <CModal scrollable :visible="visibleQuickView" size="lg" @close="
        () => {
          visibleQuickView = false;
        }
      ">
        <CModalHeader>
          <button class="btn-circle bg-black size-35" @click="close()">
            <base-icon-svg name="close" :width="10" :height="10" />
          </button>
        </CModalHeader>

        <CModalBody class="p-0">
          <div class="row mx-0">
            <div class="col-lg-6 mb-30 mb-lg-0 p-0">
              <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                :product-name="product.name" :url="product.url" :summary="product.summary"
                :networks="product.shareOptions" />
            </div>

            <div class="col-lg-6 mr-20">
              <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
            </div>
          </div>
        </CModalBody>
      </CModal>
    </teleport>
    <!-- End Quick View Modal -->
  </div>
  <!--End compare page style-->

  <!--List Style-->
  <div class="list-style-product" v-else-if="listStyle">
    <div class="single-product-item single-product product-horizontal align-items-center">
      <!-- Product Image -->
      <router-link :to="`/products/${item.slug}`" class="product-thumbnail">
        <v-lazy-image class="w-100" :src="item.thumbnail_image" :alt="item.name" />
      </router-link>
      <!-- End Product Image -->

      <!-- Product Summery -->
      <div class="d-flex flex-column product-summary">
        <h4 class="product-title mt-0">
          <router-link :to="`/products/${item.slug}`" class="hover-title">{{
            item.name
            }}</router-link>
        </h4>
        <!-- Rating -->
        <div class="star-rating mb-12" v-if="
          site_config.enable_product_reviews == 1 &&
          site_config.enable_product_star_rating == 1
        ">
          <div class="product-rating-wrapper">
            <i :data-star="item.avg_rating" :title="item.avg_rating" class="star-rating"></i>
          </div>
        </div>
        <!-- End Rating -->

        <!-- Price -->
        <span class="product-price d-flex flex-wrap">
          <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
          <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
          <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
        </span>
        <!-- End Price -->
      </div>
      <!-- End Product Summery -->
    </div>
  </div>
  <!---End List style-->
  <!--Featured Style-->
  <div class="single-product-item featured-style d-flex flex-column" v-else-if="featuredStyle">
    <div class="position-relative overflow-hidden">
      <!-- Thumb -->
      <router-link :to="`/products/${item.slug}`" class="d-block product-thumbnail">
        <v-lazy-image class="w-100" :src="featuredImage != null ? featuredImage : item.thumbnail_image"
          :alt="item.name" />
      </router-link>
      <!-- End Thumb -->

      <!-- Action buttons -->
      <div
        class="product-action-buttons align-items-center bg-overlay d-flex fixed-bottom h-auto justify-content-center p-1 position-absolute w-100">
        <!-- Add to Cart -->
        <button v-if="item.quantity > 0" class="btn-circle bg-black" v-bind:title="$t('Add To Cart')" v-on="item.has_variant == 1 || item.attatchment_title != null
          ? { click: () => productQuickView() }
          : { click: () => addToCart() }
          ">
          <base-icon-svg name="cart" :height="17.5" :width="17.5" />
        </button>
        <!-- End Add to Cart -->

        <!-- Add to Wishlist -->
        <button class="btn-circle bg-black" v-bind:title="$t('Add To Wishlist')" @click.prevent="addToWishlist">
          <base-icon-svg name="wishlist" :height="16.5" :width="16.5" />
        </button>
        <!-- End Add to Wishlist -->

        <!-- Quick view -->
        <button class="btn-circle bg-black" v-bind:title="$t('Quick View')" @click.prevent="productQuickView()">
          <base-icon-svg name="quickview" :height="17.688" :width="18.8" />
        </button>
        <!-- End Quick view -->
      </div>
      <!-- End Action buttons -->

      <!-- Discount -->
      <div v-if="item.discount > 0" class="badge-container">
        <span class="product-badge">
          <span v-if="item.discount.discountType == 1">
            -{{ item.discount }}%
          </span>
          <span v-else>
            -{{ item.discount.discount_amount }}{{ this.currency.symbol }}</span>
        </span>
      </div>
      <!-- End Discount -->
    </div>

    <!-- Summary -->
    <div class="product-summary">
      <!-- Rating -->
      <div class="star-rating mx-auto" v-if="
        site_config.enable_product_reviews == 1 &&
        site_config.enable_product_star_rating == 1
      ">
        <div class="product-rating-wrapper">
          <i :data-star="item.avg_rating" :title="item.avg_rating" class="star-rating"></i>
        </div>
      </div>
      <!-- End Rating -->

      <!-- Title -->
      <h4 class="product-title">
        <router-link :to="`/products/${item.slug}`">
          {{ item.name }}
        </router-link>
      </h4>
      <!-- End Title -->

      <!-- Price -->
      <span class="product-price">
        <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
        <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
        <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
      </span>
      <!-- End Price -->
    </div>
    <!-- End Summary -->
    <!-- Quick View Modal -->
    <teleport to="body">
      <CModal scrollable :visible="visibleQuickView" size="lg" @close="
        () => {
          visibleQuickView = false;
        }
      ">
        <CModalHeader>
          <button class="btn-circle bg-black size-35" @click="close()">
            <base-icon-svg name="close" :width="10" :height="10" />
          </button>
        </CModalHeader>

        <CModalBody class="p-0">
          <div class="row mx-0">
            <div class="col-lg-6 mb-30 mb-lg-0 p-0">
              <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                :product-name="product.name" :url="product.url" :summary="product.summary"
                :networks="product.shareOptions" />
            </div>

            <div class="col-lg-6 mr-20">
              <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
            </div>
          </div>
        </CModalBody>
      </CModal>
    </teleport>
    <!-- End Quick View Modal -->
  </div>
  <!--End Featured Style-->

  <!--Home page style-->
  <div v-else class="home-style single-product-item d-flex flex-column">
    <div class="position-relative overflow-hidden">
      <!-- Thumb -->
      <router-link :to="`/products/${item.slug}`" class="d-block product-thumbnail">
        <v-lazy-image class="w-100" :src="item.thumbnail_image" :alt="item.name" />
      </router-link>
      <!-- End Thumb -->

      <!-- Action buttons -->
      <div
        class="product-action-buttons align-items-center bg-overlay d-flex fixed-bottom h-auto justify-content-center p-1 position-absolute w-100">
        <!-- Add to Cart -->
        <button v-if="item.quantity > 0" class="btn-circle bg-black" v-bind:title="$t('Add To Cart')" v-on="item.has_variant == 1 || item.attatchment_title != null
          ? { click: () => productQuickView() }
          : { click: () => addToCart() }
          ">
          <base-icon-svg name="cart" :height="17.5" :width="17.5" />
        </button>
        <!-- End Add to Cart -->

        <!-- Add to Wishlist -->
        <button class="btn-circle bg-black" v-bind:title="$t('Add To Wishlist')" @click.prevent="addToWishlist">
          <base-icon-svg name="wishlist" :height="16.5" :width="16.5" />
        </button>
        <!-- End Add to Wishlist -->

        <!-- Quick view -->
        <button class="btn-circle bg-black" v-bind:title="$t('Quick View')" @click.prevent="productQuickView()">
          <base-icon-svg name="quickview" :height="17.688" :width="18.8" />
        </button>
        <!-- End Quick view -->
      </div>
      <!-- End Action buttons -->

      <!-- Discount -->
      <div v-if="item.discount > 0" class="badge-container">
        <span class="product-badge">
          <span v-if="item.discount.discountType == 1">
            -{{ item.discount }}%
          </span>
          <span v-else>
            -{{ item.discount.discount_amount }}{{ this.currency.symbol }}</span>
        </span>
      </div>
      <!-- End Discount -->
    </div>

    <!-- Summary -->
    <div class="product-summary">
      <!-- Rating -->
      <div class="star-rating mx-auto" v-if="
        site_config.enable_product_reviews == 1 &&
        site_config.enable_product_star_rating == 1
      ">
        <div class="product-rating-wrapper">
          <i :data-star="item.avg_rating" :title="item.avg_rating" class="star-rating"></i>
        </div>
      </div>
      <!-- End Rating -->

      <!-- Title -->
      <h4 class="product-title">
        <router-link :to="`/products/${item.slug}`">
          {{ item.name }}
        </router-link>
      </h4>
      <!-- End Title -->

      <!-- Price -->
      <span class="product-price">
        <the-currency :amount="item.price" tag="span" v-if="item.base_price > item.price"></the-currency>
        <the-currency :amount="item.base_price" tag="span" v-else></the-currency>
        <the-currency :amount="item.base_price" tag="del" v-if="item.base_price > item.price"></the-currency>
      </span>
      <!-- End Price -->
    </div>
    <!-- End Summary -->
    <!-- Quick View Modal -->
    <teleport to="body">
      <CModal scrollable :visible="visibleQuickView" size="lg" @close="
        () => {
          visibleQuickView = false;
        }
      ">
        <CModalHeader>
          <button class="btn-circle bg-black size-35" @click="close()">
            <base-icon-svg name="close" :width="10" :height="10" />
          </button>
        </CModalHeader>

        <CModalBody>
          <div class="row">
            <div class="col-lg-6 mb-30 mb-lg-0">
              <details-gallery :gallery-images="product.galleryImages" :voucher-list="product.voucher_list"
                :product-name="product.name" :url="product.url" :summary="product.summary"
                :networks="product.shareOptions" />
            </div>

            <div class="col-lg-6">
              <details-content :product="product" @color-variant-images="colorVariantImages" :key="product.id" />
            </div>
          </div>
        </CModalBody>
      </CModal>
    </teleport>
    <!-- End Quick View Modal -->
  </div>
  <!--End home page style-->
</template>

<script>
import DetailsGallery from "@/components/product/DetailsGallery.vue";
import DetailsContent from "@/components/product/DetailsContent.vue";
import VLazyImage from "v-lazy-image";
const axios = require("axios").default;
import { mapState } from "vuex";
import {
  CModal,
  CButton,
  CModalHeader,
  CModalTitle,
  CModalBody,
  CModalFooter,
  CTableDataCell,
  CTableHeaderCell,
  CTableHead,
  CTableRow,
} from "@coreui/vue";

export default {
  name: "SingleProduct",
  emits: ["remove-wishlist"],
  components: {
    "v-lazy-image": VLazyImage,
    DetailsGallery,
    DetailsContent,
    CModal,
    CButton,
    CModalHeader,
    CModalBody,
    CModalFooter,
    CModalTitle,
    CTableDataCell,
    CTableHeaderCell,
    CTableHead,
    CTableRow,
  },
  props: {
    item: {
      type: Object,
      required: true,
    },
    index: {
      type: Number,
      default: 0,
    },
    styleEight: {
      type: Boolean,
      default: false,
    },
    widgetSlider: {
      type: Boolean,
      default: false,
    },
    small: {
      type: Boolean,
      default: false,
    },
    compareSearch: {
      type: Boolean,
      default: false,
    },
    wishlistStyle: {
      type: Boolean,
      default: false,
    },
    compareStyle: {
      type: Boolean,
      default: false,
    },
    listStyle: {
      type: Boolean,
      default: false,
    },
    featuredStyle: {
      type: Boolean,
      default: false,
    },
    featuredImage: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      quantityValue:
        this.item.min_qty != null && this.item.min_qty > 0
          ? parseInt(this.item.min_qty)
          : 1,
      visibleQuickView: false,
      product: {},
      galleryKey: 0,
    };
  },
  computed: mapState({
    customerToken: (state) => state.customerToken,
    isCustomerLogin: (state) => state.isCustomerLogin,
    site_config: (state) => state.siteSettings,
    currency: (state) => state.currency,
    min_qty() {
      return this.item.min_qty != null && parseInt(this.item.min_qty) > 0
        ? parseInt(this.item.min_qty)
        : 1;
    },
    max_qty() {
      return this.item.max_qty != null &&
        parseInt(this.item.max_qty) > 0 &&
        parseInt(this.item.max_qty) < parseInt(this.item.quantity)
        ? parseInt(this.item.max_qty)
        : this.item.quantity;
    },
  }),
  methods: {
    /**
     * Product quick view
     */
    productQuickView() {
      this.$store.dispatch("showPreloader", true);
      axios
        .post("/api/v1/ecommerce-core/product-details", {
          permalink: this.item.slug,
        })
        .then((response) => {
          if (response.data.success) {
            this.product = response.data.data;
            this.visibleQuickView = true;
            this.$store.dispatch("showPreloader", false);
          } else {
            this.$store.dispatch("showPreloader", false);
            this.$toast.error(this.$t("Product Loading Failed"));
          }
        })
        .catch((error) => {
          this.$store.dispatch("showPreloader", false);
          this.$toast.error(this.$t("Product Loading Failed"));
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
        });
    },
    /**
     * Store items to cart
     */
    addToCart() {
      let item = {
        uid: Date.now(),
        id: this.item.id,
        name: this.item.name,
        permalink: this.item.slug,
        image: this.item.thumbnail_image,
        variant: null,
        variant_code: null,
        unitPrice: this.item.price,
        oldPrice: this.item.base_price,
        attachment: null,
        quantity: this.quantityValue,
        max_item: this.max_qty,
        min_item: this.min_qty,
        seller: this.item.seller,
        shop_name: this.item.shop != null ? this.item.shop.shop_name : null,
        shop_slug: this.item.shop != null ? this.item.shop.shop_slug : null,
      };
      let messages = {
        success: this.$t("Product added to cart successfully"),
        viewCart: this.$t("View Cart"),
        limit_error: this.$t("You can not add more than available stock"),
        error: this.$t("Product add to cart failed"),
      };
      this.$store.dispatch("addToCart", { item, messages });
    },
    /**
     * Add to wishlist
     */
    addToWishlist() {
      if (this.isCustomerLogin) {
        axios
          .post(
            "/api/v1/ecommerce-core/customer/store-product-to-wishlist",
            {
              product_id: this.item.id,
            },
            {
              headers: {
                Authorization: `Bearer ${this.customerToken}`,
              },
            }
          )
          .then((response) => {
            if (response.data.success) {
              this.$store.dispatch("refreshCustomerDashboardInfo");
              this.$toast.success(
                this.$t("Product added to wishlist successfully")
              );
            } else {
              this.$toast.error(this.$t("Product add to wishlist failed"));
            }
          })
          .catch((error) => {
            this.$toast.error(this.$t("Product add to wishlist failed"));
          });
      } else {
        this.$toast.error("Please login");
        this.$router.push("/login");
      }
    },
    /**
     *Remove product from wishlist
     */
    removeItemFromWishlist(product_id) {
      if (this.isCustomerLogin) {
        axios
          .post(
            "/api/v1/ecommerce-core/customer/product-remove-from-wishlist",
            {
              product_id: product_id,
            },
            {
              headers: {
                Authorization: `Bearer ${this.customerToken}`,
              },
            }
          )
          .then((response) => {
            if (response.data.success) {
              this.$emit("remove-wishlist");
              this.$store.dispatch("refreshCustomerDashboardInfo");
              this.$toast.success(
                this.$t("Product remove from wishlist successfully")
              );
            } else {
              this.$toast.error(this.$t("Product remove from wishlist failed"));
            }
          })
          .catch((error) => {
            this.$toast.error(this.$t("Product remove from wishlist failed"));
          });
      } else {
        this.$toast.error("Please login");
        this.$router.push("/login");
      }
    },
    close() {
      this.visibleQuickView = false;
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.fixed-top {
  z-index: 99 !important;
}

.single-product-item {
  // border: 1px solid #eaebed;
  border-radius: 5px;
  // margin-bottom: 30px;
  background-color: #fff;
  overflow: hidden;

  .badge-container {
    position: absolute;
    right: 0;
    top: 0;
    width: 90px;
    height: 90px;

    .product-badge {
      position: absolute;
      width: calc(100% * 2);
      left: -30%;
      transform: rotate(45deg);
      top: 5px;
      padding: 9px 10px;
      font-weight: 500;
      color: #fff;
      background-color: $c1;
      text-align: center;
      font-size: 13px;
    }
  }

  .product-action-buttons {
    transition: 0.2s ease-in;
    opacity: 0;
    visibility: hidden;

    >button {
      width: 36px;
      min-width: 36px;
      height: 36px;
      transform: scale(0.8);

      &:nth-child(2) {
        transition-delay: 0.05s;
      }

      &:nth-child(3) {
        transition-delay: 0.1s;
      }

      &:not(:last-child) {
        margin-right: 5px;
      }
    }
  }

  .product-price {
    font-size: 16px;
    font-weight: 700;
    color: $c1;

    del {
      font-weight: 700;
      font-size: 12px;
      margin-left: 12px;
      color: $text-color-light;
    }

    @media (min-width: 479px) {
      font-size: 15px;

      del {
        font-size: 14px;
      }
    }
  }

  .product-title {
    margin: 8px 0 8px;
    font-weight: 500;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 2;
    font-size: 15px;
    height: 40px;
    overflow: hidden;

    @media (max-width: 479px) {
      font-size: 15px;
    }

    a {
      color: #3b3b3b;
      background-repeat: no-repeat;
      background-image: -o-linear-gradient(transparent 96%, currentColor 1px);
      background-image: linear-gradient(transparent 96%, currentColor 1px);
      -webkit-transition: 0.5s cubic-bezier(0.85, 0.26, 0.17, 1);
      -o-transition: 0.5s cubic-bezier(0.85, 0.26, 0.17, 1);
      transition: 0.5s cubic-bezier(0.85, 0.26, 0.17, 1);
      background-size: 0 100%;

      &:hover {
        background-size: 100% 100%;
      }
    }
  }

  .product-summary {
    padding: 20px;
    min-height: 134px;

    @media (max-width: 479px) {
      padding: 14px;
      padding-top: 18px;
    }
  }

  &:hover {
    .product-action-buttons {
      opacity: 1;
      visibility: visible;

      >button {
        transform: scale(1);
      }

      @media (max-width: 479px) {
        display: none !important;
      }
    }
  }

  &.style--eight {
    box-shadow: 3px 3px 30px rgba(0, 0, 0, 0.03);

    .product-title {
      font-weight: 500;
      margin: 8px 0 8px;
    }

    .product-price {
      font-size: 15px;
      font-weight: 900;
      color: $c1;
    }

    .text-rating {
      .rating {
        font-size: 12px;
      }
    }

    &:hover {
      box-shadow: 5px 5px 60px rgba(0, 0, 0, 0.05);
    }
  }

  &--two {
    .product-thumb {
      width: 160px;
      height: 160px;

      img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }
    }

    &.small {
      .product-thumb {
        width: 100px;
        height: 100px;
      }

      p {
        font-size: 14px;
      }
    }

    .old-price {
      position: relative;
      color: $title-color;
      font-size: 16px;
      font-weight: $medium;
      margin-right: 13px;

      &::after {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        height: 1px;
        background-color: $c1;
        content: "";
      }
    }

    .new-price {
      font-size: 13px;
      font-weight: $medium;
      background-color: $c1;
      color: $white;
      padding: 4px 6px;
      border-radius: 50px;
    }
  }
}

.small-image {
  width: 65px;
}

.star-rating {
  font-size: 18px !important;
}

.compare-product-image {
  width: 60px !important;
  height: 60px !important;
  min-width: 60px;
}

.single-product .product-thumbnail {
  overflow: hidden;
  position: relative;
}

.single-product .product-thumbnail img {
  width: 100%;
  transform: scale(1);
  transition: all 0.4s ease;
}

// List Style Product
.single-product.product-horizontal {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  margin-bottom: 15px;
  overflow: hidden;
}

.single-product.product-horizontal .product-thumbnail {
  margin-bottom: 0;
  margin-right: 0px;
  max-width: 121px;
  max-height: 121px;
}

.single-product .product-thumbnail img:hover {
  transform: scale(1.1);
}

.single-product-item .product-thumbnail img {
  width: 100%;
  transform: scale(1);
  transition: all 0.4s ease;
}

.single-product-item:hover .product-thumbnail img {
  transform: scale(1.1);
}

.single-product.product-horizontal .product-summary {
  -webkit-box-flex: 1;
  -ms-flex: 1;
  flex: 1;
  padding: 7px;
  text-align: left;
  min-height: 110px !important;
}

.product-horizontal .product-thumbnail {
  max-height: 100% !important;
}

.single-product .product-summary .product-title {
  line-height: 1.33;
  margin-bottom: 12px;
}

.single-product .product-summary .product-price {
  font-size: 14px;
  line-height: 1;
  font-weight: 500;
}

.home-style.single-product-item {
  margin-bottom: 0px !important;
}

.custom-product-list .home-style.single-product-item {
  margin-bottom: 24px !important;
}
</style>
