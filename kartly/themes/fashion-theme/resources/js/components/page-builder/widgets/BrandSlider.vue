<template>
  <!-- Banners -->
  <section class="brand-slider-section home-page-section">
    <div class="px-3 px-sm-0">
      <swiper v-if="properties.brands.length && properties.style == 'slider'" :modules="modules" :loop="true" :autoplay="{
        delay: 2500,
        disableOnInteraction: false,
        pauseOnMouseEnter: true,
      }" @onSlideChange="onSlideChange" class="category-slider category-full-width theme-slider-dots" :breakpoints="{
          '0': {
            slidesPerView: 2,
            spaceBetween: 16,
          },
          '768': {
            slidesPerView: 3,
            spaceBetween: 20,
          },
          '1024': {
            slidesPerView: 4,
            spaceBetween: 20,
          },
          '1440': {
            slidesPerView: 5,
            spaceBetween: 20,
          },
        }">
        <swiper-slide v-for="(brand, index) in properties.brands" :key="`brand-${index}`">
          <brand-card :brand="brand" />
        </swiper-slide>
      </swiper>
      <div class="row" v-else>
        <div v-for="(brand, index) in properties.brands" :key="`brand-${index}`"
          class="col-6 col-sm-6 col-md-4 col-lg-3 col-xl-2">
          <brand-card :brand="brand" />
        </div>
      </div>
    </div>
  </section>
  <!-- End Categories -->
</template>

<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
const BrandCard = defineAsyncComponent(() => import("../../ui/BrandCard.vue"));

import { Autoplay, Pagination } from "swiper/modules";

export default {
  name: "BrandSlider",
  components: {
    BrandCard,
    Swiper,
    SwiperSlide,
  },
  setup() {
    return {
      modules: [Autoplay, Pagination],
    };
  },
  props: {
    properties: {
      type: Object,
      default: {},
    },
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
