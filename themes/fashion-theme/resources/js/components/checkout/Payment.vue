<template>
  <div class="shadow-card mb-30 pb-1">
    <div class="row" v-if="!loading">
      <div class="col-12">
        <h3 class="checkout-title">{{ $t("Payment") }}</h3>
      </div>

      <!--Payment methods-->
      <div class="col-12">
        <label class="font-weight-bold fz-12">{{ $t("Select Payment method") }}</label>
        <div class="payment-gateway-wrapper">
          <ul class="flex-wrap">
            <li :class="selected_payment_method != null && selected_payment_method.id == payment.id ? 'active' : ''"
              v-for="(payment, index) in paymentMethods" :key="index">
              <input :id="'payment-method-' + payment.id" type="radio" :value="payment" class="payment-method d-none"
                name="payment-method" v-model="selected_payment_method" />
              <label :for="'payment-method-' + payment.id">
                <span class="label-title" v-if="payment.logo">
                  <img :src="payment.logo" :alt="payment.name" />
                </span>
                <span class="label-title" v-else>
                  {{ payment.name }}
                </span>
              </label>
            </li>
          </ul>
        </div>

      </div>
      <!--End Payment methods-->
      <!--Selected payment instruction-->
      <div class="col-12 mb-3" v-if="selected_payment_method != null">
        <p v-html="selected_payment_method.instruction"></p>
      </div>
      <!--End Selected payment instruction-->

      <!--Bank payment-->
      <div class="col-12 mb-3" v-if="
        selected_payment_method != null &&
        selected_payment_method.id + '' == '9'
      ">
        <h5>{{ $t("Bank Payment") }}</h5>
        <div class="row">
          <div class="form-group mb-2 col-lg-6">
            <label class="fz-12 font-weight-bold">{{ $t("Account Name") }}</label>
            <input class="theme-input-style" type="text" v-model="bankDetails.accountName"
              v-bind:placeholder="$t('Account Name')" />
            <div v-if="validationErrors[`account_name`]">
              <p v-for="(msg, i) in validationErrors[`account_name`]" :key="i" class="d-block invalid-feedback">
                {{ msg }}
              </p>
            </div>
          </div>
          <div class="form-group mb-2 col-lg-6">
            <label class="fz-12 font-weight-bold">{{ $t("Account Number") }} </label>
            <input class="theme-input-style" type="text" v-model="bankDetails.accountNumber"
              v-bind:placeholder="$t('Account Number')" />
            <div v-if="validationErrors[`account_number`]">
              <p v-for="(msg, i) in validationErrors[`account_number`]" :key="i" class="d-block invalid-feedback">
                {{ msg }}
              </p>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6 form-group mb-2">
            <label class="fz-12 font-weight-bold">{{ $t("Bank Name") }}</label>
            <input class="theme-input-style" type="text" v-model="bankDetails.bankName"
              v-bind:placeholder="$t('Bank Name')" />
            <div v-if="validationErrors[`bank_name`]">
              <p v-for="(msg, i) in validationErrors[`bank_name`]" :key="i" class="d-block invalid-feedback">
                {{ msg }}
              </p>
            </div>
          </div>
          <div class="col-lg-6 form-group mb-2">
            <label class="fz-12 font-weight-bold">{{ $t("Branch Name") }}</label>
            <input class="theme-input-style" type="text" v-model="bankDetails.branchName"
              v-bind:placeholder="$t('Branch Name')" />
            <div v-if="validationErrors[`branch_name`]">
              <p v-for="(msg, i) in validationErrors[`branch_name`]" :key="i" class="d-block invalid-feedback">
                {{ msg }}
              </p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 form-group mb-2">
            <label class="fz-12 font-weight-bold">{{ $t("Transaction Number") }}</label>
            <input class="theme-input-style" type="text" v-model="bankDetails.transactionNumber"
              v-bind:placeholder="$t('Transaction Number')" />
            <div v-if="validationErrors[`transaction_number`]">
              <p v-for="(msg, i) in validationErrors[`transaction_number`]" :key="i" class="d-block invalid-feedback">
                {{ msg }}
              </p>
            </div>
          </div>
          <div class="col-lg-6 form-group mb-2">
            <label class="fz-12 font-weight-bold">{{ $t("Attach Receipt") }}</label>
            <input type="file" name="receipt" id="receipt"
              class="align-content-center form-control p-0 text-center theme-input-style" @change="handleFileUpload">
            <div v-if="validationErrors[`receipt`]">
              <p v-for="(msg, i) in validationErrors[`receipt`]" :key="i" class="d-block invalid-feedback">
                {{ msg }}
              </p>
            </div>
          </div>
        </div>


      </div>
      <!--End Bank payment-->
      <div class="col-lg-12 mb-30" v-if="validationErrors[`products`]">
        <p class="alert alert-danger" v-for="(msg, i) in validationErrors[`products`]">
          {{ msg }}
        </p>
      </div>

      <!--Order Note-->
      <div class="col-lg-12" v-if="config.enable_order_note_in_checkout == enums.status.ACTIVE">
        <div class="form-group mb-20">
          <label class="font-weight-bold fz-12 mb-2">
            {{ $t("Order Note") }}
          </label>
          <textarea class="theme-input-style" v-model="additional_order_note"
            v-bind:placeholder="$t('Write Additional Order Note')">
          </textarea>
        </div>
      </div>
      <!--End Order Note-->


      <!--Wallet area-->
      <div class="col-12 my-3" v-if="
        config.enable_wallet_in_checkout == enums.status.ACTIVE &&
        isCustomerLogin &&
        config.is_active_wallet == enums.status.ACTIVE &&
        wallet_available_balance >= totalPayableAmount
      ">
        <div class="wallet-wrapper bg-body-tertiary py-20 px-3 text-center">
          <p class="mb-0">{{ $t("OR") }}</p>
          <p>
            {{ $t("Your Wallet Balance") }}
            <strong><the-currency :amount="wallet_available_balance"></the-currency></strong>
          </p>
          <button type="submit" class="btn btn_fill m-w-100 justify-content-center" @click.prevent="payWithWallet">
            {{ $t("Pay with wallet") }}
          </button>
        </div>
      </div>
      <!--End Wallet Area-->
      <div class="col-lg-12 my-3">
        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="terms_and_condition" :checked="terms_and_condition"
              v-on:change="() => { terms_and_condition = !terms_and_condition }">
            <label class="form-check-label" for="terms_and_condition">
              {{ $t("I have read and agree to the") }}
              <router-link :to="`/page/${config.customer_term_condition_page_slug}`" target="_blank" class="c1">
                {{ $t("Terms and Conditions") }}
              </router-link>,

              <router-link :to="`/page/${config.privacy_policy_page_slug}`" target="_blank" class="c1">
                {{ $t("Privacy Policy") }}
              </router-link>
              &
              <router-link :to="`/page/${config.return_policy_page_slug}`" target="_blank" class="c1">
                {{ $t("Return Policy") }}
              </router-link>
            </label>
          </div>
        </div>
      </div>
      <!--Action Area-->
      <div class="col-12 mt-15">
        <div class="d-flex flex-wrap justify-content-between">
          <router-link to="/cart" class="btn btn_border mb-20 m-w-100 justify-content-center">
            <span class="material-icons me-2"> arrow_back </span>
            {{ $t("Back to Cart") }}
          </router-link>
          <button type="submit" class="btn btn_fill mb-20 m-w-100 justify-content-center" :disabled="orderCreating"
            @click.prevent="
              () => {
                pay_wallet = false;
                createOrder();
              }
            ">
            <span v-if="orderCreating">
              <CSpinner component="span" size="sm" aria-hidden="true" />
              {{ $t("Please wait") }}
            </span>
            <span v-else>
              {{ $t("Place Order") }}
            </span>
          </button>
        </div>
      </div>
      <!--End action area-->
    </div>
    <div v-if="loading">
      <skeleton class="col-12 mb-20 single-package border-bottom" height="70px"></skeleton>
      <skeleton class="col-12 mb-20 mt-2 single-package border-bottom" height="150px"></skeleton>
      <skeleton class="col-12 mb-2 mt-2 single-package border-bottom" height="80px"></skeleton>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import { mapState } from "vuex";
