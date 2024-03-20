<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import "primevue/dropdown/style";
import Dropdown from 'primevue/dropdown';

import PrimaryButton from "@/Components/PrimaryButton.vue";
import {nextTick, ref, watch} from "vue";

const props = defineProps({
    forma_pagamento: Array,
})
const form = useForm({
    cliente: null,
    parcelas: [],
    total: 0,
    produtos: []
})
const submit = () => {
    form.transform(d => ({
        ...d,
        cliente_id: d.cliente.id,
        cliente: undefined
    }))
}
const clientesOptions = ref([]);
const produtosOptions = ref([]);
const selectedProdutos = ref([])
const currentProduto = ref(null);
const searchProdutosProcessing = ref(false);
const searchClientesProcessing = ref(false);
const searchProdutosText = text => {
    searchProdutosProcessing.value = true;
    axios.get(route('produtos.find', {
        'not': selectedProdutos.value.length ? selectedProdutos.value.map(item => item.id).join(',') : undefined,
        'search': text.target.value
    }))
        .then(d => produtosOptions.value = d.data)
        .finally(()=> searchProdutosProcessing.value = false)
}
const searchClientes = text => {
    searchClientesProcessing.value = true
    axios.get(route('clientes.find', {
        'not': clientesOptions.value.length ? clientesOptions.value.map(item => item.id).join(',') : undefined,
        'search': text.target.value
    }))
        .then(d => clientesOptions.value = d.data)
        .finally(() => searchClientesProcessing.value = false)
}
const beforeDropdownProductsHide = () =>{
    console.log('chanmou')
    nextTick(() =>{
        if(currentProduto.value && !selectedProdutos.value.some(item => item.id === currentProduto.value.id)){
            selectedProdutos.value.push(currentProduto.value)
            currentProduto.value = null
        }
    })
}
</script>

<template>
    <Head title="Cadastrar Cliente"/>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cadastrar Vendas</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-4 py-2">
                        <div class="mb-4 font-medium text-xl">
                            Preencha os dados corretamente
                        </div>
                        <form @submit.prevent="submit">
                            <div class="w-full mb-4">
                                <h2 class="font-medium text-lg">Cliente</h2>
                                <div class="flex flex-col md:flex-row gap-2">
                                    <Dropdown editable
                                              :loading="searchClientesProcessing"
                                              class="w-1/2"
                                              selection-message="selecione"
                                              empty-message="Sem clientes"
                                              @input="searchClientes"

                                              v-model="form.cliente"
                                              option-label="nome"
                                              :options="clientesOptions"/>
                                    <div v-if="form.cliente"
                                         class="w-1/2 flex flex-col justify-center">
                                        <span>
                                           Nome: {{ form.cliente.nome }}
                                        </span>
                                        <span>
                                            CPF: {{ form.cliente.cpf }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full flex-col gap-2">
                                <Dropdown
                                    class="w-full"
                                    :options="produtosOptions"
                                    @input="searchProdutosText"
                                    editable
                                    @beforeHide="beforeDropdownProductsHide"
                                    v-model="currentProduto"
                                    :loading="searchProdutosProcessing"
                                    option-label="nome"

                                />
                                <div v-for="produto of selectedProdutos">
                                    <span>{{ produto.nome }}</span>
                                    <span>{{ produto.preco_base }}</span>
                                </div>
                            </div>
                            <div class="flex justify-end">
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
