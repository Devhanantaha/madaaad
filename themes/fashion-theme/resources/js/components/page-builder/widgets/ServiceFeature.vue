<template>
  <div class="row align-items-center">
    <div class="col-xl-3 col-sm-6" v-for="(item, index) in properties" :key="index">
      <!-- Delivery Process -->
      <div class="delivery-process">
        <div class="icon" v-if="item.image">
          <v-lazy-image :src="`${item.image.path}`" :alt="item.title" />
        </div>
        <div class="content">
          <h3>{{ item.title }}</h3>
          <p>{{ item.sub_title }}</p>
        </div>
      </div>
      <!-- End Delivery Process -->
    </div>
  </div>
</template>
<script>
import VLazyImage from "v-lazy-image";
export default {
  name: "ServiceFeature",
  components: {
    "v-lazy-image": VLazyImage,
  },
  props: {
    properties: {
      type: Object,
      default: {},
    },
  },
};
</script>
<style scoped lang="scss">
/* ************************
   05.12: Delivery Process
   ********************* */

.delivery-process {
  background-color: #fff;
  display: -webkit-box;
  display: -ms-flexbox;
  display: flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  padding: 15px;
  margin-bottom: 20px;
  border-radius: 5px;
}

.delivery-process .icon {
  margin-right: 15px;
  width: 80px;
  height: 80px;
  background-color: #ffffff;
  border-radius: 50%;
  display: -webkit-inline-box;
  display: -ms-inline-flexbox;
  display: inline-flex;
  -webkit-box-align: center;
  -ms-flex-align: center;
  align-items: center;
  -webkit-box-pack: center;
  -ms-flex-pack: center;
  justify-content: center;
  padding: 5px;
}

.content h3 {
  color: #111111;
  margin-bottom: 7px;
  letter-spacing: -0.5px;
  font-size: 20px;
}

.content p {
  font-size: 14px;
}
</style>
