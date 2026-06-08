<template>
    <div class="contact-form my-2">
        <p v-bind:class="notificationClass" v-if="notification">
            {{ notification }}
        </p>
        <form @submit.prevent="handleSubmit">
            <div class="form-group mb-3">
                <label for="name">{{ $t('Name') }}</label>
                <input type="text" id="name" class="theme-input-style" v-model="name"
                    v-bind:placeholder="$t('Enter Name')" />
                <template v-if="errors.name">
                    <p class="fz-12 text-danger mt-1" v-for="(error, index) in errors.name" :key="index">
                        {{ error }}
                    </p>
                </template>
            </div>
            <div class="form-group mb-3">
                <label for="email">{{ $t('Email') }}</label>
                <input type="email" id="email" class="theme-input-style" v-model="email"
                    v-bind:placeholder="$t('Enter Email')" />
                <template v-if="errors.email">
                    <p class="fz-12 text-danger mt-1" v-for="(error, index) in errors.email" :key="index">
                        {{ error }}
                    </p>
                </template>
            </div>
            <div class="form-group mb-3">
                <label for="subject">{{ $t('Subject') }}</label>
                <input type="text" id="subject" class="theme-input-style" v-model="subject"
                    v-bind:placeholder="$t('Enter Subject')" />
                <template v-if="errors.subject">
                    <p class="fz-12 text-danger mt-1" v-for="(error, index) in errors.subject" :key="index">
                        {{ error }}
                    </p>
                </template>
            </div>
            <div class="form-group mb-3">
                <label for="message">{{ $t('Message') }}</label>
                <textarea id="message" class="theme-input-style" v-model="message"
                    v-bind:placeholder="$t('Write Message')"></textarea>
                <template v-if="errors.message">
                    <p class="fz-12 text-danger mt-1" v-for="(error, index) in errors.message" :key="index">
                        {{ error }}
                    </p>
                </template>
            </div>
            <div class="d-flex justify-content-start">
                <button type="submit" class="btn rounded-0">{{ $t('Send') }}</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "ContactForm",
    data() {
        return {
            name: "",
            email: "",
            message: "",
            subject: "",
            errors: [],
            formSubmitting: false,
            notification: "",
            notificationClass: "alert alert-info",
        }
    },
    props: {
        properties: {
            type: Object,
            default: {},
        }
    },
    methods: {
        handleSubmit() {
            this.formSubmitting = true;
            this.notification = "";
            this.errors = [];
            axios
                .post("/api/v1/store/contact/message", {
                    email: this.email,
                    name: this.name,
                    subject: this.subject,
                    message: this.message
                })
                .then((response) => {
                    if (response.data.success) {
                        this.$toast.success(this.$t("Message sent successfully"));
                        this.formSubmitting = false;
                        this.name = "";
                        this.email = "";
                        this.subject = "";
                        this.message = "";
                    } else {
                        this.formSubmitting = false;
                        this.notification = response.data.message;
                        this.notificationClass = "alert alert-danger";
                    }
                })
                .catch((error) => {
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors;
                    }
                    this.formSubmitting = false;
                });
        }
    }
};
</script>
