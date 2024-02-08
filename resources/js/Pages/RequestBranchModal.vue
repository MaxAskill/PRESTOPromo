<template>
  <div>
    <div
      class="overflow-x-hidden overflow-y-auto fixed inset-0 z-50 outline-none focus:outline-none md:justify-center md:items-center flex bg-black bg-opacity-50 py-12 sm:px-3 px-2"
    >
      <div class="relative my-auto mx-auto w-max">
        <!--content-->
        <div
          class="border-0 rounded-lg shadow-lg relative w-full bg-white outline-none focus:outline-none"
        >
          <!--header-->
          <div
            class="flex items-center justify-end px-5 py-3 border-b border-solid border-slate-200 rounded-t"
          >
            <h1 class="text-lg font-bold">Request Temporary Branch</h1>
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
          <div class="relative px-5 py-3">
            <div class="grid items-end gap-3 md:grid-cols-2">
              <div class="relative grid grid-cols-1">
                <label class="text-gray-500 text-sm">Company Name</label>
                <el-select
                  v-model="edit.Company"
                  @change="
                    fetchChainCode(),
                      (branchList = []),
                      (edit.ChainCode = ''),
                      (edit.BranchName = '')
                  "
                  placeholder="Select Company"
                >
                  <el-option
                    v-for="company in companyList"
                    :value="company.shortName"
                    :label="company.name + ' (' + company.shortName + ') '"
                  />
                </el-select>
              </div>
              <div class="relative grid grid-cols-1">
                <label class="text-gray-500 text-sm">Chain Name</label>
                <el-select
                  v-model="edit.ChainCode"
                  @change="fetchChainName(), (edit.BranchName = '')"
                  placeholder="Select Chain Name"
                >
                  <el-option
                    v-for="chain in chainCodeList"
                    :value="chain.chainCode"
                    :label="chain.chainCode"
                  />
                </el-select>
              </div>
              <div class="relative grid grid-cols-1">
                <label class="text-gray-500 text-sm">Branch Name</label>
                <el-select v-model="edit.BranchName" placeholder="Select Branch Name">
                  <el-option
                    v-for="branch in branchList"
                    :value="branch.branchName"
                    :label="branch.branchName"
                  />
                </el-select>
              </div>
              <div class="relative grid grid-cols-2 gap-x-2">
                <div class="">
                  <label class="text-gray-500 text-sm">Start Date</label><br />
                  <el-date-picker
                    v-model="edit.StartDate"
                    type="date"
                    :disabled-date="disabledDate"
                    placeholder="Pick a Date"
                  />
                </div>
                <div class="">
                  <label class="text-gray-500 text-sm">End Date</label><br />
                  <el-date-picker
                    v-model="edit.EndDate"
                    type="date"
                    :disabled-date="disabledDate"
                    placeholder="Pick a Date"
                  />
                </div>
              </div>
            </div>
            <div class="flex justify-center">
              <el-button
                class="mt-3"
                type="success"
                plain
                @click="addBranchInput()"
                :disabled="disableAddBranch"
                ><el-icon><Plus /></el-icon>&nbsp;Add This Branch
              </el-button>
            </div>
            <div class="mt-3 grid grid-cols-1" v-if="requestBranch.length > 0">
              <el-table :data="requestBranch" style="width: 100%">
                <el-table-column prop="branch" label="Branch" min-width="180" />
                <el-table-column prop="date_start" label="Start Date" min-width="120" />
                <el-table-column prop="date_end" label="End Date" min-width="130" />
                <el-table-column fixed="right" label="Actions" width="120">
                  <template #default="scope">
                    <el-button
                      v-if="scope.$index != editIndex"
                      type="warning"
                      size="small"
                      @click="editBranchInput(scope.$index, scope.row)"
                      ><el-icon><Edit /></el-icon
                    ></el-button>
                    <el-button
                      v-if="scope.$index == editIndex"
                      type="info"
                      size="small"
                      @click="cancelEditBranchInput()"
                      ><el-icon><Close /></el-icon
                    ></el-button>
                    <el-button
                      type="danger"
                      size="small"
                      @click="removeBranchInput(scope.$index)"
                    >
                      <el-icon><Delete /></el-icon>
                    </el-button>
                  </template>
                </el-table-column>
              </el-table>
            </div>
          </div>
          <!--footer-->
          <div
            class="flex items-center justify-center px-5 py-3 border-t border-solid border-slate-200 rounded-b"
          >
            <el-button type="info" @click="$emit('close')"> Cancel </el-button>
            <el-button
              type="success"
              @click="sendRequestConfirmation()"
              :disabled="disableSendRequest"
            >
              Send Request
            </el-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { ElMessage, ElMessageBox } from "element-plus";
import { DateTime } from "luxon";

