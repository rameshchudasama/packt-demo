
import {createWebHistory, createRouter} from "vue-router";

import Home from '../pages/Home';
import Login from '../pages/Login';
import Dashboard from '../pages/Dashboard';
import Books from '../components/Books';
import AddBook from '../components/AddBook';
import EditBook from '../components/EditBook';
import DetailBook from '../components/DetailBook';

export const routes = [
    {
        name: 'home',
        path: '/',
        component: Home
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'dashboard',
        path: '/dashboard',
        component: Dashboard
    },
    {
        name: 'books',
        path: '/books',
        component: Books
    },
    {
        name: 'addbook',
        path: '/books/add',
        component: AddBook
    },
    {
        name: 'editbook',
        path: '/books/edit/:id',
        component: EditBook
    },
    {
        name: 'detailbook',
        path: '/books/detail/:id',
        component: DetailBook
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
