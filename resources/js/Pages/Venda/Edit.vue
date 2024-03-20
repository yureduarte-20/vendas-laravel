<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    id: Number,
    cpf:String,
    nome: String
})
const form = useForm({
    cpf: props.cpf,
    nome: props.nome
})
const submit = () =>{
    form.put(route('clientes.update', { 'cliente': props.id }), {
        onError: console.log
    })
}


</script>

<template>
    <Head title="Editar Cliente"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Cliente</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-2 py-4">
                        <div class="mb-4 font-medium text-md">
                            Tela de cadastro de clientes
                        </div>
                        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="w-full">
                                <InputLabel value="CPF"/>
                                <TextInput type="text"
                                       v-model="form.cpf"
                                       min="11"
                                       max="11"
                                       class="w-full" />
                                <InputError  v-if="form.errors.cpf" :message="form.errors.cpf"/>
                            </div>
                            <div class="w-full">
                                <InputLabel value="Nome"/>
                                <TextInput class="w-full" v-model="form.nome"/>
                                <InputError v-if="form.errors.nome" :message="form.errors.nome"/>
                            </div>
                            <div></div>
                            <div class="flex justify-end">
                                <PrimaryButton>Salvar</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
