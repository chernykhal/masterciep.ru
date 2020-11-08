<template>
    <div class="flex flex-wrap mx-9">
        <div v-for="product in products" :key="product.id" class="w-full md:px-3 md:w-1/2 xl:w-1/3 ">
            <div class="list-item list-item__margins flex flex-row justify-between">
                <img :src="product.image_url" :alt="product.name"
                     class="h-10 w-10 md:h-20 md:w-20 object-cover self-center">
                <div class="self-center">
                    {{ product.name }}

                </div>

                <button @click="openModal(product)" title="Добавить"
                        class="self-center inline-flex bg-white tracking-widest focus:outline-none focus:border-blue-300 pr-3">
                    <svg class="w-6 h-6" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="9.5" cy="9.5" r="9" fill="white" stroke="#10B42B"/>
                        <line x1="9.5" y1="5" x2="9.5" y2="14" stroke="#10B42B"/>
                        <line x1="14" y1="9.5" x2="5" y2="9.5" stroke="#10B42B"/>
                    </svg>
                </button>
                <jet-dialog-modal :show="opened===product.id"
                                  @close="opened = false">
                    <template #title>
                        Подтверждение
                    </template>

                    <template #content>
                        Введите количество продукта {{ product.name }}

                        <div class="mt-4">
                            <jet-input type="number" class="mt-1 block w-full" :placeholder="product.unit"
                                       @keyup.enter.native="editProduct(product)" v-model="form.unit_value"/>

                            <jet-input-error :message="form.error('unit_value')"
                                             class="mt-2"/>
                        </div>
                    </template>

                    <template #footer>
                        <jet-secondary-button
                            @click.native="closeModal">
                            Отмена
                        </jet-secondary-button>

                        <jet-danger-button class="ml-2 btn-primary" @click.native="editProduct(product)"
                                           :disabled="form.processing">
                            Подтвердить
                        </jet-danger-button>
                    </template>
                </jet-dialog-modal>
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
    data() {
        return {
            opened: false,
            form: this.$inertia.form({
                '_method': 'POST',
                unit_value: '',
            }, {
                bag: 'addProduct'
            }),
        }
    },
    methods: {
        openModal(product) {
            this.form.unit_value = '';
            this.opened = product.id;
        },
        closeModal() {
            this.opened = false;
        },
        editProduct(product) {
          this.form.post(route('products.add',product), {
              preserveScroll: true
          })
        },
    },
}
</script>
