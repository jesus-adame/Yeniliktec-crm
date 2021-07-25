<template>
    <input class="border my-1 p-1 border-gray-500 focus:ring focus:ring-indigo-200 shadow-sm w-full" :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" ref="input">
</template>

<script>
    export default {
        props: ['modelValue'],

        emits: ['update:modelValue'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>

