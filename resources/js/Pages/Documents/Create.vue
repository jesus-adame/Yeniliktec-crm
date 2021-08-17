<template>
    <app-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight container mx-auto">Cotizaciones</h1>
        </template>
        <div class="container-fluid mx-auto my-5">
            <div class="w-full mx-auto container">
                <form method="post" class="bg-white flex flex-wrap shadow-md px-8 pt-6 pb-8 mb-4" @submit="sendForm" enctype="multipart/form-data">
                    <div class="md:w-1/2 md:px-2">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
                                Nombre del documento
                            </label>
                            <input class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="name"
                                name="name"
                                type="text" placeholder="Nombre">
                        </div>
                        <!-- <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="lead_id">
                                Lead Id
                            </label>
                            <input class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none" id="lead_id"
                                name="lead_id"
                                type="text" placeholder="Contact email">
                        </div> -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="lead">
                                Documento
                            </label>
                            <input class="shadow appearance-none border w-full
                                py-2 px-3 text-gray-700 leading-tight focus:outline-none"
                                type="file"
                                name="document" id="document">
                        </div>
                    </div>
                    <div class="flex items-center justify-end w-full">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 focus:outline-none mr-4" type="submit">
                            Registrar
                        </button>
                        <inertia-link :href="route('quotes.index')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 focus:outline-none">
                            Cancelar
                        </inertia-link>
                    </div>
                </form>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
    },

    props: [],

    setup() {

        const sendForm = async (e) => {
            let form = new FormData(e.target);
            
            e.preventDefault();

            try {
                let response = await axios.post(route('documents.store'), form);

                __alert_notification(response.data.message);
                Inertia.visit(route('documents.index'));
                
            } catch (fail) {
                let errors = Object.values(fail.response.data.errors);
                let text = '';

                errors.forEach(error => text += `<p>${error[0]}</p>`);

                Swal.fire({
                    icon: 'error',
                    title: fail.response.data.message,
                    html: text,
                });
            }
        }

        return {
            sendForm,
        };
    }
}
</script>