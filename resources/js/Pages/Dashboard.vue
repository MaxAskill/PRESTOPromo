<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
      </template> -->

    <div class="py-4 z-0 max-h-[90vh] overflow-y-auto">
      <div class="w-auto px-2.5 3xs:px-3 2xs:px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-6 rounded-xl drop-shadow-lg">
          <div class="grid items-end lg:gap-6 gap-3 sm:grid-cols-2 lg:grid-cols-4">
            <div class="relative">
              <label class="text-gray-500 text-sm">Company Name</label>
              <el-select
                v-model="newTransaction.companyName"
                class="w-full"
                placeholder="Select Company Name"
                size="large"
                @change="
                  fetchChainCode(),
                    (listBranchName = []),
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
              <label class="text-gray-500 text-sm">Chain Name</label>
              <el-select
                v-model="newTransaction.chainCode"
                class="w-full"
                placeholder="Select Chain Name"
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
            class="grid gap-3 2xl:grid-cols-8 xl:grid-cols-6 lg:grid-cols-4 3xs:grid-cols-2"
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
            <!-- <el-button
              class="col-span-1"
              style="margin: 0px !important"
              type="primary"
              plain
              >Import Item &nbsp;<el-icon><DocumentAdd /></el-icon>
            </el-button> -->
          </div>
          <br v-if="isBoxLabel" />
          <div class="flex gap-2" v-if="isBoxLabel">
            <el-select
              class="w-full"
              v-model="newBoxLabel"
              filterable
              clearable
              allow-create
              default-first-option
              placeholder="Select or Enter the Box Label"
              popper-class="select-boxlabel"
              :popper-append-to-body="false"
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
            <div v-if="loadingFetchEdit" class="w-full">
              <el-progress
                :percentage="100"
                :stroke-width="15"
                status="success"
                striped
                striped-flow
                :duration="10"
              />
            </div>
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
                  <div class="flex gap-2" v-if="isAddItem">
                    <el-button type="success" plain @click="addItem(boxLabel.boxNumber)">
                      Add Item &nbsp;<el-icon><ShoppingCartFull /></el-icon>
                    </el-button>
                    <el-button
                      v-if="indexBox == deleteItemBtn && multipleSelection.length != 0"
                      type="info"
                      plain
                      @click="clearSelectedItems(indexBox)"
                    >
                      <span v-html="clearBTN"></span><el-icon><Remove /></el-icon>
                    </el-button>
                    <el-button
                      v-if="indexBox == deleteItemBtn && multipleSelection.length != 0"
                      type="danger"
                      plain
                      @click="deleteSelectedItems"
                    >
                      <span v-html="deleteBTN"></span><el-icon><Delete /></el-icon>
                    </el-button>
                  </div>

                  <div
                    v-for="newItem in newItemInputBox"
                    v-show="newItem.id === boxLabel.boxNumber"
                  >
                    <div
                      class="flex flex-row flex-wrap w-full gap-y-2 justify-center"
                      v-if="newItem.id == showItemInput"
                    >
                      <div
                        class="basis-full xs:basis-[38%] sm:basis-[33%] md:basis-[28%] xl:basis-[20%] 2xl:basis-[17%] flex justify-center"
                      >
                        <el-radio-group v-model="itemDigitsBarcode">
                          <el-radio-button label="16 Digits" />
                          <el-radio-button label="12 Digits" />
                        </el-radio-group>
                      </div>
                      <div
                        class="basis-full xs:basis-[62%] md:basis-[72%] xl:basis-[79%] 2xl:basis-[82%] flex gap-2"
                      >
                        <el-select
                          class="w-full"
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
                        <el-button
                          style="margin: 0px !important"
                          type="danger"
                          plain
                          @click="cancelItem()"
                          ><el-icon><CloseBold /></el-icon
                        ></el-button>
                      </div>
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
                      <el-table-column label="Color" width="100">
                        <template #default="scope">
                          {{ scope.row.color }}
                        </template>
                      </el-table-column>
                      <el-table-column
                        :label="isNBFI ? 'Brand' : 'Category'"
                        min-width="200"
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
                            @keypress="keyPress"
                            @paste="onPaste"
                            @blur="handleBlurQuantity(scope.row)"
                          />
                        </template>
                      </el-table-column>
                      <el-table-column label="Box Label" min-width="360">
                        <template #default="scope">
                          <el-select
                            class="w-full"
                            v-model="scope.row.boxNumber"
                            @change="
                              editBoxLabel(
                                scope.row.code,
                                scope.row.quantity,
                                scope.row.boxNumber
                              )
                            "
                          >
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
              <label class="text-gray-500 text-sm row"
                >Upload Images (Up to 10 images [JPEG, PNG, and other image files] with a
                maximum size of 2 MB per image will be accepted.)
              </label>
            </div>
            <div class="mb-2">
              <label class="text-red-500 text-sm row" v-if="showMaxImgMsg"
                >You have reached the maximum number of images.
              </label>
            </div>
            <div>
              <!-- <el-tooltip
                content="You reached the maximum number of images."
                :disabled="disableUploadTooltip"
              > -->
              <el-upload
                :key="uploadImageKey"
                ref="uploadImage"
                action="#"
                :limit="10"
                list-type="picture-card"
                accept="image/jpeg, image/png"
                :auto-upload="false"
                :http-request="handleFileSuccess"
                v-model:file-list="fileImages"
                :disabled="disableUploadImage"
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
                      <el-popconfirm
                        width="280"
                        confirm-button-text="Confirm"
                        cancel-button-text="Cancel"
                        :icon="WarningFilled"
                        icon-color="#c45656"
                        title="Are you sure you want to remove this image?"
                        @confirm="handleRemove(file, true)"
                        @cancel="handleRemove(file, false)"
                      >
                        <template #reference>
                          <span class="el-upload-list__item-delete">
                            <el-icon><Delete /></el-icon> </span
                        ></template>
                      </el-popconfirm>
                    </span>
                  </div>
                </template>
              </el-upload>
              <!-- </el-tooltip> -->
            </div>
          </div>
          <br />
          <div class="flex justify-center items-center gap-3">
            <a href="http://104.199.254.102:80/pullouttransactions" v-if="showCancel"
              ><el-button type="warning">Cancel</el-button></a
            >
            <el-button @click="openMessageBox('draft')" type="warning" v-else
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
      <el-dialog id="imgDialog" v-model="dialogVisible" center width="30%">
        <img w-full :src="dialogImageUrl" alt="Preview Image" />
      </el-dialog>
      <el-dialog
        id="inactiveDialog"
        v-model="inactiveUser"
        :close-on-click-modal="false"
        :close-on-press-escape="false"
        :show-close="false"
      >
        <p class="text-center">
          You have successfully created an account.<br /><br />However, your account is
          temporarily unavailable. Kindly contact your Administrator.
        </p>
        <div class="inactiveButton mt-3.5">
          <Link :href="route('logout')" method="post" as="button"
            ><el-button type="primary">Okay</el-button></Link
          >
        </div>
      </el-dialog>
    </div>

    <DeleteBoxLabelModal
      :transferredData="newTransaction"
      :newItemInputBox="newItemInputBox"
      v-if="openDeleteModal"
      @close="openDeleteModal = false"
      @DeletedItemsByBox="transferDeletedItems($event)"
      @TransferDataBoxNumber="reArrangeBoxNumber($event)"
      @DeletedBoxNumber="reArrangeItems($event)"
    >
    </DeleteBoxLabelModal>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/DashboardLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import axios from "axios";
