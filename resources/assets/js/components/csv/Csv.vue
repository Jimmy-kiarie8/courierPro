<template>
<v-content>
    <v-container fluid fill-height>
        <v-card>
            <!-- <v-icon class="text-center" color="indigo" style="font-size:100px; text-align:center;">account_circle</v-icon> -->
            <v-btn color="red" darken-1 raised @click="onPickFile" style="color: #fff;">Upload</v-btn>
            <!-- <input type="file" @change="Getimage" accept="image/*"> -->
            <input type="file" @change="Getimage" style="display: none" ref="fileInput">
            <!-- <img v-show="imagePlaced" :src="avatar" style="width: 300px; height: 240px;"> -->
            <v-btn @click="upload" flat :loading="loading" :disabled="loading">Upload</v-btn>
            <v-btn @click="cancle" flat>Cancle</v-btn>
        </v-card>
    </v-container>
    <v-snackbar :timeout="timeout" :bottom="y === 'bottom'" :color="color" :left="x === 'left'" v-model="snackbar">
        {{ message }}
        <v-icon dark right>check_circle</v-icon>
    </v-snackbar>
</v-content>
</template>

<script>
export default {
    data() {
        return{
            loading: false,
            imagePlaced: false,

            timeout: 5000,

            color: 'black',

            snackbar: false,

            message: 'Success',

            y: 'bottom',

            x: 'left',
        }
    },
    methods: {

        onPickFile() {
            this.$refs.fileInput.click()
        },
        onFilePicked(event) {
            this.imagePlaced = true
            const files = event.target.files
            let filename = files[0].name
            if (filename.lastIndexOf('.') <= 0) {
                return alert('please upload a valid image')
            }
            const fileReader = new FileReader()
            fileReader.addEventListener('load', () => {
                this.avatar = fileReader.result
            })
            fileReader.readAsDataURL(files[0])
            this.image = files[0]
        },
        Getimage(e) {
            let image = e.target.files[0];
            // this.read(image);
            let reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = e => {
                this.avatar = e.target.result
            }
            this.imagePlaced = true
            let form = new FormData();
            form.append('image', image);
            this.file = form
            console.log(e.target.files);
        },

        upload() {
            this.loading = true
            axios.post('/csv', this.file)
                .then((response) => {
                    this.loading = false
                    console.log(response);
                    this.imagePlaced = false;
                    this.color = 'black';
                    this.text = 'Profile image updated';
                    this.snackbar = true;
                    // this.close()
                })
                .catch((error) => {
                    this.loading = false
                    this.errors = error.response.data.errors
                })
        },
        cancle() {
            this.avatar = this.LogedUser.profile;
            this.imagePlaced = false;
        },
    }
}
</script>
