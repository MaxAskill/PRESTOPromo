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
      isCollapse: false,
      showSideBar: true,
      // sideBarWidth: "flex-1 relative float-right",
      viewportWidth: null,
      activeMenu: "dashboard", // Set the active menu item initially
      date: "",
      time: "",
      activeIndex: null,
      labelHeader: "",
    };
  },
  watch: {
    viewportWidth: {
      handler() {
        if (this.viewportWidth < 991) {
          this.showSideBar = false;
          this.isCollapse = false;
        } else {
          this.showSideBar = true;
        }
      },
      deep: true,
    },
  },
  methods: {
    dateTime() {
      const today = new Date();

      const months = [
        "January",
        "February",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December",
      ];

      // const today = new Date();
      const formattedDate =
        months[today.getMonth()] +
        " " +
        (today.getDate() < 10 ? "0" : "") +
        today.getDate() +
        ", " +
        today.getFullYear();

      // const date =
      //   today.getFullYear() + "-" + (today.getMonth() + 1) + "-" + today.getDate();

      this.date = formattedDate;

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

      this.time = time;
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
    handleViewportResize() {
      this.viewportWidth = window.innerWidth;
    },
  },
  created() {
    setInterval(this.dateTime, 1000);
    window.addEventListener("resize", this.handleViewportResize);
  },
  mounted() {
    this.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    this.handleViewportResize();

    switch (window.location.href.slice(window.location.href.lastIndexOf("/"))) {
      case "/pulloutform":
        this.activeIndex = "2-1";
        this.labelHeader = "Pull-Out Requisition Form";
        break;
      case "/drafttransaction":
        this.activeIndex = "2-2";
        this.labelHeader = "Pull-Out Draft Documentation";
        break;
      case "/pullouttransactions":
        this.activeIndex = "2-3";
        this.labelHeader = "Pull-Out Transactions";
        break;
      case "/myprofile":
        this.activeIndex = "1-1";
        this.labelHeader = "My Profile";
        break;
    }
  },
  computed: {},
};
</script>

