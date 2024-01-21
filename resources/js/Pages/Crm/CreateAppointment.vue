<template>
    <app-layout>
        <template #header>
            <h1 class="font-semibold text-xl text-gray-800 leading-tight container mx-auto">Citas</h1>
        </template>
        <div class="container-fluid mx-auto my-5">
            <div class="flex w-full mx-auto container">
                <form class="bg-white flex flex-wrap shadow-md px-8 pt-6 pb-8 mb-4 md:w-1/2" @submit="sendForm">
                    <input type="hidden" name="lead" :value="id">
                    <div class="items-center justify-end w-full">
                        <label for="title">Título</label>
                        <input class="form-control mb-2" type="text" name="title">
                        <label for="title">Descripción</label>
                        <input class="form-control mb-2" type="text" name="description">
                        <label for="title">Inicio</label>
                        <div class="flex space-x-4">
                            <input class="form-control mb-2" type="date" name="start_date">
                            <input class="form-control mb-2" type="time" name="start_time">
                        </div>
                        <label for="title">Final</label>
                        <div class="flex space-x-4">
                            <input class="form-control mb-2" type="date" name="end_date">
                            <input class="form-control mb-2" type="time" name="end_time">
                        </div>
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-4 focus:outline-none mr-4" type="submit">
                            Registrar
                        </button>
                        <inertia-link :href="route('crm.index')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-4 focus:outline-none">
                            Cancelar
                        </inertia-link>
                    </div>
                </form>
                <FullCalendar class="lg:w-1/2 mx-auto p-4" :options="calendarOptions" />
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import axios from 'axios';
import Swal from 'sweetalert2';
import { Inertia } from '@inertiajs/inertia';
import FullCalendar from '@fullcalendar/vue3';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
        FullCalendar,
        dayGridPlugin,
        interactionPlugin,
    },

    props: ['id'],

    data() {
        return {
            calendarOptions: {
                plugins: [ dayGridPlugin, interactionPlugin ],
                initialView: 'dayGridMonth',
                dateClick: this.handleDateClick,
            }
        }
    },

    methods: {
        handleDateClick: function(arg) {
            alert('date click! ' + arg.dateStr)
        }
    },

    setup() {
        const formatNumber = (number) => {
            return numeral(number).format();
        }

        const sendForm = async (e) => {
            let form = new FormData(e.target);
            
            e.preventDefault();

            try {
                let response = await axios.post(route('appointments.store'), form);

                __alert_notification(response.data.message);
                Inertia.visit(route('crm.index'));
                
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
            formatNumber,
        };
    }
}
</script>