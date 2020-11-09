<template>
    <jet-form-section @submitted="updateType">
        <template #title>
            Изменить информацию о категории продуктов
        </template>
        <template #description>
            Измените категорию продуктов в базе
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
                    <div class="mt-2" v-show="! imagePreview">
                        <img :src="type.image_url" alt="Current Profile Photo" class="h-20 w-20 object-cover">
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
    props: ['type'],
    data() {
        return {
            form: this.$inertia.form({
                _method: "PUT",
                type_id: this.type.id,
                name: this.type.name,
                image: this.type.image_url,
            }, {
                bag: 'updateType',
                resetOnSuccess: false,
            }),
            imagePreview: null,
        }
    },
    methods: {
        updateType() {
            if (this.$refs.image) {
                this.form.image = this.$refs.image.files[0]
            }
            this.form.post(route('types.update', this.type), {
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
