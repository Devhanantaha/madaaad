<template>
    <page-header class="py-3 mb-3" whiteBg :items="bItems" />
    <div class="row mx-0">
        <div class="col-lg-6 mx-auto">
            <div class="order-tracking-form p-0 shadow-card mt-lg-4">
                <div class="form-header border-bottom">
                    <h5 class="mb-0">{{ $t("Track your order") }}</h5>
                </div>
                <div class="form-body p-4">
                    <form @submit.prevent="getOrderDetails">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group mb-20">
                                    <label class="font-weight-bold" for="tracking_code">{{ $t("Order ID") }}</label>
                                    <input type="text" class="theme-input-style" id="tracking_code" v-model="orderID"
                                        v-bind:placeholder="$t('Enter Order ID')" />
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn_fill w-100 mx-auto justify-content-center">
                            {{ $t("Track Order") }}
                        </button>
                    </form>
                </div>
            </div>
            <template v-if="!loading">
                <div class="my-4 order_details p-2 p-3 rounded-1 shadow-card"
                    v-if="orderDetails != NULL && searchCompleted">
                    <!--Order details header-->
                    <div class="bg-light mb-3 p-4 rounded shadow-card">
                        <div class="row">
                            <div class="col-12 col-lg-6">
                                <h6 class="mb-0">{{ $t("Order ID") }} : {{ orderDetails.order_code }}</h6>
                            </div>
                            <div class="align-items-center col-12 col-lg-6 d-flex justify-content-lg-end">
                                <h6 class="mb-0">
                                    {{ $t("Total") }} :
                                    <the-currency :amount="orderDetails.total_payable_amount"></the-currency>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <!--End order details header-->
                    <!--Product list-->
                    <div class="row" v-for="(product, index) in orderDetails.products.data" :key="index">
                        <div class="col-12">
                            <div class="card mb-30 p-0">
                                <div class="border-bottom d-flex justify-content-between p-3 pb-2">
                                    <h5>
                                        {{ $t("Item ") + (index + 1) }}
                                    </h5>
                                </div>
                                <div class="p-3">
                                    <div class="row px-12" v-if="
                                        product.delivery_status !=
                                        enums.order_delivery_status.CANCELLED
                                    ">
                                        <!--Shipping info-->
                                        <div class="col-md-12">
                                            <div class="order-info d-flex justify-content-between">
                                                <!--Delivery time-->
                                                <div class="delivery-time" v-if="
                                                    product.delivery_status !=
                                                    enums.order_delivery_status.DELIVERED
                                                ">
                                                    <span v-if="product.estimate_delivery_time != null">
                                                        {{ $t("Estimated Delivery By") }}
                                                        {{ product.estimate_delivery_time }}
                                                    </span>
                                                </div>
                                                <div class="delivery-time" v-else>
                                                    <span v-if="product.delivered_date != null">
                                                        {{ $t("Delivered On") }} {{ product.delivered_date }}
                                                    </span>
                                                </div>
                                                <!--End delivery time-->
                                                <!--Shipping method-->
                                                <div class="shipping-method" v-if="orderDetails.pickup_point == null">
                                                    {{ $t("Shipping") }}:
                                                    <span>
                                                        <the-currency :amount="product.shipping_cost"
                                                            v-if="product.shipping_cost">
                                                        </the-currency>
                                                    </span>
                                                    <span v-if="product.shipping != null">
                                                        ({{ product.shipping["name"] }}
                                                        <span v-if="product.shipping['via']">
                                                            {{ $t("via") }} {{ product.shipping["via"] }}
                                                        </span>
                                                        )
                                                    </span>
                                                    <span v-else> N/A </span>
                                                </div>
                                                <div class="shipping-method" v-else>
                                                    <span>{{ $t("Local Pickup") }}</span>
                                                </div>
                                                <!--End shipping method-->
                                            </div>
                                        </div>
                                        <!--End shipping info-->
                                        <!--Order status and tracking history-->
                                        <div class="col-12">
                                            <div class="order-status-range d-none d-lg-block">
                                                <order-steps :status="product.delivery_status"></order-steps>
                                            </div>

                                            <order-tracking v-if="product.tracking_list.length > 0"
                                                :items="product.tracking_list"></order-tracking>
                                        </div>
                                    </div>
                                    <div class="row px-4" v-else>
                                        <p class="alert alert-danger">
                                            {{ $t("This item has been cancelled") }}
                                        </p>
                                    </div>
                                    <!--End Order status and  tracking history-->
                                    <!--Product details-->
                                    <div class="row px-12">
                                        <div class="col-md-12">
                                            <div class="product-list-group">
                                                <div class="product-list p-3 bg-body-tertiary">
                                                    <div class="row gy-10 align-items-center">
                                                        <div class="col-md-6">
                                                            <div class="d-flex align-items-center">
                                                                <router-link :to="`/products/${product.permalink}`">
                                                                    <img :src="product.image" :alt="product.name"
                                                                        class="cart-image mr-10 rounded-circle" />
                                                                </router-link>
                                                                <div class="item-info">
                                                                    <!--Item Name-->
                                                                    <router-link :title="`${product.name}`"
                                                                        :to="`/products/${product.permalink}`"
                                                                        class="cart-product-name product-name text-capitalize">
                                                                        {{ product.name }}
                                                                    </router-link>
                                                                    <!--End Item Name-->
                                                                    <!--Variant-->
                                                                    <div class="product-variant extra-addons-wrap d-flex flex-wrap"
                                                                        v-if="product.variant != null">
                                                                        <product-variant
                                                                            class="font-weight-medium fz-12"
                                                                            :variant="product.variant"
                                                                            tag="p"></product-variant>
                                                                    </div>
                                                                    <!--End Variant-->
                                                                    <a :href="product.attachment" target="_blank"
                                                                        v-if="product.attachment"
                                                                        class="btn-link fz-12">
                                                                        {{ $t("View Attachment") }}
                                                                    </a>
                                                                    <!--Shop-->
                                                                    <div class="extra-addons-wrap d-flex flex-wrap"
                                                                        v-if="product.shop != null">
                                                                        <p class="product-shop fz-12">
                                                                            {{ $t("Sold By") }}
                                                                            <router-link
                                                                                :to="`/shop/${product.shop.shop_slug}`"
                                                                                target="_blank" class="link-danger">
                                                                                {{ product.shop.shop_name }}
                                                                            </router-link>
                                                                        </p>
                                                                    </div>
                                                                    <!--End shop-->
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div
                                                                class="align-items-center d-flex justify-content-between right-info">
                                                                <div class="price">
                                                                    <the-currency
                                                                        :amount="product.unit_price"></the-currency>
                                                                    <span>x{{ product.quantity }}</span>
                                                                </div>
                                                                <div class="price">
                                                                    <the-currency :amount="product.unit_price * product.quantity
                                                                        "></the-currency>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--End product details-->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--End product list-->
                </div>
                <the-not-found v-if="orderDetails == NULL && searchCompleted" title="No Order found"
                    class="my-4"></the-not-found>

                <div class="init" v-if="orderDetails == NULL && !searchCompleted">
                    <div class="my-5 pt-md-5 mx-auto text-center max-width-350px">
                        <span class="material-icons fz-80">
                            local_shipping
                        </span>
                        <p>
                            {{ $t("Enter your order ID to get your order delivery updates") }}
                        </p>
                    </div>
                </div>
            </template>
            <template v-if="loading">
                <skeleton class="w-100 mb-20" height="70px"></skeleton>
                <skeleton class="w-100" height="500px"></skeleton>
            </template>
        </div>
    </div>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import OrderTracking from "@/components/ui/OrderTracking.vue";
