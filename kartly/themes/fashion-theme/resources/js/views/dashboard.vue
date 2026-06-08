<template>
  <div class="light-bg pt-4 pt-md-5 pb-4 pb-md-5">
    <div class="custom-container2">
      <div class="row" v-if="isCustomerLogin">
        <div class="col-lg-3">
          <div class="store-info media c1-bg pl-20 pr-20 pt-15 pb-15 align-items-center mb-30">
            <div class="reseller-logo position-relative me-3">
              <img class="rounded-circle border" :src="customerInfo.image" :alt="`${customerInfo.name}`" height="50px"
                width="50px" />

              <template>
                <span class="btn_tooltip position-absolute" title="Verified">
                  <span class="tooltip-icon w-auto h-auto">
                    <img src="/src/assets/images/icons/dashboard/verified.png" alt="" />
                  </span>
                </span>
              </template>
            </div>
            <div class="media-body">
              <h5 class="store-title text-white font-weight-medium mb-0">
                {{ customerInfo.name }}
              </h5>
              <span class="store-tagline fz-12">ID: {{ customerInfo.uid }}</span>
            </div>
          </div>
        </div>

        <div class="col-lg-9">
          <div class="c1-bg pl-20 pr-20 pt-20 pb-15 mb-30">
            <div class="row gx-0">
              <!-- Store Info -->
              <div class="single-store-info col-lg-3 col-md-6 mb-10 mb-lg-0">
                <span class="d-block fz-12">{{
                  $t("Total Purchase Amount")
                }}</span>
                <h4 class="text-white mb-0">
                  <the-currency :amount="dashboard_content != null
                    ? dashboard_content.total_purchase_amount
                    : 0
                    "></the-currency>
                </h4>
              </div>
              <!-- End Store Info -->

              <!-- Store Info -->
              <div class="single-store-info col-lg-4 col-md-6 mb-10 mb-lg-0">
                <span class="d-block fz-12">{{ $t("Total Purchase in") }}
                  {{ dashboard_content.current_month }}
                </span>
                <h4 class="text-white mb-0">
                  <the-currency :amount="dashboard_content.current_month_purchase"></the-currency>
                </h4>
              </div>
              <!-- End Store Info -->

              <div class="col-lg-5">
                <!-- Store Info -->
                <div class="single-store-info d-flex align-items-center mb-1">
                  <span class="d-block fz-12">
                    {{ $t("Last Purchase") }} (
                    {{ dashboard_content.last_purchase_date }}
                    )
                  </span>
                  <h6 class="text-white font-weight-medium ml-10 mb-0">
                    <the-currency :amount="dashboard_content.last_purchase_amount"></the-currency>
                  </h6>
                </div>
                <!-- End Store Info -->
                <!-- Store Info -->
                <div class="single-store-info d-flex align-items-center">
                  <span class="d-block fz-12">
                    {{ $t("Last Month Purchase") }} ({{
                      dashboard_content.last_month
                    }})
                  </span>
                  <h6 class="text-white font-weight-medium ml-10 mb-0">
                    <the-currency :amount="dashboard_content.last_month_purchase"></the-currency>
                  </h6>
                </div>
                <!-- End Store Info -->
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
          <ul class="reseller-dashboard-menu nav flex-column">
            <li>
              <router-link to="/dashboard" class="d-flex align-items-center gap-2">
                <span class="material-icons fz-16">dashboard</span> {{ $t("Dashboard") }}
              </router-link>
            </li>
            <li>
              <router-link to="/dashboard/orders" class="d-flex align-items-center gap-2">
                <span class="material-icons">receipt</span>{{ $t("Orders") }}
              </router-link>
            </li>
            <li>
              <router-link to="/dashboard/wishlist" class="d-flex align-items-center gap-2">
                <span class="material-icons">favorite_border</span> {{ $t("Wishlist") }}
              </router-link>
            </li>
            <li>
              <router-link to="/dashboard/address" class="d-flex align-items-center gap-2">
                <span class="material-icons">local_post_office</span> {{ $t("Address") }}
              </router-link>
            </li>

            <li>
              <router-link to="/dashboard/refund/requests" class="d-flex align-items-center gap-2">
                <span class="material-icons">arrow_circle_left</span>{{ $t("Refunds") }}
              </router-link>
            </li>
            <li>
              <router-link to="/dashboard/wallet" v-if="siteSettings.is_active_wallet == status.ACTIVE"
                class="d-flex align-items-center gap-2">
                <span class="material-icons">wallet</span>{{ $t("My Wallet") }}
              </router-link>
            </li>

            <li>
              <router-link to="/dashboard/manage-account" class="d-flex align-items-center gap-2">
                <span class="material-icons">account_box</span> {{ $t("Account") }}
              </router-link>
            </li>
          </ul>
        </div>

        <div class="col-lg-9">
          <slot></slot>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";
import enums from "../enums/enums";
export default {
  data() {
    return {
      supportTicketMgs: 2,
      status: enums.status,
    };
  },
  computed: mapState({
    isCustomerLogin: (state) => state.isCustomerLogin,
    customerToken: (state) => state.customerToken,
    customerInfo: (state) => state.customerInfo,
    dashboard_content: (state) => state.customerDashboardInfo,
    siteSettings: (state) => state.siteSettings,
  }),
};
</script>

<style lang="scss" scoped>
@import "../assets/sass/00-abstracts/01-variables";

.fz-12 {
  font-size: 12px;
}

.store-info {
  line-height: 1;

  .btn_tooltip {
    bottom: 0;
    right: 0;

    .tooltip-icon {
      width: 18px;
      height: 18px;
    }
  }

  .store-title {
    line-height: calc(24 / 16);
  }

  .store-tagline {
    color: #fff;
  }
}

.single-store-info {
  line-height: 1;

  span {
    color: #fff;
    line-height: calc(18 / 12);
  }

  h4 {
    line-height: calc(27 / 18);
  }
}

.reseller-dashboard-menu {
  li {
    &:not(:last-child) {
      margin-bottom: 5px;
    }

    a {
      display: block;
      line-height: 50px;
      padding: 0 12px;
      background-color: #fff;

      img {
        margin-right: 12px;
      }

      &:hover,
      &.router-link-exact-active {
        color: $c1;
      }
    }
  }

  .btn_badge {
    width: 25px;
    height: 25px;
    border: 2px solid #ffe6e6;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 12px;
    background-color: #fff;
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
  }
}
</style>
