<template>
  <header class="hk-header" :class="{ 'is-sticky': isSticky }" ref="header">
    <div class="hk-container">
      <div class="hk-header-inner">
        <router-link :to="{ name: 'HomePage' }" class="hk-logo">
          <img src="/images/happy_kids_logo.jpg" alt="Happy Kids" />
          <div>
            <h4 style="margin:0;font-size:1rem;">Trimex Colleges</h4>
            <span style="font-size:0.75rem;color:#6e6e73;">Happy Kids</span>
          </div>
        </router-link>

        <nav class="hk-nav">
          <router-link :to="{ name: 'HomePage' }">Home</router-link>
          <div class="hk-dropdown">
            <a href="javascript:void(0)" class="hk-nav-link">About</a>
            <div class="hk-dropdown-menu">
              <router-link :to="{ name: 'AboutPage' }">Our Mission</router-link>
              <router-link :to="{ name: 'OurTeamPage' }">Our Team</router-link>
              <router-link :to="{ name: 'FAQPage' }">FAQs</router-link>
            </div>
          </div>
          <div class="hk-dropdown">
            <a href="javascript:void(0)" class="hk-nav-link">Our Program</a>
            <div class="hk-dropdown-menu">
              <router-link :to="{ name: 'FeedingProgramPage' }">Feeding</router-link>
              <router-link :to="{ name: 'ScholarshipProgramPage' }">Scholarship</router-link>
              <router-link :to="{ name: 'EventsPage' }">Events</router-link>
            </div>
          </div>
          <router-link :to="{ name: 'StoriesPage' }">Stories</router-link>
          <router-link :to="{ name: 'ContactPage' }">Contact Us</router-link>
        </nav>

        <button class="hk-btn hk-btn-gold hk-btn-sm" @click="onDonate">Donate Now</button>

        <button class="hk-mobile-toggle" @click="mobileOpen = !mobileOpen">☰</button>
      </div>
    </div>

    <div class="hk-mobile-nav" :class="{ open: mobileOpen }">
      <router-link @click.native="mobileOpen = false" :to="{ name: 'HomePage' }">Home</router-link>
      <router-link @click.native="mobileOpen = false" :to="{ name: 'AboutPage' }">Our Mission</router-link>
      <router-link @click.native="mobileOpen = false" :to="{ name: 'OurTeamPage' }">Our Team</router-link>
      <router-link @click.native="mobileOpen = false" :to="{ name: 'FAQPage' }">FAQs</router-link>
      <router-link @click.native="mobileOpen = false" :to="{ name: 'FeedingProgramPage' }">Feeding Program</router-link>
      <router-link @click.native="mobileOpen = false" :to="{ name: 'ScholarshipProgramPage' }">Scholarship Program</router-link>
      <router-link @click.native="mobileOpen = false" :to="{ name: 'EventsPage' }">Events</router-link>
      <router-link @click.native="mobileOpen = false" :to="{ name: 'StoriesPage' }">Stories</router-link>
      <router-link @click.native="mobileOpen = false" :to="{ name: 'ContactPage' }">Contact Us</router-link>
    </div>

    <donate-modal @onHideModal="onCancelForm" @success="onSuccess" />
  </header>
</template>

<script>
import DonateModal from "./donate_modal.vue";
export default {
  components: { DonateModal },
  data() { return { isSticky: false, mobileOpen: false }; },
  mounted() {
    window.addEventListener('scroll', () => {
      this.isSticky = window.scrollY > 10;
    });
  },
  methods: {
    onDonate() {
      $("#donate_modal").modal("show");
      $("#donate_modal").appendTo("body");
    },
    onCancelForm() { $("#donate_modal").modal("hide"); },
    onSuccess() { $("#donate_modal").modal("hide"); },
  },
};
</script>
