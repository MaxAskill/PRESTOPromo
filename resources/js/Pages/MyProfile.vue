<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <div class="py-4 z-0 max-h-[90vh] overflow-y-auto">
      <div class="w-auto px-2.5 3xs:px-3 2xs:px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-xl drop-shadow-lg">
          <div class="grid md:grid-cols-5 grid-cols-1 gap-4">
            <div class="md:col-span-2">
              <label class="text-gray-500 text-sm">Name</label>
              <el-input
                v-model.trim="user.name"
                disabled
                placeholder="Employee Name"
                class="w-full"
              />
            </div>
            <div class="md:col-span-2">
              <label class="text-gray-500 text-sm">Email</label>
              <el-input v-model="user.email" disabled placeholder="Email Address" />
            </div>
            <div class="col-span-1">
              <label class="text-gray-500 text-sm">Date Registered</label>
              <el-input
                v-model.trim="user.date"
                disabled
                placeholder="Date Registered"
                class="w-full"
              />
            </div>
            <div class="md:col-span-2">
              <label class="text-gray-500 text-sm">Company</label>
              <el-input
                v-model.trim="user.company"
                disabled
                placeholder="Company Name / Business Unit"
              />
            </div>
            <div class="col-span-1">
              <label class="text-gray-500 text-sm">Chain Name</label>
              <el-input
                v-model.trim="user.chainCode"
                disabled
                placeholder="Chain Name"
                class="w-full"
              />
            </div>
            <div class="md:col-span-2">
              <label class="text-gray-500 text-sm">Branch Name</label>
              <el-input
                v-model.trim="user.branchName"
                disabled
                placeholder="Branch Name"
                class="w-full"
              />
            </div>
          </div>
          <br />
          <div class="grid grid-cols-1" v-show="!editMode">
            <el-table
              :data="tableData"
              header-cell-class-name="tableHeader"
              max-height="350"
              style="width: 100%"
            >
              <!-- Index Column -->
              <el-table-column header-align="center" label="Temporary Branch">
                <el-table-column label="" class="el-table-mod" width="40">
                  <template #default="scope">
                    <span>{{ scope.$index + 1 }}</span>
                  </template>
                </el-table-column>

                <el-table-column
                  v-for="column in tableColumns"
                  :key="column.label"
                  :prop="column.prop"
                  :label="column.label"
                  :min-width="column.minWidth"
                  header-align="center"
                >
                </el-table-column>
                <el-table-column
                  label="Request Status"
                  width="130"
                  header-align="center"
                  align="center"
                >
                  <template #default="scope">
                    <el-tooltip
                      v-if="scope.row.request == 'remove'"
                      content="Pending request to remove this branch."
                      placement="top"
                    >
                      <el-tag class="m-0" type="danger">For Removal</el-tag>
                    </el-tooltip>
                    <el-tooltip
                      v-else-if="scope.row.request == 'additional'"
                      content="Pending request to add this branch."
                      placement="top"
                    >
                      <el-tag class="m-0" color="white" type="warning">Pending</el-tag>
                    </el-tooltip>
                    <el-tooltip
                      v-else
                      content="Your request to add this branch is Approved by the Officer."
                      placement="top"
                    >
                      <el-tag class="m-0" color="white" type="success">Approved</el-tag>
                    </el-tooltip>
                  </template>
                </el-table-column>
                <el-table-column
                  prop="date_start"
                  label="Start Date"
                  width="170"
                  header-align="center"
                  align="center"
                ></el-table-column>
                <el-table-column
                  prop="date_end"
                  label=" End Date"
                  width="170"
                  header-align="center"
                  align="center"
                >
                </el-table-column>
                <el-table-column
                  v-if="user.withRequest"
                  width="50"
                  header-align="center"
                  align="center"
                  fixed="center"
                >
                  <template #default="scope">
                    <el-tooltip
                      v-if="scope.row.request == 'additional'"
                      content="Cancel this request."
                      placement="top"
                    >
                      <el-button
                        type="danger"
                        size="small"
                        @click="cancelRequest(scope.row)"
                      >
                        <el-icon><Close /></el-icon>
                      </el-button>
                    </el-tooltip>
                  </template>
                </el-table-column>
              </el-table-column>
            </el-table>
          </div>
          <br />
          <div class="flex justify-center">
            <!-- <el-tooltip
              content="You still have a pending request. Kindly wait for the approval by your officer."
              placement="bottom"
              :disabled="disableRequestBranchTooltip"
            > -->
            <el-button @click="openModal()" type="primary"
              >Request Temporary Branch</el-button
            >
            <!-- </el-tooltip> -->
          </div>
        </div>
      </div>
    </div>
    <RequestBranchModal
      v-if="openRequestModal"
      :userMultiple="userMultiple"
      :branchTemp="branchMultiple"
      :permBranch="permBranch"
      @fetchData="fetchData()"
      @close="(openRequestModal = false), (branchMultiple = [])"
    ></RequestBranchModal>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/DashboardLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import { Search } from "@element-plus/icons-vue";
