<template>
    <!-- Offcanvas -->
    <div class="offcanvas-custom-container2 d-flex">
        <!-- Offcanvas Trigger -->
        <span class="aoc-trigger material-icons" :class="{ active: isOffcanvasOpened }" @click="toggleOffcanvas"> person
        </span>
        <!-- End Offcanvas Trigger -->

        <div class="offcanvas-wrapper" :class="{ open: isOffcanvasOpened }">
            <div class="offcanvas-panel w-100 h-100 bg-white"
                :class="headerStyle.custom_header == 1 ? 'custom-offcanvas-panel' : ''">

                <div class="c1-bg offcanvas-header position-relative"
                    :class="headerStyle.custom_header == 1 ? 'custom-offcanvas-header' : ''">
                    <!-- Offcanvas Close -->
                    <span
                        class="offcanvas-close d-inline-flex align-items-center justify-content-center position-absolute text-white"
                        @click="toggleOffcanvas">
                        <base-icon-svg name="close" />
                    </span>
                    <!-- End Offcanvas Close -->

                    <!-- User Info -->
                    <div v-if="userInfo.name" class="user-info">
                        <div class="user-avatar mb-10">
                            <img class="rounded-circle" :src="userInfo.image" :alt="userInfo.name" width="70"
                                height="70" />
                        </div>
                        <h4 class="mb-0 text-white">{{ userInfo.name }}</h4>
                    </div>
                    <!-- End User Info -->

                    <!-- Login Register -->
                    <div v-else class="login-register mt-15">
                        <a href="/login" class="text-white"> {{ $t("Login") }}</a>
                        |
                        <a href="/register" class="text-white">
                            {{ $t("Registration") }}
                        </a>
                    </div>
                    <!-- End Login Register -->
                </div>

                <div class="offcanvas-content px-3">
                    <div class="offcanvas-menu">
                        <ul class="list-unstyled mb-0">
                            <template v-for="(item, index) in menuItems">
                                <menu-item v-if="!item.submenu" :key="`item-${index}`" :item="item" off-canvas
                                    :header-menu-style="headerMenuStyle" />

                                <menu-dropdown v-else :key="`group-${index}`" :item="item" off-canvas
                                    :open-group="isGroupActive(item, $route.fullPath)"
                                    :header-menu-style="headerMenuStyle" />
                            </template>
                            <li>
                                <a href="#" @click.prevent="customerLogout()" class="d-flex align-items-center gap-1">
                                    <span class="material-icons">logout</span>
                                    <span>{{ $t("Logout") }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Offcanvas -->
</template>

<script setup>
import { ref, computed } from "vue";
import MenuItem from "./MenuItem.vue";
import MenuDropdown from "./MenuDropdown.vue";
import { useCustomerAuth } from "@/composables/useCustomerAuth";

// composable
const { customerLogout } = useCustomerAuth();

// props
defineProps({
    userInfo: {
        type: Object,
        default: null,
    },
    menuItems: {
        type: Array,
        required: true,
    },
    headerMenuStyle: {
        type: Object,
        required: false,
        default: () => ({}),
    },
    headerStyle: {
        type: Object,
        required: false,
        default: () => ({}),
    },
});

// state
const isOffcanvasOpened = ref(false);

// computed
const isGroupActive = computed(() => {
    return (item, path) => {
        let openGroup = false;

        const func = (subItem) => {
            if (subItem.submenu) {
                subItem.submenu.forEach((child) => {
                    if (path === child.url) {
                        openGroup = true;
                    } else if (child.submenu) {
                        func(child);
                    }
                });
            }
        };

        func(item, path);
        return openGroup;
    };
});

// methods
const toggleOffcanvas = () => {
    isOffcanvasOpened.value = !isOffcanvasOpened.value;
    document.body.classList.toggle("offcanvas-oppened");
};

</script>

<style lang="scss" scoped>
@import "../../assets/sass/00-abstracts/01-variables";

.aoc-trigger.material-icons {
    font-size: 24px;
}
</style>
