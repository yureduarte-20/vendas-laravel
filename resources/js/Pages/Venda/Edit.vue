<script setup>

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";

import 'primevue/resources/themes/lara-light-green/theme.css';
import Dropdown from 'primevue/dropdown';

import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed, nextTick, onMounted, reactive, ref, toRef, watch, onUpdated} from "vue";

import Modal from "@/Components/Modal.vue";
import {addMonths} from "date-fns";
import InputError from "@/Components/InputError.vue";

const props = defineProps({
    forma_pagamento: Array,
    id: Number,
    cliente: Object,
    produtos: Array,
    parcelas: Array,
    total: Number
})
const form = useForm({
    cliente: toRef(() => props.cliente),
    parcelas: props.parcelas.map(item => reactive({...item, vencimento: item.vencimento.split('T')[0]})),
    total: props.total,
    produtos: props.produtos.map(item => item),
})
onMounted(() => {
    clientesOptions.value = [props.cliente]
})
const total = computed(() =>{
    let sum = 0;
    for (let i = 0; i < form.produtos.length; i++) {
        sum += form.produtos[i].preco_base * (form.produtos[i].qtde ?? 1)
    }
    return sum;
})
const submit = () => {
    console.log(total, form)
    form
        .transform(d => ({
            cliente: d.cliente.id,
            produtos: d.produtos.map(item => ({
                item: item.id,
                valor: item.preco_base,
                qtde: item.qtde,
            })),
            parcelas: d.parcelas.map(item => ({
                valor: item.valor,
                meio_pagamento: item.meio_pagamento?.id ?? item.meio_pagamento,
                vencimento: item.vencimento
            })),
            total: total.value
        }))
        .patch(route('vendas.update', {'venda': props.id}))
}
const clientesOptions = ref([]);
const produtosOptions = ref([]);
const showModal = ref(false)
const currentProduto = ref(null);
const searchProdutosProcessing = ref(false);
const searchClientesProcessing = ref(false);

const removeProduto = id => {
    form.produtos = form.produtos.filter(item => item.id !== id)
}
const removerParcela = index => {
    form.parcelas = form.parcelas.filter((item, _index) => _index != index)
}

