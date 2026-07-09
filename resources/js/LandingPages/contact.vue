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
        <div class="hk-split">
          <div>
            <span class="hk-section-label">{{ pages.description?.title }}</span>
            <h2 class="hk-section-title">{{ pages.description?.sub_title }}</h2>
            <p style="color:#6e6e73;line-height:1.6;margin-bottom:32px;">{{ pages.description?.contact_description }}</p>
            <div class="hk-footer-social" style="display:flex;gap:16px;">
              <a target="_blank" href="https://www.facebook.com/TrimexCollegesHappyKids/" aria-label="facebook" style="color:#6e6e73;font-size:1.2rem;"><i class="fab fa-facebook-square"></i></a>
              <a target="_blank" href="https://www.instagram.com/trimexcollegeshappykids/" aria-label="instagram" style="color:#6e6e73;font-size:1.2rem;"><i class="fab fa-instagram"></i></a>
            </div>
          </div>

          <div>
            <form @submit.prevent="onSubmit">
              <div class="hk-form-group">
                <input type="text" v-model="name" class="hk-input" :class="{ 'has-error': name_error }" placeholder="Your Name" />
                <small class="hk-error" v-if="name_error">{{ name_error }}</small>
              </div>
              <div class="hk-form-group">
                <input type="email" v-model="email" class="hk-input" :class="{ 'has-error': email_error }" placeholder="Email Address" />
                <small class="hk-error" v-if="email_error">{{ email_error }}</small>
              </div>
              <div class="hk-form-group">
                <input type="text" v-model="phone" class="hk-input" :class="{ 'has-error': phone_error }" placeholder="Phone Number" />
                <small class="hk-error" v-if="phone_error">{{ phone_error }}</small>
              </div>
              <div class="hk-form-group">
                <input type="text" v-model="subject" class="hk-input" :class="{ 'has-error': subject_error }" placeholder="Subject" />
                <small class="hk-error" v-if="subject_error">{{ subject_error }}</small>
              </div>
              <div class="hk-form-group">
                <textarea v-model="message" class="hk-input" :class="{ 'has-error': message_error }" placeholder="Write a Message"></textarea>
                <small class="hk-error" v-if="message_error">{{ message_error }}</small>
              </div>
              <button type="submit" class="hk-btn hk-btn-primary">Submit Message</button>
            </form>
          </div>
        </div>
      </div>
    </section>

    <div class="hk-map">
      <iframe title="Google Maps" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3865.52394025598!2d121.08277597507755!3d14.339062986116863!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d9fb7cc36f43%3A0x8b917e497bdafc50!2sTrimex%20Colleges!5e0!3m2!1sen!2sph!4v1701584053000!5m2!1sen!2sph" allowfullscreen></iframe>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      is_loading: false,
      pages: [],
      name: "",
      email: "",
      phone: "",
      subject: "",
      message: "",
      name_error: "",
      email_error: "",
      phone_error: "",
      subject_error: "",
      message_error: "",
    };
  },
  created() {
    this.onPopulateData();
  },
  methods: {
    onPopulateData() {
      this.is_loading = true;
      this.$front_queries("front_page_data", {
        action_type: "display_contact_page",
      })
        .then((res) => {
          this.is_loading = false;
          let response = res.data.data.front;
          this.pages = response.pages;
        })
        .catch((err) => {
          console.error("error:" + err);
        });
    },
    isFieldsValid() {
      let is_valid = true;
      if (this.name == "") { this.name_error = "Name is required"; is_valid = false; } else { this.name_error = ""; }
      if (this.email == "") { this.email_error = "Email is required"; is_valid = false; } else { this.email_error = ""; }
      if (this.phone == "") { this.phone_error = "Phone is required"; is_valid = false; } else { this.phone_error = ""; }
      if (this.subject == "") { this.subject_error = "Subject is required"; is_valid = false; } else { this.subject_error = ""; }
      if (this.message == "") { this.message_error = "Message is required"; is_valid = false; } else { this.message_error = ""; }
      return is_valid;
    },
    onClearFields() {
      this.name = "";
      this.email = "";
      this.phone = "";
      this.subject = "";
      this.message = "";
    },
    onSubmit() {
      if (this.isFieldsValid()) {
        this.is_loading = true;
        this.$front_queries("send_email", {
          front: {
            name: this.name,
            email: this.email,
            phone: this.phone,
            subject: this.subject,
            message: this.message,
            action_type: "send_email",
          },
        })
          .then((res) => {
            this.is_loading = false;
            let response = res.data.data.front;
            if (response.error) {
              Swal.fire("Error!", this.global_error_message, "error");
              return;
            }
            Swal.fire("Success!", response.message, "success");
            this.onClearFields();
          })
          .catch((err) => {
            console.error("error:" + err);
          });
      }
    },
  },
};
</script>
