<template>
<v-content>

    <v-container fluid fill-height>
        <v-layout justify-center align-center>
            <v-btn flat color="orange" @click="test">Test</v-btn>
            <download-excel :data="AllShipments">
                Download Data
                <img src="/storage/csv.png">
            </download-excel>
        </v-layout>
    </v-container>
</v-content>
</template>

<script>
export default {
    data() {
        return {
            json_fields: {
                 'Order Id': 'order_id',
                 'Sender Name': 'sender_name',
                 'Sender Email': 'sender_email',
                 'Sender Phone': 'sender_phone',
                 'Sender City': 'sender_city',
                 'Sender Address': 'sender_address',
                 'Driver': 'driver',
                 'Client Name': 'client_name',
                 'Client Email': 'client_email',
                 'Client Phone': 'client_phone',
                 'Client City': 'client_city',
                 'Client Address': 'client_address',
                 'Derivery Status': 'derivery_status',
                 'Quantity': 'amount_ordered',
                 'Booking Date': 'booking_date',
            },
            AllShipments: [],
            json_data: [{
                    'client_name': 'Tony Peña',
                    'client_city': 'New York',
                    'client_phone': 'United States',
                    'birthdate': '1978-03-15',
                    'phone': {
                        'mobile': '1-541-754-3010',
                        'landline': '(541) 754-3010'
                    }
                },
                {
                    'name': 'Thessaloniki',
                    'city': 'Athens',
                    'country': 'Greece',
                    'birthdate': '1987-11-23',
                    'phone': {
                        'mobile': '+1 855 275 5071',
                        'landline': '(2741) 2621-244'
                    }
                }
            ],
            json_meta: [
                [{
                    'key': 'charset',
                    'value': 'utf-8'
                }]
            ],
        }
    },
    methods: {
        test() {
            axios.post('carbon')
                .then((response) => {
                    this.tests = response.data
                })
                .catch((error) => {
                    this.errors = error.response.data.errors
                })
        }
    },
    mounted() {
        axios
                .get("/userDateExpo")

                .then(response => {
                    this.loading = false
                    this.AllShipments = response.data;

                    this.loader = false;
                })

                .catch(error => {
                    this.loading = false
                    this.errors = error.response.data.errors;

                    this.loader = false;
                });
      
    }
}
</script>
