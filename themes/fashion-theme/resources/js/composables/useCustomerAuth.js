import { ref, watchEffect } from "vue";
import { refreshCustomerAuthToken, readNotificationAPI, readNotificationMarkAsReadAPI, customerLogoutAPI } from "@/api/customer.js";
import { useStore } from "vuex";
import { useToast } from "vue-toast-notification";
import { useRouter } from "vue-router";
export function useCustomerAuth() {
    const loading = ref(false);
    const error = ref(null);
    const customerAuthData = ref(null);
    const notificationReadResponse = ref(null);
    const store = useStore();
    const $toast = useToast();
    const router = useRouter();
    async function customerLogout() {
        loading.value = true;
        error.value = null;
        try {
            const data = await customerLogoutAPI();
            if (data.success) {
                store.dispatch("customerLogout").then(() => {
                    $toast.success("Logged out successfully");

                    router.push({ name: "CustomerLogin" });
                });
            } else {
                error.value = data.message
            }
        } catch (err) {
            error.value = err.response?.data?.message || err.message;
        } finally {
            loading.value = false;
        }
    }


    async function refreshCustomerToken(params = {}) {
        loading.value = true;
        error.value = null;
        try {
            const data = await refreshCustomerAuthToken(params);
            customerAuthData.value = data;
        } catch (err) {
            store.dispatch("customerLogout");
            error.value = err.response?.data?.message || err.message;
        } finally {
            loading.value = false;
        }
    }

    async function readNotification(notification) {
        error.value = null;
        try {
            const data = await readNotificationAPI(notification.id);
            notificationReadResponse.value = data;
        } catch (err) {
            error.value = err.response?.data?.message || err.message;
        } finally {
            loading.value = false;
        }
    }

    async function notificationMarksAsRead() {
        error.value = null;
        try {
            const data = await readNotificationMarkAsReadAPI();
            notificationReadResponse.value = data;
        } catch (err) {
            error.value = err.response?.data?.message || err.message;
        } finally {
            loading.value = false;
        }
    }


    watchEffect(() => {
        const token = localStorage.getItem("customerToken");
        if (token) {
            refreshCustomerToken();
        }
    });

    return {
        loading,
        error,
        customerAuthData,
        notificationReadResponse,
        refreshCustomerToken,
        readNotification,
        notificationMarksAsRead,
        customerLogout
    };
}