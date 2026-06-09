import axios from "axios";


const http = axios.create({
    baseURL: '/',
    timeout: 15000,
    headers: { "Content-Type": "application/json", "Accept-Language": localStorage.getItem("locale") || "en", }
});

http.interceptors.request.use((config) => {
    const token = JSON.parse(localStorage.getItem("customerToken"))
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export { http };