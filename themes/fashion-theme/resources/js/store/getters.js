export default {
  currency(state) {
    return state.currency;
  },
  wishlistItem: (state) =>
    state.customerDashboardInfo != null
      ? state.customerDashboardInfo.total_wishlisted_product
      : 0,

  cartItem: (state) =>
    state.cart.length ? state.cart.reduce((a, b) => a + b.quantity, 0) : 0,

  compareItem: (state) =>
    state.compareItems.length ? state.compareItems.length : 0,
};
