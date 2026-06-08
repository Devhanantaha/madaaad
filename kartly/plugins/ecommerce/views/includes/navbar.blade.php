 @php
     //pickup order from  plugin
     $isactivatePickupPoint = isActivePlugin('pickuppoint');
     $order_pickup_point_active_link_file_links = [];
     $order_pickup_point_active_link_file =
         base_path() . '/plugins/pickuppoint/views/includes/submenu/order_active_link.json';
     if (file_exists($order_pickup_point_active_link_file)) {
         $order_pickup_point_active_link_file_links = json_decode(
             file_get_contents($order_pickup_point_active_link_file),
             true,
         );
     }
     $isactivateMultivendor = isActivePlugin('multivendor');
     //Seller Products from  plugin
     $seller_products_active_link_file_links = [];
     $seller_products_active_link_file =
         base_path() . '/plugins/multivendor/views/includes/submenu/products_active_link.json';
     if (file_exists($seller_products_active_link_file)) {
         $seller_products_active_link_file_links = json_decode(
             file_get_contents($seller_products_active_link_file),
             true,
         );
     }
     //Seller order from  plugin
     $order_seller_active_link_file_links = [];
     $order_seller_active_link_file =
         base_path() . '/plugins/multivendor/views/includes/submenu/order_active_link.json';
     if (file_exists($order_seller_active_link_file)) {
         $order_seller_active_link_file_links = json_decode(file_get_contents($order_seller_active_link_file), true);
     }
 @endphp
 <!--Products Module-->
 @if (auth()->user()->can('Manage Add New Product') ||
         auth()->user()->can('Manage Inhouse Products') ||
         auth()->user()->can('Manage Colors') ||
         auth()->user()->can('Manage Brands') ||
         auth()->user()->can('Manage Categories') ||
         auth()->user()->can('Manage Attributes') ||
         auth()->user()->can('Manage Units') ||
         auth()->user()->can('Manage Product Reviews') ||
         auth()->user()->can('Manage Product collections') ||
         auth()->user()->can('Manage Product Tags') ||
         auth()->user()->can('Manage Product Conditions'))
     <li
         class="{{ Request::routeIs($seller_products_active_link_file_links, ['plugin.ecommerce.product.reviews.list','plugin.ecommerce.product.collection.products','plugin.ecommerce.product.collection.edit','plugin.ecommerce.product.collection.add.new','plugin.ecommerce.product.collection.list','plugin.ecommerce.product.edit','plugin.ecommerce.product.list','plugin.ecommerce.product.add.new','plugin.ecommerce.product.units.edit','plugin.ecommerce.product.attributes.values.edit','plugin.ecommerce.product.attributes.values','plugin.ecommerce.product.attributes.edit','plugin.ecommerce.product.attributes.add','plugin.ecommerce.product.attributes.list','plugin.ecommerce.product.tags.edit','plugin.ecommerce.product.tags.add.new','plugin.ecommerce.product.tags.list','plugin.ecommerce.product.conditions.edit','plugin.ecommerce.product.conditions.new','plugin.ecommerce.product.conditions.list','plugin.ecommerce.product.units.new','plugin.ecommerce.product.units.list','plugin.ecommerce.product.colors.edit','plugin.ecommerce.product.colors.list','plugin.ecommerce.product.colors.new','plugin.ecommerce.product.brand.edit','plugin.ecommerce.product.brand.new','plugin.ecommerce.product.category.list','plugin.ecommerce.product.category.new','plugin.ecommerce.product.category.edit','plugin.ecommerce.product.brand.list'])? 'active sub-menu-opened': '' }}">
         <a href="#">
             <i class="icofont-bucket1"></i>
             <span class="link-title"> {{ translate('Products') }}</span>
         </a>
         <ul class="nav sub-menu">
             @if (auth()->user()->can('Manage Add New Product'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.product.add.new']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.product.add.new') }}">{{ translate('Add New Product') }}</a>
                 </li>
             @endif
             @if (auth()->user()->can('Manage Inhouse Products'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.product.list']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.product.list') }}">
                         @if ($isactivateMultivendor)
                             {{ translate('Inhouse Products') }}
                         @else
                             {{ translate('All Products') }}
                         @endif
                     </a>
                 </li>
                 @if ($isactivateMultivendor)
                     @includeIf('plugin/multivendor::includes.submenu.products')
                 @endif
             @endif
             @if (auth()->user()->can('Manage Colors'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.product.colors.edit', 'plugin.ecommerce.product.colors.list', 'plugin.ecommerce.product.colors.new']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.product.colors.list') }}">{{ translate('Colors') }}</a>
                 </li>
             @endif
             @if (auth()->user()->can('Manage Brands'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.product.brand.edit', 'plugin.ecommerce.product.brand.list', 'plugin.ecommerce.product.brand.new']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.product.brand.list') }}">{{ translate('Brands') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Categories'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.product.category.list', 'plugin.ecommerce.product.category.new', 'plugin.ecommerce.product.category.edit']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.product.category.list') }}">{{ translate('Categories') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Attributes'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.product.attributes.values.edit', 'plugin.ecommerce.product.attributes.values', 'plugin.ecommerce.product.attributes.edit', 'plugin.ecommerce.product.attributes.add', 'plugin.ecommerce.product.attributes.list']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.product.attributes.list') }}">{{ translate('Attributes') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Units'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.product.units.edit', 'plugin.ecommerce.product.units.new', 'plugin.ecommerce.product.units.list']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.product.units.list') }}">{{ translate('Units') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Product Reviews'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.product.reviews.list']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.product.reviews.list') }}">{{ translate('Product Reviews') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Product collections'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.product.collection.list']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.product.collection.list') }}">{{ translate('Product collections') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Product Tags'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.product.tags.edit', 'plugin.ecommerce.product.tags.add.new', 'plugin.ecommerce.product.tags.list']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.product.tags.list') }}">{{ translate('Product Tags') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Product conditions'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.product.conditions.edit', 'plugin.ecommerce.product.conditions.new', 'plugin.ecommerce.product.conditions.list']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.product.conditions.list') }}">{{ translate('Product conditions') }}</a>
                 </li>
             @endif
         </ul>
     </li>
 @endif

 <!--End Products Module-->
 <!--Orders Module-->
 @if (auth()->user()->can('Manage Inhouse Orders') || auth()->user()->can('Manage Pickup Point Order'))
     <li
         class="{{ Request::routeIs($order_pickup_point_active_link_file_links, $order_seller_active_link_file_links, ['plugin.ecommerce.orders.details', 'plugin.ecommerce.orders.inhouse']) ? 'active sub-menu-opened' : '' }}">
         <a href="#">
             <i class="icofont-cart"></i>
             <span class="link-title">{{ translate('Orders') }}</span>
         </a>
         <ul class="nav sub-menu">
             @if (auth()->user()->can('Manage Inhouse Orders'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.orders.inhouse']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.orders.inhouse') }}">{{ translate('Inhouse Orders') }}</a>
                 </li>
             @endif

             @if ($isactivateMultivendor)
                 @includeIf('plugin/multivendor::includes.submenu.order')
             @endif
             @if ($isactivatePickupPoint)
                 @includeIf('plugin/pickuppoint::includes.submenu.order')
             @endif

         </ul>
     </li>
 @endif

 <!--End Orders Module-->

 @if (auth()->user()->can('Manage Customers'))
     <!--Customer Module-->
     <li class="{{ Request::routeIs(['plugin.ecommerce.customers.list']) ? 'active' : '' }}">
         <a href="{{ route('plugin.ecommerce.customers.list') }}">
             <i class="icofont-users-alt-4"></i>
             <span class="link-title">{{ translate('Customers') }}</span>
         </a>

     </li>
     <!--End Customer module-->
 @endif

 <!--Shippings Module-->
 @php
     //carrier  plugin
     $isactivateCarrier = isActivePlugin('carrier');
     $shipping_carrier_active_link_file_links = [];
     $shipping_carrier_active_link_file =
         base_path() . '/plugins/carrier/views/includes/submenu/shipping_active_link.json';
     if (file_exists($shipping_carrier_active_link_file)) {
         $shipping_carrier_active_link_file_links = json_decode(
             file_get_contents($shipping_carrier_active_link_file),
             true,
         );
     }
     //pickup  plugin
     $isactivatePickupPoint = isActivePlugin('pickuppoint');
     $shipping_pickup_point_active_link_file_links = [];
     $shipping_pickup_point_active_link_file =
         base_path() . '/plugins/pickuppoint/views/includes/submenu/shipping_active_link.json';
     if (file_exists($shipping_pickup_point_active_link_file)) {
         $shipping_pickup_point_active_link_file_links = json_decode(
             file_get_contents($shipping_pickup_point_active_link_file),
             true,
         );
     }
     //delivery boy plugun
     $isactivateDeliveryBoy = isActivePlugin('deliveryboy');
     $shipping_delivery_boy_active_link_file_links = [];
     $shipping_delivery_boy_active_link_file =
         base_path() . '/plugins/deliveryboy/views/includes/submenu/shipping_active_link.json';
     if (file_exists($shipping_delivery_boy_active_link_file)) {
         $shipping_delivery_boy_active_link_file_links = json_decode(
             file_get_contents($shipping_delivery_boy_active_link_file),
             true,
         );
     }
 @endphp

 @if (auth()->user()->can('Manage Shipping & Delivery') ||
         auth()->user()->can('Manage Pickup Points') ||
         auth()->user()->can('Manage Carriers') ||
         auth()->user()->can('Manage Locations'))

     <li
         class="{{ Request::routeIs($shipping_carrier_active_link_file_links, $shipping_delivery_boy_active_link_file_links, $shipping_pickup_point_active_link_file_links, ['plugin.ecommerce.shipping.profile.manage', 'plugin.ecommerce.shipping.profile.form', 'plugin.ecommerce.shipping.configuration', 'plugin.ecommerce.shipping.locations.cities.edit', 'plugin.ecommerce.shipping.locations.cities.add.new', 'plugin.ecommerce.shipping.locations.cities.list', 'plugin.ecommerce.shipping.locations.states.edit', 'plugin.ecommerce.shipping.locations.states.new.add', 'plugin.ecommerce.shipping.locations.states.list', 'plugin.ecommerce.shipping.locations.country.edit', 'plugin.ecommerce.shipping.locations.country.new', 'plugin.ecommerce.shipping.locations.country.list']) ? 'active sub-menu-opened' : '' }}">
         <a href="#">
             <i class="icofont-ship"></i>
             <span class="link-title">{{ translate('Shippings') }}</span>
         </a>
         <ul class="nav sub-menu">
             @if (auth()->user()->can('Manage Shipping & Delivery'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.shipping.configuration']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.shipping.configuration') }}">{{ translate('Shipping & Delivery') }}</a>
                 </li>
             @endif


             @if ($isactivatePickupPoint)
                 @if (auth()->user()->can('Manage Pickup Points'))
                     @includeIf('plugin/pickuppoint::includes.submenu.shipping')
                 @endif
             @endif

             @if ($isactivateCarrier)
                 @if (auth()->user()->can('Manage Carriers'))
                     @includeIf('plugin/carrier::includes.submenu.shipping')
                 @endif
             @endif

             @if (auth()->user()->can('Manage Locations'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.shipping.locations.cities.edit', 'plugin.ecommerce.shipping.locations.cities.add.new', 'plugin.ecommerce.shipping.locations.cities.list', 'plugin.ecommerce.shipping.locations.states.edit', 'plugin.ecommerce.shipping.locations.states.new.add', 'plugin.ecommerce.shipping.locations.states.list', 'plugin.ecommerce.shipping.locations.country.edit', 'plugin.ecommerce.shipping.locations.country.new', 'plugin.ecommerce.shipping.locations.country.list']) ? 'sub-menu-opened' : '' }}">
                     <a href="{{ route('core.languages') }}">{{ translate('Locations') }}</a>
                     <ul class="nav sub-menu">
                         <li
                             class="{{ Request::routeIs(['plugin.ecommerce.shipping.locations.country.edit', 'plugin.ecommerce.shipping.locations.country.new', 'plugin.ecommerce.shipping.locations.country.list']) ? 'active ' : '' }}">
                             <a
                                 href="{{ route('plugin.ecommerce.shipping.locations.country.list') }}">{{ translate('Countries') }}</a>
                         </li>
                         <li
                             class="{{ Request::routeIs(['plugin.ecommerce.shipping.locations.states.edit', 'plugin.ecommerce.shipping.locations.states.new.add', 'plugin.ecommerce.shipping.locations.states.list']) ? 'active ' : '' }}">
                             <a
                                 href="{{ route('plugin.ecommerce.shipping.locations.states.list') }}">{{ translate('States') }}</a>
                         </li>
                         <li
                             class="{{ Request::routeIs(['plugin.ecommerce.shipping.locations.cities.edit', 'plugin.ecommerce.shipping.locations.cities.add.new', 'plugin.ecommerce.shipping.locations.cities.list']) ? 'active ' : '' }}">
                             <a
                                 href="{{ route('plugin.ecommerce.shipping.locations.cities.list') }}">{{ translate('Cities') }}</a>
                         </li>
                     </ul>
                 </li>
             @endif
         </ul>
     </li>
 @endif

 <!--End Shippings Module-->



 <!--Payments Module-->
 @if (auth()->user()->can('Manage Payment Methods') || auth()->user()->can('Manage Transaction history'))
     <li
         class="{{ Request::routeIs(['plugin.ecommerce.payments.methods', 'plugin.ecommerce.payments.transactions.history']) ? 'active sub-menu-opened' : '' }}">
         <a href="#">
             <i class="icofont-money"></i>
             <span class="link-title">{{ translate('Payments') }}</span>
         </a>
         <ul class="nav sub-menu">
             @if (auth()->user()->can('Manage Payment Methods'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.payments.methods']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.payments.methods') }}">{{ translate('Payment Methods') }}</a>
                 </li>
             @endif
             @if (auth()->user()->can('Manage Transaction history'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.payments.transactions.history']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.payments.transactions.history') }}">{{ translate('Transaction history') }}</a>
                 </li>
             @endif
         </ul>
     </li>
 @endif
 <!--End Payments Module-->


 <!--Marketings Module-->
 @php
     //flashdeal plugin
     $isactivateFlashdeal = isActivePlugin('flashdeal');
     $marketing_active_link_file_links = [];
     $marketing_active_link_file = base_path() . '/plugins/flashdeal/views/includes/submenu/marketing_active_link.json';
     if (file_exists($marketing_active_link_file)) {
         $marketing_active_link_file_links = json_decode(file_get_contents($marketing_active_link_file), true);
     }
     //coupon plugin
     $isactivateCoupon = isActivePlugin('coupon');
     $marketing_coupon_active_link_file_links = [];
     $marketing_coupon_active_link_file =
         base_path() . '/plugins/coupon/views/includes/submenu/marketing_active_link.json';
     if (file_exists($marketing_coupon_active_link_file)) {
         $marketing_coupon_active_link_file_links = json_decode(
             file_get_contents($marketing_coupon_active_link_file),
             true,
         );
     }
 @endphp
 @if (auth()->user()->can('Manage Flash Deals') ||
         auth()->user()->can('Manage Coupons') ||
         auth()->user()->can('Manage Custom notification'))
     <li
         class="{{ Request::routeIs($marketing_coupon_active_link_file_links, $marketing_active_link_file_links, ['plugin.ecommerce.marketing.custom.notification', 'plugin.ecommerce.marketing.custom.notification.create.new']) ? 'active sub-menu-opened' : '' }}">
         <a href="#">
             <i class="icofont-megaphone"></i>
             <span class="link-title">{{ translate('Marketing') }}</span>
         </a>
         <ul class="nav sub-menu">
             @if ($isactivateFlashdeal)
                 @includeIf('plugin/flashdeal::includes.submenu.marketing')
             @endif
             @if ($isactivateCoupon)
                 @includeIf('plugin/coupon::includes.submenu.marketing')
             @endif
             @if (auth()->user()->can('Manage Custom notification'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.marketing.custom.notification', 'plugin.ecommerce.marketing.custom.notification.create.new']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.marketing.custom.notification') }}">{{ translate('Custom Notification') }}</a>
                 </li>
             @endif
         </ul>
     </li>
 @endif
 <!--End Marketings Module-->
 <!--Report Module-->
 @if (auth()->user()->can('Manage Product Reports') ||
         auth()->user()->can('Manage Keyword Search Reports') ||
         auth()->user()->can('Manage Wishlist Reports'))
     <li
         class="{{ Request::routeIs(['plugin.ecommerce.reports.search.keyword', 'plugin.ecommerce.reports.products.wishlist', 'plugin.ecommerce.reports.products']) ? 'active sub-menu-opened' : '' }}">
         <a href="#">
             <i class="icofont-list"></i>
             <span class="link-title">{{ translate('Reports') }}</span>
         </a>
         <ul class="nav sub-menu">
             @if (auth()->user()->can('Manage Product Reports'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.reports.products']) ? 'active ' : '' }}">
                     <a href="{{ route('plugin.ecommerce.reports.products') }}">{{ translate('Product Reports') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Keyword Search Reports'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.reports.search.keyword']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.reports.search.keyword') }}">{{ translate('Keyword Search Reports') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Wishlist Reports'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.reports.products.wishlist']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.reports.products.wishlist') }}">{{ translate('Wishlist Reports') }}</a>
                 </li>
             @endif
         </ul>
     </li>
 @endif
 <!--End Report Module-->

 <!--Ecommerce Settings Module-->
 @if (auth()->user()->can('Manage Taxes') ||
         auth()->user()->can('Manage Settings') ||
         auth()->user()->can('Manage Currencies') ||
         auth()->user()->can('Manage Product Share Options'))
     <li
         class="{{ Request::routeIs(['plugin.ecommerce.ecommerce.edit.currency', 'plugin.ecommerce.ecommerce.add.currency', 'plugin.ecommerce.ecommerce.all.currencies', 'plugin.ecommerce.ecommerce.configuration', 'plugin.ecommerce.ecommerce.settings.taxes.manage.rates', 'plugin.ecommerce.products.share.options', 'plugin.ecommerce.ecommerce.settings.taxes.list']) ? 'active sub-menu-opened' : '' }}">
         <a href="#">
             <i class="icofont-interface"></i>
             <span class="link-title">{{ translate('E-commerce Settings') }}</span>
         </a>
         <ul class="nav sub-menu">
             @if (auth()->user()->can('Manage Settings'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.ecommerce.configuration']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.ecommerce.configuration') }}">{{ translate('E-commerce Settings') }}</a>
                 </li>
             @endif
             @if (auth()->user()->can('Manage Currencies'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.ecommerce.all.currencies']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.ecommerce.all.currencies') }}">{{ translate('Currencies') }}</a>
                 </li>
             @endif

             @if (auth()->user()->can('Manage Product Share Options'))
                 <li class="{{ Request::routeIs(['plugin.ecommerce.products.share.options']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.products.share.options') }}">{{ translate('Product Share Options') }}</a>
                 </li>
             @endif
             @if (auth()->user()->can('Manage Taxes'))
                 <li
                     class="{{ Request::routeIs(['plugin.ecommerce.ecommerce.settings.taxes.manage.rates', 'plugin.ecommerce.ecommerce.settings.taxes.list']) ? 'active ' : '' }}">
                     <a
                         href="{{ route('plugin.ecommerce.ecommerce.settings.taxes.list') }}">{{ translate('Tax') }}</a>
                 </li>
             @endif
         </ul>
     </li>
 @endif
 <!--End Ecommerce Settings Module-->

 <!--App Configuration Module-->
 <li
     class="{{ Request::routeIs(['plugin.ecommerce.app.configuration.banner.edit', 'plugin.ecommerce.app.configuration.banner.add', 'plugin.ecommerce.app.configuration.banner.list']) ? 'active sub-menu-opened' : '' }}">
     <a href="#">
         <i class="icofont-android-nexus"></i>
         <span class="link-title">{{ translate('App Configuration') }}</span>
     </a>
     <ul class="nav sub-menu">
         <li
             class="{{ Request::routeIs(['plugin.ecommerce.app.configuration.banner.list', 'plugin.ecommerce.app.configuration.banner.add', 'plugin.ecommerce.app.configuration.banner.edit']) ? 'active ' : '' }}">
             <a
                 href="{{ route('plugin.ecommerce.app.configuration.banner.list') }}">{{ translate('Banner Setup') }}</a>
         </li>
     </ul>
 </li>
 <!--End App Configuration Module-->
