<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="py-4 z-0 max-h-[90vh] overflow-y-auto">
      <div class="w-auto px-2.5 3xs:px-3 2xs:px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-4 rounded-xl drop-shadow-lg">
          <div v-if="!isMobile" class="w-96 mb-4">
            <el-input v-model="searchInput" placeholder="Search..." />
          </div>
          <div class="grid grid-cols-1">
            <el-table
              v-if="isMobile"
              :data="queriedTableData"
              style="width: 100%"
              border
              max-height="700"
            >
              <!-- <el-table-column type="index" width="50" /> -->
              <!-- <el-table-column prop="plID" label="Transaction Number" width="160" /> -->
              <!-- <el-table-column prop="branchName" label="Branch Name" min-width="230" />
              <el-table-column
                prop="transactionType"
                label="Transaction Type"
                min-width="200"
              />
              <el-table-column prop="date" label="Creation Date" min-width="100" />
              <el-table-column prop="time" label="Creation Time" min-width="100" /> -->
              <el-table-column fixed="right" min-width="200">
                <template #header>
                  <el-input v-model="searchInput" placeholder="Search..." />
                </template>
                <template #default="scope">
                  <p class="font-bold">{{ scope.row.plID }}</p>
                  <p class="font-bold">{{ scope.row.branchName }}</p>
                  <p>{{ scope.row.transactionType }}</p>
                  <p>{{ scope.row.date }}</p>
                  <p>{{ scope.row.time }}</p>

                  <el-button
                    type="primary"
                    size="small"
                    @click="openModal(scope.row)"
                    v-if="scope.row.status === 'approved'"
                  >
                    APPROVED
                  </el-button>
                  <el-button
                    type="warning"
                    size="small"
                    @click="openModal(scope.row)"
                    v-else-if="scope.row.status === 'unprocessed'"
                  >
                    FOR REVIEW
                  </el-button>
                  <el-button
                    type="success"
                    size="small"
                    @click="openModal(scope.row)"
                    v-else-if="scope.row.status === 'endorsement'"
                  >
                    FOR APPROVAL
                  </el-button>
                  <el-button
                    type="danger"
                    size="small"
                    @click="openModal(scope.row)"
                    v-else
                  >
                    DENIED
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
            <el-table
              v-else
              :data="queriedTableData"
              style="width: 100%"
              border
              max-height="700"
            >
              <el-table-column type="index" width="50" />
              <el-table-column prop="plID" label="Transaction Number" width="160" />
              <el-table-column prop="branchName" label="Branch Name" min-width="230" />
              <el-table-column
                prop="transactionType"
                label="Transaction Type"
                min-width="200"
              />
              <el-table-column prop="date" label="Creation Date" min-width="100" />
              <el-table-column prop="time" label="Creation Time" min-width="100" />
              <el-table-column
                width="140"
                fixed="right"
                label="Status"
                header-align="center"
              >
                <template #default="props">
                  <el-button
                    type="primary"
                    size="small"
                    @click="openModal(props.row)"
                    v-if="props.row.status === 'approved'"
                  >
                    APPROVED
                  </el-button>
                  <el-button
                    type="warning"
                    size="small"
                    @click="openModal(props.row)"
                    v-else-if="props.row.status === 'unprocessed'"
                  >
                    FOR REVIEW
                  </el-button>
                  <el-button
                    type="success"
                    size="small"
                    @click="openModal(props.row)"
                    v-else-if="props.row.status === 'endorsement'"
                  >
                    FOR APPROVAL
                  </el-button>
                  <el-button
                    type="danger"
                    size="small"
                    @click="openModal(props.row)"
                    v-else
                  >
                    DENIED
                  </el-button>
                </template>
              </el-table-column>
            </el-table>
          </div>
        </div>
      </div>
    </div>
    <PullOutDetails
      v-if="openDetailsModal"
      :pullOutDetails="pullOutDetails"
      :itemData="itemData"
      :viewImages="viewImages"
      @close="openDetailsModal = false"
    ></PullOutDetails>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/DashboardLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import { Search } from "@element-plus/icons-vue";
import axios from "axios";
import PullOutDetails from "./PullOutDetailsModal.vue";

export default {
  components: {
    AuthenticatedLayout,
    PullOutDetails,
  },
  data() {
    return {
      openDetailsModal: false,
      pullOutDetails: {
        transactionNumber: "",
        branchName: "",
        transactionType: "",
        chainCode: "",
        creationDate: "",
        creationTime: "",
        status: "",
        createdBy: "",
        reviewedBy: "",
        dateReviewed: "",
        company: "",
        approvedBy: "",
        dateApproved: "",
        authorizedPersonnel: "",
        pullOutStartedDate: "",
        pullOutEndDate: "",
      },
      rowNumber: 5,
      searchInput: "",
      propsToSearch: ["plID", "branchName", "transactionType", "status", "date", "time"],
      tableData: [],
      itemData: [],
      viewImages: [],
      isMobile: false,
    };
  },
  computed: {
    queriedTableData() {
      return this.tableData.filter((row) => {
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
    },
  },
  mounted() {
    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    this.fetchData();
  },
  methods: {
    fetchData() {
      axios
        .get("/fetchUserRequestTransactionList", {
          params: {
            company: this.$page.props.auth.user.company,
            promoEmail: this.$page.props.auth.user.email,
          },
        })
        .then((response) => {
          this.tableData = response.data;

          this.tableData = response.data.map((item, index) => {
            return { index: index + 1, ...item };
          });
        })
        .catch((error) => {
          console.error(error);
        });
    },
    openModal(row) {
      this.viewImages = [];
      this.pullOutDetails = row;

      this.pullOutDetails.transactionNumber = row.transactionNumber;
      this.pullOutDetails.branchName = row.branchName;
      this.pullOutDetails.transactionType = row.transactionType;
      this.pullOutDetails.chainCode = row.chainCode;
      this.pullOutDetails.creationDate = row.creationDate;
      this.pullOutDetails.creationTime = row.creationTime;
      this.pullOutDetails.status = row.status;
      this.pullOutDetails.createdBy = row.createdBy;
      this.pullOutDetails.reviewedBy = row.reviewedBy;
      this.pullOutDetails.dateReviewed = row.dateReviewed;
      this.pullOutDetails.company = row.company;
      this.pullOutDetails.approvedBy = row.approvedBy;
      this.pullOutDetails.dateApproved = row.dateApproved;
      this.pullOutDetails.authorizedPersonnel = row.authorizedPersonnel;
      this.pullOutDetails.pullOutStartedDate = row.pullOutStartedDate;
      this.pullOutDetails.pullOutEndDate = row.pullOutEndDate;

      this.openDetailsModal = !this.openDetailsModal;

      var company1 = row.company.split("(")[1];
      var company = company1.split(")")[0];

      axios
        .get("/fetchPullOutRequestItem", {
          params: {
            plID: row.plID,
            company: company,
          },
        })
        .then((response) => {
          this.itemData = response.data[0];
        })
        .catch((error) => {
          console.error(error);
        });

      console.log("ID", row.plID);
      console.log("Company", company);
      axios
        .get("/fetchImageBranchDoc", {
          params: {
            transactionID: row.plID,
            company: company,
          },
        })
        .then((response) => {
          console.log("Response Images:", response.data);
          response.data.imagePaths.forEach((path) => {
            let name = path.split("/");
            name = name[name.length - 1];
            console.log("path: ", name, path);
            this.viewImages.push({
              name: name,
              url: path,
            });
          });
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
