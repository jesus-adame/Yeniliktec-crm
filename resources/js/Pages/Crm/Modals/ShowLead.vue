<template>
    <dialog-modal :show="showModal" @close="closeModal" maxWidth="3xl">
        <template v-slot:title>
            <h2>{{ lead.title }}</h2>
        </template>

        <template v-slot:content>
            <div class="flex">
                <div class="p-2">
                    <label for="title">Título*</label>
                    <Input v-model="form.title" name="title"/>

                    <label for="title">Autor email*</label>
                    <Input v-model="form.author" name="author"/>

                    <label for="title">Agente email*</label>
                    <Input v-model="form.agent" name="agent"/>

                    <label for="title">Estatus*</label>
                    <select class="border my-1 p-1 border-gray-500 focus:ring focus:ring-indigo-200 shadow-sm w-full" name="status" v-model="form.status">
                        <option value="">- Elegir -</option>
                        <option value="pending">Pendiente</option>
                        <option value="in-process">En proceso</option>
                        <option value="finished">Terminado</option>
                    </select>

                    <label for="title">Fase*</label>
                    <select class="border my-1 p-1 border-gray-500 focus:ring focus:ring-indigo-200 shadow-sm w-full"
                        name="status"
                        v-model="form.column_id">
                        <option value="">- Elegir -</option>
                        <option
                            v-for="column in columns" :key="column.id"
                            :value="column.id">
                            {{ column.name }}
                        </option>
                    </select>

                    <label for="title">Descripción</label>
                    <textarea class="w-full" name="description" rows="3" v-model="form.description"></textarea>
                </div>

                <div class="p-2">
                    <contact-card :contact="form.contact" :lead="lead"></contact-card>
                </div>
            </div>
            <h3 class="font-bold">Cotizaciones</h3>
            <inertia-link class="p-1 px-3 rounded mb-4 inline-block text-white bg-green-400" :href="route('quotes.create')">Registrar</inertia-link>
            <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class=" py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class=" shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col"
                                            class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Contacto
                                        </th>
                                        <th scope="col"
                                            class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Descripción
                                        </th>
                                        <th
                                            scope="col"
                                            class=" px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Valor
                                        </th>
                                        <th
                                            scope="col"
                                            class="relative px-6 py-3">
                                            <span class="sr-only">Editar</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="quote in lead.quotes" :key="quote.id">
                                        <td class=" px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="font-bold text-sm text-gray-900">
                                                        <a :href="route('print.quote', { quote: quote.id })" target="_blank">{{ quote.contact.name }}</a>
                                                    </div>
                                                    <div
                                                        class="text-sm text-gray-500">
                                                        {{ quote.contact.email }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class=" px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <table>
                                                    <tr v-for="item in quote.items" :key="item.id">
                                                        <td>
                                                            <span class="px-2 text-green-800 bg-green-100 rounded-full">
                                                                {{ item.quantity }}
                                                                {{ item.product.unity }}
                                                            </span>
                                                            * {{ item.product.sku }} {{ item.product.name }}
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </td>
                                        <td class=" px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            $ {{ formatNumber(quote.total) }}
                                        </td>
                                        <td class=" px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a class="rounded text-white mx-1 bg-gray-500 py-1 px-2 hover:text-indigo-900" :href="route('print.quote', { quote: quote.id })" target="_blank">
                                                Imprimir
                                            </a>
                                            <inertia-link :href="route('quotes.edit', { id: quote.id })"
                                                class="rounded text-white mx-1 bg-yellow-400 py-1 px-2 hover:text-indigo-900">
                                                Editar
                                            </inertia-link>
                                            <button class="rounded text-white mx-1 bg-red-400 py-1 px-2 hover:text-indigo-900"
                                                @click="destroy(quote.id)">Eliminar</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template v-slot:footer>
            <Button class="mr-2" @click="updateLead(lead.id)">Actualizar</Button>
            <DangerButton class="mr-2" @click="deleteLead(lead.id)">Eliminar</DangerButton>
            <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
        </template>
    </dialog-modal>
</template>

<script>
import DialogModal from '@/Jetstream/DialogModal.vue';
import Input from '@/Jetstream/Input';
import Button from '@/Jetstream/Button';
import SecondaryButton from '@/Jetstream/SecondaryButton';
import DangerButton from '@/Jetstream/DangerButton';
import Swal from 'sweetalert2';
import { reactive, toRefs } from '@vue/reactivity';
import axios from 'axios';
import ContactCard from './../Components/ContactCard.vue';
import { onUpdated } from '@vue/runtime-core';

export default {
    props: [
        'showModal',
        'lead',
        'columns',
    ],

    emits: [
        'closeModal',
        'leadRegistered',
    ],

    components: {
        DialogModal,
        Input,
        DangerButton,
        SecondaryButton,
        Button,
        ContactCard,
    },

    setup(props, { emit }) {
        const { showModal, lead, columns } = toRefs(props);
        const contact = reactive({
            name: '',
            last_name: '',
            email: '',
            phone_number: '',
            description: '',
        });
        const form = reactive({
            title: '',
            author: '',
            agent: '',
            status: 'pending',
            description: '',
            contact: {},
        });

        const updateLead = async (leadId) => {
            try {
                Swal.showLoading();
                
                let response = await axios.put('/leads/' + leadId, form);

                emit('leadRegistered');
                __alert_notification(response.data.message);
                closeModal();
                
            } catch (fail) {
                __alert_axios_notification(fail.response);
            }
        }

        const deleteLead = async (leadId) => {
            try {
                Swal.showLoading();
                
                let response = await axios.delete('/leads/' + leadId, form);

                emit('leadRegistered');
                __alert_notification(response.data.message);
                closeModal();
                
            } catch (fail) {
                Swal.fire({
                    icon: 'error',
                    title: fail.response.data.message,
                })
            }
        }

        const closeModal = () => {
            emit('closeModal');
        }

        const resetForm = () => {
            form.title = lead.value.title;
            form.author = lead.value.author.email;
            form.agent = lead.value.agent.email;
            form.status = lead.value.status;
            form.column_id = lead.value.column_id;
            form.description = lead.value.description;
            form.contact.name = lead.value.contact.name;
            form.contact.last_name = lead.value.contact.last_name;
            form.contact.email = lead.value.contact.email;
            form.contact.phone_number = lead.value.contact.phone_number;
            form.contact.description = lead.value.contact.description;
        }

        onUpdated(() => {
            resetForm();
        })

        const formatNumber = (number) => {
            return numeral(number).format();
        }

        return {
            contact,
            columns,
            form,
            showModal,
            closeModal,
            updateLead,
            deleteLead,
            lead,
            formatNumber,
        }
    }
}
</script>