<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {f} from "../../../../public/build/assets/app-C5kg9Pp-.js";

const form = useForm({
    preco_base: '',
    nome: '',
    descricao: null
})
const submit = () =>{
    form.post(route('produtos.store'), {
        onError: console.log
    })
}


</script>

<template>
    <Head title="Cadastrar Cliente"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cadastrar Produtos</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-2 py-4">
                        <div class="mb-4 font-medium text-md">
                            Tela de cadastro de produtos
                        </div>
                        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="w-full">
                                <InputLabel value="Nome do produto"/>
                                <TextInput type="text"
                                       v-model="form.nome"
                                       min="11"
                                       max="11"
                                       class="w-full" />
                                <InputError  v-if="form.errors.nome" :message="form.errors.nome"/>
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

                            <div  class="flex justify-end " style="grid-column: span 2;">
                                <PrimaryButton>Cadastrar</PrimaryButton>
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
