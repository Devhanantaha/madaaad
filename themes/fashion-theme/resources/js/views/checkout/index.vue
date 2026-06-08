<template>
  <div class="">
    <page-header class="pt-3 pb-3" :items="bItems" />
    <div class="checkout pt-60 pb-60">
      <!--Container-->
      <div class="custom-container2" v-if="!dataLoading && tableData.length">
        <form action="#">
          <div class="row">
            <div class="col-lg-7 order-1">

              <shipping :enums="enums" :config="configuration" :customer-address="customerAddress"
                :is-customer-login="isCustomerLogin" :pickup-points="pickupPoints" :errors="errors"
                @shipping-confirmed="handleShippingConfirmed">
              </shipping>

              <billing
                v-if="configuration.enable_billing_address == 1 && configuration.use_shipping_address_as_billing_address != 1"
                :enums="enums" :config="configuration" :customer-address="customerAddress"
                :is-customer-login="isCustomerLogin" :errors="errors">
              </billing>

            </div>
            <div class="col-lg-5 order-2">
              <div class="summary shadow-card mb-4">
                <h3 class="checkout-title">{{ $t("Products") }}</h3>
                <product-summary :enums="enums" :config="configuration" :is-customer-login="isCustomerLogin"
                  :shipping-packages="product_packages" @finalize-product="completedOrderReview" ref="productSummary">
                </product-summary>
                <Order-summary :enums="enums" :config="configuration" @get-total-payable="calculateTotalPayable">
                </Order-summary>
              </div>
            </div>
            <div class="col-lg-7 order-3">
              <payment :enums="enums" :config="configuration" :product-packages="product_packages"
                :is-customer-login="isCustomerLogin" :customer-address="customerAddress"
                :total-payable-amount="totalPayableAmount" @get-errors="handleCheckoutErrors" ref="payment">
              </payment>
            </div>
          </div>
        </form>
      </div>
      <!--End Container-->
      <!--Preloader-->
      <div class="custom-container2" v-if="dataLoading">
        <div class="row mx-0 mb-20 d-none d-lg-block">
          <skeleton class="col-12" height="50px"></skeleton>
        </div>

        <div class="row">
          <div class="col-lg-8">
            <skeleton class="w-100 mb-20" height="150px"></skeleton>
            <skeleton class="w-100 mb-20" height="150px"></skeleton>
            <skeleton class="w-100" height="150px"></skeleton>
          </div>

          <div class="col-lg-4">
            <skeleton class="w-100" height="400px"></skeleton>
          </div>
        </div>
      </div>
      <!--End Preloader-->
    </div>
  </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import Shipping from "@/components/checkout/Shipping";
import Billing from "@/components/checkout/Billing.vue";
import Payment from "@/components/checkout/Payment";
import ProductSummary from "@/components/checkout/ProductSummary";
import OrderSummary from "@/components/checkout/OrderSummary";
import enums from "../../enums/enums";
import axios from "axios";
import { mapState } from "vuex";
export default {
  name: "Checkout",
  components: {
    PageHeader,
    Shipping,
    Billing,
    Payment,
    ProductSummary,
    OrderSummary,
  },
  data() {
    return {
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Checkout"),
          active: true,
        },
      ],
      enums: enums,
      tableData: this.$store.state.checkoutItems,
      totalPayableAmount: 0,
      customerAddress: [],
      pickupPoints: [],
      dataLoading: false,
      product_packages: [],
      errors: [],
    };
  },
  computed: mapState({
    customerToken: (state) => state.customerToken,
    isCustomerLogin: (state) => state.isCustomerLogin,
    configuration: (state) => state.siteSettings,
  }),
  beforeMount() {
    if (this.tableData.length < 1) {
      this.$router.push("/cart");
    }
  },
  mounted() {
    document.title = this.$t("Checkout");
    this.CheckCheckoutConfig();
  },
  methods: {
    handleShippingConfirmed() {
      this.$refs.productSummary.getShippingOptions();
      this.$refs.payment.getPaymentMethods();
    },
    /**
     * Will get checkout configuration
     */
    CheckCheckoutConfig() {
      //Check guest checkout
      if (
        this.configuration.enable_guest_checkout ==
        this.enums.status.IN_ACTIVE &&
        !this.isCustomerLogin
      ) {
        this.$toast.error(this.$t("Please login to complete checkout"));
        this.$router.push("/login");
      }
      //Get customer address
      if (this.isCustomerLogin) {
        this.getCustomerAddress();
      }
      //Get pickup points
      if (
        this.configuration.enable_pickup_point_in_checkout ==
        this.enums.status.ACTIVE
      ) {
        this.getPickupPoints();
      }
    },
    /**
     * Will get customer address
     */
    getCustomerAddress() {
      this.dataLoading = true;
      axios
        .get("/api/v1/ecommerce-core/customer/get-customer-active-address", {
          headers: {
            Authorization: `Bearer ${this.customerToken}`,
          },
        })
        .then((response) => {
          if (response.data.success) {
            this.customerAddress = response.data.data;
            let defaultShipping = this.customerAddress.find(
              (address) => address.default_shipping === this.enums.status.ACTIVE
            ) || null;

            this.$store.dispatch("storeShippingDetails", defaultShipping);

          } else {
            this.customerAddress = [];
          }
          this.dataLoading = false;
        })
        .catch((error) => {
          this.dataLoading = false;
          this.customerAddress = [];
        });
    },
    /**
     * Will get active pickup points
     */
    getPickupPoints() {
      axios
        .post("/api/v1/pickup-points/active-list")
        .then((response) => {
          if (response.data.success) {
            this.pickupPoints = response.data.data;
          } else {
            this.pickupPoints = [];
          }
        })
        .catch((error) => {
          this.this.pickupPoints = [];
        });
    },
    /**
     * Go  to next step from order review step
     *
     * @param {*} e
     */
    completedOrderReview(e) {
      this.product_packages = e;
    },

    handleCheckoutErrors(e) {
      this.errors = e;
      let firstInvalidFeedback = document.querySelector(".invalid-feedback:not(.d-none)");
      if (firstInvalidFeedback) {
        firstInvalidFeedback.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    },
    /**
     * Will calculate total payable order amount
     */
    calculateTotalPayable(amount) {
      this.totalPayableAmount = amount;
    },
  },
};
</script>
