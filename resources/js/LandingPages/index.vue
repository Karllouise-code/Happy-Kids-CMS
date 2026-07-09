<template>
  <div>
    <landing-header></landing-header>
    <router-view></router-view>
    <landing-footer></landing-footer>
  </div>
</template>

<script>
import LandingHeader from "./indludes/header.vue";
import LandingFooter from "./indludes/footer.vue";
export default {
  name: "App",
  components: { LandingHeader, LandingFooter },
};
</script>
