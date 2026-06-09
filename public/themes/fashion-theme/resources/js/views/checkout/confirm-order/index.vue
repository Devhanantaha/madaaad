<template>
  <div class="">
    <page-header :items="bItems" />

    <div class="pt-60 pb-60 light-bg">
      <div class="custom-container2">
        <div class="row" v-if="!dataLoading">
          <div class="col-12" v-if="success">
            <div class="shadow-card py-5 text-center mb-30">
              <img src="/public/web-assets/frontend/themes/fashion-theme/assets/img/complete-order.png" class="mb-4"
                alt="Order" />
              <h3>{{ $t("Thank You") }}!</h3>
              <h4>{{ $t("Order ID") }}: {{ orderDetails.order_code }}</h4>


              <div class="row mt-3" v-if="orderDetails.payment_required == enums.status.ACTIVE">
                <div class="col-12 text-center">
                  <p>{{ $t("Payment is incomplete") }}</p>
                  <button class="btn btn-sm" :disabled="payment_processing" @click.prevent="PayNow">
                    <span v-if="payment_processing">
                      <CSpinner component="span" size="sm" aria-hidden="true" />
                      {{ $t("Please wait") }}
                    </span>
                    <span v-else>
                      {{ $t("Pay Now") }}
                    </span>
                  </button>
                </div>
              </div>

            </div>

            <div class="shadow-card mb-30">
              <h3 class="order-summery-title">{{ $t("Order Summery") }}</h3>

              <div class="row mt-4">
                <div class="col-lg-6">
                  <ul class="order-summery-list" v-if="orderDetails.pickup_point == null">
                    <li>
                      <span>{{ $t("Order Code") }}:</span>
                      <span>{{ orderDetails.order_code }}</span>
                    </li>
                    <template v-if="orderDetails.shipping_details">
                      <li>
                        <span>{{ $t("Name") }}:</span>
                        <span>{{ orderDetails.shipping_details.name }}</span>
                      </li>
                      <li>
                        <span>{{ $t("Mobile") }}:</span>
                        <span>{{ orderDetails.shipping_details.phone }}</span>
                      </li>
                      <li>
                        <span>{{ $t("Address") }}:</span>
                        <p>
                          <span v-if="orderDetails.shipping_details.address != null">{{
                            orderDetails.shipping_details.address }},</span>
                          <span v-if="orderDetails.shipping_details.city != null">{{ orderDetails.shipping_details.city
                          }},</span>
                          <span v-if="orderDetails.shipping_details.state != null">{{
                            orderDetails.shipping_details.state }},</span>
                          <span v-if="orderDetails.shipping_details.country">{{ orderDetails.shipping_details.country
                          }}.</span>
                        </p>
                      </li>
                      <li>
                        <span>{{ $t("Postal Code") }}:</span>
                        <span>{{
                          orderDetails.shipping_details.postal_code
                        }}</span>
                      </li>
                    </template>
                  </ul>
                  <ul class="order-summery-list" v-else>
                    <li>
                      <span>{{ $t("Order Code") }}:</span>{{ orderDetails.order_code }}
                    </li>
                    <template v-if="orderDetails.pickup_point">
                      <li>
                        <span>{{ $t("Pickup Point") }}:</span>{{ orderDetails.pickup_point.name }}
                      </li>
                      <li>
                        <span>{{ $t("Mobile") }}:</span>{{ orderDetails.pickup_point.phone }}
                      </li>
                      <li>
                        <span>{{ $t("Address") }}:</span>
                        {{ orderDetails.pickup_point.location }}
                      </li>
                    </template>
                  </ul>
                </div>
                <div class="col-lg-6 mt-2 mt-lg-0">
                  <ul class="order-summery-list">
                    <li>
                      <span>{{ $t("Order Date") }}:</span>{{ orderDetails.order_date }}
                    </li>
                    <li class="text-capitalize">
                      <span>{{ $t("Order Status") }}:</span>{{ orderDetails.delivery_status_label }}
                    </li>
                    <li class="text-capitalize">
                      <span>{{ $t("Total Amount") }}:</span><the-currency
                        :amount="orderDetails.total_payable_amount"></the-currency>
                    </li>
                    <li class="text-capitalize">
                      <span>{{ $t("Payment Status") }}:</span>{{ orderDetails.payment_status_label }}
                    </li>
                    <li class="text-capitalize">
                      <span>{{ $t("Payment method") }}:</span>{{ orderDetails.payment_method }}
                    </li>
                  </ul>
                </div>
              </div>
            </div>

            <div class="shadow-card">
              <h3 class="checkout-title">{{ $t("Order Details") }}:</h3>

              <div class="table-responsive mt-3">
                <CTable class="cart-table">
                  <CTableHead>
                    <CTableRow>
                      <CTableHeaderCell>{{
                        $t("Product Name")
                      }}</CTableHeaderCell>
                      <CTableHeaderCell>{{
                        $t("Price") + "/" + $t("Unit")
                      }}</CTableHeaderCell>
                      <CTableHeaderCell>{{ $t("Quantity") }}</CTableHeaderCell>
                      <CTableHeaderCell>{{ $t("Total") }}</CTableHeaderCell>
                    </CTableRow>
                  </CTableHead>
                  <CTableBody>
                    <CTableRow v-for="product in orderDetails.products.data" :key="product.id">
                      <CTableDataCell class="w-50">
                        <div class="d-flex align-items-center">
                          <router-link :to="`/products/${product.permalink}`">
                            <img :src="product.image" :alt="product.name" class="cart-image mr-10" />
                          </router-link>
                          <div class="item-info">
                            <!--Item Name-->
                            <router-link :title="`${product.name}`" :to="`/products/${product.permalink}`"
                              class="cart-product-name product-name text-capitalize">
                              {{ product.name }}
                            </router-link>
                            <!--End Item Name-->
                            <!--Variant-->
                            <div class="product-variant extra-addons-wrap d-flex flex-wrap"
                              v-if="product.variant != null">
                              <product-variant class="font-weight-medium fz-12" :variant="product.variant"
                                tag="p"></product-variant>
                            </div>
                            <!--End Variant-->
                            <!--Shop-->
                            <div class="extra-addons-wrap d-flex flex-wrap" v-if="product.shop != null">
                              <p class="product-shop fz-12">
                                {{ $t("Sold By") }}
                                <router-link :to="`/shop/${product.shop.shop_slug}`" target="_blank"
                                  class="link-danger">
                                  {{ product.shop.shop_name }}
                                </router-link>
                              </p>
                            </div>
                            <!--End shop-->
                          </div>
                        </div>
                      </CTableDataCell>
                      <CTableDataCell><the-currency :amount="product.unit_price"></the-currency>
                      </CTableDataCell>
                      <CTableDataCell>{{ product.quantity }}</CTableDataCell>
                      <CTableDataCell class="fw-medium"><the-currency
                          :amount="product.unit_price * product.quantity"></the-currency></CTableDataCell>
                    </CTableRow>
                  </CTableBody>
                </CTable>
              </div>
              <div class="order-details">
                <div class="table-responsive">
                  <table class="shop_table w-100">
                    <tbody>
                      <!--Subtotal-->
                      <tr class="order-total">
                        <td>{{ $t("Subtotal") }}</td>
                        <td>
                          <span class="price-amount amount"><bdi><span class="price-currency-symbol"></span>
                              <the-currency :amount="orderDetails.sub_total"></the-currency> </bdi></span>
                        </td>
                      </tr>
                      <!--End Subtotal-->
                      <!--Shipping Cost-->
                      <tr class="font-weight-regular">
                        <td>{{ $t("Shipping Cost") }}</td>
                        <td>
                          <span class="price-amount amount"><bdi><span class="price-currency-symbol">+</span>
                              <the-currency :amount="orderDetails.total_delivery_cost"></the-currency> </bdi></span>
                        </td>
                      </tr>
                      <!--End Shipping Cost-->
                      <!--Tax-->
                      <tr class="font-weight-regular" v-if="orderDetails.total_tax > 0">
                        <td>
                          {{ $t("Tax") }}
                        </td>
                        <td>
                          <span class="price-amount amount">
                            <bdi><span class="price-currency-symbol">+</span>
                              <the-currency :amount="orderDetails.total_tax"></the-currency>
                            </bdi>
                          </span>
                        </td>
                      </tr>
                      <!--End Tax-->
                      <!--Discount-->
                      <tr class="order-savings font-weight-regular" v-if="orderDetails.total_discount > 0">
                        <td>
                          {{ $t("Discount") }}
                        </td>
                        <td>
                          <span class="price-amount amount">
                            <bdi><span class="price-currency-symbol">-</span>
                              <the-currency :amount="orderDetails.total_discount"></the-currency>
                            </bdi>
                          </span>
                        </td>
                      </tr>
                      <!--End Discount-->
                      <tr class="order-total">
                        <td class="c1">{{ $t("Payable Total") }}</td>
                        <td>
                          <span class="price-amount amount c1">
                            <bdi>
                              <span class="price-currency-symbol"></span>
                              <the-currency :amount="orderDetails.total_payable_amount"></the-currency>
                            </bdi>
                          </span>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="mt-3 d-flex flex-wrap justify-content-between">
                <router-link to="/dashboard/orders" class="btn btn_fill mb-1">
                  {{ $t("View Orders") }}
                </router-link>
                <router-link to="/products" class="btn btn_fill mb-1">
                  {{ $t("Shop More") }}
                </router-link>
              </div>
            </div>
          </div>
          <div class="col-12" v-else>
            <div class="shadow-card py-5 text-center mb-30">
              <the-not-found title="Order details not found"> </the-not-found>
            </div>
          </div>
        </div>
        <div v-if="dataLoading">
          <skeleton width="100%" height="300px" class="mb-30"> </skeleton>
          <skeleton width="100%" height="500px"> </skeleton>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import ProductVariant from "@/components/ui/ProductVariant.vue";
