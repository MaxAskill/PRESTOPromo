<script>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
import { Document, Menu, Location, Setting } from "@element-plus/icons-vue";
import axios from "axios";
// const isOpen = ref(false);

export default {
  components: {
    Link,
  },
  data() {
    return {
      isMobile: null,
      isOpen: false,
      isCollapse: true,
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
  },
  created() {
    setInterval(this.dateTime, 1000);
  },
  mounted() {
    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
  },
};
</script>

<template>
  <div class="flex">
    <el-menu
      class="el-menu-vertical-demo"
      :collapse="isCollapse"
      @open="handleOpen"
      @close="handleClose"
    >
      <el-sub-menu index="1">
        <template #title>
          <el-icon><Avatar /></el-icon>
          <span tag="b">Name of Employee</span>
        </template>

        <span tag="b">Date: {{ date }}</span>

        <span tag="b">Time: {{ time }}</span>

        <el-menu-item-group>
          <el-menu-item index="1-1" href="route('profile.edit')" method="post" as="button"
            >My Profile</el-menu-item
          >
          <el-menu-item index="1-2"
            ><Link :href="route('logout')" method="post" as="button">Logout</Link>
          </el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>
      <!-- <el-sub-menu index="2">
        <template #title>
          <el-icon><location /></el-icon>
          <span>Navigator One</span>
        </template>
        <el-menu-item-group>
          <template #title><span>Group One</span></template>
          <el-menu-item index="2-1">item one</el-menu-item>
          <el-menu-item index="2-2">item two</el-menu-item>
        </el-menu-item-group>
        <el-menu-item-group title="Group Two">
          <el-menu-item index="2-3">item three</el-menu-item>
        </el-menu-item-group>
        <el-sub-menu index="2-4">
          <template #title><span>item four</span></template>
          <el-menu-item index="1-4-1">item one</el-menu-item>
        </el-sub-menu>
      </el-sub-menu> -->
      <el-menu-item index="3">
        <el-icon><DocumentAdd /></el-icon>
        <template #title>Pull-Out Form</template>
      </el-menu-item>
      <el-menu-item index="4">
        <el-icon><Document /></el-icon>
        <template #title>Pull-Out Draft Documentation</template>
      </el-menu-item>
      <el-menu-item index="5">
        <el-icon><DocumentCopy /></el-icon>
        <template #title>Pull-Out Transaction</template>
      </el-menu-item>
      <el-sub-menu index="6">
        <template #title>
          <el-icon><Setting /></el-icon>
          <span>Navigator Four</span>
        </template>
        <el-menu-item-group>
          <template #title><span>Group One</span></template>
          <el-menu-item index="5-1" :href="route('profile.edit')">Profile</el-menu-item>
          <el-menu-item index="5-2" :href="route('logout')" method="post" as="button"
            >Logout</el-menu-item
          >
        </el-menu-item-group>
      </el-sub-menu>
      <el-menu-item index="7" class="absolute-bottom" disabled>
        <el-icon><DocumentCopy /></el-icon>
        <template #title>Date and Time</template>
      </el-menu-item>
    </el-menu>
    <!-- Responsive Navigation Menu -->
    <div>
      <div class="flex items-center justify-between">
        <button @click="isCollapse = !isCollapse">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="w-6 h-6 text-black"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"
            />
          </svg>
        </button>
      </div>
    </div>
    <!-- Page Heading -->
    <!-- <header class="bg-white shadow" v-if="$slots.header">
      <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <slot name="header" />
      </div>
    </header> -->

    <!-- Page Content -->
    <main class="w-full">
      <slot />
    </main>
    <!-- <div class="container mx-auto mt-12">
            <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-3">
                <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 truncate">
                        Total users
                    </div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        12,00
                    </div>
                </div>
                <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 truncate">
                        Total Profit
                    </div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        $ 450k
                    </div>
                </div>
                <div class="w-full px-4 py-5 bg-white rounded-lg shadow">
                    <div class="text-sm font-medium text-gray-500 truncate">
                        Total Orders
                    </div>
                    <div class="mt-1 text-3xl font-semibold text-gray-900">
                        20k
                    </div>
                </div>
            </div>
        </div> -->
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
