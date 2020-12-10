<template>
    <form @submit.prevent="addProducts" autocomplete="off" class="md:px-3">
        <div class="relative md:flex md:flex-row md:justify-between md:flex-wrap">
            <div class="relative flex flex-row justify-between md:w-1/2 w-full md:px-3"
                 v-for="(selectedProduct, index) in form.selectedProducts">
                <div class="list-item list-item__margins flex flex-row justify-between ">
                    <div class="relative flex-3">
                        <select name="id[]"
                                class="block appearance-none bg-white text-gray-700 w-full py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                v-model="selectedProduct.id">
                            <option v-for="product in productsList" :key="product.id" :value="product.id"
                                    :selected="selectedProduct.id">
                                {{ product.name }}, {{ product.unit }}
                            </option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20">
                                <path
                                    d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                        <jet-input-error :message="form.error('id')" class="mt-2"/>
                    </div>
                    <div class="w-1/6 ml-4">
                        <jet-input type="number"
                                   class="block w-full border-r-0 border-t-0 border-l-0 border-r-0 unit_input text-center"
                                   v-model="selectedProduct.unit_value"/>
                        <jet-input-error :message="form.error('unit_value')" class="mt-2"/>
                    </div>
                    <div class="w-1/6 self-center text-right mr-4 flex justify-end">
                        <button @click="removeSelectedProduct(selectedProduct)" type="button"
                                class="focus:outline-none self-center">
                            <svg class="w-5 h-5" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="9.5" cy="9.5" r="9" fill="white" stroke="#FF0000"/>
                                <line x1="14" y1="9.5" x2="5" y2="9.5" stroke="#FF0000"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <button @click="addProduct" type="button"
                class="text-center py-3 w-full list-item list-item__margins flex flex-row justify-center focus:outline-none md:px-3">
            <svg class="w-5 h-5" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="9.5" cy="9.5" r="9" fill="white" stroke="#10B42B"/>
                <line x1="9.5" y1="5" x2="9.5" y2="14" stroke="#10B42B"/>
                <line x1="14" y1="9.5" x2="5" y2="9.5" stroke="#10B42B"/>
            </svg>
        </button>
        <jet-input-error :message="form.error('selectedProducts')" class="mt-2"/>
        <div class="flex items-center justify-end px-4 py-3 text-right sm:px-6">
            <jet-secondary-button
                @click.native="cancel">
                Отмена
            </jet-secondary-button>
            <jet-danger-button class="ml-2 btn-primary" @click.native="addProducts">
                Подтвердить
            </jet-danger-button>
        </div>
    </form>
</template>

<script>
import JetButton from './../../../Jetstream/Button'
import JetFormSection from './../../../Jetstream/FormSection'
import JetInput from './../../../Jetstream/Input'
import JetText from './../../../Jetstream/Text'
import JetInputError from './../../../Jetstream/InputError'
import JetLabel from './../../../Jetstream/Label'
import JetActionMessage from './../../../Jetstream/ActionMessage'
import JetSecondaryButton from './../../../Jetstream/SecondaryButton'
import JetDangerButton from "./../../../Jetstream/DangerButton";


export default {
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetText,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        JetDangerButton,

    },
    data() {
        return {
            form: this.$inertia.form({
                _method: "POST",
                selectedProducts: this.selectedProducts,
            }, {
                bag: 'addProducts',
                resetOnSuccess: false,
            }),
        }
    },
    props: ['productsList', 'selectedProducts'],
    methods: {
        addProducts() {
            this.form.post(route('products.scan.addProductsFromQr'), {
                preserveScroll: true
            })
        },
        cancel() {
            this.$inertia.get(route('products.scan'), {
                preserveScroll: true
            })
        },
        addProduct: function () {
            this.form.selectedProducts.push({id: ''});
        },
        removeSelectedProduct: function (selectedProduct) {
            const index = this.form.selectedProducts.indexOf(selectedProduct);
            if (index > -1) {
                this.form.selectedProducts.splice(index, 1);
            }
        },
    },
}
</script>