import OrderSteps from "@/components/ui/OrderSteps.vue";
import ProductVariant from "@/components/ui/ProductVariant.vue";
import enums from "../../enums/enums";
import axios from "axios";
import { mapState } from "vuex";
import { CSpinner } from "@coreui/vue";

export default {
    name: "TrackingOrder",
    components: {
        CSpinner,
        PageHeader,
        OrderTracking,
        OrderSteps,
        ProductVariant
    },
    data() {
        return {
            pageTitle: "Order Tracking",
            loading: false,
            searchCompleted: false,
            success: false,
            bItems: [
                {
                    text: this.$t("Home"),
                    href: "/",
                },
                {
                    text: this.$t("Order Tracking"),
                    active: true,
                },
            ],
            orderDetails: null,
            enums: enums,
            errors: [],
            phoneNumber: "",
            orderID: "",
        };
    },

    computed: mapState({
        customerToken: (state) => state.customerToken,
        product_config: (state) => state.siteSettings,
    }),
    mounted() {
        document.title = this.$t("Order Tracking");
    },
    methods: {
        /**
         * Get order details
         */
        getOrderDetails() {
            if (!this.orderID) {
                this.$toast.error(this.$t("Please enter order id"));
                return false;
            }
            this.loading = true;
            this.searchCompleted = false;
            this.orderDetails = null;
            axios
                .post(
                    "/api/v1/ecommerce-core/order/track",
                    {
                        order_code: this.orderID,
                    }
                )
                .then((response) => {
                    if (response.data.success) {
                        this.loading = false;
                        this.orderDetails = response.data.data;
                        this.success = true;
                    } else {
                        this.loading = false;
                    }
                    this.searchCompleted = true;
                })
                .catch((error) => {
                    this.loading = false;
                    this.searchCompleted = true;
                });
        },
    },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.order-info {
    flex-wrap: wrap;
    gap: 10px;
    padding: 0;
}

.px-12 {
    padding-inline: 12px;
}

.gy-10 {
    gap: 10px 0;
}

.product-list-group {
    margin-top: 45px;
}

.product-list:not(:last-child) {
    margin-bottom: 20px;
}

.product-list .product-info {
    gap: 10px;
}

.product-list .product-info .image {
    max-width: 100px;
}

.product-list .product-info .title h5 {
    display: block;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
}

.product-list .product-info:not(:last-child) {
    margin-bottom: 10px;
}



.td-right {
    text-align: right;
}

@media (max-width: 575px) {
    .right-info {
        flex-direction: column;
    }
}

.img70 {
    min-width: 70px !important;
    max-width: 70px !important;
}






.order-tracking-form {
    max-width: 500px;
    border-radius: 5px;
    margin-left: auto;
    margin-right: auto;
}

.order-tracking-form .form-header {
    border-bottom: 1px solid #bfc1c7;
    padding: 15px 10px;
    text-align: center;
}

.max-width-350px {
    max-width: 350px;
}

.fz-80 {
    font-size: 80px;
}
</style>
