<template>
  <!-- Offcanvas -->
  <div class="offcanvas-custom-container2 d-flex">
    <!-- Offcanvas Trigger -->
    <button class="hamburger" :class="{ active: isOffcanvasOpened }" @click="toggleOffcanvas">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <!-- End Offcanvas Trigger -->

    <div class="offcanvas-wrapper" :class="{ open: isOffcanvasOpened }">
      <div :class="headerStyle.custom_header == 1
        ? 'offcanvas-panel custom-offcanvas-panel w-100 h-100 bg-white'
        : 'offcanvas-panel w-100 h-100 bg-white'
        ">
        <div :class="headerStyle.custom_header == 1
          ? 'custom-offcanvas-header offcanvas-header position-relative'
          : 'offcanvas-header position-relative'
          ">
          <!-- Offcanvas Close -->
          <span class="offcanvas-close d-inline-flex align-items-center justify-content-center position-absolute"
            @click="toggleOffcanvas">
            <base-icon-svg name="close" />
          </span>
          <!-- End Offcanvas Close -->

          <template v-if="mode == 'dark'">
            <the-logo :header-logo-style="headerLogoStyle" :logo="siteProperties.logo_dark"
              :title="siteProperties.site_name" v-if="siteProperties.logo_dark" />
            <h2 class="site-title mb-0" v-else>
              <a href="/">{{ siteProperties.site_name }}</a>
            </h2>
          </template>
          <template v-else>
            <the-logo :header-logo-style="headerLogoStyle" :logo="siteProperties.logo" :title="siteProperties.site_name"
              v-if="siteProperties.logo" />
            <h2 class="site-title mb-0" v-else>
              <a href="/">{{ siteProperties.site_name }}</a>
            </h2>
          </template>
        </div>

        <div class="offcanvas-content bg-white">
          <mega-menu v-if="!dataLoading && !isSticky" :isMobile="true" :mega-categories="megaCategories" />

          <div class="offcanvas-menu px-3">
            <ul class="list-unstyled mb-0">
              <template v-for="(item, index) in menuItems">
                <menu-item v-if="!item.submenu" :key="`item-${index}`" :item="item" off-canvas
                  :header-menu-style="headerMenuStyle" />

                <menu-dropdown v-else :key="`group-${index}`" :item="item" off-canvas
                  :open-group="isGroupActive(item, $route.fullPath)" :header-menu-style="headerMenuStyle" />
              </template>
            </ul>
          </div>

          <div v-if="headerStyle && headerStyle.header_bot_email_text !== ''" class="contact-info">
            <a :href="'tel:' + headerStyle.header_bot_email_text" class="d-flex align-items-center"
              v-if="headerStyle.header_bot_email_text_icon == 'phone'">
              <span class="material-icons mx-1">phone_enabled</span>
              <span class="text-color-white">{{ headerStyle.header_bot_email_text }}</span>
            </a>

            <a :href="'mailto:' + headerStyle.header_bot_email_text" class="d-flex align-items-center"
              v-if="headerStyle.header_bot_email_text_icon == 'email'">
              <span class="material-icons mx-1">email</span>
              <span class="text-color-white">{{ headerStyle.header_bot_email_text }}</span>
            </a>
          </div>


        </div>
      </div>
    </div>
  </div>
  <!-- End Offcanvas -->
</template>

<script>
import MegaMenu from "@/components/menu/MegaMenu.vue";
import MenuItem from "./MenuItem.vue";
import MenuDropdown from "./MenuDropdown.vue";
export default {
  components: {
    MenuItem,
    MenuDropdown,
    MegaMenu
  },
  props: {
    siteProperties: {
      type: Object,
      required: false,
    },
    megaCategories: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    userInfo: {
      type: Object,
      default: null,
    },
    menuItems: {
      type: Array,
      required: true,
    },
    headerMenuStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    mode: {
      type: String,
      required: false,
    },
    headerStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      isOffcanvasOpened: false,
    };
  },
  computed: {
    isGroupActive() {
      return (item, path) => {
        let openGroup = false;
        const func = (item) => {
          if (item.submenu) {
            item.submenu.forEach((item) => {
              if (path === item.url) {
                openGroup = true;
              } else if (item.submenu) {
                func(item);
              }
            });
          }
        };
        func(item, path);
        return openGroup;
      };
    },
  },
  methods: {
    toggleOffcanvas() {
      this.isOffcanvasOpened = !this.isOffcanvasOpened;
      document.body.classList.toggle("offcanvas-oppened");
    },
  },
};
</script>

<style lang="scss" scoped>
.contact-info {
  margin: 20px 10px;
}
</style>
