<template>
  <div class="light-bg" v-if="pageLoading">
    <skeleton height="100vh" class="w-100 pt-20"></skeleton>
  </div>

  <!-- From Page Builder -->
  <builder-section v-if="active_pagebuilder && page.page_type == 'builder'" :page="page" :sections="page_section"
    :widgets="page_builder_widgets" @section-loaded="loaded" />

  <div class="pt-30 pt-lg-60 pb-60 light-bg" v-else-if="active_pagebuilder && page.page_type == 'default'">
    <div class="custom-container2">
      <div class="row">
        <div class="col-lg-12">
          <!-- Page Details -->
          <article class="post-details">
            <!-- Page Header -->
            <header class="entry-header">
              <div class="entry-thumbnail" v-if="page.page_image != null">
                <img :src="page.page_image" alt="" />
              </div>

              <h1 class="entry-title">
                {{ page.title }}
              </h1>
            </header>
            <!-- End Page Header -->

            <!-- Page Content -->
            <div class="entry-content mb-40" v-html="page.content"></div>
            <!-- End Page Content -->
          </article>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { defineAsyncComponent } from "vue";

const BuilderSection = defineAsyncComponent(() =>
  import("@/components/page-builder/BuilderSection.vue")
);

const axios = require("axios").default;
export default {
  name: "Home",
  components: {
    BuilderSection,
  },
  data() {
    return {
      pageLoading: true,
      page: {},
      page_section: {},
      active_pagebuilder: false,
      page_builder_widgets: {},
    };
  },
  mounted() {
    document.title = localStorage.getItem("site_title");
    this.getSections();
  },

  methods: {
    /**
     * Get active sections
     */
    getSections() {
      this.$store.dispatch("showPreloader", true);
      axios
        .get("/api/theme/fashion-theme/v1/active-home-page-sections")
        .then((response) => {
          if (response.data.success) {
            this.page = response.data.page ?? {};
            this.page_section = response.data.page_sections ?? {};
            this.active_pagebuilder = response.data.active_pagebuilder ?? false;
            this.page_builder_widgets =
              response.data.page_builder_widgets ?? {};
            if (!this.active_pagebuilder) {
              this.loaded();
            }
          }
        })
        .catch((error) => { });
    },
    /**
     * Make Preloader False
     */
    loaded() {
      this.$store.dispatch("showPreloader", false);
      this.pageLoading = false;
    },
  },
};
</script>
<style lang="scss">
.product-banner-overflow-auto {
  overflow: hidden;

  .swiper {
    overflow: initial;
  }
}
</style>
