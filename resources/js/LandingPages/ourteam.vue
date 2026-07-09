<template>
  <div>
    <div class="hk-loading" v-if="is_loading"></div>

    <!-- Page Header -->
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

    <!-- Team Section -->
    <section class="hk-section">
      <div class="hk-container">
        <span class="hk-section-label" style="text-align:center;display:block;">{{ pages?.description?.title }}</span>
        <h2 class="hk-section-title" style="text-align:center;">{{ pages?.description?.sub_title }}</h2>
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
        action_type: "display_team_page",
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
