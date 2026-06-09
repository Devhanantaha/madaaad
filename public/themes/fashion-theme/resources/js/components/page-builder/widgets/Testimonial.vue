<template>
  <section class="testimonial-section home-page-section">
    <div class="px-3 px-sm-0">
      <swiper
        :modules="modules"
        slidesPerView="1"
        :loop="true"
        :centeredSlides="true"
        :pagination="{
          clickable: true,
        }"
        class="theme-slider-dots"
      >
        <swiper-slide
          v-for="(testimonial, index) in properties.testimonials"
          :key="`testimonial-${index}`"
        >
          <testimonial-card :testimonial="testimonial"></testimonial-card>
        </swiper-slide>
      </swiper>
    </div>
  </section>
</template>

<script>
import { defineAsyncComponent } from "vue";
import { Swiper, SwiperSlide } from "swiper/vue";
const TestimonialCard = defineAsyncComponent(() =>
  import("../../ui/TestimonialCard.vue")
);

import { Autoplay, Pagination } from "swiper/modules";

export default {
  name: "Testimonial",
  components: {
    Swiper,
    SwiperSlide,
    TestimonialCard,
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
};
</script>
<style scoped>
.home-page-section.testimonial-section {
  padding: 30px;
}
.swiper-pagination {
  justify-content: flex-start !important;
}
</style>
