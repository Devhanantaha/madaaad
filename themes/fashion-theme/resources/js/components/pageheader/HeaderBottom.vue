<template>
  <!-- Header Bottom -->
  <div :class="this.headerStyle.custom_header == 1
    ? 'custom-header-bottom header-bottom header-border'
    : 'header-bottom bg-white header-border'
    ">
    <div class="custom-container2">
      <div class="row position-relative align-items-center justify-content-between">
        <div class="col-xl-3 col-6 position-static d-flex align-items-center">
          <!-- MegaMenu -->
          <mega-menu v-if="!dataLoading && !isSticky" :mega-categories="megaCategories" />

          <div v-if="dataLoading" class="header-bottom-preloader-item">
            <skeleton height="50px" border-radius="0px" width="100%">
            </skeleton>
          </div>
          <!-- End MegaMenu -->
        </div>
        <div class="col-lg-7 align-items-center d-flex justify-content-start position-static">
          <!-- Menu -->
          <div v-if="dataLoading">
            <ul class="nav-horizontal desktop">
              <li v-for="(item, index) in menuSkeletons" :key="index">
                <skeleton height="12px" border-radius="10px" :width="item">
                </skeleton>
              </li>
            </ul>
          </div>

          <HorizontalMenu v-if="!dataLoading" :menu-items="menuItems" :header-menu-style="headerMenuStyle" />
          <!-- End Menu -->
        </div>
        <div class="col-lg-2 position-static d-flex justify-content-end align-items-center">
          <div v-if="dataLoading" class="header-bottom-preloader-item">
            <skeleton height="10px" border-radius="5px" width="100%">
            </skeleton>
          </div>

          <div v-if="!dataLoading && headerStyle && headerStyle.header_bot_email_text !== ''"
            class="header-btn-group__item">

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
  <!-- End Header Bottom -->
</template>
<script>

import MegaMenu from "@/components/menu/MegaMenu.vue";
import HorizontalMenu from "@/components/menu/HorizontalMenu.vue";
export default {
  name: "HeaderBottom",
  components: {
    MegaMenu,
    HorizontalMenu,
  },

  props: {
    megaCategories: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    menuItems: {
      type: Array,
      required: false,
      default: () => {
        return [];
      },
    },
    headerStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    headerMenuStyle: {
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
      menuSkeletons: ["100px", "70px", "130px", "90px", "100px"],
    };
  },
};
</script>