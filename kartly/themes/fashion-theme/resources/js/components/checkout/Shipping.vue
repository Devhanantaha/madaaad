<template>
  <div class="shadow-card mb-30">
    <h3 class="checkout-title">{{ $t("Shipping information") }}</h3>
    <div class="row">
      <div class="col-12">
        <ul class="list-unstyled border rounded-1">
          <li class="single-form-selector">
            <div class="custom-radio-btn shipping-option border-bottom font-weight-medium"
              :class="{ active: isActiveHomeDelivery }">
              <label class="d-flex align-items-center">
                <input type="radio" value="Pickup" class="shipping-method delivery-type-pickup" name="delivery-options"
                  :checked="isActiveHomeDelivery" v-on:change="activeHomeDelivery()" />
                <span class="label-title black ml-5">{{ $t("Home Delivery") }}</span>
              </label>
            </div>
            <div class="item-body border-bottom p-2" v-if="isActiveHomeDelivery">
              <!--Home Delivery-->
              <!--Login user Checkout-->
              <div v-if="isCustomerLogin">
                <!--Shipping Address-->
                <div class="row" v-if="isActiveHomeDelivery">
                  <div class="col-12">
                    <!--Address List-->
                    <div class="address-list row" v-if="customerAddress.length > 0">
                      <div class="col-lg-6 my-2" v-for="address in customerAddress" :key="address.name">
                        <span class="custom-radio-btn" ref="addressRadio" :class="{
                          active:
                            customerShippingInfo != null &&
                            address.id == customerShippingInfo.id,
                        }">
                          <label class="radio-label">
                            <input name="customerShippingAddress" type="radio" :value="address"
                              v-model="customerShippingInfo" @change.prevent="checkedAddress" :checked="customerShippingInfo != null &&
                                address.id == customerShippingInfo.id
                                " />
                            <address-card :address="address"></address-card>
                          </label>
                        </span>
                      </div>
                    </div>
                    <!--End Address List-->
                    <!--New Address Store list-->
                    <div class="address-list row">
                      <div class="col-lg-12 my-2">
                        <router-link to="/dashboard/address"
                          class="align-items-center border-dashed d-flex fz-14 gap-2 justify-content-center p-3 w-100">
                          <span class="material-icons">add</span> {{ $t("Add new address") }}
                        </router-link>
                      </div>
                    </div>
                    <!--End New Address Store list-->
                  </div>
                </div>
                <!--End Shipping Address-->
              </div>
              <!--End Login user Checkout-->

              <!--Guest Checkout -->
              <div v-if="!isCustomerLogin">
                <!--Guest Shipping Address-->
                <div class="row guest-shipping-address" v-if="isActiveHomeDelivery">

                  <div v-if="config.enable_name_in_checkout == enums.status.ACTIVE" class="form-group mb-20 col-lg-6">
                    <input type="text" v-bind:placeholder="$t('Your Name')" v-model="guestShippingInfo.name"
                      class="theme-input-style bg-white" />

                    <div v-if="errors[`shipping_address.name`]">
                      <p v-for="(msg, i) in errors[`shipping_address.name`]" :key="i" class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>

                  <div v-if="config.enable_email_in_checkout == enums.status.ACTIVE" class="form-group mb-20 col-lg-6">
                    <input type="email" v-bind:placeholder="$t('Email Address')" v-model="guestShippingInfo.email"
                      class="theme-input-style bg-white" />
                    <div v-if="errors[`shipping_address.email`]">
                      <p v-for="(msg, i) in errors[`shipping_address.email`]" :key="i" class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>

                  <div v-if="config.enable_phone_in_checkout == enums.status.ACTIVE" class="form-group mb-20 col-lg-6">
                    <input type="tel" v-bind:placeholder="$t('Phone Number')" v-model="guestShippingInfo.phone"
                      class="theme-input-style bg-white" />
                    <div v-if="errors[`shipping_address.phone`]">
                      <p v-for="(msg, i) in errors[`shipping_address.phone`]" :key="i" class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>

                  <div v-if="config.enable_address_in_checkout == enums.status.ACTIVE"
                    class="form-group mb-20 col-lg-6">
                    <input type="text" class="theme-input-style bg-white" v-bind:placeholder="$t('Address')"
                      v-model="guestShippingInfo.address" />
                    <div v-if="errors[`shipping_address.address`]">
                      <p v-for="(msg, i) in errors[`shipping_address.address`]" :key="i"
                        class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>

                  <div v-if="config.enable_post_code_in_checkout == enums.status.ACTIVE" :class="config.enable_country_state_city_in_checkout == enums.status.ACTIVE
                    ? 'form-group mb-20 col-lg-12'
                    : 'form-group mb-20 col-lg-6'
                    ">
                    <input type="text" class="theme-input-style bg-white" v-bind:placeholder="$t('Postal Code')"
                      v-model="guestShippingInfo.postal_code" />
                    <div v-if="errors[`shipping_address.postal_code`]">
                      <p v-for="(msg, i) in errors[`shipping_address.postal_code`]" :key="i"
                        class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>

                  <div v-if="config.enable_country_state_city_in_checkout == enums.status.ACTIVE && enableCountry"
                    class="form-group mb-20 col-lg-6">
                    <v-select :options="countries" v-model="guestShippingInfo.country" label="name"
                      :clearable="false"></v-select>
                    <div v-if="errors[`shipping_address.country_id`]">
                      <p v-for="(msg, i) in errors[`shipping_address.country_id`]" :key="i"
                        class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>

                  <div v-if="config.enable_country_state_city_in_checkout == enums.status.ACTIVE"
                    class="form-group mb-20 col-lg-6">
                    <v-select :options="guestShippingInfo.states_options" v-model="guestShippingInfo.state" label="name"
                      :clearable="false"></v-select>
                    <div v-if="errors[`shipping_address.state_id`]">
                      <p v-for="(msg, i) in errors[`shipping_address.state_id`]" :key="i"
                        class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>

                  <div v-if="config.enable_country_state_city_in_checkout == enums.status.ACTIVE"
                    class="form-group mb-20 col-lg-6">
                    <v-select :options="guestShippingInfo.cities_options" v-model="guestShippingInfo.city" label="name"
                      :clearable="false"></v-select>
                    <div v-if="errors[`shipping_address.city_id`]">
                      <p v-for="(msg, i) in errors[`shipping_address.city_id`]" :key="i"
                        class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>
                </div>
                <!--End Guest Shipping Address-->
                <!--Create New Account-->
                <div class="row">
                  <div class="form-group mb-20 col-lg-6" v-if="
                    config.create_account_in_guest_checkout == enums.status.ACTIVE
                  ">
                    <label class="d-flex gap-1 radio-label">
                      <input name="createNewAccount" type="checkbox"
                        @change="isActiveCreateNewAccount = !isActiveCreateNewAccount"
                        :checked="isActiveCreateNewAccount" />
                      <span class="radio-text"> {{ $t("Create an Account") }} ? </span>
                    </label>
                  </div>
                  <div class="row m-0 p-0" v-if="
                    isActiveCreateNewAccount &&
                    config.create_account_in_guest_checkout == enums.status.ACTIVE
                  ">
                    <div class="form-group mb-20 col-lg-6">
                      <input type="password" placeholder="Password" class="theme-input-style bg-white"
                        v-model="guestCustomerInfo.password" />
                      <div v-if="errors[`password`]">
                        <p v-for="(msg, i) in errors[`password`]" :key="i" class="d-block invalid-feedback">
                          {{ msg }}
                        </p>
                      </div>
                    </div>
                    <div class="form-group mb-20 col-lg-6">
                      <input type="password" placeholder="Confirm Password" v-model="guestCustomerInfo.confirm_password"
                        class="theme-input-style bg-white" />
                    </div>
                  </div>
                </div>
                <!--End Create New Account-->

              </div>
              <!--End Guest Checkout -->
              <!--End Home delivery-->
            </div>
          </li>
          <li class="single-form-selector" v-if="
            config.enable_pickup_point_in_checkout == enums.status.ACTIVE &&
            config.is_active_pickuppoint == enums.status.ACTIVE
          ">
            <div class="custom-radio-btn shipping-option font-weight-medium"
              :class="{ 'active border-bottom': !isActiveHomeDelivery }">
              <label class="d-flex align-items-center">
                <input type="radio" value="Delivery" class="shipping-method delivery-type-delivery"
                  name="delivery-options" :checked="!isActiveHomeDelivery" v-on:change="activePickupPoint()" />
                <span class="label-title black ml-5">{{ $t("Local Pickup") }}</span>
              </label>
            </div>
            <!--Pickup point Selector-->
            <div class="item-body p-2" v-if="!isActiveHomeDelivery">


              <div class="row" v-if="!isCustomerLogin">
                <h5>{{ $t("Personal Information") }}</h5>
                <div class="form-group mb-20 col-lg-6">
                  <input type="text" v-bind:placeholder="$t('Your Name')" class="theme-input-style bg-white"
                    v-model="guestCustomerInfo.name" />
                  <div v-if="errors[`customer_name`]">
                    <p v-for="(msg, i) in errors[`customer_name`]" :key="i" class="d-block invalid-feedback">
                      {{ msg }}
                    </p>
                  </div>

                </div>
                <div class="form-group mb-20 col-lg-6">
                  <input type="email" v-bind:placeholder="$t('Email')" v-model="guestCustomerInfo.email"
                    class="theme-input-style bg-white" />
                  <div v-if="errors[`customer_email`]">
                    <p v-for="(msg, i) in errors[`customer_email`]" :key="i" class="d-block invalid-feedback">
                      {{ msg }}
                    </p>
                  </div>
                </div>
                <div class="form-group mb-20 col-lg-6"
                  v-if="config.create_account_in_guest_checkout == enums.status.ACTIVE">
                  <label class="d-flex gap-1 radio-label">
                    <input name="createNewAccount" type="checkbox"
                      @change="isActiveCreateNewAccount = !isActiveCreateNewAccount"
                      :checked="isActiveCreateNewAccount" />
                    <span class="radio-text"> {{ $t("Create an Account") }} ? </span>
                  </label>
                </div>
                <div class="row m-0 p-0" v-if="
                  isActiveCreateNewAccount &&
                  config.create_account_in_guest_checkout == enums.status.ACTIVE
                ">
                  <div class="form-group mb-20 col-lg-6">
                    <input type="password" placeholder="Password" class="theme-input-style bg-white"
                      v-model="guestCustomerInfo.password" />
                    <div v-if="errors[`password`]">
                      <p v-for="(msg, i) in errors[`password`]" :key="i" class="d-block invalid-feedback">
                        {{ msg }}
                      </p>
                    </div>
                  </div>
                  <div class="form-group mb-20 col-lg-6">
                    <input type="password" placeholder="Confirm Password" v-model="guestCustomerInfo.confirm_password"
                      class="theme-input-style bg-white" />

                  </div>
                </div>
              </div>

              <div class="form-group col-lg-12">
                <label class="font-weight-bold fz-12 mb-10">{{ $t("Select Pickup Point") }}</label>
                <v-select :options="pickupPoints" :clearable="false" :searchable="true" v-model="selectedPickupPoints"
                  label="name" class="pickup-point-select">
                  <template v-slot:selected-option="option">
                    <div class="row">
                      <p class="col-12 font-weight-bold mb-0">{{ option.name }}</p>
                      <p v-if="option.location" class="mb-0">
                        <span>{{ option.location }}</span>
                      </p>
                      <p v-if="option.phone" class="mb-0">
                        <span>{{ option.phone }}</span>
                      </p>
                      <p class="mb-0" v-if="option.zone_name != null">
                        <span>{{ option.zone_name }}</span>
                      </p>
                    </div>
                  </template>
                  <template v-slot:option="option">
                    <p class="col-12 font-weight-bold mb-0">{{ option.name }}</p>
                    <p class="mb-0">
                      <span>{{ option.location }}</span>
                    </p>
                    <p class="mb-0">
                      <span>{{ option.phone }}</span>
                    </p>
                    <p class="mb-0" v-if="option.zone_name != null">
                      <span>{{ option.zone_name }}</span>
                    </p>
                  </template>
                </v-select>
                <div v-if="errors[`pickup_point`]">
                  <p v-for="(msg, i) in errors[`pickup_point`]" :key="i" class="d-block invalid-feedback">
                    {{ msg }}
                  </p>
                </div>
              </div>
            </div>
            <!--End Pickup Point Selector-->
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>
<script>
import axios from "axios";
import AddressCard from "@/components/checkout/AddressCard.vue";
export default {
  name: "Shipping",
  components: { AddressCard },
  props: {
    config: {
      type: Object,
      required: true,
    },
    enums: {
      type: Object,
      required: true,
    },
    customerAddress: {
      type: Array,
      required: false,
    },
    pickupPoints: {
      type: Array,
      required: false,
    },
    isCustomerLogin: {
      type: Boolean,
      required: false,
      default: false,
    },
    errors: {
      type: Array,
      required: false,
    },
  },
  data() {
    return {
      loading: true,
      isActiveHomeDelivery: true,
      isActiveCreateNewAccount: false,
      deliveryNotAvailable: false,
      selectedShippingOption: {},
      selectedPickupPoints: "",
      enableCountry: false,
      countries: [],
      guestCustomerInfo: {},
      guestShippingInfo: {},
      customerShippingInfo:
        this.customerAddress.find(
          (address) => address.default_shipping === this.enums.status.ACTIVE
        ) || null,
    };
  },
  mounted() {
    this.$store.dispatch("setFinalShippingCost", 0);
    if (this.customerShippingInfo != null && this.isCustomerLogin) {
      this.$store.dispatch("storeShippingDetails", this.customerShippingInfo);
    }
    if (!this.isCustomerLogin) {
      this.$store.dispatch("storeShippingDetails", null);
      this.getCounties();
    }
    this.getPreviousData();
  },
  watch: {
    "guestShippingInfo.country"() {
      this.guestShippingInfo.state = this.$t("Select State");
      this.guestShippingInfo.city = this.$t("Select City");
      this.guestShippingInfo.cities_options = [];
      this.getStates();
    },
    "guestShippingInfo.state"() {
      this.guestShippingInfo.city = this.$t("Select City");
      this.getCities();
    },
    "selectedPickupPoints"() {
      this.$store.dispatch("storePickupPoint", this.selectedPickupPoints);
    },
    guestShippingInfo: {
      handler(val, oldVal) {
        if (!this.isCustomerLogin) {
          this.$store.dispatch("storeShippingDetails", this.guestShippingInfo);
          this.$emit("shipping-confirmed");
        }
      },
      deep: true,
    },

    guestCustomerInfo: {
      handler(val, oldVal) {
        if (!this.isCustomerLogin) {
          this.$store.dispatch("storeGuestCustomerDetails", this.guestCustomerInfo);
        }
      },
      deep: true,

    },
    isActiveCreateNewAccount() {
      this.$store.dispatch("storeIsActiveCreateNewAccount", this.isActiveCreateNewAccount);
    }

  },
  methods: {

    activeHomeDelivery() {
      this.isActiveHomeDelivery = true;
      this.$store.dispatch("storeHomeDeliveryCheckout", this.isActiveHomeDelivery);
      this.$emit("shipping-confirmed");
    },
    activePickupPoint() {
      this.isActiveHomeDelivery = false;
      this.$store.dispatch("storeHomeDeliveryCheckout", this.isActiveHomeDelivery);
      this.$emit("shipping-confirmed");
    },

    /**
     * Will get previously save data
     */
    getPreviousData() {
      let emptyShippingData = {
        id: "",
        name: "",
        email: "",
        phone_code:
          localStorage.getItem("country") != null
            ? JSON.parse(localStorage.getItem("country")).phone_code
            : "",
        phone: "",
        postal_code: "",
        address: "",
        country: this.$t("Select Country"),
      };
      let emptyBillingData = {
        id: "",
        name: "",
        email: "",
        phone_code:
          localStorage.getItem("country") != null
            ? JSON.parse(localStorage.getItem("country")).phone_code
            : "",
        phone: "",
        postal_code: "",
        address: "",
        country: this.$t("Select Country"),
      };
      this.isActiveHomeDelivery = this.$store.state.isActiveHomeDelivery;

      if (this.isCustomerLogin) {
        //Shipping address
        if (this.$store.state.shippingDetails != null) {
          this.customerShippingInfo = this.$store.state.shippingDetails.name
            ? this.$store.state.shippingDetails
            : emptyShippingData;
        }
      } else {

        this.isActiveCreateNewAccount =
          this.$store.state.isActiveCreateNewAccount;

        this.guestBillingInfo =
          this.$store.state.billingDetails != null
            ? this.$store.state.billingDetails
            : emptyBillingData;

        this.guestShippingInfo =
          this.$store.state.shippingDetails != null
            ? this.$store.state.shippingDetails
            : emptyShippingData;

        this.guestCustomerInfo =
          this.$store.state.guestCustomerInfo != null
            ? this.$store.state.guestCustomerInfo
            : {
              name: "",
              email: "",
              password: "",
              confirm_password: "",
            };
      }
      this.selectedPickupPoints =
        this.$store.state.pickupPoint != null
          ? this.$store.state.pickupPoint
          : {
            id: "",
            name: this.$t("Select pickup point"),
            location: "",
            phone: "",
            zone_id: "",
            zone_name: "",
          };
      this.loading = false;
    },


    /**
     * Change shipping address
     * @param {*} e
     */
    checkedAddress(e) {
      this.$refs.addressRadio.forEach((element) => {
        element.classList.remove("active");
      });

      e.target.parentElement.parentElement.classList.add("active");
      this.$store.dispatch("storeShippingDetails", this.customerShippingInfo);
      this.$emit("shipping-confirmed");
    },

    /**
     * Get counties list
     */
    getCounties() {
      axios
        .get("/api/v1/ecommerce-core/get-countries")
        .then((response) => {
          if (response.data.success) {
            this.countries = response.data.data.countries;
            if (this.countries.length == 1) {
              this.guestShippingInfo.country = this.countries[0];
              this.getStates();
            } else {
              this.enableCountry = true;
              this.guestShippingInfo.country = null;
            }
          }
        })
        .catch((error) => {
          this.countries = [];
        });
    },
    /**
     * Will get state list of a country
     */
    getStates() {
      let country_id = country_id = this.guestShippingInfo.country ? this.guestShippingInfo.country.id : "";
      axios
        .post("/api/v1/ecommerce-core/get-states-of-countries", {
          country_id: country_id,
        })
        .then((response) => {
          if (response.data.success) {
            this.guestShippingInfo.states_options = response.data.data.states;
          }
        })
        .catch((error) => { });
    },
    /**
     * Will get cities list of a state
     */
    getCities() {
      let state = state = this.guestShippingInfo.state ? this.guestShippingInfo.state.id : "";
      axios
        .post("/api/v1/ecommerce-core/get-cities-of-state", {
          state_id: state,
        })
        .then((response) => {
          if (response.data.success) {
            this.guestShippingInfo.cities_options = response.data.data.cities;
          }
        })
        .catch((error) => { });
    },
  },
};
</script>
<style lang="scss" scoped>
.shipping-option {
  padding: 10px 10px;
  font-size: 15px;
  color: #3b3b3b;
  background-color: color-mix(in srgb, var(--mainC) 10%, white);
}

.shipping-option.active {
  background-color: color-mix(in srgb, var(--mainC) 40%, white);
  color: var(--mainC);
  border-bottom: 1px solid #d8dbe0;
}

.border-dashed {
  border: 1px dashed #666;
}
</style>