<template>
    <jet-form-section @submitted="updateProduct">
        <template #title>
            Изменить информацию о продукте
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Название"/>
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                <jet-input-error :message="form.error('name')" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="unit" value="Единица измерения"/>
                <jet-input id="unit" type="text" class="mt-1 block w-full" v-model="form.unit"/>
                <jet-input-error :message="form.error('unit')" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="product_type_id	" value="Тип продукта"/>
                <div class="relative">
                    <select name="product_type_id"
                            class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                            id="product_type_id	" v-model="form.product_type_id">
                        <option v-for="(type, index) in typesList" :key="type" :value="index">{{ type }}</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                        </svg>
                    </div>
                    <jet-input-error :message="form.error('product_type_id')" class="mt-2"/>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <div class="col-span-6 sm:col-span-4">
                    <input type="file" class="hidden"
                           ref="image"
                           @change="updateImagePreview">
                    <jet-label for="image" value="Изображение"/>
                    <div class="mt-2" v-show="! imagePreview">
                        <img :src="product.image_url" alt="Current Profile Photo" class="h-20 w-20 object-cover">
                    </div>
                    <div class="mt-2" v-show="imagePreview">
                    <span class="block w-20 h-20"
                          :style="'background-size: cover; background-repeat: no-repeat; background-position: center center; background-image: url(\'' + imagePreview + '\');'">
                    </span>
                    </div>

                    <jet-secondary-button class="mt-2 mr-2" type="button" @click.native.prevent="selectNewImage">
                        Выберите файл
                    </jet-secondary-button>
                    <jet-input-error :message="form.error('image')" class="mt-2"/>
                </div>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Сохранено.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25 ': form.processing }" :disabled="form.processing">
                Сохранить
            </jet-button>
        </template>
    </jet-form-section>
</template>

<script>
import JetButton from './../../../Jetstream/Button'
import JetFormSection from './../../../Jetstream/FormSection'
import JetInput from './../../../Jetstream/Input'
import JetInputError from './../../../Jetstream/InputError'
import JetLabel from './../../../Jetstream/Label'
import JetActionMessage from './../../../Jetstream/ActionMessage'
import JetSecondaryButton from './../../../Jetstream/SecondaryButton'

export default {
    components: {
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
    },
    props: ['typesList', 'product'],
    data() {
        return {
            form: this.$inertia.form({
                _method: "PUT",
                product_id: this.product.id,
                name: this.product.name,
                image: this.product.image_url,
                unit: this.product.unit,
                product_type_id: this.product.product_type_id,
            }, {
                bag: 'updateProduct',
                resetOnSuccess: false,
            }),
            imagePreview: null,
        }
    },
    methods: {
        updateProduct() {
            if (this.$refs.image) {
                this.form.image = this.$refs.image.files[0]
            }
            this.form.post(route('products.update', this.product), {
                preserveScroll: true
            })
        },
        selectNewImage() {
            this.$refs.image.click();
        },

        updateImagePreview() {
            const reader = new FileReader();

            reader.onload = (e) => {
                this.imagePreview = e.target.result;
            };

            reader.readAsDataURL(this.$refs.image.files[0]);
        },

    },
}
</script>
