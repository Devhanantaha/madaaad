<script>
import { mapState } from "vuex";
import SearchForm from "@/components/ui/SearchForm.vue";
import SingleProduct from "@/components/product/SingleProduct.vue";
import { useCustomerAuth } from "@/composables/useCustomerAuth";
const { notificationReadResponse, readNotification, notificationMarksAsRead } = useCustomerAuth();
import axios from "axios";
export default {
  name: "HeaderMiddle",
  components: {
    SearchForm,
    SingleProduct
  },
  emits: ["logout-customer"],
  props: {
    siteProperties: {
      type: Object,
      required: false,
    },
    mode: {
      type: String,
      required: false,
    },
    cartItem: {
      type: Number,
      required: false,
      default: 0,
    },
    wishlistItem: {
      type: Number,
      required: false,
      default: 0,
    },
    compareItem: {
      type: Number,
      required: false,
      default: 0,
    },
    headerStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    headerLogoStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    dataLoading: {
      type: Boolean,
      required: true,
      default: false,
    },
  },
  data() {
    return {
      loading: false,
      suggestionsOpen: false,
      products_suggestions: [],
      category_suggestions: [],
      tag_suggestions: [],
      showMyAccount: false,
      showNotification: false,
    };
  },
  computed: mapState({
    isCustomerLogin: (state) => state.isCustomerLogin,
    customerToken: (state) => state.customerToken,
    customerInfo: (state) => state.customerInfo,
    notifications: (state) => state.notifications,
    siteSettings: (state) => state.siteSettings,
    totalNotifications: (state) =>
      state.notifications.length ? state.notifications.length : 0,

    enable_product_compare: (state) =>
      state.siteSettings != null
        ? state.siteSettings.enable_product_compare
        : 0,
  }),
  mounted() {
    window.addEventListener("scroll", this.scrollHandler);
    document.addEventListener("click", this.closeDropdown);
  },

  methods: {
    /**
        * Close Dropdown
        */
    closeDropdown(e) {
      let target = e.target;
      let notificationDropdown = this.$refs.notificationDropdownMenu;
      if (notificationDropdown !== target && !notificationDropdown?.contains(target)) {
        this.showNotification = false;
      }

      let searchSuggestionDropdown = this.$refs.searchSuggestion;
      if (searchSuggestionDropdown !== target && !searchSuggestionDropdown?.contains(target)) {
        this.suggestionsOpen = false;
      }
    },
    /**
    * Will logout customer
    *
    */
    customerLogout() {
      this.$emit("logout-customer");
    },
    scrollHandler() {
      const fooHeader = this.$refs.fooHeader;
      if (window.pageYOffset > 100) {
        this.isSticky = true;
        fooHeader.classList.add("sticky", "fadeInDowns");
      } else {
        this.isSticky = false;
        fooHeader.classList.remove("sticky", "fadeInDowns");
      }
    },
    /**
        * Will read notification
        */
    readNotification(notification) {
      readNotification(notification).then(() => {
        if (notificationReadResponse.value && notificationReadResponse.value.success) {
          if (notification.link != null) {
            window.location.href = notification.link;
          } else {
            this.$store.dispatch(
              "customerNotificationAction",
              notificationReadResponse.value.unread_notification.data
            );
          }
        }
      });
    },
    /**
     * Will mark as read all notification
     */
    markAsReadAll() {
      notificationMarksAsRead().then(() => {
        if (notificationReadResponse.value && notificationReadResponse.value.success) {
          this.$store.dispatch("customerNotificationAction", []);
        }
      });
    },
    /**
     * Get suggestions products
     *
     */
    getSearchProducts(search_key) {
      this.search_key = search_key;
      this.loading = true;
      if (search_key) {
        axios
          .post("/api/v1/ecommerce-core/search-suggestions", {
            search_key: search_key,
          })
          .then((response) => {
            this.loading = false;
            if (response.data.success) {
              this.suggestionsOpen = true;
              this.category_suggestions = response.data.categories.data;
              this.products_suggestions = response.data.products.data;
              this.tag_suggestions = response.data.tags;
            }
          })
          .catch((error) => { });
      } else {
        this.suggestionsOpen = false;
      }
    },

  },
};
</script>
<template>
  <!-- Header Middle -->
  <div :class="headerStyle.custom_header == 1
    ? 'custom-header-mid header-middle header-top py-3 header-border'
    : 'header-middle header-middle py-3 header-border'
    " @scroll="scrollHandler" ref="fooHeader">
    <div class="custom-container2">
      <div class="row align-items-center">
        <!--Site Brand-->
        <div class="col-lg-3">
          <template v-if="!dataLoading">
            <template v-if="mode == 'dark'">
              <the-logo :header-logo-style="headerLogoStyle" :logo="siteProperties.logo_dark"
                :title="siteProperties.site_name" v-if="siteProperties.logo_dark" />
              <h2 class="site-title mb-0" v-else>
                <a href="/">{{ siteProperties.site_name }}</a>
              </h2>
            </template>
            <template v-else>
              <the-logo :header-logo-style="headerLogoStyle" :logo="siteProperties.logo"
                :title="siteProperties.site_name" v-if="siteProperties.logo" />
              <h2 class="site-title mb-0" v-else>
                <a href="/">{{ siteProperties.site_name }}</a>
              </h2>
            </template>
          </template>
          <div v-if="dataLoading" class="header-bottom-preloader-item">
            <skeleton height="52px" border-radius="5px" width="100%">
            </skeleton>
          </div>
        </div>
        <!--End Site Brand-->
        <div class="col-lg-5 d-none d-xl-block position-relative">
          <!-- Search Form -->
          <search-form v-if="!dataLoading" @search-suggestions="getSearchProducts" />
          <!-- End Search Form -->
          <!--Search Suggestions-->
          <div class="search-suggestion box-shadow bg-white" v-if="suggestionsOpen">
            <div v-if="
              tag_suggestions.length ||
              category_suggestions ||
              products_suggestions
            ">
              <!--Tags suggestions List-->
              <div v-if="tag_suggestions.length">
                <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">
                  {{ $t("Popular Suggestions") }}
                </div>
                <ul class="list-unstyled mb-0">
                  <li class="d-block text-left suggestion_list" v-for="(tag, index) in tag_suggestions" :key="index">
                    <router-link :to="`/product/search?tag=${tag.permalink}`">{{
                      tag.name
                    }}</router-link>
                  </li>
                </ul>
              </div>
              <!--End Tags suggestion List--->

              <!--Categories suggestions List-->
              <div v-if="category_suggestions">
                <div class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">
                  {{ $t("Category Suggestions") }}
                </div>
                <ul class="list-unstyled mb-0">
                  <li class="d-block text-left suggestion_list" v-for="(category, index) in category_suggestions"
                    :key="index">
                    <router-link :to="`/products/category/${category.slug}`">{{
                      category.name
                    }}</router-link>
                  </li>
                </ul>
              </div>
              <!--End Categories suggestion List--->
              <div v-if="products_suggestions">
                <div ref="searchSuggestion"
                  class="px-2 py-1 text-uppercase fs-10 text-right text-muted bg-soft-secondary">
                  {{ $t("Products Suggestions") }}
                </div>
                <!--Product List-->
                <ul class="list-unstyled mb-0">
                  <li class="d-block text-left suggestion_list" v-for="(product, index) in products_suggestions"
                    :key="index">
                    <single-product :item="product" small />
                  </li>
                </ul>
                <!--End Product List--->
              </div>
            </div>

            <div class="p-3 text-center mt-1" v-else>
              {{ $t("Sorry, nothing found for") }}
              <strong>{{ search_key }}</strong>
            </div>
          </div>
          <!--End Search Suggestions-->
          <div v-if="dataLoading" class="header-bottom-preloader-item">
            <skeleton height="50px" border-radius="5px" width="100%">
            </skeleton>
          </div>
        </div>

        <div class="col-xl-4 col-6">
          <div v-if="!dataLoading" class="d-flex align-items-center justify-content-end header-btn-group">
            <ul class="top-bar-buttons header-middle list-inline mb-0 align-items-center">
              <!--My Account-->
              <li ref="myAccountDropdownMenu" @mouseleave="showMyAccount = false" @mouseenter="showMyAccount = true">
                <div class="my-account-area d-flex align-items-center gap-1 py-1">
                  <div class="icon border p-2 rounded-circle">
                    <span class="cursor-pointer">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 20"
                        class="svg replaced-svg">
                        <path id="Account"
                          d="M15.75,20v-.356A6.545,6.545,0,0,0,9,13.341a6.545,6.545,0,0,0-6.75,6.3V20H0v-.356a8.131,8.131,0,0,1,2.642-5.973A9.254,9.254,0,0,1,9,11.206a9.255,9.255,0,0,1,6.359,2.465A8.131,8.131,0,0,1,18,19.644V20ZM4.4,4.587A4.6,4.6,0,1,1,9,9.175,4.6,4.6,0,0,1,4.4,4.587Zm2.142,0A2.459,2.459,0,1,0,9,2.137,2.458,2.458,0,0,0,6.541,4.587Z">
                        </path>
                      </svg>
                    </span>
                  </div>
                  <div class="title ml-5">{{ isCustomerLogin ? customerInfo.name : $t("My Account") }}</div>
                  <div class="caret">
                    <span class="material-icons text-black"> expand_more </span>
                  </div>
                </div>

                <div class="my-account-dropdown" v-if="showMyAccount">
                  <ul class="list-unstyled" v-if="isCustomerLogin">
                    <li>
                      <router-link to="/dashboard" class="d-flex align-items-center gap-1">
                        <span class="material-icons">dashboard</span>
                        <span class="ml-5">{{ $t("Dashboard") }}</span>
                      </router-link>
                    </li>
                    <li>
                      <router-link to="/dashboard/orders" class="d-flex align-items-center gap-1">
                        <span class="material-icons">receipt</span>
                        <span class="ml-5">{{ $t("Orders") }}</span>
                      </router-link>
                    </li>
                    <li>
                      <router-link to="/dashboard/wishlist" class="d-flex align-items-center gap-1">
                        <span class="material-icons">favorite_border</span>
                        <span class="ml-5"> {{ $t("Wishlist") }}</span>
                      </router-link>
                    </li>
                    <li>
                      <router-link to="/dashboard/address" class="d-flex align-items-center gap-1">
                        <span class="material-icons">local_post_office</span>
                        <span class="ml-5">{{ $t("Address") }}</span>
                      </router-link>
                    </li>

                    <li>
                      <router-link to="/dashboard/refund/requests" class="d-flex align-items-center gap-1">
                        <span class="material-icons">arrow_circle_left</span>
                        <span class="ml-5">{{ $t("Refunds") }}</span>
                      </router-link>
                    </li>
                    <li>
                      <router-link to="/dashboard/wallet" class="d-flex align-items-center gap-1">
                        <span class="material-icons">wallet</span>
                        <span class="ml-5">{{ $t("Wallet") }}</span>
                      </router-link>
                    </li>

                    <li>
                      <router-link to="/dashboard/manage-account" class="d-flex align-items-center gap-1">
                        <span class="material-icons">account_box</span>
                        <span class="ml-5">{{ $t("Account") }}</span>
                      </router-link>
                    </li>
                    <li>
                      <router-link to="#" @click.prevent="customerLogout()" class="d-flex align-items-center gap-1">
                        <span class="material-icons">logout</span>
                        <span class="ml-5">{{ $t("Logout") }}</span>
                      </router-link>
                    </li>
                  </ul>

                  <ul class="list-unstyled" v-else>
                    <li>
                      <router-link to="/login" class="d-flex align-items-center gap-1">
                        <span class="material-icons">login</span>
                        <span class="ml-5">{{ $t("Login") }}</span>
                      </router-link>
                    </li>
                    <li>
                      <router-link to="/register" class="d-flex align-items-center gap-1">
                        <span class="material-icons">person_add</span>
                        <span class="ml-5">{{ $t("Register") }}</span>
                      </router-link>
                    </li>
                  </ul>
                </div>
              </li>
              <!--End My Account-->
              <!--Notification-->
              <li ref="notificationDropdownMenu" v-if="isCustomerLogin">
                <div class="notification-btn" @click.prevent="showNotification = !showNotification">
                  <span class="cursor-pointer">
                    <svg class="fill" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960"
                      width="24">
                      <path
                        d="M180.001-204.616v-59.998h72.308v-298.463q0-80.692 49.807-142.692 49.808-62 127.885-79.307v-24.923q0-20.833 14.57-35.416 14.57-14.584 35.384-14.584t35.429 14.584q14.615 14.583 14.615 35.416v24.923q78.077 17.307 127.885 79.307 49.807 62 49.807 142.692v298.463h72.308v59.998H180.001ZM480-497.692Zm-.068 405.383q-29.855 0-51.047-21.24-21.192-21.24-21.192-51.067h144.614q0 29.923-21.26 51.115-21.26 21.192-51.115 21.192ZM312.307-264.614h335.386v-298.463q0-69.462-49.116-118.577Q549.462-730.77 480-730.77q-69.462 0-118.577 49.116-49.116 49.115-49.116 118.577v298.463Z" />
                    </svg>
                  </span>
                  <span class="count" v-if="totalNotifications > 0">
                    {{ totalNotifications }}
                  </span>
                </div>
                <div class="notification-dropdown" v-if="showNotification">
                  <div class="bg-light d-flex justify-content-between p-2 notification-header mb-2">
                    <h6 class="mb-0">{{ $t("Notification") }}</h6>
                    <a href="#" class="mb-0 text-black" v-if="totalNotifications > 0" @click.prevent="markAsReadAll">
                      {{ $t("Mark all as read") }}
                    </a>
                  </div>
                  <ul class="list-unstyled p-3 pt-0" v-if="totalNotifications > 0">
                    <li v-for="(notification, index) in notifications" :key="index" class="notification-item">
                      <a href="#" class="d-flex align-items-center text-black"
                        @click.prevent="readNotification(notification)">
                        <div class="content">
                          <div class="main-text mb-1" v-html="notification.message"></div>
                          <p class="time text-muted">{{ notification.time }}</p>
                        </div>
                      </a>
                    </li>
                  </ul>
                  <ul class="list-unstyled p-3" v-else>
                    <li>
                      <p>
                        {{ $t("You have no unread notification") }}
                      </p>
                    </li>
                  </ul>
                </div>
              </li>
              <!--End Notification-->
            </ul>
            <!-- Counter -->
            <ul class="top-bar-buttons header-middle list-inline mb-0 align-items-center">
              <li v-if="enable_product_compare == 1">
                <router-link to="/compare" class="pr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 18 12"
                    class="svg replaced-svg">
                    <path id="Path_29515" data-name="Path 29515"
                      d="M8.309,13.616H2.9a.955.955,0,0,0,0,1.907H8.309V17.23a.443.443,0,0,0,.765.334l2.5-2.661a.511.511,0,0,0,0-.677l-2.5-2.661a.445.445,0,0,0-.765.334Zm5.382-2.108V9.8H19.1a.955.955,0,0,0,0-1.907H13.691V6.187a.443.443,0,0,0-.765-.334l-2.5,2.661a.511.511,0,0,0,0,.677l2.5,2.661A.447.447,0,0,0,13.691,11.508Z"
                      transform="translate(-2 -5.708)"></path>
                  </svg>
                  <span class="count" v-if="compareItem > 0">
                    {{ compareItem }}
                  </span>
                </router-link>
              </li>

              <li>
                <router-link to="/dashboard/wishlist" class="pr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 16 16"
                    class="svg replaced-svg">
                    <path id="wishlist"
                      d="M1731.994,46a.633.633,0,0,1-.47-.223l-6.2-7.033a5.613,5.613,0,0,1-.007-7.241,4.161,4.161,0,0,1,6.409.007l.276.313.269-.306a4.163,4.163,0,0,1,6.409,0,5.628,5.628,0,0,1-.006,7.244l-6.211,7.023A.632.632,0,0,1,1731.994,46Zm-3.475-14.517a2.991,2.991,0,0,0-2.255,1.062,4.01,4.01,0,0,0,0,5.164l5.728,6.5,5.741-6.49a4.02,4.02,0,0,0,0-5.168,2.933,2.933,0,0,0-4.521,0l-.739.839a.614.614,0,0,1-.944,0l-.746-.846A3,3,0,0,0,1728.519,31.481Z"
                      transform="translate(-1723.999 -29.999)"></path>
                  </svg>
                  <span class="count" v-if="wishlistItem">
                    {{ wishlistItem }}
                  </span>
                </router-link>
              </li>
              <li>
                <router-link to="/cart" class="pr-3">
                  <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 20 20"
                    class="svg replaced-svg" style="">
                    <path id="Cart"
                      d="M1783.345,37.526h-3.636l-4.545-6.364c-.343-.493-.326-.909-.909-.909h0a2.618,2.618,0,0,0-1.818.909l-4.546,6.364h-2.727a.9.9,0,0,0-.909.909h0a12.409,12.409,0,0,0,2.727,8.182,9.091,9.091,0,0,0,14.545,0,12.412,12.412,0,0,0,2.727-8.182h0A.9.9,0,0,0,1783.345,37.526Zm-9.091-5.455c.021-.029-.009.006,0,0s-.021-.029,0,0l3.636,5.455h-8.182Zm0,16.364c-4.317,0-7.922-4.229-8.182-9.091h16.364C1782.176,44.206,1778.572,48.435,1774.254,48.435Z"
                      transform="translate(-1764.254 -30.253)"></path>
                  </svg>
                  <span class="count" v-if="cartItem > 0">
                    {{ cartItem }}
                  </span>
                </router-link>
              </li>
            </ul>
            <!-- End Counter -->

          </div>
          <div v-if="dataLoading" class="d-flex align-items-center gap-1">
            <skeleton height="50px" border-radius="5px" width="30%">
            </skeleton>
            <skeleton height="50px" border-radius="5px" width="18%">
            </skeleton>
            <skeleton height="50px" border-radius="5px" width="18%">
            </skeleton>
            <skeleton height="50px" border-radius="5px" width="18%">
            </skeleton>
            <skeleton height="50px" border-radius="5px" width="18%">
            </skeleton>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- End Header Middle -->
