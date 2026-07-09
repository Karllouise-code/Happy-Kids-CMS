<template>
  <div>
    <div class="hk-loading" v-if="is_loading"></div>

    <section class="hk-page-header" :style="{ backgroundImage: `url('/uploads/pages/${pages.pages_id}/large/${pages.image}')` }">
      <div>
        <h1>{{ pages?.title }}</h1>
        <div class="hk-breadcrumb">
          <router-link :to="{ name: 'HomePage' }">Home</router-link>
          <span> / </span>
          <span>{{ pages?.title }}</span>
        </div>
      </div>
    </section>

    <section class="hk-section">
      <div class="hk-container">
        <div style="text-align:center;margin-bottom:48px;">
          <span class="hk-section-label">{{ pages?.description?.title }}</span>
          <h2 class="hk-section-title">{{ pages?.description?.sub_title }}</h2>
        </div>
        <div class="hk-split">
          <div>
            <h3 style="font-family:'Barlow',sans-serif;font-weight:700;margin-bottom:16px;color:#1a1a2e;">{{ pages?.description?.program_intro_title }}</h3>
            <p style="color:#6e6e73;line-height:1.6;">{{ pages?.description?.program_intro_description }}</p>
          </div>
          <div>
            <img :src="`/uploads/pages/program_/feeding-program/${pages.description?.program_image_webp}`" :alt="pages?.description?.program_intro_title" />
          </div>
        </div>
      </div>
    </section>

    <section class="hk-section hk-section-alt">
      <div class="hk-container" style="max-width:800px;text-align:center;">
        <h2 class="hk-section-title">{{ pages?.description?.program_about_title }}</h2>
        <p style="color:#6e6e73;line-height:1.6;">{{ pages?.description?.program_about_description }}</p>
      </div>
    </section>

    <section class="hk-section hk-section-dark" v-if="team.length > 0">
      <div class="hk-container">
        <h2 class="hk-section-title" style="color:white;text-align:center;">Meet the Happy Kids Top Volunteers</h2>
        <div class="hk-grid-4" style="margin-top:48px;">
          <div class="hk-team-card" v-for="(a, i) in team" :key="i">
            <img :src="`/uploads/team/${a.original_team_id}/${a.image}`" :alt="a.name" />
            <div class="hk-team-body">
              <h4>{{ a.name }}</h4>
              <span>{{ a.position }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="hk-section">
      <div class="hk-container" style="max-width:800px;text-align:center;">
        <h2 class="hk-section-title">{{ pages?.description?.program_overview_title }}</h2>
        <p style="color:#6e6e73;line-height:1.6;">{{ pages?.description?.program_overview_description }}</p>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      is_loading: false,
      pages: [],
      team: [],
    };
  },
  created() {
    this.onPopulateData();
  },
  methods: {
    onPopulateData() {
      this.is_loading = true;
      this.$front_queries("front_page_data", {
        action_type: "display_feeding_program_page",
      })
        .then((res) => {
          this.is_loading = false;
          let response = res.data.data.front;
          this.pages = response.pages;
          this.team = response.team;
        })
        .catch((err) => {
          console.error("error:" + err);
        });
    },
  },
};
</script>
