<template>
  <div class="main-review-wrap" :class="{ style2: styleTwo }">
    <div class="review-wrapper" v-if="totalItems > 0">
      <div class="review-summary">
        <div class="d-flex flex-wrap">
          <div class="seller-review mb-4 mb-md-0 me-md-5">
            <div class="summary">
              <div class="score">
                <span class="score-average">{{
                  reviewSummary.avg_review
                  }}</span><span class="score-max">/5</span>
              </div>
              <div class="average">
                <div class="container-star">
                  <div class="star-rating">
                    <div class="product-rating-wrapper">
                      <i class="fs-34" :data-star="reviewSummary.avg_review" :title="reviewSummary.avg_review"></i>
                    </div>
                  </div>
                </div>
              </div>
              <div class="count">
                {{ reviewSummary.total_reviews }}
                {{ $t("Ratings") }}
              </div>
            </div>
          </div>
          <div class="seller-ratings">
            <ul class="list-unstyled mb-0">
              <li>
                <div class="container-star">
                  <span v-for="i in 5" :key="i" class="material-icons">
                    star
                  </span>
                </div>
                <span class="progress-wrap">
                  <div class="pdp-review-progress">
                    <div class="bar" :style="{ width: five_star_length + '%' }"></div>
                  </div>
                </span>
                <span class="percent"> {{ reviewSummary.five }}</span>
              </li>
              <li>
                <div class="container-star">
                  <span v-for="i in 5" :key="i" class="material-icons" :class="{ disable: i === 5 || i === 5 }">
                    star
                  </span>
                </div>
                <span class="progress-wrap">
                  <div class="pdp-review-progress">
                    <div class="bar" :style="{ width: four_star_length + '%' }"></div>
                  </div>
                </span>
                <span class="percent">
                  {{ reviewSummary.four }}
                </span>
              </li>
              <li>
                <div class="container-star">
                  <span v-for="i in 5" :key="i" class="material-icons" :class="{ disable: i === 5 || i === 4 }">
                    star
                  </span>
                </div>
                <span class="progress-wrap">
                  <div class="pdp-review-progress">
                    <div class="bar" :style="{ width: three_star_length + '%' }"></div>
                  </div>
                </span>
                <span class="percent">
                  {{ reviewSummary.three }}
                </span>
              </li>
              <li>
                <div class="container-star">
                  <span v-for="i in 5" :key="i" class="material-icons"
                    :class="{ disable: i === 5 || i === 4 || i === 3 }">
                    star
                  </span>
                </div>
                <span class="progress-wrap">
                  <div class="pdp-review-progress">
                    <div class="bar" :style="{ width: two_star_length + '%' }"></div>
                  </div>
                </span>
                <span class="percent">
                  {{ reviewSummary.two }}
                </span>
              </li>
              <li>
                <div class="container-star">
                  <span v-for="i in 5" :key="i" class="material-icons" :class="{
                    disable: i === 5 || i === 4 || i === 3 || i === 2,
                  }">
                    star
                  </span>
                </div>
                <span class="progress-wrap">
                  <div class="pdp-review-progress">
                    <div class="bar" :style="{ width: one_star_length + '%' }"></div>
                  </div>
                </span>
                <span class="percent">
                  {{ reviewSummary.one }}
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <hr>
      <div class="review-header d-flex flex-wrap align-items-center justify-content-between">
        <h4 class="mb-0">
          {{ $t("Buyer Ratings and Reviews") }} ({{ totalItems }})
        </h4>

        <div class="d-flex justify-content-lg-end mt-2 mt-md-0">
          <ul class="filter-wrap list-unstyled inline-links d-flex flex-wrap align-items-center">
            <li class="d-none d-lg-block me-2">
              <strong>{{ $t("Sort By") }} :</strong>
            </li>
            <li>
              <select class="theme-input-style" v-model="sorting" @change="sortingReview">
                <option v-for="sortItem in sortLists" :key="sortItem.value" :value="sortItem.value">
                  {{ sortItem.name }}
                </option>
              </select>
            </li>
          </ul>
        </div>
      </div>
      <hr>
      <div class="review-body">
        <!-- Single Review -->
        <div class="single-review" v-for="(review, index) in reviews" :key="index">
          <div class="single-review-inner">
            <div class="review-avatar rounded-circle border">
              <img :src="review.customer.image" :alt="`${review.customer.name}`" />
            </div>
            <div class="review-content w-100">
              <div class="review-header border-0 d-flex justify-content-between mb-0">
                <div class="info">
                  <h5 class="review-profile-name">
                    {{ review.customer.name }}
                  </h5>
                  <p class="color-family" v-if="review.variant">
                    {{ review.variant }}
                  </p>
                  <div class="star-rating" v-if="config.enable_product_star_rating == enums.status.ACTIVE">
                    <div class="product-rating-wrapper">
                      <i :data-star="review.rating" :title="review.rating"></i>
                    </div>
                  </div>

                  <div class="d-flex flex-wrap mb-2">
                    <span class="d-inline-flex align-items-center" v-if="
                      config.verified_customer_on_product_review ==
                      enums.status.ACTIVE
                    ">
                      <img class="verifyImg me-2"
                        src="/public/web-assets/frontend/themes/fashion-theme/assets/img/varified_purchase.png" />
                      <span class="verify">{{ $t("Verified") }}</span>
                    </span>
                  </div>
                </div>
                <div class="time">
                  <p class="mb-0 fz-12">{{ review.time }}</p>
                </div>
              </div>
              <p class="review-text">
                {{ review.review }}
              </p>
              <product-review-images v-if="review.images != null" :images="review.images"></product-review-images>
            </div>
          </div>
        </div>
        <!-- End Single Review -->

        <div class="row">
          <div class="col-12">
            <!-- Pagination -->
            <pagination :options="paginationOptions" v-model="currentPage" :records="totalItems" :per-page="perPage"
              @paginate="getProductReviews" />
            <!-- End Pagination -->
          </div>
        </div>
      </div>
    </div>
    <div class="no-review-wrapper text-center py-5" v-else>
      <h2>{{ $t("No Review Yet") }}</h2>
      <p>
        {{ $t("There are currently no ratings or reviews for this product") }}
      </p>
    </div>
  </div>
