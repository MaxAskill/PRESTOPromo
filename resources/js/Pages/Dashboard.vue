<template>
  <Head title="Dashboard" />

  <AuthenticatedLayout>
    <!-- <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
      </template> -->

    <div class="py-12">
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

          <div class="flex">
            <el-select
              class="w-full"
              v-model="value"
              multiple
              filterable
              allow-create
              default-first-option
              :reserve-keyword="false"
              placeholder="Select Box Label (Items Remark)"
            >
              <el-option
                v-for="item in listBoxLabels"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
            <el-button type="success" plain
              >Add Box Label <el-icon><Box /></el-icon
            ></el-button>
            <el-button type="danger" plain
              >Delete Box Label <el-icon><Delete /></el-icon
            ></el-button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout copy 2.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

export default {
  components: {
    AuthenticatedLayout,
  },
  data() {
    return {
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
          value: "",
          label: "",
        },
        {
          value: "",
          label: "",
        },
        {
          value: "",
          label: "",
        },
      ],
    };
  },
};
</script>
