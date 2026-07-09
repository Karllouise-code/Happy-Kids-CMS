<template>
  <div>
    <div v-if="is_loading" class="hk-loading"></div>
    <div class="modal fade show" tabindex="-1" id="donate_modal" data-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius:12px;border:none;">
          <div class="modal-header justify-content-center" style="border-bottom:1px solid #e8e8ed;">
            <h5 style="font-family:'Barlow',sans-serif;font-weight:700;color:#1a1a2e;">Donate Now</h5>
          </div>
          <form @submit.prevent="onSubmit">
            <div class="modal-body px-5 pt-4">
              <div class="d-flex justify-content-between px-5" style="gap:8px;">
                <button v-for="(value, index) in prices" :key="index" class="btn btn-sm" :class="amount === value ? 'btn-danger' : 'btn-outline-secondary'" @click="handleButtonClick(value)" type="button" style="border-radius:20px;font-family:'Barlow',sans-serif;font-weight:600;">₱{{ value }}</button>
              </div>

              <div class="input-group mt-3 px-5">
                <span class="input-group-text" style="background:#f8f9fa;border:1px solid #e8e8ed;">₱</span>
                <input @keypress="checkInput" type="text" class="form-control" style="border:1px solid #e8e8ed;font-size:1.3rem;" v-model="inputAmount" />
                <span class="input-group-text" style="background:#f8f9fa;border:1px solid #e8e8ed;">.00</span>
              </div>

              <span class="text-danger mb-0 px-5" style="font-size:0.8rem;display:block;">{{ amount_error }}</span>

              <div class="form-check d-flex align-items-center mx-auto mt-3" style="padding-left:2.5rem;">
                <input class="form-check-input" type="checkbox" id="coverCost" v-model="coverCost" style="border-color:#e8e8ed;" />
                <label class="form-check-label" for="coverCost" style="font-size:0.85rem;color:#6e6e73;">Cover Transaction Cost</label>
              </div>

              <div class="px-5" style="font-size:0.8rem;color:#6e6e73;">
                <p v-if="coverCost" class="mb-0">Transaction fee: ₱{{ (inputAmount * percentageDeducted + fixedDeducted).toFixed(2) }}</p>
                <p v-if="coverCost" class="mb-0" style="color:#1a1a2e;font-weight:700;">Total: ₱{{ displayAmount }}</p>
              </div>
            </div>

            <div class="modal-footer border-0 pt-0" style="padding:1rem;">
              <div class="d-flex align-items-center gap-3">
                <a @click="onHideModal" href="javaScript:void(0)" style="color:#6e6e73;text-decoration:none;font-size:0.85rem;">Cancel</a>
                <button type="submit" class="hk-btn hk-btn-gold hk-btn-sm">Submit</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      is_loading: false,
      prices: [50, 200, 500, 1000],
      amount: 0,
      inputAmount: 0,
      amount_error: "",
      coverCost: false,
      percentageDeducted: 0.039,
      fixedDeducted: 15,
    };
  },
  methods: {
    onValidateAmount() {
      let isError = false;
      if (this.totalAmount < 50) {
        this.amount_error = "Minimum amount is ₱50.";
        isError = true;
      }
      if (this.totalAmount === 0 || this.totalAmount === "") {
        this.amount_error = "Please enter an amount.";
        isError = true;
      }
      return isError;
    },
    onHideModal() {
      this.$emit("onHideModal");
      this.onClearFields();
      this.onClearErrors();
    },
    handleButtonClick(value) {
      this.amount = value;
      this.inputAmount = value;
    },
    onSubmit() {
      this.onClearErrors();
      if (this.onValidateAmount()) return;
      this.is_loading = true;
      this.$front_queries("save_donate", {
        donate: {
          amount: this.totalAmount,
          action_type: "donate",
        },
      })
        .then((res) => {
          this.is_loading = false;
          if (res.data.errors) {
            let errors = Object.values(res.data.errors[0].extensions.validation).flat();
            let errors_keys = Object.keys(res.data.errors[0].extensions.validation).flat();
            this.amount_error = errors_keys.some((q) => q == "donate.amount") ? errors[errors_keys.indexOf("donate.amount")] : "";
          } else {
            let response = res.data.data.donate;
            if (!response.error) {
              this.onClearFields();
              this.onClearErrors();
              this.$emit("success");
              window.location.href = response.href;
              this.is_loading = true;
            } else {
              Swal.fire("Error!", response.message, "error");
            }
          }
        })
        .catch(() => {
          this.is_loading = false;
          Swal.fire("Error!", this.global_error_message, "error");
        });
    },
    onClearFields() {
      this.amount = 0;
      this.inputAmount = 0;
      this.coverCost = false;
    },
    onClearErrors() {
      this.amount_error = "";
    },
    checkInput(event) {
      let keyCode = event.keyCode ? event.keyCode : event.which;
      if (keyCode < 48 || keyCode > 57) {
        event.preventDefault();
      }
    },
  },
  computed: {
    totalAmount() {
      let amount = Number(this.inputAmount);
      if (isNaN(amount)) return 0;
      if (this.coverCost) {
        return amount + amount * this.percentageDeducted + this.fixedDeducted;
      } else {
        return amount;
      }
    },
    displayAmount() {
      return this.totalAmount.toFixed(2);
    },
  },
  watch: {
    inputAmount(val) {
      this.amount = Number(val);
    },
  },
};
</script>
