<template>
    <dialog-modal :show="showModal" @close="closeModal" maxWidth="2xl">
        <template v-slot:title>
            <h3 class="font-bold">{{ message.from.mail }}</h3>
            <h4>{{ message.subject }}</h4>
        </template>
        <template v-slot:content>
            <div v-html="message.body"></div>
        </template>
        <template v-slot:footer>
            <SecondaryButton @click="closeModal">Cerrar</SecondaryButton>
        </template>
    </dialog-modal>
</template>

<script>
import DialogModal from '@/Jetstream/DialogModal.vue';
import SecondaryButton from '@/Jetstream/SecondaryButton';

export default {
    props: ['message', 'showModal'],

    emits: ['closeModal'],

    components: {
        DialogModal,
        SecondaryButton,
    },

    methods: {
        closeModal() {
            this.$emit('closeModal');
        }
    }
}
</script>