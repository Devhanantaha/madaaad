<template>
    <li>
        <a :href="item.href ? item.href : item.url" :class="this.headerMenuStyle.custom_menu == 1 ? 'custom-menu' : ''">
            <span :class="item.icon ? 'd-flex align-items-center gap-2' : ''">
                <span class="material-icons" v-if="item.icon">{{ item.icon }}</span>
                {{ offCanvas ? $t(item.name) : item.name }}
            </span>
        </a>
    </li>
</template>

<script>
export default {
    name: "MenuItem",
    props: {
        item: {
            type: Object,
            required: true,
        },
        offCanvas: {
            type: Boolean,
            required: false,
            default: false
        },
        headerMenuStyle: {
            type: Object,
            required: false,
            default: () => {
                return {};
            },
        },
    },
};
</script>
