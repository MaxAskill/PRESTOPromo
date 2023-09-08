<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
      </template> -->

    <div class="py-4">
      <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white p-6">
          <div class="grid items-end gap-6 md:grid-cols-4">
            <div class="relative">
              <label class="text-gray-500 text-sm">Company Name</label>
              <el-select
                v-model="newTransaction.companyName"
                class="w-full"
                placeholder="Select Company Name"
                size="large"
                @change="fetchChainCode()"
              >
                <el-option
                  v-for="item in listCompanyName"
                  :key="item.shortName"
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
                @change="fetchChainName()"
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
              >
                <el-option
                  v-for="item in listTransactionType"
                  :key="item.value"
                  :label="item.label"
                  :value="item.value"
                />
              </el-select>
            </div>
          </div>
          <br />
          <div class="flex">
            <el-button type="success" plain
              >Add Box Label <el-icon><Box /></el-icon
            ></el-button>
            <el-button type="danger" plain
              >Delete Box Label <el-icon><Delete /></el-icon
            ></el-button>
            <el-button type="primary" plain
              >Import Item <el-icon><DocumentAdd /></el-icon>
            </el-button>
          </div>
          <br />
          <div class="flex">
            <el-select
              class="w-full"
              v-model="newTransaction.boxLabel"
              filterable
              placeholder="Select"
            >
              <el-option
                v-for="item in listBoxLabels"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
            <el-button type="success" plain
              ><el-icon><Select /></el-icon
            ></el-button>
            <el-button type="danger" plain
              ><el-icon><CloseBold /></el-icon
            ></el-button>
          </div>
          <br />

          <div class="flex">
            <div class="demo-collapse w-full">
              <el-collapse v-model="activeNames" @change="handleChange">
                <el-collapse-item
                  title="Box No. 1 of 1 CLOSED STORE/BRANCH - GOOD ITEMS"
                  name="1"
                >
                  <div class="flex">
                    <el-select
                      class="w-full"
                      v-model="newTransaction.boxLabel"
                      filterable
                      placeholder="Select"
                    >
                      <el-option
                        v-for="item in listBoxLabels"
                        :key="item.value"
                        :label="item.label"
                        :value="item.value"
                      />
                    </el-select>
                    <el-button type="success" plain
                      ><el-icon><Select /></el-icon
                    ></el-button>
                    <el-button type="danger" plain
                      ><el-icon><CloseBold /></el-icon
                    ></el-button>
                  </div>
                  <br />
                  <div>
                    <el-button @click="toggleSelection()" type="info"
                      >Clear Selection</el-button
                    >
                    <el-button @click="toggleSelection()" type="danger"
                      >Delete Items</el-button
                    >
                  </div>
                  <div class="flex">
                    <el-table
                      :data="tableData"
                      style="width: 100%"
                      @selection-change="handleSelectionChange"
                    >
                      <el-table-column type="selection" width="55" />
                      <el-table-column
                        fixed
                        prop="itemCode"
                        label="ITEM CODE"
                        width="150"
                      />
                      <el-table-column
                        prop="description"
                        label="DESCRIPTION"
                        width="300"
                      />
                      <el-table-column prop="size" label="SIZE" width="130" />
                      <el-table-column prop="color" label="COLOR" width="130" />
                      <el-table-column prop="brand" label="BRAND" width="130" />
                      <el-table-column prop="quantity" label="QUANTITY" width="180">
                        <template #default="scope">
                          <el-input-number
                            v-model="scope.row.quantity"
                            :min="0"
                            @change="handleChange"
                          />
                        </template>
                      </el-table-column>
                      <el-table-column prop="boxLabel" label="BOX LABEL" width="500" />
                    </el-table>
                  </div>
                </el-collapse-item>
              </el-collapse>
            </div>
          </div>
          <br />
          <div class="flex grid grid-cols-1">
            <div>
              <label class="text-gray-500 text-sm"
                >Upload Photos (Only JPG and PNG files with max 2 MB and 10 images)
              </label>
            </div>
            <div>
              <el-upload action="#" list-type="picture-card" :auto-upload="false">
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
                      <span
                        v-if="!disabled"
                        class="el-upload-list__item-delete"
                        @click="handleDownload(file)"
                      >
                        <el-icon><Download /></el-icon>
                      </span>
                      <span
                        v-if="!disabled"
                        class="el-upload-list__item-delete"
                        @click="handleRemove(file)"
                      >
                        <el-icon><Delete /></el-icon>
                      </span>
                    </span>
                  </div>
                </template>
              </el-upload>

              <el-dialog v-model="dialogVisible">
                <img w-full :src="dialogImageUrl" alt="Preview Image" />
              </el-dialog>
            </div>
          </div>
          <br />
          <div class="flex justify-center items-center">
            <el-button @click="toggleSelection()" type="warning">Save as Draft</el-button>
            <el-button @click="toggleSelection()" type="success">Submit</el-button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

