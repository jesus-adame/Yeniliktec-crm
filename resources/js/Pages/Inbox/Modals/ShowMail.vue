<template>
    <dialog-modal :show="showModal" @close="closeModal" maxWidth="4xl">
        <template v-slot:title>
            <h3 class="font-bold">{{ message.subject }}</h3>
            <h4>Contacto: {{ message.from.full }}</h4>
            <div class="text-gray-500 text-sm">
                <span class="mr-2">{{ dateFormat(message.date) }}</span>
                <span class="">{{ timeFormat(message.date) }}</span>
            </div>
        </template>
        <template v-slot:content>
            <div class="py-4" v-html="message.body"></div>
            <div v-if="message.attachments.length">
                <h4 class="font-bold py-2">Adjuntos</h4>
                <div class="flex">
                    <div v-for="attachment in message.attachments" :key="attachment.id"
                        class="file p-2 border border-gray-200 shadow">
                        <a class="block mb-2" :href="'/storage' + attachment.img_src" target="_blank">{{ attachment.name }}</a>
                        <div class="flex space-x-2">
                            <a class="block btn w-full" :href="'/storage' + attachment.img_src" target="_blank">Ver</a>
                            <a class="block btn bg-blue-400 text-white w-full" :href="'/storage' + attachment.img_src" target="_blank" download="download">Descargar</a>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:footer>
            <button class="btn bg-blue-400 text-white mr-2" title="Crear lead de este correo"
                @click="createLead">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="id-badge"
                        class="svg-inline--fa fa-id-badge w-2 mr-1" role="img" viewBox="0 0 384 512">
                        <path fill="currentColor" d="M336 0H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V48c0-26.5-21.5-48-48-48zM144 32h96c8.8 0 16 7.2 16 16s-7.2 16-16 16h-96c-8.8 0-16-7.2-16-16s7.2-16 16-16zm48 128c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zm112 236.8c0 10.6-10 19.2-22.4 19.2H102.4C90 416 80 407.4 80 396.8v-19.2c0-31.8 30.1-57.6 67.2-57.6h5c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h5c37.1 0 67.2 25.8 67.2 57.6v19.2z"/>
                    </svg>
                    Crear lead
                </div>
            </button>
            <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
        </template>
    </dialog-modal>
</template>

<script>
import DialogModal from '@/Jetstream/DialogModal.vue';
import SecondaryButton from '@/Jetstream/SecondaryButton';
import axios from 'axios';
import Swal from 'sweetalert2';

export default {
    props: ['message', 'showModal', 'account'],

    emits: ['closeModal'],

    components: {
        DialogModal,
        SecondaryButton,
    },

    methods: {
        closeModal() {
            this.$emit('closeModal');
        },
        
        dateFormat(date) {
            return moment(date).format('DD-MM-YYYY')
        },

        timeFormat(date) {
            return moment(date).format('h:mm a')
        },

        createLead() {
            Swal.showLoading();
            axios.post('/email-leads/' + this.message.uid, {
                account: this.account,
            })
            .then(response => {
                __alert_notification(response.data.message);
                this.$inertia.visit('/');
            })
            .catch(fail => {
                console.log(fail);
                Swal.fire(fail.response.data.message);
            })
        }
    }
}
</script>