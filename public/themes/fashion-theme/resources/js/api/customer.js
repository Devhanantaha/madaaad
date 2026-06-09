import { http } from "@/api/http.js";

export async function refreshCustomerAuthToken(params = {}) {
    const { data } = await http.get("/api/v1/ecommerce-core/auth/customer-refresh-auth", { params });
    return data;
}


export async function readNotificationAPI(notificationId) {
    const { data } = await http.post("api/v1/ecommerce-core/customer/mark-as-read-single-notification", { id: notificationId });
    return data;
}

export async function readNotificationMarkAsReadAPI() {
    const { data } = await http.get("/api/v1/ecommerce-core/customer/mark-as-read-all-notification");
    return data;
}

export async function customerLogoutAPI() {
    const { data } = await http.get("/api/v1/ecommerce-core/auth/customer-logout");
    return data;
}