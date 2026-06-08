import { http } from "@/api/http.js";

export async function allMenuItemsAPI() {
    const { data } = await http.get("/api/theme/fashion-theme/v1/get-all-menus-for-ecommerce-home");
    return data;
}
