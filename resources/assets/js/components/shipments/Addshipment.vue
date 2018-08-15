<template>
  <v-layout row justify-center>
  
    <v-dialog v-model="addShipment" persistent>
  
      <v-card>
        <v-card-title>
  
              Add Shipment
  
        </v-card-title>
  
        <v-container grid-list-md v-show="!loader">
          <v-card-text>
  
          <v-layout wrap>
  
            <v-form ref="form" @submit.prevent="submit">
  
              <v-container grid-list-xl fluid>
  
                <v-layout wrap>
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.client_name" :rules="rules.name" color="blue darken-2" label="Client name" required></v-text-field>
  
                  </v-flex>
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.client_email" :rules="emailRules" color="blue darken-2" label="Client Email" required></v-text-field>
  
                  </v-flex>
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.client_address" :rules="rules.name" color="blue darken-2" label="Client Address" required></v-text-field>
  
                  </v-flex>
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.client_city" :rules="rules.name" color="blue darken-2" label="Client City" required></v-text-field>
  
                  </v-flex>
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.client_phone" color="blue darken-2" label="Client Phone" required></v-text-field>
  
                  </v-flex>
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.assign_staff" :rules="rules.name" color="blue darken-2" label="Assigned Staff" required></v-text-field>
  
                  </v-flex>
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.airway_bill_no" color="blue darken-2" label="Zip Code" required></v-text-field>
  
                  </v-flex>
  
                  <v-divider></v-divider>
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.shipment_type" :rules="rules.name" color="blue darken-2" label="Shipment Type" required></v-text-field>
  
                  </v-flex>
  
  
  
                  <v-spacer></v-spacer>
  
                  <!-- date picker -->
  
                  <v-flex xs6 sm3 md3>
  
                    <v-dialog ref="dialog1" v-model="dmodal1" :return-value.sync="form.booking_date" persistent lazy full-width width="290px">
  
                      <v-text-field slot="activator" v-model="form.booking_date" label="Booking Date" prepend-icon="event" readonly></v-text-field>
  
                      <v-date-picker v-model="form.booking_date" scrollable>
  
                        <v-spacer></v-spacer>
  
                        <v-btn flat color="primary" @click="dmodal1 = false">Cancel</v-btn>
  
                        <v-btn flat color="primary" @click="$refs.dialog1.save(form.booking_date)">OK</v-btn>
  
                      </v-date-picker>
  
                    </v-dialog>
  
                  </v-flex>
  
  
  
                  <v-flex xs6 sm3 md3>
  
                    <v-dialog ref="dialog2" v-model="dmodal2" :return-value.sync="form.derivery_date" persistent lazy full-width width="290px">
  
                      <v-text-field slot="activator" v-model="form.derivery_date" label="Derivery Date" prepend-icon="event" readonly></v-text-field>
  
                      <v-date-picker v-model="form.derivery_date" scrollable>
  
                        <v-spacer></v-spacer>
  
                        <v-btn flat color="primary" @click="dmodal2 = false">Cancel</v-btn>
  
                        <v-btn flat color="primary" @click="$refs.dialog2.save(form.derivery_date)">OK</v-btn>
  
                      </v-date-picker>
  
                    </v-dialog>
  
                  </v-flex>
  
                  <!-- date picker -->
  
  
  
                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.derivery_time" :rules="rules.name" :type="'time'" color="blue darken-2" label="Derivery Time" required></v-text-field>
  
                  </v-flex>
  
                  <select class="custom-select col-md-3" v-model="form.insuarance_status">
  
                  <option value="0" selected="">Insuarance</option>
  
                  <option value="yes">Yes</option>
  
                  <option value="no">No</option>
  
                </select>
  
                  <select class="custom-select custom-select-md col-md-3" v-model="form.payment">
  
                  <option value="yes">Yes</option>
  
                  <option value="no">No</option>
  
                </select>
  
                  <select class="custom-select custom-select-md col-md-3" v-model="form.customer_id">
  
                  <option v-for="customers in Allcustomer" :key="customers.id" :value="customers.id">{{customers.name}}</option>
  
                </select>

                  <v-flex xs4 sm3>
  
                    <v-text-field v-model="form.bar_code" color="blue darken-2" label="Barcode" required></v-text-field>
  
                  </v-flex>
  
                  <barcode v-bind:value="form.bar_code"></barcode>  
  
                </v-layout>
  
              </v-container>
  
            </v-form>
          </v-layout>

          <v-layout wrap>
            <div v-for="product in form.products">
              <v-flex xs12 sm12>

                <v-text-field v-model="product.product_name" :rules="rules.name" color="blue darken-2" label="Product Name" required></v-text-field>

              </v-flex>
              
              <v-flex xs12 sm12>

                <v-text-field v-model="product.weight" :rules="rules.name" color="blue darken-2" label="Product Weight" required></v-text-field>

              </v-flex>
              
              <v-flex xs12 sm12>

                <v-text-field v-model="product.quantity"  color="blue darken-2" label="Quantity" required></v-text-field>

              </v-flex>
              
              
              <v-flex xs12 sm12>

                <v-text-field v-model="product.price" color="blue darken-2" label="Price" required></v-text-field>

              </v-flex>
              <v-btn @click="remove(product)" icon class="mx-0">
                <v-icon color="pink darken-2" small>delete</v-icon>
              </v-btn>

            </div>

          </v-layout>

          <v-divider></v-divider>
          <v-flex xs12 sm12>

            <v-text-field v-model="subTotal" color="blue darken-2" label="Price" disabled></v-text-field>

          </v-flex>

          
          <v-btn color="primary" flat @click="add_product">Add product</v-btn>

          </v-card-text>

          <v-card-actions>

            <v-btn flat @click="resetForm">reset</v-btn>

            <v-btn flat @click="close">Close</v-btn>

            <v-spacer></v-spacer>

            <v-btn flat color="primary" @click="save" :loading="loading" :disabled="loading">Add Shipment</v-btn>

          </v-card-actions>
  
        </v-container>
  
        <div v-show="loader" style="text-align: center">
  
          <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>
  
        </div>
  
      </v-card>
  
    </v-dialog>
  
  
  
  
  
  </v-layout>
