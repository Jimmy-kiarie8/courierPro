<template>
  <v-layout row justify-center>
      
        <v-dialog v-model="UpdateShipment" persistent>
  
          <v-card v-if="UpdateShipment">
  
            <v-card-title>
  
              Update Shipment
  
            </v-card-title>
  
            <v-container grid-list-md>
  
              <v-layout row wrap>
  
                <v-flex xs8>
  
                  <v-container fill-height>
  
                    <v-layout align-center>
  
                      <v-flex>
  
                        <!-- <GmapMap
  
                    :center="{lat:-1.2808685, lng:36.73657560000004}"
  
                    :zoom="7"
  
                    map-type-id="terrain"
  
                    style="width: 700px; height: 400px"
  
                    >
  
                    <GmapMarker
  
                    :position="markers.position"
  
                    :clickable="true"
  
                    :draggable="true"
  
                    @click="center=markers.position"
  
                    />
  
                  </GmapMap> -->
  
                      </v-flex>
  
                    </v-layout>
  
                  </v-container>
  
                </v-flex>
  
                <v-flex xs4>
  
                  <v-card>
  
                    <!-- <v-flex xs12 sm12>
  
                  <v-text-field
  
                  v-model="updateitedItem.client_city"
  
                  :rules="rules.name"
  
                  color="blue darken-2"
  
                  label="Current City"
  
                  required
  
                  ></v-text-field>
  
                </v-flex> -->
  
                    <!-- <vue-google-autocomplete ref="address" id="map" classname="form-control" placeholder="Please type your address" v-on:placechanged="getAddressData" country="ke"></vue-google-autocomplete> -->
  
                    <!-- <select class="custom-select custom-select-md col-md-12" v-model="updateitedItem.status">
  
                  <option value="waiting_pproval">Awaiting Approval</option>
  
                  <option value="approved">Approved</option>
  
                </select> -->
  
  
  
  
  
                <select class="custom-select custom-select-md col-md-12" v-model="updateitedItem.status">
  
                  <option value="Awaiting Approval">Awaiting Approval</option>
  
                  <option value="Approved">Approved</option>
  
                  <option value="Cancelled">Cancelled</option>
  
                  <option value="Shipment Collected">Shipment Collected</option>
  
                  <option value="Waiting for Scan">Waiting for scan</option>
  
                  <option value="Ready For Depart">Ready For Depart</option>
  
                  <option value="Despatched">Despatched</option>
  
                  <option value="Arrived">Arrived</option>
  
                  <option value="Cleared">Cleared</option>
  
                  <option value="Transit">Transit</option>
  
                  <option value="Out For Destination">Out For Destination</option>
  
                  <option value="Out For Delivery">Out For Delivery</option>
  
                  <option value="Delivered">Delivered</option>
  
                  <option value="Returned">Returned</option>
  
                  <option value="Hold">Hold</option>
  
                </select>
  
                    <v-flex xs12 sm12>
  
                      <v-textarea v-model="updateitedItem.remark" color="blue">
  
                        <div slot="label">
  
                          Remark <small>(optional)</small>
  
                        </div>
  
                      </v-textarea>
  
                    </v-flex>
  
                    <v-btn color="primary" flat @click="UpdateStatus" :loading="loading" :disabled="loading">Update Status</v-btn>
  
                  </v-card>
  
                </v-flex>
  
              </v-layout>
  
  
  
              <v-divider></v-divider>
  
              <v-divider></v-divider>
  
  
  
              <!-- Add Products -->
  
              <!-- <v-jumbotron color="grey lighten-2"> -->
  
                <v-layout align-center>
  
                  <v-flex>
  
                    <h2 class="text-center">Products</h2>
  
  
                    <table class="table table-hove table-striped table-responsive">
  
                      <thead>
  
                        <th>Product Name</th>
  
                        <th>Weight</th>
  
                        <th>Price</th>
  
                        <th>Quantity</th>
  
                        <th>Total</th>
  
                      </thead>
  
                      <tbody>
  
                        <tr v-for="products in updateitedItem.products" :key="products.id">
  
                          <td>{{ products.product_name }}</td>
  
                          <td>{{ products.weight }}</td>
  
                          <td>{{ products.price }}</td>
  
                          <td>{{ products.quantity }}</td>
  
                          <td>{{ products.total }}</td>
  
                        </tr>
  
                      </tbody>

                      <tfoot>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>Sub Total</th>
                        <th>{{ updateitedItem.sub_total }}</th>
                      </tfoot>
  
                    </table>
  
                  </v-flex>
  
                </v-layout>
  
              <!-- </v-jumbotron> -->
  
              <!-- Add Products -->
               <v-card-actions>


                  <v-btn flat @click="close">Close</v-btn>

                </v-card-actions>
        
              </v-container>
  
            <!-- </v-container> -->
  
            <v-divider></v-divider>
  
          </v-card>
          <v-snackbar
            :timeout="timeout"
            top="top"
            :color="color"
            right="right"
            v-model="snackbar"
            >
            {{ message }}
            <v-icon dark right>check_circle</v-icon>
            </v-snackbar>
        </v-dialog>

  </v-layout>
</template>

<script>
// import VueGoogleAutocomplete from "vue-google-autocomplete";
export default {
  components: {
    // VueGoogleAutocomplete,
  },
  props: ['UpdateShipment', 'AllProducts', 'updateitedItem'],
  data () {

    return {
      loading: false,

      snackbar: false,

      timeout: 5000,

      message: "",

      color: "",
    }
  },  

  methods: {
    UpdateStatus() {
      // alert(this.updateitedItem.id);
      this.loading = true
      axios
        .post(`/updateStatus/${this.updateitedItem.id}`, {
          formobg: this.updateitedItem,

          address: this.address
        })

        .then(response => {
          this.loading = false

          this.message = "Updated";

          this.color = "black";

          this.snackbar = true;

          // this.markers.push(response.data);
        })

        .catch(error => {
          this.loading = false;

          this.errors = error.response.data.errors;
        });
    },

    // getAddressData: function(addressData, placeResultData, id) {
    //   this.address = addressData;
    // },

    close() {
      this.$emit("closeRequest");
    },

  },

  mounted() {
    // this.$refs.address.focus();
  }
}
</script>