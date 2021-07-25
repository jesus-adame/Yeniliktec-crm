<template>
    <dialog-modal :show="showModal" @close="closeModal">
        <template v-slot:title>
            <h2>Registrar lead</h2>
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
                    <label for="title">Descripción</label>
                    <textarea class="w-full" name="description" rows="3" v-model="form.description"></textarea>
                </div>

                <div class="p-2">
                    {{ form.contact }}

                    <div class="flex justify-between items-center">
                        <p class="font-bold">Contacto</p>
                    </div>

                    <label for="title">Nombre*</label>
                    <Input name="name" v-model="form.contact_name"/>

                    <label for="title">Apellidos*</label>
                    <Input name="last_name" v-model="form.contact_last_name"/>

                    <label for="title">Teléfono*</label>
                    <Input name="phone_number" v-model="form.contact_phone_number"/>

                    <label for="title">E-mail*</label>
                    <Input name="email" v-model="form.contact_email"/>

                    <label for="title">Descripción</label>
                    <textarea class="w-full" name="description" rows="3" v-model="form.contact_description"></textarea>
                </div>
            </div>
        </template>

        <template v-slot:footer>
            <Button class="mr-2" @click="sendForm">Registrar</Button>
            <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
        </template>
    </dialog-modal>
</template>

<script>
import DialogModal from '@/Jetstream/DialogModal.vue';
import Input from '@/Jetstream/Input';
import Button from '@/Jetstream/Button';
import SecondaryButton from '@/Jetstream/SecondaryButton';
import Swal from 'sweetalert2';
import { reactive, toRefs } from '@vue/reactivity';
import { usePage } from '@inertiajs/inertia-vue3';
import { computed, onUpdated } from '@vue/runtime-core';
import axios from 'axios';
import ContactCard from './../Components/ContactCard.vue';

export default {
    props: [
        'showModal'
    ],

    emits: [
        'closeModal',
        'leadRegistered',
    ],

    components: {
        DialogModal,
        Input,
        SecondaryButton,
        Button,
        ContactCard,
    },

    setup(props, { emit }) {
        const { showModal } = toRefs(props);
        const auth = computed(() => usePage().props.value.user);
        const lead = reactive({});
        const form = reactive({
            title: '',
            author: auth.value.email,
            author: '',
            status: 'pending',
            description: '',
            contact_name: '',
            contact_last_name: '',
            contact_phone_number: '',
            contact_email: '',
            contact_description: '',
        });

        const sendForm = async () => {
            try {
                Swal.showLoading();
                
                let response = await axios.post('/leads', form);

                emit('leadRegistered');
                successAlert(response.data.message);
                closeModal();
                
            } catch (fail) {
                let text = '';
                let message = 'Ocurrió un error';

                if (fail.response.status == 422) {
                    let errors = Object.values(fail.response.data.errors);

                    message = fail.response.data.message;
                    errors.forEach(error => text += `<p>${error[0]}</p>`);
                }

                Swal.fire({
                    icon: 'error',
                    title: message,
                    html: text,
                })
            }
        }

        const closeModal = () => {
            emit('closeModal');
            resetForm();
        }

        const successAlert = (message) => {
            return Swal.fire({
                icon: 'success',
                title: message,
                toast: true,
                position: 'top-right',
                showConfirmButton: false,
                timer: 2200,
            })
        }

        const resetForm = () => {
            form.title = '';
            form.author = auth.value.email;
            form.agent = '';
            form.status = 'pending';
            form.description = '';
            form.contact_name = '';
            form.contact_last_name = '';
            form.contact_phone_number = '';
            form.contact_email = '';
            form.contact_description = '';
        }

        onUpdated(() => {
            resetForm();
        })

        return {
            form,
            lead,
            showModal,
            closeModal,
            sendForm,
            auth,
        }
    }
}
</script>