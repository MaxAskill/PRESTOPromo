<script>
import { Link } from "@inertiajs/vue3";
import { Document, Menu, Setting } from "@element-plus/icons-vue";
import axios from "axios";

export default {
  components: {
    Link,
  },
  props: ["isCollapse"],
  data() {
    return {
      isMobile: null,
      date: "",
      time: "",
      emitResponse: true,
      subMenuIndex: 0,
    };
  },
  watch: {},
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
    subMenuEmitCollapse(index) {
      this.emitResponse = !this.emitResponse;
      if (this.subMenuIndex != index && this.emitResponse && index != 1)
        this.emitResponse = false;
      this.subMenuIndex = index;
      this.$emit("emitCollapseSideBar", this.emitResponse);
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
  <div class="fixed top-0 bottom-0 left-0 z-50">
    <el-menu
      ref="sideBarMenu"
      class="el-menu-vertical"
      :collapse="isCollapse"
      unique-opened="true"
    >
      <el-sub-menu index="1" @click="subMenuEmitCollapse(1)">
        <template #title>
          <el-icon><Avatar /></el-icon>
          <span tag="b">Name of Employee</span>
        </template>

        <el-menu-item-group>
          <el-menu-item index="1-1" href="/myprofile" method="post" as="button"
            >My Profile</el-menu-item
          >
          <el-menu-item index="1-2"
            ><Link :href="route('logout')" method="post" as="button">Logout</Link>
          </el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>
      <el-sub-menu index="2" @click="subMenuEmitCollapse(2)">
        <template #title>
          <el-icon><Van /></el-icon>
          <span tag="b">Pull-Out</span>
        </template>

        <el-menu-item-group>
          <el-menu-item index="2-1" href="" method="post" as="button">
            <el-icon><DocumentAdd /></el-icon>Pull-Out Form</el-menu-item
          >
          <el-menu-item index="2-2" :href="drafttransaction" method="post" as="button">
            <el-icon><Document /></el-icon>Pull-Out Draft Documentation
          </el-menu-item>
          <el-menu-item index="2-3" :href="pullouttransactions" method="post" as="button"
            ><el-icon><DocumentCopy /></el-icon> Pull-Out Transactions
          </el-menu-item>
        </el-menu-item-group>
      </el-sub-menu>
      <el-menu-item index="7" class="absolute-bottom" disabled>
        <el-icon><DocumentCopy /></el-icon>
        <template #title>Date and Time</template>
      </el-menu-item>
    </el-menu>
  </div>
</template>

<style>
.el-menu-vertical:not(.el-menu--collapse) {
  width: max-content;
  height: 100vh;
}
.el-menu-vertical {
  height: 100vh;
}
.absolute-bottom {
  position: absolute;
  bottom: 0;
}
</style>
