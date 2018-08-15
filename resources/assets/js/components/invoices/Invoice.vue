<template>
<div>

    <v-content>

        <v-container fluid fill-height>

            <div v-show="loader" style="text-align: center; width: 100%; margin-top: 200px;">

                <v-progress-circular :width="3" indeterminate color="red" style="margin: 1rem"></v-progress-circular>

            </div>

            <v-layout justify-center align-center v-show="!loader">

                <div class="row">

                    <div class="col-md-12">

                        <!-- {{form.start_date}} and {{form.end_date}} -->

                        <v-btn @click="invoiceAdd" flat color="primary">Add Invoice</v-btn>

                        <!-- <v-flex xs12 sm4> -->

                        <div class="row">

                            <div class="col-md-4">

                                <v-text-field v-model="form.start_date" color="blue darken-2" type="date" required></v-text-field>

                            </div>

                            <!-- </v-flex> -->

                            <!-- <v-flex xs12 sm4 offset-sm1> -->

                            <div class="col-md-4">

                                <v-text-field v-model="form.end_date" color="blue darken-2" type="date" required></v-text-field>

                            </div>

                            <!-- </v-flex> -->

                            <!-- <v-flex sm3> -->

                            <div class="col-md-4">

                                <v-btn color="primary" flat @click="sort">Sort</v-btn>

                            </div>

                        </div>

                        <!-- </v-flex> -->
                    </div>
                    <v-card>
                        <v-card-title>
                            Nutrition
                            <v-spacer></v-spacer>
                            <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
                        </v-card-title>
                        <v-data-table :headers="headers" :items="invoices" class="elevation-1" :search="search" :loading="loading">
							<v-progress-linear slot="progress" color="blue" indeterminate></v-progress-linear>
                            <template slot="items" slot-scope="props">
                                <td>{{ props.item.invoice_no }}</td>
                                <td class="text-xs-right">{{ props.item.client }}</td>
                                <td class="text-xs-right">{{ props.item.invoice_date }}</td>
                                <td class="text-xs-right">{{ props.item.due_date }}</td>
                                <td class="text-xs-right">{{ props.item.grand_total }}</td>
                                <td class="justify-center layout px-0">
                                    <v-tooltip bottom>
                                        <v-btn slot="activator" icon class="mx-0" @click="invoiceEdit(props.item)">
                                            <v-icon small color="blue darken-2">edit</v-icon>
                                        </v-btn>
                                        <span>Edit</span>
                                    </v-tooltip>
                                    <v-tooltip top>
                                        <v-btn slot="activator" icon class="mx-0" @click="invoiceShow(props.item)">
                                            <v-icon small color="blue darken-2">visibility</v-icon>
                                        </v-btn>
                                        <span>View</span>
                                    </v-tooltip>
                                    <v-tooltip right>
                                        <v-btn slot="activator" icon class="mx-0" @click="invoiceMail(props.item)">
                                            <v-icon small color="blue darken-2">mail</v-icon>
                                        </v-btn>
                                        <span>Send email</span>
                                    </v-tooltip>
                                </td>
                            </template>
                            <v-alert slot="no-results" :value="true" color="error" icon="warning">
                                Your search for "{{ search }}" found no results.
                            </v-alert>
                        </v-data-table>
                    </v-card>
                    <!-- <v-flex sm12> -->

                </div>

            </v-layout>

        </v-container>

        <v-snackbar :timeout="timeout" bottom :color="color" left v-model="snackbar">

            {{ message }}

            <!-- <v-icon dark right>check_circle</v-icon> -->

            <v-btn>close</v-btn>

        </v-snackbar>

    </v-content>

    <AddInvoice @closeRequest="close" :openAddRequest="dispAdd" @alertRequest="showAlert" :buyers="AllBuyers"></AddInvoice>

    <EditInvoice @closeRequest="close" :openAddRequest="dispEdit" @alertRequest="showAlert" :buyers="AllBuyers" :invoiceData="editinvoice"></EditInvoice>

    <ShowInvoice @closeRequest="close" :openAddRequest="dispShow" @alertRequest="showAlert" :invoice="editinvoice"></ShowInvoice>

    <MailInvoice @closeRequest="close" :openMailRequest="dispMail" @alertRequest="showAlert" :invoice="editinvoice"></MailInvoice>