import axios from "axios";
import { mapState } from "vuex";
import enums from "@/enums/enums";
import {
  CTable,
  CTableHead,
  CTableBody,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
} from "@coreui/vue";
export default {
  name: "SuccessOrder",
  layout: "main",
  components: {
    PageHeader,
    CTable,
    CTableBody,
    CTableRow,
    CTableDataCell,
    CTableHeaderCell,
    CTableHead,
    ProductVariant,
  },
  data() {
    return {
      pageTitle: this.$t("Confirm Order"),
      order_code: "",
      quantityValue: 1,
      subtotal: 990,
      deliveryCost: 50,
      couponDiscount: 100,
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Confirm Order"),
          active: true,
        },
      ],
      orderDetails: {},
      success: false,
      dataLoading: true,
      payment_processing: false,
      enums: enums,
    };
  },
  computed: mapState({
    customerToken: (state) => state.customerToken,
    isCustomerLogin: (state) => state.isCustomerLogin,
  }),
  mounted() {
    document.title = this.$t("Success Order");
    this.order_code = this.$route.params.id;
    if (this.isCustomerLogin) {
      this.customerOrderDetails();
    } else {
      this.guestCustomerOrderDetails();
    }
    if (this.$route.query.payment_status) {

      if (this.$route.query.payment_status == "SUCCESS") {
        this.$toast.success(this.$t("Payment successfully completed"));
      }

      if (this.$route.query.payment_status == "FAILED") {
        this.$toast.error(this.$t("payment failed"));
      }

      if (this.$route.query.payment_status == "CANCELLED") {
        this.$toast.error(this.$t("Payment cancelled"));
      }

    }
  },
  methods: {
    /**
     * Get successful order details
     *
     */
    customerOrderDetails() {
      axios
        .post(
          "/api/v1/ecommerce-core/customer/order/details",
          {
            order_code: this.order_code,
          },
          {
            headers: {
              Authorization: `Bearer ${this.customerToken}`,
            },
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.orderDetails = response.data.data;
            this.success = true;
          } else {
            this.$toast.error("Order not found");
          }
          this.dataLoading = false;
        })
        .catch((error) => {
          this.$toast.error("Order not found");
          this.dataLoading = false;
        });
    },
    /**
     * Get guest customer order details
     */
    guestCustomerOrderDetails() {
      axios
        .post("/api/v1/ecommerce-core/guest/order/details", {
          order_code: this.order_code,
        })
        .then((response) => {
          if (response.data.success) {
            this.orderDetails = response.data.data;
            this.success = true;
          } else {
            this.$toast.error("Order not found");
          }
          this.dataLoading = false;
        })
        .catch((error) => {
          this.dataLoading = false;
          this.$toast.error("Order not found");
        });
    },
    PayNow() {
      this.payment_processing = true;
      axios
        .post(
          "/api/v1/ecommerce-core/generate-order-payment-url",
          {
            order_id: this.orderDetails.id,
          }
        )
        .then((response) => {
          if (response.data.success) {
            window.location.href = response.data.link;
          } else {
            this.payment_processing = false;
            this.$toast.error(this.$t("Something went wrong"));
          }
        })
        .catch((error) => {
          this.payment_processing = false;
          this.$toast.error(this.$t("Something went wrong"));
        });
    },
  },
};
</script>
