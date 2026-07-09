<template>
  <div>
    <div v-if="is_calling_api" class="hk-loading"></div>
    <div v-else>
      <section class="hk-page-header" :style="{ backgroundImage: getBgImage(cover_image) }">
        <div>
          <h1>Story Title</h1>
          <div class="hk-breadcrumb">
            <router-link :to="{ name: 'HomePage' }">Home</router-link>
            <span> / </span>
            <router-link :to="{ name: 'StoriesPage' }">Stories</router-link>
            <span> / </span>
            <span>Story Details</span>
          </div>
        </div>
      </section>

      <section class="hk-section">
        <div class="hk-container">
          <div style="display:grid;grid-template-columns:2fr 1fr;gap:48px;">
            <div>
              <img :src="imgSrc(cover_image)" :alt="blogs.image" style="width:100%;border-radius:12px;margin-bottom:24px;display:block;" @error="imgFallback" />
              <div style="display:flex;gap:16px;font-size:0.8rem;color:#6e6e73;margin-bottom:16px;">
                <span><i class="far fa-user-circle"></i> {{ formatFullname(author_firstname ?? "", author_lastname ?? "") }}</span>
                <span><i class="far fa-calendar-alt"></i> {{ blogs.date | formatTransDate2 }}</span>
              </div>
              <h2 style="font-family:'Barlow',sans-serif;font-weight:700;font-size:1.5rem;color:#1a1a2e;margin-bottom:16px;">{{ blogs.title }}</h2>
              <div v-html="blogs.description" style="color:#6e6e73;line-height:1.7;"></div>

              <div style="margin-top:32px;padding-top:24px;border-top:1px solid #e8e8ed;">
                <span style="font-size:0.8rem;color:#6e6e73;">Category: </span>
                <span class="hk-badge">{{ category_name }}</span>
              </div>

              <div v-if="related_blogs.length > 0" style="margin-top:32px;">
                <h3 style="font-family:'Barlow',sans-serif;font-weight:700;font-size:1.1rem;color:#1a1a2e;margin-bottom:16px;">Related Stories</h3>
                <div class="hk-blog-card" v-for="a in related_blogs.slice(0, 1)" :key="a.id">
                  <div class="hk-blog-body">
                    <router-link :to="{ name: 'StoriesDetailsPage', params: { slug: a.slug } }" class="hk-blog-title">{{ a.title }}</router-link>
                  </div>
                </div>
              </div>
            </div>

            <div class="hk-sidebar">
              <div class="hk-sidebar-widget">
                <h4>Latest Posts</h4>
                <div class="hk-sidebar-post" v-for="a in latest_blogs" :key="a.id" @click="onRedirectBlog(a)">
                  <img :src="imgSrc('/uploads/blogs/' + a?.original_blogs_id + '/thumb/' + a?.image)" alt="" @error="imgFallback" />
                  <div>
                    <h5>{{ a.title }}</h5>
                    <span>{{ a.date | formatTransDate2 }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</template>

<script>
export default {
  beforeRouteEnter(to, from, next) {
    next((vm) => {
      vm.onAddPageView();
    });
  },
  data() {
    return {
      is_calling_api: false,
      blogs: {},
      popular_blogs: [],
      related_blogs: [],
      latest_blogs: [],
      blog_categories: [],
      pages: {},
      author_firstname: "",
      author_lastname: "",
      minutes_read: "",
      author_profile_icon: "",
      slug: this.$route.params.slug,
      cover_image: "",
      category_name: "",
    };
  },
  created() {
    this.onPopulateData();
  },
  methods: {
    onPopulateData() {
      this.is_calling_api = true;
      this.$front_queries("display_data", {
        action_type: "display_by_blogs_slug",
        slug: this.slug,
      })
        .then((res) => {
          this.is_calling_api = false;
          let response = res.data.data.front;
          this.blogs = response.single_blog;
          this.author_firstname = this.blogs.author.firstname ?? "";
          this.author_lastname = this.blogs.author.lastname ?? "";
          this.latest_blogs = response.latest_posts;
          this.blog_categories = response.blog_category;
          this.related_blogs = response.related_blogs;
          this.cover_image = `/uploads/blogs/${this.blogs?.original_blogs_id}/large/${this.blogs?.image}`;
          this.category_name = this.blogs.blog_category?.name ?? "";
        })
        .catch((err) => {
          console.log(err);
        });
    },
    onRedirectBlog(data) {
      this.$router.push({ name: "StoriesDetailsPage", params: { slug: data.slug } });
    },
    onAddPageView() {
      this.$front_queries("save_blogs", {
        blogs: {
          action_type: "add_blog_views",
          slug: this.slug,
        },
      })
        .then((res) => {
          let response = res.data.data.blogs;
          if (response.error) {
            window.history.back();
            return false;
          }
        })
        .catch((err) => {
          console.log("err: ", err);
        });
    },
  },
  metaInfo() {
    return {
      title: this.blogs && this.blogs.meta ? this.blogs.meta.title : "",
      meta: [
        { vmid: "description", name: "description", content: this.blogs && this.blogs.meta ? this.blogs.meta.description : "" },
        { vmid: "keywords", name: "keywords", content: this.blogs && this.blogs.meta ? this.blogs.meta.keywords : "" },
        { vmid: "og:title", name: "og:title", content: this.blogs && this.blogs.meta ? this.blogs.meta.title : "" },
        { vmid: "og:description", name: "og:description", content: this.blogs && this.blogs.meta ? this.blogs.meta.description : "" },
      ],
    };
  },
  watch: {
    slug(val) {
      if (val) {
        this.onPopulateData();
      }
    },
    $route(to, from) {
      this.slug = to.params.slug;
      this.onAddPageView();
    },
  },
};
</script>