<template>
  <div class="relative top-0 h-screen bg-[#f4f3ef]">
    <div class="flex fixed top-0 bottom-0 left-0 z-20 w-full h-full">
      <el-menu
        v-if="showSideBar"
        ref="menuRef"
        class="el-menu-vertical"
        :collapse="isCollapse"
        @open="handleOpen"
        @close="handleClose"
      >
        <div :class="!isCollapse ? 'flex mx-3 px-2 py-2.5' : 'flex mx-3 my-2.5'">
          <div class="w-9">
            <img src="../../../public/images/BElogo.png" />
          </div>
          <label v-if="!isCollapse" class="mt-1 text-xl font-semibold px-3">
            PRESTO</label
          >
        </div>
        <div class="border-b-2 border-gray-300 mx-3"></div>
        <el-sub-menu index="1">
          <template #title>
            <el-icon><Avatar /></el-icon>
            <span class="font-bold text-xs uppercase" tag="b">{{
              $page.props.auth.user.name
            }}</span>
          </template>

          <el-menu-item-group>
            <Link :href="route('myprofile')" as="button" class="w-full">
              <el-menu-item
                index="1-1"
                :style="activeIndex == '1-1' ? 'color: #409eff' : ''"
                ><el-icon><User /></el-icon>My Profile
              </el-menu-item>
            </Link>
            <Link :href="route('logout')" method="post" as="button" class="w-full">
              <el-menu-item index="1-2"
                ><el-icon><SwitchButton /></el-icon>Logout
              </el-menu-item>
            </Link>
          </el-menu-item-group>
        </el-sub-menu>
        <div class="border-b border-gray-300 mx-3"></div>
        <el-sub-menu index="2">
          <template #title>
            <el-icon><Van /></el-icon>
            <span class="font-bold text-xs" tag="b">PULL-OUT</span>
          </template>

          <el-menu-item-group>
            <Link :href="route('pulloutform')" as="button" class="w-full">
              <el-menu-item
                index="2-1"
                :style="activeIndex == '2-1' ? 'color: #409eff' : ''"
              >
                <el-icon><DocumentAdd /></el-icon>Pull-Out Forms
              </el-menu-item>
            </Link>
            <Link :href="route('drafttransaction')" as="button" class="w-full">
              <el-menu-item
                index="2-2"
                :style="activeIndex == '2-2' ? 'color: #409eff' : ''"
              >
                <el-icon><Document /></el-icon>Pull-Out Draft Documentation
              </el-menu-item>
            </Link>

            <Link :href="route('pullouttransactions')" as="button" class="w-full">
              <el-menu-item
                index="2-3"
                :style="activeIndex == '2-3' ? 'color: #409eff' : ''"
                ><el-icon><DocumentCopy /></el-icon> Pull-Out Transactions
              </el-menu-item>
            </Link>
          </el-menu-item-group>
        </el-sub-menu>
        <div class="absolute-bottom flex justify-center w-full mb-4 text-xs text-center">
          <!-- <el-icon><DocumentCopy /></el-icon> -->
          <span>
            Date: <b>{{ date }}</b
            ><br />
            Time: <b>{{ time }}</b>
          </span>
        </div>
      </el-menu>

      <div class="flex-1 relative float-right">
        <div class="p-3 border-b border-gray-300 shadow-sm">
          <div class="flex items-center justify-between">
            <el-button
              v-if="viewportWidth > 990"
              type="info"
              @click="isCollapse = !isCollapse"
              circle
              plain
            >
              <el-icon v-if="isCollapse"><ArrowRightBold /></el-icon>
              <el-icon v-else><ArrowLeftBold /></el-icon>
            </el-button>
            <el-button
              v-else
              type="info"
              @click="showSideBar = !showSideBar"
              circle
              plain
            >
              <el-icon v-if="showSideBar"><Fold /></el-icon>
              <el-icon v-else><Expand /></el-icon>
            </el-button>
            <div
              v-if="!(viewportWidth < 600 && showSideBar) || viewportWidth >= 600"
              class="font-bold uppercase text-sm 4xs:text-base sm:text-xl subpixel-antialiased"
            >
              {{ labelHeader }}
            </div>
            <div class="w-2 sm:w-8 h-8"></div>
          </div>
        </div>
        <main>
          <slot />
        </main>
      </div>
    </div>
  </div>
</template>

<style>
.el-menu-vertical:not(.el-menu--collapse) {
  width: 260px;
  height: 100vh;
}
.el-menu-vertical {
  height: 100vh;
}
.absolute-bottom {
  position: absolute;
  bottom: 0;
}
.el-menu-item-group__title {
  padding: 0px !important;
}
.el-menu-item {
  margin: 0px 10px;
  padding: 0px 13px !important;
  font-size: 12px;
}

:root {
  --el-transition-duration: 0.3s;
  --el-transition-duration-fast: 0.2s;
  --el-transition-function-ease-in-out-bezier: cubic-bezier(0.645, 0.045, 0.355, 1);
  --el-transition-function-fast-bezier: cubic-bezier(0.23, 1, 0.32, 1);
}

#main-panel {
  /* transition: 0.4s cubic-bezier(0.685, 0.0473, 0.346, 1); */
  /* transition: 0.3s ease; */
  transition: all var(--el-transition-duration)
    var(--el-transition-function-ease-in-out-bezier);
}

#main-panel-fade {
  transition: opacity var(--el-transition-duration)
    var(--el-transition-function-fast-bezier);
}

#main-panel-md-fade {
  transition: transform var(--el-transition-duration)
      var(--el-transition-function-fast-bezier),
    opacity var(--el-transition-duration) var(--el-transition-function-fast-bezier);
}

#main-panel-fade-linear {
  transition: opacity var(--el-transition-duration-fast) linear;
}

#main-panel-border {
  transition: border-color var(--el-transition-duration-fast)
    var(--el-transition-function-ease-in-out-bezier);
}

#main-panel-box-shadow {
  transition: box-shadow var(--el-transition-duration-fast)
    var(--el-transition-function-ease-in-out-bezier);
}

#main-panel-color {
  transition: color var(--el-transition-duration-fast)
    var(--el-transition-function-ease-in-out-bezier);
}
</style>
