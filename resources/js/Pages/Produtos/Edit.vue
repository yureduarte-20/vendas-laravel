<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
    id: Number,
    nome:String,
    descricao: String,
    preco_base: Number
})
const form = useForm({
    nome:props.nome,
    descricao: props.descricao,
    preco_base: props.preco_base
})
const submit = () =>{
    form.put(route('produtos.update', { 'produto': props.id }), {
        onError: console.log
    })
}

</script>

<template>
    <Head title="Editar Cliente"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Produto</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-2 py-4">
                        <div class="mb-4 font-medium text-md">
                            Tela de cadastro edição de produtos
                        </div>
                        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div class="w-full">
                                <InputLabel value="Nome"/>
                                <TextInput class="w-full" v-model="form.nome"/>
                                <InputError v-if="form.errors.nome" :message="form.errors.nome"/>
                            </div>
                            <div class="w-full">
                                <InputLabel value="Preço Base"/>
                                <TextInput class="w-full " type="number" v-model="form.preco_base"/>
                                <InputError v-if="form.errors.preco_base" :message="form.errors.preco_base"/>
                            </div>
                            <div style="grid-column: span 2;">
                                <InputLabel value="Descrição"/>
                                <textarea class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full" v-model="form.descricao"></textarea>
                            </div>
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
