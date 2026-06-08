<template>
  <div class="product-summary">
    <template v-if="!loading">
      <div class="row">
        <!--Shipping Not Available-->
        <div class="col-12 mb-2 mt-2 single-package border-bottom"
          v-for="(product, index1) in shippingNotAvailableProducts" :key="index1">
          <div class="row d-flex product-content justify-content-between mb-2">
            <div class="product-info d-flex gap-2 col-8">
              <div class="image">
                <router-link :to="`/products/${product.permalink}`" class="product-img">
                  <img :src="product.image" :alt="product.name" class="border img-70 rounded" />
                </router-link>
              </div>
              <div class="description">
                <router-link :to="`/products/${product.permalink}`">
                  <h5 class="product_name  font-weight-regular fz-14 mb-0 text-black text-capitalize">
                    {{ product.name }}
                  </h5>
                </router-link>
                <p class="product-variant mb-1 fz-sm-12" v-if="product.variant">
                  <product-variant :variant="product.variant"></product-variant>
                </p>
                <p class="mb-1 fz-sm-12">
                  {{ $t("Qty") }}: {{ product.quantity }}
                </p>
                <!--Attachment-->
                <div class="align-items-center d-flex flex-wrap gap-1 product-document"
                  v-if="product.attachment != null">
                  <p class="fz-sm-12 mb-0">
                    {{ product.attachment.title }}:
                    <a :href="`${product.attachment.path}`" target="_blank">
                      {{ product.attachment.file_name }}
                    </a>
                  </p>
                </div>
                <!--End Attachment-->

                <div class="extra-addons-wrap d-flex flex-wrap" v-if="
                  product.shop_name != null &&
                  product.shop_slug
                ">
                  <p class="product-shop fz-12">
                    {{ $t("Sold By") }}
                    <router-link :to="`/shop/${product.shop_slug}`" target="_blank" class="c1">
                      {{ product.shop_name }}
                    </router-link>
                  </p>
                </div>

              </div>
            </div>
            <div class="price-info col-4">
              <p class="font-weight-regular text-black fz-14 mb-1 d-flex justify-content-end">
                <the-currency :amount="product.unitPrice">
                </the-currency>
              </p>

              <p class="mb-1 font-weight-regular fz-14 d-flex justify-content-end gap-2">
                {{ $t("Total") }}: <the-currency :amount="product.unitPrice * product.quantity">
                </the-currency>
              </p>

            </div>
          </div>
          <div class="row shipping-content" v-if="showNotAvailableAlert">
            <div class="pl-shipping large">
              <div class="pl-shipping-left">
                <p class="pl-shipping-left-title font-weight-medium mb-0">{{ $t("Shipping") }}</p>
                <p class="text-danger fz-12">{{ $t("Shipping not available for selected address") }}</p>
              </div>
              <div class="pl-shipping-right p-0">
                <button class="bg-danger border border-0 border-danger fz-12 text-white"
                  @click.prevent="removeCheckoutItem(product.uid)">{{ $t("Remove") }}</button>
              </div>
            </div>
          </div>
        </div>
        <!--End Shipping Not Available-->


        <div class="col-12 mb-2 mt-2 single-package border-bottom"
          v-for="(shipping_package, index2) in shippingPackages" :key="index2">
          <div class="row d-flex product-content justify-content-between mb-2">
            <div class="product-info d-flex gap-2 col-8">
              <div class="image">
                <router-link :to="`/products/${shipping_package.product.permalink}`" class="product-img">
                  <img :src="shipping_package.product.image" :alt="shipping_package.product.name"
                    class="border img-70 rounded" />
                </router-link>
              </div>
              <div class="description">
                <router-link :to="`/products/${shipping_package.product.permalink}`">
                  <h5 class="product_name  font-weight-regular fz-14 mb-0 text-black text-capitalize">
                    {{ shipping_package.product.name }}
                  </h5>
                </router-link>
                <p class="product-variant mb-1 fz-sm-12" v-if="shipping_package.product.variant">
                  <product-variant :variant="shipping_package.product.variant"></product-variant>
                </p>
                <p class="mb-1 fz-sm-12">
                  {{ $t("Qty") }}: {{ shipping_package.product.quantity }}
                </p>
                <!--Attachment-->
                <div class="align-items-center d-flex flex-wrap gap-1 product-document"
                  v-if="shipping_package.product.attachment != null">
                  <p class="fz-sm-12 mb-0">
                    {{ shipping_package.product.attachment.title }}:
                    <a :href="`${shipping_package.product.attachment.path}`" target="_blank">
                      {{ shipping_package.product.attachment.file_name }}
                    </a>
                  </p>
                </div>
                <!--End Attachment-->
                <div class="extra-addons-wrap d-flex flex-wrap" v-if="
                  shipping_package.product.shop_name != null &&
                  shipping_package.product.shop_slug
                ">
                  <p class="product-shop fz-12">
                    {{ $t("Sold By") }}
                    <router-link :to="`/shop/${shipping_package.product.shop_slug}`" target="_blank" class="c1">
                      {{ shipping_package.product.shop_name }}
                    </router-link>
                  </p>
                </div>

              </div>
            </div>
            <div class="price-info col-4">
              <p class="font-weight-regular text-black fz-14 mb-1 d-flex justify-content-end">
                <the-currency :amount="shipping_package.product.unitPrice">
                </the-currency>
              </p>

              <p class="mb-1 font-weight-regular fz-14 d-flex justify-content-end gap-2">
                {{ $t("Total") }}: <the-currency
                  :amount="shipping_package.product.unitPrice * shipping_package.product.quantity">
                </the-currency>
              </p>

            </div>
          </div>
          <!--Shipping Option -->
          <div v-if="
            isActiveHomeDelivery && config.shipping_option == 3
          " class="row shipping-content" @click.prevent="openShippingOptionPopup(shipping_package.id)">
            <div class="pl-shipping large">
              <div class="pl-shipping-left">
                <div class="pl-shipping-left-title font-weight-medium">
                  {{ $t("Shipping") }}:
                  <the-currency :amount="shipping_package.default_option.shipping_cost"></the-currency>
                </div>
                <div class="pl-shipping-left-cost">
                  {{ $t("Estimated Delivery Time") }}:
                  {{ shipping_package.default_option.shipping_time }}
                </div>
                <div class="pl-shipping-left-tags"></div>
              </div>
              <div class="pl-shipping-right p-0">
                <span class="material-icons"> arrow_forward_ios </span>
              </div>
            </div>
          </div>
          <!--End Shipping Options-->

        </div>


      </div>
    </template>
    <div class="row mb-4" v-if="loading">
      <skeleton class="col-12 mb-20 single-package border-bottom" height="70px"></skeleton>
      <skeleton class="col-12 mb-2 mt-2 single-package border-bottom" height="150px"></skeleton>
      <skeleton class="col-12 mb-2 mt-2 single-package border-bottom" height="150px"></skeleton>
    </div>

    <!--Shipping Address picker -->
    <CModal :visible="visibleShippingOptionPicker" size="md" @close="
      () => {
        visibleShippingOptionPicker = false;
      }
    ">
      <CModalHeader>
        <CModalTitle>{{ $t("Shipping Options") }}</CModalTitle>
        <button class="btn-circle bg-black size-35" @click.prevent="
          () => {
            visibleShippingOptionPicker = false;
          }
        ">
          <base-icon-svg name="close" :width="10" :height="10" />
        </button>
      </CModalHeader>
      <CModalBody>
        <div class="row address-list">
          <div class="col-lg-12 mb-4" v-for="(option, index) in shippingOptions.options.data" :key="index">
            <span class="custom-radio-btn" ref="optionRadio" :class="{
              active: option.id == shippingOptions.default_option.id,
            }">
              <label class="radio-label">
                <input name="shippingOption" type="radio" :value="option" v-model="shippingOptions.default_option"
                  :checked="option.id == shippingOptions.default_option.id" @change.prevent="changeShippingOption" />
                <span class="radio-text">
                  <span class="font-weight-bold"> {{ option.title }} </span>
                  <small class="m-2" v-if="option.by">{{ $t("via") }} {{ option.by }}</small>
                  <br />
                  <span>
                    {{ $t("Shipping Cost") }}:
                    <the-currency :amount="option.shipping_cost" class="ml-1"></the-currency></span>
                  <br />
                  <span>
                    From {{ option.shipping_from }} to
                    {{ shippingDetails.city.name }},
                    {{ shippingDetails.state.name }},
                    {{ shippingDetails.country.name }}.
                  </span>
                  <br />
                  <span v-if="option.shipping_time">{{ $t("Estimated Delivery:") }}
                    {{ option.shipping_time }}</span>
                </span>
              </label>
            </span>
          </div>
        </div>
      </CModalBody>
    </CModal>
    <!-- End Shipping option picker -->
  </div>
