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

        <div class="hk-grid-3">
          <div class="hk-blog-card" v-for="a in displayedBlogs" :key="a.id">
            <img class="hk-blog-img" :src="`/uploads/blogs/thumbnail/${a.original_blogs_id}/${a.thumbnail}`" :alt="a.thumbnail" />
            <div class="hk-blog-body">
              <div class="hk-blog-meta">
                <span><i class="far fa-user-circle"></i> {{ formatFullname(a.author?.firstname, a.author?.lastname) }}</span>
                <span><i class="far fa-calendar-alt"></i> {{ a.date | formatTransDate2 }}</span>
              </div>
              <router-link :to="{ name: 'StoriesDetailsPage', params: { slug: a.slug } }" class="hk-blog-title">{{ a.title }}</router-link>
              <p class="hk-blog-excerpt" v-html="truncate(a.description, 120)"></p>
              <router-link :to="{ name: 'StoriesDetailsPage', params: { slug: a.slug } }" class="hk-blog-link">Read More <i class="fas fa-arrow-right"></i></router-link>
            </div>
          </div>
        </div>

        <div style="text-align:center;margin-top:48px;" v-if="blogs.length > blog_card_limiter">
          <button class="hk-btn hk-btn-outline" @click="onClickSeeMore">
            {{ is_see_more ? "Show Less" : "See All Stories" }}
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
      blogs: [],
      pages: {},
      blog_card_limiter: 3,
      is_see_more: false,
    };
  },
  created() {
    this.onPopulateData();
  },
  methods: {
    onPopulateData() {
      this.is_loading = true;
      this.$front_queries("display_data", {
        action_type: "display_all_blogs",
      })
        .then((res) => {
          let response = res.data.data.front;
          this.pages = response.pages;
          this.blogs = response.blogs;
          this.is_loading = false;
        })
        .catch(() => {
          Swal.fire("Error!", this.global_error_message, "error");
        });
    },
    onClickSeeMore() {
      this.is_see_more = !this.is_see_more;
    },
  },
  computed: {
    displayedBlogs() {
      if (this.is_see_more) {
        return this.blogs;
      } else {
        return this.blogs.slice(0, this.blog_card_limiter);
      }
    },
  },
};
</script>
