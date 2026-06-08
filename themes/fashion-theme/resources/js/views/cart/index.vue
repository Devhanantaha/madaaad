<template>
  <div class="">
    <page-header :items="bItems" />

    <div class="pt-60 pb-60 cart-page">
      <div class="custom-container2">
        <div class="row" v-if="!dataLoading">

          <!--Cart Table-->
          <div class="col-lg-8 col-12" v-if="tableData.length">
            <div class="shadow-card">
              <h3 class="checkout-title">{{ $t("Your Cart") }}</h3>
              <!--Items List-->
              <div class="row m-0">
                <div class="align-items-center border-bottom col-12 d-flex justify-content-between pb-10 px-0">
                  <div class="d-flex gap-2 selector">
                    <input type="checkbox" class="item-selector text-black" v-model="all_selected"
                      :checked="all_selected" @click="selectOrDeselectItems" id="all-selector" />
                    <label class="fz-12 pt-1 text-black" for="all-selector">
                      {{ $t("SELECT ALL") }}
                    </label>
                  </div>
                  <div class="delete-all">
                    <router-link to="#" class="d-flex align-items-center" @click="clearCart">
                      <span class="material-icons"> delete </span>
                      <span class="fz-12 mt-1">{{ $t("REMOVE ALL") }}</span>
                    </router-link>
                  </div>
                </div>
                <div class="col-12 mb-2 mt-2 px-0 single-package border-bottom" v-for="tdata in tableData"
                  :key="tdata.id" :class="{
                    disableCart: tdata.is_available == 2,
                  }">
                  <div class="row product-content align-items-center mb-2">
                    <div class="align-items-center col-4 col-lg-2 d-flex gap-3">
                      <input type="checkbox" v-model="tdata.is_selected" :checked="tdata.is_selected"
                        class="item-selector" />
                      <router-link :to="`/products/${tdata.permalink}`" class="product-img d-flex align-items-center">
                        <img :src="tdata.image" :alt="tdata.name" class="cart-image-review" />
                      </router-link>
                    </div>
                    <div class="col-lg-10 col-8">
                      <div class="row">
                        <div class="col-12 col-lg-7 description px-0">
                          <router-link :to="`/products/${tdata.permalink}`">
                            <h5 class="product_name text-capitalize fz-sm-14">
                              {{ tdata.name }}
                            </h5>
                          </router-link>
                          <p class="product-variant mb-1 cart-info" v-if="tdata.variant">
                            <product-variant :variant="tdata.variant"></product-variant>
                          </p>
                          <p class="unit-price mb-1 cart-info">
                            <span>{{ $t("Unit Price") }}: </span>
                            <the-currency :amount="tdata.unitPrice">
                            </the-currency>
                          </p>

                          <div class="extra-addons-wrap d-flex flex-wrap"
                            v-if="tdata.shop_name != null && tdata.shop_slug">
                            <p class="product-shop cart-info">
                              {{ $t("Sold By") }}
                              <router-link :to="`/shop/${tdata.shop_slug}`" target="_blank" class="c1">
                                {{ tdata.shop_name }}
                              </router-link>
                            </p>
                          </div>
                          <!--Attachment-->
                          <div v-if="tdata.attachment != null">
                            <div
                              class="extra-addons-wrap d-flex flex-wrap mb-1 product-document align-items-center gap-1"
                              :id="`info-${tdata.uid}`">
                              <p class="font-weight-medium cart-info mb-0">
                                {{ tdata.attachment.title }}:
                                <a :href="`${tdata.attachment.path}`" target="_blank">
                                  {{ tdata.attachment.file_name }}
                                </a>
                              </p>
                              <span class="bg-info doc-btn cursor-pointer fz-12 px-2 py-1 text-white"
                                @click="modifyAttachment('change', tdata.uid)">{{ $t("Change") }}</span>
                            </div>
                            <div
                              class="extra-addons-wrap d-flex flex-wrap mb-1 upload-product-document align-items-center gap-1 d-none"
                              :id="`upload-${tdata.uid}`">
                              <p class="font-weight-medium cart-info mb-0">
                                {{ tdata.attachment.title }}:
                                <input type="file" name="attach"
                                  @change="changeAttachment($event, tdata.uid, tdata.attachment.file_id, tdata.attachment.title)" />
                              </p>
                              <span class="bg-danger doc-btn cursor-pointer fz-12 px-2 py-1 text-white"
                                @click="modifyAttachment('cancel', tdata.uid)">
                                {{ $t("Cancel") }}
                              </span>
                            </div>
                          </div>
                          <!--End Attachment-->
                        </div>
                        <div class="col-12 col-lg-5 d-flex justify-content-lg-end pl-0">
                          <div
                            class="align-items-center cart-actions d-flex gap-lg-4 justify-content-between justify-content-lg-end w-100">
                            <!--Quantity-->
                            <div class="quantity-input text-center d-flex">
                              <button
                                class="d-flex align-items-center justify-content-center p-0 bg-transparent border-0"
                                @click.prevent="decrease(tdata.uid)">
                                <span class="material-icons"> remove </span>
                              </button>
                              <input v-model="tdata.quantity" type="number"
                                class="border-0 text-center font-weight-bold w-100" @change="
                                  () => {
                                    if (tdata.quantity > tdata.max_item) {
                                      tdata.quantity = tdata.max_item;
                                    } else if (
                                      tdata.quantity < tdata.min_item
                                    ) {
                                      tdata.quantity = tdata.min_item;
                                    } else {
                                      tdata.quantity = parseInt(tdata.quantity);
                                    }
                                    updateCartItems(tdata);
                                  }
                                " />
                              <button
                                class="d-flex align-items-center justify-content-center p-0 bg-transparent border-0"
                                @click.prevent="increase(tdata.uid)">
                                <span class="material-icons"> add </span>
                              </button>
                            </div>
                            <!--End Quantity-->
                            <p class="mb-0 product-price fz-sm-12 c1">
                              <the-currency :amount="tdata.unitPrice * tdata.quantity">
                              </the-currency>
                            </p>
                            <!--Remove btn-->
                            <span class="icon-wrap d-flex" @click.prevent="removeItem(tdata.uid)">
                              <span class="material-icons text-danger"> delete </span>
                            </span>
                            <!--End remove btn-->
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

              </div>
              <!--End Items-->
              <!--Minimum order amount alert-->
              <div class="d-none d-lg-block mt-3 col-12 d-lg-flex flex-wrap justify-content-end">
                <p class="text text-danger" v-if="
                  !proceedToCheckout &&
                  config.enable_minimum_order_amount == 1
                ">
                  {{ $t("Minimum Order Amount") }}
                  <the-currency :amount="config.min_order_amount" tag="span"></the-currency>
                </p>
              </div>
              <!--End minimum order amount alert-->
              <!--Desktop Action Buttons-->
              <div class="d-none d-lg-block mt-3 col-12 d-lg-flex flex-wrap justify-content-between">
                <router-link to="/products" class="btn btn_border justify-content-center m-w-100 mb-20">
                  <span class="material-icons me-2"> arrow_back </span>
                  {{ $t("Continue Shopping") }}
                </router-link>

                <button type="button" class="btn btn_fill mb-20 justify-content-center m-w-100"
                  @click.prevent="createOrder" :disabled="!proceedToCheckout">
                  {{ $t("Checkout") }}
                  <span class="material-icons ms-2"> arrow_forward </span>
                </button>
              </div>
              <!--End Desktop Action Buttons-->
            </div>
          </div>
          <!--End Cart Table-->
          <!--Order Summary-->
          <div class="col-lg-4 col-12" v-if="tableData.length">
            <div class="shadow-card" v-if="tableData.length">
              <h3 class="checkout-title">{{ $t("Order Summary") }}</h3>
              <div class="order-details">
                <div class="table-responsive mt-3">
                  <!--Coupon Apply Area-->
                  <div v-if="enableApplyCoupon" class="coupon">
                    <div class="form-group">
                      <input class="form-control me-1" type="text" v-model="coupon_code"
                        v-bind:placeholder="$t('Your Coupon')" />
                      <button type="submit" class="btn coupon-btn btn_border py-0" :disabled="couponApplying"
                        @click.prevent="applyCoupon">
                        <span v-if="couponApplying">
                          <CSpinner component="span" size="sm" aria-hidden="true" />
                          {{ $t("Wait") }}
                        </span>
                        <span v-else>
                          {{ $t("Apply") }}
                        </span>
                      </button>
                    </div>
                  </div>
                  <!--End Coupon apply area-->
                  <!--Summary-->
                  <table class="shop_table w-100">
                    <tbody>
                      <!--Subtotal-->
                      <tr class="sub-total">
                        <td>{{ $t("Subtotal") }}</td>
                        <td>
                          <span class="price-amount amount">
                            <bdi>
                              <span class="price-currency-symbol"></span>
                              <the-currency :amount="totalUnitPrice"></the-currency>
                            </bdi>
                          </span>
                        </td>
                      </tr>
                      <!--End Sub total-->
                      <!--Coupon discount-->
                      <template v-if="
                        couponDiscounts.length > 0 &&
                        config.enable_coupon_in_checkout ==
                        this.enums.status.ACTIVE
                      ">
                        <tr class="order-savings font-weight-regular" v-for="(discount, index) in couponDiscounts"
                          :key="index">
                          <td class="d-flex">
                            <span class="c1">{{ discount.coupon_code }}</span>
                            <a href="#" class="material-icons c1 mt-1" @click.prevent="
                              removeCoupon(discount.coupon_code)
                              ">delete
                            </a>
                          </td>
                          <td>
                            <span class="price-amount amount c1">
                              <bdi>
                                <span class="price-currency-symbol">-</span>
                                <the-currency :amount="discount.discount"></the-currency>
                              </bdi>
                            </span>
                          </td>
                        </tr>
                      </template>
                      <template v-else>
                        <tr class="no-discount font-weight-regular">
                          <td>{{ $t("Discount") }}</td>
                          <td>
                            <span class="price-amount amount">
                              <bdi>
                                <span class="price-currency-symbol"></span>
                                <the-currency :amount="0"></the-currency>
                              </bdi>
                            </span>
                          </td>
                        </tr>
                      </template>
                      <!--End Coupon discount-->
                      <!--Total Amount-->
                      <tr class="font-weight-bold grand-total">
                        <td>{{ $t("Total") }}</td>
                        <td>
                          <span class="price-amount amount">
                            <bdi>
                              <span class="price-currency-symbol"></span>
                              <the-currency :amount="totalUnitPrice - totalDiscount"></the-currency>
                            </bdi>
                          </span>
                        </td>
                      </tr>
                      <!--End Total Amount-->
                    </tbody>
                  </table>
                  <!--End Summary-->
                  <!--Minimum order amount alert-->
                  <div class="d-block d-lg-none mt-3 col-12 d-flex flex-wrap">
                    <p class="text text-danger" v-if="
                      !proceedToCheckout &&
                      config.enable_minimum_order_amount == 1
                    ">
                      {{ $t("Minimum Order Amount") }}
                      <the-currency :amount="config.min_order_amount" tag="span"></the-currency>
                    </p>
                  </div>
                  <!--End minimum order amount alert-->
                  <!--Mobile Action Buttons-->
                  <div class="d-block d-lg-none mt-3 col-12 d-flex flex-wrap justify-content-between">
                    <router-link to="/products" class="btn btn_border justify-content-center m-w-100 mb-20">
                      <span class="material-icons me-2"> arrow_back </span>
                      {{ $t("Continue Shopping") }}
                    </router-link>

                    <button type="button" class="btn btn_fill mb-20 justify-content-center m-w-100"
                      @click.prevent="createOrder" :disabled="!proceedToCheckout">
                      {{ $t("Checkout") }}
                      <span class="material-icons ms-2"> arrow_forward </span>
                    </button>
                  </div>
                  <!--End Mobile Action Buttons-->
                </div>
              </div>
            </div>
          </div>
          <!--End Order Summary-->

          <!--No Items found-->
          <div class="col-12" v-if="!tableData.length">

            <div class="empty-cart text-center">
              <h2 class="mb-4">
                {{ $t("Your Cart is Empty") }}
              </h2>
              <router-link to="/products" class="btn btn_fill">
                {{ $t("Continue Shopping") }}
              </router-link>
            </div>
          </div>
          <!--No Items Found-->
        </div>

        <!--Preloader-->
        <div class="row" v-if="dataLoading">
          <div class="col-12 col-lg-8">
            <skeleton class="w-100 mb-20" height="350px"></skeleton>
            <div class="mt-3 d-flex flex-wrap justify-content-between">
              <skeleton class="justify-content-center m-w-100 mb-20" tag="a" height="40px" width="150px"></skeleton>
              <skeleton class="justify-content-center m-w-100 mb-20" tag="a" height="40px" width="150px"></skeleton>
            </div>
          </div>
          <div class="col-12 col-lg-4">
            <skeleton class="w-100 mb-20" height="300px"></skeleton>
          </div>
        </div>
        <!--End Preloader-->
      </div>
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import OrderTotal from "@/components/product/OrderTotal.vue";
import enums from "../../enums/enums";
import ProductVariant from "@/components/ui/ProductVariant.vue";
import { mapState } from "vuex";
import { useCart } from "@/composables/useCart";
import axios from "axios";
import {
  CTable,
  CTableHead,
  CTableBody,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
} from "@coreui/vue";
export default {
  name: "Cart",
  layout: "main",
  components: {
    ProductVariant,
    PageHeader,
    CTable,
    CTableBody,
    CTableRow,
    CTableDataCell,
    CTableHeaderCell,
    CTableHead,
    OrderTotal,
  },
  data() {
    return {
      ...useCart(),
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Cart"),
          active: true,
        },
      ],
      proceedToCheckout: false,
      dataLoading: true,
      enums: enums,
      errors: [],
      tableData: [],
      coupon_code: "",
      couponApplying: false,
      all_selected: true,
      checkoutItems: [],
      attachment: null,
    };
  },
  computed: mapState({
    customerToken: (state) => state.customerToken,
    isCustomerLogin: (state) => state.isCustomerLogin,
    customer_id: (state) =>
      state.customerInfo != null ? state.customerInfo.id : null,
    config: (state) => state.siteSettings,
    couponDiscounts: (state) =>
      state.couponDiscount ? state.couponDiscount : [],
    totalUnitPrice() {
      return this.tableData.reduce((accum, item) => {
        if (item.is_available == 2 || !item.is_selected) {
          return parseFloat(accum);
        }

        return parseFloat(accum) + parseFloat(item.unitPrice * item.quantity);
      }, 0.0);
    },
    totalDiscount() {
      return this.couponDiscounts.reduce((accum, item) => {
        return parseFloat(accum) + parseFloat(item.discount);
      }, 0.0);
    },
    enableApplyCoupon() {
      if (
        this.config.is_active_coupon == this.enums.status.ACTIVE &&
        this.config.enable_coupon_in_checkout == this.enums.status.ACTIVE
      ) {
        if (
          this.couponDiscounts.length > 0 &&
          this.config.enable_multiple_coupon_in_checkout ==
          this.enums.status.IN_ACTIVE
        ) {
          return false;
        } else if (
          this.config.enable_coupon_in_checkout == this.enums.status.IN_ACTIVE
        ) {
          return false;
        } else {
          return true;
        }
      } else {
        return false;
      }
    },
  }),
  mounted() {
    document.title = this.$t("Cart");
    this.validateCustomerCart();
  },
  watch: {
    totalUnitPrice() {
      this.checkMinimumOrderAmount();
    },
    tableData: {
      handler(newValue, oldValue) {
        this.selectOrDeselectButton();
      },
      deep: true,
    },
  },
  methods: {
    modifyAttachment(action, uid) {
      if (action == 'change') {
        document.getElementById(`info-${uid}`).classList.add('d-none');
        document.getElementById(`upload-${uid}`).classList.remove('d-none');
      }
      if (action == 'cancel') {
        document.getElementById(`info-${uid}`).classList.remove('d-none');
        document.getElementById(`upload-${uid}`).classList.add('d-none');
      }
    },
    changeAttachment(event, uid, file_id, title) {
      const file = event.target.files[0];
      if (!file) return;

      let formData = new FormData();
      formData.append("attachment_old", file_id);
      formData.append("attachment", file);
      axios
        .post("/api/v1/ecommerce-core/upload-attachment-in-order", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then((response) => {
          if (response.data.success) {
            let tempData = null;
            tempData = response.data.attatchment;
            tempData.title = title;
            //Update data
            const data = this.tableData;
            for (let i = 0; i < data.length; i++) {
              const item = data[i];
              if (item.uid === uid) {
                item.attachment = tempData;
                this.updateCartItems(item);
              }
            }
            this.modifyAttachment('cancel', uid);
            this.$toast.success(title + " " + this.$t("updated successfully"));
          } else {
            this.$toast.error(title + " " + this.$t("updated failed"));
          }
        })
        .catch((error) => {
          this.$toast.error(title + " " + this.$t("update failed"));
        });

    },
    //Select or deselect sector button
    selectOrDeselectButton() {
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (!item.is_selected) {
          this.all_selected = false;
          break;
        }
        this.all_selected = true;
      }
    },
    selectOrDeselectItems() {
      this.all_selected = !this.all_selected;
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (this.all_selected && item.is_available == 1) {
          item.is_selected = true;
        } else {
          item.is_selected = false;
        }
      }
    },
    /**
     * Validate cart items
     */
    validateCustomerCart() {
      this.$store.dispatch("showPreloader", true);
      let items = JSON.stringify(this.$store.state.cart);

      this.validateCartItems(items).then(() => {
        if (this.cartSuccess) {
          this.tableData = this.validateItems;
          this.checkMinimumOrderAmount();
        }
        this.$store.dispatch("showPreloader", false);
        this.dataLoading = false;
      });
    },
    selectOrDeselectItems() {
      this.all_selected = !this.all_selected;
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (this.all_selected && item.is_available == 1) {
          item.is_selected = true;
        } else {
          item.is_selected = false;
        }
      }
    },
    /**
    * Will clear cart
    */
    clearCart() {
      if (this.isCustomerLogin) {
        this.$store.dispatch("showPreloader", true);
        let uid = 'all';
        this.removeCartItem(uid).then(() => {
          if (this.cartSuccess) {
            this.tableData = [];
            this.$store.dispatch("updateCart", []).then(() => {
              this.$toast.success(this.$t("Cart clear successfully"));
            });
          } else {
            this.$store.dispatch("showPreloader", false);
          }
        });
      } else {
        this.tableData = [];
        this.$store.dispatch("updateCart", []).then(() => {
          this.$toast.success(this.$t("Cart clear successfully"));
        });
      }
    },
    /**
     *
     */
    checkMinimumOrderAmount() {
      if (this.config.enable_minimum_order_amount == this.enums.status.ACTIVE) {
        if (this.totalUnitPrice < this.config.min_order_amount) {
          this.proceedToCheckout = false;
        } else {
          this.proceedToCheckout = true;
        }
      } else {
        this.proceedToCheckout = true;
      }
    },
    nextStep() {
      this.$emit("go-next-step");
    },
    /**
     * Decrease item number from cart
     *
     * @param {*} id
     */
    decrease(id) {
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (item.uid === id) {
          if (item.quantity > 1 && item.quantity > item.min_item) {
            item.quantity--;
            this.updateCartItems(item);
          } else {
            return;
          }
        }
      }
    },
    /**
     * Increase item number from cart
     *
     * @param {*} id
     */
    increase(id) {
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (item.uid === id) {
          if (item.quantity > 0 && item.quantity < item.max_item) {
            item.quantity++;
            this.updateCartItems(item);
          } else {
            return;
          }
        }
      }
    },
    /**
     * This method will update cart
     */
    updateCartItems(item) {
      //For authenticate customer
      if (this.isCustomerLogin) {
        this.$store.dispatch("showPreloader", true);
        this.updateCartItem(JSON.stringify(item)).then(() => {
          this.$store.dispatch("showPreloader", false);
          if (this.cartSuccess) {
            this.$store.dispatch("updateCart", this.tableData);
            this.$store.dispatch("flushCouponData");
          }
        });
      }
      //For guest customer
      if (!this.isCustomerLogin) {
        this.$store.dispatch("updateCart", this.tableData);
        this.$store.dispatch("flushCouponData");
      }
    },

    /**
     * Remove item from cart
     *
     * @param {*} index
     */
    removeItem(index) {
      let updatedTableData = this.tableData.filter(
        (item) => item.uid !== index
      );
      this.tableData = updatedTableData;
      //For authenticate customer
      if (this.isCustomerLogin) {
        this.$store.dispatch("showPreloader", true);
        this.removeCartItem(index).then(() => {
          if (this.cartSuccess) {
            this.$store.dispatch("updateCart", updatedTableData).then(() => {
              this.$store.dispatch("flushCouponData").then(() => {
                this.$toast.success("Product removed from cart successfully");
              });
            });
          } else {
            this.$store.dispatch("showPreloader", false);
          }
        });
      }
      //For guest customer
      if (!this.isCustomerLogin) {
        this.$store.dispatch("updateCart", updatedTableData).then(() => {
          this.$store.dispatch("flushCouponData").then(() => {
            this.$toast.success(
              this.$t("Product remove from cart successfully")
            );
          });
        });
      }
    },
    /**
     * Will apply coupon code
     *
     */
    applyCoupon() {
      this.prepareCheckoutItems();
      let checkCoupon = this.couponDiscounts.find(
        (coupon) => coupon.coupon_code == this.coupon_code
      );
      if (checkCoupon) {
        this.$toast.error(this.$t("Already applied"));
        return 0;
      }
      this.couponApplying = true;
      let customer_id = this.isCustomerLogin ? this.customer_id : null;
      this.applyCouponInCart(this.coupon_code, JSON.stringify(this.checkoutItems), customer_id).then(() => {
        if (this.couponApply) {
          //Get Discount
          if (this.couponDiscount > 0) {
            this.$store
              .dispatch("storeCouponDiscount", this.appliedCoupon)
              .then(() => {
                this.$toast.success(this.$t("Coupon applied successfully"));
                this.coupon_code = "";
              });
          }

          //does not get discount
          if (this.couponDiscount < 1) {
            this.$toast.error("Coupon is not applied");
          }
        } else {
          this.$toast.error("Coupon is not applied");
        }

        this.couponApplying = false;

      });
    },
    /**
     * Remove Applied coupon
     */
    removeCoupon(code) {
      this.$store.dispatch("removeCouponDiscount", code).then(() => {
        this.$toast.success(this.$t("Coupon Remove Successfully"));
      });
    },
    //Prepare checkout items
    prepareCheckoutItems() {
      this.checkoutItems = [];
      const data = this.tableData;
      for (let i = 0; i < data.length; i++) {
        const item = data[i];
        if (item.is_available == 1 && item.is_selected) {
          this.checkoutItems.push(item);
        }
      }
    },
    /**
     * Go to checkout page
     *
     */
    createOrder() {
      this.prepareCheckoutItems();
      if (this.checkoutItems.length > 0) {
        this.$store
          .dispatch("addItemsToCheckoutItems", this.checkoutItems)
          .then(() => {
            this.$router.push("/checkout");
          });
      }
      if (this.checkoutItems.length < 1) {
        this.$toast.error(this.$t("No item selected for checkout"));
      }
    },
  },
};
</script>
<style scoped>
.item-selector {
  min-width: 14px;
  cursor: pointer;
}

.pl-0 {
  padding-left: 0;
}

.disableCart {
  position: relative;
}

.disableCart:after {
  content: "Not Available";
  text-align: center;
  font-size: 20px;
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #fff;
  -webkit-backdrop-filter: blur(20px);
  backdrop-filter: blur(51px);
  opacity: 0.8;
  z-index: 1;
  line-height: 77px;
}

.close-btn {
  position: relative;
  z-index: 99;
}

.cart-image {
  width: 90px !important;
  height: 90px !important;
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

.quantity-input {
  border: 1px solid #e5e5e5;
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

  .quantity-input input {
    height: 34px;
    font-size: 14px;
  }
}

.icon-wrap {
  z-index: 9;
  cursor: pointer;
}

.empty-cart-image {
  max-width: 100%;
}

.cart-info {
  font-size: 12px;
  font-weight: 500;
  color: #666666;
}

.doc-btn {
  line-height: 1rem;
}
</style>
