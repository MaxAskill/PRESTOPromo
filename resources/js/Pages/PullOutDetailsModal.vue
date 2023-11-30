<template>
  <div>
    <div
      class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none md:justify-center md:items-center flex bg-black bg-opacity-50 py-12"
    >
      <div class="relative my-auto mx-auto max-w-full px-2 md:px-0">
        <!--content-->
        <div
          class="border-0 rounded-lg shadow-lg relative flex flex-col w-full bg-white outline-none focus:outline-none"
        >
          <!--header-->
          <div
            class="flex items-center justify-end px-2 md:px-5 py-3 border-b border-solid border-slate-200 rounded-t"
          >
            <h1 class="text-lg font-bold">PULL OUT TRANSACTION</h1>
            <el-button
              class="ml-auto"
              size="small"
              type="danger"
              plain
              @click="$emit('close')"
            >
              <el-icon><CloseBold /></el-icon>
            </el-button>
          </div>
          <!--body-->
          <div class="relative px-2 md:px-5 py-3 flex-auto">
            <div class="flex justify-center text-center w-full text-sm">
              <ol
                class="flex items-center w-full text-sm font-medium text-center text-gray-500 dark:text-gray-400 sm:text-base"
              >
                <li
                  :class="{
                    'text-blue-600': transactionActivity > 1,
                  }"
                  class="flex md:w-full items-center dark:text-blue-500 sm:before:content-[''] before:w-full before:h-1 before:border-b before:border-gray-200 before:border-1 before:hidden sm:before:inline-block before:mx-6 xl:before:mx-10 sm:after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10 dark:after:border-gray-700"
                >
                  <span
                    class="flex whitespace-nowrap items-center before:content-['/'] sm:before:hidden before:mx-0 before:text-gray-200 after:content-['/'] sm:after:hidden after:mx-0 after:text-gray-200 dark:after:text-gray-500"
                  >
                    <svg
                      class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-2.5"
                      aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                      />
                    </svg>
                    For Review
                  </span>
                </li>
                <li
                  :class="{
                    'text-blue-600': transactionActivity > 2,
                  }"
                  class="flex md:w-full items-center sm:before:content-[''] before:w-full before:h-1 before:border-b before:border-gray-200 before:border-1 before:hidden sm:before:inline-block before:mx-6 xl:before:mx-10 after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10 dark:after:border-gray-700"
                >
                  <span
                    class="flex whitespace-nowrap items-center dark:text-blue-500 before:content-[''] sm:before:hidden before:mx-2 before:text-gray-200 after:content-[''] sm:after:hidden after:mx-2 after:text-gray-200 dark:after:text-gray-500"
                  >
                    <svg
                      class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-2.5"
                      aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                      />
                    </svg>
                    <!-- <span class="mr-2">2</span> -->
                    For Approval
                  </span>
                </li>
                <li
                  :class="{
                    'text-blue-600': transactionActivity === 3,
                  }"
                  class="flex md:w-full items-center sm:before:content-[''] before:w-full before:h-1 before:border-b before:border-gray-200 before:border-1 before:hidden sm:before:inline-block before:mx-6 xl:before:mx-10 after:content-[''] after:w-full after:h-1 after:border-b after:border-gray-200 after:border-1 after:hidden sm:after:inline-block after:mx-6 xl:after:mx-10 dark:after:border-gray-700"
                >
                  <span
                    class="flex whitespace-nowrap items-center dark:text-blue-500 before:content-['/'] sm:before:hidden before:mx-0 before:text-gray-200 after:content-['/'] sm:after:hidden after:mx-0 after:text-gray-200 dark:after:text-gray-500"
                  >
                    <svg
                      class="w-3.5 h-3.5 sm:w-4 sm:h-4 mr-2.5"
                      aria-hidden="true"
                      xmlns="http://www.w3.org/2000/svg"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"
                      />
                    </svg>
                    Confirmation
                  </span>
                </li>
              </ol>
            </div>
            <br />
            <div class="text-center w-full grid gap-1 md:grid-cols-3 grid-cols-3">
              <div>
                <label class="text-gray-500 text-xs">Transaction Number </label>
                <p class="font-bold md:text-2xl text-2xl">
                  {{
                    pullOutDetails.transactionNumber != "" ? pullOutDetails.plID : "--"
                  }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Status</label>
                <p
                  v-if="pullOutDetails.status === 'endorsement'"
                  class="font-bold md:text-sm text-xs"
                  style="color: #409eff"
                >
                  FOR APPROVAL
                </p>
                <p
                  v-else-if="pullOutDetails.status === 'unprocessed'"
                  class="font-bold md:text-sm text-xs"
                  style="color: #e6a23c"
                >
                  FOR REVIEW
                </p>
                <p
                  v-else-if="pullOutDetails.status === 'approved'"
                  class="font-bold md:text-sm text-xs"
                  style="color: #67c23a"
                >
                  APPROVED
                </p>
                <p v-else class="font-bold md:text-sm text-xs" style="color: #f56c6c">
                  DENIED
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Transaction Type</label>
                <p class="font-bold md:text-sm text-xs">
                  {{
                    pullOutDetails.transactionType != ""
                      ? pullOutDetails.transactionType
                      : "--"
                  }}
                </p>
              </div>

              <div>
                <label class="text-gray-500 text-xs">Branch</label>
                <p class="font-bold md:text-sm text-xs">
                  {{ pullOutDetails.branchName != "" ? pullOutDetails.branchName : "--" }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Created By</label>
                <p class="font-bold md:text-sm text-xs">
                  {{ pullOutDetails.createdBy != "" ? pullOutDetails.createdBy : "--" }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Date Created</label>
                <p class="font-bold md:text-sm text-xs">
                  {{ pullOutDetails.date != "" ? pullOutDetails.date : "--" }}
                </p>
              </div>

              <div>
                <label class="text-gray-500 text-xs">Chain</label>
                <p class="font-bold md:text-sm text-xs">
                  {{ pullOutDetails.chainCode != "" ? pullOutDetails.chainCode : "--" }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Reviewed By</label>
                <p class="font-bold md:text-sm text-xs">
                  {{
                    pullOutDetails.reviewedBy != null ? pullOutDetails.reviewedBy : "--"
                  }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Date Reviewed</label>
                <p class="font-bold md:text-sm text-xs">
                  {{
                    pullOutDetails.reviewedDate != null
                      ? pullOutDetails.reviewedDate
                      : "--"
                  }}
                </p>
              </div>

              <div>
                <label class="text-gray-500 text-xs">Company</label>
                <p class="font-bold md:text-sm text-xs">
                  {{ pullOutDetails.company != "" ? pullOutDetails.company : "--" }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Approved By</label>
                <p class="font-bold md:text-sm text-xs">
                  {{
                    pullOutDetails.approvedBy != null ? pullOutDetails.approvedBy : "--"
                  }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Date Approved</label>
                <p class="font-bold md:text-sm text-xs">
                  {{
                    pullOutDetails.approvedDate != null
                      ? pullOutDetails.approvedDate
                      : "--"
                  }}
                </p>
              </div>

              <div>
                <label class="text-gray-500 text-xs">Authorized Personnel</label>
                <p class="font-bold md:text-sm text-xs">
                  {{
                    pullOutDetails.authorizedPersonnel != null
                      ? pullOutDetails.authorizedPersonnel
                      : "--"
                  }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Pull-Out Started Date</label>
                <p class="font-bold md:text-sm text-xs">
                  {{
                    pullOutDetails.pullOutStartedDate != null
                      ? pullOutDetails.pullOutStartedDate
                      : "--"
                  }}
                </p>
              </div>
              <div>
                <label class="text-gray-500 text-xs">Pull-Out End Date</label>
                <p class="font-bold md:text-sm text-xs">
                  {{
                    pullOutDetails.pullOutEndDate != null
                      ? pullOutDetails.pullOutEndDate
                      : "--"
                  }}
                </p>
              </div>
            </div>
            <br />

            <div v-if="!isMobile">
              <div class="flex space-x-reverse">
                <div class="w-96">
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
              <br />
              <div class="overflow-x-auto w-screen md:w-full text-xs">
                <el-table
                  class="w-full text-center text-xs"
                  :data="queriedTableData"
                  border
                  max-height="360"
                >
                  <el-table-column prop="itemCode" label="Item Code" width="160" />
                  <el-table-column prop="brand" label="Brand/Category" width="200" />
                  <el-table-column
                    prop="boxNumber"
                    label="Box Number"
                    width="150"
                    align="center"
                  />
                  <el-table-column label="Box Label" min-width="400">
                    <template #default="prop">
                      <p class="text-gray-500">
                        BOX NO. {{ prop.row.boxNumber }} OF {{ totalBoxes }}
                        {{ prop.row.boxLabel }}
                      </p>
                    </template>
                  </el-table-column>
                  <el-table-column prop="quantity" label="Quantity" width="120" />
                  <el-table-column prop="amount" label="Amount" width="120">
                    <template #default="prop">
                      <p class="text-gray-500">
                        ₱ {{ prop.row.amount }}
                        <!-- {{ formatBoxAmount(prop.row.amount) }} -->
                      </p>
                    </template>
                  </el-table-column>
                </el-table>
                <br />
              </div>
            </div>

            <div v-else class="flex">
              <div class="overflow-x-auto w-screen md:w-full">
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
                <el-table
                  class="w-full text-center"
                  :data="queriedTableData"
                  border
                  max-height="320"
                >
                  <!-- <el-table-column prop="itemCode" label="Item Code" width="150" />
                  <el-table-column prop="brand" label="Brand/Category" width="250" />
                  <el-table-column prop="boxNumber" label="Box Number" width="150" />
                  <el-table-column prop="boxLabel" label="Box Label" min-width="350" />
                  <el-table-column prop="quantity" label="Quantity" width="120" />
                  <el-table-column prop="amount" label="Amount (P)" min-width="150" /> -->
                  <el-table-column label="List of Items" fixed="right" min-width="200">
                    <template #default="prop">
                      <p class="font-bold text-sm">
                        {{ prop.row.itemCode }} - {{ prop.row.brand }}
                      </p>
                      <p class="text-xs text-gray-500">
                        BOX NO. {{ prop.row.boxNumber }} OF {{ totalBoxes }}
                        {{ prop.row.boxLabel }}
                      </p>
                      <p class="text-xs text-gray-500 text-right">
                        <span>Quantity: </span>{{ prop.row.quantity }}
                      </p>
                      <p
                        class="text-xs text-gray-500 text-right"
                        style="text-align: right"
                      >
                        <span>Amount: </span> {{ formatBoxAmount(prop.row.amount) }}
                      </p>
                    </template>
                  </el-table-column>
                </el-table>
                <br />
              </div>
            </div>

            <div class="flex">
              <table
                class="mt-2 w-full md:w-fit mx-auto border border-collapse border-slate-200 w-fit text-xs"
              >
                <thead>
                  <tr class="bg-gray-100">
                    <th class="px-4 py-2 border border-slate-200">Box Label</th>
                    <th class="px-4 py-2 border border-slate-200">Amount</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(value, key) in totalAmounts" :key="key">
                    <td class="px-4 py-2 border border-slate-200">{{ key }}</td>
                    <td class="px-4 py-2 border border-slate-200">
                      {{ formatBoxAmount(value) }}
                    </td>
                  </tr>
                </tbody>
                <tfoot>
                  <tr class="bg-gray-100">
                    <th class="px-4 py-2 border border-slate-200">Total Amount</th>
                    <th class="px-4 py-2 border border-slate-200">
                      {{ formatAllAmount }}
                    </th>
                  </tr>
                  <tr class="bg-gray-100">
                    <th class="px-4 py-2 border border-slate-200">No. of Items</th>
                    <th class="px-4 py-2 border border-slate-200">{{ totalItems }}</th>
                  </tr>
                  <tr class="bg-gray-100">
                    <th class="px-4 py-2 border border-slate-200">No. of Boxes</th>
                    <th class="px-4 py-2 border border-slate-200">{{ totalBoxes }}</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <br />
            <div class="flex pb-2">
              <label class="ml-2 font-semibold">IMAGES: </label>
            </div>
            <div class="flex sm:max-h-none max-h-40 overflow-y-auto">
              <el-upload
                id="viewImg"
                action="#"
                :limit="10"
                list-type="picture-card"
                :auto-upload="false"
                v-model:file-list="fileImages"
              >
                <el-icon><Plus /></el-icon>

                <template #file="{ file }">
                  <div>
                    <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" />
                    <span class="el-upload-list__item-actions">
                      <span
                        class="el-upload-list__item-preview"
                        @click="handlePictureCardPreview(file)"
                      >
                        <el-icon><zoom-in /></el-icon>
                      </span>
                    </span>
                  </div>
                </template>
              </el-upload>
            </div>
          </div>
          <!--footer-->
          <div
            class="flex items-center justify-end px-5 py-3 border-t border-solid border-slate-200 rounded-b"
          >
            <el-button
              type="warning"
              size="small"
              v-if="pullOutDetails.status === 'denied'"
              @click="denied(pullOutDetails)"
            >
              <el-icon><Edit /></el-icon> EDIT
            </el-button>
          </div>
        </div>
      </div>
    </div>
    <el-dialog v-model="dialogVisible" center width="30%">
      <img w-full :src="dialogImageUrl" alt="Preview Image" />
    </el-dialog>
  </div>
</template>

<script>
import { View, Tickets, Box } from "@element-plus/icons-vue";
import axios from "axios";

export default {
  components: { View, Tickets, Box },
  watch: {
    itemData: {
      handler() {
        this.transfer();
      },
      deep: true,
    },
  },
  props: ["pullOutDetails", "itemData", "viewImages"],
  data() {
    return {
      openDeleteConfirmationModal: false,
      tableData: [],
      overAllTotal: 0,
      isMobile: false,
      fileImages: [],
      dialogVisible: false,
      disableUploadImage: false,
      searchInput: "",
      propsToSearch: ["itemCode", "brand", "boxNumber", "boxLabel", "quantity"],
      pagination: {
        perPage: 5,
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
    overAllAmount() {
      return this.itemData.reduce(
        (total, item) => total + parseFloat(item.amount.replace(/,/g, "")),
        0
      );
    },

    formatAllAmount() {
      return this.overAllAmount.toLocaleString("en-PH", {
        style: "currency",
        currency: "PHP", // Replace with 'PHP' for Philippine Peso or your desired currency code
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    },

    totalItems() {
      let totals = 0;
      for (const item of this.itemData) {
        // console.log("B Amount", item.quantity);
        totals += parseInt(item.quantity);
      }
      return totals.toLocaleString("en-US");
    },
    totalAmounts() {
      const totals = {};

      //filtering the data with box numbers
      const filteredData = this.itemData.filter((obj, index, self) => {
        const boxNumber = obj.boxNumber;
        return self.findIndex((o) => o.boxNumber === boxNumber) === index;
      });

      for (const item of this.itemData) {
        const key = `BOX NO. ${item.boxNumber} OF ${filteredData.length} ${item.boxLabel}`;
        if (!totals[key]) {
          totals[key] = 0;
        }
        totals[key] += parseFloat(item.amount.replace(/,/g, ""));
      }

      return totals;
    },
    totalBoxes() {
      return Object.keys(this.totalAmounts).length;
    },

    transactionActivity() {
      if (this.pullOutDetails.status === "unprocessed") return 1;
      else if (this.pullOutDetails.status === "endorsement") return 2;
      else if (
        this.pullOutDetails.status === "approved" ||
        this.pullOutDetails.status === "denied"
      )
        return 3;
      else return 0;
    },
  },
  mounted() {
    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    this.rowMobile();
  },
  methods: {
    formatBoxAmount(value) {
      const amount = parseFloat(value);

      return amount.toLocaleString("en-PH", {
        style: "currency",
        currency: "PHP", // Replace with 'PHP' for Philippine Peso or your desired currency code
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
      });
    },
    rowMobile() {
      if (this.isMobile) {
        this.pagination.perPage = 3;
      }
    },
    transfer() {
      this.tableData = this.itemData;
      this.fileImages = this.viewImages;
    },
    denied(row) {
      var company1 = row.company.split("(")[1];
      var company = company1.split(")")[0];

      // window.location.href =
      //   "http://192.168.0.7:97/pulloutform?transactionID=" +
      //   row.plID +
      //   "&company=" +
      //   company;

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
        this.convertToAlphanumeric(company);
    },
    handlePictureCardPreview(uploadFile) {
      this.dialogImageUrl = uploadFile.url;
      this.dialogVisible = true;
      // console.log("Image List: ", this.fileImages);
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
<style>
#viewImg .el-upload.el-upload--picture-card {
  width: 0px !important;
  height: 0px !important;
}
#viewImg .el-upload.el-upload--picture-card .el-icon {
  width: 0px !important;
  height: 0px !important;
}
@media only screen and (max-width: 500px) {
  .el-dialog {
    margin: 15vh auto 0px !important;
    border-radius: 10px !important;
    width: max-content !important;
  }
  .el-dialog__body img {
    max-width: 82vw !important;
    max-height: 50vh !important;
  }
}
@media only screen and (min-width: 501px) {
  .el-dialog {
    margin: 10vh auto 0px !important;
    border-radius: 10px !important;
    width: max-content !important;
  }
  .el-dialog__body img {
    max-width: 75vw !important;
    max-height: 70vh !important;
  }
}
@media only screen and (min-width: 960px) {
  .el-dialog__body img {
    max-width: 45vw !important;
    max-height: 70vh !important;
  }
}
@media only screen and (min-width: 1300px) {
  .el-dialog__body img {
    max-width: 35vw !important;
    max-height: 70vh !important;
  }
}
.el-dialog__body img {
  width: 100% !important;
  height: 100% !important;
}
</style>
