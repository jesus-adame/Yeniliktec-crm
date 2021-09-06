<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                CRM (LEADS)
            </h2>
        </template>

        <div class="container-fluid h-full mx-auto px-2 sm:px-6 lg:px-8 mt-4">
            <button class="bg-blue-500 text-white mb-3 btn" @click="openRegisterLead">
                Registrar seguimiento
            </button>
            <inertia-link class="bg-gray-700 hover:bg-gray-800 text-white btn ml-2" href="/quotes">Cotizaciones</inertia-link>
            <inertia-link class="bg-gray-700 hover:bg-gray-800 text-white btn ml-2" href="/documents">Documentos</inertia-link>

            <div class="flex">
                <div v-for="board in boards" :key="board.id" class="w-full mb-4">
                    <h2 class="text-center uppercase py-2 font-bold">{{ board.name }}</h2>
                    <hr><br>
                    <div class="flex justify-between w-full h-full overflow-auto" style="height: 65vh">
                        <div v-for="column in board.columns" :key="column.id"
                            class="uppercase block w-80 p-2 flex-none h-full">
                            <div class="p-3 shadow-md rounded" :style="{ 'background-color': column.bg_color }">
                                <h3 class="text-center" :style="{ color: column.text_color }">{{ column.name }}</h3>
                                <hr><br>

                                <!-- Draggable lead -->
                                <draggable item-key="id" v-model="column.leads" group="leads"
                                    @update="moveLead"
                                    :component-data="{name:'fade'}">
                                    <template #item="{element}">
                                        <lead-card class="mb-2" :lead="element" @click="openLead(element)"></lead-card>
                                    </template>
                                </draggable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
    <register-lead
        :showModal="registerLead"
        :users="users"
        @closeModal="closeRegisterLead"
        @leadRegistered="resfreshLeads"></register-lead>
    <show-lead
        :showModal="showLead"
        :lead="lead"
        :columns="columns"
        @closeModal="closeLead"
        @leadRegistered="resfreshLeads"></show-lead>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout';
import Welcome from '@/Jetstream/Welcome';
import RegisterLead from './Modals/RegisterLead.vue';
import ShowLead from './Modals/ShowLead.vue';
import LeadCard from './Components/LeadCard.vue';
import axios from 'axios';
import Swal from 'sweetalert2';
import draggable from 'vuedraggable';

export default {
    inheritAttrs: false,

    components: {
        AppLayout,
        Welcome,
        ShowLead,
        RegisterLead,
        LeadCard,
        draggable,
    },

    props: {
        leads: Array,
        boards: Array,
        columns: Array,
        users: Array,
    },

    data() {
        return {
            lead: {
                title: '',
                author: {},
                agent: {},
                status: 'pending',
                description: '',
                contact: {},
            },
            showLead: false,
            registerLead: false,
        }
    },

    methods: {
        async openLead(lead) {
            try {
                Swal.showLoading();
                let response = await axios.get('/leads/' + lead.id);
                this.lead = response.data;
                this.showLead = true;
                Swal.close();
                
            } catch (fail) {
                Swal.fire({
                    icon: 'error',
                    title: fail.response.data.message
                });
            }
        },

        moveLead(element) {
            console.log(element);
        },

        closeLead() {
            this.showLead = false;
        },

        openRegisterLead() {
            this.registerLead = true;
        },

        closeRegisterLead() {
            this.registerLead = false;
        },

        resfreshLeads() {
            this.$inertia.reload({ only: ['leads', 'boards'] });
        }
    },
}
</script>
