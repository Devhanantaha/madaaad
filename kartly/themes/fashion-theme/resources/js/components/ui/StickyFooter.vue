<template>
  <div class="bottom-tabbar-list d-md-none">
    <router-link to="/">
      <span class="material-icons"> home </span>
      <span class="mt-1">{{ $t("Home") }}</span>
    </router-link>
    <router-link to="/categories">
      <span class="material-icons"> category </span>
      <span class="mt-1">{{ $t("Category") }}</span>
    </router-link>
    <router-link to="/compare">
      <span class="material-icons"> compare_arrows </span>
      <span class="mt-1">{{ $t("Compare") }}</span>
    </router-link>
    <router-link to="/dashboard/wishlist">
      <span class="material-icons"> favorite_border </span>
      <span class="mt-1">{{ $t("Wishlist") }}</span>
    </router-link>
    <router-link to="/dashboard">
      <account-offcanvas :menuItems="offcanvas.menuItems" :userInfo="customerInfo" />
      <span class="mt-1">{{ $t("Account") }}</span>
    </router-link>
  </div>
</template>
<script>
import offcanvas from "@/fakeDB/offcanvas.json";
import AccountOffcanvas from "@/components/menu/AccountOffcanvas.vue";
import { mapState } from "vuex";
export default {
  name: "StickyFooter",
  components: {
    AccountOffcanvas,
  },
  computed: mapState({
    customerInfo: (state) => state.customerInfo,
  }),
  data() {
    return {
      styled: false,
      offcanvas,
    };
  }
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.bottom-tabbar-list {
  display: flex;
  height: 50px;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  z-index: 9999;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
  background-color: #fff;
  box-sizing: border-box;

  a {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    flex: 1;
    flex-basis: 1px;
    box-sizing: border-box;
    color: #010f1ccf;
    width: 1px;
    text-decoration: none;
    opacity: 1;

    span:not(.material-icons) {
      color: #4f4a4a;
      font-size: 11px;
      line-height: 1;
      font-weight: 600;
    }

    .material-icons {
      font-size: 24px;
    }

    img {
      margin-bottom: 5px;
    }

    &.router-link-exact-active {
      background-color: #f0f0f0;
      opacity: 1;

      span {
        color: $c1;
      }
    }
  }
}
</style>
