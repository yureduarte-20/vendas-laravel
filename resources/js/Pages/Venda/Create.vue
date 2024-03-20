<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import 'primevue/resources/themes/lara-light-green/theme.css';
import Dropdown from 'primevue/dropdown';
import Divider from 'primevue/divider';
import TabView from 'primevue/tabview/TabView.vue';
import TabPanel from 'primevue/tabpanel/TabPanel.vue';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {defineComponent, nextTick, reactive, ref, watch} from "vue";
import Lara from '@/preset/lara.js'
import Modal from "@/Components/Modal.vue";

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
const showModal = ref(false)
const currentProduto = ref(null);
const searchProdutosProcessing = ref(false);
const searchClientesProcessing = ref(false);
const qtdeParcelas = ref(1)
const parcelas = ref([])
const removeProduto = id => {
    form.produtos = form.produtos.filter(item => item.id !== id)
}
watch(qtdeParcelas, (qtde, old) => {
    if (qtde == old) return
    const valor_por_parcela = form.total / qtde
    const _parcelas = new Array(qtde).fill(Object.assign({}, {
        valor: valor_por_parcela,
        meio_pagamento: 0
    })).map(item => ({...item, id: Math.floor(1000 * Math.random())}))
    parcelas.value = _parcelas
})
watch(form, (newV, old) => {
    if (form.produtos.length > 1)
        form.total = form.produtos.reduce((curr, nex) => (curr.qtde * curr.preco_base) + (curr.qtde * nex.preco_base))
    else if (form.produtos.length == 1)
        form.total = form.produtos[0].preco_base * (form.produtos[0].qtde ?? 1)
})
watch(parcelas, (newV, old) => {
    if (newV.length > 1) {
        let sum = 0;

        const lastIndex = newV.length - 1

        for (let i = 0; i < lastIndex - 1; i++) {
            sum += newV[i].valor;
        }
        if (sum - newV[lastIndex].valor <= 0) {
            alert('As parcelas não podem ter valor zero')
            parcelas.value = old
            return
        }
        const ultima = Object.assign({}, {valor: sum - newV[lastIndex].valor, ...newV[lastIndex]})
        newV[lastIndex] = ultima;
    }
})
const searchProdutosText = text => {
    searchProdutosProcessing.value = true;
    axios.get(route('produtos.find', {
        'not': (form.produtos && form.produtos.length) ? form.produtos.map(item => item.id).join(',') : undefined,
        'search': text.target.value
    }))
        .then(d => produtosOptions.value = d.data)
        .finally(() => searchProdutosProcessing.value = false)
}
const searchClientes = text => {
    searchClientesProcessing.value = true
    axios.get(route('clientes.find', {
        'not': form.cliente ? form.cliente.id : undefined,
        'search': text.target.value
    }))
        .then(d => clientesOptions.value = d.data)
        .finally(() => searchClientesProcessing.value = false)
}
const beforeDropdownProductsHide = () => {

    nextTick(() => {
        if (currentProduto.value && typeof currentProduto.value === 'object' && !form.produtos.some(item => item.id === currentProduto.value.id)) {
            currentProduto.value.qtde = 1
            form.produtos.push(currentProduto.value)
            currentProduto.value = null
            nextTick(() => {
                if (form.produtos.length > 1)
                    form.total = form.produtos.reduce((curr, nex) => (curr.qtde * curr.preco_base) + (curr.qtde * nex.preco_base))
                else
                    form.total = form.produtos[0].preco_base * (form.produtos[0].qtde ?? 1)
            })
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
                            <div class="w-full mb-6">
                                <h2 class="font-medium text-lg">Cliente</h2>
                                <div class="flex flex-col md:flex-row gap-2">
                                    <Dropdown editable
                                              :loading="searchClientesProcessing"
                                              class="w-1/2 rounded border"
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
                            <div class="w-full flex-col gap-2  mt-4">
                                <h3>Produtos</h3>
                                <Dropdown
                                    class="w-full rounded mb-5"
                                    :options="produtosOptions"
                                    @input="searchProdutosText"
                                    editable
                                    @beforeHide="beforeDropdownProductsHide"
                                    v-model="currentProduto"
                                    :loading="searchProdutosProcessing"
                                    option-label="nome"

                                />
                                <ul v-if="form.produtos.length > 0"
                                    class="w-full block list-style-none border rounded px-2">
                                    <template :key="produto.id" v-for="produto of form.produtos">
                                        <li class=" w-full flex flex-row items-center">
                                            <span class="text-md w-1/4">{{ produto.nome }}</span>
                                            <div class="w-1/4 ">
                                                <InputLabel value="Quantidade"/>
                                                <TextInput v-model="produto.qtde"
                                                           type="number"
                                                           default-value="1"
                                                           required
                                                           min="1"
                                                           step="1"/>
                                            </div>
                                            <div class="w-1/4">
                                                <InputLabel value="Valor"/>
                                                <TextInput v-model="produto.preco_base"
                                                           type="number"
                                                           required
                                                           min="1"
                                                           step="1"/>
                                            </div>
                                            <div class="w-1/4">
                                                <a
                                                    @click.prevent="removeProduto(produto.id)"
                                                    class="text-red-500 inline-block px-1 mx-1 hover:text-red-700"
                                                    href="#"
                                                >
                                                    Remover
                                                </a>
                                            </div>
                                        </li>
                                    </template>
                                    <span class="font-medium">Total: R$ {{ form.total }}</span>
                                </ul>
                            </div>
                            <div>
                                <Modal :closeable="true"
                                       @close="showModal=  false"
                                       :show="showModal">
                                    <div class="max-w-8xl mx-auto sm:px-6 lg:px-8">
                                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-4 py-2">
                                            <div class="w-full">
                                                <InputLabel value="Quantidade de Parcelas"/>
                                                <TextInput v-model="qtdeParcelas"
                                                           type="number"
                                                           required
                                                           min="1" step="1"/>
                                            </div>
                                            <ul>
                                                <li v-for="parcela of parcelas" :key="parcela.id"
                                                    class="w-full flex flex-row">
                                                    <TextInput v-model="parcela.valor"
                                                               type="number"/>
                                                    <Dropdown
                                                        :options="forma_pagamento"
                                                        option-label="nome"
                                                        v-model="parcela.meio_pagamento"
                                                    />

                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </Modal>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton @click.prevent="showModal = !showModal">Pagamento</PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import "primevue/resources/themes/aura-light-green/theme.css";
</style>