export default {
  props: ["branchTemp", "permBranch", "userMultiple"],
  data() {
    return {
      edit: {
        Company: "",
        ChainCode: "",
        BranchName: "",
        StartDate: "",
        EndDate: "",
      },
      requestBranch: [],
      branchList: [],
      companyList: [],
      chainCodeList: [],
      disableAddBranch: true,
      editBranchMode: false,
      editIndex: null,
      isBranchChanged: true,
      disableSendRequest: true,
      removeBranch: [],
      additionalBranch: [],
    };
  },
  watch: {
    edit: {
      handler() {
        this.validateAddBranch();
      },
      deep: true,
    },
    requestBranch: {
      handler() {
        this.validateAddBranch();
      },
      deep: true,
    },
  },
  computed: {},
  mounted() {
    axios
      .get("/fetchCompanyListByUser", {
        params: {
          company: this.$page.props.auth.user.company,
        },
      })
      .then((response) => {
        this.companyList = response.data;
      });
  },
  methods: {
    fetchChainCode() {
      axios
        .get("/fetchChain", {
          params: {
            company: this.edit.Company,
          },
        })
        .then((response) => {
          this.chainCodeList = response.data;
        })
        .catch((error) => {
          //console.error(error);
        });
    },
    fetchChainName() {
      axios
        .get("/fetchChainName", {
          params: {
            chainCode: this.edit.ChainCode,
            company: this.edit.Company,
          },
        })
        .then((response) => {
          this.branchList = response.data;
        })
        .catch((error) => {
          //console.error(error);
        });
    },
    addBranchInput() {
      this.edit.StartDate = DateTime.fromJSDate(new Date(this.edit.StartDate)).toFormat(
        "yyyy-MM-dd"
      );
      this.edit.EndDate = DateTime.fromJSDate(new Date(this.edit.EndDate)).toFormat(
        "yyyy-MM-dd"
      );
      console.log(this.edit.StartDate, typeof this.edit.StartDate);
      var isStartDate = new Date(this.edit.StartDate); //dd-mm-YYYY
      var isEndDate = new Date(this.edit.EndDate); //dd-mm-YYYY
      var today = new Date();
      today.setHours(0, 0, 0, 0);
      if (this.permBranch == this.edit.BranchName) {
        ElMessage.error("It is your Permanent Branch. Kindly select other branch.");
      } else if (
        this.branchTemp.some((branch) => branch.branch === this.edit.BranchName)
      ) {
        ElMessage.error("You already requested this branch. Kindly choose another one.");
      } else if (isStartDate < today) {
        ElMessage.error("Start Date must be today or any date later than today's date.");
      } else if (isEndDate < isStartDate) {
        ElMessage.error("End Date must be equal or any date later than Start date.");
      } else {
        let temp = false;
        if (this.requestBranch.length == 0) temp = true;
        for (let i = 0; i < this.requestBranch.length; i++) {
          if (this.editBranchMode) {
            if (
              i == this.editIndex &&
              this.edit.BranchName != this.requestBranch[i].branch
            ) {
              this.requestBranch[i].company = this.edit.Company;
              this.requestBranch[i].chainCode = this.edit.ChainCode;
              this.requestBranch[i].branch = this.edit.BranchName;
              this.requestBranch[i].date_start = this.edit.StartDate;
              this.requestBranch[i].date_end = this.edit.EndDate;
              this.requestBranch[i].permanent = false;
              break;
            }
          } else {
            if (this.edit.BranchName != this.requestBranch[i].branch) temp = true;
            else {
              temp = false;
              break;
            }
          }
        }
        if (temp)
          this.requestBranch.push({
            company: this.edit.Company,
            chainCode: this.edit.ChainCode,
            branch: this.edit.BranchName,
            date_start: this.edit.StartDate,
            date_end: this.edit.EndDate,
          });
        this.edit.Company = "";
        this.edit.ChainCode = "";
        this.edit.BranchName = "";
        this.edit.StartDate = "";
        this.edit.EndDate = "";
        this.chainCodeList = [];
        this.branchList = [];
        this.editBranchMode = false;
        this.editIndex = null;
      }
    },
    validateAddBranch() {
      if (
        this.edit.BranchName &&
        this.edit.ChainCode &&
        this.edit.Company &&
        this.edit.StartDate &&
        this.edit.EndDate
      )
        this.disableAddBranch = false;
      else this.disableAddBranch = true;

      if (this.requestBranch.length >= 3)
        if (!this.editBranchMode) this.disableAddBranch = true;

      this.validateSendRequest();
    },
    editBranchInput(index, row) {
      this.editBranchMode = true;
      this.editIndex = index;
      this.edit.Company = this.requestBranch[index].company;
      this.edit.ChainCode = this.requestBranch[index].chainCode;
      this.edit.BranchName = this.requestBranch[index].branch;
      this.edit.StartDate = this.requestBranch[index].date_start;
      this.edit.EndDate = this.requestBranch[index].date_end;
      this.fetchChainCode();
      this.fetchChainName();
    },
    cancelEditBranchInput() {
      this.editBranchMode = false;
      this.editIndex = null;
      this.edit.Company = "";
      this.edit.ChainCode = "";
      this.edit.BranchName = "";
      this.edit.StartDate = "";
      this.edit.EndDate = "";
    },
    removeBranchInput(index) {
      this.requestBranch.splice(index, 1);
    },
    validateSendRequest() {
      if (this.requestBranch.length != 0) {
        console.log("true 1");
        if (this.userMultiple.length == this.requestBranch.length) {
          console.log("true 2");
          let temp = false;
          for (let i = 0; i < this.userMultiple.length; i++) {
            for (let j = 0; j < this.requestBranch.length; j++)
              if (this.userMultiple[i].branchName == this.requestBranch[j].branch) {
                temp = true;
                break;
              } else temp = false;
          }
          if (temp) this.isBranchChanged = false;
          else this.isBranchChanged = true;
        }
        if (
          this.requestBranch.length == this.userMultiple.length &&
          !this.isBranchChanged
        )
          this.disableSendRequest = true;
        else this.disableSendRequest = false;
      } else this.disableSendRequest = true;
    },
    sendRequestConfirmation() {
      if (this.requestBranch.length != this.userMultiple.length || this.isBranchChanged) {
        // this.removeBranch = [];
        // this.userMultiple.forEach((temp) => {
        //   var removeB = true;
        //   for (let i = 1; i < this.requestBranch.length; i++) {
        //     if (temp.branchName == this.requestBranch[i].branch) {
        //       removeB = false;
        //       break;
        //     }
        //   }
        //   if (!temp.permanent && removeB) {
        //     this.removeBranch.push({
        //       id: temp.id,
        //       company: temp.company,
        //       chainCode: temp.chainCode,
        //       branch: temp.branchName,
        //     });
        //   }
        // });
        // let removeMessage = "";
        // if (this.removeBranch.length > 0)
        //   removeMessage =
        //     "<span class='spanLabel'>You want to <b>remove</b> this as your Temporary Branch:</span>";
        // this.removeBranch.forEach((rem) => {
        //   removeMessage =
        //     removeMessage + "<br /><span class='divBranch'>" + rem.branch + "</span>";
        // });

        this.additionalBranch = [];
        this.requestBranch.forEach((req) => {
          var additionalB = true;
          for (let i = 1; i < this.userMultiple.length; i++) {
            if (req.branch == this.userMultiple[i].branchName) {
              additionalB = false;
              break;
            }
          }
          if (!req.permanent && additionalB) {
            req.request = "additional";
            this.additionalBranch.push({
              company: req.company,
              chainCode: req.chainCode,
              branch: req.branch,
              date_start: req.date_start,
              date_end: req.date_end,
            });
          }
        });
        let additionalMessage = "";
        if (this.additionalBranch.length > 0) {
          if (this.removeBranch.length > 0) additionalMessage = "<br />";
          additionalMessage =
            additionalMessage +
            "<span class='spanLabel'>You want to <b>add</b> this as your Temporary Branch:</span>";
        }
        this.additionalBranch.forEach((add) => {
          additionalMessage =
            additionalMessage + "<br /><span class='divBranch'>" + add.branch + "</span>";
        });

        ElMessageBox.confirm(
          additionalMessage +
            "<br /><br />Do you want to submit this request to change your assigned branch?",
          "Request for Changing of Branch",
          {
            confirmButtonText: "CONFIRM",
            cancelButtonText: "CANCEL",
            center: true,
            showClose: false,
            dangerouslyUseHTMLString: true,
            closeOnClickModal: false,
            closeOnPressEscape: false,
          }
        )
          .then(() => {
            this.SendRequest();
          })
          .catch(() => {
            ElMessage({
              type: "info",
              message: "Request Canceled",
            });
          });
      }
    },
    SendRequest() {
      // this.removeBranch.forEach((ctr) => {
      //   axios
      //     .post("/postPromoUserBranch", {
      //       userID: this.$page.props.auth.user.id,
      //       company: ctr.company,
      //       chainCode: ctr.chainCode,
      //       branchName: ctr.branch,
      //       req: "remove",
      //       id: ctr.id,
      //     })
      //     .then((response) => {})
      //     .catch((error) => {
      //       console.error(error);
      //     });
      // });
      this.additionalBranch.forEach((ctr) => {
        axios
          .post("/postPromoUserBranch", {
            userID: this.$page.props.auth.user.id,
            company: ctr.company,
            chainCode: ctr.chainCode,
            branchName: ctr.branch,
            date_start: ctr.date_start,
            date_end: ctr.date_end,
            req: "additional",
          })
          .then((response) => {})
          .catch((error) => {
            console.error(error);
          });
      });
      ElMessageBox({
        title: "Your Request was Submitted",
        message:
          "You can contact your authorized officer to immediately approve your request.",
        confirmButtonText: "OKAY",
        type: "success",
        center: true,
        showClose: false,
        closeOnClickModal: false,
        closeOnPressEscape: false,
      }).then(() => {
        this.$emit("fetchData");
        this.$emit("close");
      });
    },
    disabledDate(time) {
      return time.getTime() < Date.now() - 8.64e7;
    },
  },
};
</script>
<style>
.el-input {
  width: 100% !important;
}
.el-message-box__content {
  padding: 5px 15px 10px !important;
}
.el-message-box__title {
  font-size: 14px;
  font-weight: 600;
  text-transform: uppercase;
}

.spanLabel {
  font-size: 0.8571em;
  color: #9a9a9a;
}
.divBranch {
  font-size: 13px;
  font-weight: 600;
}
</style>
