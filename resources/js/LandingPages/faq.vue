<template>
  <div>
    <div class="hk-loading" v-if="is_loading"></div>

    <section class="hk-page-header" :style="{ backgroundImage: `url('/uploads/pages/${pages.pages_id}/large/${pages.image}')` }">
      <div>
        <h1>Frequently Asked Questions</h1>
        <div class="hk-breadcrumb">
          <router-link :to="{ name: 'HomePage' }">Home</router-link>
          <span> / </span>
          <span>{{ pages?.title }}</span>
        </div>
      </div>
    </section>

    <section class="hk-section">
      <div class="hk-container" style="max-width:800px;">
        <div style="text-align:center;margin-bottom:48px;">
          <span class="hk-section-label">{{ pages?.description?.title }}</span>
          <h2 class="hk-section-title">{{ pages?.description?.sub_title }}</h2>
        </div>

        <div class="hk-faq-item" v-for="(a, index) in faq" :key="index">
          <div class="hk-faq-question" :class="{ open: activeFaq === index }" @click="activeFaq = activeFaq === index ? -1 : index">
            {{ a.question }}
          </div>
          <div class="hk-faq-answer" :class="{ open: activeFaq === index }">
            {{ a.answer }}
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
      faq: [],
      activeFaq: 0,
    };
  },
  created() {
    this.onPopulateData();
  },
  methods: {
    onPopulateData() {
      this.is_loading = true;
      this.$front_queries("front_page_data", {
        action_type: "display_faq_page",
      })
        .then((res) => {
          this.is_loading = false;
          let response = res.data.data.front;
          this.pages = response.pages;
          this.faq = response.faq;
        })
        .catch((err) => {
          console.error("error:" + err);
        });
    },
  },
};
</script>