</template>
<script>
import ProductVariant from "../ui/ProductVariant.vue";
import axios from "axios";
import { mapState } from "vuex";
import {
  CModal,
  CButton,
  CModalHeader,
  CModalTitle,
  CModalBody,
} from "@coreui/vue";
export default {
  name: "ProductSummary",
  components: {
    ProductVariant,
    CModal,
    CButton,
    CModalHeader,
    CModalTitle,
    CModalBody,
  },
  emits: ["finalize-product"],
  props: {
    config: {
      type: Object,
      required: true,
    },
    enums: {
      type: Object,
      required: true,
    },
    isCustomerLogin: {
      type: Boolean,
      required: false,
      default: false,
    },

  },
  data() {
    return {
      loading: false,
      selectedPickupPoint: "",
      visibleShippingOptionPicker: false,
      shippingOptions: [],
      city_id: null,
      post_code: null,
      zone_id: null,
      errors: [],
      shippingPackages: [],
      shippingNotAvailableProducts: [],
      showNotAvailableAlert: false,
    };
  },
  computed: mapState({
    isActiveHomeDelivery: (state) => state.isActiveHomeDelivery,
    checkoutItems: (state) => state.checkoutItems,
    shippingDetails: (state) => state.shippingDetails,
  }),

  watch: {
    city_id(val, oldVal) {
      this.showNotAvailableAlert = this.city_id ? true : false;
    },
  },
  mounted() {
    this.getShippingOptions();
  },
  methods: {
    /**
     * Calculate shipping cost
     */
    getShippingOptions() {

      if (this.isActiveHomeDelivery && this.shippingDetails != null) {
        let new_city = this.shippingDetails.city ? this.shippingDetails.city.id : '';
        if (new_city != this.city_id) {
          this.city_id = new_city;
        } else {
          this.city_id = new_city;
          return 0;
        }
        this.post_code = this.shippingDetails.city ? this.shippingDetails.city.post_code : '';
      } else {
        this.city_id = null;
        this.post_code = null;
      }

      this.shippingNotAvailableProducts = [];
      this.shippingPackages = [];
      this.loading = true;

      axios
        .post("/api/v1/ecommerce-core/get-shipping-options", {
          location: this.isActiveHomeDelivery ? this.city_id : null,
          shipping_type: this.isActiveHomeDelivery ? "home_delivery" : "pickup_delivery",
          post_code: this.post_code,
          products: JSON.stringify(this.$store.state.checkoutItems),
          coupons: JSON.stringify(this.$store.state.couponDiscount),
          zone_id: this.zone_id
        })
        .then((response) => {

          if (response.data.success) {
            //Available Shipping Options
            if (response.data.options && response.data.options.length > 0) {
              this.shippingPackages = response.data.options;
            }

            //Not Available Products
            if (response.data.not_available_products && response.data.not_available_products.length > 0) {
              this.shippingNotAvailableProducts = response.data.not_available_products;
            }

            this.calculateShippingCostAndTax();

          } else {
            this.$store.dispatch("setFinalShippingCost", 0);
          }
          this.loading = false;
        })
        .catch((error) => {
          this.loading = false;
          this.$store.dispatch("setFinalShippingCost", 0);
        });
    },
    /**
     * Open shipping option popup
     * @param {*} id
     */
    openShippingOptionPopup(id) {
      let option = this.shippingPackages.find((item) => item.id === id);
      if (option) {
        this.shippingOptions = option;
        this.visibleShippingOptionPicker = true;
      }
    },
    /**
     * Change Shipping option
     * @param {*} e
     */
    changeShippingOption(e) {
      this.$refs.optionRadio.forEach((element) => {
        element.classList.remove("active");
      });
      e.target.parentElement.parentElement.classList.add("active");
      this.calculateShippingCostAndTax();
    },
    /**
     * Calculate shipping cost
     *
     * Calculate tax
     *
     */
    calculateShippingCostAndTax() {
      //calculate subtotal
      let total_unit_price = 0;
      total_unit_price = this.shippingPackages.reduce((accum, item) => {
        return (
          parseFloat(accum) + parseFloat(item.product.unitPrice * item.product.quantity)
        );
      }, 0.0);
      this.$store.dispatch("setFinalSubtotal", total_unit_price);


      //Calculate shipping cost
      let total_shipping_cost = 0;
      total_shipping_cost = this.shippingPackages.reduce((accum, item) => {
        return (
          parseFloat(accum) + parseFloat(item.default_option.shipping_cost)
        );
      }, 0.0);
      this.$store.dispatch("setFinalShippingCost", this.isActiveHomeDelivery ? total_shipping_cost : 0);

      //calculate tax
      let total_tax = 0;
      total_tax = this.shippingPackages.reduce((accum, item) => {
        return parseFloat(accum) + parseFloat(item.tax);
      }, 0.0);
      this.$store.dispatch("setFinalTax", this.config.enable_tax_in_checkout == this.enums.status.ACTIVE ? total_tax : 0);
      this.visibleShippingOptionPicker = false;
      this.finalizeProduct();
      this.loading = false;
    },
    /**
     * Will finalize product
     */
    finalizeProduct() {
      let final_product_packages = [];
      for (let i = 0; i < this.shippingPackages.length; i++) {
        let temp = {
          uid: this.shippingPackages[i].id,
          tax:
            this.config.enable_tax_in_checkout == this.enums.status.ACTIVE
              ? this.shippingPackages[i].tax
              : 0,
          product_id: this.shippingPackages[i].product.id,
          quantity: this.shippingPackages[i].product.quantity,
          unitPrice: this.shippingPackages[i].product.unitPrice,
          oldPrice: this.shippingPackages[i].product.oldPrice,
          variant_code: this.shippingPackages[i].product.variant_code,
          variant: this.shippingPackages[i].product.variant,
          image: this.shippingPackages[i].product.image,
          shipping_cost: this.isActiveHomeDelivery
            ? this.shippingPackages[i].default_option.shipping_cost
            : 0,
          shipping_rate_id: this.isActiveHomeDelivery
            ? this.shippingPackages[i].default_option.id
            : "",
          attatchment:
            this.shippingPackages[i].product.attachment != null
              ? this.shippingPackages[i].product.attachment.file_id
              : null,
        };
        final_product_packages.push(temp);
      }
      this.$emit("finalize-product", final_product_packages);
    },
    removeCheckoutItem(uid) {
      let index = this.checkoutItems.findIndex(item => item.uid === uid);
      if (index !== -1) {
        this.checkoutItems.splice(index, 1);
      }
      this.$store.dispatch("addItemsToCheckoutItems", this.checkoutItems);

      let index2 = this.shippingNotAvailableProducts.findIndex(item => item.uid === uid);
      if (index2 !== -1) {
        this.shippingNotAvailableProducts.splice(index2, 1);
      }


    }
  },
};
</script>
<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.cart-image {
  width: 90px;
  height: 90px;
}

