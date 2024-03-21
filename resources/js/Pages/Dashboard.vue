<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router, useForm} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";

const click = () => axios.get(route('generate.pdf'),
    {responseType: 'blob'})
    .then(res => {
        let blob = new Blob([res.data], {type: res.headers['content-type']});
        let link = document.createElement('a');
        link.href = window.URL.createObjectURL(blob);
        link.setAttribute('target', '__blank')
        link.click()
    }).catch(err => {
    })
</script>

<template>
    <Head title="Dashboard"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">Você está logado</div>
                    <form @submit.prevent="()=>{}" ref="form">
                        <PrimaryButton
                            type="submit"
                            @click="click"
                        > Emitir relatório de vendas
                        </PrimaryButton>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
