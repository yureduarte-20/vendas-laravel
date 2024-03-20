<script setup>
import {onMounted} from "vue";

const props = defineProps({
    title: String,
    message: String,
    type: String,
    dismiss: Number
})
const emit = defineEmits(['click'])
onMounted(() => {
    setTimeout(() => emit('click'),props.dismiss ?? 5000)
})
</script>

<template>
    <div class="border px-4 py-3 rounded relative" :class="{
        'bg-red-100 border-red-400 text-red-700': type === 'danger' | type === 'error',
        'bg-green-100 border-green-400 text-green-700': type === 'success',
        'bg-blue-100 border-blue-400 text-blue-700': type === 'info',

    }" role="alert">
        <div class="mr-10">
            <strong class="font-bold">{{ title }}</strong>
            <span class="block sm:inline">{{ message }}</span>
        </div>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
    <svg class="fill-current h-6 w-6 " :class="{
        // text-red-500
        'text-red-500': type === 'danger' | type === 'error',
        'text-green-500': type === 'success',
        'text-blue-500': type === 'info',
    }" role="button" @click.prevent="$emit('click')" xmlns="http://www.w3.org/2000/svg"
         viewBox="0 0 20 20"><title>Close</title><path
        d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
  </span>
    </div>
</template>

<style scoped>

</style>
