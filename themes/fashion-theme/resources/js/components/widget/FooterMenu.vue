<template>
  <!-- Widget Nav Menu -->
  <div class="widget widget_nav_menu">
    <template v-if="data != null">
      <h3 :class="this.footerStyle.custom_footer == 1
        ? 'custom-title-style'
        : 'widget-title'
        ">
        {{ data.widget_title }}
      </h3>

      <ul v-if="data.menu_items.length > 0">
        <li v-for="(item, index) in data.menu_items" :key="`menu-${index}`">
          <a :href="item.url">{{ item.name }}</a>
        </li>
      </ul>
    </template>
  </div>
  <!-- End Widget Nav Menu -->
</template>

<script>
export default {
  name: "FooterMenu",
  props: {
    title: {
      type: String,
      default: "Menu",
    },
    data: {
      type: Object,
      default: () => { },
    },
    titleStyleThree: {
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
    footerStyle: {
      type: Object,
      required: false,
      default: () => {
        return {};
      },
    },
  },
};
</script>