const valor_restante = computed(() => {
    let diff = 0;
    if (form.parcelas.length > 1) {
        const res = form.parcelas.reduce( (p, c) => {
            return p + parseFloat(c.valor ?? c.preco_base)
        }, 0)
        diff = Number.isNaN(res) ? 0 : parseFloat((res).toFixed(2))
    } else if (form.parcelas.length == 1) {
        const res = form.parcelas[0].valor
        diff = Number.isNaN(res) ? 0 : parseFloat((res).toFixed(2))
    }
    return parseFloat((total.value - diff).toFixed(2))
})
const parcela = reactive({vencimento: new Date(), valor: 0, meio_pagamento: {id: 0}})
const appendParcela = () => {

    if (parcela.valor > form.total) return alert('Valor da parcela não pode ser maior que o valor de compra')
    if (parcela.valor > valor_restante.value) return alert('Valor da parcela não pode ser maior que o valor restante')
    form.parcelas.push({...parcela});
    parcela.valor = 0;
    parcela.vencimento = 0;
    parcela.meio_pagamento = {id: 0};
}
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
                    form.total = form.produtos.reduce((curr, nex) => (curr.qtde * curr.preco_base) + (nex.qtde * nex.preco_base))
                else if (form.produtos.length == 1)
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
                    <form class="px-4 py-2">
                        <div class="mb-4 font-medium text-xl">
                            Preencha os dados corretamente
                        </div>
                        <form @submit.prevent="submit">
                            <div class="w-full mb-6">
                                <h2 class="font-medium text-lg">Cliente</h2>
                                <div class="flex flex-col md:flex-row gap-2">
                                    <div class="w-1/2">
                                        <Dropdown editable
                                                  :loading="searchClientesProcessing"
                                                  class=" w-full rounded border"
                                                  selection-message="selecione"
                                                  empty-message="Sem clientes"
                                                  @input="searchClientes"
                                                  v-model="form.cliente"
                                                  required
                                                  option-label="nome"
                                                  :options="clientesOptions"/>
                                        <InputError :message="form.errors.cliente"/>
                                    </div>
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
                                                <TextInput
                                                    v-model="produto.qtde"
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
                                    <InputError :message="form.errors.produtos"/>
                                    <InputError :message="form.errors.total"/>
                                    <span class="font-medium">Total: R$ {{ total }}</span>
                                </ul>
                            </div>
                            <div>
                                <Modal :closeable="true"
                                       @close="showModal=  false"
                                       :show="showModal">
                                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg px-4 py-2">
                                            <div class="mb-5">
                                                <h2>Definir as parcelas e condidções de pagamento</h2>
                                                <span>Valor restante: R$ {{ valor_restante }}</span>
                                                <InputError v-if="valor_restante < 0"
                                                            message="Somatória das parcelas maior que o total do pedido"/>
                                            </div>
                                            <form @submit.prevent="appendParcela">
                                                <div class="flex flex-row gap-4">
                                                    <div class="w-1/4">
                                                        <InputLabel value="Valor da Parcela"/>
                                                        <TextInput
                                                            class=" w-full"
                                                            v-model="parcela.valor"
                                                            min="0.01"
                                                            required
                                                            step="0.01"
                                                            type="number"/>
                                                    </div>
                                                    <div class="w-1/2 px-2">
                                                        <InputLabel value="Forma de Pagamento"/>
                                                        <Dropdown
                                                            class="w-full border-gray-400 border-2"
                                                            required
                                                            :options="forma_pagamento"
                                                            option-label="nome"
                                                            v-model="parcela.meio_pagamento"
                                                        />
                                                    </div>
                                                    <div class="w-1/4">
                                                        <InputLabel value="Vencimento"/>
                                                        <TextInput required
                                                                   class=" w-full"
                                                                   v-model="parcela.vencimento"
                                                                   type="date"></TextInput>
                                                    </div>
                                                </div>
                                                <PrimaryButton
                                                    :disabled="valor_restante <= 0"
                                                    class="mb-5"
                                                    type="submit"
                                                >
                                                    Acrescentar
                                                </PrimaryButton>
                                            </form>
                                            <ul>
                                                <span class="text-lg">Parcelas</span>
                                                <li v-for="(parcela, index) of form.parcelas" :key="index"
                                                    class="w-full flex flex-row mb-2">
                                                    <TextInput
                                                        v-model="parcela.valor"
                                                        min="0.01"
                                                        step="0.01"
                                                        class="w-1/4"
                                                        :max="valor_restante + parcela.valor"
                                                        type="number"/>
                                                    <Dropdown
                                                        :options="forma_pagamento"
                                                        option-label="nome"
                                                        class="w-1/2"
                                                        v-model="parcela.meio_pagamento"
                                                    />
                                                    <TextInput v-model="parcela.vencimento" type="date"></TextInput>
                                                    <div class="w-1/6 flex items-center">
                                                        <a

                                                            @click.prevent="removerParcela(index)"
                                                            class=" w-full text-red-500 inline-block px-1 mx-1 hover:text-red-700"
                                                            href="#"
                                                        >
                                                            Remover
                                                        </a>
                                                    </div>
                                                </li>
                                            </ul>
                                            <InputError :message="form.errors.parcelas"/>
                                            <PrimaryButton
                                                type="button"
                                                @click.prevent="submit"
                                                :disabled="valor_restante > 0 || form.parcelas.length <= 0 || valor_restante < 0">
                                                Finalizar venda
                                            </PrimaryButton>
                                        </div>
                                    </div>
                                </Modal>
                            </div>
                            <div class="flex justify-end">
                                <PrimaryButton @click.prevent="showModal = !showModal">Pagamento</PrimaryButton>
                            </div>
                        </form>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@import "primevue/resources/themes/aura-light-green/theme.css";
</style>
