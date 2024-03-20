<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {router, useForm} from "@inertiajs/vue3";
import {Link} from '@inertiajs/vue3';
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {computed} from "vue";

const props = defineProps({
    produtos: Object
})

const handleDelete = (id) => {
    window.confirm('Tem certeza que deseja apagar este produto?') && router.delete(route('produtos.destroy', {'produto': id}))
}
</script>

<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Produtos</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-2 py-4">
                        <div class="relative overflow-x-auto">
                            <PrimaryButton @click.prevent="router.get(route('produtos.create'))" class="mb-2 float-end">
                                Cadastrar
                            </PrimaryButton>

                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 ">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        código
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Nome
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        preço base
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        Ações

                                    </th>
                                </tr>
                                </thead>
                                <tbody>

                                <tr v-for="cliente of produtos.data" class="bg-white border-b ">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-700 whitespace-nowrap">
                                        {{ cliente.id }}
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ cliente.nome }}
                                    </td>
                                    <td class="px-6 py-4">
                                        R$ {{ cliente.preco_base }}
                                    </td>
                                    <td class="px-6 py-4 flex ">
                                        <a
                                            class="text-green-800 inline-block px-1 mx-1 hover:text-green-900"
                                            @click.prevent="router.get(route('produtos.edit', { 'produto': cliente.id  }))"
                                        >
                                            Editar
                                        </a>
                                        <a
                                            @click.prevent="handleDelete(cliente.id)"
                                            class="text-red-500 inline-block px-1 mx-1 hover:text-red-700"

                                            href="#"
                                        >
                                            Deletar
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                            <nav aria-label="Page navigation" class="mt-4">
                                <ul class="list-style-none flex">
                                    <li v-for="link of produtos.links">
                                        <Link
                                            :disabled="link.url"
                                            class="relative block rounded active:bg-neutral-100 bg-transparent px-3 py-1.5 text-sm text-surface transition duration-300 hover:bg-neutral-100 focus:bg-neutral-100 focus:text-primary-700 focus:outline-none focus:ring-0 active:text-primary-700"
                                            :aria-current="link.active"
                                            :href="link.url"
                                        >{{ link.label.replaceAll('&laquo; Anterior', 'Anterior').replaceAll('&raquo;', '')  }}</Link>
                                    </li>
                                </ul>
                            </nav>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>

</style>
