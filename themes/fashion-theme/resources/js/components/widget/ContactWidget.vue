<template>
    <div class="widget widget_contact_info">
        <h3 :class="this.footerStyle.custom_footer == 1
            ? 'custom-title-style'
            : 'widget-title'
            ">
            {{ data.contact_info.widget_title }}
        </h3>
        <ul>
            <li v-if="data.contact_info.address != null || data.contact_info.address != ''">
                <base-icon-svg name="mapmarker" :width="15" :height="15" />
                <span>{{ data.contact_info.address }}</span>
            </li>
            <li v-if="data.contact_info.mail != null || data.contact_info.mail != ''">
                <base-icon-svg name="envelope" :width="15" :height="15" />
                <span>{{ data.contact_info.mail }}</span>
            </li>
            <li v-if="data.contact_info.mobile != null || data.contact_info.mobile != ''">
                <base-icon-svg name="phone" :width="15" :height="15" />
                <span>{{ data.contact_info.mobile }}</span>
            </li>
        </ul>
    </div>
    <ul class="widget nav share-list gap-2 widget align-items-center"
        v-if="data.social_links != null && data.social_links.length > 0">
        <li v-for="(item, index) in data.social_links" :key="`social-${index}`">
            <a class="social-icon btn-circle size-35" :href="item.social_icon_url" target="_blank">
                <i class="fa" :class="item.social_icon"></i>
            </a>
        </li>
    </ul>
</template>

<script>
export default {
    name: "ContactWidget",
    props: {
        data: {
            type: Object,
            required: false,
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
