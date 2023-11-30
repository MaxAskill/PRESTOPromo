<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="py-4 z-0 max-h-[90vh] overflow-y-auto">
      <div class="w-auto px-2.5 3xs:px-3 2xs:px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-4 rounded-xl drop-shadow-lg">
          <div v-if="!isMobile" class="flex space-x-reverse">
            <div class="w-96 mb-4">
              <el-input v-model="searchInput" placeholder="Search..." />
            </div>

            <div class="demo-pagination-block ml-auto">
              <div class="flex items-center justify-center">
                <el-pagination
                  v-model:current-page="pagination.currentPage"
                  v-model:page-size="pagination.perPage"
                  :page-sizes="[5, 15, 50, 100]"
                  :small="small"
                  :disabled="disabled"
                  :background="background"
                  layout="sizes, prev, pager, next"
                  :total="pagination.total"
                />
              </div>
            </div>
          </div>
          <div v-if="!isMobile" class="grid grid-cols-1">
            <el-table
              :data="queriedTableData"
              style="width: 100%"
              lazy
              border
              max-height="700"
            >
              <el-table-column width="50">
                <template #default="scope">
                  <span>{{
                    (pagination.currentPage - 1) * pagination.perPage + scope.$index + 1
                  }}</span>
                </template>
              </el-table-column>
              <el-table-column prop="branchName" label="Branch Name" min-width="300" />
              <el-table-column
                prop="transactionType"
                label="Transaction Type"
                min-width="220"
              />
              <el-table-column prop="date" label="Creation Date" min-width="120" />
              <el-table-column prop="time" label="Creation Time" min-width="120" />
              <el-table-column fixed="right" label="Actions" min-width="150">
                <template #default="scope">
                  <el-button
                    type="warning"
                    size="small"
                    @click="handleEdit(scope.$index, scope.row)"
                    ><el-icon><Edit /></el-icon
                  ></el-button>
                  <el-button
                    type="danger"
                    size="small"
                    @click="handleDelete(scope.$index, scope.row)"
                  >
                    <el-icon><Delete /></el-icon>
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>

          <div v-if="isMobile" class="">
            <div class="w-full mb-1">
              <el-input v-model="searchInput" placeholder="Search..." />
            </div>

            <div class="demo-pagination-block mx-auto">
              <div class="flex items-center justify-center">
                <el-pagination
                  v-model:current-page="pagination.currentPage"
                  v-model:page-size="pagination.perPage"
                  :page-sizes="[1, 5, 15, 50, 100]"
                  :small="small"
                  :disabled="disabled"
                  :background="background"
                  layout=" prev, pager, next"
                  :total="pagination.total"
                />
              </div>
            </div>
          </div>
          <div v-if="isMobile" class="grid grid-cols-1">
            <el-table
              :data="queriedTableData"
              style="width: 100%"
              lazy
              border
              max-height="700"
            >
              <el-table-column label="Pull-Out Drafts">
                <template #default="scope">
                  <p class="font-bold">{{ scope.row.branchName }}</p>
                  <p>{{ scope.row.transactionType }}</p>
                  <p>{{ scope.row.date }}</p>
                  <p>{{ scope.row.time }}</p>
                  <el-button
                    type="warning"
                    size="small"
                    @click="handleEdit(scope.$index, scope.row)"
                    ><el-icon><Edit /></el-icon
                  ></el-button>
                  <el-button
                    type="danger"
                    size="small"
                    @click="handleDelete(scope.$index, scope.row)"
                  >
                    <el-icon><Delete /></el-icon>
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/DashboardLayout.vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import { ElMessage, ElMessageBox } from "element-plus";

