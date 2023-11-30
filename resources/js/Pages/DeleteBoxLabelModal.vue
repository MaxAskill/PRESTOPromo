<template>
  <div class="absolute inset-0 bg-black bg-opacity-50 z-30">
    <div class="flex justify-center max-h-screen mt-24">
      <div
        class="bg-white text-black rounded-lg shadow-xl p-6 md:w-3/5 w-11/12"
        role="dialog"
        aria-modal="true"
      >
        <div class="flex justify-between items-center text-center">
          <div class="flex-1 flex items-center">
            <h1 class="text-lg font-bold mx-auto">LIST OF BOXES</h1>
          </div>
          <div>
            <el-button class="ml-auto" type="danger" plain @click="$emit('close')">
              <el-icon><CloseBold /></el-icon>
            </el-button>
          </div>
        </div>

        <div class="flex">
          <el-table :data="transferredData.boxLabels" style="width: 100%">
            <el-table-column prop="id" label="Box Number" width="110" />
            <el-table-column prop="boxLabel" label="Box Label" min-width="300" />
            <el-table-column fixed="right" label="Action" width="120">
              <template #default="scope">
                <el-popconfirm
                  width="210"
                  title="Are you sure you want to delete this box?"
                  confirm-button-text="CONFIRM"
                  cancel-button-text="CANCEL"
                  @confirm="confirmDeleteBoxLabel"
                >
                  <template #reference>
                    <el-button
                      type="danger"
                      @click="
                        deleteBoxLabel = scope.row;
                        deleteBoxLabel.boxLength = transferredData.boxLabels.length;
                      "
                    >
                      <el-icon><Delete /></el-icon>
                    </el-button>
                  </template>
                </el-popconfirm>
              </template>
            </el-table-column>
          </el-table>
        </div>
        <div class="flex justify-end py-4">
          <el-button type="info" plain @click="$emit('close')">Close</el-button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ElMessage, ElMessageBox } from "element-plus";

export default {
  components: {},
  props: ["transferredData", "newItemInputBox"],
  data() {
    return {
      openDeleteConfirmationModal: false,
      deleteboxLabel: null,
      deletedItems: [],
    };
  },
  methods: {
    // transferBoxLabel(deleteboxLabel) {
    //   this.deleteBoxLabel = deleteboxLabel;
    //   this.deleteBoxLabel.boxLength = this.transferredData.boxLabels.length;
    //   // ElMessageBox.confirm("Are you sure you want to delete this box?", {
    //   //   confirmButtonText: "CONFIRM",
    //   //   cancelButtonText: "CANCEL",
    //   //   type: "warning",
    //   // })
    //   //   .then(() => {
    //   //     ElMessage({
    //   //       type: "success",
    //   //       message: "Box Deleted",
    //   //     });
    //   //     this.deletedItems = this.transferredData.items.filter(
    //   //       (obj) =>
    //   //         obj.boxNumber ===
    //   //         this.transferredData.boxLabels[
    //   //           this.transferredData.boxLabels.findIndex(
    //   //             (tablerow) => tablerow.id === this.deleteBoxLabel.id
    //   //           )
    //   //         ].id
    //   //     );
    //   //     this.removeBoxLabel();
    //   //   })
    //   //   .catch(() => {});
    // },
    confirmDeleteBoxLabel() {
      ElMessage({
        type: "success",
        message: "Box Deleted",
      });
      this.deletedItems = this.transferredData.items.filter(
        (obj) =>
          obj.boxNumber ===
          this.transferredData.boxLabels[
            this.transferredData.boxLabels.findIndex(
              (tablerow) => tablerow.id === this.deleteBoxLabel.id
            )
          ].id
      );
      this.removeBoxLabel();
    },
    removeBoxLabel() {
      let localData = this.transferredData.boxLabels.findIndex(
        (tablerow) => tablerow.id === this.deleteBoxLabel.id
      );
      this.transferredData.items = this.transferredData.items.filter(
        (obj) => obj.boxNumber !== this.transferredData.boxLabels[localData].id
      );
      if (Object.keys(this.transferredData.boxLabels).length !== 0) {
        for (let key in this.transferredData.boxLabels) {
          if (this.transferredData.boxLabels.hasOwnProperty(key)) {
            const box = this.transferredData.boxLabels[key];
            if (box.boxNumber > this.deleteBoxLabel.boxNumber) {
              box.boxNumber--;
            }
          }
        }
      }
      if (localData >= 0) {
        this.transferredData.boxLabels.splice(localData, 1);
        this.newItemInputBox.splice(localData, 1);
      }
      this.transferredData.boxLabels.forEach((key, index) => {
        this.transferredData.boxLabels[index].id = index + 1;
      });
      this.$emit("DeletedItemsByBox", this.deletedItems);
      this.$emit("TransferDataBoxNumber", this.transferredData.boxLabels);
      this.$emit("DeletedBoxNumber", this.deleteBoxLabel.boxNumber);

      if (this.transferredData.boxLabels.length == 0) this.$emit("close");
    },
  },
};
</script>
