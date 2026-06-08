import { ref } from "vue";
import { fetchThemeStyle, fetchFooterWidgets } from "@/api/site";

export function useTheme() {
    const headerStyle = ref({});
    const headerTopOptions = ref({});
    const headerLogoStyle = ref({});
    const headerMenuStyle = ref({});
    const footerStyle = ref({});
    const socialStyle = ref({});
    const subscriptionFormStyle = ref({});
    const topBarBannerProps = ref(null);
    const visibleTopBarBanner = ref(false);
    const gdprProps = ref(null);
    const visibleGdpr = ref(false);
    const websitePopupProps = ref(null);
    const footerWidgets = ref({});
    const visibleWebsitePopup = ref(false);
    const visibleDarkLightSwitcher = ref(false);

    async function loadThemeStyle() {
        const data = await fetchThemeStyle();
        if (data.success) {
            headerStyle.value = data.headerOptions;
            headerLogoStyle.value = data.headerLogoStyles;
            headerMenuStyle.value = data.headerMenuStyle;
            footerStyle.value = data.footerStyle;
            socialStyle.value = data.socialStyle;
            subscriptionFormStyle.value = data.subscriptionFormStyle;
            topBarBannerProps.value = data.top_bar_banner_properties;
            headerTopOptions.value = data.headerTopOptions;

            //set topbar banner value
            if (data.top_bar_banner_properties != null && data.top_bar_banner_properties.topbar_banner_status == 1) {
                let banner_cookie = null;
                const cookies = document.cookie.split("; ");
                for (const cookie of cookies) {
                    const [name, value] = cookie.split("=");
                    if (name === "top_bar_banner") {
                        banner_cookie = value;
                    }
                }

                if (banner_cookie == null && banner_cookie != 1) {
                    visibleTopBarBanner.value = true;
                }
            }

            //set gdpr value
            if (data.gdpr_properties != null && data.gdpr_properties.gdpr_status == 1) {
                let gdpr_cooke_value = null;
                const cookies = document.cookie.split("; ");
                for (const cookie of cookies) {
                    const [name, value] = cookie.split("=");
                    if (name === "acceptCookies") {
                        gdpr_cooke_value = value;
                    }
                }

                if (gdpr_cooke_value == null && gdpr_cooke_value != 1) {
                    visibleGdpr.value = true;
                }
            }
            gdprProps.value = data.gdpr_properties;

            websitePopupProps.value = data.website_popup_properties;
            //set website popup value
            if (data.website_popup_properties != null && data.website_popup_properties.website_popup_status == 1) {
                let cooke_value = null;
                const cookies = document.cookie.split("; ");
                for (const cookie of cookies) {
                    const [name, value] = cookie.split("=");
                    if (name === "website_popup") {
                        cooke_value = value;
                    }
                }

                if (cooke_value == null && cooke_value != 1) {
                    visibleWebsitePopup.value = true;
                }
            }

            visibleDarkLightSwitcher.value = data.dark_light_status == 1 ? true : false;
        }
    }

    async function loadFooterWidgets() {
        const data = await fetchFooterWidgets();
        if (data.success) {
            footerWidgets.value = data.widget_options;
        }
    }

    function closePopupModal() {
        const now = new Date();
        const expires = new Date(now.getTime() + 2 * 60 * 60 * 1000);
        document.cookie = `website_popup=1; expires=${expires.toUTCString()}; path=/`;
        visibleWebsitePopup.value = false;
    }
    function closeTopBanner() {
        const now = new Date();
        const expires = new Date(now.getTime() + 2 * 60 * 60 * 1000);
        document.cookie = `top_bar_banner=1; expires=${expires.toUTCString()}; path=/`;
        visibleTopBarBanner.value = false;
    }

    function agreeGdpr() {
        const now = new Date();
        const expires = new Date(now.getTime() + 24 * 60 * 60 * 1000);
        document.cookie = `acceptCookies=1; expires=${expires.toUTCString()}; path=/`;
        visibleGdpr.value = false;
    }
    return { headerTopOptions, visibleDarkLightSwitcher, agreeGdpr, visibleGdpr, closeTopBanner, visibleTopBarBanner, visibleWebsitePopup, closePopupModal, headerStyle, headerLogoStyle, headerMenuStyle, footerStyle, socialStyle, subscriptionFormStyle, topBarBannerProps, gdprProps, websitePopupProps, footerWidgets, loadThemeStyle, loadFooterWidgets };
}
