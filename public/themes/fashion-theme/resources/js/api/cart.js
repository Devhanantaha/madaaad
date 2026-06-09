import { http } from "@/api/http.js";

export async function removeCustomerCartItemAPI(uid) {
    const { data } = await http.post("api/v1/ecommerce-core/customer/cart/remove-item", { uid: uid });
    return data;
}

export async function updateCustomerCartItemAPI(item) {
    const { data } = await http.post("api/v1/ecommerce-core/customer/cart/update-cart-item", { item: item });
    return data;
}

export async function validateCustomerCartItemsAPI(items) {
    const { data } = await http.post("api/v1/ecommerce-core/cart/validate-cart-items", { items: items });
    return data;
}

export async function applyCouponAPI(coupon_code, products, customer_id = null) {
    const { data } = await http.post("api/v1/ecommerce-core/apply-coupon", { coupon_code: coupon_code, products: products, customer_id: customer_id });
    return data;
}