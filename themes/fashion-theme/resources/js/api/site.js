import { http } from "@/api/http.js";

const headers = () => ({
    "Content-Type": "application/json",
    "Accept-Language": localStorage.getItem("locale") || "en",
});

export async function fetchSiteProperties() {
    const { data } = await http.get("/api/v1/ecommerce-core/site-properties", { headers: headers() });
    return data;
}

export async function fetchMegaCategories() {
    const { data } = await http.get("/api/v1/ecommerce-core/mega-categories", { headers: headers() });
    return data;
}

export async function fetchThemeStyle() {
    const { data } = await http.get("/api/theme/fashion-theme/v1/get-theme-style", { headers: headers() });
    return data;
}

export async function fetchFooterWidgets() {
    const { data } = await http.get("/api/theme/fashion-theme/v1/get-footer-widgets", { headers: headers() });
    return data;
}
