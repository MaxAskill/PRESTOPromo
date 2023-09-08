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
      isSideNavHidden: true,
      usersChart: null,
      commercesChart: null,
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
    toggleSideNav() {
      this.isSideNavHidden = !this.isSideNavHidden;
      console.log("dsada");
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
  <div class="flex flex-col h-screen bg-gray-100">
    <div class="bg-white text-white shadow w-full p-2 flex items-center justify-between">
      <div class="flex items-center">
        <div class="hidden md:flex items-center">
          <img
            src="https://www.emprenderconactitud.com/img/POC%20WCS%20(1).png"
            alt="Logo"
            class="w-28 h-18 mr-2"
          />
        </div>
        <div class="md:hidden flex items-center">
          <button id="menuBtn" class="text-black" @click="toggleSideNav()">
            adssdasd
            <i class="fas fa-bars text-gray-500 text-lg"></i>
          </button>
        </div>
      </div>

      <div class="space-x-5">
        <button class="text-black">
          bell
          <i class="fas fa-bell text-gray-500 text-lg"></i>
        </button>
        <button class="text-black">
          person
          <i class="fas fa-user text-gray-500 text-lg"></i>
        </button>
      </div>
    </div>

    <div class="flex-1 flex w-full overflow-hidden">
      <div
        class="p-2 bg-white w-60 flex flex-col md:flex"
        :class="{ hidden: isSideNavHidden }"
      >
        <nav>
          <a
            class="block text-gray-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-400 hover:to-cyan-300 hover:text-white"
            href="#"
          >
            <i class="fas fa-home mr-2"></i>Inicio
          </a>
          <a
            class="block text-gray-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-400 hover:to-cyan-300 hover:text-white"
            href="#"
          >
            <i class="fas fa-file-alt mr-2"></i>Autorizaciones
          </a>
          <a
            class="block text-gray-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-400 hover:to-cyan-300 hover:text-white"
            href="#"
          >
            <i class="fas fa-users mr-2"></i>Usuarios
          </a>
          <a
            class="block text-gray-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-400 hover:to-cyan-300 hover:text-white"
            href="#"
          >
            <i class="fas fa-store mr-2"></i>Comercios
          </a>
          <a
            class="block text-gray-500 py-2.5 px-4 my-4 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-400 hover:to-cyan-300 hover:text-white"
            href="#"
          >
            <i class="fas fa-exchange-alt mr-2"></i>Transacciones
          </a>
        </nav>

        <a
          class="block text-gray-500 py-2.5 px-4 my-2 rounded transition duration-200 hover:bg-gradient-to-r hover:from-cyan-400 hover:to-cyan-300 hover:text-white mt-auto"
          href="#"
        >
          <i class="fas fa-sign-out-alt mr-2"></i>Cerrar sesión
        </a>

        <div class="bg-gradient-to-r from-cyan-300 to-cyan-500 h-px mt-2"></div>

        <p class="mb-1 px-5 py-3 text-left text-xs text-cyan-500">
          Copyright WCSLAT@2023
        </p>
      </div>

      <div class="flex-1 p-2 md:p-6">
        <main>
          <slot />
        </main>
        <!-- <div class="absolute top-1 left-2 inline-flex items-center p-2">
            <i class="fas fa-search text-gray-400"></i>
          </div>
          <input
            class="w-full h-10 pl-10 pr-4 py-1 text-base placeholder-gray-500 border rounded-full focus:shadow-outline"
            type="search"
            placeholder="Buscar..."
          /> -->
      </div>
    </div>
  </div>
</template>

<style>
.absolute-bottom {
  position: absolute;
  bottom: 0;
}
</style>