</template>
<style lang="scss" scoped>
.site-title a {
  color: #000;
}

.text-color-white {
  color: #fff;
}

// My Account Dropdown
.header-btn-group ul .my-account-dropdown {
  position: absolute;
  top: calc(98%);
  left: 0px;
  z-index: 99;
  background: #fff;
  width: max-content;
  min-width: 180px;
  padding: 20px 16px;
  box-shadow: 0 2px 10px #0000001a;
  border-radius: 8px;

  &::before {
    content: "";
    position: absolute;
    top: -10px;
    left: 40px;
    width: 0px;
    height: 0px;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #fff;
  }
}

.my-account-dropdown ul li:not(:last-child) {
  padding-bottom: 15px;
}

//Notification Dropdown
.header-btn-group ul .notification-dropdown {
  position: absolute;
  top: calc(98%);
  right: -30px;
  z-index: 99;
  background: #fff;
  min-width: 290px;
  box-shadow: 0 2px 10px #0000001a;
  border-radius: 8px;
  max-height: 400px;
  overflow-y: auto;
  scrollbar-width: thin !important;

  &::before {
    content: "";
    position: absolute;
    top: -10px;
    left: 40px;
    width: 0px;
    height: 0px;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
    border-bottom: 10px solid #fff;
  }
}