import { CSpinner } from "@coreui/vue";
export default {
  name: "Payment",
  emits: ["get-errors"],
  components: {
    CSpinner,
  },
  props: {
    config: {
      type: Object,
      required: true,
    },
    enums: {
      type: Object,
      required: true,
    },
    productPackages: {
      type: Array,
      required: true,
    },
    isCustomerLogin: {
      type: Boolean,
      required: false,
      default: false,
    },
    totalPayableAmount: {
      type: Number,
      required: false,
      default: 0,
    },
  },
  data() {
    return {
      loading: true,
      paymentMethods: [],
      additional_order_note: "",
      selected_payment_method: null,
      wallet_available_balance: 0,
      pay_wallet: false,
      orderCreating: false,
      bank_receipt: [],
      errors: {},
      bankDetails: {
        bankName: "",
        branchName: "",
        accountNumber: "",
        accountName: "",
        transactionNumber: "",
      },
      validationErrors: [],
      terms_and_condition: false,
    };
  },
  computed: mapState({
    customerToken: (state) => state.customerToken,
    isActiveHomeDelivery: (state) => state.isActiveHomeDelivery,
    pickupPoint: (state) => state.pickupPoint,
    shippingDetails: (state) => state.shippingDetails,
    billingDetails: (state) => state.billingDetails,
    guestCustomerInfo: (state) => state.guestCustomerInfo,
    isActiveCreateNewAccount: (state) => state.isActiveCreateNewAccount,
    billToDifferentAddress: (state) => state.billToDifferentAddress ?? false,
    guestCustomerInfo: (state) => state.guestCustomerInfo,
  }),
  mounted() {
    if (this.isCustomerLogin) {
      this.getCustomerWalletSummary();
    }
  },
  methods: {
    /**
     * Get active Payment methods
     *
     */
    getPaymentMethods() {
      axios
        .post("/api/v1/ecommerce-core/active-payment-methods", {
          city: this.isActiveHomeDelivery && this.shippingDetails && this.shippingDetails.city ? this.shippingDetails.city.id : "",
          pickup_point: !this.isActiveHomeDelivery && this.pickupPoint != null ? this.pickupPoint.id : "",
          products: JSON.stringify(this.$store.state.checkoutItems),
        })
        .then((response) => {
          if (response.data.success) {
            this.paymentMethods = response.data.data;
          } else {
            this.paymentMethods = [];
          }
          this.loading = false;
        })
        .catch((error) => {
          this.this.paymentMethods = [];
          this.loading = false;
        });
    },
    /**
     * Get customer wallet transactions
     */
    getCustomerWalletSummary() {
      axios
        .post(
          "/api/wallet/v1/customer-wallet-summary",
          {
            perPage: null,
          },
          {
            headers: {
              Authorization: `Bearer ${this.customerToken}`,
            },
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.wallet_available_balance =
              response.data.summary.total_available;
          } else {
            this.wallet_available_balance = 0;
          }
        })
        .catch((error) => {
          this.wallet_available_balance = 0;
        });
    },
    /**
     * Check out with wallet
     */
    payWithWallet() {
      if (!this.terms_and_condition) {
        this.$toast.error(this.$t("Please accept terms and conditions"));
        return null;
      }
      this.pay_wallet = true;
      this.createOrder();
    },

    handleFileUpload(event) {
      this.bank_receipt = event.target.files[0];
    },
    /**
     * Create new order
     */
    createOrder() {
      if (!this.terms_and_condition) {
        this.$toast.error(this.$t("Please accept terms and conditions"));
        return null;
      }

      this.orderCreating = true;
      this.validationErrors = [];
      this.$emit("get-errors", this.validationErrors);

      if (this.selected_payment_method == null && !this.pay_wallet) {
        this.$toast.error(this.$t("Please select a payment option"));
        this.orderCreating = false;
        return null;
      }

      if (this.isCustomerLogin) {
        this.customerCheckout();
      } else if (
        this.config.enable_guest_checkout == this.enums.status.ACTIVE
      ) {
        this.guestCheckout();
      } else {
        this.$router.push("/login");
      }
    },

    /**
     * Customer checkout
     */
    customerCheckout() {
      let formData = new FormData();
      formData.append("coupon_discounts", JSON.stringify(this.$store.state.couponDiscount));

      formData.append("payment_id", this.selected_payment_method ? this.selected_payment_method.id : '');
      formData.append("wallet_payment",
        this.pay_wallet ? this.enums.status.ACTIVE : this.enums.status.IN_ACTIVE
      );

      formData.append("note", this.additional_order_note);

      //pickup point delivery
      if (!this.isActiveHomeDelivery) {
        if (this.pickupPoint == null) {
          this.$toast.error(this.$t("Please select a pickup point"));
          this.orderCreating = false;
          return null;
        }
        formData.append("pickup_point", this.pickupPoint != null ? this.pickupPoint.id : '');

        if (this.config.enable_billing_address == this.enums.status.ACTIVE) {
          let billingAddress = this.getAddressData(this.$store.state.billingDetails);
          if (billingAddress == null) {
            this.$toast.error(this.$t("Please select a billing address"));
            this.orderCreating = false;
            return null;
          }
          formData.append("billing_address", this.billingDetails.id);
        }

      }

      //Home Delivery
      if (this.isActiveHomeDelivery) {
        if (this.shippingDetails == null) {
          this.$toast.error(this.$t("Please select a shipping address"));
          this.orderCreating = false;
          return null;
        }
        //Shipping address
        formData.append("shipping_address", this.shippingDetails.id);

        //Billing address
        if (this.config.enable_billing_address == this.enums.status.ACTIVE) {

          //Same as shipping
          if (!this.billToDifferentAddress) {
            formData.append("billing_address", this.shippingDetails.id);
          }

          //Bill to Different
          if (this.billToDifferentAddress) {
            if (this.billingDetails == null) {
              this.$toast.error(this.$t("Please select a billing address"));
              this.orderCreating = false;
              return null;
            }
            formData.append("billing_address", this.billingDetails.id);
          }
        }
      }

      //Bank Checkout
      if (
        this.selected_payment_method != null &&
        this.selected_payment_method.id + "" == "9"
      ) {
        formData.append("bank_name", this.bankDetails.bankName);
        formData.append("branch_name", this.bankDetails.branchName);
        formData.append("account_number", this.bankDetails.accountNumber);
        formData.append("account_name", this.bankDetails.accountName);
        formData.append("transaction_number", this.bankDetails.transactionNumber);
        formData.append("receipt", this.bank_receipt);
      }

      formData.append("products", JSON.stringify(this.productPackages));
      formData.append("origin", 'web');

      axios
        .post("/api/v1/ecommerce-core/customer/order/create", formData, {
          headers: {
            Authorization: `Bearer ${this.customerToken}`,
          },
        })
        .then((response) => {
          if (response.data.success) {
            if (response.data.redirect_url != null) {
              this.$store.dispatch("flushCartData").then(() => {
                window.location.href = response.data.redirect_url;
              });
            } else {
              this.orderCreating = false;
            }
          } else {
            this.orderCreating = false;
            this.$toast.error("Order create failed");
          }
        })
        .catch((error) => {
          this.orderCreating = false;
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
            this.$emit("get-errors", this.validationErrors);
          } else {
            this.$toast.error("Order create failed");
          }
        });
    },
    /**
     * Guest checkout
     */
    guestCheckout() {
      let formData = new FormData();
      formData.append("coupon_discounts", JSON.stringify(this.$store.state.couponDiscount));

      if (this.isActiveCreateNewAccount) {
        formData.append("password", this.guestCustomerInfo.password ?? "");
        formData.append("password_confirmation", this.guestCustomerInfo.confirm_password ?? "");
        formData.append("create_new_account", this.$store.state.isActiveCreateNewAccount);
      }

      formData.append("payment_id", this.selected_payment_method.id);
      formData.append("note", this.additional_order_note);

      //pickup point delivery
      if (!this.isActiveHomeDelivery) {

        formData.append("customer_name", this.guestCustomerInfo.name);
        formData.append("customer_email", this.guestCustomerInfo.email);


        if (this.pickupPoint == null) {
          this.$toast.error(this.$t("Please select a pickup point"));
          this.orderCreating = false;
          return null;
        }
        formData.append("pickup_point", this.pickupPoint != null ? this.pickupPoint.id : null);

        //Billing address
        if (this.config.enable_billing_address == this.enums.status.ACTIVE) {
          let billingAddress = this.getAddressData(this.$store.state.billingDetails);
          if (billingAddress == null) {
            this.$toast.error(this.$t("Please fill billing address"));
            this.orderCreating = false;
            return null;
          }
          formData.append("billing_address", JSON.stringify(billingAddress));
        }
      }
      //Home Delivery
      if (this.isActiveHomeDelivery) {
        let shippingAddress = this.getAddressData(this.shippingDetails);

        formData.append("shipping_address", JSON.stringify(shippingAddress));

        //Billing address
        if (this.config.enable_billing_address == this.enums.status.ACTIVE) {
          //Shipping address as billing address
          if (!this.billToDifferentAddress) {
            formData.append("billing_address", JSON.stringify(shippingAddress));
          }

          // Billing address as billing address
          if (this.billToDifferentAddress) {
            let billingAddress = this.getAddressData(this.$store.state.billingDetails);
            formData.append("billing_address", JSON.stringify(billingAddress));
          }
        }
      }

      formData.append("products", JSON.stringify(this.productPackages));
      formData.append("origin", 'web');
      axios
        .post("/api/v1/ecommerce-core/guest/checkout", formData)
        .then((response) => {
          if (response.data.success) {
            if (response.data.redirect_url != null) {
              this.$store.dispatch("flushCartData").then(() => {
                window.location.href = response.data.redirect_url;
              });
            }
          } else {
            this.$toast.error("Order create failed");
          }
          this.orderCreating = false;
        })
        .catch((error) => {
          this.orderCreating = false;
          if (error.response.status == 422) {
            this.validationErrors = error.response.data.errors;
            this.$emit("get-errors", this.validationErrors);
          } else {
            this.$toast.error("Order create failed");
          }
        });
    },

    /**
     * Set address data
     *
     */
    getAddressData(address) {

      let modifiedAddress = {
        address: address.address,
        name: address.name,
        email: address.email,
        phone: address.phone,
        phone_code: address.phone_code,
        postal_code: address.postal_code,
        country_id: address.country.hasOwnProperty("id")
          ? address.country.id
          : null,
        state_id: address.state.hasOwnProperty("id") ? address.state.id : null,
        city_id: address.city.hasOwnProperty("id") ? address.city.id : null,
      };

      return modifiedAddress;
    },
  },
};
</script>
<style lang="scss" scoped>
.payment-gateway-wrapper ul {
  margin: 0;
  padding: 0;
  list-style: none;
  display: flex;
  align-items: center;
  margin-bottom: 20px;
}

