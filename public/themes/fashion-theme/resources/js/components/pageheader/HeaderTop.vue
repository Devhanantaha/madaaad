<script>
import { mapState } from "vuex";
export default {
    name: "HeaderTop",
    emits: ["change-currency", "change-language"],
    data() {
        return {
            selected_lang: localStorage.getItem("locale") || "en",
            selected_currency: JSON.parse(localStorage.getItem("currency")),
            showLanguageDropdown: false,
            showCurrencyDropdown: false,
            showSellerDropdown: false,
        };
    },
    props: {
        currencies: {
            type: Array,
            required: false,
        },
        languages: {
            type: Array,
            required: false,
        },
        headerTopOptions: {
            type: Object,
            required: false,
            default: () => ({}),
        }
    },
    computed: mapState({
        siteSettings: (state) => state.siteSettings,
        selectedLanguage() {
            return this.languages.filter((lang) => lang.code === this.selected_lang)[0] ?? null;
        },
    }),
    mounted() {
        document.addEventListener("click", this.closeDropdown);
    },

    methods: {
        /**
         * Close Dropdown
         */
        closeDropdown(e) {
            let target = e.target;
            let languageSwitcher = this.$refs.languageSwitcher;
            if (languageSwitcher !== target && !languageSwitcher?.contains(target)) {
                this.showLanguageDropdown = false;
            }

            let currencySwitcher = this.$refs.currencySwitcher;
            if (currencySwitcher !== target && !currencySwitcher?.contains(target)) {
                this.showCurrencyDropdown = false;
            }

            let sellerDropdown = this.$refs.sellerDropdown;
            if (sellerDropdown !== target && !sellerDropdown?.contains(target)) {
                this.showSellerDropdown = false;
            }
        },
        /**
        * Change currency
        */
        setCurrency() {
            this.$emit(
                "change-currency",
                this.selected_currency
            );
        },
        /**
         * Change  Language 
         */
        setLanguage() {
            this.$emit(
                "change-language",
                this.selected_lang,
            );
        },

    },
};
</script>
<template>
    <!-- Header Top -->
    <div class="custom-header-top-bar header-top-bar header-border">
        <div class="custom-container2">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6 col-sm-4 col-5">
                    <div class="header-info-wrap">
                        <ul class="list-unstyled header-info">
                            <li ref="languageSwitcher"
                                v-if="headerTopOptions && headerTopOptions.language_dropdown_status == 1">
                                <div class="language-switcher d-flex align-items-center gap-1"
                                    @click="showLanguageDropdown = !showLanguageDropdown">
                                    <div class="title">
                                        {{ selectedLanguage?.title || selected_lang }}
                                    </div>
                                    <div class="caret">
                                        <span class="material-icons"> expand_more </span>
                                    </div>
                                </div>
                                <div class="header-top-dropdown mt--1" v-if="showLanguageDropdown">
                                    <ul class="list-unstyled">
                                        <li v-for="language in languages" :key="language.code"
                                            @click="selected_lang = language.code; setLanguage()"
                                            class="cursor-pointer">
                                            {{ language.title }}
                                        </li>
                                    </ul>
                                </div>
                            </li>
                            <li ref="currencySwitcher"
                                v-if="headerTopOptions && headerTopOptions.currency_dropdown_status == 1">
                                <div class="currency-switcher d-flex align-items-center gap-1"
                                    @click="showCurrencyDropdown = !showCurrencyDropdown">
                                    <div class="title">{{ selected_currency.code }}
                                    </div>
                                    <div class="caret">
                                        <span class="material-icons"> expand_more </span>
                                    </div>
                                </div>
                                <div class="header-top-dropdown" v-if="showCurrencyDropdown">
                                    <ul class="list-unstyled">
                                        <li v-for="currency in currencies" :key="currency.code" class="cursor-pointer"
                                            @click="selected_currency = currency; setCurrency()">
                                            {{ currency.name }} ({{ currency.code }})
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-8 col-7">
                    <div class="header-info-wrap">
                        <ul class="list-unstyled header-info justify-content-end">
                            <li>
                                <router-link to="/order-tracking" class="title">{{ $t("Order Tracking") }}</router-link>
                            </li>
                            <li ref="sellerDropdown" v-if="siteSettings && siteSettings.is_active_multivendor == 1">
                                <div class=" d-flex align-items-center gap-1"
                                    @click="showSellerDropdown = !showSellerDropdown">
                                    <div class="title cursor-pointer">{{ $t("Become a Seller") }}
                                    </div>
                                    <div class="caret">
                                        <span class="material-icons"> expand_more </span>
                                    </div>
                                </div>
                                <div class="header-top-dropdown" v-if="showSellerDropdown">
                                    <ul class="list-unstyled">
                                        <li class="mb-2">
                                            <a href="/seller/login" target="_blank"
                                                class="align-items-center d-flex gap-2">
                                                <span class="material-icons">login</span>
                                                <span class="ml-5">{{ $t("Login") }}</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="/seller/register" class="align-items-center d-flex gap-2">
                                                <span class="material-icons">person_add</span>
                                                <span class="ml-5">{{ $t("Register") }}</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Header Top -->
</template>
<style scoped>
.language-switcher,
.currency-switcher {
    position: relative;
    cursor: pointer;
}

.currency-switcher .material-icons {
    font-size: 13px;
    line-height: 0;
    vertical-align: middle;
}

.language-switcher .material-icons {
    font-size: 13px;
    line-height: 0;
    vertical-align: middle;
}

.title {
    font-size: 13px;
    text-transform: uppercase;
    font-weight: 400;
}
</style>
