<template>
    <transition name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
                <div class="modal-container">

                    <div class="fas fa-close close" @click.self="$emit('close')"></div>

                    <br>
                    <vue-dropzone ref="myVueDropzone" id="dropzone" :options="dropzoneOptions" @vdropzone-sending="onSending" @vdropzone-queue-complete="completed" />

                </div>
            </div>
        </div>
    </transition>
</template>


<style scoped>
    .modal-container{
        width: 800px;
        height: 280px;
    }
</style>

<script>
import vueDropzone from 'vue2-dropzone-vue3'

export default {
    props: ['prefix', 'path'],
    components: {
        vueDropzone,
    },
    data () {
        return {
            dropzoneOptions: {
                url: this.prefix + '/upload',
                thumbnailWidth: 140,
                thumbnailHeight: 90,
                thumbnailMethod: 'contain',
                maxFilesize: 20,
                headers: { "My-Awesome-Header": "header value" }
            }
        }
    },
    methods:{
        onSending(file, xhr, formData) {
            formData.append('path', this.path);
        },
        completed(){
            // this.$emit('close');
            this.$emit('uploaded');
        }
    }
}
</script>
