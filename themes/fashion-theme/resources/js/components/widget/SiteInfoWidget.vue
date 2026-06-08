<template>
  <div class="widget widget_contact_info">
    <template v-if="data.contact_info != null">
      <div class="site-info mb-3">
        <the-logo :header-logo-style="headerLogoStyle" :logo="siteProperties.logo" :title="siteProperties.site_name"
          v-if="siteProperties.logo_dark" />
        <h2 class="site-title mb-0" v-else>
          <a href="/">{{ siteProperties.site_name }}</a>
        </h2>
      </div>
      <p class="footer-site-intro">
        {{ data.contact_info.site_intro }}
      </p>
    </template>
    <div class="app-icons mt-3">
      <h5 class="mb-3"> {{ data.contact_info.app_download_title }} </h5>
      <div class="add-icons d-flex gap-2">
        <a :href="`${data.contact_info.play_store_link}`" target="_blank" v-if="data.contact_info.play_store_link">
          <img :src="`${data.contact_info.play_store_icon}`" alt="icon" class="app-icon">
        </a>

        <a :href="`${data.contact_info.app_store_link}`" target="_blank" v-if="data.contact_info.app_store_link">
          <img :src="`${data.contact_info.app_store_icon}`" alt="icon" class="app-icon">
        </a>

      </div>
    </div>
  </div>

</template>

<script>
import { mapState } from "vuex";
export default {
  name: "SiteInfoWidget",
  props: {
    data: {
      type: Object,
      required: true,
    },
    styleTwo: {
      type: Boolean,
      default: true,
    }
  },
  computed: mapState({
    siteProperties: (state) => state.siteProperties,
  }),
};
</script>
<style scoped>
.app-title {
  color: #fff;
}

.app-icon {
  width: 120px;
  height: 40px;
}
</style>
