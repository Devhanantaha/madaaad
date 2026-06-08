<template>
    <span class="radio-text">
        <span class="font-weight-bold">{{ address.name }}</span>
        <br />
        <span> {{ $t("Address:") }} {{ address.address }} </span>
        <br />
        <span>{{ $t("Phone:") }} {{ address.phone }}</span>
        <br />
        <span>
            {{ $t("Postal Code:") }} {{ address.postal_code }}
        </span>
        <br />
        <span v-if="address.city != null">
            {{ address.city.name }} ,
        </span>
        <span v-if="address.state != null">
            {{ address.state.name }} ,
        </span>
        <span v-if="address.country != null">
            {{ address.country.name }}
        </span>
    </span>
</template>

<script>
export default {
    name: "AddressCard",
    props: {
        address: {
            type: Object,
            required: true,
        },
    },
}
</script>
