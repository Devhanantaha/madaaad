<template>
    <div class="h-100 details-right">
        <ul class="info-list" ref="quickConnect">
            <li class="info-item" v-if="product.condition">
                <h5 class="font-weight-medium mb-1">
                    <i class="pr-10 fa fa-connectdevelop"></i>{{ $t("Conditions") }}
                </h5>
                <p class="pl-30">{{ $t("Product Condition") }}</p>
                <span class="c1 pl-30">{{ product.condition }}</span>
            </li>

            <li class="info-item" v-if="product.is_authentic">
                <h5 class="font-weight-medium mb-1">
                    <i class="pr-10 fa fa-registered"></i>{{ $t("Authentic") }}
                </h5>
                <span class="c1 pl-30">{{ product.is_authentic }}</span>
            </li>

            <li class="info-item" v-if="product.is_active_cod">
                <h5 class="font-weight-medium mb-1">
                    <i class="pr-10 fa fa-money"></i>{{ $t("Payment Option") }}
                </h5>
                <p class="pl-30">{{ $t("Cash on Delivery") }}</p>
                <span class="c1 pl-30">{{ product.is_active_cod }}</span>
            </li>

            <li class="info-item">
                <h5 class="font-weight-medium mb-1">
                    <i class="pr-10 fa fa-history"></i>
                    {{ $t("Return Options") }}
                </h5>
                <p class="pl-30">
                    {{ $t("Change of mind is not applicable") }}
                </p>
                <span class="c1 pl-30">{{ product.return_option }}</span>
            </li>

            <li class="info-item">
                <h5 class="font-weight-medium mb-1">
                    <i class="pr-10 fa fa-shield"></i>{{ $t("Warranty") }}
                </h5>
                <p class="pl-30">{{ $t("Seller warranty") }}</p>
                <p class="c1 pl-30" v-if="product.has_warranty == enums.status.ACTIVE">
                    <span>{{ product.warrenty_days }} {{ $t("Days") }}
                        <span v-if="product.has_replacement_warranty">
                            {{ $t("Replacement") }}</span>
                        <span class="ml-5"> {{ $t("warranty") }}</span>
                    </span>
                </p>
                <p class="c1 pl-30" v-else>
                    {{ $t("Not Available") }}
                </p>
            </li>
        </ul>
    </div>
</template>

<script>
import enums from "../../enums/enums";
export default {
    name: "DetailsRight",
    props: {
        product: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            enums: enums,

        };
    },
};
</script>
<style scoped lang="scss">
.info-list {
    list-style: none;
    margin: 0;
    padding: 20px;
    height: 100%;
    overflow-x: hidden;
    background-color: #ccced05e;

    @media only screen and (max-width: 1199px) {
        padding: 0;
    }

    li {
        flex-grow: 1;

        &:not(:first-child) {
            padding-top: 20px;
        }

        &:not(:last-child) {
            padding-bottom: 20px;
            border-bottom: 1px solid #d8dbe0;
        }

        @media only screen and (max-width: 1199px) {
            padding: 15px;
            box-shadow: 3px 3px 30px rgb(0 0 0 / 3%);
            flex-grow: inherit;
            min-width: 220px;
        }

        p {
            margin-bottom: 0;
        }

        span,
        a {
            font-size: 13px;
        }
    }
}
</style>
