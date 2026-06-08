<script setup>
const $toast = useToast();
import { onMounted, computed, watch, ref } from "vue";
import { useStore } from "vuex";
import { useToast } from "vue-toast-notification";
import { useCustomerAuth } from "@/composables/useCustomerAuth";
import { useMenu } from "@/composables/useMenu";
import { useSite } from "@/composables/useSite";
import { useTheme } from "@/composables/useTheme";
import { useRoute } from "vue-router";
import { CModal, CModalBody } from "@coreui/vue";

const route = useRoute();
const isSingleProduct = ref(false);
watch(
  () => route.name,
  (newName) => {
    isSingleProduct.value = newName === "product";
  },
  { immediate: true }
);

const store = useStore();
const wishlistItem = computed(() => store.getters.wishlistItem);
const cartItem = computed(() => store.getters.cartItem);
const compareItem = computed(() => store.getters.compareItem);
const mode = computed(() => store.state.mode);

// composables
const { refreshCustomerToken, customerAuthData, error: authError, customerLogout } = useCustomerAuth();
const { MenuItemsLoading, headerBottomMenu, fetchAllMenuItems } = useMenu();
const { siteProperties, languages, currencies, loadSiteProperties, loadMegaCategories, megaCategories } = useSite();
const { headerTopOptions, visibleDarkLightSwitcher, headerStyle, headerLogoStyle, headerMenuStyle, footerStyle, subscriptionFormStyle, topBarBannerProps, gdprProps, visibleGdpr, websitePopupProps, visibleTopBarBanner, visibleWebsitePopup, closePopupModal, loadThemeStyle, loadFooterWidgets, footerWidgets } = useTheme();


import { defineAsyncComponent } from "vue";

const Copyright = defineAsyncComponent(() =>
  import("@/components/ui/Copyright.vue")
);
const StickyFooter = defineAsyncComponent(() =>
  import("@/components/ui/StickyFooter.vue")
);
const Gdpr = defineAsyncComponent(() => import("@/components/ui/Gdpr.vue"));

const TopBarBanner = defineAsyncComponent(() =>
  import("@/components/ui/TopBarBanner.vue")
);

const HeaderTop = defineAsyncComponent(() =>
  import("@/components/pageheader/HeaderTop.vue")
);
const HeaderMiddle = defineAsyncComponent(() =>
  import("@/components/pageheader/HeaderMiddle.vue")
);
const HeaderBottom = defineAsyncComponent(() =>
  import("@/components/pageheader/HeaderBottom.vue")
);

const MobileHeader = defineAsyncComponent(() =>
  import("@/components/pageheader/MobileHeader.vue")
);

const BackToTop = defineAsyncComponent(() =>
  import("@/components/ui/BackToTop.vue")
);
const Preloader = defineAsyncComponent(() =>
  import("@/components/ui/Preloader.vue")
);


const SubscribeForm = defineAsyncComponent(() =>
  import("@/components/widget/SubscribeForm.vue")
);

const MainFooter = defineAsyncComponent(() =>
  import("@/components/footer/MainFooter.vue")
);


onMounted(() => {
  loadSiteProperties();
  loadMegaCategories();
  fetchAllMenuItems();
  loadThemeStyle();
  loadFooterWidgets();

  document.body.classList.toggle("dark", mode.value == "dark");

  if (store.state.isCustomerLogin) {
    refreshCustomerToken().then(() => {
      if (customerAuthData.value.success) {
        store.dispatch("customerLogin", customerAuthData.value);
        store.dispatch("getCustomerCartItems");
      } else {
        store.dispatch("customerLogout");
      }
    });
  }
});

/**
    * 
    * Set Currency
    */
function setCurrency(currency) {
  localStorage.setItem("currency", JSON.stringify(currency));
  $toast.success("Currency changed successfully");
  location.reload();
}
/**
 * Set Language
 */
function setLanguage(lang) {
  localStorage.setItem("locale", lang);
  $toast.success("Language changed successfully");
  location.reload();
}

function logoutCustomer() {
  customerLogout();

}
/**
    * Toggle dark mood
    */
