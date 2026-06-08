<template>
  <Dashboard>
    <div class="purchase_history">
      <page-header class="py-3" whiteBg :items="bItems" />
      <template v-if="!loading">
        <div v-if="tableData.length > 0">
          <div class="filter_wrapper my-2">
            <div class="row">
              <div class="col-12 d-flex flex-wrap gap-3">
                <div class="dataTables_filter">
                  <select class="theme-input-style" v-model="perPage" v-on:change="
                    () => {
                      currentPage = 1;
                      getCustomerOrders();
                    }
                  ">
                    <option v-for="(option, index) in pageOptions" :key="index" :value="option.key">
                      {{ option.label }}
                    </option>
                  </select>
                </div>
                <div class="dataTables_filter">
                  <input class="theme-input-style" v-model="search_key" v-bind:placeholder="$t('Order ID')" />
                </div>
                <div class="dataTables_filter">
                  <button class="bg-cover theme-input-style" @click.prevent="searchOrder">
                    {{ $t("Filter") }}
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="table-responsive">
                <CTable class="style1 mb-0" :class="tableData.length == 1 ? 't-mh-160' : ''">
                  <CTableHead>
                    <CTableRow>
                      <CTableHeaderCell>{{ $t("#") }}</CTableHeaderCell>
                      <CTableHeaderCell>{{ $t("ID") }}</CTableHeaderCell>
                      <CTableHeaderCell>{{
                        $t("Order Date")
                      }}</CTableHeaderCell>
                      <CTableHeaderCell>{{ $t("Amount") }}</CTableHeaderCell>
                      <CTableHeaderCell>{{
                        $t("Num of Products")
                      }}</CTableHeaderCell>
                      <CTableHeaderCell>{{ $t("Actions") }}</CTableHeaderCell>
                    </CTableRow>
                  </CTableHead>
                  <CTableBody>
                    <CTableRow v-for="(tdata, index) in tableData" :key="tdata.id">
                      <CTableDataCell>{{ index + 1 }}</CTableDataCell>
                      <CTableDataCell>
                        <router-link :to="`/dashboard/order-details/${tdata.id}`">
                          {{ tdata.order_code }}
                        </router-link>
                      </CTableDataCell>
                      <CTableDataCell>{{ tdata.order_date }}</CTableDataCell>
                      <CTableDataCell>
                        <the-currency :amount="tdata.total_payable_amount"></the-currency>
                      </CTableDataCell>
                      <CTableDataCell>{{
                        tdata.total_products
                      }}</CTableDataCell>
                      <CTableDataCell>
                        <CDropdown>
                          <CDropdownToggle><span class="material-icons">
                              more_vert
                            </span></CDropdownToggle>
                          <CDropdownMenu>
                            <CDropdownItem>
                              <router-link :to="`/dashboard/order-details/${tdata.id}`">{{ $t("Details")
                              }}</router-link>
                            </CDropdownItem>
                          </CDropdownMenu>
                        </CDropdown>
                      </CTableDataCell>
                    </CTableRow>
                  </CTableBody>
                </CTable>
              </div>
            </div>
          </div>

          <div class="row align-items-center mt-30" v-if="tableData.length > 0">
            <div class="col-md-6">
              <!-- Showing Per Page -->
              <showing-per-page class="text-center text-md-start" :total-items="totalRows" :current-page="currentPage"
                :items-per-page="perPage" />
              <!-- Showing Per Page -->
            </div>
            <div class="col-md-6 d-flex justify-content-end">
              <!-- Pagination -->
              <pagination :options="paginationOptions" v-model="currentPage" :records="totalRows" :per-page="perPage"
                @paginate="getCustomerOrders" />
              <!-- End Pagination -->
            </div>
          </div>
        </div>
        <div v-else>
          <the-not-found title="No item found"></the-not-found>
        </div>
      </template>
      <template v-if="loading">
        <skeleton class="w-100 mb-20" height="70px"></skeleton>
        <skeleton class="w-100" height="500px"></skeleton>
      </template>
    </div>
  </Dashboard>
</template>

<script>
import PageHeader from "@/components/pageheader/PageHeader.vue";
import ShowingPerPage from "@/components/ui/ShowingPerPage.vue";
import Dashboard from "@/views/dashboard.vue";
import Pagination from "v-pagination-3";
import axios from "axios";
import { mapState } from "vuex";
import {
  CTable,
  CTableBody,
  CTableRow,
  CTableDataCell,
  CTableHeaderCell,
  CTableHead,
  CDropdown,
  CDropdownToggle,
  CDropdownMenu,
  CDropdownItem,
} from "@coreui/vue";
export default {
  name: "Orders",
  components: {
    PageHeader,
    ShowingPerPage,
    Dashboard,
    CTable,
    CTableBody,
    CTableRow,
    CTableDataCell,
    CTableHeaderCell,
    CTableHead,
    CDropdown,
    CDropdownToggle,
    CDropdownMenu,
    CDropdownItem,
    Pagination,
  },
  data() {
    return {
      pageTitle: "Orders",
      loading: false,
      bItems: [
        {
          text: this.$t("Home"),
          href: "/",
        },
        {
          text: this.$t("Dashboard"),
          href: "/dashboard",
        },
        {
          text: this.$t("Orders"),
          active: true,
        },
      ],
      tableData: [],
      totalRows: 1,
      currentPage: 1,
      perPage: 10,
      search_key: "",
      filter: null,
      paginationOptions: {
        chunk: 3,
        theme: "bootstrap4",
        hideCount: true,
      },
      pageOptions: [
        {
          label: 10,
          key: 10,
        },
        {
          label: 20,
          key: 20,
        },
        {
          label: this.$t("All"),
          key: null,
        },
      ],
    };
  },
  watch: {
    perPage() {
      if (this.perPage == null) {
        this.perPage = this.totalRows;
      }
    },
  },
  computed: mapState({
    customerToken: (state) => state.customerToken,
  }),
  mounted() {
    document.title = this.$t("Dashboard") + " | " + this.$t("Purchase History");
    this.getCustomerOrders();
  },
  methods: {
    /**
     * Get order list of a customer
     */
    getCustomerOrders() {
      this.loading = true;
      axios
        .post(
          "/api/v1/ecommerce-core/customer/orders",
          {
            perPage: this.perPage,
            page: this.currentPage,
            search_key: this.search_key,
          },
          {
            headers: {
              Authorization: `Bearer ${this.customerToken}`,
            },
          }
        )
        .then((response) => {
          if (response.data.success) {
            this.tableData = response.data.data;
            this.totalRows = response.data.meta.total;
            this.success = true;
            this.loading = false;
          } else {
            this.loading = false;
            this.totalRows = 0;
            this.$toast.error("No Order found");
          }
        })
        .catch((error) => {
          this.totalRows = 0;
          this.loading = false;
          this.$toast.error("No order found");
        });
    },
    searchOrder() {
      this.currentPage = 1;
      this.getCustomerOrders();
    },
  },
};
</script>
