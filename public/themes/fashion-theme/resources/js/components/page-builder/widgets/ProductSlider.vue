<template>
  <section class="product-slider-section home-page-section">
    <div class="page-builder-widget widget widget_special-offer">
      <h3 class="mb-15 section-title" v-if="properties.header_title_t_ && properties.header_title_t_ != null">
        {{ properties.header_title_t_ }}
      </h3>
      <div :class="properties.header_title_t_ && properties.header_title_t_ != null
        ? 'widget_content'
        : 'widget_content'
        ">
        <swiper slidesPerView="1" :grid="{
          rows: row_count,
          fill: 'row',
        }" :modules="modules" spaceBetween="1" :loop="true" :autoplay="{
          delay: 3000,
          disableOnInteraction: false,
        }" class="product-slider-swiper">
          <swiper-slide v-for="(item, index) in products" :key="`slide-${index}`">
            <single-product :item="item" listStyle />
          </swiper-slide>
        </swiper>
      </div>
    </div>
  </section>
</template>
<script>
import { defineAsyncComponent } from "vue";
import "swiper/css";
import "swiper/css/grid";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Grid, Autoplay, Pagination } from "swiper/modules";

const SingleProduct = defineAsyncComponent(() =>
  import("../../product/SingleProduct.vue")
);

export default {
  components: {
    Swiper,
    SwiperSlide,
    SingleProduct,
  },
  props: {
    properties: {
      type: Object,
      default: {},
    },
  },
  setup() {
    return {
      modules: [Grid, Autoplay, Pagination],
    };
  },
  data() {
    return {
      products: [],
      row_count:
        this.properties.row_count != null
          ? parseInt(this.properties.row_count)
          : 1,
    };
  },
  mounted() {
    this.products =
      this.properties["products"] && this.properties["products"]["data"]
        ? this.properties["products"]["data"]
        : [];
  },
};
</script>
<style scoped>
.swiper {
  width: 100%;
  height: 100%;
  margin-left: auto;
  margin-right: auto;
}

.multi--vendor-sidebar .widget:not(:last-child) {
  margin-bottom: 30px;
}

.widget:not(:last-child) {
  margin-bottom: 60px;
}

.widget .widget_title.title-dark {
  font-size: 18px !important;
  background-color: #22303f;
  font-weight: 600;
  color: #ffffff;
  padding: 15px 10px;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}

.section-title {
  font-size: 24px;
}
</style>