import axios from "axios";
import RequestBranchModal from "./RequestBranchModal.vue";
import { DateTime } from "luxon";
import { ElMessage, ElMessageBox } from "element-plus";

export default {
  components: {
    AuthenticatedLayout,
    RequestBranchModal,
  },
  data() {
    return {
      openRequestModal: false,
      user: {
        name: "",
        email: "",
        date: "",
        status: "",
        withRequest: null,
        company: "",
        chainCode: "",
        branchName: "",
      },
      userMultiple: [],
      branchMultiple: [],
      permBranch: "",
      disableRequestBranchTooltip: true,
      tableData: [],
      tableColumns: [
        {
          prop: "branchName",
          label: "Branch Name",
          minWidth: 220,
        },
        {
          prop: "chainCode",
          label: "Chain Name",
          minWidth: 120,
        },
        {
          prop: "company",
          label: "Company",
          minWidth: 150,
        },
      ],
    };
  },
  computed: {
    // dateStart(date) {
    //   return DateTime.fromISO(date).toFormat("MMMM dd, yyyy");
    // },
  },
  mounted() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      axios
        .get("/usersProfile", {
          params: {
            userID: this.$page.props.auth.user.id,
          },
        })
        .then((response) => {
          this.user.name = response.data[0].name;
          this.user.email = response.data[0].email;
          this.user.date = response.data[0].date;
          this.user.status = response.data[0].status;
          this.user.withRequest = response.data[0].withRequest;
          if (response.data[0].position == "User") {
            this.userMultiple = response.data;
          }
          this.tableData = [];
          let ctr = 0;

          var today = new Date();
          today.setHours(0, 0, 0, 0);

          for (let temp of response.data) {
            let tempEndDate = new Date(temp.date_end);
            temp.date_start = DateTime.fromISO(temp.date_start).toFormat("MMMM dd, yyyy");
            temp.date_end = DateTime.fromISO(temp.date_end).toFormat("MMMM dd, yyyy");
            if (!temp.permanent && tempEndDate >= today) this.tableData.push(temp);
            else {
              this.user.company = temp.company;
              this.user.chainCode = temp.chainCode;
              this.user.branchName = temp.branchName;
            }
            if (temp.request == "remove" || temp.request == "additional") ctr++;
          }
          if (ctr > 0) {
            this.user.withRequest = true;
            this.disableRequestBranchTooltip = false;
          } else {
            this.user.withRequest = false;
            this.disableRequestBranchTooltip = true;
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
    openModal() {
      this.userMultiple.forEach((userM) => {
        var company1 = userM.company.split("(")[1];
        var company = company1.split(")")[0];
        if (!userM.permanent)
          this.branchMultiple.push({
            id: userM.id,
            company: company,
            chainCode: userM.chainCode,
            branch: userM.branchName,
            permanent: userM.permanent,
            date_start: userM.date_start,
            date_end: userM.date_end,
          });
        else this.permBranch = userM.branchName;
      });

      this.openRequestModal = !this.openRequestModal;
    },
    cancelRequest(data) {
      var temp = data.company.match(/\((.*?)\)/);
      let company = temp[1];
      ElMessageBox.confirm(
        "Are you sure you want to cancel your request for branch: " +
          data.branchName +
          "?",
        "Cancellation of Request",
        {
          confirmButtonText: "Confirm",
          cancelButtonText: "Cancel",
          type: "warning",
          center: true,
        }
      )
        .then(() => {
          axios
            .post("/deleteUserBranch", {
              userID: this.$page.props.auth.user.id,
              company: company,
              chainCode: data.chainCode,
              branchName: data.branchName,
              req: data.request,
            })
            .then((response) => {
              ElMessage({
                type: "success",
                message: "Your request has been removed.",
              });
              this.fetchData();
            })
            .catch((error) => {
              console.error(error);
            });
        })
        .catch(() => {});
    },
  },
};
</script>
<style>
.tableHeader {
  background-color: transparent !important;
  text-transform: uppercase;
  font-size: 12px;
}
.el-table_1_column_1_column_9.is-center.el-table__cell .cell {
  padding: 0px !important;
}
</style>