</template>

<script>
import ProductReviewImages from "./ProductReviewImages.vue";
import Pagination from "v-pagination-3";
import axios from "axios";
import enums from "../../enums/enums";
export default {
  name: "ProductReview",
  components: {
    Pagination,
    ProductReviewImages,
  },
  props: {
    styleTwo: {
      type: Boolean,
      default: false,
      required: false,
    },
    config: {
      type: Object,
      required: false,
    },
    productId: {
      type: Number,
      required: true,
    },
    reviewSummary: {
      type: Object,
      required: false,
    },
  },
  data() {
    return {
      toggler: true,
      enums: enums,
      sortLists: [
        {
          name: this.$t("Recent"),
          value: "DESC",
        },
        {
          name: this.$t("Rating: High to Low"),
          value: "RDESC",
        },
        {
          name: this.$t("Rating: Low to High"),
          value: "RASC",
        },
      ],
      reviews: [],
      currentPage: 1,
      perPage: 3,
      totalItems: 0,
      sorting: "DESC",
      paginationOptions: {
        chunk: 3,
        theme: "bootstrap4",
        hideCount: true,
      },
    };
  },
  mounted() {
    this.getProductReviews();
  },
  methods: {
    /**
     * Will get product reviews
     */
    getProductReviews() {
      axios
        .post("/api/v1/ecommerce-core/get-product-reviews", {
          page: this.currentPage,
          perPage: this.perPage,
          sorting: this.sorting,
          product_id: this.productId,
        })
        .then((response) => {
          if (response.data.success) {
            this.reviews = response.data.data;
            this.totalItems = response.data.meta.total;
          } else {
            this.reviews = [];
            this.totalItems = 0;
          }
        })
        .catch((error) => {
          this.reviews = [];
          this.totalItems = 0;
        });
    },
    /**
     * Will shorting review
     *
     */
    sortingReview() {
      this.currentPage = 1;
      this.getProductReviews();
    },
  },
};
</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.review-header {
  h3 {
    font-weight: 500;
  }
}

.single-review {
  &:not(:last-child) {
    border-bottom: 1px solid #f2f2f2;
    margin-bottom: 30px;
    padding-bottom: 20px;
  }

  &-inner {
    display: flex;

    @media only screen and (max-width: 575px) {
      flex-direction: column;
    }
  }

  .review-avatar {
    margin-right: 15px;
    width: 40px;
    min-width: 40px;
    height: 40px;

    @media only screen and (max-width: 575px) {
      margin: 0;
      margin-bottom: 15px;
    }

    img {
      width: 100%;
      border-radius: 50%;
    }
  }
}

.review-profile-name {
  margin-bottom: 0;
  font-size: 15px;
  font-weight: 500;
}


p {
  margin-bottom: 14px;
}

.review-images {
  display: grid;
  grid-template-columns: repeat(auto-fill, 100px);
  grid-gap: 10px;
}

.verifyImg {
  min-width: 20px;
  height: 20px;
}

.like-dislike {

  i,
  .material-icons {
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .like,
  .dislike {
    display: flex;
    align-items: center;

    &.active {

      i,
      .material-icons {
        color: $c1;
      }
    }
  }
}

.seller-reply-wrapper {
  position: relative;
  background-color: #f7f8fa;
  padding: 20px;
  margin-top: 20px;
  margin-left: 55px;

  @media only screen and (max-width: 575px) {
    margin-left: 15px;
  }

  &:before {
    content: " ";
    position: absolute;
    top: -15px;
    left: 30px;
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 16px solid #f8f8f8;
  }
}
</style>