.pl-store-custom-container2 .pl-shipping:last-child {
  padding-bottom: 0;
}

.pl-shipping.large {
  margin-top: 0;
  cursor: pointer;
}

.pl-shipping {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-pack: justify;
  -webkit-justify-content: space-between;
  -ms-flex-pack: justify;
  justify-content: space-between;
  color: #999;
  text-align: center;
  -webkit-box-align: center;
  -webkit-align-items: center;
  -ms-flex-align: center;
  align-items: center;
  font-size: 12px;
  margin-top: 12px;
  position: relative;
  padding-bottom: 12px;
}

.pl-shipping.large .pl-shipping-left {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-orient: vertical;
  -webkit-box-direction: normal;
  -webkit-flex-direction: column;
  -ms-flex-direction: column;
  flex-direction: column;
}

.pl-shipping.large .pl-shipping-left .pl-shipping-left-title {
  margin-right: 6px;
}

.pl-shipping-left-title {
  font-family: $title-font;
  font-weight: 700;
  color: #222;
  letter-spacing: 0;
}

.pl-shipping.large .pl-shipping-left .pl-shipping-left-cost {
  font-weight: 700;
}

.pl-shipping-left-cost {
  font-family: $title-font;
  font-size: 10px;
  color: #999;
  letter-spacing: 0;
}

.pl-shipping-left-tags,
.pl-shipping-left-tags-item {
  display: -webkit-box;
  display: -webkit-flex;
  display: -ms-flexbox;
  display: flex;
}

.pl-shipping-left-tags {
  -webkit-flex-wrap: nowrap;
  -ms-flex-wrap: nowrap;
  flex-wrap: nowrap;
}

.pl-shipping-left {
  text-align: start;
}

.pl-shipping-right {
  height: -webkit-fit-content;
  height: -moz-fit-content;
  height: fit-content;
  position: relative;
  padding-right: 14px;
}

.product_name {
  display: block;
  display: -webkit-box;
  -webkit-line-clamp: 1;
  -webkit-box-orient: vertical;
  overflow: hidden;
  text-overflow: ellipsis;
}

.product-price {
  color: #3b3b3b;
  font-size: 16px;
  font-weight: 700;
}

@media (max-width: 575px) {
  .fz-sm-12 {
    font-size: 12px !important;
    line-height: 14px !important;
  }

  .fz-sm-14 {
    font-size: 14px !important;
    line-height: 14px !important;
  }
}
</style>
