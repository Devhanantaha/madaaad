import { ref } from "vue";
import { validateCustomerCartItemsAPI, removeCustomerCartItemAPI, updateCustomerCartItemAPI, applyCouponAPI } from "@/api/cart";

export function useCart() {
    const cart = ref([]);
    const cartLoading = ref(false);
    const error = ref(null);
    const cartSuccess = ref(false);
    const validateItems = ref([]);
    const couponApply = ref(false);
    const appliedCoupon = ref({});
    const couponDiscount = ref(0);

    async function removeCartItem(uid) {
        cartSuccess.value = false;
        try {
            const data = await removeCustomerCartItemAPI(uid);
            if (data.success) {
                cartSuccess.value = true;
            }
        } catch (err) {
            cartSuccess.value = false;
        }
    }

    async function updateCartItem(item) {
        cartSuccess.value = false;
        try {
            const data = await updateCustomerCartItemAPI(item);
            if (data.success) {
                cartSuccess.value = true;
            }
        } catch (err) {
            cartSuccess.value = false;
        }
    }

    async function validateCartItems(items) {
        cartSuccess.value = false;
        try {
            const data = await validateCustomerCartItemsAPI(items);
            if (data.success) {
                cartSuccess.value = true;
                validateItems.value = data.items;
            }
        } catch (err) {
            cartSuccess.value = false;
        }
    }

    async function applyCouponInCart(coupon_code, products, customer_id = null) {
        couponApply.value = false;
        couponDiscount.value = 0;
        try {
            const data = await applyCouponAPI(coupon_code, products, customer_id);
            if (data.success) {
                couponApply.value = true;
                couponDiscount.value = data.discount;
                let coupon_details = {
                    discount: data.discount,
                    id: data.coupon_id,
                    coupon_code: coupon_code,
                    allow_free_shipping: data.free_shipping,
                };
                appliedCoupon.value = coupon_details;

            }
        } catch (err) {
            couponApply.value = false;
        }
    }

    return {
        cart,
        cartLoading,
        error,
        cartSuccess,
        validateItems,
        couponApply,
        appliedCoupon,
        couponDiscount,
        removeCartItem,
        updateCartItem,
        validateCartItems,
        applyCouponInCart
    }
}