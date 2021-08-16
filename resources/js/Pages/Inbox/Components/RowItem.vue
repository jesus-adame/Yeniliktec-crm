<template>
    <div class="cursor-pointer">
        <h3 class="font-bold">{{ message.from.mail }}</h3>
        <h4>{{ message.subject }}</h4>
        <hr>
    </div>
</template>

<script>
export default {
    props: ['message']
}
</script>