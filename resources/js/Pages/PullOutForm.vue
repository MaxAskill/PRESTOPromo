<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
      </template> -->

    <div class="py-4 z-0 max-h-[90vh] overflow-y-auto">
      <div class="w-auto px-2.5 3xs:px-3 2xs:px-4 sm:px-6 lg:px-8">
        <div id="mainForm" ref="mainForm" class="bg-white p-6 rounded-xl drop-shadow-lg">
          <div class="grid items-end gap-6 md:grid-cols-4">
            <div class="relative">
              <label class="text-gray-500 text-sm">Company Name</label>
              <el-select
                v-model="newTransaction.companyName"
                class="w-full"
                placeholder="Select Company Name"
                size="large"
                @change="
                  fetchChainCode(),
                    (newTransaction.chainCode = ''),
                    (newTransaction.branchName = ''),
                    (newTransaction.transactionType = '')
                "
                :disabled="isCompany"
              >
                <el-option
                  v-for="item in listCompanyName"
                  :key="item.id"
                  :label="item.name + ' (' + item.shortName + ')'"
                  :value="item.shortName"
                />
              </el-select>
            </div>
            <div class="relative">
              <label class="text-gray-500 text-sm">Chain Code</label>
              <el-select
                v-model="newTransaction.chainCode"
                class="w-full"
                placeholder="Select Chain Code"
                size="large"
                @change="
                  fetchChainName(),
                    (newTransaction.branchName = ''),
                    (newTransaction.transactionType = '')
                "
                :disabled="isChainCode"
              >
                <el-option
                  v-for="item in listChainCode"
                  :key="item.chainCode"
                  :label="item.chainCode"
                  :value="item.chainCode"
                />
              </el-select>
            </div>
            <div class="relative">
              <label class="text-gray-500 text-sm">Branch Name</label>
              <el-select
                v-model="newTransaction.branchName"
                class="w-full"
                placeholder="Select Branch Name"
                size="large"
                @change="
                  (isTransactionType = false), (newTransaction.transactionType = '')
                "
                :disabled="isBranchName"
              >
                <el-option
                  v-for="item in listBranchName"
                  :key="item.branchName"
                  :label="item.branchName"
                  :value="item.branchName"
                />
              </el-select>
            </div>
            <div class="relative">
              <label class="text-gray-500 text-sm">Transaction Type</label>
              <el-select
                v-model="newTransaction.transactionType"
                class="w-full"
                placeholder="Select Transaction Type"
                size="large"
                :disabled="isTransactionType"
              >
                <el-option
                  v-for="item in listTransactionType"
                  :key="item"
                  :label="item"
                  :value="item"
                />
              </el-select>
            </div>
          </div>
          <br v-if="isShowButton" />
          <div
            class="grid gap-3 2xl:grid-cols-8 xl:grid-cols-6 lg:grid-cols-4 md:grid-cols-3"
            v-show="isShowButton"
          >
            <el-button class="col-span-1" type="success" plain @click="addBoxLabel()"
              >Add Box Label &nbsp;<el-icon><Box /></el-icon
            ></el-button>
            <el-button
              class="col-span-1"
              style="margin: 0px !important"
              :disabled="isEditBLDisabled"
              type="danger"
              plain
              @click="openDeleteModal = !openDeleteModal"
              >Delete Box Label &nbsp;<el-icon><Delete /></el-icon
            ></el-button>
            <el-button
              class="col-span-1"
              style="margin: 0px !important"
              type="primary"
              plain
              >Import Item &nbsp;<el-icon><DocumentAdd /></el-icon>
            </el-button>
          </div>
          <br v-if="isBoxLabel" />
          <div class="flex gap-2" v-if="isBoxLabel">
            <el-select
              class="w-full"
              v-model="newBoxLabel"
              filterable
              placeholder="Select or Enter the Box Label"
            >
              <el-option v-for="item in listBoxLabels" :value="item" />
            </el-select>
            <el-button type="success" plain @click="saveBoxLabel()"
              ><el-icon><Select /></el-icon
            ></el-button>
            <el-button
              type="danger"
              style="margin: 0px !important"
              plain
              @click="cancelBoxLabel()"
              ><el-icon><CloseBold /></el-icon
            ></el-button>
          </div>
          <br />
          <!-- flex xxxxs:max-w-[82vw] xxxs:max-w-[345px] xxs:max-w-[395px] xs:max-w-[445px] sm:max-w-none overflow-x-auto -->
          <div
            class="flex 4xs:max-w-[82vw] 3xs:max-w-[83vw] 2xs:max-w-[84vw] xs:max-w-[86vw] sm:max-w-none overflow-x-auto"
          >
            <div class="grid grid-cols-1">
              <el-collapse>
                <el-collapse-item
                  v-for="(boxLabel, indexBox) in newTransaction.boxLabels"
                  :key="boxLabel.id"
                  :name="boxLabel.boxNumber"
                >
                  <template #title>
                    <p class="whitespace-nowrap">
                      Box No. {{ boxLabel.boxNumber }} of
                      {{ newTransaction.boxLabels.length }} &nbsp;&nbsp;
                      {{ boxLabel.boxLabel }}
                    </p>
                  </template>
                  <div class="flex" v-if="isAddItem">
                    <el-button type="success" plain @click="addItem(boxLabel.boxNumber)">
                      Add Item &nbsp;<el-icon><ShoppingCartFull /></el-icon>
                    </el-button>
                    <el-button
                      v-if="indexBox == deleteItemBtn && multipleSelection.length != 0"
                      type="info"
                      plain
                      @click="clearSelectedItems(indexBox)"
                    >
                      Clear Selection &nbsp;<el-icon><Remove /></el-icon>
                    </el-button>
                    <el-button
                      v-if="indexBox == deleteItemBtn && multipleSelection.length != 0"
                      type="danger"
                      plain
                      @click="deleteSelectedItems"
                    >
                      Delete Items &nbsp;<el-icon><Delete /></el-icon>
                    </el-button>
                  </div>
                  <div
                    class="flex"
                    v-for="newItem in newItemInputBox"
                    v-show="newItem.id === boxLabel.boxNumber"
                  >
                    <div class="items-center gap-2" v-if="newItem.id == showItemInput">
                      <el-radio-group v-model="itemDigitsBarcode">
                        <el-radio-button label="16 Digits" />
                        <el-radio-button label="12 Digits" />
                      </el-radio-group>
                      <el-select
                        v-model="newItemInput"
                        remote
                        :remote-method="fetchItems"
                        @change="compareItemCode()"
                        filterable
                        placeholder="Enter the Item Code"
                      >
                        <el-option
                          v-for="item in itemCodeList"
                          :key="item.ItemNo"
                          :value="item.ItemNo + ' - ' + item.ItemDescription"
                        />
                      </el-select>
                      <el-button
                        type="success"
                        plain
                        @click="saveItem(boxLabel.boxNumber)"
                        ><el-icon><Select /></el-icon
                      ></el-button>
                      <el-button type="danger" plain @click="cancelItem()"
                        ><el-icon><CloseBold /></el-icon
                      ></el-button>
                    </div>
                  </div>
                  <br />

                  <div class="flex">
                    <el-table
                      ref="itemsDataTable"
                      border
                      :data="tableData[indexBox]"
                      style="width: 100%"
                      @select="handleSelect"
                      @select-all="handleSelectAll"
                      :row-class-name="tableRowClassName"
                    >
                      <el-table-column
                        type="selection"
                        width="40"
                        header-align="center"
                        align="center"
                      />
                      <el-table-column label="Item Code" width="160">
                        <template #default="scope">
                          <b>{{ scope.row.code }}</b>
                        </template>
                      </el-table-column>
                      <el-table-column label="Description" min-width="450">
                        <template #default="scope">
                          {{ scope.row.description }}
                        </template>
                      </el-table-column>
                      <el-table-column
                        label="Size"
                        :width="isNBFI ? '60' : ''"
                        :min-width="isNBFI ? '' : '150'"
                      >
                        <template #default="scope">
                          {{ scope.row.size }}
                        </template>
                      </el-table-column>
                      <el-table-column label="Color" width="120">
                        <template #default="scope">
                          {{ scope.row.color }}
                        </template>
                      </el-table-column>
                      <el-table-column
                        :label="isNBFI ? 'Brand' : 'Category'"
                        min-width="300"
                      >
                        <template #default="scope">
                          {{ scope.row.categorybrand }}
                        </template>
                      </el-table-column>
                      <el-table-column prop="quantity" label="Quantity" width="180">
                        <template #default="scope">
                          <el-input-number
                            v-model="scope.row.quantity"
                            :min="0"
                            :max="150"
                          />
                        </template>
                      </el-table-column>
                      <el-table-column label="Box Label" min-width="300">
                        <template #default="scope">
                          <el-select class="w-full" v-model="scope.row.boxNumber">
                            <el-option
                              v-for="boxLabel in newTransaction.boxLabels"
                              :value="boxLabel.boxNumber"
                              :label="
                                'Box No. ' +
                                boxLabel.boxNumber +
                                ' of ' +
                                newTransaction.boxLabels.length +
                                ' ' +
                                boxLabel.boxLabel
                              "
                              :key="boxLabel.id"
                            >
                            </el-option>
                          </el-select>
                        </template>
                      </el-table-column>
                    </el-table>
                  </div>
                </el-collapse-item>
              </el-collapse>
            </div>
          </div>
          <br />
          <div class="flex grid grid-cols-1">
            <div class="mb-2">
              <label class="text-gray-500 text-sm"
                >Upload Photos (Only JPG and PNG files with max 2 MB and 10 images)
              </label>
            </div>
            <div>
              <el-upload
                ref="uploadImage"
                action="#"
                :limit="10"
                list-type="picture-card"
                accept="image/jpeg, image/png"
                :auto-upload="false"
                :on-exceed="handleExceedImage"
                :on-preview="handlePictureCardPreview"
                :http-request="handleFileSuccess"
                v-model:file-list="fileImages"
              >
                <el-icon><Plus /></el-icon>

                <!-- <template #file="{ file }">
                  <div>
                    <img class="el-upload-list__item-thumbnail" :src="file.url" alt="" />
                    <span class="el-upload-list__item-actions">
                      <span
                        class="el-upload-list__item-preview"
                        @click="handlePictureCardPreview(file)"
                      >
                        <el-icon><zoom-in /></el-icon>
                      </span>
                      <span
                        class="el-upload-list__item-delete"
                        @click="handleRemove(file)"
                      >
                        <el-icon><Delete /></el-icon>
                      </span>
                    </span>
                  </div>
                </template> -->
              </el-upload>

              <el-dialog v-model="dialogVisible" center width="30%">
                <img w-full :src="dialogImageUrl" alt="Preview Image" />
              </el-dialog>
            </div>
          </div>
          <br />
          <div class="flex justify-center items-center gap-3">
            <el-button @click="openMessageBox('draft')" type="warning"
              >Save as Draft</el-button
            >
            <el-tooltip
              :content="tooltipSubmit"
              :disabled="!isDisabledSubmit"
              placement="bottom"
              effect="light"
            >
              <span>
                <el-button
                  @click="openMessageBox('submit')"
                  type="success"
                  :disabled="isDisabledSubmit"
                  >Submit</el-button
                >
              </span></el-tooltip
            >
          </div>
        </div>
      </div>
    </div>

    <DeleteBoxLabelModal
      :transferredData="newTransaction"
      :newItemInputBox="newItemInputBox"
      v-if="openDeleteModal"
      @close="openDeleteModal = false"
      @TransferDataBoxNumber="reArrangeBoxNumber($event)"
      @DeletedBoxNumber="reArrangeItems($event)"
    >
    </DeleteBoxLabelModal>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/DashboardLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";