</template>

<script>
import VueBarcode from "vue-barcode";

export default {
  components: {
    barcode: VueBarcode
  },

  props: ["addShipment", "Allcustomer"],

  data() {
    const defaultForm = Object.freeze({
      client_name: "",

      client_phone: "",

      client_email: "",

      client_address: "",

      client_city: "",

      assign_staff: "",

      airway_bill_no: "",

      shipment_type: "",

      payment: "",

      total_freight: "",

      insuarance_status: "",

      booking_date: null,

      derivery_date: null,

      derivery_time: null,

      bar_code: "",

      getTotal: '',

      products: [
        {
          product_name: '',
          weight: null,
          quantity: 1,
          price: 0,
        }
      ],
    });

    return {
      loading: false,

      notifications: false,

      list: {},

      loader: false,

      dmodal1: false,

      pdialog2: false,

      dmodal2: false,

      tmodal: false,

      sound: true,

      widgets: false,

      form: Object.assign({}, defaultForm),

      emailRules: [
        v => {
          return !!v || "E-mail is required";
        },

        v =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "E-mail must be valid"
      ],

      rules: {
        name: [val => (val || "").length > 0 || "This field is required"]
      },

      items: [
        {
          state: "Yes",

          abbr: "yes"
        },

        {
          state: "No",

          abbr: "no"
        }
      ]
    };
  },

  methods: {
    save() {
      this.loading = true;

      axios
        .post("/shipment", this.$data.form)

        .then(response => {
          this.loading = false;

          // console.log(response);

          this.$emit('alertRequest');
          // this.$emit('closeRequest');
          this.$parent.AllShipments.push(response.data);

          // this.$emit('closeRequest');

          this.resetForm;
        })

        .catch(error => {
          this.loading = false;

          this.errors = error.response.data.errors;
        });
    },

    close() {
      this.$emit("closeRequest");
    },

    resetForm() {
      this.form = Object.assign({}, this.defaultForm);

      this.$refs.form.reset();
    },

    add_product() {
      this.form.products.push({
          product_name: '',
          weight: '',
          quantity: 1,
          price: 0,
        }) 
    },

    remove(product) {
      const index = this.form.products.indexOf(product)
      this.form.products.splice(index, 1)
    }
  },

  computed: {
    
      subTotal: function() {
          return this.form.products.reduce(function(carry, product) {
          return carry + parseFloat(product.price);
          }, 0);
      },
      // vat: function() {
      //     return this.grandTotal * parseFloat(0.16);
      //     // (this.subTotal - parseFloat(this.form.discount)) * parseFloat(0.16);
      // },
      // grandTotal: function() {
      //     return this.subTotal - parseFloat(this.form.discount);
      // },
    formIsValid() {
      return (
        this.form.client_name &&
        this.form.client_phone &&
        this.form.client_email &&
        this.form.client_address &&
        this.form.client_city &&
        this.form.assign_staff &&
        this.form.airway_bill_no &&
        this.form.total_weight &&
        this.form.shipment_type &&
        this.form.payment &&
        this.form.total_freight &&
        this.form.insuarance_status &&
        this.form.booking_date &&
        this.form.derivery_date
      );
    }
  },

  mounted() {},

  beforeRouteEnter(to, from, next) {
    next(vm => {
      if (vm.role === "Admin" || vm.role === "companyAdmin") {
        next();
      } else {
        next("/");
      }
    });
  }
};
</script>