import DeleteBoxLabelModal from "./DeleteBoxLabelModal.vue";
import { ElButton, ElMessage, ElMessageBox, ElProgress, buttonEmits } from "element-plus";
import { h } from "vue";

export default {
  components: {
    AuthenticatedLayout,
    DeleteBoxLabelModal,
    Link,
  },
  data() {
    return {
      viewportWidth: null,
      deleteBTN: "Delete Items &nbsp;",
      clearBTN: "Clear Selection &nbsp;",

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
      // saving_counter: null,
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
      tempItemsRemove: [],

      dialogImageUrl: "",
      dialogVisible: false,
      disableUploadImage: false,
      disableUploadTooltip: true,
      imageUrl: [],
      fileImages: [],
      uploadImageKey: false,
      fileImagesTemp: [],
      // newImage: false,
      reloadOccurred: false,

      loadingPercentage: 0,
      intervalID: null,

      inactiveUser: false,

      showMaxImgMsg: false,

      showCancel: false,

      loadingFetchEdit: false,
    };
  },
  created() {
    setInterval(this.dateTime, 1000);
    window.addEventListener("resize", this.handleViewportResize);
  },
  mounted() {
    if (this.$page.props.auth.user.status == "Inactive") this.inactiveUser = true;
    else {
      this.handleViewportResize();
      this.fetchEdit();
      this.fetchCompany();
      this.uploadImageKey = !this.uploadImageKey;
    }
  },
  watch: {
    viewportWidth: {
      handler() {
        if (this.viewportWidth < 520) {
          this.deleteBTN = "";
          this.clearBTN = "";
        } else {
          this.deleteBTN = "Delete Items &nbsp;";
          this.clearBTN = "Clear Selection &nbsp;";
        }
      },
      deep: true,
    },
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
    itemDigitsBarcode: function () {
      this.newItemInput = "";
    },
    loadingPercentage: function () {
      if (this.loadingPercentage == 100) {
        clearInterval(this.intervalID);
        location.replace("http://104.199.254.102:80/drafttransaction");
      }
    },
  },
  methods: {
    onPaste(event) {
      event.preventDefault();
    },
    keyPress($event) {
      let keyCode = $event.keyCode ? $event.keyCode : $event.which;
      if (keyCode < 48 || keyCode > 57) {
        // 46 is dot
        $event.preventDefault();
      }
    },
    handleBlurQuantity(event) {
      if (!event.quantity) event.quantity = 0;
    },
    handleViewportResize() {
      this.viewportWidth = window.innerWidth;
    },
    validateSubmit() {
      let uniqueItems = [
        ...new Set(this.newTransaction.items.map((item) => item.boxNumber)),
      ];
      let itemsValidation = false;
      let itemsValidationTemp = false;
      // for (let j in this.newTransaction.boxLabels) {
      //   for (let i in uniqueItems) {
      //     if (uniqueItems[i] == this.newTransaction.boxLabels[j].id) {
      //       itemsValidationTemp = true;
      //       itemsValidation = true;
      //       break;
      //     } else {
      //       itemsValidation = false;
      //       itemsValidationTemp = false;
      //     }
      //   }
      // }
      if (this.newTransaction.boxLabels.length == uniqueItems.length) {
        itemsValidationTemp = true;
        itemsValidation = true;
      } else {
        itemsValidation = false;
        itemsValidationTemp = false;
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
    async fetchCompany() {
      const companyUser = await axios.get("/fetchCompanyByUser", {
        params: {
          userID: this.$page.props.auth.user.id,
        },
      });

      this.listCompanyName = companyUser.data;

      // axios
      //   .get("/fetchCompanyByUser", {
      //     params: {
      //       userID: this.$page.props.auth.user.id,
      //     },
      //   })
      //   .then((response) => {
      //     this.listCompanyName = response.data;
      //   })
      //   .catch((error) => {
      //     console.error(error);
      //   });
    },
    async fetchChainCode() {
      if (
        this.newTransaction.companyName == "NBFI" ||
        this.newTransaction.companyName == "CMC" ||
        this.newTransaction.companyName == "ASC"
      )
        this.isNBFI = true;
      else this.isNBFI = false;

      const chainUser = await axios.get("/fetchChainByUser", {
        params: {
          company: this.newTransaction.companyName,
          userID: this.$page.props.auth.user.id,
        },
      });

      this.listChainCode = chainUser.data;

      // axios
      //   .get("/fetchChainByUser", {
      //     params: {
      //       company: this.newTransaction.companyName,
      //       userID: this.$page.props.auth.user.id,
      //     },
      //   })
      //   .then((response) => {
      //     this.listChainCode = response.data;
      //   })
      //   .catch((error) => {
      //     console.error(error);
      //   });

      this.isChainCode = false;
    },
    async fetchChainName() {
      const chainNameUser = await axios.get("/fetchChainNameByUser", {
        params: {
          chainCode: this.newTransaction.chainCode,
          userID: this.$page.props.auth.user.id,
        },
      });

      this.listBranchName = chainNameUser.data;
      // axios
      //   .get("/fetchChainNameByUser", {
      //     params: {
      //       chainCode: this.newTransaction.chainCode,
      //       userID: this.$page.props.auth.user.id,
      //     },
      //   })
      //   .then((response) => {
      //     this.listBranchName = response.data;
      //   })
      //   .catch((error) => {
      //     console.error(error);
      //   });

      this.isBranchName = false;
    },
    fetchItems(itemInput) {
      console.log("Digits:", this.itemDigitsBarcode);
      console.log("Item Input:", itemInput);

      if (itemInput.length >= 4) {
        if (
          this.newTransaction.companyName == "NBFI" ||
          this.newTransaction.companyName == "CMC" ||
          this.newTransaction.companyName == "ASC"
        ) {
          // var items = await axios.get("/fetchItemsNBFI", {
          //   ItemNo: itemInput,
          //   barcode: this.itemDigitsBarcode,
          // });
          // console.log("Items:", items.data);

          // this.itemCodeList = items.data;
          axios
            .get("/fetchItemsNBFI", {
              params: {
                ItemNo: itemInput,
                barcode: this.itemDigitsBarcode,
              },
            }) // Make a GET request to the specified URL
            .then((response) => {
              console.log("Items:", response.data);
              this.itemCodeList = response.data; // Update the 'data' variable with the retrieved data
            })
            .catch((error) => {
              console.error(error.reponse); // Handle any errors that may occur
            });
        } else {
          // const items = await axios.get("/fetchItems", {
          //   params: {
          //     ItemNo: itemInput,
          //     barcode: this.itemDigitsBarcode,
          //   },
          // });

          // this.itemCodeList = items.data;

          axios
            .get("/fetchItems", {
              params: {
                ItemNo: itemInput,
                barcode: this.itemDigitsBarcode,
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
    async fetchEdit() {
      try {
        //Loading of Draft
        this.loadingFetchEdit = true;

        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];
        // var company = transactionID.split("=")[2];
        var company = this.decodeFromAlphanumeric(transactionID.split("=")[2]);
        this.isDraft = true;

        const branchData = await axios.get("/fetchEditDraftBranch", {
          params: {
            company: company,
            plID: id,
          },
        });

        this.newTransaction.companyName = branchData.data[0].company;
        this.newTransaction.branchName = branchData.data[0].branchName;
        this.newTransaction.chainCode = branchData.data[0].chainCode;
        this.newTransaction.transactionType = branchData.data[0].transactionType;

        if (branchData.data[0].status == "denied") {
          this.showCancel = true;
        }

        const itemData = await axios.get("/fetchEditDraftItem", {
          params: {
            company: company,
            plID: id,
          },
        });

        for (var x = 0; x < itemData.data.length; x++) {
          this.newTransaction.items.push(itemData.data[x]); //pushing it into the items
        }

        //filtering the data with box numbers
        const filteredData = this.newTransaction.items.filter((obj, index, self) => {
          const boxNumber = obj.boxNumber;
          return self.findIndex((o) => o.boxNumber === boxNumber) === index;
        });

        // asigning the filtered Data on Box Data
        const boxData = filteredData.map((obj) => {
          return {
            boxLabel: obj.boxLabel,
            boxNumber: obj.boxNumber,
          };
        });

        // assign the box Data into box labels
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

        const imageBranchDoc = await axios.get("/fetchImageBranchDoc", {
          params: {
            company: company,
            transactionID: id,
          },
        });

        imageBranchDoc.data.imagePaths.forEach((path) => {
          let name = path.split("/");
          name = name[name.length - 1];
          this.fileImages.push({
            name: name,
            url: path,
          });
          this.fileImagesTemp.push(name);
        });
      } catch {
        //Fetching Promo Info
        const promoBranchInfo = await axios.get("/fetchPromoBranchInfo", {
          params: {
            userID: this.$page.props.auth.user.id,
          },
        });

        this.newTransaction.companyName = promoBranchInfo.data[0].company;
        this.newTransaction.chainCode = promoBranchInfo.data[0].chainCode;
        this.newTransaction.branchName = promoBranchInfo.data[0].branchName;
        if (this.newTransaction.branchName) this.isTransactionType = false;
        this.fetchChainCode();
        this.fetchChainName();

        // axios
        //   .get("/fetchPromoBranchInfo", {
        //     params: {
        //       userID: this.$page.props.auth.user.id,
        //     },
        //   })
        //   .then((response) => {
        //     this.newTransaction.companyName = response.data[0].company;
        //     this.newTransaction.chainCode = response.data[0].chainCode;
        //     this.newTransaction.branchName = response.data[0].branchName;
        //     if (this.newTransaction.branchName) this.isTransactionType = false;
        //     this.fetchChainCode();
        //     this.fetchChainName();
        //   })
        //   .catch((error) => {
        //     // console.error(error);
        //   });
      }
      //End of Loading of Draft
      this.loadingFetchEdit = false;
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

        // console.log("First Label", this.newBoxLabel);
      } else {
        tempBoxLabel = {
          id:
            parseInt(
              this.newTransaction.boxLabels[this.newTransaction.boxLabels.length - 1].id
            ) + 1,
          boxNumber: this.newTransaction.boxLabels.length + 1,
          boxLabel: this.newBoxLabel,
        };
        console.log("Add new Label");
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
    async saveItem(boxNUMBER) {
      if (this.itemDigitsBarcode == "16 Digits") {
        if (this.newItemInput.length > 16)
          this.newItemInput = this.newItemInput.slice(0, 16);
      }
      // else {
      //   this.newItemInput = this.newItemInput.slice(0, 12);
      // }
      //12 Digits
      else {
        this.newItemInput = await axios.get("/fetchItemsBarcode", {
          params: {
            ItemNo: this.newItemInput,
            company: this.newTransaction.companyName,
          },
        });
      }
      var checkItemData = true;
      var compareItemCode;
      try {
        compareItemCode = await axios.get("/compareItemCode", {
          params: {
            companyType: this.newTransaction.companyName,
            ItemNo: this.newItemInput,
          },
        });
      } catch {
        !this.newItemInput ? true : (this.isRightCode = true);
      }

      this.newItemCode = compareItemCode.data[0].ItemNo;
      this.newItemDescription = compareItemCode.data[0].ItemDescription;
      this.newStyleCode = compareItemCode.data[0].StyleCode;

      let brandCode = compareItemCode.data[0].ItemNo.toString().substr(1, 2);

      const newBrand = await axios.get("/fetchBrands", {
        params: {
          companyType: this.newTransaction.companyName,
          brandCode: brandCode,
        },
      });

      this.newBrand = newBrand.data.brandNames;

      if (checkItemData) {
        this.isNewItem = !this.newItemInput ? true : false;

        if (this.isNewItem) {
          this.isRightCode = false;
          return 0;
        }
        var newResponseData = await axios.get("/fetchSameItem", {
          params: {
            company: this.newTransaction.companyName,
            ItemCode: this.newItemCode,
            ItemDescription: this.newItemDescription,
            StyleCode: this.newStyleCode,
          },
        });
      }
      if (checkItemData) {
        for (var x = 0; x < newResponseData.data.length; x++) {
          var flag = true;
          for (var i = 0; i < this.newTransaction.items.length; i++) {
            if (
              this.newTransaction.items[i].code == newResponseData.data[x].ItemNo &&
              this.newTransaction.items[i].boxNumber == boxNUMBER
            ) {
              // this.newTransaction.items[i].quantity += 1;
              flag = false;
              break;
            }
          }
          if (flag) {
            if (
              this.newTransaction.companyName == "NBFI" ||
              this.newTransaction.companyName == "CMC" ||
              this.newTransaction.companyName == "ASC"
            ) {
              var categorybrand = this.newBrand;
            } else {
              var categorybrand = newResponseData.data[x].Category;
            }
            let tempItem = {
              code: newResponseData.data[x].ItemNo,
              description: newResponseData.data[x].ItemDescription,
              categorybrand: categorybrand,
              quantity: 0,
              size: newResponseData.data[x].Size,
              color: newResponseData.data[x].Color,
              // boxLabel: label,
              boxNumber: boxNUMBER,
              category: newResponseData.data[x].Category,
            };
            this.newTransaction.items.push(tempItem);
            // console.log("New Item:", this.newTransaction.items);
          }
          this.isRightCode = false;
          this.isItem = false;
          this.isAddItem = true;
          this.newItemInput = "";
          this.showItemInput = "";
        }
      }
      // setTimeout(() => {
      //   axios
      //     .get("/compareItemCode", {
      //       params: {
      //         companyType: this.newTransaction.companyName,
      //         ItemNo: this.newItemInput,
      //       },
      //     })
      //     .then((response) => {
      //       if (response.data.length == 0) checkItemData = false;

      //       this.newItemCode = response.data[0].ItemNo;
      //       this.newItemDescription = response.data[0].ItemDescription;
      //       this.newStyleCode = response.data[0].StyleCode;

      //       let brandCode = response.data[0].ItemNo.toString().substr(1, 2);
      //       axios
      //         .get("/fetchBrands", {
      //           params: {
      //             companyType: this.newTransaction.companyName,
      //             brandCode: brandCode,
      //           },
      //         })
      //         .then((response) => {
      //           this.newBrand = response.data[0].brandNames;
      //         })
      //         .catch((error) => {
      //           console.error(error);
      //         });
      //     })
      //     .catch((error) => {
      //       !this.newItemInput ? true : (this.isRightCode = true);
      //     });
      // }, 300);

      // setTimeout(() => {
      //   if (checkItemData) {
      //     this.isNewItem = !this.newItemInput ? true : false;

      //     if (this.isNewItem) {
      //       this.isRightCode = false;
      //       return 0;
      //     }
      //     axios
      //       .get("/fetchSameItem", {
      //         params: {
      //           company: this.newTransaction.companyName,
      //           ItemCode: this.newItemCode,
      //           ItemDescription: this.newItemDescription,
      //           StyleCode: this.newStyleCode,
      //         },
      //       })
      //       .then((response) => {
      //         newResponseData = response.data;
      //       })
      //       .catch((error) => {
      //         //console.error(error);
      //       });
      //   }
      // }, 500);

      // setTimeout(() => {
      //   if (checkItemData) {
      //     for (var x = 0; x < newResponseData.length; x++) {
      //       var flag = true;
      //       for (var i = 0; i < this.newTransaction.items.length; i++) {
      //         if (
      //           this.newTransaction.items[i].code == newResponseData[x].ItemNo &&
      //           this.newTransaction.items[i].boxNumber == boxNUMBER
      //         ) {
      //           // this.newTransaction.items[i].quantity += 1;
      //           flag = false;
      //           break;
      //         }
      //       }
      //       if (flag) {
      //         if (
      //           this.newTransaction.companyName == "NBFI" ||
      //           this.newTransaction.companyName == "CMC" ||
      //           this.newTransaction.companyName == "ASC"
      //         ) {
      //           var categorybrand = this.newBrand;
      //         } else {
      //           var categorybrand = newResponseData[x].Category;
      //         }
      //         let tempItem = {
      //           code: newResponseData[x].ItemNo,
      //           description: newResponseData[x].ItemDescription,
      //           categorybrand: categorybrand,
      //           quantity: 0,
      //           size: newResponseData[x].Size,
      //           color: newResponseData[x].Color,
      //           // boxLabel: label,
      //           boxNumber: boxNUMBER,
      //           category: newResponseData[x].Category,
      //         };
      //         this.newTransaction.items.push(tempItem);
      //         // console.log("New Item:", this.newTransaction.items);
      //       }
      //       this.isRightCode = false;
      //       this.isItem = false;
      //       this.isAddItem = true;
      //       this.newItemInput = "";
      //       this.showItemInput = "";
      //     }
      //   }
      // }, 1000);
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
      // console.log("Items:", this.newTransaction.items);
      // Create a map to store the aggregated quantities by a unique key
      // var aggregatedItems = new Map();
      // this.newTransaction.boxLabels.forEach((box, key) => {
      //   this.tableData.push([]);
      //   this.newTransaction.items.forEach((item) => {
      //     var unique = `${item.code}-${item.boxNumber}`;
      //     if (aggregatedItems.has(unique)) {
      //       item.quantity = item.quantity == 0 ? 1 : item.quantity;
      //       aggregatedItems.set(unique, item.quantity + aggregatedItems.get(unique));

      //       console.log(
      //         "Item:",
      //         aggregatedItems.get(unique),
      //         key,
      //         item.code,
      //         item.boxNumber
      //       );
      //     } else {
      //       aggregatedItems.set(unique, item.quantity);
      //       console.log(
      //         "Item Else:",
      //         aggregatedItems.get(unique),
      //         key,
      //         item.code,
      //         item.boxNumber
      //       );
      //     }
      //   });
      // });
      // console.log("Aggregated Items:", aggregatedItems);
      // this.newTransaction.boxLabels.forEach((box, key) => {
      //   this.tableData.push([]);
      //   const boxItems = this.newTransaction.items.filter(
      //     (item) => item.boxNumber === box.boxNumber
      //   );
      //   console.log("Box Items:", boxItems);
      //   let groupedItems = {};

      //   boxItems.forEach((item) => {
      //     var key = item.itemCode + item.boxNumber;
      //     console.log("KEy:", key);
      //     if (!groupedItems[key]) {
      //       groupedItems[key] = { ...item, quantity: 0 };
      //     }
      //     groupedItems[key].quantity += item.quantity;
      //   });

      //   console.log("Group Items:", groupedItems);

      //   // Ensure the quantity is at least 1 for each item
      //   Object.values(groupedItems).forEach((groupedItem) => {
      //     groupedItem.quantity = Math.max(groupedItem.quantity, 1);
      //     this.tableData[key].push(groupedItem);
      //   });
      // });
      // this.tableData = [];
      // this.multipleSelection = [];
      this.newTransaction.boxLabels.forEach((box, key) => {
        this.tableData.push([]);
        this.newTransaction.items.forEach((item) => {
          if (box.boxNumber == item.boxNumber) {
            // console.log("Key:", key);
            // console.log("Item:", item);

            this.tableData[key].push(item);
          }
        });
      });

      // console.log("Items:", this.tableData);
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
      this.multipleSelection = [];
      this.deleteItemBtn = null;
    },
    deleteSelectedItems() {
      ElMessageBox.confirm(
        "Item/s you have selected will be remove. Continue?",
        "Removing of Items",
        {
          confirmButtonText: "OK",
          cancelButtonText: "Cancel",
          type: "warning",
        }
      )
        .then(() => {
          this.multipleSelection.forEach((selected) => {
            this.removeItem(selected.code, selected.boxNumber);
          });
          this.deleteItemBtn = null;
          console.log("New Items:", this.newTransaction.items);
          console.log("New Boxes:", this.newTransaction.boxLabels);
        })
        .catch(() => {
          ElMessage({
            type: "info",
            message: "Removing of Items canceled.",
          });
        });
    },
    removeItem(code, boxNumber) {
      // this.validateSubmit();
      for (let key in this.newTransaction.items)
        if (
          this.newTransaction.items[key].code === code &&
          this.newTransaction.items[key].boxNumber === boxNumber
        ) {
          if (Object.keys(this.newTransaction.items[key]).length != 8)
            this.tempItemsRemove.push(this.newTransaction.items[key].id);
          this.newTransaction.items.splice(key, 1);
          break;
        }
    },
    reArrangeItems(deletedBoxNumber) {
      this.newTransaction.items.forEach((temp) => {
        if (deletedBoxNumber < temp.boxNumber) temp.boxNumber--;
      });
      // console.log("deleted", this.newTransaction.items);
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
      // console.log("Delete Box Label: ", transfer, this.newTransaction.boxLabels);
    },
    transferDeletedItems(transfer) {
      transfer.forEach((item) => {
        if (Object.keys(item).length != 8) this.tempItemsRemove.push(item.id);
      });
    },
    async handleRemove(uploadFile, confirm) {
      if (confirm) {
        try {
          if (Object.keys(this.fileImages[this.fileImages.length - 1]).length !== 7) {
            const response = await axios.post("/deleteImage", {
              company: this.newTransaction.companyName,
              path: uploadFile.name,
            });

            console.log(response.data);
          }

          this.fileImages = this.fileImages.filter((obj) => obj.uid !== uploadFile.uid);

          ElMessage({
            type: "success",
            message: "Image has been removed.",
          });
        } catch (error) {
          console.error(error);
        }
      }
    },

    // async handleRemove(uploadFile, confirm) {
    //   if (confirm) {
    //     if (Object.keys(this.fileImages[this.fileImages.length - 1]).length != 7)
    //       await axios
    //         .post("/deleteImage", {
    //           company: this.newTransaction.companyName,
    //           path: uploadFile.name,
    //         })
    //         .then((response) => {
    //           console.log(response.data);
    //         })
    //         .catch((error) => {
    //           console.error(error);
    //         });

    //     this.fileImages = this.fileImages.filter(function (obj) {
    //       return obj.uid !== uploadFile.uid;
    //     });

    //     ElMessage({
    //       type: "success",
    //       message: "Image has been removed.",
    //     });
    //   }
    // },
    // beforeRemove() {
    //   return ElMessageBox.confirm("Are you sure you want to remove this image?", {
    //     confirmButtonText: "Confirm",
    //     cancelButtonText: "Cancel",
    //     type: "warning",
    //     center: true,
    //     closeOnClickModal: false,
    //     closeOnPressEscape: false,
    //   })
    //     .then(() => {
    //       if (Object.keys(this.fileImages[this.fileImages.length - 1]).length != 7)
    //         this.newImage = true;
    //       else this.newImage = false;
    //       return true;
    //     })
    //     .catch(() => false);
    // },
    handlePictureCardPreview(uploadFile) {
      // console.log(uploadFile);
      this.dialogImageUrl = uploadFile.url;
      this.dialogVisible = true;
    },
    handleFileSuccess(file) {
      axios
        .post(
          "/upload",
          {
            image: file.file,
            uID: file.file.uid,
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
          // console.log("Success Saved Image:", response.data);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    validateImage() {
      const maxSizeInBytes = 2 * 1024 * 1024; // 2MB
      this.disableUploadImage = false;
      this.disableUploadTooltip = true;

      if (this.fileImages.length > 0) {
        this.showMaxImgMsg = false;
        if (Object.keys(this.fileImages[this.fileImages.length - 1]).length == 7)
          if (
            !(
              this.fileImages[this.fileImages.length - 1].raw.type == "image/jpeg" ||
              this.fileImages[this.fileImages.length - 1].raw.type == "image/png" ||
              this.fileImages[this.fileImages.length - 1].raw.type == "image/apng" ||
              this.fileImages[this.fileImages.length - 1].raw.type == "image/avif" ||
              this.fileImages[this.fileImages.length - 1].raw.type == "image/svg+xml" ||
              this.fileImages[this.fileImages.length - 1].raw.type == "image/webp" ||
              this.fileImages[this.fileImages.length - 1].raw.type == "image/gif"
            )
          ) {
            this.fileImages.splice(-1, 1);
            ElMessage.error(
              "Invalid file. Kindly select an image file (.jpeg, .png, or other image files)."
            );
          } else if (this.fileImages[this.fileImages.length - 1].size > maxSizeInBytes) {
            this.fileImages.splice(-1, 1);
            ElMessage.error("Please select an image file smaller than 2MB.");
          }
      }
      if (this.fileImages.length == 10) {
        this.disableUploadImage = true;
        this.disableUploadTooltip = false;
        this.showMaxImgMsg = true;
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
    editBoxLabel(code, quantity, boxNumber) {
      console.log("Items", code, quantity, boxNumber);
      this.validateSubmit();
      //console.log(
      //   "Item Code Edit:",
      //   code,
      //   " Quantity:",
      //   quantity,
      //   " Box Number",
      //   boxNumber
      // );
      //console.log(" == : ", this.newTransaction.items);
      var filteredItems = this.newTransaction.items.filter(
        (item) => item.code === code && item.boxNumber === boxNumber
      );

      //console.log("Filtered Items: ", filteredItems);
      var uniqueItems = [];
      filteredItems.forEach((item) => {
        var existingItem = uniqueItems.find(
          (uniqueItem) => uniqueItem.code === item.code
        );
        if (existingItem) {
          if (existingItem.quantity == 0 && item.quantity == 0) existingItem.quantity = 1;
          else existingItem.quantity += item.quantity;
        } else {
          uniqueItems.push({ ...item });
        }
      });
      // The uniqueItems array will contain unique items based on itemCode, with quantities added up.
      //console.log("Unique Items: 1", uniqueItems);
      //console.log("Unique Items: Code", uniqueItems[0].code);
      //console.log("Unique Items: Box Number", uniqueItems[0].boxNumber);

      this.newTransaction.items = this.newTransaction.items.filter(
        (item) =>
          item.code !== uniqueItems[0].code || item.boxNumber !== uniqueItems[0].boxNumber
      );
      this.newTransaction.items.push(uniqueItems[0]);
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
    async submit() {
      this.isValid.company = !this.newTransaction.companyName ? true : false;
      this.isValid.chainCode = !this.newTransaction.chainCode ? true : false;
      this.isValid.branchName = !this.newTransaction.branchName ? true : false;
      this.isValid.transactionType = !this.newTransaction.transactionType ? true : false;
      this.isValid.boxLabel = !this.newTransaction.boxLabels.length ? true : false;
      this.isValid.item = !this.newTransaction.items.length ? true : false;

      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];

        this.newTransaction.items.forEach((item) => {
          if (Object.keys(item).length == 8) {
            item.id = null;
          }
          let labelBox = this.newTransaction.boxLabels.find(
            (box) => box.id === item.boxNumber
          );
          item.boxLabel = labelBox.boxLabel;
        });

        try {
          var response = await axios.post("/updatePullOutBranchRequest", {
            id: id,
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.companyName,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            status: "unprocessed",
            email: this.$page.props.auth.user.email,
            boxes: this.newTransaction.boxLabels,
            items: this.newTransaction.items,
            removedItems: this.tempItemsRemove,
          });

          console.log("Success Response:", response.data);
          this.transferTransactionID = id;
          this.$refs.uploadImage.submit();
          this.openSubmitMessageBox();
        } catch (error) {
          console.error(error);
        }
      } catch {
        try {
          var response = await axios.post("/savePullOutBranchRequest", {
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.companyName,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            boxes: this.newTransaction.boxLabels,
            items: this.newTransaction.items,
            email: this.$page.props.auth.user.email,
            status: "unprocessed",
          });
          this.transferTransactionID = response.data;

          this.$refs.uploadImage.submit();
          this.openSubmitMessageBox();
        } catch (error) {
          console.error(error);
        }
      }
    },
    async draft() {
      try {
        const uri = window.location.href;
        var transactionID = uri.split("?")[1];
        var id = transactionID.split("=")[1].split("&")[0];

        this.newTransaction.items.forEach((item) => {
          if (Object.keys(item).length == 8) {
            item.id = null;
          }
          let labelBox = this.newTransaction.boxLabels.find(
            (box) => box.id === item.boxNumber
          );
          item.boxLabel = labelBox.boxLabel;
        });

        try {
          var response = await axios.post("/updatePullOutBranchRequest", {
            id: id,
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.companyName,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            status: "draft",
            email: this.$page.props.auth.user.email,
            boxes: this.newTransaction.boxLabels,
            items: this.newTransaction.items,
            removedItems: this.tempItemsRemove,
          });

          console.log("Success Response:", response.data);
          this.transferTransactionID = id;
          this.$refs.uploadImage.submit();
          this.openDraftMessageBox();
        } catch (error) {
          console.error(error);
        }
      } catch {
        // this.newTransaction.items.forEach((item) => {
        //   var countItemBox = 0;
        //   this.newTransaction.boxLabels.forEach((box) => {
        //     if (item.boxNumber == box.boxNumber) {
        //       countItemBox += 1;
        //     }
        //   });
        //   if(countItemBox == 0){

        //   }
        // });
        try {
          var response = await axios.post("/savePullOutBranchRequest", {
            chainCode: this.newTransaction.chainCode,
            companyType: this.newTransaction.companyName,
            branchName: this.newTransaction.branchName,
            transactionType: this.newTransaction.transactionType,
            boxes: this.newTransaction.boxLabels,
            items: this.newTransaction.items,
            email: this.$page.props.auth.user.email,
            status: "draft",
          });
          this.transferTransactionID = response.data;

          this.$refs.uploadImage.submit();
          this.openDraftMessageBox();
        } catch (error) {
          console.error(error);
        }
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
            message:
              typePO == "submit"
                ? "Pull Out Request submitted successfully"
                : "Pull Out Request save as draft successfully ",
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
            }, 2000);
          } else {
            done();
          }
        },
      }).then(() => {
        // console.log("Reload Page Submit");
        location.replace("http://104.199.254.102:80/pullouttransactions");
      });
    },
    openDraftMessageBox() {
      // ElMessageBox.alert("Draft has been saved.", {
      //   // if you want to disable its autofocus
      //   // autofocus: false,
      //   confirmButtonText: "OK",
      //   type: "success",
      //   center: true,
      //   callback: () => {
      //     // console.log("Reload Page");
      //     location.replace("http://104.199.254.102:80/drafttransaction");
      //   },
      // });
      this.loadingPercentage = 1;
      this.intervalID = setInterval(() => {
        this.loadingPercentage = (this.loadingPercentage % 100) + 1;
      }, 20);

      ElMessageBox({
        title: "Draft has been saved",
        type: "success",
        center: true,
        showClose: false,
        showConfirmButton: false,
        closeOnClickModal: false,
        closeOnPressEscape: false,
        message: () => {
          return h(ElProgress, {
            percentage: this.loadingPercentage,
            strokeWidth: 15,
            striped: true,
            stripedFlow: true,
          });
        },
      });
    },
    decodeFromAlphanumeric(input) {
      let result = "";

      for (let i = 0; i < input.length; i += 2) {
        const alphanumericChar = input.substr(i, 2);
        const charCode = parseInt(alphanumericChar, 36);

        result += String.fromCharCode(charCode);
      }

      return result;
    },
  },
};
</script>
<style>
.el-table .warning-row {
  background: rgb(253, 230, 230) !important;
}
@media only screen and (max-width: 500px) {
  .select-boxlabel .el-select-dropdown {
    white-space: nowrap !important;
    max-width: 95vw !important;
    overflow-x: auto !important;
  }
  #imgDialog .el-dialog {
    margin: 15vh auto 0px !important;
    border-radius: 10px !important;
    width: max-content !important;
  }
  #imgDialog .el-dialog__body img {
    max-width: 82vw !important;
    max-height: 50vh !important;
  }
}
@media only screen and (min-width: 501px) {
  #imgDialog .el-dialog {
    margin: 10vh auto 0px !important;
    border-radius: 10px !important;
    width: max-content !important;
  }
  #imgDialog .el-dialog__body img {
    max-width: 75vw !important;
    max-height: 70vh !important;
  }
}
@media only screen and (min-width: 960px) {
  #imgDialog .el-dialog__body img {
    max-width: 45vw !important;
    max-height: 70vh !important;
  }
}
@media only screen and (min-width: 1300px) {
  #imgDialog .el-dialog__body img {
    max-width: 35vw !important;
    max-height: 70vh !important;
  }
}
#imgDialog .el-dialog__body img {
  width: 100% !important;
  height: 100% !important;
}
#inactiveDialog {
  display: inline-block !important;
  width: 93vw !important;
  max-width: 420px !important;
  margin: 0px !important;
  /* margin: 10vh auto 0px !important; */
  padding-bottom: 10px !important;
  vertical-align: middle !important;
  text-align: left !important;
  overflow: hidden !important;
  position: absolute !important;
  top: 50% !important;
  left: 50% !important;
  transform: translate(-50%, -50%);
}
#inactiveDialog .el-dialog__body {
  padding: 10px 27px 0px !important;
}
#inactiveDialog header {
  margin: 0px !important;
  padding: 0px !important;
}

.el-overlay-dialog {
  text-align: center !important;
  /* padding: 16px !important; */
}
.inactiveButton {
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  align-items: center;
}
</style>
