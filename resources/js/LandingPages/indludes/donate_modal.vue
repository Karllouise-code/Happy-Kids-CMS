<template>
  <div>
    <div v-if="is_loading" class="hk-loading"></div>
    <div class="modal fade show hk-donate-modal" tabindex="-1" id="donate_modal" data-backdrop="static" data-bs-keyboard="false">
      <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:480px;">
        <div class="modal-content">

          <!-- Header -->
          <div class="hk-donate-header">
            <div class="hk-donate-icon">❤</div>
            <h3 class="hk-donate-title">Make a Donation</h3>
            <p class="hk-donate-subtitle">Support our mission to help children in need</p>
          </div>

          <!-- Frequency Toggle -->
          <div class="hk-freq-toggle">
            <div class="hk-freq-option" :class="{ active: frequency === 'one-time' }" @click="frequency = 'one-time'">One-time</div>
            <div class="hk-freq-option" :class="{ active: frequency === 'monthly' }" @click="frequency = 'monthly'">Monthly</div>
          </div>

          <!-- Amount Selection -->
          <div class="hk-amount-section">
            <span class="hk-amount-label">Select Amount</span>
            <div class="hk-amount-grid">
              <div
                v-for="(value, index) in prices"
                :key="index"
                class="hk-amount-pill"
                :class="{ active: amount === value }"
                @click="handleButtonClick(value)"
              >₱{{ value.toLocaleString() }}</div>
              <div
                class="hk-amount-pill is-custom"
                :class="{ active: isCustomActive }"
                @click="focusCustomInput"
              >Custom</div>
            </div>
            <div class="hk-amount-input">
              <span class="hk-currency">₱</span>
              <input ref="customInput" type="text" v-model="inputAmountDisplay" @keypress="checkInput" @focus="onCustomFocus" />
              <span class="hk-decimal">.00</span>
            </div>
            <span class="hk-amount-error" v-if="amount_error">{{ amount_error }}</span>
          </div>

          <!-- Cover Fee -->
          <div class="hk-fee-section">
            <div class="hk-fee-toggle" @click="coverCost = !coverCost">
              <div class="hk-fee-checkbox" :class="{ checked: coverCost }"></div>
              <span class="hk-fee-label">Cover Transaction Cost</span>
            </div>
            <div class="hk-fee-breakdown" v-if="coverCost && displayAmount > 0">
              <div class="hk-fee-row">
                <span>Transaction fee (3.9% + ₱15)</span>
                <span>₱{{ transactionFee }}</span>
              </div>
              <div class="hk-fee-total">
                <span>Total</span>
                <span>₱{{ displayAmount }}</span>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="hk-donate-footer">
            <button class="hk-donate-cancel" @click="onHideModal">Cancel</button>
            <button class="hk-donate-submit" :disabled="totalAmount < 50" @click="onSubmit">Proceed to Pay</button>
          </div>

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
      frequency: 'one-time',
      prices: [50, 100, 200, 500, 1000, 5000],
      amount: 0,
      inputAmount: 0,
      inputAmountDisplay: '0',
      amount_error: "",
      coverCost: false,
      isCustomActive: false,
      percentageDeducted: 0.039,
      fixedDeducted: 15,
    };
  },
  methods: {
    onValidateAmount() {
      if (this.totalAmount < 50) {
        this.amount_error = "Minimum amount is ₱50.";
        return true;
      }
      if (this.totalAmount === 0 || isNaN(this.totalAmount)) {
        this.amount_error = "Please enter an amount.";
        return true;
      }
      return false;
    },
    onHideModal() {
      this.$emit("onHideModal");
      this.onClearFields();
      this.onClearErrors();
    },
    handleButtonClick(value) {
      this.amount = value;
      this.inputAmount = value;
      this.inputAmountDisplay = String(value);
      this.isCustomActive = false;
      this.amount_error = "";
    },
    focusCustomInput() {
      this.isCustomActive = true;
      this.amount = 0;
      this.inputAmount = 0;
      this.inputAmountDisplay = '';
      this.$nextTick(() => {
        if (this.$refs.customInput) this.$refs.customInput.focus();
      });
    },
    onCustomFocus() {
      this.isCustomActive = true;
      this.amount = 0;
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
      this.inputAmountDisplay = '0';
      this.coverCost = false;
      this.isCustomActive = false;
      this.frequency = 'one-time';
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
      }
      return amount;
    },
    displayAmount() {
      return this.totalAmount.toFixed(2);
    },
    transactionFee() {
      let amount = Number(this.inputAmount);
      if (isNaN(amount)) return "0.00";
      return (amount * this.percentageDeducted + this.fixedDeducted).toFixed(2);
    },
  },
  watch: {
    inputAmountDisplay(val) {
      let num = parseInt(val.replace(/[^0-9]/g, ''), 10);
      if (isNaN(num)) num = 0;
      this.inputAmount = num;
      this.amount = num;
    },
  },
};
</script>
