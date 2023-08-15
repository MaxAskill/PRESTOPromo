<template>
  <Head title="Dashboard" />

  <AuthenticatedLayoutCopy>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
    </template>

    <div class="py-12">
      <div class="max-w-full mx-auto sm:px-6 lg:px-8">
        <div class="bg-white grid items-end gap-6 md:grid-cols-4 p-6">
          <div class="relative">
            <label></label>
            <el-select
              v-model="newTransaction.companyName"
              class="m-2 w-full"
              placeholder="Select Company Name"
              size="large"
            >
              <el-option
                v-for="item in listCompanyName"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </div>
          <div class="relative">
            <el-select
              v-model="newTransaction.chainCode"
              class="m-2 w-full"
              placeholder="Select Chain Code"
              size="large"
            >
              <el-option
                v-for="item in listChainCode"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </div>
          <div class="relative">
            <el-select
              v-model="newTransaction.branchName"
              class="m-2 w-full"
              placeholder="Select Branch Name"
              size="large"
            >
              <el-option
                v-for="item in listBranchName"
                :key="item.value"
                :label="item.label"
                :value="item.value"
              />
            </el-select>
          </div>
          <div class="relative">
            <el-select
              v-model="newTransaction.transactionType"
              class="m-2 w-full"
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
      </div>
    </div>
  </AuthenticatedLayoutCopy>
</template>

<script>
import AuthenticatedLayoutCopy from "@/Layouts/AuthenticatedLayout copy.vue";
import { Head } from "@inertiajs/vue3";
import { ref } from "vue";

export default {
  components: {
    AuthenticatedLayoutCopy,
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
    };
  },
};
</script>
