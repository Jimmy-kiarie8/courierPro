<template>
  <v-layout row justify-center>
    
          <v-dialog v-model="EditShipment" persistent>
  
            <v-card>
  
              <v-card-title>
  
                    Edit Shipment
        
              </v-card-title>
  
              <v-container grid-list-md>
  
                <v-layout wrap>
  
                  <v-form ref="form" @submit.prevent="submit">
  
                    <v-container grid-list-xl fluid>
  
                      <v-layout wrap>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.client_name" :rules="rules.name" color="blue darken-2" label="Client name" required></v-text-field>
  
                        </v-flex>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.client_email" :rules="emailRules" color="blue darken-2" label="Client Email" required></v-text-field>
  
                        </v-flex>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.client_address" :rules="rules.name" color="blue darken-2" label="Client Address" required></v-text-field>
  
                        </v-flex>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.client_city" :rules="rules.name" color="blue darken-2" label="Client City" required></v-text-field>
  
                        </v-flex>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.client_phone" color="blue darken-2" label="Client Phone" required></v-text-field>
  
                        </v-flex>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.assign_staff" :rules="rules.name" color="blue darken-2" label="Assigned Staff" required></v-text-field>
  
                        </v-flex>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.airway_bill_no" :rules="rules.name" color="blue darken-2" label="Zip Code" required></v-text-field>
  
                        </v-flex>
  
                        <v-divider></v-divider>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.shipment_type" :rules="rules.name" color="blue darken-2" label="Shipment Type" required></v-text-field>
  
                        </v-flex>
  
  
  
                        <v-spacer></v-spacer>
  
                        <!-- date picker -->
  
                        <v-flex xs6 sm3 md3>
  
                          <v-dialog ref="dialog1" v-model="dmodal1" :return-value.sync="editedItems.booking_date" persistent lazy full-width width="290px">
  
                            <v-text-field slot="activator" v-model="editedItems.booking_date" label="Booking Date" prepend-icon="event" readonly></v-text-field>
  
                            <v-date-picker v-model="editedItems.booking_date" scrollable>
  
                              <v-spacer></v-spacer>
  
                              <v-btn flat color="primary" @click="dmodal1 = false">Cancel</v-btn>
  
                              <v-btn flat color="primary" @click="$refs.dialog1.save(editedItems.booking_date)">OK</v-btn>
  
                            </v-date-picker>
  
                          </v-dialog>
  
                        </v-flex>
  
  
  
                        <v-flex xs6 sm3 md3>
  
                          <v-dialog ref="dialog2" v-model="dmodal2" :return-value.sync="editedItems.derivery_date" persistent lazy full-width width="290px">
  
                            <v-text-field slot="activator" v-model="editedItems.derivery_date" label="Derivery Date" prepend-icon="event" readonly></v-text-field>
  
                            <v-date-picker v-model="editedItems.derivery_date" scrollable>
  
                              <v-spacer></v-spacer>
  
                              <v-btn flat color="primary" @click="dmodal2 = false">Cancel</v-btn>
  
                              <v-btn flat color="primary" @click="$refs.dialog2.save(editedItems.derivery_date)">OK</v-btn>
  
                            </v-date-picker>
  
                          </v-dialog>
  
                        </v-flex>
  
                        <!-- date picker -->
  
  
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.derivery_time" :rules="rules.name" :type="'time'" color="blue darken-2" label="Derivery Time" required></v-text-field>
  
                        </v-flex>
  
                        <v-divider></v-divider>
  
                        <div class="form-group row">
  
                          <label for="password" class="col-md-4 col-form-label text-md-right">Insuarance</label>
  
                          <div class="col-md-6">
  
                            <select class="custom-select col-md-12" v-model="editedItems.insuarance_status">
  
                              <option value="yes">Yes</option>
          
                              <option value="no">No</option>
          
                            </select>
  
                          </div>
  
                        </div>
  
                        <div class="form-group row">
  
                          <label for="password" class="col-md-4 col-form-label text-md-right">Paid</label>
  
                          <div class="col-md-6">
  
                            <select class="custom-select custom-select-md col-md-12" v-model="editedItems.payment">
  
                              <option value="yes">Yes</option>
          
                              <option value="no">No</option>
          
                            </select>
  
                          </div>
  
                        </div>
  
  
  
                        <div class="form-group row">
  
                          <label for="password" class="col-md-4 col-form-label text-md-right">Customer</label>
  
                          <div class="col-md-6">
  
                            <select class="custom-select custom-select-md col-md-12" v-model="form.customer_id">
  
                              <option v-for="customer in customers" :key="customer.id" :value="customer.id">{{customer.name}}</option>
          
                            </select>
  
                          </div>
  
                        </div>
  
                        <v-flex xs4 sm3>
  
                          <v-text-field v-model="editedItems.bar_code" :rules="rules.name" color="blue darken-2" label="Barcode" required></v-text-field>
  
                        </v-flex>
  
                        <barcode v-bind:value="editedItems.bar_code">
  
                          Show this if the rendering fails.
  
                        </barcode>
  
  
  
                      </v-layout>
  
                    </v-container>
  
                    <v-card-actions>
  
                      <!-- <v-btn flat @click="resetForm">reset</v-btn> -->
  
                      <v-btn flat @click="close">Close</v-btn>
  
                      <v-spacer></v-spacer>
  
                      <v-btn flat color="primary" @click="update" :loading="loading" :disabled="loading">Add Shipment</v-btn>
  
                    </v-card-actions>
  
                  </v-form>
  
                </v-layout>
  
              </v-container>
  
              <v-divider></v-divider>
  
            </v-card>
  
          </v-dialog>
  
          <!-- Shipment Edit -->
 
  </v-layout>
</template>

<script>
import VueBarcode from "vue-barcode";
  export default {
    props: ['EditShipment', 'customers', 'editedItems'],

    components: {
      barcode: VueBarcode,
    },
  
    data() {
      return {
  
        notifications: false,
  
        dmodal1: false,

        loading: false,
  
        pdialog2: false,
  
        dmodal2: false,
  
        tmodal: false,
  
        sound: true,
  
        list: {},
  
        form: {},
  
        emailRules: [
  
          v => {
  
            return !!v || 'E-mail is required'
  
          },
  
          v => /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
  
        ],
  
        rules: {
  
          name: [val => (val || '').length > 0 || 'This field is required']
  
        },
  
        items: [{
  
            state: 'Yes',
  
            abbr: 'yes'
  
          },
  
          {
  
            state: 'No',
  
            abbr: 'no'
  
          },
  
        ],
  
      }
  
    },
  
    methods: {  
    update() {
      this.loading = true
      axios.patch(`/shipment/${this.editedItems.id}`, this.editedItems)

        .then(response => {

          this.loading = false

          this.$emit('alertRequest');

          Object.assign(this.$parent.AllShipments[this.$parent.editedIndex], this.$parent.editedItem)

          this.$emit('closeRequest');
        })

        .catch(error => {
          this.loading = false;

          this.errors = error.response.data.errors;
        });
    },
      close() {
  
        this.$emit('closeRequest');
  
      },
  
    },
  
  
  
    computed: {
  
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
  
        )
  
      },
  
    },
  
  }
</script>