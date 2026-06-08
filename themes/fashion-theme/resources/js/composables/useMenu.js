import { ref } from "vue";
import { allMenuItemsAPI } from "@/api/menuItems";

export function useMenu() {
    const MenuItemsLoading = ref(false);
    const error = ref(null);

    const rightMenuItems = ref([]);
    const leftMenuItems = ref([]);
    const headerBottomMenu = ref([])

    async function fetchAllMenuItems() {
        MenuItemsLoading.value = true;
        error.value = null;

        try {
            const data = await allMenuItemsAPI();

            if (data.success) {
                rightMenuItems.value = data.header_top_right_menus?.menus || [];
                leftMenuItems.value = data.header_top_left_menus?.menus || [];
                headerBottomMenu.value = data.header_bottom_middle_menus?.menus || [];
            }
        } catch (err) {
            error.value = err.response?.data?.message || err.message;
        } finally {
            MenuItemsLoading.value = false;
        }
    }

    return {
        MenuItemsLoading,
        error,
        rightMenuItems,
        leftMenuItems,
        headerBottomMenu,
        fetchAllMenuItems,
    };
}
