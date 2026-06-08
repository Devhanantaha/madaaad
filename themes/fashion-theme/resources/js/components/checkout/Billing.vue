<template>
    <div class="shadow-card mb-30">
        <h3 class="checkout-title">{{ $t("Billing information") }}</h3>
        <div class="row">
            <div class="col-lg-12">
                <ul class="list-unstyled border rounded-1">
                    <li class="single-form-selector" v-if="isActiveHomeDelivery">
                        <div class="custom-radio-btn billing-option border-bottom font-weight-medium"
                            :class="{ active: !isActiveBillToDifferentAddress }">
                            <label class="d-flex align-items-center">
                                <input type="radio" class="billing-option" name="billing-options"
                                    v-on:change="() => { isActiveBillToDifferentAddress = false }"
                                    :checked="!isActiveBillToDifferentAddress" />
                                <span class="label-title black ml-5">{{ $t("Same as shipping address") }}</span>
                            </label>
                        </div>
                    </li>
                    <li class="single-form-selector">
                        <div class="custom-radio-btn billing-option font-weight-medium"
                            :class="{ active: isActiveBillToDifferentAddress }">
                            <label class="d-flex align-items-center">
                                <input type="radio" class="billing-option" name="billing-options"
                                    :checked="isActiveBillToDifferentAddress"
                                    v-on:change="() => { isActiveBillToDifferentAddress = true }" />
                                <span class="label-title black ml-5">{{ $t("Use a different billing address") }}</span>
                            </label>
                        </div>
                        <div class="item-body p-2" v-if="isActiveBillToDifferentAddress">

                            <div class="address-list row" v-if="isCustomerLogin">
                                <div class="col-lg-6 my-1" v-for="address in customerAddress" :key="address.name">
                                    <span class="custom-radio-btn" ref="billingAddressRadio" :class="{
                                        active:
                                            customerBillingInfo != null &&
                                            address.id == customerBillingInfo.id,
                                    }">
                                        <label class="radio-label">
                                            <input name="customerBillingAddress" type="radio" :value="address"
                                                v-model="customerBillingInfo" @change.prevent="checkedBillingAddress"
                                                :checked="customerBillingInfo != null &&
                                                    address.id == customerBillingInfo.id
                                                    " />
                                            <address-card :address="address"></address-card>
                                        </label>
                                    </span>
                                </div>
                            </div>

                            <div class="row guest-billing-address" v-if="!isCustomerLogin">
                                <div class="form-group mb-20 col-lg-6">
                                    <input type="text" v-bind:placeholder="$t('Your Name')"
                                        v-model="guestBillingInfo.name" class="theme-input-style" />
                                    <div v-if="errors[`billing_address.name`]">
                                        <p v-for="(msg, i) in errors[`billing_address.name`]" :key="i"
                                            class="d-block invalid-feedback">
                                            {{ msg }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group mb-20 col-lg-6">
                                    <input type="email" v-bind:placeholder="$t('Email Address')"
                                        v-model="guestBillingInfo.email" class="theme-input-style" />
                                    <div v-if="errors[`billing_address.email`]">
                                        <p v-for="(msg, i) in errors[`billing_address.email`]" :key="i"
                                            class="d-block invalid-feedback">
                                            {{ msg }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group mb-20 col-lg-6">
                                    <input type="tel" v-bind:placeholder="$t('Phone Number')"
                                        v-model="guestBillingInfo.phone" class="theme-input-style" />
                                    <div v-if="errors[`billing_address.phone`]">
                                        <p v-for="(msg, i) in errors[`billing_address.phone`]" :key="i"
                                            class="d-block invalid-feedback">
                                            {{ msg }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group mb-20 col-lg-6">
                                    <input type="text" class="theme-input-style" v-bind:placeholder="$t('Address')"
                                        v-model="guestBillingInfo.address" />
                                    <div v-if="errors[`billing_address.address`]">
                                        <p v-for="(msg, i) in errors[`billing_address.address`]" :key="i"
                                            class="d-block invalid-feedback">
                                            {{ msg }}
                                        </p>
                                    </div>
                                </div>
                                <div :class="config.hide_country_state_city_in_checkout == enums.status.ACTIVE
                                    ? 'form-group mb-20 col-lg-12'
                                    : 'form-group mb-20 col-lg-6'
                                    ">
                                    <input type="text" class="theme-input-style" v-bind:placeholder="$t('Postal Code')"
                                        v-model="guestBillingInfo.postal_code" />
                                    <div v-if="errors[`billing_address.postal_code`]">
                                        <p v-for="(msg, i) in errors[`billing_address.postal_code`]" :key="i"
                                            class="d-block invalid-feedback">
                                            {{ msg }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group mb-20 col-lg-6" v-if="
                                    config.hide_country_state_city_in_checkout != enums.status.ACTIVE && enableCountry
                                ">
                                    <v-select :options="countries" v-model="guestBillingInfo.country" label="name"
                                        :clearable="false"></v-select>
                                    <div v-if="errors[`billing_address.country_id`]">
                                        <p v-for="(msg, i) in errors[`billing_address.country_id`]" :key="i"
                                            class="d-block invalid-feedback">
                                            {{ msg }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group mb-20 col-lg-6" v-if="
                                    config.hide_country_state_city_in_checkout != enums.status.ACTIVE
                                ">
                                    <v-select :options="guestBillingInfo.states_options"
                                        v-model="guestBillingInfo.state" label="name" :clearable="false"></v-select>
                                    <div v-if="errors[`billing_address.state_id`]">
                                        <p v-for="(msg, i) in errors[`billing_address.state_id`]" :key="i"
                                            class="d-block invalid-feedback">
                                            {{ msg }}
                                        </p>
                                    </div>
                                </div>
                                <div class="form-group mb-20 col-lg-6" v-if="
                                    config.hide_country_state_city_in_checkout != enums.status.ACTIVE
                                ">
                                    <v-select :options="guestBillingInfo.cities_options" v-model="guestBillingInfo.city"
                                        :clearable="false" label="name"></v-select>
                                    <div v-if="errors[`billing_address.city_id`]">
                                        <p v-for="(msg, i) in errors[`billing_address.city_id`]" :key="i"
                                            class="d-block invalid-feedback">
                                            {{ msg }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </div>

</template>
<script>
import axios from "axios";
import { mapState } from "vuex";
import AddressCard from "@/components/checkout/AddressCard.vue";
export default {
    name: "Billing",
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
            countries: [],
            enableCountry: false,
            isActiveBillToDifferentAddress: this.$store.state.billToDifferentAddress ?? false,
            guestBillingInfo: {},
            customerBillingInfo: {},
        };
    },
    computed: mapState({
        isActiveHomeDelivery: (state) => state.isActiveHomeDelivery,
    }),
    mounted() {
        if (!this.isCustomerLogin) {
            this.getCounties();
        }
    },
    watch: {
        "guestBillingInfo.country"() {
            this.guestBillingInfo.state = this.$t("Select State");
            this.guestBillingInfo.city = this.$t("Select City");
            this.guestBillingInfo.cities_options = [];
            this.getStates();
        },
        "guestBillingInfo.state"() {
            this.guestBillingInfo.city = this.$t("Select City");
            this.getCities();
        },
        guestBillingInfo: {
            handler() {
                this.$store.dispatch("storeBillingDetails", this.guestBillingInfo);
            },
            deep: true,
        },
        isActiveBillToDifferentAddress(val, oldVal) {
            this.$store.dispatch("storeIsActiveBillToDifferentAddress", this.isActiveBillToDifferentAddress);
        },
        isActiveHomeDelivery(val, oldVal) {
            if (!this.isActiveHomeDelivery) {
                this.$store.dispatch("storeIsActiveBillToDifferentAddress", true);
                this.isActiveBillToDifferentAddress = true;
            }
        }

    },
    methods: {
        /**
         * Change  billing address
         * @param {*} e
         */
        checkedBillingAddress(e) {
            this.$refs.billingAddressRadio.forEach((element) => {
                element.classList.remove("active");
            });

            e.target.parentElement.parentElement.classList.add("active");
            this.$store.dispatch("storeBillingDetails", this.customerBillingInfo);
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
                            this.guestBillingInfo.country = this.countries[0];
                            this.getStates();
                        } else {
                            this.enableCountry = true;
                            this.guestBillingInfo.country = null;
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
            let country_id = this.guestBillingInfo.country ? this.guestBillingInfo.country.id : "";
            axios
                .post("/api/v1/ecommerce-core/get-states-of-countries", {
                    country_id: country_id,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.guestBillingInfo.states_options = response.data.data.states;
                    }
                })
                .catch((error) => { });
        },
        /**
         * Will get cities list of a state
         */
        getCities() {
            let state = this.guestBillingInfo.state ? this.guestBillingInfo.state.id : "";
            axios
                .post("/api/v1/ecommerce-core/get-cities-of-state", {
                    state_id: state,
                })
                .then((response) => {
                    if (response.data.success) {
                        this.guestBillingInfo.cities_options = response.data.data.cities;
                    }
                })
                .catch((error) => { });
        },
    },
};
</script>
<style lang="scss" scoped>
.billing-option {
    padding: 10px 10px;
    font-size: 15px;
    color: #3b3b3b;
    background-color: color-mix(in srgb, var(--mainC) 10%, white);
}

.billing-option.active {
    background-color: color-mix(in srgb, var(--mainC) 40%, white);
    color: var(--mainC);
    border-bottom: 1px solid #d8dbe0;
}
</style>