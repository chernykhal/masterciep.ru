<template>
    <div>
        <div class="md:col-span-2 px-9">
            <div class="shadow overflow-hidden sm:rounded-md">
                <div class="bg-white">
                    <div class="bg-white">
                        <div class="table w-full">
                            <div class="table-row-group">
                                <div v-for="product in products" :key="product.id" class="table-row mr-3">
                                    <div class="table-cell pb-0 pr-5" title="Дата контракта">
                                        <img :src="product.image_url" alt="Current Profile Photo" class="h-20 w-20 object-cover">
                                    </div>
                                    <div class="table-cell pb-0 align-middle" title="Дата контракта">
                                        {{product.name}}
                                    </div>
                                    <div class="table-cell text-right align-middle">
                                        <inertia-link :href="route('products.edit', product)" title="Редактировать"
                                                      class="inline-flex bg-white tracking-widest focus:border-blue-300">
                                            <svg class="w-5 h-5" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.3536 4.35355C13.5488 4.15829 13.5488 3.84171 13.3536 3.64645L10.1716 0.464466C9.97631 0.269204 9.65973 0.269204 9.46447 0.464466C9.2692 0.659728 9.2692 0.976311 9.46447 1.17157L12.2929 4L9.46447 6.82843C9.2692 7.02369 9.2692 7.34027 9.46447 7.53553C9.65973 7.7308 9.97631 7.7308 10.1716 7.53553L13.3536 4.35355ZM0 4.5H13V3.5H0V4.5Z" fill="#10B42B"/>
                                            </svg>
                                        </inertia-link>
                                    </div>
                                </div>
                                <!--                                <jet-pagination :links="contracts.links"></jet-pagination>-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import JetButton from './../../../Jetstream/Button'
import JetFormSection from './../../../Jetstream/FormSection'
import JetInput from './../../../Jetstream/Input'
import JetInputError from './../../../Jetstream/InputError'
import JetLabel from './../../../Jetstream/Label'
import JetActionMessage from './../../../Jetstream/ActionMessage'
import JetSecondaryButton from './../../../Jetstream/SecondaryButton'
import JetSearchSection from "./../../../Jetstream/SearchSection";
import JetDialogModal from "./../../../Jetstream/DialogModal";
import JetDangerButton from "./../../../Jetstream/DangerButton";
// import JetPagination from "../../Jetstream/Pagination";

export default {
    components: {
        JetSearchSection,
        JetDialogModal,
        JetDangerButton,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        // JetPagination
    },
    props: ['products'],
}
</script>
