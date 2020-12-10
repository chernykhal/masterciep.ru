<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Продукты
            </h2>
        </template>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center my-5">Подтверждение</h2>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3">
            <span class="text-sm text-gray-400 mx-9" v-if="$page.type">Выбрано: {{ $page.type }}
                <inertia-link :href="route('products.index')" title="Сбросить фильтр">
                    <svg class="w-3 h-3 inline" viewBox="0 0 8 7" fill="none" xmlns="http://www.w3.org/2000/svg">
<line x1="7.35856" y1="0.353553" x2="1.35351" y2="6.3586" stroke="#10B42B"/>
<line x1="7.2275" y1="6.38908" x2="1.22245" y2="0.384028" stroke="#10B42B"/>
</svg>
                    </inertia-link>


            </span>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-3">
            <jet-products-list :products-list="$page.productsList" :selected-products="$page.selectedProducts"/>
        </div>
    </app-layout>
</template>

<script>
import JetButton from './../../../Jetstream/Button'
import JetInput from './../../../Jetstream/Input'
import JetLabel from './../../../Jetstream/Label'
import JetActionMessage from './../../../Jetstream/ActionMessage'
import JetSecondaryButton from './../../../Jetstream/SecondaryButton'
import JetSearchSection from './../../../Jetstream/SearchSection'
import JetProductsList from "./ProductsList";
import AppLayout from "./../../../Layouts/AppLayout";
import ResponsiveNavLink from "./../../../Jetstream/ResponsiveNavLink";
import JetDialogModal from "./../../../Jetstream/DialogModal";
import JetDangerButton from "./../../../Jetstream/DangerButton";
import JetInputError from "./../../../Jetstream/InputError";

export default {
    components: {
        ResponsiveNavLink,
        JetDialogModal,
        JetSearchSection,
        AppLayout,
        JetActionMessage,
        JetInputError,
        JetDangerButton,
        JetButton,
        JetInput,
        JetLabel,
        JetSecondaryButton,
        JetProductsList,
    },
    props:{
        productsList:Array,
        selectedProducts:Array,
    },
    data() {
        return {
            type: this.type,
        };
    },
}
</script>
