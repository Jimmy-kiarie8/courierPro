<template>
<v-layout row justify-center>

    <v-dialog v-model="UpdateShipmentStatus" persistent width="500px">

        <v-card v-if="UpdateShipmentStatus">

            <v-card-title>

                Update Shipment Status

            </v-card-title>

            <v-container grid-list-md>

                <v-layout row wrap>

                    <v-flex xs12>

                        <v-card>

                            <select class="custom-select custom-select-md col-md-12" v-model="form.status" style="font-size: 15px;">
  
                              <option value="scheduled">scheduled</option>
              
                              <option value="Not Peaking">Not Peaking</option>
              
                              <option value="Cancled">Cancled</option>
              
                              <option value="Awaiting Confirmation">Awaiting Confirmation</option>
              
                            </select>

                            <div v-if="form.status === 'scheduled'">
                                <v-flex xs12 sm12>

                                    <v-flex xs4 sm3>

                                        <v-text-field v-model="form.scheduled_date" color="blue darken-2" label="Schedule Date" type="date"></v-text-field>

                                    </v-flex>

                                </v-flex>

                            </div>

                            <v-flex xs12 sm12>

                                <v-textarea v-model="form.remark" color="blue">

                                    <div slot="label">

                                        Remark <small>(optional)</small>

                                    </div>

                                </v-textarea>

                            </v-flex>

                            <v-btn color="primary" flat @click="UpdateShipment" :loading="loading" :disabled="loading">Update Status</v-btn>

                        </v-card>

                    </v-flex>

                </v-layout>

                <v-divider></v-divider>

                <v-divider></v-divider>

                <!-- Add Products -->

                <!-- <v-jumbotron color="grey lighten-2"> -->

                <!-- </v-jumbotron> -->

                <!-- Add Products -->
                <v-card-actions>

                    <v-btn flat @click="close">Close</v-btn>

                </v-card-actions>

            </v-container>

            <v-divider></v-divider>

        </v-card>
    </v-dialog>

</v-layout>
</template>

<script>
export default {
    props: ['UpdateShipmentStatus', 'updateitedItem', 'selectedItems'],
    data() {

        return {
            loading: false,

            snackbar: false,

            timeout: 5000,

            message: "",

            color: "",

            form: {
              scheduled_date: '',
            },
        }
    },

    methods: {
        UpdateShipment() {
            // alert(this.updateitedItem.id);
            this.loading = true
            axios
                .patch(`/UpdateShipment`, {
                    selected: this.selectedItems,
                    form: this.form
                })

                .then(response => {
                    this.loading = false
                    this.$emit("alertRequest");
                    //   this.$emit("closeRequest");
                })

                .catch(error => {
                    this.loading = false;

                    this.errors = error.response.data.errors;
                });
        },

        close() {
            this.$emit("closeRequest");
        },

    },

    mounted() {}
}
</script>