export default {
  components: {
    AuthenticatedLayout,
  },
  data() {
    return {
      switchBoxLabel: false,
      newTransaction: {
        companyName: "",
        chainCode: "",
        branchName: "",
        transactionType: "",
        items: [],
      },
      listCompanyName: [
        {
          value: "New Barbizon Fashion Incorporated (NBFI)",
          label: "New Barbizon Fashion Incorporated (NBFI)",
        },
        {
          value: "ActiveStyle Corporation (ASC)",
          label: "ActiveStyle Corporation (ASC)",
        },
        {
          value: "Cotton Mountain Corporation (CMC)",
          label: "Cotton Mountain Corporation (CMC)",
        },
        {
          value: "Everyday Products Corporation (EPC)",
          label: "Everyday Products Corporation (EPC)",
        },
        {
          value: "Athome Lifestyle Corporation (AHLC)",
          label: "Athome Lifestyle Corporation (AHLC)",
        },
      ],

      listChainCode: [
        {
          value: "METRO",
          label: "METRO",
        },
        {
          value: "SM DEPT. STORE",
          label: "SM DEPT. STORE",
        },
        {
          value: "STA. LUCIA",
          label: "STA. LUCIA",
        },
        {
          value: "TOY KINGDOM",
          label: "WDS",
        },
      ],

      listBranchName: [
        {
          value: "Metro Dept. Store Up Town Center",
          label: "Metro Dept. Store Up Town Center",
        },
        {
          value: "Metro Dept. Store Alabang",
          label: "Metro Dept. Store Alabang",
        },
        {
          value: "Metro Dept. Store Angeles",
          label: "Metro Dept. Store Angeles",
        },
        {
          value: "Metro Dept. Store Ayala Bacolod",
          label: "Metro Dept. Store Ayala Bacolod",
        },
        {
          value: "Metro Dept. Store Ayala Cebu",
          label: "Metro Dept. Store Ayala Cebu",
        },
      ],

      listTransactionType: [
        {
          value: "CPO Item for Disposal in the Store c/o Supervisor",
          label: "CPO Item for Disposal in the Store c/o Supervisor",
        },
        {
          value: "CPO for Transfer to Another Store",
          label: "CPO for Transfer to Another Store",
        },
        {
          value: "CPO Back to WH via In-House Service",
          label: "CPO Back to WH via In-House Service",
        },
      ],

      listBoxLabels: [
        {
          value: "Box No. 1 of 3 CLOSED STORE/BRANCH - GOOD ITEMS",
          label: "Box No. 1 of 3 CLOSED STORE/BRANCH - GOOD ITEMS",
        },
        {
          value: "Box No. 2 of 3 CLOSED STORE/BRANCH - GOOD ITEMS",
          label: "Box No. 2 of 3 CLOSED STORE/BRANCH - GOOD ITEMS",
        },
        {
          value: "Box No. 3 of 3 CLOSED STORE/BRANCH - GOOD ITEMS",
          label: "Box No. 3 of 3 CLOSED STORE/BRANCH - GOOD ITEMS",
        },
      ],

      tableData: [
        {
          itemCode: "1029990236161211",
          description: "TEENS BRA",
          size: "36A",
          color: "PEACH",
          brand: "MONALISA",
          quantity: 0,
          boxLabel: "Box No. 1 of 1 CLOSED STORE/BRANCH - GOOD ITEMS",
        },
        {
          itemCode: "1029990236161211",
          description: "SHORTS",
          size: "SMALL",
          color: "ORANGE",
          quantity: 0,
          boxLabel: "Box No. 1 of 1 CLOSED STORE/BRANCH - GOOD ITEMS",
        },
        {
          itemCode: "1029990236161211",
          description: "SHORTS",
          size: "LARGE",
          color: "RED",
          quantity: 0,
          boxLabel: "Box No. 1 of 1 CLOSED STORE/BRANCH - GOOD ITEMS",
        },
      ],
    };
  },
};
</script>
