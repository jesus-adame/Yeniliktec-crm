<template>
    <dialog-modal :show="showModal" @close="closeModal" maxWidth="2xl">
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

        return {
            contact,
            columns,
            form,
            showModal,
            closeModal,
            updateLead,
            deleteLead,
            lead,
        }
    }
}
</script>