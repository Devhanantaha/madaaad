import { createRouter, createWebHistory } from "vue-router";
import { loadLanguageAsync } from "../i18n_setup";
import store from "../store";
/**
 * Check customer is logged in or not
 * 
 * @param {*} to 
 * @param {*} from 
 * @param {*} next 
 */
function customerAuth(to, from, next) {
  if (store.state.isCustomerLogin) {
    next();
  } else {
    next('/login');
  }
}
function isCustomerLogin(to, from, next) {

  if (!store.state.isCustomerLogin) {
    next();
  } else {
    next('/dashboard');
  }
}
const router = createRouter({
  routes: [
    {
      path: "/",
      name: "home",
      component: () => import(/* webpackChunkName: "Home" */ '../views/Home.vue'),
    },
    {
      path: "/categories",
      name: "categories",
      component: () => import(/* webpackChunkName: "ProductCategories" */ '../views/category/index.vue'),
    },
    {
      path: "/products",
      name: "products",
      component: () => import(/* webpackChunkName: "ProductPage" */ '../views/products/index.vue'),
    },
    {
      path: "/products/:id",
      name: "product",
      component: () => import(/* webpackChunkName: "ProductDetails" */ '../views/products/_id/index.vue'),
    },
    {
      path: "/product/search",
      name: "searchProduct",
      component: () => import(/* webpackChunkName: "SearchResult" */ './../views/products/search/index.vue'),
    },
    {
      path: "/products/category/:id",
      name: "categoryProducts",
      component: () => import(/* webpackChunkName: "CategoryProducts" */ '../views/products/category/_id/index.vue'),
    },
    {
      path: "/deals/:id",
      name: "Deals",
      component: () => import(/* webpackChunkName: "DealPage" */ '../views/deals/index.vue'),
    },
    {
      path: "/collection/:id",
      name: "Collection",
      component: () => import(/* webpackChunkName: "CollectionPage" */ '../views/products/collection/CollectionProducts.vue'),
    },
    {
      path: "/blogs",
      name: "blogs",
      component: () => import(/* webpackChunkName: "ListBlogs" */ '../views/blogs/index.vue'),
    },
    {
      path: "/blog/category/:id",
      name: "blog-category",
      component: () => import(/* webpackChunkName: "CategoryBlogPage" */ '../views/blogs/category/_id/index.vue'),
    },
    {
      path: "/blog/tag/:id",
      name: "blog-tag",
      component: () => import(/* webpackChunkName: "TagBlogs" */ '../views/blogs/tag/_id/index.vue'),
    },
    {
      path: "/blogs/search-result",
      name: "blog-search-result",
      component: () => import(/* webpackChunkName: "SearchBlogs" */ '../views/blogs/search/index.vue'),
    },
    {
      path: "/blog/:id",
      name: "blog",
      component: () => import(/* webpackChunkName: "SingleBlog" */ '../views/blogs/_id/index.vue'),
    },
    {
      path: "/blog-preview",
      name: "blog-preview",
      component: () => import(/* webpackChunkName: "PreviewBlog" */ '../views/blogs/preview/index.vue'),
    },
    {
      path: "/page/:id(.*)",
      name: "page",
      component: () => import(/* webpackChunkName: "SinglePage" */ '../views/page/_id/index.vue'),
    },
    {
      path: "/page-preview/:id",
      name: "page-preview",
      component: () => import(/* webpackChunkName: "PagePreview" */ '../views/page/preview/_id/index.vue'),
    },
    {
      path: "/cart",
      name: "Cart",
      component: () => import(/* webpackChunkName: "ShippingCart" */ '../views/cart/index.vue'),
    },
    {
      path: "/checkout",
      name: "Checkout",
      component: () => import(/* webpackChunkName: "CustomerCheckout" */ '../views/checkout/index.vue'),
    },
    {
      path: "/order-success/:id",
      name: "Confirm Order",
      component: () => import(/* webpackChunkName: "OrderSuccess" */ '../views/checkout/confirm-order/index.vue'),
    },
    {
      path: "/order-tracking",
      name: "Order Tracking",
      component: () => import(/* webpackChunkName: "OrderTracking" */ '../views/order-tracking/index.vue'),
    },
    {
      path: "/compare",
      name: "Compare",
      component: () => import(/* webpackChunkName: "ProductCompare" */ '../views/compare/index.vue'),
    },
    //customer dashboard
    {
      path: "/dashboard",
      name: "Dashboard",
      component: () => import(/* webpackChunkName: "CustomerDashboard" */ '../views/dashboard/index.vue'),
      beforeEnter: customerAuth
    },
    {
      path: "/dashboard/orders",
      name: "Orders",
      component: () => import(/* webpackChunkName: "Orders" */ '../views/dashboard/orders/index.vue'),
      beforeEnter: customerAuth
    },
    {
      path: "/dashboard/order-details/:id",
      name: "Order Details",
      component: () => import(/* webpackChunkName: "CustomerOrderDetails" */ '../views/dashboard/order-details/index.vue'),
      beforeEnter: customerAuth
    },
    {
      path: "/dashboard/address",
      name: "CustomerAddress",
      component: () => import(/* webpackChunkName: "CustomerAddress" */ '../views/dashboard/address/index.vue'),
      beforeEnter: customerAuth
    },
    {
      path: "/dashboard/wishlist",
      name: "Wishlist",
      component: () => import(/* webpackChunkName: "CustomerWishlist" */ '../views/dashboard/wishlist/index.vue'),
      beforeEnter: customerAuth
    },
    {
      path: "/dashboard/wallet",
      name: "wallet",
      component: () => import(/* webpackChunkName: "CustomerWallet" */ '../views/dashboard/my-wallet/index.vue'),
      beforeEnter: customerAuth
    },
    {
      path: "/dashboard/refund/requests",
      name: "CustomerRefundRequest",
      component: () => import(/* webpackChunkName: "CustomerOrderRefundList" */ '../views/dashboard/refunds/index.vue'),
      beforeEnter: customerAuth
    },
    {
      path: "/dashboard/refund/details/:id",
      name: "CustomerRefundRequestDetails",
      component: () => import(/* webpackChunkName: "CustomerRefundDetails" */ '../views/dashboard/refund-details/index.vue'),
      beforeEnter: customerAuth
    },
    {
      path: "/dashboard/manage-account",
      name: "ManageAccount",
      component: () => import(/* webpackChunkName: "CustomerManageAccount" */ '../views/dashboard/manage-account/index.vue'),
      beforeEnter: customerAuth
    },
    //End Customer dashboard
    {
      path: "/register",
      name: "CustomerRegister",
      component: () => import(/* webpackChunkName: "CustomerRegistration" */ '../views/auth/register.vue'),
      beforeEnter: isCustomerLogin
    },
    {
      path: "/login",
      name: "CustomerLogin",
      component: () => import(/* webpackChunkName: "CustomerLogin" */ '../views/auth/login.vue'),
      beforeEnter: isCustomerLogin
    },
    {
      path: "/password/forgot",
      name: "CustomerPasswordForgot",
      component: () => import(/* webpackChunkName: "CustomerForgotPassword" */ '../views/auth/forgotPassword.vue'),
      beforeEnter: isCustomerLogin
    },
    {
      path: "/password/reset",
      name: "ResetPassword",
      component: () => import(/* webpackChunkName: "CustomerPasswordReset" */ '../views/auth/resetPassword.vue'),
    },
    {
      path: "/email/reset",
      name: "ResetEmail",
      component: () => import(/* webpackChunkName: "CustomerEmailReset" */ '../views/auth/resetEmail.vue'),
    },
    {
      path: "/customer/email-verification",
      name: "CustomerEmailVerification",
      component: () => import(/* webpackChunkName: "CustomerEmailVerification" */ '../views/auth/verification.vue'),
    },
    {
      path: "/seller/register",
      name: "SellerRegister",
      component: () => import(/* webpackChunkName: "SellerRegistration" */ '../views/multivendor/auth/register.vue')
    },
    /**
     * Shop Routes
     */
    {
      path: "/all-shops",
      name: "AllShops",
      component: () => import(/* webpackChunkName: "AllShops" */ '../views/multivendor/shop/all_shops.vue'),
    },
    {
      path: "/shop/:slug",
      name: "ShopPage",
      component: () => import(/* webpackChunkName: "ShopPage" */ '../views/multivendor/shop/shop_page.vue'),
    },

    {
      path: "/404",
      name: "PageNotExist",
      component: () => import(/* webpackChunkName: "ErrorPage" */ '../views/Error.vue'),
    },
    {
      path: "/:catchAll(.*)", // Unrecognized path automatically matches 404
      redirect: "/404",
    },
  ],
  scrollBehavior: function (to, _from, savedPosition) {
    return window.scrollTo(0, 0);
  },
  history: createWebHistory('/'),
});

router.beforeEach((to, from, next) => {
  const lang = localStorage.getItem("locale") || "en";
  loadLanguageAsync(lang).then(() => next());
});
export default router;