</div>
</template>

<script>
let AddInvoice = require('./AddInvoice');

let EditInvoice = require('./EditInvoice');

let ShowInvoice = require('./ShowInvoice');

let MailInvoice = require('./EMail');

export default {

    components: {

        AddInvoice,

        EditInvoice,

        ShowInvoice,

        MailInvoice

    },

    data() {

        return {

            dispAdd: false,

            dispEdit: false,

            dispShow: false,

            dispMail: false,

            loader: false,

            snackbar: false,

            timeout: 5000,

            color: '',

            message: '',

            Allusers: [],

            invoices: [],

            AllBuyers: {},

            editinvoice: {},

            form: {

                start_date: '',

                end_date: ''

			},
			loading: false,
            search: '',
            headers: [{
                    text: 'Invoice Number',
                    align: 'left',
                    sortable: false,
                    value: 'invoice_no'
                },
                {
                    text: 'Client Name',
                    value: 'calories'
                },
                {
                    text: 'Invoice Date',
                    value: 'fat'
                },
                {
                    text: '	Due Date',
                    value: 'carbs'
                },
                {
                    text: 'Grand Total',
                    value: 'protein'
                },
                {
                    text: 'Actions',
                    value: 'name',
                    sortable: false
                }
            ],

        }

    },

    methods: {

        sort() {

            this.loading = true

            axios.post('getInvoiceSort', this.form)

                .then((response) => {

                    this.loading = false

                    this.invoices = response.data

                })

                .catch((error) => {

                    this.loading = false

                    this.errors = error.response.data.errors

                })

        },

        invoiceEdit(invoice) {

            // console.log(invoice);

            this.editinvoice = Object.assign({}, invoice)

            this.editedIndex = this.invoices.indexOf(invoice)

            // console.log(this.editedItem);

            this.dispEdit = true

        },

        invoiceAdd() {

            this.dispAdd = true

        },

        /*invoiceEdit(key){		      	this.$children[2].list = this.invoices[key]		this.dispEdit  = true		},*/

        invoiceShow(invoice) {

            this.editinvoice = Object.assign({}, invoice)

            this.editedIndex = this.invoices.indexOf(invoice)

            // console.log(this.editedItem);

            this.dispShow = true

            // this.$children[3].list = this.invoices[key]

        },

        invoiceMail(invoice) {

            this.editinvoice = Object.assign({}, invoice)

            this.editedIndex = this.invoices.indexOf(invoice)

            // console.log(this.editedItem);

            this.dispMail = true

            // this.$children[3].list = this.invoices[key]

        },

        editItem(item) {

            this.editedItem = Object.assign({}, item)

            this.editedIndex = this.Allusers.indexOf(item)

            // console.log(this.editedItem);

            this.pdialog2 = true

        },

        showAlert() {

            this.message = 'Successifully Added';

            this.snackbar = true;

            this.color = 'indigo';

        },

        invoicedel(key, id) {

            if (confirm('Are you sure you want to delete this item?')) {

                this.loader = true

                axios.delete(`/users/${id}`)

                    .then((response) => {

                        this.Allusers.splice(index, 1)

                        this.loader = false

                        this.message = 'deleted successifully'

                        this.color = 'red'

                        this.snackbar = true

                    })

                    .catch((error) => {

                        this.errors = error.response.data.errors

                        this.loader = false

                        this.message = 'something went wrong'

                        this.color = 'red'

                        this.snackbar = true

                    })

            }

        },

        close() {

            this.dispAdd = this.dispShow = this.dispEdit = this.dispMail = false

        },

    },

    computed: {

        Start_dates() {

            return this.form.start_date;

        },

        end_dates() {

            return this.form.end_date;

        }

    },

    mounted() {

        this.loader = true

        axios.get('getUsers')

            .then((response) => {

                this.Allusers = response.data

            })

            .catch((error) => {

                this.errors = error.response.data.errors

            })

        axios.get('getCustomer')

            .then((response) => {

                this.AllBuyers = response.data

            })

            .catch((error) => {

                this.errors = error.response.data.errors

            })

        axios.get('getInvoice')

            .then((response) => {

                this.loader = false

                this.invoices = response.data

            })

            .catch((error) => {

                this.loader = false

                this.errors = error.response.data.errors

            })

    },

}
</script>