.notification-dropdown ul li.notification-item {
  font-weight: 400;
  line-height: 1rem;
  padding: 7px 0 7px;
  clear: both;
  text-transform: capitalize;
  margin-bottom: 0px;
  font-size: 13px;
}

.notification-dropdown ul li:not(:last-child) {
  border-bottom: 1px solid #f5f5f5;
}

.search-suggestion {
  z-index: 9;
  position: absolute;
  top: 99%;
  width: 96%;
  left: 50%;
  max-height: 500px;
  min-height: 100px;
  overflow-y: scroll;
  overflow-x: hidden;
  transform: translateX(-50%);
  border-radius: 0px;
  scrollbar-width: none !important;
  box-shadow: 0 10px 15px -3px rgb(0 0 0 / 10%), 0 4px 6px -2px rgb(0 0 0 / 5%) !important;
}

.search-suggestion::-webkit-scrollbar {
  display: none;
}

.color-gray {
  color: #0a0e33b8;
}

.font-weight-custom {
  font-weight: 600;
}

.suggestion_list {
  padding: 5px 5px;
}

.suggestion_list:not(:last-child) {
  border-bottom: 1px solid rgba(51, 51, 51, 0.1);
}

.suggestion_list:hover {
  background-color: #dcdcdc36;
}

.bg-soft-secondary {
  background-color: rgb(238 240 242) !important;
}

.fs-10 {
  font-size: 0.625rem !important;
}

.text-muted {
  color: #6c757d !important;
}
</style>