<template>
  <div>
    <div class="hk-loading" v-if="is_loading"></div>

    <!-- Page Header -->
    <section class="hk-page-header" :style="{ backgroundImage: getBgImage(`/uploads/pages/${pages.pages_id}/large/${pages.image}`) }">
      <div>
        <h1>{{ pages?.title }}</h1>
        <div class="hk-breadcrumb">
          <router-link :to="{ name: 'HomePage' }">Home</router-link>
          <span> / </span>
          <span>{{ pages?.title }}</span>
        </div>
      </div>
    </section>

    <!-- Two Images + Intro -->
    <section class="hk-section hk-section-sm" v-if="pages.extras_image_1 || pages.extras_image_2">
      <div class="hk-container">
        <div class="hk-split">
          <div>
            <img :src="`/uploads/pages/${pages.pages_id}/large/${pages.extras_image_1}`" alt="" v-if="pages.extras_image_1" />
          </div>
          <div>
            <img :src="`/uploads/pages/${pages.pages_id}/large/${pages.extras_image_2}`" alt="" v-if="pages.extras_image_2" />
          </div>
        </div>
      </div>
    </section>

    <!-- Mission Intro -->
    <section class="hk-section hk-section-alt" v-if="pages.description">
      <div class="hk-container">
        <div class="hk-split">
          <div>
            <span class="hk-section-label">{{ pages.description?.mission_intro_title }}</span>
            <h2 class="hk-section-title">{{ pages.description?.mission_intro_section_title }}</h2>
            <p style="color:#6e6e73;line-height:1.6;">{{ pages.description?.mission_intro_description }}</p>
          </div>
          <div>
            <ul class="hk-checklist">
              <li v-if="pages.description?.checklist_checklist1">{{ pages.description.checklist_checklist1 }}</li>
              <li v-if="pages.description?.checklist_checklist2">{{ pages.description.checklist_checklist2 }}</li>
              <li v-if="pages.description?.checklist_checklist3">{{ pages.description.checklist_checklist3 }}</li>
            </ul>
            <div style="margin-top:32px;padding:24px;background:#f8f9fa;border-radius:12px;text-align:center;">
              <p style="font-family:'Barlow',sans-serif;font-weight:700;font-size:1.2rem;color:#1a1a2e;">{{ pages.description?.checklist_short_text }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Team Section -->
    <section class="hk-section hk-section-dark" v-if="team.length > 0" :style="{ backgroundImage: getBgImage(`/uploads/pages/volunteer_/our-mission/${pages.description?.volunteer_image_webp}`), backgroundSize: 'cover', backgroundPosition: 'center' }">
      <div class="hk-container">
        <span class="hk-section-label" style="color:#F8B803;text-align:center;display:block;">{{ pages.description?.volunteers_title }}</span>
        <h2 class="hk-section-title" style="color:white;text-align:center;">{{ pages.description?.volunteers_subtitle }}</h2>
        <p style="color:rgba(255,255,255,0.7);text-align:center;max-width:600px;margin:0 auto 48px;">{{ pages.description?.volunteers_description }}</p>
        <div class="hk-grid-4">
          <div class="hk-team-card" v-for="(a, i) in team" :key="i">
            <img :src="imgSrc(`/uploads/team/${a.original_team_id}/${a.image}`)" :alt="a.name" @error="imgFallback" />
            <div class="hk-team-body">
              <h4>{{ a.name }}</h4>
              <span>{{ a.position }}</span>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Video Section -->
    <section class="hk-video" v-if="pages.description?.video_image_webp" :style="{ backgroundImage: getBgImage(`/uploads/pages/video_/our-mission/${pages.description.video_image_webp}`) }">
      <div>
        <span class="hk-section-label" style="color:#F8B803;">{{ pages.description?.video_title }}</span>
        <h3>{{ pages.description?.video_subtitle }}</h3>
        <div style="margin-top:24px;display:flex;gap:20px;justify-content:center;align-items:center;flex-wrap:wrap;">
          <a :href="pages.description?.video_link" target="_blank" class="hk-play-btn"><i class="fas fa-play"></i></a>
          <button class="hk-btn hk-btn-primary" @click="onDonate">Start Donating</button>
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
        action_type: "display_about_page",
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
    onDonate() {
      $("#donate_modal").modal("show");
      $("#donate_modal").appendTo("body");
    },
  },
};
</script>
