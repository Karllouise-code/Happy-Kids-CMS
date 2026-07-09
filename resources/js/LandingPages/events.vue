<template>
  <div>
    <div class="hk-loading" v-if="is_loading"></div>

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

    <section class="hk-section">
      <div class="hk-container">
        <div style="text-align:center;margin-bottom:48px;">
          <span class="hk-section-label">{{ pages?.description?.title }}</span>
          <h2 class="hk-section-title">{{ pages?.description?.sub_title }}</h2>
        </div>

        <div>
          <div class="hk-event-card" v-for="a in displayedEvents" :key="a.id" style="margin-bottom:16px;">
            <div class="hk-event-date">
              <span class="day">{{ a.date_start | formatTransDate3 }}</span>
            </div>
            <div class="hk-event-body">
              <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                <h4 class="hk-event-title">{{ a.title }}</h4>
                <span class="hk-badge" v-if="a.is_expired">Done</span>
              </div>
              <div class="hk-event-meta">
                <span><i class="far fa-clock"></i> {{ onDisplayTimeSpan(a.date_start, a.date_end) }}</span>
                <span><i class="fas fa-map-marker-alt"></i> {{ a.location }}</span>
              </div>
            </div>
          </div>
        </div>

        <div style="text-align:center;margin-top:48px;" v-if="events.length > events_card_limiter">
          <button class="hk-btn hk-btn-outline" @click="onClickSeeMore">
            {{ is_see_more ? "Show Less" : "See All Events" }}
          </button>
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
      events: [],
      pages: {},
      events_card_limiter: 9,
      is_see_more: false,
    };
  },
  methods: {
    onPopulateData() {
      this.is_loading = true;
      this.$front_queries("display_data", {
        action_type: "display_all_events",
      })
        .then((res) => {
          let response = res.data.data.front;
          this.pages = response.pages;
          this.events = response.events;
          this.is_loading = false;
          this.events.sort((a, b) => {
            if (a.is_expired === true && b.is_expired !== true) return 1;
            else if (a.is_expired !== true && b.is_expired === true) return -1;
            else return 0;
          });
        })
        .catch(() => {
          Swal.fire("Error!", this.global_error_message, "error");
        });
    },
    onClickSeeMore() {
      this.is_see_more = !this.is_see_more;
    },
    onCheckAndSetExpired() {
      this.$front_queries("events", {
        action_type: "check_and_set_expired",
      })
        .then(() => {})
        .catch(() => {
          Swal.fire("Error!", this.global_error_message, "error");
        });
    },
  },
  created() {
    this.onCheckAndSetExpired();
  },
  mounted() {
    this.onPopulateData();
  },
  computed: {
    displayedEvents() {
      if (this.is_see_more) {
        return this.events;
      } else {
        return this.events.slice(0, this.events_card_limiter);
      }
    },
  },
};
</script>
