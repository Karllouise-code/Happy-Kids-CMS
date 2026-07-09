<template>
  <footer class="hk-footer">
    <div class="hk-container">
      <div class="hk-footer-grid">
        <div>
          <img src="/images/happy_kids_logo.jpg" alt="Happy Kids" />
          <p>
            <a href="https://trimexcolleges.edu.ph" target="_blank">Trimex Colleges</a><br />
            Happy Kids
          </p>
          <ul class="hk-footer-contact">
            <li><i class="fas fa-phone"></i> <a href="tel:0495119278">(049) 511-9278</a></li>
            <li><i class="fas fa-envelope"></i> <a href="mailto:linkages@trimexcolleges.edu.ph">linkages@trimexcolleges.edu.ph</a></li>
            <li><i class="fas fa-map-marker-alt"></i> <a href="https://www.google.com/maps/place/Trimex+Colleges/@14.339063,121.0763387,16z/data=!4m10!1m2!2m1!1sTrimex+Colleges,+Bi%C3%B1an,+Laguna!3m6!1s0x3397d9fb7cc36f43:0x8b917e497bdafc50!8m2!3d14.339063!4d121.0853509!15sCh9UcmltZXggQ29sbGVnZXMsIEJpw7FhbiwgTGFndW5hkgEPcHJpdmF0ZV9jb2xsZWdl4AEA!16s%2Fg%2F11bwqqh8kv?entry=ttu" target="_blank">Trimex Colleges, Biñan, Laguna</a></li>
          </ul>
        </div>

        <div>
          <h4>Explore</h4>
          <ul class="hk-footer-links">
            <li><router-link :to="{ name: 'HomePage' }">Home</router-link></li>
            <li><router-link :to="{ name: 'AboutPage' }">Our Mission</router-link></li>
            <li><router-link :to="{ name: 'OurTeamPage' }">Our Team</router-link></li>
            <li><router-link :to="{ name: 'FAQPage' }">FAQs</router-link></li>
            <li><router-link :to="{ name: 'FeedingProgramPage' }">Feeding Program</router-link></li>
            <li><router-link :to="{ name: 'ScholarshipProgramPage' }">Scholarship Program</router-link></li>
            <li><router-link :to="{ name: 'EventsPage' }">Events</router-link></li>
            <li><router-link :to="{ name: 'StoriesPage' }">Stories</router-link></li>
            <li><router-link :to="{ name: 'ContactPage' }">Contact Us</router-link></li>
          </ul>
        </div>

        <div>
          <h4>Latest Stories</h4>
          <div class="hk-sidebar-post" v-for="blog in blogs" :key="blog.id" @click="onRedirectBlog(blog)">
            <img :src="'/uploads/blogs/' + blog?.original_blogs_id + '/thumb/' + blog?.image" alt="" />
            <div>
              <h5>{{ blog.title }}</h5>
              <span>{{ blog.date | formatTransDate }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="hk-footer-bottom">
        <p>© Copyright {{ new Date().getFullYear() }} Happy Kids. All rights reserved.</p>
        <div class="hk-footer-social">
          <a target="_blank" href="https://www.facebook.com/TrimexCollegesHappyKids/" aria-label="facebook"><i class="fab fa-facebook-square"></i></a>
          <a target="_blank" href="https://www.instagram.com/trimexcollegeshappykids/" aria-label="instagram"><i class="fab fa-instagram"></i></a>
        </div>
      </div>
    </div>
  </footer>
</template>

<script>
export default {
  data() {
    return { blogs: [] };
  },
  methods: {
    onPopulateData() {
      this.$front_queries("blogs", {
        action_type: "take_2_blogs",
      })
        .then((res) => {
          this.blogs = res.data.data.blogs;
        })
        .catch((err) => {
          console.error("footer error:" + err);
        });
    },
    onRedirectBlog(data) {
      this.$router.push({ name: "StoriesDetailsPage", params: { slug: data.slug } }).catch(() => {});
    },
  },
  created() {
    this.onPopulateData();
  },
};
</script>
