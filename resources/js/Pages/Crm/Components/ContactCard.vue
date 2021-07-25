<template>
    <div class="border border-gray-500 p-2">
        <div class="flex justify-between items-center">
            <p class="font-bold">Contacto</p>
        </div>

        <label for="title">Nombre*</label>
        <Input name="name" v-model="form.name"/>

        <label for="title">Apellidos*</label>
        <Input name="last_name" v-model="form.last_name"/>

        <label for="title">Teléfono*</label>
        <Input name="phone_number" v-model="form.phone_number"/>

        <label for="title">E-mail*</label>
        <Input name="email" v-model="form.email"/>

        <label for="title">Descripción</label>
        <textarea class="w-full" name="description" rows="3" v-model="form.description"></textarea>

        <Button @click="updateContact">Actualizar</Button>
    </div>
</template>

<script>
import Input from '@/Jetstream/Input';
import { reactive, toRefs } from '@vue/reactivity';
import Button from '@/Jetstream/Button';
import SecondaryButton from '@/Jetstream/SecondaryButton';
import { onMounted } from '@vue/runtime-core';
import axios from 'axios';

export default {
    components: {
        Input,
        Button,
        SecondaryButton,
    },

    props: ['contact', 'lead'],
    
    setup(props) {
        const {contact, lead} = toRefs(props);
        const form = reactive({
            name: '',
            last_name: '',
            phone_number: '',
            email: '',
            description: '',
        });

        const updateContact = async () => {
            try {
                let response = await axios.post('/leads-contact-create-update/' + lead.value.id, form);
                __alert_notification(response.data.message);

            } catch (fail) {
                __alert_axios_errors(fail.response);
            }
        }

        onMounted(() => {
            form.name = contact.value.name;
            form.last_name = contact.value.last_name;
            form.phone_number = contact.value.phone_number;
            form.email = contact.value.email;
            form.description = contact.value.description;
        });

        return {
            form,
            updateContact,
        }
    }
}
</script>