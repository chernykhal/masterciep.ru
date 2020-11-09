<template>
    <jet-form-section @submitted="createRecipe">
        <template #title>
            Добавить новый рецепт
        </template>
        <template #description>
            Добавьте новый рецепт в базу
        </template>

        <template #form>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="name" value="Название"/>
                <jet-input id="name" type="text" class="mt-1 block w-full" v-model="form.name"/>
                <jet-input-error :message="form.error('name')" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <div class="col-span-6 sm:col-span-4">
                    <input type="file" class="hidden"
                           ref="image"
                           @change="updateImagePreview">
                    <jet-label for="image" value="Изображение"/>
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
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="video_url" value="Ссылка на видео"/>
                <jet-input id="video_url" type="text" class="mt-1 block w-full" v-model="form.video_url"/>
                <jet-input-error :message="form.error('video_url')" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label for="process" value="Процесс"/>
                <jet-text id="process" type="text" class="mt-1 block w-full" rows="5" v-model="form.process"/>
                <jet-input-error :message="form.error('process')" class="mt-2"/>
            </div>
            <div class="col-span-6 sm:col-span-4">
                <jet-label class="text-center" value="Ингредиенты"/>
                <div class="relative flex flex-row justify-between" v-for="(ingredient, index) in form.ingredients">
                    <div class="list-item flex-auto flex flex-row justify-between">
                        <div class="relative flex-auto">
                            <select name="product_id[]"
                                    class="block appearance-none bg-white text-gray-700 w-full py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                                    v-model="ingredient.product_id">
                                <option v-for="product in productsList" :key="product.id" :value="product.id">{{
                                        product.name
                                    }}, {{ product.unit }}
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
                            <jet-input-error :message="form.error('product_id')" class="mt-2"/>
                        </div>
                        <div class="flex-1 ml-4">
                            <jet-input type="number"
                                       class="block w-full border-r-0 border-t-0 border-l-0 border-r-0 unit_input text-center"
                                       v-model="ingredient.unit_value"/>
                            <jet-input-error :message="form.error('unit_value')" class="mt-2"/>
                        </div>
                        <div class="flex-auto self-center text-right mr-4 flex justify-end">
                            <button @click="removeIngredient(ingredient)" type="button" class="focus:outline-none self-center">
                                <svg class="w-5 h-5" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="9.5" cy="9.5" r="9" fill="white" stroke="#FF0000"/>
                                    <line x1="14" y1="9.5" x2="5" y2="9.5" stroke="#FF0000"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                <button @click="addIngredient" type="button"
                        class="text-center py-3 w-full justify-center flex list-item focus:outline-none">
                    <svg class="w-5 h-5" viewBox="0 0 19 19" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="9.5" cy="9.5" r="9" fill="white" stroke="#10B42B"/>
                        <line x1="9.5" y1="5" x2="9.5" y2="14" stroke="#10B42B"/>
                        <line x1="14" y1="9.5" x2="5" y2="9.5" stroke="#10B42B"/>
                    </svg>
                </button>
                <jet-input-error :message="form.error('ingredients')" class="mt-2"/>
            </div>
        </template>

        <template #actions>
            <jet-action-message :on="form.recentlySuccessful" class="mr-3">
                Создано.
            </jet-action-message>

            <jet-button :class="{ 'opacity-25 ': form.processing }" :disabled="form.processing">
                Создать
            </jet-button>
        </template>
    </jet-form-section>
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
    },
    data() {
        return {
            form: this.$inertia.form({
                _method: "PUT",
                name: null,
                image: null,
                video_url: null,
                process: null,
                ingredients: [],
            }, {
                bag: 'storeRecipe',
                resetOnSuccess: false,
            }),
            imagePreview: null,
        }
    },
    props: ['productsList'],
    methods: {
        createRecipe() {
            if (this.$refs.image) {
                this.form.image = this.$refs.image.files[0]
            }
            this.form.post(route('recipes.store'), {
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
        addIngredient: function () {
            this.form.ingredients.push({product_id:'', unit_value: ''});
        },
        removeIngredient: function (ingredient) {
            const index = this.form.ingredients.indexOf(ingredient);
            if (index > -1) {
                this.form.ingredients.splice(index, 1);
            }
        },
    },
}
</script>
