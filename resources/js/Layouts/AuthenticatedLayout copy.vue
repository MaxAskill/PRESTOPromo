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
      isExpand: false,
      isShow: null,
      isMobile: null,
      isOpen: false,
      isCollapse: false,
      activeMenu: "dashboard", // Set the active menu item initially
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
  <!-- <div class="flex">
    <div
      :class="`flex flex-col w-16 min-h-screen overflow-hidden p-4 text-white bg-gray-900 transition duration-300 ease-out ${
        isExpand ? 'w-80 transition duration-300 ease-out' : ''
      }`"
    >
      <div class="mt-1 w-8 mb-4">
        <img src="../../../public/images/BElogo.png" />
      </div>
      <div>
        <div
          :class="`menu-toggle-wrap flex  mb-4 relative top-0 transition duration-300 ease-out ${
            isExpand
              ? 'top-[-3rem] transition duration-300 ease-out mt-1 justify-end'
              : 'justify-center'
          } `"
        >
          <button
            class="menu-toggle transition duration-300 ease-out hover:text-green-500 hover:translate-x-2 transition-transform"
            @click="expand()"
          >
            <span class="material-icons text-2xl">
              <el-icon tag="b" v-if="!isExpand"><DArrowRight /></el-icon>
              <el-icon tag="b" v-else><DArrowLeft /></el-icon>
            </span>
          </button>
        </div>
        <div class="mx-0 duration-300 ease-out">
          <button
            class="flex items-center no-underline p-2 transition duration-200 ease-out text-white rounded hover:bg-gray-700"
          >
            <span class="materials-icon text-2xl transition duration-200 ease-out">
              <el-icon><DocumentAdd /></el-icon>
            </span>
            <span class="transition duration-200 ease-out">
              <Link :href="route('dashboard')" as="button">Pull-Out Form</Link></span
            >
          </button>
          <button
            class="flex items-center no-underline p-2 transition duration-200 ease-out text-white rounded hover:bg-gray-700"
          >
            <span class="materials-icon text-2xl transition duration-200 ease-out">
              <el-icon><DocumentAdd /></el-icon>
            </span>
            <span class="transition duration-200 ease-out">
              <Link :href="route('drafttransaction')" as="button"
                >Pull-Out Draft Documentation</Link
              ></span
            >
          </button>
          <button
            class="flex items-center no-underline p-2 transition duration-200 ease-out text-white rounded hover:bg-gray-700"
          >
            <span class="materials-icon text-2xl transition duration-200 ease-out">
              <el-icon><DocumentAdd /></el-icon>
            </span>
            <span class="transition duration-200 ease-out">
              <Link :href="route('pullouttransactions')" as="button"
                >Pull-Out Transactions</Link
              ></span
            >
          </button>
        </div>
      </div>
    </div>
  </div> -->

  <div class="flex">
    <el-menu
      class="el-menu-vertical-demo sticky h-screen"
      v-if="isShow"
      :collapse="isCollapse"
      @open="handleOpen"
      @close="handleClose"
    >
      <el-sub-menu index="1">
        <template #title>
          <el-icon><Avatar /></el-icon>
          <span tag="b">Name of Employee</span>
        </template>

        <el-menu-item-group>
          <el-menu-item index="1-1" href="route('profile.edit')" method="post" as="button"
            >My Profile</el-menu-item
          >
          <el-menu-item index="1-2"
            ><Link :href="route('logout')" method="post" as="button">Logout</Link>
          </el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>
      <el-sub-menu index="2">
        <template #title>
          <el-icon><Van /></el-icon>
          <span tag="b">Pull-Out</span>
        </template>

        <el-menu-item-group>
          <el-menu-item index="2-1" @click="navigateTo('dashboard')" as="button">
            <el-icon><DocumentAdd /></el-icon>Pull-Out Form</el-menu-item
          >
          <el-menu-item index="2-2" @click="navigateTo('drafttransaction')" as="button">
            <el-icon><Document /></el-icon>Pull-Out Draft Documentation
          </el-menu-item>
          <el-menu-item
            index="2-3"
            @click="navigateTo('pullouttransactions')"
            as="button"
          >
            <el-icon><DocumentCopy /></el-icon> Pull-Out Transactions
          </el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>
      <el-menu-item index="7" class="absolute-bottom" disabled>
        <el-icon><DocumentCopy /></el-icon>
        <template #title>Date and Time</template>
      </el-menu-item>
    </el-menu>

    <div class="flex-1">
      <div class="p-4 border-b border-gray-300 shadow-sm">
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
      <main class="w-full">
        <slot />
      </main>
    </div>
  </div>
</template>

<style>
.el-menu-vertical-demo:not(.el-menu--collapse) {
  width: max-content;
  height: 100vh;
}
.el-menu-vertical-demo {
  height: 100vh;
}
.absolute-bottom {
  position: absolute;
  bottom: 0;
}
</style>
