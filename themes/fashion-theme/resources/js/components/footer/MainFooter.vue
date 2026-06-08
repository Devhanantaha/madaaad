<template>
    <div class="main-footer">
        <template v-for="(widget, index) in footerWidgets" :key="index">
            <div v-if="widget.widget === 'newsletter_widget'" class="footer-top c1-bg">
                <div class="custom-container2">
                    <div class="row justify-content-between">
                        <div class="col-12">
                            <newsletter-widget :data="widget" :styleTwo="true"
                                :subscriptionFormStyle=subscriptionFormStyle :footerStyle="{ custom_footer: 1 }" />
                        </div>
                    </div>
                </div>
            </div>
        </template>


        <div class="footer-middle" :class="footerStyle && footerStyle.custom_footer == 1
            ? 'custom-footer footer'
            : 'footer'">
            <div class="custom-container2">
                <div class="row justify-content-between">
                    <template v-for="(widget, index) in footerWidgets" :key="index">
                        <div class="col-lg-3 col-sm-6" v-if="widget.widget === 'site_info_widget'">
                            <site-info-widget :data="widget" :styleTwo="false" :footerStyle="{ custom_footer: 1 }" />
                        </div>
                        <div class="col-lg-2 com-sm-6" v-if="widget.widget === 'footer_menu'"
                            :key="`footer-menu-${index}`">
                            <footer-menu :data="widget" :styleTwo="false" :footerStyle="{ custom_footer: 1 }" />
                        </div>

                        <div class="col-lg-3 col-sm-6" v-if="widget.widget === 'contact_widget'">
                            <contact-widget :data="widget" :footerStyle="{ custom_footer: 1 }" />
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { defineAsyncComponent, reactive } from "vue";

const SiteInfoWidget = defineAsyncComponent(() =>
    import("@/components/widget/SiteInfoWidget.vue")
);

const NewsletterWidget = defineAsyncComponent(() =>
    import("@/components/widget/NewsletterWidget.vue")
);
const FooterMenu = defineAsyncComponent(() =>
    import("@/components/widget/FooterMenu.vue")
);

const ContactWidget = defineAsyncComponent(() =>
    import("@/components/widget/ContactWidget.vue")
);

export default {
    name: "MainFooter",
    components: {
        SiteInfoWidget,
        FooterMenu,
        NewsletterWidget,
        ContactWidget
    },

    props: {
        footerWidgets: {
            type: Array,
            required: false,
            default: () => {
                return [];
            },
        },
        dataLoading: {
            type: Boolean,
            required: true,
            default: false,
        },
        subscriptionFormStyle: {
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
    data() {
        return {
            menuSkeletons: ["100px", "70px", "130px", "90px", "100px"],
        };
    }
};
</script>