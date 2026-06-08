<template>
  <!-- Widget Newsletter -->
  <div class="widget mb-0 widget_newsletter" :class="{
    'no-style': noStyle,
    'style--two': styleTwo,
    'style--three': styleThree,
  }">
    <template v-if="data != null">
      <div class="newsletter-wrapper d-flex justify-content-between align-items-center">
        <div class="intro">
          <h2 :class="footerStyle.custom_footer == 1
            ? 'custom-title-style'
            : 'widget-title'
            ">
            {{ data.newsletter_info.widget_title }}
          </h2>
          <p class="mb-2">{{ data.newsletter_info.newsletter_short_desc }}</p>

        </div>
        <div class="w-50 newsletter-form">
          <template v-if="styleTwo">
            <div class="input-group title-font">
              <input v-model="email" :class="this.subscriptionFormStyle.custom_subscription == 1
                ? 'form-control mb-15 custom-input-style'
                : 'form-control mb-15'
                " type="email" v-bind:placeholder="$t('Enter your email')" />
              <button @click.preventDefault="subscribe" type="submit" :class="this.subscriptionFormStyle.custom_subscription == 1
                ? 'custom-subs-btn'
                : 'btn'
                ">
                {{ $t("Subscribe") }}
              </button>
            </div>
          </template>
        </div>
      </div>

    </template>
  </div>
</template>
<script>
const axios = require("axios").default;
export default {
  name: "NewsletterWidget",
  props: {
    styleTwo: {
      type: Boolean,
      default: true,
    },
    styleThree: {
      type: Boolean,
      default: false,
    },
    footerStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    subscriptionFormStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
    data: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      email: "",
    };
  },
  methods: {
    subscribe() {
      if (this.email == "") {
        this.$toast.error("Please enter your email");
        return;
      }
      axios
        .post("/api/theme/fashion-theme/v1/newsletter-store", {
          email: this.email,
        })
        .then((response) => {
          if (response.data.success) {
            this.$toast.success(response.data.message);
            this.email = "";
          } else {
            this.$toast.error("Something went wrong. Please try again");
          }
        })
        .catch((error) => {
          this.$toast.error("Something went wrong. Please try again");
        });
    },
  },
};
</script>
<style scoped>
.custom-title-style {
  font-size: 2rem !important;
  color: #fff;
  margin-bottom: 5px;
}
</style>
