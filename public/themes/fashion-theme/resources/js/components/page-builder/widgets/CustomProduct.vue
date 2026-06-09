<template>
  <section class="collection-section home-page-section">
    <!--Header -->
    <div class="section-header d-flex align-items-center flex-wrap gap-2 justify-content-between mb-15 "
      v-if="properties.section_title_t_ || properties.next_previous == '1'">
      <div class="section-title mb-0" v-if="
        properties.section_title_t_ && properties.section_title_t_ != null
      ">
        <h3 class="mb-0 section-title">{{ properties.section_title_t_ }}</h3>
      </div>
      <div class="btn-area" v-if="
        properties.section_btn_title_t_ &&
        properties.section_btn_title_t_ != null
      ">
        <a :href="properties.button_link" class="btn">
          {{ properties.section_btn_title_t_ }}
        </a>
      </div>
    </div>
    <!--End Header-->
    <div :class="properties.featured_item_position &&
      properties.featured_item_position == 'end'
      ? 'row section-body flex-row-reverse'
      : 'row section-body'
      ">
      <div class="col-xxl-4 col-xl-5 col-lg-6 col-md-4 col-sm-5 mb-5 mb-sm-0 featured_item d-none d-lg-block" v-if="
        properties.featured_item_status &&
        properties.featured_item_status == '1' &&
        featuredItem != null
      ">
        <single-product :item="featuredItem" :featured-image="properties.featured_product_image" featuredStyle />
      </div>
      <div :class="properties.featured_item_status &&
        properties.featured_item_status == '1' &&
        featuredItem != null
        ? 'col-xxl-8 col-xl-7 col-lg-6 col-md-8 col-sm-7 custom_product_list'
        : 'col-12 custom_product_list'
        ">
        <!---Swiper Slide Style-->
        <swiper v-if="products.length && properties.style == 'slider'" ref="swiperRef"
          :slidesPerView="slider_items.desktop" :grid="{
            rows: row_count,
            fill: 'row',
          }" :modules="modules" :spaceBetween="24" :autoplay="{
            delay: 2500,
            disableOnInteraction: false,
            pauseOnMouseEnter: true,
          }" :loop="true" :pagination="pagination" @onSlideChange="onSlideChange"
          class="product-grid-slider theme-slider-dots" :breakpoints="{
            '0': {
              slidesPerView: slider_items.mobile,
            },
            '768': {
              slidesPerView: slider_items.tab,
            },
            '1024': {
              slidesPerView: slider_items.desktop,
            },
          }">
          <swiper-slide v-for="(item, index) in products" :key="`slide-${index}`">
            <single-product :item="item" styleEight />
          </swiper-slide>
        </swiper>
        <!---End Swiper Slide Style-->
        <!--List Style-->
        <div class="row custom-product-list" v-else>
          <div v-for="(item, index) in products" :key="`product-${index}`" :class="product_column">
            <single-product :item="item" styleEight />
          </div>
        </div>
        <!--End List Style-->
      </div>
    </div>
  </section>
</template>

<script>
import { defineAsyncComponent } from "vue";
import { ref } from "vue";
const SingleProduct = defineAsyncComponent(() =>
  import("../../product/SingleProduct.vue")
);
import "swiper/css";
import "swiper/css/grid";
import { Swiper, SwiperSlide } from "swiper/vue";
import { Grid, Autoplay, Pagination } from "swiper/modules";

export default {
  name: "CustomProduct",
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
    const swiperRef = ref(null);
    return {
      modules: [Grid, Autoplay, Pagination],
      swiperRef,
    };
  },

  data() {
    return {
      products: [],
      slider_items: {
        mobile: 2,
        tab: 3,
        desktop: 6,
      },
      row_count:
        this.properties.row_count != null ? this.properties.row_count : 1,
      product_column: "col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2",
      pagination: { clickable: true },
      featuredItem: null,
    };
  },

  mounted() {
    this.products =
      this.properties["products"] && this.properties["products"]["data"]
        ? this.properties["products"]["data"]
        : [];

    let featured_list =
      this.properties["featured_product"] &&
        this.properties["featured_product"]["data"]
        ? this.properties["featured_product"]["data"]
        : [];

    this.featuredItem = featured_list.length > 0 ? featured_list[0] : null;
    this.product_column = this.properties["column"] ?? this.product_column;
    this.pagination = this.properties["pagination"] ? this.pagination : false;

    this.slider_items.mobile = this.properties["slide_item_sm"]
      ? parseInt(this.properties["slide_item_sm"])
      : this.slider_items.mobile;
    this.slider_items.tab = this.properties["slide_item_md"]
      ? parseInt(this.properties["slide_item_md"])
      : this.slider_items.tab;
    this.slider_items.desktop = this.properties["slide_item_lg"]
      ? parseInt(this.properties["slide_item_lg"])
      : this.slider_items.desktop;
  },

  methods: {
    onSlideChange(swiper) {
      if (swiper.activeIndex === swiper.slides.length - 1) {
        swiper.slideTo(0);
      }
    },
  },
};
</script>
<style scoped lang="scss">
.section-title {
  font-size: 24px;
}
</style>
