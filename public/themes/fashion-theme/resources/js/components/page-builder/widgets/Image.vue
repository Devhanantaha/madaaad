<template>
  <div
    v-if="notLink()"
    :class="
      properties.title_on_hover && properties.title_on_hover == '1'
        ? 'hover'
        : ''
    "
  >
    <v-lazy-image :src="imageSrc()" alt="Image" />
    <h5 v-if="properties.title_on_hover && properties.title_on_hover == '1'">
      {{ properties["title_text_t_"] }}
    </h5>
  </div>

  <a
    :href="imageLink()"
    :target="imageTarget()"
    :class="
      properties.title_on_hover && properties.title_on_hover == '1'
        ? 'hover'
        : ''
    "
    v-else
  >
    <v-lazy-image :src="imageSrc()" alt="Image" />
    <h5 v-if="properties.title_on_hover && properties.title_on_hover == '1'">
      {{ properties["title_text_t_"] }}
    </h5>
  </a>
</template>

<script>
import VLazyImage from "v-lazy-image";

export default {
  name: "Image",

  components: {
    "v-lazy-image": VLazyImage,
  },

  props: {
    properties: {
      type: Object,
      default: {},
    },
  },

  methods: {
    /**
     * Image Link
     */
    notLink() {
      return (
        !this.properties["link"] ||
        (this.properties["link"] && this.properties["link"] == "none")
      );
    },

    /**
     * Image Src
     */
    imageSrc() {
      return this.properties["widget_image"] ?? "";
    },

    /**
     * Image Link
     */
    imageLink() {
      return this.properties["link"] == "media_file"
        ? this.properties["widget_image"]
        : this.properties["link_url"];
    },

    /**
     * Image Target
     */
    imageTarget() {
      return this.properties["link"] == "custom_url" &&
        this.properties["new_window"] == "1"
        ? "_blank"
        : "_self";
    },
  },
};
</script>
<style lang="scss" scoped>
@import "../../../assets/sass/00-abstracts/01-variables";
.hover {
  position: relative;
  display: block;
}
.hover h5 {
  position: absolute;
  z-index: 2;
  font-size: 18px;
  top: 40%;
  left: 50%;
  -webkit-transform: translate(-50%, -50%);
  -ms-transform: translate(-50%, -50%);
  transform: translate(-50%, -50%);
  opacity: 0;
  color: #fff;
  width: 100%;
  text-align: center;
  transition: 0.3s ease-in-out;
}

.hover:after {
  position: absolute;
  content: "";
  background-color: $c1;
  opacity: 0;
  width: calc(100% - 10px);
  height: calc(100% - 10px);
  left: 5px;
  top: 5px;
  -webkit-transform: scaleY(0.3);
  -ms-transform: scaleY(0.3);
  transform: scaleY(0.3);
  -webkit-transform-origin: 0 0;
  -ms-transform-origin: 0 0;
  transform-origin: 0 0;
  transition: 0.3s ease-in-out;
}
.hover:hover:after {
  opacity: 0.8;
  -webkit-transform: scaleY(1);
  -ms-transform: scaleY(1);
  transform: scaleY(1);
}

.hover:hover h5 {
  opacity: 1;
  top: 50%;
}
</style>
