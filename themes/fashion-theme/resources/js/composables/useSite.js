import { ref } from "vue";
import { fetchSiteProperties, fetchMegaCategories } from "@/api/site";
import { useStore } from "vuex";

export function useSite() {
    const store = useStore();
    const siteProperties = ref({});
    const languages = ref([]);
    const currencies = ref([]);
    const megaCategories = ref([]);
    const error = ref(null);
    const loading = ref(false);

    async function loadSiteProperties() {
        loading.value = true;
        try {
            const data = await fetchSiteProperties();
            if (data.success) {
                siteProperties.value = data.siteProperties;
                languages.value = data.languages;
                currencies.value = data.currencies;
                store.dispatch("siteSettings", data.site_settings);
                store.dispatch("siteProperties", data.siteProperties);
            }
        } catch (err) {
            error.value = err.message;
        } finally {
            loading.value = false;
        }
    }

    async function loadMegaCategories() {
        try {
            const data = await fetchMegaCategories();
            if (data.success) {
                megaCategories.value = data.data;
            }
        } catch (err) {
            megaCategories.value = [];
            error.value = err.message;
        }
    }

    return { siteProperties, languages, currencies, megaCategories, error, loading, loadSiteProperties, loadMegaCategories };
}
