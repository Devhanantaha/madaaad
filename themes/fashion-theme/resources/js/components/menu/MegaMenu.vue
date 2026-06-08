<template>
  <!-- MegaMenu Wrapper -->
  <div class="mega-menu-wrapper p-0" ref="catDropdown">
    <!-- MegaMenu Button -->
    <button class="btn w-100 text-left text-uppercase d-flex align-items-center font-weight-medium rounded-0"
      :class="{ 'px-2': isMobile }" @click="toggleMegaMenu">
      <base-icon-svg class="mr-15" name="category" :height="5.5" :width="16" />
      {{ $t("All Categories") }}
    </button>
    <!-- End MegaMenu Button -->

    <!-- MegaMenu -->
    <div class="megamenu mb-4" :class="{ 'show position-relative': isMobile && isShow, 'show': !isMobile && isShow }">

      <!--Mobile-->
      <div class="mega-menu-mobile-content offcanvas-menu" v-if="isMobile">
        <ul class="list-unstyled mb-0">
          <template v-for="(item, index) in megaCategories">
            <menu-item v-if="item.childs.data.length < 1" :key="`item-${index}`" :item="item" :off-canvas="false"
              :header-menu-style="headerMenuStyle" />

            <menu-dropdown v-else :key="`group-${index}`" :item="item" :off-canvas="false" :isMegaCategory="true"
              :open-group="isGroupActive(item, $route.fullPath)" :header-menu-style="headerMenuStyle" />
          </template>
        </ul>
      </div>

      <!--Desktop-->
      <div class="megamenu-content" v-if="!isMobile">
        <div class="row gx-0 position-relative">
          <div class="cat-dropdown light-bg position-static box-shadow">

            <!-- Categories -->
            <ul class="list-unstyled mb-0 categories">
              <li v-for="(cat, index) in megaCategories" :key="`cat-${index}`" class="category-link">
                <router-link :to="`/products/category/${cat.slug}`" class="custom-menu">{{ cat.name }}</router-link>

                <!-- Sub Category Menu -->
                <div v-if="cat.childs.data" class="sub-categories box-shadow">
                  <div class="row gx-0">
                    <div :class="cat.offerInfo ? 'col-lg-10' : 'col-12'">
                      <div class="row">
                        <div v-for="(subCatGroup, i) in cat.childs.data" :key="`subCatGroup-${i}`"
                          class="col-lg-3 mt-2 mb-2">
                          <!-- Sub Category Group -->
                          <div class="sub-category-group">
                            <h6 class="sub-category-title">
                              <router-link :to="`/products/category/${subCatGroup.slug}`">
                                {{ subCatGroup.name }}
                              </router-link>
                            </h6>

                            <ul class="sub-category list-unstyled mb-0">
                              <li v-for="(item, j) in subCatGroup.childs.data" :key="`item-${j}`"
                                class="sub-category-link">
                                <router-link :to="`/products/category/${item.slug}`">
                                  {{ item.name }}
                                </router-link>
                              </li>
                            </ul>
                          </div>
                          <!-- End Sub Category Group -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End Sub Category Menu -->
              </li>
            </ul>
            <!-- End Categories -->
          </div>
        </div>
      </div>
    </div>
    <!-- End MegaMenu -->
  </div>
  <!-- End MegaMenu Wrapper -->
</template>

<script>
import MenuItem from "./MenuItem.vue";
import MenuDropdown from "./MenuDropdown.vue";
export default {
  name: "MegaMenu",
  components: {
    MenuItem,
    MenuDropdown,
  },
  props: {
    megaCategories: {
      type: Array,
      required: true,
    },
    isMobile: {
      type: Boolean,
      required: false,
      default: false,
    },
  },
  data() {
    return {
      isShow: false,
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
  mounted() {
    document.addEventListener("click", this.close);
  },
  watch: {
    $route(to, from) {
      this.isShow = false;
    },
  },

  methods: {
    toggleMegaMenu() {
      this.isShow = !this.isShow;
    },
    close(e) {
      let el = this.$refs.catDropdown;
      let target = e.target;
      if (el !== target && !el?.contains(target)) {
        this.isShow = false;
      }
    },
  },
  beforeDestroy() {
    document.removeEventListener("click", this.close);
  },
};
</script>
<style scoped>
.text-color-white {
  color: #fff;
}
</style>