export default {
  components: {
    AuthenticatedLayout,
  },
  data() {
    return {
      searchInput: "",
      propsToSearch: ["branchName", "transactionType", "date", "time"],
      tableData: [],
      isMobile: false,
      pagination: {
        perPage: 15,
        currentPage: 1,
        perPageOptions: [5, 15, 50, 100],
        total: 0,
      },
      mobileRow: 1,
    };
  },
  computed: {
    pagedData() {
      return this.tableData.slice(this.from, this.to);
    },
    /***
     * Searches through table data and returns a paginated array.
     * Note that this should not be used for table with a lot of data as it might be slow!
     * Do the search and the pagination on the server and display the data retrieved from server instead.
     * @returns {computed.pagedData}
     */
    queriedTableData() {
      if (!this.searchInput) {
        this.pagination.total = this.tableData.length;
        return this.pagedData;
      }
      let result = this.tableData.filter((row) => {
        for (let key of this.propsToSearch) {
          if (
            !this.searchInput ||
            row[key].toString().toLowerCase().includes(this.searchInput.toLowerCase())
          ) {
            return true; // Return true if the condition is met
          }
        }
        return false; // Return false if the condition is not met for any key
      });
      this.pagination.total = result.length;
      return result.slice(this.from, this.to);
    },
    to() {
      let highBound = this.from + this.pagination.perPage;
      if (this.total < highBound) {
        highBound = this.total;
      }
      return highBound;
    },
    from() {
      return this.pagination.perPage * (this.pagination.currentPage - 1);
    },
    total() {
      this.pagination.total = this.tableData.length;
      return this.tableData.length;
    },
  },
  mounted() {
    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    this.fetchData();
    this.rowMobile();
  },
  methods: {
    rowMobile() {
      if (this.isMobile) {
        this.pagination.perPage = 4;
      }
    },
    fetchData() {
      console.log("Company:", this.$page.props.auth.user.company);
      console.log("Promo Email:", this.$page.props.auth.user.email);
      console.log("User ID:", this.$page.props.auth.user.id);

      axios
        .get("/fetchUserRequestDraft", {
          params: {
            company: this.$page.props.auth.user.company,
            promoEmail: this.$page.props.auth.user.email,
            userID: this.$page.props.auth.user.id,
          },
        })
        .then((response) => {
          console.log("Response:", response.data);
          this.tableData = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    handleEdit(index, row) {
      // window.location.href =
      //   "http://192.168.0.7:97/pulloutform?transactionID=" +
      //   row.plID +
      //   "&company=" +
      //   row.company;

      var tempTransactionID = this.convertToAlphanumeric("transactionID");
      var tempcompany = this.convertToAlphanumeric("company");
      location.href =
        "http://192.168.0.7:97/pulloutform?" +
        tempTransactionID +
        "=" +
        row.plID +
        "&" +
        tempcompany +
        "=" +
        this.convertToAlphanumeric(row.company);
    },
    handleDelete(index, row) {
      ElMessageBox.confirm(
        "Are you sure you want to delete this draft?",
        "Deletion of Draft",
        {
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel",
          type: "warning",
          center: true,
          closeOnClickModal: false,
          closeOnPressEscape: false,
        }
      )
        .then(() => {
          ElMessage({
            type: "success",
            message: "Draft has been deleted.",
          });
          let indexToDelete = this.tableData.findIndex(
            (tableRow) => tableRow.plID === row.plID
          );
          if (indexToDelete >= 0) {
            this.tableData.splice(indexToDelete, 1);
          }

          axios
            .post("/deleteDraft", {
              id: row.plID,
              company: row.company,
            })
            .then((response) => {
              console.log("Success Delete:", response.data);
            })
            .catch((error) => {
              console.error(error);
            });
        })
        .catch(() => {
          ElMessage({
            type: "info",
            message: "Deletion of draft is canceled.",
          });
        });
    },
    convertToAlphanumeric(input) {
      let result = "";

      for (let i = 0; i < input.length; i++) {
        const char = input[i];
        const charCode = char.charCodeAt(0);

        // Check if the character is alphanumeric
        if (
          (char >= "0" && char <= "9") ||
          (char >= "a" && char <= "z") ||
          (char >= "A" && char <= "Z")
        ) {
          // Convert the character code to a base-36 alphanumeric representation
          const alphanumericChar = charCode.toString(36);
          result += alphanumericChar;
        } else {
          // Non-alphanumeric characters are left unchanged
          result += char;
        }
      }

      return result;
    },
  },
};
</script>
