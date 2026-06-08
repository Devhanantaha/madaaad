<template>
  <div class="testimonial-content">
    <div class="quote">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="32"
        height="27"
        viewBox="0 0 32 27"
        class="svg replaced-svg"
      >
        <path
          id="quote"
          d="M3543.667,27V13.5A13.431,13.431,0,0,1,3557,0V4.5a8.955,8.955,0,0,0-8.889,9H3557V27ZM3525,27V13.5A13.431,13.431,0,0,1,3538.333,0V4.5a8.955,8.955,0,0,0-8.889,9h8.889V27Z"
          transform="translate(-3525)"
          fill="#ef2543"
        ></path>
      </svg>
    </div>
    <p>
      {{ testimonial.review }}
    </p>

    <div class="testimonial-profile mt-15">
      <div class="img">
        <img :src="`${testimonial.user_image}`" class="img-70" alt="img" />
      </div>
      <div class="content">
        <h4>{{ testimonial.user_name }}</h4>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "TestimonialCard",
  props: {
    testimonial: {
      type: Object,
      default: {},
    },
  },
};
</script>
<style scoped>
.testimonial-content .quote {
  margin-bottom: 20px;
}
.testimonial-content .testimonial-profile {
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
}
.testimonial-content .testimonial-profile .img {
  margin-right: 15px;
}
</style>
