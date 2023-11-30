<template>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">Add New Book Data</h5>
                <div>
                    <router-link :to="{name: 'books'}" class="btn btn-success">Go Back</router-link>
                </div>
            </div>

            <div v-if="strSuccess" class="alert alert-success alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strSuccess}}</strong>
            </div>

            <div v-if="strError" class="alert alert-danger alert-dismissible fade show" role="alert">
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                <strong>{{strError}}</strong>
            </div>


            <form @submit.prevent="addBook" enctype="multipart/form-data">
                <div class="form-group mb-2">
                    <label>Name</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="title" placeholder="Enter Book name">
                </div>

                 <div class="form-group mb-2">
                    <label>Author</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="author" placeholder="Enter Book Author">
                </div>

                    <div class="form-group mb-2">
                    <label>Genre</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="genre" placeholder="Enter Book Genre">
                </div>

                 <div class="form-group mb-2">
                    <label>Isbn</label><span class="text-danger"> *</span>
                    <input type="text" class="form-control" v-model="isbn" placeholder="Enter Book Isbn">
                </div>

                <div class="form-group mb-2">
                    <label>Description</label><span class="text-danger"> *</span>
                   <textarea class="form-control" rows="3" v-model="description" placeholder="Enter book description"></textarea>
                </div>

                <div class="form-gorup mb-2">
                    <label>Image</label><span class="text-danger"></span>
                    <input type="file" class="form-control mb-2" v-on:change="onChange">

                    <div v-if="img">
                        <img v-bind:src="imgPreview" width="100" height="100"/>
                    </div>
                </div>

                 <div class="form-gorup mb-2">
                    <label class="mb-2">Publication Date</label><span class="text-danger"></span>
                     <datepicker
                    v-model="published_at"
                    :locale="locale"
                    :upperLimit="to"
                    :lowerLimit="from"
                    format="yyyy-MM-dd"
                    class="form-group mb-2"
                />

                </div>

                
                <button type="submit" class="btn btn-primary mt-4 mb-4"> Add Book</button>

            </form>
            
        </div>
    </div>
</template>

<script>
import Datepicker from 'vue3-datepicker';
import moment from 'moment';
export default{
    components: {
    Datepicker
  },
    data() {
        return {
            title: '',
            description: '',
            img: '',
            strSuccess: '',
            strError: '',
            imgPreview: null,
            published_at: new Date("2019-09-17")
        }
    },
    methods: {
        onChange(e) {
            this.img = e.target.files[0];
            let reader = new FileReader();
            reader.addEventListener("load", function () {
                this.imgPreview = reader.result;
            }.bind(this), false);

            if (this.img) {
                if ( /\.(jpe?g|png|gif)$/i.test( this.img.name ) ) {
                    reader.readAsDataURL( this.img );
                }
            }
        },
        addBook(e) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;
                const config = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                }

                const formData = new FormData();
                formData.append('title', this.title);
                formData.append('description', this.description);
                formData.append('file', this.img);
                formData.append('author', this.author);
                formData.append('genre', this.genre);
                formData.append('isbn', this.isbn);
                 formData.append('published_at', moment(this.published_at).format('YYYY-MM-DD'));
                this.$axios.post('/api/books/add', formData, config)
                .then(response => {
                    existingObj.strError = "";
                    existingObj.strSuccess = response.data.success;
                })
                .catch(function(error) {
                    existingObj.strSuccess ="";
                    existingObj.strError = error.response.data.message;
                });
            });
        }

    },
    beforeRouteEnter(to, from, next) {
        if (!window.Laravel.isLoggedin) {
            window.location.href = "/";
        }
        next();
    }
}

</script>