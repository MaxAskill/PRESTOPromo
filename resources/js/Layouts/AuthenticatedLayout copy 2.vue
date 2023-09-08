<script>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, router } from "@inertiajs/vue3";
import { Document, Menu, Location, Setting } from "@element-plus/icons-vue";
import axios from "axios";
// const isOpen = ref(false);

export default {
  components: {
    Link,
    router,
  },
  data() {
    return {
      expandPullOut: false,
      isExpand: false,
      isShow: null,
      isMobile: null,
      isOpen: false,
      isCollapse: false,
      activeMenu: "pulloutform", // Set the active menu item initially
      date: "",
      time: "",
    };
  },
  methods: {
    dateTime() {
      const today = new Date();

      const date =
        today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate();

      this.date = date;

      const hours = today.getHours();
      const minutes = today.getMinutes();
      const seconds = today.getSeconds();

      let formattedHours = hours % 12;
      formattedHours = formattedHours === 0 ? 12 : formattedHours;

      const period = hours >= 12 ? "PM" : "AM";
      const formattedMinutes = minutes < 10 ? "0" + minutes : minutes;
      const formattedSeconds = seconds < 10 ? "0" + seconds : seconds;

      const time =
        formattedHours + ":" + formattedMinutes + ":" + formattedSeconds + " " + period;
      // const dateTime = date + ' ' + time;

      this.time = time;
      // console.log("Date:", this.date);
      // console.log("Time:", this.time);

      // this.position = sessionStorage.getItem("Position");
    },
    logout() {
      console.log("Logout");
      axios
        .post("logout")
        .then((response) => {
          console.log("Success Logout");
          this.$router.push("/");
        })
        .catch((error) => {
          console.error(error);
        });
      // this.$router.push({ path: "logout" });
    },
    navigateTo(route) {
      this.$router.push({ name: route }); // Use Vue Router to navigate
      this.activeMenu = route; // Update the active menu item
    },
    expand() {
      this.isExpand = !this.isExpand;
      console.log("expand", this.expand);
    },
  },
  created() {
    setInterval(this.dateTime, 1000);
  },
  mounted() {
    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    this.isShow = this.isMobile;
  },
};
</script>

<template>
  <div class="flex">
    <div
      v-if="!isShow"
      :class="`flex flex-col min-h-screen  overflow-hidden text-white bg-gray-900 transition duration-300 ease-out ${
        isExpand
          ? 'w-16 transition duration-300 ease-out'
          : 'w-72 transition duration-300 ease-out'
      }`"
    >
      <div class="flex px-4 pt-4">
        <div class="mt-1 w-8 mb-4">
          <img src="../../../public/images/BElogo.png" />
        </div>
        <label v-if="!isExpand" class="text-4xl px-4"> PRESTO</label>
      </div>
      <div>
        <div
          v-if="!isMobile"
          :class="`menu-toggle-wrap flex  mb-4 relative transition duration-300 ease-out ${
            isExpand
              ? ' transition duration-300 ease-out mt-1 justify-center'
              : ' justify-end flex top-[-3rem] pr-4'
          } `"
        >
          <button
            class="menu-toggle transition duration-300 ease-out hover:text-green-500 hover:translate-x-2 transition-transform"
            @click="expand()"
          >
            <span class="material-icons text-2xl">
              <el-icon tag="b" v-if="isExpand"><DArrowRight /></el-icon>
              <el-icon tag="b" v-else><DArrowLeft /></el-icon>
            </span>
          </button>
        </div>

        <button
          class="w-full px-4 py-2 flex transition duration-500 ease-out hover:bg-gray-600 hover:text-green-500"
          @click="expandPullOut = !expandPullOut"
        >
          <div class="mr-auto">
            <el-icon><Tickets /></el-icon>
            <span v-if="!isExpand" class="px-1">Pull-Out</span>
          </div>
          <el-icon v-show="!isExpand" v-if="expandPullOut" class="ml-auto"
            ><ArrowDownBold
          /></el-icon>
          <el-icon v-show="!isExpand" v-else class="ml-auto"><ArrowUpBold /></el-icon>
        </button>
        <div v-show="expandPullOut">
          <div class="w-full mx-0 transition duration-500 ease-out">
            <Link :href="route('pulloutform')" as="button" class="w-full">
              <button
                class="w-full flex items-center no-underline px-6 py-2 transition duration-500 ease-out text-white hover:bg-gray-700 hover:text-green-500"
              >
                <span class="materials-icon transition duration-500 ease-out">
                  <el-icon><DocumentAdd /></el-icon>
                </span>
                <span v-if="!isExpand" class="transition duration-500 ease-out">
                  Pull-Out Form
                </span>
              </button>
            </Link>
            <Link :href="route('drafttransaction')" as="button" class="w-full">
              <button
                class="w-full flex items-center no-underline px-6 py-2 transition duration-500 ease-out text-white hover:bg-gray-700 hover:text-green-500"
              >
                <span class="materials-icon transition duration-500 ease-out">
                  <el-icon><Document /></el-icon>
                </span>
                <span v-if="!isExpand" class="transition duration-500 ease-out">
                  Pull-Out Draft Documentation
                </span>
              </button>
            </Link>
            <Link :href="route('pullouttransactions')" as="button" class="w-full">
              <button
                class="w-full flex items-center no-underline px-6 py-2 transition duration-500 ease-out text-white hover:bg-gray-700 hover:text-green-500"
              >
                <span class="materials-icon transition duration-500 ease-out">
                  <el-icon><DocumentCopy /></el-icon>
                </span>
                <span v-if="!isExpand" class="transition duration-500 ease-out">
                  Pull-Out Transactions
                </span>
              </button>
            </Link>
          </div>
        </div>
      </div>
    </div>
    <div class="flex-1 max-w-full">
      <div v-if="isMobile" class="p-4 max-w-full border-b border-gray-300 shadow-sm">
        <div class="flex items-center justify-between">
          <el-button
            v-show="!isMobile"
            type="info"
            @click="isCollapse = !isCollapse"
            circle
            plain
          >
            <el-icon v-if="isCollapse"><ArrowRightBold /></el-icon>
            <el-icon v-else><ArrowLeftBold /></el-icon>
          </el-button>
          <el-button v-show="isMobile" type="info" @click="isShow = !isShow" circle plain>
            <el-icon v-if="isShow"><Fold /></el-icon>
            <el-icon v-else><Expand /></el-icon>
          </el-button>
        </div>
      </div>
      <main class="flex-1 flex max-w-full text-center overflow-hidden">
        <slot />
      </main>
    </div>
  </div>
</template>

<style>
.absolute-bottom {
  position: absolute;
  bottom: 0;
}
</style>
