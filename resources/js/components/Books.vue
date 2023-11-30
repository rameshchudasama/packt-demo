<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between pb-2 mb-2">
                <h5 class="card-title">All Books Data</h5>
                <div v-if="userRoleId == '1'">
                    <button class="btn btn-success" type="button" @click="this.$router.push('/books/add')">New Book</button>
                </div>
            </div>
            <div class="input-group">
                <div class="form-outline mb-4">
                    <input type="search" id="form1" name="search" v-model="search" class="form-control"
                        v-on:change="searchBook" />
                </div>

            </div>
            <table class="table table-hover table-sm">
                <thead class="bg-dark text-light">
                    <tr>
                        <th width="50" class="text-center">ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Genre</th>
                        <th>ISBN</th>
                        <th>Published At</th>
                        <th class="text-center" width="200" v-if="userRoleId == '1'">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(book, index) in books" :key="book.id">
                        <td class="text-center">{{ book.id }}.</td>
                        <td> <router-link :to="{ name: 'detailbook', params: { id: book.id } }"
                                target="_blank">{{ book.title }}</router-link></td>
                        <td>{{ book.author }}</td>
                        <td>{{ book.genre }}</td>
                        <td>{{ book.isbn }}</td>
                        <td>{{ book.published_at }}</td>
                        <td class="text-center" v-if="userRoleId == '1'">
                            <router-link :to="{ name: 'editbook', params: { id: book.id } }"
                                class="btn btn-warning">Edit</router-link>
                            <button class="btn btn-danger" @click="deleteBook(book.id)">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <ul class="pagination">
                <li v-bind:class="(item.active) ? 'active' : ''" class="page-item" v-for="item in paginationLinks">
                    <a v-html="item.label" href="javascript:void(0);" class="page-link" @click="getResults(item.label)">
                    </a>
                </li>
            </ul>


        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            books: [],
            strSuccess: '',
            strError: '',
            paginationLinks: '',
            previousPage: '',
            nextPage: '',
            search: '',
            userRoleId: '',
        }
    },
    created() {
        this.getResults();
    },
    methods: {

        searchBook() {
            this.getResults();
        },
        getResults(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            else {
                if (page == "&laquo; Previous") {
                    if (this.previousPage < 0) {
                        page = 1;
                    }
                    page = this.previousPage;
                }
                else if (page == "Next &raquo;") {
                    page = this.nextPage;
                }
            }


            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let requestApi = '/api/books?page=' + page + '&search=' + this.search;
                this.$axios.get(requestApi)
                    .then(response => {

                        this.books = response.data.data;
                        this.userRoleId = response.data.userInfo.role_id;
                        console.log("role info ", this.userRoleId);
                        this.paginationLinks = response.data.links;
                        if (response.data.current_page != 1) {
                            this.previousPage = response.data.current_page - 1;
                        }

                        this.nextPage = response.data.current_page + 1;
                        if (this.nextPage > response.data.last_page) {
                            this.nextPage = response.data.last_page;
                        }


                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            });
        },
        deleteBook(id) {
            this.$axios.get('/sanctum/csrf-cookie').then(response => {
                let existingObj = this;

                if (confirm("Do you really want to delete this data?")) {
                    this.$axios.delete(`/api/books/delete/${id}`)
                        .then(response => {
                            let i = this.books.map(item => item.id).indexOf(id); // find index of your object
                            this.books.splice(i, 1);
                            existingObj.strError = "";
                            existingObj.strSuccess = response.data.success;

                        })
                        .catch(function (error) {
                            existingObj.strError = "";
                            existingObj.strSuccess = error.response.data.message;
                        });
                }
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