<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Correos
            </h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
            <div v-for="message in messages" :key="message.id" @click="openMail(message)">
                <h3 class="font-bold">{{ message.from.mail }}</h3>
                <h4>{{ message.subject }}</h4>
                <hr>
            </div>
        </div>
        <show-mail :showModal="modalMail" :message="fullMessage" @closeModal="closeMail"></show-mail>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import ShowMail from './Modals/ShowMail.vue';

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
        ShowMail,
    },

    props: {
        messages: Array,
    },

    data() {
        return {
            fullMessage: {},
            modalMail: false,
        }
    },

    methods: {
        openMail(message) {
            this.fullMessage = message;
            console.log(message);
            this.modalMail = true;
        },
        closeMail() {
            this.modalMail = false;
        }
    }
}
</script>