import axios from "axios";
import DeleteBoxLabelModal from "./DeleteBoxLabelModal.vue";
import { ElMessage, ElMessageBox } from "element-plus";

export default {
  components: {
    AuthenticatedLayout,
    DeleteBoxLabelModal,
  },
  data() {
    return {
      switchItemCode: false,
      openDeleteModal: false,
      newTransaction: {
        companyName: "",
        chainCode: "",
        branchName: "",
        transactionType: "",
        boxLabels: [],
        items: [],
      },
      newItemInputBox: [],
      itemCodeList: [],
      listCompanyName: [],
      listChainCode: [],
      listBranchName: [],

      isValid: {
        company: false,
        chainCode: false,
        branchName: false,
        transactionType: false,
        boxLabel: false,
        item: false,
      },

      listTransactionType: [
        "CPO Item for Disposal in the Store c/o Supervisor",
        "CPO for Transfer to Another Store",
        "CPO Back to WH via In-House Delivery Service",
        "CPO Back to WH via Chain Distribution Center",
        "CPO Back to WH via 3rd Party Trucking",
        "CPO Back to WH c/o Supervisor",
      ],

      listBoxLabels: [
        "CLOSED STORE/BRANCH - GOOD ITEMS",
        "CLOSED STORE/BRANCH - DAMAGED/DIRTY ITEMS",
        "CLOSED STORE/BRANCH - DISPOSAL/CONTAINS BROKEN GLASS ITEMS",
        "REGULAR PULL-OUT - GOOD ITEMS",
        "REGULAR PULL-OUT - DAMAGED/DIRTY ITEMS",
        "REGULAR PULL-OUT - DISPOSAL/CONTAINS BROKEN GLASS ITEMS",
        "STORE TO STORE/BRANCH TO BRANCH - GOOD ITEMS",
        "STORE TO STORE/BRANCH TO BRANCH - DAMAGED/DIRTY ITEMS",
        "STORE TO STORE/BRANCH TO BRANCH - DISPOSAL/CONTAINS BROKEN GLASS ITEMS",
      ],

      tooltipSubmit: "Complete the form above to enable this button.",
      isCompany: false,
      isChainCode: true,
      isBranchName: true,
      isTransactionType: true,
      isShowButton: false,
      isEditBLDisabled: false,
      isBoxLabel: false,
      isItem: false,
      isAddItem: true,
      isDisabledSubmit: true,

      newBoxLabel: "",
      isNewBoxLabel: false,
      isBoxLabel: false,
      isItem: false,
      isAddItem: true,
      isRightCode: false,
      isNewItem: false,
      isDraft: false,
      showItemInput: "",
      itemDigitsBarcode: "16 Digits",
      saving_counter: null,
      transferTransactionID: null,
      newItemInput: "",
      newItemDescription: "",
      newStyleCode: "",
      newItemCode: "",
      newBrand: "",

      tableData: [],
      multipleSelection: [],
      deleteItemBtn: null,
      isNBFI: false,

      dialogImageUrl: "",
      dialogVisible: false,
      disableUploadImage: false,
      imageUrl: [],
      fileImages: [],
      isMobile: false,
    };
  },
  mounted() {
    setTimeout(() => {
      window.location.reload();
    }, 3000);
    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    this.fetchEdit();
    this.fetchCompany();
  },
  watch: {
    newTransaction: {
      handler(val) {
        this.validateSubmit();
        this.showButtons();
      },
      deep: true,
    },
    fileImages: {
      handler(val) {
        this.validateImage();
      },
      deep: true,
    },
    "newTransaction.items": {
      handler(val) {
        this.addCategoryBoxLabel();
        this.createTableData();
      },
      deep: true,
    },
    "newTransaction.boxLabels": {
      handler(val) {
        this.enableDropDowns();
        this.createTableData();
      },
      deep: true,
    },
  },
  methods: {
    validateSubmit() {
      let uniqueItems = [
        ...new Set(this.newTransaction.items.map((item) => item.boxNumber)),
      ];
      let itemsValidation = false;
      let itemsValidationTemp = false;
      for (let j in this.newTransaction.boxLabels) {
        for (let i in uniqueItems) {
          if (uniqueItems[i] == this.newTransaction.boxLabels[j].id) {
            itemsValidationTemp = true;
            itemsValidation = true;
            break;
          } else {
            itemsValidation = false;
            itemsValidationTemp = false;
          }
        }
      }
      for (let x in this.newTransaction.items) {
        if (this.newTransaction.items[x].quantity == 0) {
          itemsValidation = false;
          this.tooltipSubmit = "No items should have a quantity of 0.";
          break;
        }
      }
      if (!itemsValidationTemp)
        this.tooltipSubmit = "Kindly add item/s on every boxes you've added.";

      if (
        !this.newTransaction.companyName ||
        !this.newTransaction.chainCode ||
        !this.newTransaction.branchName ||
        !this.newTransaction.transactionType
      )
        this.tooltipSubmit = "Complete the form above to enable this button.";
      else if (this.newTransaction.boxLabels.length <= 0)
        this.tooltipSubmit = "No box added. Kindly add box/es to be able to add item/s.";
      else if (this.newTransaction.items.length <= 0)
        this.tooltipSubmit =
          "No items added. Kindly add item/s for this pull-out request.";

      if (
        this.newTransaction.companyName &&
        this.newTransaction.chainCode &&
        this.newTransaction.branchName &&
        this.newTransaction.transactionType &&
        this.newTransaction.boxLabels.length > 0 &&
        itemsValidation
      ) {
        this.isDisabledSubmit = false;
        this.tooltipSubmit = "";
        //disabled tooltip
      } else this.isDisabledSubmit = true;
    },
    showButtons() {
      if (
        this.newTransaction.companyName &&
        this.newTransaction.chainCode &&
        this.newTransaction.branchName &&
        this.newTransaction.transactionType
      ) {
        this.isShowButton = true;
        if (this.newTransaction.boxLabels.length == 0) this.isEditBLDisabled = true;
        else this.isEditBLDisabled = false;
      } else this.isShowButton = false;
    },
    fetchCompany() {
      axios
        .get("/fetchCompanyByUser", {
          params: {
            userID: this.$page.props.auth.user.id,
          },
        })
        .then((response) => {
          this.listCompanyName = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchChainCode() {
      if (
        this.newTransaction.companyName == "NBFI" ||
        this.newTransaction.companyName == "CMC" ||
        this.newTransaction.companyName == "ASC"
      )
        this.isNBFI = true;
      else this.isNBFI = false;

      axios
        .get("/fetchChainByUser", {
          params: {
            company: this.newTransaction.companyName,
            userID: this.$page.props.auth.user.id,
          },
        })
        .then((response) => {
          this.listChainCode = response.data;
        })
        .catch((error) => {
          console.error(error);
        });

      this.isChainCode = false;
    },
    fetchChainName() {
      axios
        .get("/fetchChainNameByUser", {
          params: {
            chainCode: this.newTransaction.chainCode,
            userID: this.$page.props.auth.user.id,
          },
        })
        .then((response) => {
          this.listBranchName = response.data;
        })
        .catch((error) => {
          console.error(error);
        });

      this.isBranchName = false;
    },
    fetchItems(itemInput) {
      if (itemInput.length >= 4) {
        if (
          this.newTransaction.companyName == "NBFI" ||
          this.newTransaction.companyName == "CMC" ||
          this.newTransaction.companyName == "ASC"
        ) {
          axios
            .get("/fetchItemsNBFI", {
              params: {
                ItemNo: itemInput,
                barcode: this.itemDigitsBarcode,
              },
            }) // Make a GET request to the specified URL
            .then((response) => {
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data
            })
            .catch((error) => {
              // console.error(error.reponse); // Handle any errors that may occur
            });
        } else {
          axios
            .get("/fetchItems", {
              params: {
                ItemNo: itemInput,
              },
            })
            .then((response) => {
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data
            })
            .catch((error) => {
              // console.error(error.reponse); // Handle any errors that may occur
            });
        }
      } else if (itemInput.length == 0) this.itemCodeList = [];
    },
    fetchEdit() {
      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];
        var company = transactionID.split("=")[2];
        this.isDraft = true;
        axios
          .get("/fetchEditDraftBranch", {
            params: {
              company: company,
              plID: id,
            },
          })
          .then((response) => {
            this.newTransaction.companyName = response.data[0].company;
            this.newTransaction.branchName = response.data[0].branchName;
            this.newTransaction.chainCode = response.data[0].chainCode;
            this.newTransaction.transactionType = response.data[0].transactionType;

            if (
              response.data[0].status == "denied" ||
              response.data[0].status == "endorsement" ||
              response.data[0].status == "unprocessed"
            ) {
              this.isDenied = false;
              this.isCancel = true;
            }

            if (response.data[0].status == "endorsement") {
              this.isApproved = true;
              this.isSubmit = false;
            }

            axios
              .get("/fetchEditDraftItem", {
                params: {
                  company: company,
                  plID: id,
                },
              })
              .then((response) => {
                for (var x = 0; x < response.data.length; x++) {
                  this.newTransaction.items.push(response.data[x]);
                }

                const filteredData = this.newTransaction.items.filter(
                  (obj, index, self) => {
                    const boxLabel = obj.boxLabel;
                    return self.findIndex((o) => o.boxLabel === boxLabel) === index;
                  }
                );

                const boxData = filteredData.map((obj) => {
                  return {
                    boxLabel: obj.boxLabel,
                    boxNumber: obj.boxNumber,
                  };
                });

                for (var x = 0; x < boxData.length; x++) {
                  this.newTransaction.boxLabels.push({
                    id: boxData[x].boxNumber,
                    boxNumber: boxData[x].boxNumber,
                    boxLabel: boxData[x].boxLabel,
                  });
                  this.newItemInputBox.push({
                    id: boxData[x].boxNumber,
                  });
                }

                this.isDraft = false;
                if (this.newTransaction.companyName) {
                  this.isCompany = false;
                  this.isChainCode = false;
                  this.fetchCompany();
                  this.fetchChainCode();
                }

                if (this.newTransaction.chainCode) {
                  this.isChainCode = false;
                  this.isBranchName = false;
                  this.fetchChainName();
                  this.fetchChainCode();
                }

                if (this.newTransaction.branchName) {
                  this.isBranchName = false;
                  this.isTransactionType = false;
                  this.fetchChainName();
                }

                if (this.newTransaction.transactionType) {
                  this.isCompany = false;
                  this.isChainCode = false;
                  this.isBranchName = false;
                  this.isTransactionType = false;
                  // this.isShowButton = true;
                }

                if (this.newTransaction.items.length) {
                  this.isCompany = true;
                  this.isChainCode = true;
                  this.isBranchName = true;
                  this.isTransactionType = true;
                  // this.isShowButton = true;
                }
              })
              .catch((error) => {
                // console.error(error);
              });
          })
          .catch((error) => {
            // console.error(error);
          });
      } catch {
        //Fetching Promo Info
        axios
          .get("/fetchPromoBranchInfo", {
            params: {
              userID: this.$page.props.auth.user.id,
            },
          })
          .then((response) => {
            this.newTransaction.companyName = response.data[0].company;
            this.newTransaction.chainCode = response.data[0].chainCode;
            this.newTransaction.branchName = response.data[0].branchName;
            if (this.newTransaction.branchName) this.isTransactionType = false;
            this.fetchChainCode();
            this.fetchChainName();
          })
          .catch((error) => {
            // console.error(error);
          });
      }
    },
    compareItemCode() {
      let value = this.newItemInput;
      if (value.indexOf(" - ") >= 0) {
        let parts = value.split(" - ");
        this.newItemInput = parts[0];
      }
    },
    addBoxLabel() {
      this.isBoxLabel = true;
      this.isEditBLDisabled = true;
    },
    saveBoxLabel() {
      this.isNewBoxLabel = !this.newBoxLabel ? true : false;
      if (this.isNewBoxLabel) return 0;

      this.isBoxLabel = false;
      let tempBoxLabel = [];

      if (this.newTransaction.boxLabels.length == 0) {
        tempBoxLabel = {
          id: this.newTransaction.boxLabels.length + 1,
          boxNumber: this.newTransaction.boxLabels.length + 1,
          boxLabel: this.newBoxLabel,
        };
      } else {
        tempBoxLabel = {
          id:
            parseInt(
              this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1].id
            ) + 1,
          boxNumber: this.newTransaction.boxLabels.length + 1,
          boxLabel: this.newBoxLabel,
        };
      }

      let tempItem = [];

      if (this.newTransaction.boxLabels.length == 0) {
        tempItem = {
          id: this.newTransaction.boxLabels.length + 1,
        };
      } else {
        tempItem = {
          id:
            parseInt(
              this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1].id
            ) + 1,
        };
      }

      this.newTransaction.boxLabels.push(tempBoxLabel);
      this.newItemInputBox.push(tempItem);
      this.newBoxLabel = "";
      //Disable the above select buttons
      this.isCompany = true;
      this.isChainCode = true;
      this.isBranchName = true;
      this.isTransactionType = true;
      this.isEditBLDisabled = false;
    },
    cancelBoxLabel() {
      this.isNewBoxLabel = false;
      this.isBoxLabel = false;
      this.newBoxLabel = "";
      if (this.newTransaction.boxLabels.length == 0) this.isEditBLDisabled = true;
      else this.isEditBLDisabled = false;
    },
    addItem(boxNUMBER) {
      this.isItem = false;
      this.isAddItem = false;
      this.showItemInput = boxNUMBER;
    },
    saveItem(boxNUMBER) {
      if (this.itemDigitsBarcode == "16 Digits") {
        if (this.newItemInput.length > 16)
          this.newItemInput = this.newItemInput.slice(0, 16);
      } else {
        this.newItemInput = this.newItemInput.slice(0, 12);
      }
      if (this.itemDigitsBarcode == "12 Digits") {
        axios
          .get("/fetchItemsBarcode", {
            params: {
              ItemNo: this.newItemInput,
              company: this.newTransaction.companyName,
            },
          })
          .then((response) => {
            this.newItemInput = response.data[0].ItemNo;
          })
          .catch((error) => {
            console.error(error);
          });
      }
      var checkItemData = true;
      setTimeout(() => {
        axios
          .get("/compareItemCode", {
            params: {
              companyType: this.newTransaction.companyName,
              ItemNo: this.newItemInput,
            },
          })
          .then((response) => {
            if (response.data.length == 0) checkItemData = false;

            this.newItemCode = response.data[0].ItemNo;
            this.newItemDescription = response.data[0].ItemDescription;
            this.newStyleCode = response.data[0].StyleCode;

            let brandCode = response.data[0].ItemNo.toString().substr(1, 2);
            axios
              .get("/fetchBrands", {
                params: {
                  companyType: this.newTransaction.companyName,
                  brandCode: brandCode,
                },
              })
              .then((response) => {
                this.newBrand = response.data[0].brandNames;
              })
              .catch((error) => {
                console.error(error);
              });
          })
          .catch((error) => {
            !this.newItemInput ? true : (this.isRightCode = true);
          });
      }, 300);
      var newResponseData;

      setTimeout(() => {
        if (checkItemData) {
          this.isNewItem = !this.newItemInput ? true : false;

          if (this.isNewItem) {
            this.isRightCode = false;
            return 0;
          }
          axios
            .get("/fetchSameItem", {
              params: {
                company: this.newTransaction.companyName,
                ItemCode: this.newItemCode,
                ItemDescription: this.newItemDescription,
                StyleCode: this.newStyleCode,
              },
            })
            .then((response) => {
              newResponseData = response.data;
            })
            .catch((error) => {
              //console.error(error);
            });
        }
      }, 500);

      setTimeout(() => {
        if (checkItemData) {
          for (var x = 0; x < newResponseData.length; x++) {
            var flag = true;
            for (var i = 0; i < this.newTransaction.items.length; i++) {
              if (
                this.newTransaction.items[i].code == newResponseData[x].ItemNo &&
                this.newTransaction.items[i].boxNumber == boxNUMBER
              ) {
                this.newTransaction.items[i].quantity =
                  parseInt(this.newTransaction.items[x].quantity) + 1;
                flag = false;
                break;
              }
            }
            if (flag) {
              if (
                this.newTransaction.company == "NBFI" ||
                this.newTransaction.company == "CMC" ||
                this.newTransaction.company == "ASC"
              ) {
                var categorybrand = this.newBrand;
              } else {
                var categorybrand = newResponseData[x].Category;
              }
              let tempItem = {
                code: newResponseData[x].ItemNo,
                description: newResponseData[x].ItemDescription,
                categorybrand: categorybrand,
                quantity: 0,
                size: newResponseData[x].Size,
                color: newResponseData[x].Color,
                // boxLabel: label,
                boxNumber: boxNUMBER,
                category: newResponseData[x].Category,
              };
              this.newTransaction.items.push(tempItem);
            }
            this.isRightCode = false;
            this.isItem = false;
            this.isAddItem = true;
            this.newItemInput = "";
            this.showItemInput = "";
          }
        }
      }, 1000);
    },
    cancelItem() {
      this.isRightCode = false;
      this.isNewItem = false;
      this.isItem = false;
      this.isAddItem = true;
      this.showItemInput = "";
      this.newItemInput = "";
      this.itemDigitsBarcode = "16 Digits";
    },
    createTableData() {
      this.tableData = [];
      this.multipleSelection = [];
      this.newTransaction.boxLabels.forEach((box, key) => {
        this.tableData.push([]);
        this.newTransaction.items.forEach((item) => {
          if (box.boxNumber == item.boxNumber) this.tableData[key].push(item);
        });
      });
    },
    handleSelectAll(val) {
      if (val.length == 0) {
        this.multipleSelection = [];
        this.deleteItemBtn = null;
      } else this.deleteItemBtn = val[0].boxNumber - 1;

      val.forEach((row) => {
        let temp = true;
        for (let [index, mul] of this.multipleSelection.entries())
          if (row.boxNumber == mul.boxNumber && row.code == mul.code) {
            temp = false;
            break;
          }
        if (temp) this.multipleSelection.push(row);

        if (this.multipleSelection.length > 0) {
          const selectedItemsOther = this.multipleSelection.filter(
            (mul) => mul.boxNumber !== val[0].boxNumber
          );
          selectedItemsOther.forEach((rowSel) => {
            this.$refs.itemsDataTable[parseInt(rowSel.boxNumber - 1)].toggleRowSelection(
              rowSel
            );
          });
          const selectedItems = this.multipleSelection.filter(
            (mul) => mul.boxNumber === val[0].boxNumber
          );
          if (selectedItems.length > 0) this.multipleSelection = selectedItems;
        }
      });
    },
    handleSelect(val, row) {
      if (val.length == 0) this.deleteItemBtn = null;
      else this.deleteItemBtn = row.boxNumber - 1;

      let temp = true;
      for (let [index, mul] of this.multipleSelection.entries())
        if (row.boxNumber == mul.boxNumber && row.code == mul.code) {
          this.multipleSelection.splice(index, 1);
          temp = false;
          break;
        }
      if (temp) this.multipleSelection.push(row);

      if (this.multipleSelection.length > 0) {
        const selectedItemsOther = this.multipleSelection.filter(
          (mul) => mul.boxNumber !== val[0].boxNumber
        );
        selectedItemsOther.forEach((rowSel) => {
          this.$refs.itemsDataTable[parseInt(rowSel.boxNumber - 1)].toggleRowSelection(
            rowSel
          );
        });
        const selectedItems = this.multipleSelection.filter(
          (mul) => mul.boxNumber === val[0].boxNumber
        );
        if (selectedItems.length > 0) this.multipleSelection = selectedItems;
      }
    },
    tableRowClassName({ row, rowIndex }) {
      if (row.quantity == 0) return "warning-row";
      else return "";
    },
    clearSelectedItems(index) {
      this.$refs.itemsDataTable[index].clearSelection();
      this.deleteItemBtn = null;
    },
    deleteSelectedItems() {
      this.multipleSelection.forEach((selected) => {
        this.removeItem(selected.code, selected.boxNumber);
      });
      this.deleteItemBtn = null;
    },
    removeItem(code, boxNumber) {
      // this.validateSubmit();
      for (let key in this.newTransaction.items)
        if (
          this.newTransaction.items[key].code === code &&
          this.newTransaction.items[key].boxNumber === boxNumber
        ) {
          this.newTransaction.items.splice(key, 1);
          break;
        }
    },
    reArrangeItems(deletedBoxNumber) {
      this.newTransaction.items.forEach((temp) => {
        if (deletedBoxNumber < temp.boxNumber) temp.boxNumber--;
      });
    },
    reArrangeBoxNumber(transfer) {
      this.newTransaction.boxLabels = transfer;
      this.newItemInputBox = [];
      this.newTransaction.boxLabels.forEach((boxLabel) => {
        var tempIdBox = {
          id: boxLabel.id,
        };
        this.newItemInputBox.push(tempIdBox);
      });
    },
    handleRemove(uploadFile, uploadFiles) {
      console.log("Image File", uploadFile, uploadFiles, this.$refs.uploadImage);
    },
    handlePictureCardPreview(uploadFile) {
      this.dialogImageUrl = uploadFile.url;
      this.dialogVisible = true;
    },
    handleExceedImage(files, uploadFiles) {
      this.disableUploadImage = true;
      ElMessage.warning(
        `The limit is 10 images, you selected ${
          files.length
        } files this time, add up to ${files.length + uploadFiles.length} totally`
      );
    },
    handleFileSuccess(file) {
      axios
        .post(
          "/upload",
          {
            image: file.file,
            company: this.newTransaction.companyName,
            branchName: this.newTransaction.branchName,
            transactionID: this.transferTransactionID,
          },
          {
            headers: {
              "content-type": "multipart/form-data",
            },
          }
        )
        .then((response) => {
          this.saving_counter = this.saving_counter - 2;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    validateImage() {
      const maxSizeInBytes = 2 * 1024 * 1024; // 2MB
      if (this.fileImages[this.fileImages.length - 1].size > maxSizeInBytes) {
        this.fileImages.splice(-1, 1);
        ElMessage.error("Please select an image file smaller than 2MB.");
      }
    },
    addCategoryBoxLabel() {
      if (this.isDraft == false) {
        this.newTransaction.boxLabels.forEach((box) => {
          var filteredItems = this.newTransaction.items.filter(
            (item) => item.boxNumber === box.boxNumber
          );
          var uniqueCategory = [
            ...new Set(filteredItems.map((item) => item.categorybrand)),
          ];
          let strCategory = "";
          for (let i = 0; i < uniqueCategory.length; i++) {
            if (i == 0) strCategory = " [ " + uniqueCategory[i];
            else strCategory = strCategory + ", " + uniqueCategory[i];
            if (i == uniqueCategory.length - 1) strCategory = strCategory + " ]";
          }
          let tempIndex = box.boxLabel.indexOf("[");
          if (tempIndex > 0) box.boxLabel = box.boxLabel.substr(0, tempIndex).trim();
          box.boxLabel = box.boxLabel + strCategory;
        });
      }
    },
    enableDropDowns() {
      if (this.newTransaction.boxLabels.length == 0) {
        this.isCompany = false;
        this.isChainCode = false;
        this.isBranchName = false;
        this.isTransactionType = false;
        this.isEditBLDisabled = true;
      } else this.isEditBLDisabled = false;
    },
    // editBoxLabel(code, quantity, boxNumber) {
    //   console.log("Itemsss: ", code, quantity, boxNumber);
    //   // this.validateSubmit();
    //   var filteredItems = this.newTransaction.items.filter(
    //     (item) => item.code === code && item.boxNumber === boxNumber
    //   );

    //   var uniqueItems = [];
    //   filteredItems.forEach((item) => {
    //     var existingItem = uniqueItems.find(
    //       (uniqueItem) => uniqueItem.code === item.code
    //     );
    //     if (existingItem) {
    //       existingItem.quantity += item.quantity;
    //     } else {
    //       uniqueItems.push({ ...item });
    //     }
    //   });

    //   this.newTransaction.items = this.newTransaction.items.filter(
    //     (item) =>
    //       item.code !== uniqueItems[0].code || item.boxNumber !== uniqueItems[0].boxNumber
    //   );
    //   this.newTransaction.items.push(uniqueItems[0]);
    // },
    submit() {
      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];

        this.isValid.company = !this.newTransaction.companyName ? true : false;
        this.isValid.chainCode = !this.newTransaction.chainCode ? true : false;
        this.isValid.branchName = !this.newTransaction.branchName ? true : false;
        this.isValid.transactionType = !this.newTransaction.transactionType
          ? true
          : false;
        this.isValid.boxLabel = !this.newTransaction.boxLabels.length ? true : false;
        this.isValid.item = !this.newTransaction.items.length ? true : false;

        var status = "unprocessed";

        axios
          .post("/updatePullOutBranchRequest", {
            id: id,
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.company,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            email: this.$page.props.auth.user.email,
            status: status,
          })
          .then((response) => {
            this.transferTransactionID = id;

            for (var x = 0; x < this.newTransaction.items.length; x++) {
              let labelBox = "";
              for (let box of this.newTransaction.boxLabels) {
                if (box.id == this.newTransaction.items[x].boxNumber) {
                  labelBox = box.boxLabel;
                }
              }

              axios
                .post("/updatePullOutItemRequest", {
                  id: this.newTransaction.items[x].id,
                  plID: id,
                  companyType: this.newTransaction.companyName,
                  brand: this.newTransaction.items[x].categorybrand,
                  boxNumber: this.newTransaction.items[x].boxNumber,
                  boxLabel: labelBox,
                  itemCode: this.newTransaction.items[x].code,
                  quantity: this.newTransaction.items[x].quantity,
                  email: this.$page.props.auth.user.email,
                  status: status,
                })
                .then((response) => {})
                .catch((error) => {
                  console.error(error);
                });
            }
            this.saving_counter = this.fileImages.length * 2;
            this.openSubmitMessageBox();
            setTimeout(this.$refs.uploadImage.submit(), 500);
          })
          .catch((error) => {
            //console.error(error);
          });
      } catch {
        this.isValid.company = !this.newTransaction.companyName ? true : false;
        this.isValid.chainCode = !this.newTransaction.chainCode ? true : false;
        this.isValid.branchName = !this.newTransaction.branchName ? true : false;
        this.isValid.transactionType = !this.newTransaction.transactionType
          ? true
          : false;
        this.isValid.boxLabel = !this.newTransaction.boxLabels.length ? true : false;
        this.isValid.item = !this.newTransaction.items.length ? true : false;

        axios
          .post("/savePullOutBranchRequest", {
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.companyName,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            email: this.$page.props.auth.user.email,
            status: "unprocessed",
          })
          .then((response) => {
            this.transferTransactionID = response.data.id;

            for (var x = 0; x < this.newTransaction.items.length; x++) {
              let labelBox = "";
              for (let box of this.newTransaction.boxLabels) {
                if (box.id == this.newTransaction.items[x].boxNumber) {
                  labelBox = box.boxLabel;
                }
              }
              axios
                .post("/savePullOutItemRequest", {
                  plID: response.data.id,
                  companyType: this.newTransaction.companyName,
                  brand: this.newTransaction.items[x].categorybrand,
                  boxNumber: this.newTransaction.items[x].boxNumber,
                  boxLabel: labelBox,
                  itemCode: this.newTransaction.items[x].code,
                  quantity: this.newTransaction.items[x].quantity,
                  email: this.$page.props.auth.user.email,
                  status: "unprocessed",
                })
                .then((response) => {
                  //console.log("Success Items Save: ", response.data);
                })
                .catch((error) => {
                  //console.error(error);
                });
            }
            this.saving_counter = this.fileImages.length * 2;
            this.openSubmitMessageBox();
            setTimeout(this.$refs.uploadImage.submit(), 500);
          })
          .catch((error) => {
            //console.error(error);
          });
      }
    },
    draft() {
      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];

        axios
          .post("/updatePullOutBranchRequest", {
            id: id,
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.companyName,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            status: "draft",
            email: this.$page.props.auth.user.email,
          })
          .then((response) => {
            for (var x = 0; x < this.newTransaction.items.length; x++) {
              let labelBox = "";
              for (let box of this.newTransaction.boxLabels) {
                if (box.id == this.newTransaction.items[x].boxNumber) {
                  labelBox = box.boxLabel;
                }
              }

              axios
                .post("/updatePullOutItemRequest", {
                  plID: id,
                  companyType: this.newTransaction.companyName,
                  brand: this.newTransaction.items[x].categorybrand,
                  boxNumber: this.newTransaction.items[x].boxNumber,
                  boxLabel: labelBox,
                  itemCode: this.newTransaction.items[x].code,
                  quantity: this.newTransaction.items[x].quantity,
                  status: "draft",
                  email: this.$page.props.auth.user.email,
                })
                .then((response) => {
                  console.log("Success Items Save: ", response.data);
                })
                .catch((error) => {
                  console.error(error);
                });
            }
            this.openDraftMessageBox();
          })
          .catch((error) => {
            //console.error(error);
          });
      } catch {
        axios
          .post("/savePullOutBranchRequest", {
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.companyName,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            status: "draft",
            email: this.$page.props.auth.user.email,
          })
          .then((response) => {
            for (var x = 0; x < this.newTransaction.items.length; x++) {
              let labelBox = "";
              for (let box of this.newTransaction.boxLabels) {
                if (box.id == this.newTransaction.items[x].boxNumber) {
                  labelBox = box.boxLabel;
                }
              }
              axios
                .post("/savePullOutItemRequest", {
                  plID: response.data.id,
                  companyType: this.newTransaction.companyName,
                  brand: this.newTransaction.items[x].categorybrand,
                  boxNumber: this.newTransaction.items[x].boxNumber,
                  boxLabel: labelBox,
                  itemCode: this.newTransaction.items[x].code,
                  quantity: this.newTransaction.items[x].quantity,
                  status: "draft",
                  email: this.$page.props.auth.user.email,
                })
                .then((response) => {})
                .catch((error) => {
                  //console.error(error);
                });
            }
            this.openDraftMessageBox();
          })
          .catch((error) => {
            //console.error(error);
          });
      }
    },
    openMessageBox(typePO) {
      let message = "";
      let title = "";
      let btnText = "";
      if (typePO == "submit") {
        message = "Are you sure you want to submit this request?";
        title = "Pull Out Request Confirmation";
        btnText = "Confirm Submit";
      } else {
        message = "Do you want to save this as a draft?";
        title = "Pull Out Request Draft";
        btnText = "Confirm Save";
      }
      ElMessageBox.confirm(message, title, {
        confirmButtonText: btnText,
        cancelButtonText: "Cancel",
        typePO: "info",
        center: true,
        closeOnClickModal: false,
        closeOnPressEscape: false,
      })
        .then(() => {
          ElMessage({
            type: "success",
            message: "Pull Out Request Submitted Successfully",
          });
          if (typePO == "submit") this.submit();
          else this.draft();

          // this.openSubmitMessageBox();
          // this.openDraftMessageBox();
        })
        .catch(() => {
          ElMessage({
            type: "info",
            message: "Pull Out Request Canceled",
          });
        });
    },
    openSubmitMessageBox() {
      ElMessageBox({
        message:
          "Your transaction has been processed successfully.<br /><br />Transaction Number<br /><b>" +
          this.transferTransactionID +
          "</b><br /><br />Please take a photo or screenshot the transaction number before closing this window.",
        showClose: false,
        center: true,
        confirmButtonText: "Close",
        dangerouslyUseHTMLString: true,
        closeOnClickModal: false,
        closeOnPressEscape: false,
        beforeClose: (action, instance, done) => {
          if (action === "confirm") {
            instance.confirmButtonLoading = true;
            instance.confirmButtonText = "Loading...";
            setTimeout(() => {
              done();
              setTimeout(() => {
                instance.confirmButtonLoading = false;
              }, 300);
            }, this.saving_counter * 1000);
          } else {
            done();
          }
        },
      }).then(() => {
        console.log("Reload Page Submit");
      });
    },
    openDraftMessageBox() {
      ElMessageBox.alert("It will now reload this page.", "Draft has been saved.", {
        // if you want to disable its autofocus
        // autofocus: false,
        confirmButtonText: "OK",
        center: true,
        callback: () => {
          console.log("Reload Page");
        },
      });
    },
  },
};
</script>
<style>
.el-table .warning-row {
  background: rgb(253, 230, 230) !important;
}
</style>
