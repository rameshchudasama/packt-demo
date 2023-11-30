<template>
<section style="background-color: #eee;">
    <div class="container py-5">
        
        <div class="d-flex justify-content-between pb-2 mb-2">
            <h5 class="card-title">Detail Book Data</h5>
            <div>
                <router-link :to="{name: 'books'}" class="btn btn-success">Go Back</router-link>
            </div>
        </div>
  
      <div class="row">

        <div class="col-lg-8">
          <div class="card mb-4">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <p class="mb-0">Book Name</p>
                </div>
                <div class="col-sm-9">
                  <p class="text-muted mb-0">{{title}}</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <p class="mb-0">Author</p>
                </div>
                <div class="col-sm-9">
                  <p class="text-muted mb-0">{{author}}</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <p class="mb-0">Genre</p>
                </div>
                <div class="col-sm-9">
                  <p class="text-muted mb-0">{{genre}}</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <p class="mb-0">Isbn</p>
                </div>
                <div class="col-sm-9">
                  <p class="text-muted mb-0">{{isbn}}</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <p class="mb-0">Published At</p>
                </div>
                <div class="col-sm-9">
                  <p class="text-muted mb-0">{{published_at}}</p>
                </div>
              </div>
              <hr>
              <div class="row">
                <div class="col-sm-3">
                  <p class="mb-0">Description</p>
                </div>
                <div class="col-sm-9">
                  <p class="text-muted mb-0">{{description}}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</template>

<script>
    export default{
        data() {
            return {
                id:'',
                title: '',
                author: '',
                isbn: '',
                genre: '',
                description: '',
                img: '',
                strSuccess: '',
                strError: '',
                published_at:'',
                imgPreview: null
            }
        },
    
        created() {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                this.$axios.get(`/api/books/edit/${this.$route.params.id}`)
                .then(response => {
                    this.title = response.data['title'];
                    this.description = response.data['description'];
                    this.author = response.data['author'];
                    this.genre = response.data['genre'];
                    this.isbn = response.data['isbn'];
                    this.published_at =  response.data['published_at'];
                    if(response.data['image']){
                         this.img = "/img/"+response.data['image'];
                         this.imgPreview = this.img;
                    }
                 
                })
                .catch(function(error) {
                    console.log(error);
                });
            })
        },
        beforeRouteEnter(to, from, next) {
            if (!window.Laravel.isLoggedin) {
                window.location.href = "/";
            }
            next();
        }
    }
    
    </script>