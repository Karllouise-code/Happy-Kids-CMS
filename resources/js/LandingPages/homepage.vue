<template>
  <div>
    <div class="hk-loading" v-if="is_loading"></div>

    <!-- Section 1: Hero -->
    <section class="hk-hero">
      <div class="swiper" ref="heroSwiper">
        <div class="swiper-wrapper">
          <div class="swiper-slide" :style="{ backgroundImage: getBgImage(pages.image ? `/uploads/pages/${pages.pages_id}/large/${pages.image}` : '') }">
            <div class="hk-hero-content">
              <h2>{{ pages.description?.title }}</h2>
              <p>{{ pages.description?.sub_title }}</p>
              <button class="hk-btn hk-btn-gold" @click="onDonate">Start Donating</button>
            </div>
          </div>
          <div class="swiper-slide" :style="{ backgroundImage: getBgImage(pages.extras_image_1 ? `/uploads/pages/${pages.pages_id}/large/${pages.extras_image_1}` : '') }">
            <div class="hk-hero-content">
              <h2>{{ pages.description?.title }}</h2>
              <p>{{ pages.description?.sub_title }}</p>
              <button class="hk-btn hk-btn-gold" @click="onDonate">Start Donating</button>
            </div>
          </div>
          <div class="swiper-slide" :style="{ backgroundImage: getBgImage(pages.extras_image_2 ? `/uploads/pages/${pages.pages_id}/large/${pages.extras_image_2}` : '') }">
            <div class="hk-hero-content">
              <h2>{{ pages.description?.title }}</h2>
              <p>{{ pages.description?.sub_title }}</p>
              <button class="hk-btn hk-btn-gold" @click="onDonate">Start Donating</button>
            </div>
          </div>
        </div>
        <div class="swiper-pagination" ref="heroPagination"></div>
      </div>
    </section>

    <!-- Section 2: Objectives -->
    <section class="hk-section" v-if="pages.description?.objective_description">
      <div class="hk-container">
        <div class="hk-grid-3">
          <div class="hk-objective-card hk-fade-in" v-for="(item, i) in pages.description.objective_description" :key="i">
            <div class="hk-objective-icon" :style="{ background: i === 0 ? 'rgba(222,6,0,0.1)' : i === 1 ? 'rgba(248,184,3,0.15)' : 'rgba(26,26,46,0.08)', color: i === 0 ? '#de0600' : i === 1 ? '#c59400' : '#1a1a2e' }">
              <i :class="i === 0 ? 'fas fa-heart' : i === 1 ? 'fas fa-hands-helping' : 'fas fa-graduation-cap'"></i>
            </div>
            <h3>{{ item }}</h3>
            <p v-if="pages.description.objective_sub_description && pages.description.objective_sub_description[i]">{{ pages.description.objective_sub_description[i] }}</p>
            <router-link :to="{ name: 'ContactPage' }" class="hk-btn hk-btn-ghost hk-btn-sm" style="margin-top:16px;">Learn More <i class="fas fa-arrow-right"></i></router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 3: About -->
    <section class="hk-section hk-section-alt" v-if="pages.description?.about_image_webp">
      <div class="hk-container">
        <div class="hk-split">
          <div class="hk-fade-in">
            <img :src="imgSrc(`/uploads/pages/about_/homepage/${pages.description.about_image_webp}`)" alt="About us" @error="imgFallback" />
          </div>
          <div class="hk-fade-in">
            <span class="hk-section-label">{{ pages.description?.about_main_title }}</span>
            <h2 class="hk-section-title">{{ pages.description?.about_subtitle }}</h2>
            <ul class="hk-checklist">
              <li v-for="(a, i) in pages.description?.about_title" :key="i">{{ a }}</li>
            </ul>
            <router-link :to="{ name: 'AboutPage' }" class="hk-btn hk-btn-primary" style="margin-top:24px;">Discover More</router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- Section 4: Video CTA -->
    <section class="hk-video" v-if="pages.description?.video_image_webp" :style="{ backgroundImage: getBgImage(`/uploads/pages/video_/homepage/${pages.description.video_image_webp}`) }">
      <div>
        <span class="hk-section-label" style="color:#F8B803;">{{ pages.description?.video_title }}</span>
        <h3>{{ pages.description?.video_subtitle }}</h3>
        <div style="display:flex;gap:20px;justify-content:center;align-items:center;flex-wrap:wrap;">
          <a :href="pages.description?.video_link" target="_blank" class="hk-play-btn"><i class="fas fa-play"></i></a>
        </div>
        <button class="hk-btn hk-btn-primary" style="margin-top:24px;" @click="onDonate">Start Donating</button>
      </div>
    </section>

    <!-- Section 5: FAQ -->
    <section class="hk-section" v-if="faq.length > 0">
      <div class="hk-container">
        <div class="hk-split">
          <div>
            <span class="hk-section-label">{{ pages.description?.faq_title }}</span>
            <h2 class="hk-section-title">{{ pages.description?.faq_subtitle }}</h2>
            <div class="hk-faq-item" v-for="(a, index) in faq" :key="index">
              <div class="hk-faq-question" :class="{ open: activeFaq === index }" @click="activeFaq = activeFaq === index ? -1 : index">
                {{ a.question }}
              </div>
              <div class="hk-faq-answer" :class="{ open: activeFaq === index }">
                {{ a.answer }}
              </div>
            </div>
          </div>
          <div class="hk-fade-in" v-if="pages.description?.faq_image_webp">
            <img :src="imgSrc(`/uploads/pages/faq_/homepage/${pages.description.faq_image_webp}`)" alt="FAQ" @error="imgFallback" />
          </div>
        </div>
      </div>
    </section>

    <!-- Section 6: Testimonials -->
    <section class="hk-section hk-section-dark" v-if="testimonials.length > 0" :style="{ backgroundImage: getBgImage(`/uploads/pages/testimonial_/homepage/${pages.description?.testimonial_image_webp}`), backgroundSize: 'cover', backgroundPosition: 'center' }">
      <div class="hk-container">
        <span class="hk-section-label" style="color:#F8B803;text-align:center;display:block;">{{ pages.description?.testimonial_title }}</span>
        <h2 class="hk-section-title" style="color:white;text-align:center;">{{ pages.description?.testimonial_subtitle }}</h2>
        <p style="color:rgba(255,255,255,0.7);text-align:center;max-width:600px;margin:0 auto 48px;">{{ pages.description?.testimonial_description }}</p>
        <div class="swiper" ref="testimonialSwiper">
          <div class="swiper-wrapper">
            <div class="swiper-slide" v-for="(a, i) in testimonials" :key="i">
              <div class="hk-testimonial-card">
                <img :src="imgSrc(`/uploads/testimonials/${a.original_testimonial_id}/${a.image}`)" :alt="a.name" @error="imgFallback" />
                <p>{{ a.description }}</p>
                <h4>{{ a.name }}</h4>
                <span>{{ a.position }}</span>
              </div>
            </div>
          </div>
          <div class="swiper-pagination"></div>
        </div>
      </div>
    </section>

    <!-- Section 7: Events -->
    <section class="hk-section" :style="{ backgroundImage: getBgImage(`/uploads/pages/event_/homepage/${pages.description?.event_image_webp}`), backgroundSize: 'cover', backgroundPosition: 'center' }" v-if="events.length > 0">
      <div class="hk-container">
        <span class="hk-section-label">{{ pages.description?.events_title }}</span>
        <h2 class="hk-section-title">{{ pages.description?.events_description }}</h2>
        <p class="hk-section-desc">{{ pages.description?.events_subtitle }}</p>
        <div style="margin-top:48px;">
          <div class="hk-event-card" v-for="a in events" :key="a.id" style="margin-bottom:16px;">
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
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: "Home",
  data() {
    return {
      is_loading: false,
      pages: [],
      testimonials: [],
      faq: [],
      events: [],
      activeFaq: 0,
    };
  },
  mounted() {
  },
  created() {
    this.onPopulateData();
  },
  methods: {
    onPopulateData() {
      this.is_loading = true;
      this.$front_queries("front_page_data", {
        action_type: "display_homepage",
      })
        .then((res) => {
          this.is_loading = false;
          let response = res.data.data.front;
          this.pages = response.pages;
          this.testimonials = response.testimonials;
          this.faq = response.faq;
          this.events = response.events;
          this.events.sort((a, b) => {
            if (a.is_expired === true && b.is_expired !== true) return 1;
            else if (a.is_expired !== true && b.is_expired === true) return -1;
            else return 0;
          });
          this.$nextTick(() => {
            this.initSwiper();
            this.initScrollAnimations();
          });
        })
        .catch((err) => {
          console.error("error:" + err);
        });
    },
    initSwiper() {
      if (this.$refs.heroSwiper) {
        new Swiper(this.$refs.heroSwiper, {
          slidesPerView: 1,
          loop: true,
          effect: "fade",
          pagination: {
            el: this.$refs.heroPagination,
            type: "bullets",
            clickable: true,
          },
          autoplay: { delay: 5000 },
        });
      }
      if (this.$refs.testimonialSwiper) {
        new Swiper(this.$refs.testimonialSwiper, {
          slidesPerView: 1,
          spaceBetween: 24,
          pagination: { el: ".hk-section-dark .swiper-pagination", type: "bullets", clickable: true },
          autoplay: { delay: 5000 },
          breakpoints: {
            768: { slidesPerView: 2, spaceBetween: 24 },
            1200: { slidesPerView: 3, spaceBetween: 32 },
          },
        });
      }
    },
    onDonate() {
      $("#donate_modal").modal("show");
      $("#donate_modal").appendTo("body");
    },
    onDisplayTimeSpan(start, end) {
      if (!start || !end) return "";
      let s = new Date(start);
      let e = new Date(end);
      let opts = { hour: "numeric", minute: "2-digit", hour12: true };
      return s.toLocaleTimeString("en-US", opts) + " - " + e.toLocaleTimeString("en-US", opts);
    },
    onCancelForm() {
      $("#donate_modal").modal("hide");
    },
    initScrollAnimations() {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("visible");
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1 });
      this.$nextTick(() => {
        document.querySelectorAll(".hk-fade-in").forEach((el) => observer.observe(el));
      });
    },
    onSuccess() {
      $("#donate_modal").modal("hide");
    },
  },
};
</script>
