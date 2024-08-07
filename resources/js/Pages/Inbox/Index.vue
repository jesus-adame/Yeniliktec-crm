<template>
    <app-layout>
        <template #header>
            <div class="flex justify-between items-center max-w-7xl mx-auto">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Correos
                </h2>
                <select name="account" id="account" v-model="account" @change="freshMessages">
                    <option value="">- Eligir -</option>
                    <option value="ventas">Ventas</option>
                    <option value="contact">Contacto</option>
                </select>
            </div>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 lg:py-8 mt-4 bg-white shadow">
            <div class="flex justify-between">
                <h3 class="text-2xl text-gray-800">Inbox</h3>
                <button class="btn" @click="freshMessages">Refrescar</button>
            </div>
            <table class="table w-full my-4">
                <row-item v-for="message in messages" :key="message.id"
                    class="py-3"
                    :message="message"
                    @click="openMail(message)">
                </row-item>
            </table>

            <button @click="paginate(index + 1)"
                v-for="(link, index) in links" :key="index"
                class="btn inline-block mr-2"
                :class="{ 'bg-gray-100': (currentPage == index + 1) }">
                {{ index + 1 }}
                </button>
        </div>
        <show-mail
            :showModal="modalMail"
            :message="fullMessage"
            :account="account"
            @closeModal="closeMail"></show-mail>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import ShowMail from './Modals/ShowMail.vue';
import RowItem from './Components/RowItem.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
        ShowMail,
        RowItem,
    },

    data() {
        return {
            fullMessage: {},
            modalMail: false,
            date: 50,
            messages: [],
            links: [],
            currentPage: 1,
            account: 'ventas'
        }
    },

    methods: {
        openMail(message) {
            Swal.showLoading();
            axios.get('/imap-messages/' + message.uid + '?account=' + this.account)
            .then(response => {
                this.fullMessage = response.data.message;
                this.modalMail = true;
                Swal.close();
            })
            .then(this.getMessages)
            .catch(fail => {
                Swal.fire(fail.response.data.message);
            })
        },

        closeMail() {
            this.modalMail = false;
        },

        paginate(page) {
            this.currentPage = page;
            this.freshMessages();
        },

        freshMessages() {
            Swal.showLoading();
            return this.getMessages();
        },

        getMessages() {
            axios.get('/imap-messages?page=' + this.currentPage + '&account=' + this.account)
            .then(response => {
                this.links = response.data.links;
                this.messages = response.data.messages;
                this.currentPage = response.data.currentPage;
                Swal.close();
            })
            .catch(fail => {
                console.log(fail.response);
                Swal.close();
            })
        },
    },

    created() {
        this.freshMessages();
    }
}
</script>