.payment-gateway-wrapper ul li {
  margin: 5px;
}

.payment-gateway-wrapper ul li {
  cursor: pointer;
  box-sizing: border-box;
  height: 50px;
  display: flex;
  align-items: center;
  position: relative;
  overflow: hidden;
  border: 1px solid #dddddd;
  width: 130px;
}

.payment-gateway-wrapper ul li label {
  padding: 6px;
  width: 100%;
  height: auto;
  text-align: center;
  cursor: pointer;
}

@media only screen and (max-width: 375px) {
  .payment-gateway-wrapper ul li {
    width: 145px;
  }
}

@media only screen and (max-width: 320px) {
  .payment-gateway-wrapper ul li {
    width: 118px;
  }
}

.payment-gateway-wrapper ul li:before {
  border: 2px solid var(--mainC);
  position: absolute;
  right: 0;
  top: 0;
  width: 100%;
  height: 100%;
  content: '';
  visibility: hidden;
  opacity: 0;
  transition: all .3s;
}

.payment-gateway-wrapper ul li:after {
  position: absolute;
  right: 0;
  top: 0;
  width: 15px;
  height: 15px;
  background-color: var(--mainC);
  content: "\f00c";
  font-weight: 400;
  color: #fff;
  font-family: "FontAwesome";
  font-size: 10px;
  line-height: 10px;
  text-align: center;
  padding-top: 2px;
  padding-left: 2px;
  visibility: hidden;
  opacity: 0;
  transition: all .3s;
}

.payment-gateway-wrapper ul li.active:after {
  opacity: 1;
  visibility: visible;
}

.payment-gateway-wrapper ul li.active::before {
  opacity: 1;
  visibility: visible;
}
</style>