function toggleDark(e) {
  if (e.target.checked) {
    localStorage.setItem("mode", "dark");
    store.dispatch("changeScreenMode", "dark");
  } else {
    localStorage.removeItem("mode");
    store.dispatch("changeScreenMode", null);
  }
  var body = document.querySelector("body");
  body.className = e.target.checked ? "dark" : "";
}
</script>


<template>
  <div class="layout__two">

    <top-bar-banner :properties="topBarBannerProps" v-if="visibleTopBarBanner"></top-bar-banner>
    <!-- Header -->
    <header class="header__two love-sticky">
      <header-top v-if="headerTopOptions && headerTopOptions.header_top_status == 1"
        :header-top-options="headerTopOptions" :currencies="currencies" :languages="languages"
        @change-currency="setCurrency" @change-language="setLanguage"></header-top>

      <header-middle :data-loading="MenuItemsLoading" :site-properties="siteProperties" :mode="mode"
        :header-style="headerStyle" :header-logo-style="headerLogoStyle" :cart-item="cartItem"
        :wishlist-item="wishlistItem" :compare-item="compareItem" @logout-customer="logoutCustomer"
        class="d-none d-lg-block"></header-middle>

      <header-bottom :data-loading="MenuItemsLoading" :header-menu-style="headerMenuStyle"
        :menu-items="headerBottomMenu" :header-style="headerStyle" :mega-categories="megaCategories"
        class="d-none d-lg-block"></header-bottom>
    </header>

    <mobile-header :site-properties="siteProperties" :mode="mode" :cart-item="cartItem" :header-style="headerStyle"
      :header-menu-style="headerMenuStyle" :header-logo-style="headerLogoStyle" :mega-categories="megaCategories"
      :menu-items="headerBottomMenu"></mobile-header>
    <!-- End Header -->

    <div class="main_content light-bg">
      <slot />
    </div>

    <StickyFooter v-if="!isSingleProduct" />

    <!-- Footer -->
    <footer class="d-none d-md-block footer footer__two">
      <!-- Footer Top -->
      <main-footer :footer-widgets="footerWidgets" :footer-style="footerStyle"
        :subscriptionFormStyle=subscriptionFormStyle :data-loading="false">
      </main-footer>
      <!-- End Footer Top -->
      <!-- Footer Bottom -->
      <div :class="footerStyle && footerStyle.custom_footer == 1
        ? 'custom-footer footer-bottom'
        : 'footer-bottom bg-white'">
        <div class="custom-container2">
          <div class="text-center py-4">
            <copyright :site-properties="siteProperties" />
          </div>
        </div>
      </div>
      <!-- End Footer Bottom -->
    </footer>
    <!-- End Footer -->

    <!--Cookie Consent-->
    <gdpr v-if="visibleGdpr" :properties="gdprProps"></gdpr>
    <!--End Cookie Consent-->

    <!-- Dark Light Switcher -->
    <div class="floating-mode-switcher-wrap" v-if="visibleDarkLightSwitcher">
      <label class="dl-switch">
        <input class="dark-looks-mode-changer" @change="toggleDark" :checked="mode == 'dark'" type="checkbox" />
        <span class="dl-slider"></span>
        <span class="dl-light">Light</span>
        <span class="dl-dark">Dark</span>
      </label>
    </div>
    <!-- End Dark Light Switcher -->
    <!--Website popup-->
    <CModal :visible="visibleWebsitePopup" alignment="center" class="website-popup-modal" @close="
      () => {
        visibleWebsitePopup = false;
      }
    ">
      <CModalBody class="modal-body p-0 position-relative website-popup-modal-body rounded-0"
        v-if="websitePopupProps != null">
        <button class="btn-circle custom-modal-btn position-absolute size-35" @click="closePopupModal()">
          <base-icon-svg name="close" :width="10" :height="10" />
        </button>
        <div class="m-0" v-html="websitePopupProps.website_popup_content"></div>
        <subscribe-form class="mt-20" v-if="websitePopupProps.website_popup_subscribe_status == 1"></subscribe-form>
      </CModalBody>
    </CModal>
    <!--End website popup-->
    <BackToTop />
  </div>
</template>